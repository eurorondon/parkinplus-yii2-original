<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\ServiciosSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicios-search">
    <div class="row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>

        <div class="col-lg-5 col-md-5 col-xs-12">
            <label>Nombre del Servicio</label>
            <?= $form->field($model, 'nombre_servicio')->label(false) ?>
        </div> 

        <div class="col-lg-5 col-md-5 col-xs-12">
            <label>Descripción</label>
            <?= $form->field($model, 'descripcion')->label(false) ?>
        </div>

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>Costo</label>
            <?= $form->field($model, 'costo')->label(false) ?>
        </div> 

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Estátus</label>
            <?= $form->field($model, 'estatus')->widget(Select2::classname(), [
                'data' => ['0'=>'Inactivo', '1'=>'Activo'], 
                'options' => ['placeholder' => 'Selecccione Estátus'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>
        </div> 

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Tipo de Servicio</label>
            <?= $form->field($model, 'fijo')->widget(Select2::classname(), [
                'data' => ['0'=>'Servicio Opcional', '1'=>'Servicio Fijo', '2'=>'Servicio Extra'], 
                'options' => ['placeholder' => 'Selecccione Tipo de Servicio'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>
        </div>                                         

        <div align="right" class="col-lg-4 col-md-4 col-xs-12" style="margin-top: 15px">
            <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-warning']) ?>
        </div>

        <div align="right" class="col-lg-2 col-md-2 col-xs-12" style="margin-top: 15px">
            <?= Html::submitButton('BUSCAR SERVICIO', ['class' => 'btn btn-success']) ?>
        </div> 

        <div class="col-lg-12 col-md-12 col-xs-12"><hr class="linea"></div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
