<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "configuracion".
 *
 * @property int $id
 * @property string $campo
 * @property string $valor_numerico
 * @property string $valor_texto
 * @property int $estatus
 * @property int $tipo_campo 1 = Impuestos 2 = Correlativo de Reserva
 * @property int $tipo_impuesto 1 = IVA
 */
class Configuracion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuracion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['valor_numerico'], 'number'],
            [['estatus', 'tipo_campo', 'tipo_impuesto'], 'integer'],
            [['campo'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'campo' => 'Campo',
            'valor_numerico' => 'Valor Numerico',
            'valor_texto' => 'Valor Texto',
            'estatus' => 'Estatus',
            'tipo_campo' => 'Tipo Campo',
            'tipo_impuesto' => 'Tipo Impuesto',
        ];
    }
}
