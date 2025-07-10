<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "paradas".
 *
 * @property int $id
 * @property string $fecha_inicio
 * @property string $hora_inicio 
 * @property string $fecha_fin
 * @property string $hora_fin 
 * @property string|null $descripcion
 */
class Paradas extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'paradas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_inicio'], 'required','message'=>'Debe Ingresar la fecha de inicio'],
            [['fecha_fin'], 'required','message'=>'Debe Ingresar la fecha de fin'],
            [['hora_inicio'], 'required','message'=>'Debe Ingresar la hora de inicio'],
            [['hora_fin'], 'required','message'=>'Debe Ingresar la hora de fin'],
            [['descripcion'], 'required','message'=>'Debe Ingresar una descripción o motivo de la parada'],
            [['fecha_inicio', 'hora_inicio', 'fecha_fin', 'hora_fin'], 'safe'],
            [['descripcion'], 'string', 'max' => 255],
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
            'descripcion' => 'Descripción / Motivo de Parada',
            'status' => 'Estado de la parada'
        ];
    }
}
