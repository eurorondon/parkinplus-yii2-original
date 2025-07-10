<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "facturas_servicios".
 *
 * @property int $id
 * @property int $id_factura
 * @property int $id_servicio
 * @property int $cantidad
 * @property string $precio_unitario
 * @property string $precio_total
 * @property int $tipo_servicio 
 */
class FacturasServicios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facturas_servicios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_factura', 'id_servicio', 'cantidad', 'tipo_servicio'], 'integer'],
            [['precio_unitario', 'precio_total'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_factura' => 'Id Factura',
            'id_servicio' => 'Id Servicio',
            'cantidad' => 'Cantidad',
            'precio_unitario' => 'Precio Unitario',
            'precio_total' => 'Precio Total',
            'tipo_servicio' => 'Tipo de Servicio', 
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicios::className(), ['id' => 'id_factura']);
    }    

}
