<?php

use yii\db\Migration;

class m20250720_012600_add_pregunta_columns_to_encuesta_inicial_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('encuesta_inicial', 'pregunta1', $this->integer());
        $this->addColumn('encuesta_inicial', 'pregunta2', $this->integer());
        $this->addColumn('encuesta_inicial', 'pregunta3', $this->integer());
        $this->addColumn('encuesta_inicial', 'pregunta4', $this->integer());
        $this->addColumn('encuesta_inicial', 'pregunta5', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn('encuesta_inicial', 'pregunta1');
        $this->dropColumn('encuesta_inicial', 'pregunta2');
        $this->dropColumn('encuesta_inicial', 'pregunta3');
        $this->dropColumn('encuesta_inicial', 'pregunta4');
        $this->dropColumn('encuesta_inicial', 'pregunta5');
    }
}
