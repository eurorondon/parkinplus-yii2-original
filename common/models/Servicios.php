<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "servicios".
 *
 * @property int $id
 * @property string $nombre_servicio
 * @property string $descripcion
 * @property string $costo
 * @property int $estatus
 * @property int $fijo 
 * @property int $id_listas_precios 
 * 
 * @property ReservasServicios[] $reservasServicios 
 */
class Servicios extends \yii\db\ActiveRecord
{

    const STATUS_INACTIVE = 0;


    const STATUS_ACTIVE  = 1;    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'servicios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_servicio','costo','estatus','fijo'], 'required'],
            [['costo'], 'number'],
            [['estatus', 'fijo', 'id_listas_precios'], 'integer'],
            [['nombre_servicio', 'descripcion'], 'string', 'max' => 255],
            [['id_listas_precios'], 'exist', 'skipOnError' => true, 'targetClass' => ListasPrecios::className(), 'targetAttribute' => ['id_listas_precios' => 'id']],             
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_servicio' => 'Nombre del Servicio',
            'descripcion' => 'Descripción',
            'costo' => 'Precio',
            'estatus' => 'Estado',
            'fijo' => 'Tipo de Servicio',
            'id_listas_precios' => 'Plan de Servicio',
        ];
    }

    public static function getStatus()

    {

        return array(

            self::STATUS_INACTIVE => 'Inactivo',

            self::STATUS_ACTIVE => 'Activo',

            );

    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getReservasServicios()
    {
        return $this->hasMany(ReservasServicios::className(), ['id_servicio' => 'id']);
    }    

}
