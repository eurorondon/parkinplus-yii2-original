<?php

use yii\helpers\Html;
use common\models\Facturas;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;

$clientes = ArrayHelper::map(Facturas::find()->orderBy('razon_social')->all(), 'razon_social', 'razon_social')

/* @var $this yii\web\View */
/* @var $model common\models\FacturasSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="facturas-search">
    <div class="row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>

        <div class="col-lg-4 col-md-4 col-xs-12">
            <label>Nombre del Cliente / Razón Social</label>
            <?= $form->field($model, 'razon_social')->widget(Select2::classname(), [
                'data' => $clientes,
                'options' => ['placeholder' => 'Selecccione un Cliente o Propietario'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>
        </div>        

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>Estatus</label>
            <?= $form->field($model, 'estatus')->widget(Select2::classname(), [
                'data' => ['0' => 'Canceladas', '1' => 'Activas', '2' => 'Pendientes'],
                'options' => ['placeholder' => 'Selecccione estatus'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>
        </div>

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>Fecha de Factura</label>
            <?= $form->field($model, 'created_at')->widget(DatePicker::classname(), [
                'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                'language' => 'es',
                'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',                                      
                ]
            ])->label(false); ?>
        </div>        

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>N° de Factura</label>
            <?= $form->field($model, 'nro_factura')->label(false) ?>
        </div>

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>NIF</label>
            <?= $form->field($model, 'nif')->label(false) ?>
        </div>

        <div class="col-lg-2"></div>        
    
        <div class="col-lg-4 col-md-4"></div> 

        <div align="right" class="col-lg-4 col-md-4 col-xs-12" style="margin-top: 15px">
            <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-warning']) ?>
        </div>

        <div align="right" class="col-lg-2 col-md-2 col-xs-12" style="margin-top: 15px">
            <?= Html::submitButton('BUSCAR FACTURA', ['class' => 'btn btn-success']) ?>
        </div> 

        <div class="col-lg-12 col-md-12 col-xs-12"><hr class="linea"></div>     

        <?php ActiveForm::end(); ?>
    </div>
</div>
