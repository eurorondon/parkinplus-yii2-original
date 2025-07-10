<?php

namespace common\models;

use Yii;
 
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "facturas".
 *
 * @property int $id
 * @property string $serie
 * @property string $nro_factura
 * @property string $nif
 * @property string $razon_social
 * @property string $direccion
 * @property string $cod_postal
 * @property string $ciudad
 * @property string $provincia
 * @property string $pais
 * @property string $monto_factura
 * @property string $monto_impuestos
 * @property string $monto_total
 * @property int $id_tipo_pago
 * @property string|null $observacion 
 * @property int $estatus 0= Cancelada 1= Activa 2=Pendiente
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * 
 * @property TipoPago $tipoPago 
 */
 
class Facturas extends \yii\db\ActiveRecord
{

    public $servicios;
    public $tipo_servicio;
    public $precio_unitario;
    public $cantidad;
    public $precio_total;
    public $iva;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'facturas';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }     

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nro_factura', 'monto_factura', 'monto_impuestos', 'monto_total', 'nif', 'razon_social', 'direccion', 'ciudad', 'provincia', 'pais', 'cod_postal', 'id_tipo_pago'], 'required'],
            [['nro_factura', 'monto_factura', 'monto_impuestos', 'monto_total'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['id_tipo_pago', 'estatus', 'created_by', 'updated_by'], 'integer'], 
            [['serie'], 'string', 'max' => 2],
            [['nif'], 'string', 'max' => 100],
            [['razon_social', 'direccion', 'ciudad', 'provincia', 'pais', 'observacion'], 'string', 'max' => 255],
            [['cod_postal'], 'string', 'max' => 5],
            [['id_tipo_pago'], 'exist', 'skipOnError' => true, 'targetClass' => TipoPago::className(), 'targetAttribute' => ['id_tipo_pago' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'serie' => 'Serie',
            'nro_factura' => 'N° Factura',
            'nif' => 'NIF',
            'razon_social' => 'Razón Social',
            'direccion' => 'Dirección',
            'cod_postal' => 'Cód. Postal',
            'ciudad' => 'Ciudad',
            'provincia' => 'Provincia',
            'pais' => 'País',
            'monto_factura' => 'Subtotal',
            'monto_impuestos' => 'Impuestos',
            'monto_total' => 'Total',
            'id_tipo_pago' => 'Forma de Pago',
            'observacion' => 'Observación',
            'estatus' => 'Estatus',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /** 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getTipoPago() 
    { 
       return $this->hasOne(TipoPago::className(), ['id' => 'id_tipo_pago']); 
    }     

}
