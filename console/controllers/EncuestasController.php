<?php

namespace console\controllers;

use common\models\Reservas;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;

/**
 * Console commands related to customer surveys.
 */
class EncuestasController extends Controller
{
    /**
     * Sends pending service evaluation surveys by email.
     *
     * This command should be executed by a cron job instead of triggering the
     * process from a web request. You may optionally limit the number of
     * processed reservations and define the batch size used when iterating
     * through the result set in order to control memory usage.
     *
     * @param int|null $limit     Maximum number of reservations to process.
     * @param int      $batchSize Amount of records fetched per batch.
     *
     * @return int Exit code
     */
    public function actionEnviarValoracion(int $limit = null, int $batchSize = 50): int
    {
        $query = Reservas::find()
            ->where(['estatus' => 2, 'evaluacion_enviada' => 0])
            ->andWhere(new Expression('TIMESTAMP(fecha_salida, hora_salida) <= NOW() - INTERVAL 2 DAY'))
            ->orderBy(['fecha_salida' => SORT_ASC, 'hora_salida' => SORT_ASC]);

        if ($limit !== null) {
            $query->limit($limit);
        }

        $frontendBaseUrl = rtrim((string) (Yii::$app->params['frontendBaseUrl'] ?? ''), '/');
        if ($frontendBaseUrl === '') {
            $this->stderr("El parámetro 'frontendBaseUrl' no está configurado.\n");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $enviadas = 0;
        foreach ($query->each($batchSize) as $reserva) {
            try {
                $cliente = $reserva->cliente->nombre_completo;
                $emailCliente = trim((string) $reserva->cliente->correo);
                if ($emailCliente === '') {
                    continue;
                }

                $urlEncuesta = sprintf(
                    '%s/site/encuesta1?reserva=%s',
                    $frontendBaseUrl,
                    urlencode($reserva->nro_reserva)
                );

                $correo = Yii::$app->mailer->compose(
                    [
                        'html' => 'evaluacionServicio-html',
                        'text' => 'evaluacionServicio-text',
                    ],
                    [
                        'cliente' => $cliente,
                        'nro_reserva' => $reserva->nro_reserva,
                        'correo' => $emailCliente,
                        'urlEncuesta' => $urlEncuesta,
                    ]
                );
                $correo->setTo($emailCliente)
                    ->setFrom([Yii::$app->params['contactEmail'] => Yii::$app->name])
                    ->setSubject('Evalúe su reserva de aparcamiento')
                    ->send();

                $reserva->evaluacion_enviada = 1;
                $reserva->save(false);
                ++$enviadas;
            } catch (\Throwable $exception) {
                Yii::error(
                    sprintf(
                        'Error enviando evaluación a reserva %s: %s',
                        $reserva->id,
                        $exception->getMessage()
                    ),
                    __METHOD__
                );
            }
        }

        $this->stdout("Encuestas enviadas: {$enviadas}\n");

        return ExitCode::OK;
    }
}
