<?php

use yii\db\Migration;

/**
 * Handles inserting data for table `listas_precios` for Economic plan.
 */
class m20250614_000001_add_economic_plan_to_listas_precios extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Duplicate Standard plan (id=1) into new Economic plan (id=4)
        $this->execute("INSERT INTO listas_precios (id, nombre, agregado, estatus)
                         SELECT 4, 'Economic', agregado, estatus FROM listas_precios WHERE id = 1");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('listas_precios', ['id' => 4]);
    }
}
