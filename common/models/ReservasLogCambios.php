<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reservas_log_cambios".
 *
 * @property int $id
 * @property int $reserva_id
 * @property string $campo
 * @property string|null $valor_anterior
 * @property string|null $valor_nuevo
 * @property string $fecha
 *
 * @property Reservas $reserva
 */
class ReservasLogCambios extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'reservas_log_cambios';
    }

    public function rules()
    {
        return [
            [['reserva_id', 'campo'], 'required'],
            [['reserva_id'], 'integer'],
            [['valor_anterior', 'valor_nuevo'], 'string'],
            [['fecha'], 'safe'],
            [['campo'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reserva_id' => 'Reserva ID',
            'campo' => 'Campo',
            'valor_anterior' => 'Valor Anterior',
            'valor_nuevo' => 'Valor Nuevo',
            'fecha' => 'Fecha',
        ];
    }

    public function getReserva()
    {
        return $this->hasOne(Reservas::className(), ['id' => 'reserva_id']);
    }
}
