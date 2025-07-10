<?php

namespace common\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class PrereservaForm extends Model
{
    public $fecha_entrada;
    public $fecha_salida;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // fecha_entrada and fecha_salida are both required
            [['fecha_entrada', 'fecha_salida'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fecha_entrada' => 'Fecha de Entrada',
            'fecha_salida' => 'Fecha de Salida',
        ];
    }

}
