<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

?>

<div class="fechas-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'descuento-form',
            'options' => [
                'autocomplete' => 'off',
            ],
        ]);
    ?>

    <div class="row">

        <div class="col-lg-5"> 
            <label class="control-label">N° de Reserva</label>
            <?= $form->field($model, 'nro_reserva')->widget(Select2::classname(), [
                'data' => $listaR,
                'options' => ['placeholder' => 'Selecccione'],
                'pluginOptions' => [
                    'allowClear' => true,
					'dropdownParent' => new yii\web\JsExpression('$("#descuento")')
                ],
            ])->label(false); ?>
        </div>

        <div class="col-lg-7"></div>

        <div class="col-lg-12"></div>

        <div class="col-lg-8">
            <?= $form->field($model, 'cupon', [
            'template' => '{label}<div class="input-group" id="reserva">{input}
            <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></div>{error}{hint}'
            ]); ?>
        </div>      

        <div class="col-lg-3"> 
            <?= $form->field($model, 'porcentaje_cupo', [
            'template' => '{label}<div class="input-group" id="reserva">{input}
            <span class="input-group-addon">%</div>{error}{hint}'
            ]); ?>
        </div>
      

        <div align="right" class="col-lg-12">
            <br>
            <div class="form-group">
                <?= Html::submitButton('Aplicar Descuento', ['class' => 'btn btn-success']) ?>
            </div>
            
        </div>

    </div>
    <?php ActiveForm::end(); ?>

</div>

  