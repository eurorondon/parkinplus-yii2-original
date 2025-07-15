<?php
use yii\db\Migration;

class m20250715_021600_add_actualizada_column_to_reservas_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%reservas}}', 'actualizada', $this->boolean()->notNull()->defaultValue(0));
    }

    public function down()
    {
        $this->dropColumn('{{%reservas}}', 'actualizada');
    }
}
