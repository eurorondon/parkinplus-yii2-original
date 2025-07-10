<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "registro_precios".
 *
 * @property int $id
 * @property int $id_lista
 * @property int $cantidad
 * @property string $costo
 *
 * @property ListasPrecios $lista
 */
class RegistroPrecios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'registro_precios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_lista', 'cantidad'], 'integer'],
            [['costo'], 'number'],
            [['id_lista'], 'exist', 'skipOnError' => true, 'targetClass' => ListasPrecios::className(), 'targetAttribute' => ['id_lista' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_lista' => 'Lista de Precios',
            'cantidad' => 'Dia',
            'costo' => 'Costo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLista()
    {
        return $this->hasOne(ListasPrecios::className(), ['id' => 'id_lista']);
    }
}
