<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "reservas_servicios".
 *
 * @property int $id
 * @property int $id_reserva
 * @property int $id_servicio
 * @property int $cantidad
 * @property string $precio_unitario
 * @property string $precio_total
 * @property int $tipo_servicio
 *
 * @property Servicios $servicio
 */
class ReservasServicios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reservas_servicios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_reserva', 'id_servicio', 'cantidad', 'tipo_servicio'], 'integer'],
            [['precio_unitario', 'precio_total'], 'number'],
            [['id_servicio'], 'exist', 'skipOnError' => true, 'targetClass' => Servicios::className(), 'targetAttribute' => ['id_servicio' => 'id']],
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
            'id_servicio' => 'Id Servicio',
            'cantidad' => 'Cantidad',
            'precio_unitario' => 'Precio Unitario',
            'precio_total' => 'Precio Total',
            'tipo_servicio' => 'Tipo Servicio',
        ];
    }
    
    /** 
    * @return \yii\db\ActiveQuery 
    */ 
   public function getServicios() 
   { 
       return $this->hasOne(Servicios::className(), ['id' => 'id_servicio']); 
   } 
}