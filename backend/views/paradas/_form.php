<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Paradas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paradas-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-lg-6 col-md-6 col-xs-12">
            <?= $form->field($model, 'fecha_inicio')->widget(DatePicker::classname(), [
                'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()', 'value' => date('d-m-Y'),],

                'language' => 'es',
                'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy',
                    'startDate'=> date('d-m-Y'),
                    'todayHighlight' => true,                                     
                ]
            ]) ?>
        </div>

        <div class="col-lg-4 col-md-4 col-xs-12">
            <?= $form->field($model, 'hora_inicio')->widget(TimePicker::classname(), [
                'options' => ['style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
                'pluginOptions' => [
                  'showMeridian' => false,
                ]
            ]) ?> 
        </div>

        <div class="col-lg-6 col-md-6 col-xs-12">
            <?= $form->field($model, 'fecha_fin')->widget(DatePicker::classname(), [
                'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()', 'value' => date('d-m-Y'),],
                'language' => 'es',
                'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy',
                    'startDate'=> date('d-m-Y'),                                        
                ]
            ]) ?>
        </div>
        
        <div class="col-lg-4 col-md-4 col-xs-12">
            <?= $form->field($model, 'hora_fin')->widget(TimePicker::classname(), [
                'options' => ['style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
                'pluginOptions' => [
                  'showMeridian' => false,
                ]
            ]) ?> 
        </div>        
        <div class="col-lg-4 col-md-4 col-xs-12">
            <?= $form->field($model, 'status')->widget(Select2::classname(), [
                'data' => ['inactivo' => 'Inactivo', 'activo' => 'Activo'],
                'options' => ['placeholder' => 'Selecccione Estatus','value' => 'activo'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>  
        <div class="col-lg-12 col-md-12 col-xs-12">
            <br>
            <?= $form->field($model, 'descripcion')->textarea(['rows' => '3']) ?>
        </div>
    </div>

    <br>
    <div align="right" class="form-group">
        <?= Html::submitButton('Guardar Datos', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
