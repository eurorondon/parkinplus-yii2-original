<?php

use yii\db\Migration;

/**
 * Handles adding column `ajuste_pago_pendiente` to table `{{%reservas}}`.
 */
class m20250919_000001_add_ajuste_pago_pendiente_to_reservas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reservas', 'ajuste_pago_pendiente', $this->tinyInteger()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('reservas', 'ajuste_pago_pendiente');
    }
}
