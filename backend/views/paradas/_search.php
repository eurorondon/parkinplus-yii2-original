<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ParadaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paradas-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-lg-3 col-md-3 col-xs-12">
            <?= $form->field($model, 'fecha_inicio')->widget(DatePicker::classname(), [
                'options' => ['autocomplete' => 'off'],
                'language' => 'es',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,                                       
                ]
            ]) ?>
        </div>

        <div class="col-lg-3 col-md-3 col-xs-12">
            <?= $form->field($model, 'hora_inicio')->widget(TimePicker::classname(), [
                'options' => [
                    'style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important',
                    'value' => ''
                ],
                'pluginOptions' => [
                  'showMeridian' => false,
                ]
            ]) ?>
        </div>        

        <div class="col-lg-3 col-md-3 col-xs-12">
            <?= $form->field($model, 'fecha_fin')->widget(DatePicker::classname(), [
                'options' => ['autocomplete' => 'off'],
                'language' => 'es',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,                                       
                ]
            ]) ?>
        </div>

        <div class="col-lg-3 col-md-3 col-xs-12">
            <?= $form->field($model, 'hora_fin')->widget(TimePicker::classname(), [
                'options' => [
                    'style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important',
                    'value' => '',
                ],
                'pluginOptions' => [
                  'showMeridian' => false,
                ]
            ]) ?>
        </div>

        <div class="col-lg-6 col-md-6 col-xs-12" style="margin-top: 15px">
            <?= $form->field($model, 'descripcion') ?>
        </div>


        <div align="right" class="col-lg-4 col-md-4 col-xs-12" style="margin-top: 30px">
            <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-warning']) ?>
        </div>

        <div align="right" class="col-lg-2 col-md-2 col-xs-12" style="margin-top: 30px">
            <?= Html::submitButton('BUSCAR PARADA', ['class' => 'btn btn-success']) ?>
        </div> 

        <div class="col-lg-12 col-md-12 col-xs-12"><hr class="linea"></div>    
    </div>
    <?php ActiveForm::end(); ?>
</div>
