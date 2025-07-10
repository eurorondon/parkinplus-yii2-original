<?php

namespace frontend\components;


class Modal extends \yii\bootstrap\Modal{

    public static function getContent($file){

    	//var_dump($file); die();
        echo file_get_contents($file);
    }

}