<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Clientes */
/* @var $form yii\widgets\ActiveForm */

$estatus = [ 1 => 'Activo', 2 => 'Inactivo'];

?>

<div class="clientes-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-12">    
            <?= $form->field($model, 'nombre_completo')->textInput(['maxlength' => true]) ?>
        </div>       

        <div class="col-lg-12" style="margin-top: 0px"></div>

        <div class="col-lg-6">
            <?= $form->field($model, 'correo')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-6">
            <?= $form->field($model, 'movil')->textInput(['maxlength' => true]) ?>
        </div> 

        <div class="col-lg-12" style="margin-top: 0px"></div>                        
        
        <div class="col-lg-6">
            <?= $form->field($model, 'tipo_documento')->widget(Select2::classname(), [
                'data' => $tipo_documento,
                'options' => ['placeholder' => 'Selecccione'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-lg-6">
            <?= $form->field($model, 'nro_documento')->textInput(['maxlength' => true]) ?>
        </div>                 
       
        <div align="right" class="col-lg-12" style="margin-top: 10px">
            <div class="form-group">
                <?= Html::submitButton('Guardar Datos', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
