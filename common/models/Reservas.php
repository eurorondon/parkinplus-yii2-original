<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "reservas".
 *
 * @property int $id
 * @property float $nro_reserva 
 * @property string $fecha_entrada
 * @property string $hora_entrada 
 * @property string $fecha_salida
 * @property string $hora_salida 
 * @property int $id_cliente
 * @property int $id_coche
 * @property string $terminal_entrada
 * @property string $terminal_salida
 * @property string $nro_vuelo_regreso
 * @property string $ciudad_procedencia 
 * @property int $factura_equipaje
 * @property string $observaciones
 * @property int $factura 
 * @property string $nif 
 * @property string $razon_social 
 * @property string $direccion 
 * @property string $cod_postal 
 * @property string $ciudad 
 * @property string $provincia 
 * @property string $pais
 * @property string $costo_servicios
 * @property string $costo_servicios_extra
 * @property string $monto_factura
 * @property string $monto_impuestos
 * @property string $monto_total 
 * @property int $id_tipo_pago
 * @property int $condiciones
 * @property string|null $cupon
 * @property float|null $porcentaje_cupo
 * @property string|null $impreso 
 * @property int $medio_reserva
 * @property string|null $agencia 
 * @property int $estatus 
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 * @property int $actualizada
 *
 * @property Clientes $cliente
 * @property Coches $coche
 * @property TipoPago $tipoPago
 * @property string $token
 */
class Reservas extends \yii\db\ActiveRecord
{
    public $servicios;
    public $servicio_basico;
    public $cant_basico;
    public $seguro;
    public $cant_seguro;
    public $total_seguro;
    public $servicios_extras;
    public $cant_extras;
    public $correo;
    public $tipo_documento;
    public $nro_documento;
    public $movil;
    public $matricula;
    public $marca;
    public $modelo;
    public $color;

    public $tipo_servicio;
    public $precio_unitario;
    public $cantidad;
    public $precio_total;
    public $iva;

    public $dias;

    public $fechae;
    public $fechas;
    public $horae;
    public $horas;

    public $fecha_busca;

    public $cortesia;
    public $techado;

    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;
    public $token;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reservas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['condiciones'], 'required', 'message' => 'Debe Aceptar Nuestras Condiciones Generales del Servicio'],
            [['id_tipo_pago'], 'required', 'message' => 'Debe Seleccionar el tipo de pago'],
            [['fecha_entrada'], 'required', 'message' => 'Debe Seleccionar la fecha de entrada'],
            [['fecha_salida'], 'required', 'message' => 'Debe Seleccionar la fecha de salida'],
            [['id_cliente', 'costo_servicios', 'costo_servicios_extra', 'monto_factura', 'monto_impuestos', 'monto_total'], 'required'],
            [['cortesia', 'techado', 'fecha_entrada', 'hora_entrada', 'fecha_salida', 'hora_salida', 'created_at', 'updated_at'], 'safe'],
            [['id_cliente',  'factura_equipaje', 'factura', 'id_tipo_pago', 'condiciones', 'medio_reserva', 'estatus', 'created_by', 'updated_by', 'canceled_by', 'actualizada'], 'integer'],
            [['nro_reserva', 'costo_servicios', 'costo_servicios_extra', 'monto_factura', 'monto_impuestos', 'monto_total', 'porcentaje_cupo', 'monto_des'], 'number'],
            [['terminal_entrada', 'terminal_salida', 'nro_vuelo_regreso', 'ciudad_procedencia', 'observaciones', 'razon_social', 'direccion', 'ciudad', 'provincia', 'pais', 'cupon', 'agencia', 'cod_valid'], 'string', 'max' => 255],
            [['nif'], 'string', 'max' => 100],
            [['cod_postal', 'impreso', 'descuento'], 'string', 'max' => 5],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id']],
            [['id_coche'], 'exist', 'skipOnError' => true, 'targetClass' => Coches::className(), 'targetAttribute' => ['id_coche' => 'id']],
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
            'nro_reserva' => 'Reserva',
            'fecha_entrada' => 'Recogida',
            'hora_entrada' => 'Hora',
            'fecha_salida' => 'Devolución',
            'hora_salida' => 'Hora',
            'id_cliente' => 'Nombre del Cliente',
            'id_coche' => 'Vehículo',
            'terminal_entrada' => 'Terminal de Entrada',
            'terminal_salida' => 'Terminal de Salida',
            'nro_vuelo_regreso' => 'N° Vuelo de Regreso',
            'ciudad_procedencia' => 'Ciudad de Procedencia',
            'factura_equipaje' => 'Factura Equipaje ?',
            'observaciones' => 'Observaciones',
            'factura' => 'Requiere Factura',
            'nif' => 'NIF',
            'razon_social' => 'Razón Social',
            'direccion' => 'Dirección',
            'cod_postal' => 'Código Postal',
            'ciudad' => 'Ciudad',
            'provincia' => 'Provincia',
            'pais' => 'País',
            'costo_servicios' => 'Costo de Servicios',
            'costo_servicios_extra' => 'Costo de Servicios Extra',
            'monto_factura' => 'Subtotal',
            'monto_impuestos' => 'Impuestos',
            'monto_total' => 'Total',
            'id_tipo_pago' => 'Forma de Pago',
            'condiciones' => 'Acepto las Condiciones Generales del Servicio',
            'cupon' => 'Cupón',
            'porcentaje_cupo' => '% Desc.',
            'impreso' => 'Impreso',
            'medio_reserva' => 'Medio',
            'agencia' => 'Nombre de la Agencia',
            'estatus' => 'Estado',
            'created_at' => 'Fecha de Reserva',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'actualizada' => 'Actualizada',
            'canceled_by' => 'Cancelada Por',
            'correo' => 'Correo Electrónico',
            'tipo_documento' => 'Tipo de Documento',
            'nro_documento' => 'N° de Documento',
            'movil' => 'Móvil',
            'cortesia' => 'Limpieza de Cortesía',
            'techado' => 'Chofer Express',
            'descuento' => 'Descuento',
            'monto_des' => 'Monto descuento'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id' => 'id_cliente']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCanceledBy()
    {
        return $this->hasOne(User::className(), ['id' => 'canceled_by']);
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoche()
    {
        return $this->hasOne(Coches::className(), ['id' => 'id_coche']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoPago()
    {
        return $this->hasOne(TipoPago::className(), ['id' => 'id_tipo_pago']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCochesList($id)
    {
        $data = Coches::find()->where(['id_cliente' => $id])->select('*')->asArray()->all();
        return $data;
    }

    /**
     * Gets log entries for changes made by the client.
     *
     * @return \\yii\\db\\ActiveQuery
     */
    public function getCambios()
    {
        return $this->hasMany(ReservasLogCambios::className(), ["reserva_id" => "id"])->orderBy(["fecha" => SORT_DESC]);
    }
}

