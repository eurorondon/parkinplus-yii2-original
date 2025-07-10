<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "facturas_reserva".
 *
 * @property int $id
 * @property int $id_reserva
 * @property int $id_factura
 */
class FacturasReserva extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facturas_reserva';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_reserva', 'id_factura'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_reserva' => 'Id Reserva',
            'id_factura' => 'Id Factura',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReserva()
    {
        return $this->hasOne(Reservas::className(), ['id' => 'id_reserva']);
    }

}
