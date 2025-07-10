<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class PagosForm extends Model
{
    public $id_reserva;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_reserva'], 'required'],
        ];
    }   

}