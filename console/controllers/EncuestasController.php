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
        $huboError = false;
        foreach ($query->each($batchSize) as $reserva) {
            try {
                $cliente = $reserva->cliente->nombre_completo;
                $emailCliente = trim((string) $reserva->cliente->correo);
                if ($emailCliente === '') {
                    continue;
                }

                if (preg_match('/@parkingplus\\.es$/i', $emailCliente)) {
                    $reserva->evaluacion_enviada = 1;
                    $reserva->save(false);
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
                    ->setSubject('Evalúe su reserva de aparcamiento');

                if (!$correo->send()) {
                    Yii::warning(
                        sprintf(
                            'El proveedor rechazó el envío de la evaluación para la reserva %s.',
                            $reserva->id
                        ),
                        __METHOD__
                    );
                    $this->stderr(
                        "Se detuvo el proceso porque el proveedor de correo rechazó un envío. Intente más tarde.\n"
                    );
                    $huboError = true;
                    break;
                }

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
                $this->stderr(
                    "Se detuvo el proceso por un error al enviar una evaluación. Revise los logs para más detalles.\n"
                );
                $huboError = true;
                break;
            }
        }

        $this->stdout("Encuestas enviadas: {$enviadas}\n");

        if ($huboError) {
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }
}
