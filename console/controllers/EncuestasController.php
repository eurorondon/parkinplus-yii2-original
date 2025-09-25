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
    private const MAX_EMAILS_PER_HOUR = 20;

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
    public function actionEnviarValoracion(?int $limit = null, int $batchSize = 50): int
    {
        $effectiveLimit = $this->resolveEmailLimit($limit);

        $query = Reservas::find()
            ->where(['estatus' => 2, 'evaluacion_enviada' => 0])
            ->andWhere(new Expression('TIMESTAMP(fecha_salida, hora_salida) <= NOW() - INTERVAL 2 DAY'))
            ->orderBy(['fecha_salida' => SORT_ASC, 'hora_salida' => SORT_ASC]);

        $totalPendientes = (clone $query)->count();
        if ($totalPendientes === 0) {
            $this->stdoutConTimestamp("No hay encuestas pendientes de envío.\n");
            return ExitCode::OK;
        }

        if ($limit === null) {
            $this->stdoutConTimestamp(
                sprintf(
                    "Se enviarán hasta %d correos en esta ejecución (máximo permitido por hora).\n",
                    $effectiveLimit
                )
            );
        } elseif ($limit > $effectiveLimit) {
            $this->stdoutConTimestamp(
                sprintf(
                    "El límite solicitado (%d) supera el máximo configurado (%d). Se enviarán %d correos.\n",
                    $limit,
                    $this->getMaxEmailsPerHour(),
                    $effectiveLimit
                )
            );
        }

        if ($totalPendientes > $effectiveLimit) {
            $this->stdoutConTimestamp(
                sprintf(
                    "Hay %d encuestas pendientes. Se procesarán las primeras %d y el resto quedará para próximas ejecuciones.\n",
                    $totalPendientes,
                    $effectiveLimit
                )
            );
        }

        $query->limit($effectiveLimit);

        $frontendBaseUrl = rtrim((string) (Yii::$app->params['frontendBaseUrl'] ?? ''), '/');
        if ($frontendBaseUrl === '') {
            $this->stderrConTimestamp("El parámetro 'frontendBaseUrl' no está configurado.\n");
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
                    $this->stderrConTimestamp(
                        "Se detuvo el proceso porque el proveedor de correo rechazó un envío. Intente más tarde.\n"
                    );
                    $huboError = true;
                    break;
                }

                $reserva->evaluacion_enviada = 1;
                $reserva->save(false);
                ++$enviadas;

                if ($enviadas >= $effectiveLimit) {
                    break;
                }
            } catch (\Throwable $exception) {
                if ($this->esErrorDeDestinatarioInvalido($exception)) {
                    Yii::warning(
                        sprintf(
                            'Se omitió la reserva %s por dirección de correo inválida (%s).',
                            $reserva->id,
                            $reserva->cliente->correo
                        ),
                        __METHOD__
                    );
                    $reserva->evaluacion_enviada = 2;
                    $reserva->save(false);
                    continue;
                }

                Yii::error(
                    sprintf(
                        'Error enviando evaluación a reserva %s: %s',
                        $reserva->id,
                        $exception->getMessage()
                    ),
                    __METHOD__
                );
                $this->stderrConTimestamp(
                    "Se detuvo el proceso por un error al enviar una evaluación. Revise los logs para más detalles.\n"
                );
                $huboError = true;
                break;
            }
        }

        $this->stdoutConTimestamp("Encuestas enviadas: {$enviadas}\n");

        if ($totalPendientes > $effectiveLimit && $enviadas >= $effectiveLimit) {
            $this->stdoutConTimestamp(
                "Se alcanzó el máximo de correos permitido para esta hora. Ejecute el cron nuevamente después del próximo ciclo para continuar con los envíos pendientes.\n"
            );
        }

        if ($huboError) {
            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }

    private function resolveEmailLimit(?int $requestedLimit): int
    {
        $maxPerHour = $this->getMaxEmailsPerHour();

        if ($requestedLimit === null) {
            return $maxPerHour;
        }

        if ($requestedLimit < 1) {
            return 1;
        }

        return min($requestedLimit, $maxPerHour);
    }

    private function getMaxEmailsPerHour(): int
    {
        $configured = (int) (Yii::$app->params['cronEmailLimitPerHour'] ?? self::MAX_EMAILS_PER_HOUR);
        if ($configured < 1) {
            return self::MAX_EMAILS_PER_HOUR;
        }

        return $configured;
    }

    private function esErrorDeDestinatarioInvalido(\Throwable $exception): bool
    {
        $mensaje = $exception->getMessage();
        $patrones = [
            'All RCPT commands were rejected',
            'Valid RCPT command must precede DATA',
            'Invalid recipient',
            'could not deliver mail to',
        ];

        foreach ($patrones as $patron) {
            if (stripos($mensaje, $patron) !== false) {
                return true;
            }
        }

        if ($exception instanceof \Swift_TransportException) {
            return true;
        }

        return false;
    }

    private function stdoutConTimestamp(string $message): void
    {
        $this->stdout($this->formatWithTimestamp($message));
    }

    private function stderrConTimestamp(string $message): void
    {
        $this->stderr($this->formatWithTimestamp($message));
    }

    private function formatWithTimestamp(string $message): string
    {
        $timestamp = date('Y-m-d H:i:s');
        $trimmedMessage = rtrim($message, "\r\n");

        if ($trimmedMessage === '') {
            return sprintf("%s\n", $timestamp);
        }

        $lines = preg_split('/\r\n|\r|\n/', $trimmedMessage);
        $prefixedLines = array_map(
            static function (string $line) use ($timestamp): string {
                return sprintf('%s %s', $timestamp, $line);
            },
            $lines
        );

        return implode(PHP_EOL, $prefixedLines) . PHP_EOL;
    }
}
