<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "encuesta_inicial".
 *
 * @property int $id
 * @property int $reserva_id
 * @property int $respuesta
 * @property string|null $sugerencias
 */
class EncuestaInicial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'encuesta_inicial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reserva_id', 'respuesta'], 'required'],
            [['reserva_id', 'respuesta'], 'integer'],
            [['sugerencias'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reserva_id' => 'Reserva ID',
            'respuesta' => 'Respuesta',
            'sugerencias' => 'Sugerencias',
        ];
    }
}