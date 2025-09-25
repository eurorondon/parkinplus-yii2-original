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
    private const MAX_EMAILS_PER_HOUR = 100;

    /**
     * Sends pending service evaluation surveys by email.
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

        $totalPendientes = (int) (clone $query)->count();
        if ($totalPendientes === 0) {
            $this->logInfo("No hay encuestas pendientes de envío.");
            return ExitCode::OK;
        }

        if ($limit === null) {
            $this->logInfo(sprintf(
                "Se enviarán hasta %d correos en esta ejecución (máximo permitido por hora).",
                $effectiveLimit
            ));
        } elseif ($limit > $effectiveLimit) {
            $this->logInfo(sprintf(
                "El límite solicitado (%d) supera el máximo configurado (%d). Se enviarán %d correos.",
                $limit,
                $this->getMaxEmailsPerHour(),
                $effectiveLimit
            ));
        }

        if ($totalPendientes > $effectiveLimit) {
            $this->logInfo(sprintf(
                "Hay %d encuestas pendientes. Se procesarán las primeras %d y el resto quedará para próximas ejecuciones.",
                $totalPendientes,
                $effectiveLimit
            ));
        }

        $query->limit($effectiveLimit);

        $frontendBaseUrl = rtrim((string) (Yii::$app->params['frontendBaseUrl'] ?? ''), '/');
        if ($frontendBaseUrl === '') {
            $this->logWarn("El parámetro 'frontendBaseUrl' no está configurado.");
            return ExitCode::UNSPECIFIED_ERROR;
        }

        $enviadas = 0;
        $sinCorreo = 0;
        $excluidasDominio = 0;
        $destinatarioInvalido = 0;
        $huboError = false;

        foreach ($query->each($batchSize) as $reserva) {
            try {
                $cliente = $reserva->cliente->nombre_completo;
                $emailCliente = trim((string) $reserva->cliente->correo);

                // Sin correo -> marcar como no enviable (2) y continuar
                if ($emailCliente === '') {
                    $sinCorreo++;
                    $reserva->evaluacion_enviada = 2; // 2 = no enviable
                    $reserva->save(false);
                    continue;
                }

                // Excluir dominio propio
                if (preg_match('/@parkingplus\.es$/i', $emailCliente)) {
                    $excluidasDominio++;
                    $reserva->evaluacion_enviada = 1; // 1 = enviado/descartado OK
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
                        'cliente'     => $cliente,
                        'nro_reserva' => $reserva->nro_reserva,
                        'correo'      => $emailCliente,
                        'urlEncuesta' => $urlEncuesta,
                    ]
                );
                $correo->setTo($emailCliente)
                    ->setFrom([Yii::$app->params['contactEmail'] => Yii::$app->name])
                    ->setSubject('Evalúe su reserva de aparcamiento');

                if (!$correo->send()) {
                    Yii::warning(sprintf(
                        'El proveedor rechazó el envío de la evaluación para la reserva %s.',
                        $reserva->id
                    ), __METHOD__);

                    $this->logWarn("Se detuvo el proceso porque el proveedor de correo rechazó un envío. Intente más tarde.");
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
                    $destinatarioInvalido++;
                    Yii::warning(sprintf(
                        'Se omitió la reserva %s por dirección de correo inválida (%s).',
                        $reserva->id,
                        $reserva->cliente->correo
                    ), __METHOD__);

                    $reserva->evaluacion_enviada = 2; // no enviable
                    $reserva->save(false);
                    continue;
                }

                Yii::error(sprintf(
                    'Error enviando evaluación a reserva %s: %s',
                    $reserva->id,
                    $exception->getMessage()
                ), __METHOD__);

                $this->logWarn("Se detuvo el proceso por un error al enviar una evaluación. Revise los logs para más detalles.");
                $huboError = true;
                break;
            }
        }

        $this->logInfo("Encuestas enviadas: {$enviadas}");
        $this->logInfo("Sin correo: {$sinCorreo} | Excluidas dominio: {$excluidasDominio} | Destinatario inválido: {$destinatarioInvalido}");

        if ($totalPendientes > $effectiveLimit && $enviadas >= $effectiveLimit) {
            $this->logInfo("Se alcanzó el máximo de correos permitido para esta hora. Ejecute el cron nuevamente después del próximo ciclo para continuar con los envíos pendientes.");
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

    /** ----- Helpers de logging (consola + app.log) ----- */

    private function logInfo(string $message): void
    {
        $formatted = $this->formatWithTimestamp($message);
        $this->stdout($formatted);                  // consola/cron
        Yii::info(rtrim($message, "\r\n"));         // app.log (console)
    }

    private function logWarn(string $message): void
    {
        $formatted = $this->formatWithTimestamp($message);
        $this->stderr($formatted);                  // consola/cron
        Yii::warning(rtrim($message, "\r\n"));      // app.log (console)
    }

    private function formatWithTimestamp(string $message): string
    {
        $timestamp = date('Y-m-d H:i:s');
        $trimmedMessage = rtrim($message, "\r\n");

        if ($trimmedMessage === '') {
            return sprintf("%s\n", $timestamp);
        }

        $lines = preg_split('/\r\n|\r|\n/', $trimmedMessage);

        // Closure compatible con PHP < 7.4
        $prefixedLines = array_map(
            function ($line) use ($timestamp) {
                return sprintf('%s %s', $timestamp, $line);
            },
            $lines
        );

        return implode(PHP_EOL, $prefixedLines) . PHP_EOL;
    }
}
