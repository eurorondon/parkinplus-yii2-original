<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "precio_temporada".
 *
 * @property int $id
 * @property string $fecha_inicio
 * @property string $hora_inicio
 * @property string $fecha_fin
 * @property string $hora_fin
 * @property number $precio
 * @property string|null $descripcion
 */
class PrecioTemporada extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'precio_temporada';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_inicio'], 'required','message' => 'Debe Ingresar la fecha de inicio'],
            [['fecha_fin'], 'required','message' => 'Debe Ingresar la fecha de fin'],
            [['hora_inicio'], 'required','message' => 'Debe Ingresar la hora de inicio'],
            [['hora_fin'], 'required','message' => 'Debe Ingresar la hora de fin'],
            [['precio'], 'required','message' => 'Debe Ingresar el precio'],
            [['descripcion'], 'required','message' => 'Debe Ingresar una descripción o motivo del precio'],
            [['fecha_inicio', 'hora_inicio', 'fecha_fin', 'hora_fin'], 'safe'],
            [['descripcion'], 'string', 'max' => 255],
            [['precio'], 'number'],
            [['status'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha_inicio' => 'Fecha de Inicio',
            'fecha_fin' => 'Fecha de Fin',
            'hora_inicio' => 'Hora de Inicio',
            'hora_fin' => 'Hora de Fin',
            'precio' => 'Precio de Temporada',
            'descripcion' => 'Descripción / Motivo de la Temporada',
            'status' => 'Estado del precio'
        ];
        
    }
    
}
