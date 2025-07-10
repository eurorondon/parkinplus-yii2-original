<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tipo_pago".
 *
 * @property int $id
 * @property string $descripcion
 * @property int $estatus
 *
 * @property Reservas[] $reservas
 */
class TipoPago extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tipo_pago';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['estatus'], 'integer'],
            [['descripcion'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion' => 'Descripcion',
            'estatus' => 'Estatus',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReservas()
    {
        return $this->hasMany(Reservas::className(), ['id_tipo_pago' => 'id']);
    }
}
