<?php

use yii\db\Migration;

/**
 * Handles adding column `plan` to table `{{%reservas}}`.
 */
class m20250826_221917_add_plan_to_reservas_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('reservas', 'plan', $this->tinyInteger()->notNull()->defaultValue(1)->comment('1:Bronce 2:Plata 3:Oro'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('reservas', 'plan');
    }
}
