<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "agencias".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $telefono
 * @property string|null $movil
 * @property string|null $contacto
 * @property string|null $direccion
 * @property int $estatus 1= Activo 2= Inactivo
 * @property string $created_at
 * @property int $created_by
 * @property string $updated_at
 * @property int $updated_by
 */
class Agencias extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agencias';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estatus', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['nombre', 'direccion'], 'string', 'max' => 255],
            [['telefono', 'movil', 'contacto'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre de la Agencia',
            'telefono' => 'Teléfono',
            'movil' => 'Móvil',
            'contacto' => 'Contacto',
            'direccion' => 'Dirección',
            'estatus' => 'Estátus',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
}
