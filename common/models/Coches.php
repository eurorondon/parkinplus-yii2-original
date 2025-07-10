<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coches".
 *
 * @property int $id
 * @property int $id_cliente
 * @property string $matricula
 * @property string $marca
 * @property string $modelo
 * @property string $color
 * @property int $estatus_coche 1= Activo 2= Inactivo 
 * @property string $created_at 
 * @property int $created_by 
 * @property string $updated_at 
 * @property int $updated_by
 *
 * @property Clientes $cliente
 * @property Reservas[] $reservas
 */
class Coches extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coches';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'estatus_coche'], 'required'],
            [['id_cliente', 'estatus_coche', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['matricula', 'marca', 'modelo', 'color'], 'string', 'max' => 200],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['id_cliente' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_cliente' => 'Cliente - Propietario',
            'matricula' => 'Matrícula',
            'marca' => 'Marca - Modelo',
            'modelo' => 'Modelo',
            'color' => 'Color',
            'estatus_coche' => 'Estado', 
            'created_at' => 'Fecha de Registro', 
            'created_by' => 'Created By', 
            'updated_at' => 'Updated At', 
            'updated_by' => 'Updated By',             
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
    public function getReservas()
    {
        return $this->hasMany(Reservas::className(), ['id_coche' => 'id']);
    }
}
