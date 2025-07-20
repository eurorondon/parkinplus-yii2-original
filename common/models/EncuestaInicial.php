<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "encuesta_inicial".
 *
 * @property int $id
 * @property int $reserva_id
 * @property int $pregunta1
 * @property int $pregunta2
 * @property int $pregunta3
 * @property int $pregunta4
 * @property int $pregunta5
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
            [['reserva_id', 'pregunta1', 'pregunta2', 'pregunta3', 'pregunta4', 'pregunta5'], 'required'],
            [['reserva_id', 'pregunta1', 'pregunta2', 'pregunta3', 'pregunta4', 'pregunta5'], 'integer'],
            [['sugerencias'], 'string', 'max' => 255],
            ['sugerencias', 'required', 'when' => function ($model) {
                return max([
                    $model->pregunta1,
                    $model->pregunta2,
                    $model->pregunta3,
                    $model->pregunta4,
                    $model->pregunta5,
                ]) >= 4;
            }, 'whenClient' => "function(attribute, value) {
                return Math.max(
                    parseInt($('input[name=\"EncuestaInicial[pregunta1]\"]:checked').val() || 0),
                    parseInt($('input[name=\"EncuestaInicial[pregunta2]\"]:checked').val() || 0),
                    parseInt($('input[name=\"EncuestaInicial[pregunta3]\"]:checked').val() || 0),
                    parseInt($('input[name=\"EncuestaInicial[pregunta4]\"]:checked').val() || 0),
                    parseInt($('input[name=\"EncuestaInicial[pregunta5]\"]:checked').val() || 0)
                ) >= 4;
            }"],
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
            'pregunta1' => 'Pregunta 1',
            'pregunta2' => 'Pregunta 2',
            'pregunta3' => 'Pregunta 3',
            'pregunta4' => 'Pregunta 4',
            'pregunta5' => 'Pregunta 5',
            'sugerencias' => 'Sugerencias',
        ];
    }
}
