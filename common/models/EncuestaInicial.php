<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "encuesta_inicial".
 *
 * @property int $id
 * @property int $reserva_id
 * @property int $pregunta1 Tiempo de espera
 * @property int $pregunta2 Cuidado del vehículo
 * @property int $pregunta3 Recomendación
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
            [['reserva_id', 'pregunta1', 'pregunta2', 'pregunta3'], 'required'],
            [['reserva_id', 'pregunta1', 'pregunta2', 'pregunta3'], 'integer'],
            [['sugerencias'], 'string', 'max' => 255],
            ['sugerencias', 'required', 'when' => function ($model) {
                return max([
                    $model->pregunta1,
                    $model->pregunta2,
                    $model->pregunta3,
                ]) >= 4;
            }, 'whenClient' => "function(attribute, value) {
                return Math.max(
                    parseInt($('input[name=\"EncuestaInicial[pregunta1]\"]:checked').val() || 0),
                    parseInt($('input[name=\"EncuestaInicial[pregunta2]\"]:checked').val() || 0),
                    parseInt($('input[name=\"EncuestaInicial[pregunta3]\"]:checked').val() || 0)
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
            'pregunta1' => '¿Cómo calificarías la eficiencia del servicio de recogida y devolución de tu coche?',
            'pregunta2' => '¿Consideras que su vehículo fue tratado con cuidado durante el tiempo que estuvo bajo custodia del servicio?',
            'pregunta3' => '¿Recomendarías este servicio a otras personas?',
            'sugerencias' => 'Sugerencias',
        ];
    }
}
