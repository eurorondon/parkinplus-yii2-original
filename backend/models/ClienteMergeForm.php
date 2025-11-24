<?php

namespace backend\models;

use yii\base\Model;
use common\models\Clientes;

class ClienteMergeForm extends Model
{
    public $primary_id;
    public $duplicate_id;

    public function rules()
    {
        return [
            [['primary_id', 'duplicate_id'], 'required'],
            [['primary_id', 'duplicate_id'], 'integer'],
            ['primary_id', 'exist', 'targetClass' => Clientes::class, 'targetAttribute' => ['primary_id' => 'id']],
            ['duplicate_id', 'exist', 'targetClass' => Clientes::class, 'targetAttribute' => ['duplicate_id' => 'id']],
            ['primary_id', 'compare', 'compareAttribute' => 'duplicate_id', 'operator' => '!=', 'message' => 'Debe seleccionar clientes diferentes.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'primary_id' => 'Cliente principal',
            'duplicate_id' => 'Cliente duplicado',
        ];
    }
}
