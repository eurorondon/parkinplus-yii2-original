<?php

use yii\db\Migration;

/**
 * Adds payment confirmation audit fields to reservas.
 */
class m20250909_000002_add_pago_confirmado_log_to_reservas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reservas', 'pago_confirmado_log', $this->text()->null());
        $this->addColumn('reservas', 'pago_confirmado_firma_valida', $this->tinyInteger()->notNull()->defaultValue(0));
        $this->addColumn('reservas', 'pago_confirmado_actualizado', $this->dateTime()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('reservas', 'pago_confirmado_actualizado');
        $this->dropColumn('reservas', 'pago_confirmado_firma_valida');
        $this->dropColumn('reservas', 'pago_confirmado_log');
    }
}
