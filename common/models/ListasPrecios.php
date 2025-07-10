<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "listas_precios".
 *
 * @property int $id
 * @property string $nombre
 * @property string $agregado
 * @property int $estatus
 *
 * @property Servicios[] $servicios
 */
class ListasPrecios extends \yii\db\ActiveRecord
{
    public $cantidad;
    public $costo;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'listas_precios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agregado'], 'number'],
            [['estatus'], 'integer'],
            [['nombre'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Plan de Servicio',
            'agregado' => 'Cuota por Día',
            'estatus' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getServicios()
    {
        return $this->hasMany(Servicios::className(), ['id_listas_precios' => 'id']);
    }
}
