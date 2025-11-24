<?php

namespace backend\models;

use yii\base\Model;

class ClienteMergePhoneForm extends Model
{
    public $movil;

    public function rules()
    {
        return [
            [['movil'], 'required'],
            [['movil'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'movil' => 'Teléfono',
        ];
    }
}
