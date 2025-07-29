<?php

use yii\db\Migration;

class m20250729_000001_add_evaluacion_enviada_column extends Migration
{
    public function up()
    {
        $this->addColumn('reservas', 'evaluacion_enviada', $this->tinyInteger()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('reservas', 'evaluacion_enviada');
    }
}
