<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_cliente".
 *
 * @property int $id
 * @property int $id_usuario
 * @property int $id_cliente
 */
class UserCliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario', 'id_cliente'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_usuario' => 'Id Usuario',
            'id_cliente' => 'Id Cliente',
        ];
    }
}
