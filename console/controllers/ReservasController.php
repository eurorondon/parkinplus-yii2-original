<?php

namespace console\controllers;

use common\models\Reservas;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\Expression;

/**
 * Console commands related to reservations maintenance.
 */
class ReservasController extends Controller
{
    /**
     * Actualiza los estatus de las reservas basándose en las fechas de entrada y salida.
     *
     * - Marca como finalizadas las reservas cuya fecha y hora de salida ya expiraron.
     * - Marca como en curso las reservas cuya entrada ya comenzó pero todavía no finalizan.
     *
     * Este comando debe programarse mediante cron para que el cambio de estatus no
     * afecte el tiempo de carga del panel administrativo.
     */
    public function actionActualizarEstatus(): int
    {
        try {
            $finalizadas = Reservas::updateAll(
                ['estatus' => '2'],
                [
                    'and',
                    new Expression('TIMESTAMP(fecha_salida, hora_salida) <= NOW()'),
                    ['not in', 'estatus', ['0', '2', '4']],
                ]
            );

            $this->stdout("Reservas marcadas como finalizadas: {$finalizadas}\n");

            $enCurso = Reservas::updateAll(
                ['estatus' => '3'],
                [
                    'and',
                    new Expression('TIMESTAMP(fecha_entrada, hora_entrada) <= NOW()'),
                    new Expression('TIMESTAMP(fecha_salida, hora_salida) > NOW()'),
                    ['not in', 'estatus', ['0', '2', '3', '4']],
                ]
            );

            $this->stdout("Reservas marcadas como en curso: {$enCurso}\n");
        } catch (\Throwable $exception) {
            Yii::error(
                sprintf('Error actualizando estatus de reservas: %s', $exception->getMessage()),
                __METHOD__
            );
            $this->stderr("Error al actualizar los estatus. Revise los logs para más detalles.\n");

            return ExitCode::UNSPECIFIED_ERROR;
        }

        return ExitCode::OK;
    }
}
