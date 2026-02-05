<?php

use yii\db\Migration;

/**
 * Adds payment email tracking fields to reservas.
 */
class m20250909_000003_add_pago_confirmado_correo_enviado_to_reservas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reservas', 'pago_confirmado_correo_enviado', $this->tinyInteger()->notNull()->defaultValue(0));
        $this->addColumn('reservas', 'pago_confirmado_correo_enviado_at', $this->dateTime()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('reservas', 'pago_confirmado_correo_enviado_at');
        $this->dropColumn('reservas', 'pago_confirmado_correo_enviado');
    }
}
