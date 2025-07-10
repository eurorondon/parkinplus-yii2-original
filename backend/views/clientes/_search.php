<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Clientes;

$clientes = ArrayHelper::map(Clientes::find()->orderBy('nombre_completo')->all(), 'nombre_completo', 'nombre_completo')

/* @var $this yii\web\View */
/* @var $model common\models\ClientesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clientes-search">
    <div class="row">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>

        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>Nombre y Apellidos</label>
            <?= $form->field($model, 'nombre_completo')->widget(Select2::classname(), [
                'data' => $clientes,
                'options' => ['placeholder' => 'Selecccione un Cliente'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>
        </div> 

        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>Correo Electrónico</label>
            <?= $form->field($model, 'correo')->label(false) ?>
        </div>  

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>N° de Documento</label>
            <?= $form->field($model, 'nro_documento')->label(false) ?>
        </div> 

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Móvil</label>
            <?= $form->field($model, 'movil')->label(false) ?>
        </div>

        <div align="right" class="col-lg-4 col-md-4 col-xs-12" style="margin-top: 15px">
            <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-warning']) ?>
        </div>

        <div align="right" class="col-lg-2 col-md-2 col-xs-12" style="margin-top: 15px">
            <?= Html::submitButton('BUSCAR CLIENTE', ['class' => 'btn btn-success']) ?>
        </div> 

        <div class="col-lg-12 col-md-12 col-xs-12"><hr class="linea"></div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
