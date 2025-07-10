<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "clientes".
 *
 * @property int $id
 * @property string $nombre_completo
 * @property string $correo
 * @property string $tipo_documento
 * @property string $nro_documento
 * @property string $movil
 * @property int $estatus 1= Activo 2= Inactivo 
 * @property string $created_at 
 * @property int $created_by 
 * @property string $updated_at 
 * @property int $updated_by  
 *
 * @property Coches[] $coches
 * @property Reservas[] $reservas
 */
class Clientes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['nombre_completo', 'required', 'message' => 'Ingrese su Nombre y Apellidos'],
            ['movil', 'required', 'message' => 'Ingrese su N° de Móvil'],
            ['correo', 'required', 'message' => 'Ingrese su Correo Electrónico'],
            [['estatus'], 'required'],
            [['estatus', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],            
            [['nombre_completo'], 'string', 'max' => 255],
            ['correo', 'trim'],
            ['correo', 'email'],
            ['correo', 'string', 'max' => 255],
            [['tipo_documento', 'nro_documento', 'movil'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_completo' => 'Nombres y Apellidos',
            'correo' => 'Correo Electrónico',
            'tipo_documento' => 'Tipo de Documento',
            'nro_documento' => 'Nro de Documento',
            'movil' => 'N° de Móvil',
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
    public function getCoches()
    {
        return $this->hasMany(Coches::className(), ['id_cliente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReservas()
    {
        return $this->hasMany(Reservas::className(), ['id_cliente' => 'id']);
    }
}
