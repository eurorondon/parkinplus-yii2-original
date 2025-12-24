<?php

namespace backend\models;

use yii\base\Model;

class CocheMergeForm extends Model
{
    public $matricula;
    public $marca;

    public function rules(): array
    {
        return [
            [['matricula', 'marca'], 'required'],
            [['matricula', 'marca'], 'string', 'max' => 200],
            [['matricula', 'marca'], 'trim'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'matricula' => 'Matrícula',
            'marca' => 'Marca definitiva',
        ];
    }
}
