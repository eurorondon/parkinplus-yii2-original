<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "conceptos".
 *
 * @property int $id
 * @property string $descripcion
 * @property float $punitario
 * @property float $cantidad
 * @property float $ptotal
 * @property int $id_factura
 *
 * @property Facturas $factura
 */
class Conceptos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'conceptos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['punitario', 'cantidad', 'ptotal'], 'number'],
            [['id_factura'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
            [['id_factura'], 'exist', 'skipOnError' => true, 'targetClass' => Facturas::className(), 'targetAttribute' => ['id_factura' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'punitario' => 'Punitario',
            'cantidad' => 'Cantidad',
            'ptotal' => 'Ptotal',
            'id_factura' => 'Id Factura',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFactura()
    {
        return $this->hasOne(Facturas::className(), ['id' => 'id_factura']);
    }
}
