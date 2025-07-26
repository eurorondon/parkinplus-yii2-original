<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "encuesta_inicial".
 *
 * @property int $id
 * @property int $reserva_id
 * @property int $pregunta1
 * @property int $pregunta2
 * @property int $pregunta3
 * @property int|null $respuesta
 * @property string|null $sugerencias
 * @property string|null $created_at
 */
class EncuestaInicial extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'encuesta_inicial';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules()
    {
        return [
            [['reserva_id', 'pregunta1', 'pregunta2', 'pregunta3'], 'required'],
            [['reserva_id', 'pregunta1', 'pregunta2', 'pregunta3', 'respuesta'], 'integer'],
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

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reserva_id' => 'Reserva ID',
            'pregunta1' => '¿Cómo calificarías la eficiencia del servicio de recogida y devolución de tu coche?',
            'pregunta2' => '¿Consideras que su vehículo fue tratado con cuidado durante el tiempo que estuvo bajo custodia del servicio?',
            'pregunta3' => '¿Recomendarías este servicio a otras personas?',
            'respuesta' => 'Respuesta',
            'sugerencias' => 'Sugerencias',
            'created_at' => 'Fecha de creación',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->respuesta === null) {
            // Si sugerencias está vacía o contiene solo espacios, respuesta es 1
            $this->respuesta = trim((string)$this->sugerencias) === '' ? 1 : 0;
        }
        return parent::beforeSave($insert);
    }
}
