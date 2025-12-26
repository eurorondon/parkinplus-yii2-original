<?php

use yii\db\Migration;

/**
 * Adds a parada entry to block reservations between December 31st at 19:00 and January 1st at 11:00.
 */
class m20241224_000001_add_year_end_parada extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('paradas', [
            'fecha_inicio' => '2024-12-31',
            'hora_inicio' => '19:00:00',
            'fecha_fin'   => '2025-01-01',
            'hora_fin'    => '11:00:00',
            'descripcion' => 'Bloqueo de reservas durante el cierre de fin de año',
            'status'      => 'activo',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('paradas', [
            'fecha_inicio' => '2024-12-31',
            'hora_inicio' => '19:00:00',
            'fecha_fin'   => '2025-01-01',
            'hora_fin'    => '11:00:00',
            'descripcion' => 'Bloqueo de reservas durante el cierre de fin de año',
            'status'      => 'activo',
        ]);
    }
}
