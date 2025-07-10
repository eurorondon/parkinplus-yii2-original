<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Coches */
/* @var $form yii\widgets\ActiveForm */

$estatus = [ 1 => 'Activo', 2 => 'Inactivo'];

?>

<div class="coches-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-lg-8">
            <?= $form->field($model, 'id_cliente')->widget(Select2::classname(), [
                'data' => $listaClientes,
                'options' => ['placeholder' => 'Selecccione un Cliente o Propietario'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-lg-4"></div>

        <div class="col-lg-12"><br></div>

        <div class="col-lg-4">
            <?= $form->field($model, 'matricula')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'marca')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-4">
            <?= $form->field($model, 'modelo')->textInput(['maxlength' => true]) ?>
        </div>        

        <div class="col-lg-12"><br></div>

        <div class="col-lg-4">
            <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>
        </div>

        <div class="col-lg-4">
            <?= $form->field($model, 'estatus')->widget(Select2::classname(), [
                'data' => $estatus,
                'options' => ['placeholder' => 'Selecccione'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>
        <div class="col-lg-4"></div>

        <div align="right" class="col-lg-12" style="margin-top: 10px">
            <div class="form-group">
                <?= Html::submitButton('Guardar Datos', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>
