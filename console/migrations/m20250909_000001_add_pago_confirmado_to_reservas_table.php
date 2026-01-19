<?php

use yii\db\Migration;

/**
 * Handles adding column `pago_confirmado` to table `{{%reservas}}`.
 */
class m20250909_000001_add_pago_confirmado_to_reservas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reservas', 'pago_confirmado', $this->tinyInteger()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('reservas', 'pago_confirmado');
    }
}
