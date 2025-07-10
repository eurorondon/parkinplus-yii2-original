<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\AgenciasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agencias-search">
    <div class="row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>

        <div class="col-lg-4 col-md-4 col-xs-12">
            <label>Nombre de la Agencia</label>
            <?= $form->field($model, 'nombre')->label(false) ?>
        </div>

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>Teléfono</label>
            <?= $form->field($model, 'telefono')->label(false) ?>
        </div>  

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>Móvil</label>
            <?= $form->field($model, 'movil')->label(false) ?>
        </div>         

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>Estado</label>
            <?= $form->field($model, 'estatus')->widget(Select2::classname(), [
                'data' => ['0' => 'Inactiva', '1' => 'Activa'],
                'options' => ['placeholder' => 'Selecccione'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>
        </div> 

        <div class="col-lg-2 col-md-2 col-xs-12"></div> 

        <div class="col-lg-4 col-md-4 col-xs-12">
            <label>Contacto</label>
            <?= $form->field($model, 'contacto')->label(false) ?>
        </div>

        <div class="col-lg-4 col-md-4 col-xs-12"></div>                     

        <div align="right" class="col-lg-2 col-md-2 col-xs-12" style="margin-top: 15px">
            <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-warning']) ?>
        </div>

        <div align="right" class="col-lg-2 col-md-2 col-xs-12" style="margin-top: 15px">
            <?= Html::submitButton('BUSCAR AGENCIA', ['class' => 'btn btn-success']) ?>
        </div> 

        <div class="col-lg-12 col-md-12 col-xs-12"><hr class="linea"></div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
