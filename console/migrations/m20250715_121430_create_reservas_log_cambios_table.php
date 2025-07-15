<?php

use yii\db\Migration;

class m20250715_121430_create_reservas_log_cambios_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%reservas_log_cambios}}', [
            'id' => $this->primaryKey(),
            'reserva_id' => $this->integer()->notNull(),
            'campo' => $this->string()->notNull(),
            'valor_anterior' => $this->text(),
            'valor_nuevo' => $this->text(),
            'fecha' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk_reservas_log_cambios_reserva',
            '{{%reservas_log_cambios}}',
            'reserva_id',
            '{{%reservas}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function down()
    {
        $this->dropForeignKey('fk_reservas_log_cambios_reserva', '{{%reservas_log_cambios}}');
        $this->dropTable('{{%reservas_log_cambios}}');
    }
}
