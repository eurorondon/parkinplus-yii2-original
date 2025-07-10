<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "factureros".
 *
 * @property int $id
 * @property string $serie
 * @property string $factura_inicio
 * @property string $factura_fin
 * @property int $estatus
 */
class Factureros extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'factureros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['factura_inicio', 'factura_fin'], 'number'],
            [['estatus'], 'integer'],
            [['serie'], 'string', 'max' => 2],
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
            'factura_inicio' => 'Factura Inicio',
            'factura_fin' => 'Factura Fin',
            'estatus' => 'Estatus',
        ];
    }
}
