<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

?>

<div class="fechas-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'estatus-form',
            'options' => [
                'autocomplete' => 'off',
            ],
        ]);
    ?>

    <div class="row">

        <div class="col-lg-5"> 
            <label class="control-label">N° de Reserva</label>
            <?= $form->field($model, 'id')->widget(Select2::classname(), [
                'data' => $listaR,
                'options' => ['name' => 'id_reserva', 'placeholder' => 'Selecccione'],
                'pluginOptions' => [
                    'allowClear' => true,
					'dropdownParent' => new yii\web\JsExpression('$("#cambiar_estado")')
                ],
            ])->label(false); ?>
        </div>

        <div class="col-lg-7">
            <label class="control-label">Estado de la Reserva</label>
            <?= $form->field($model, 'estatus')->widget(Select2::classname(), [
                'data' => $listaE,
                'options' => ['name' => 'estatus', 'placeholder' => 'Selecccione'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>        	
        </div>

        <div align="right" class="col-lg-12">
            <br>
            <div class="form-group">
                <?= Html::submitButton('Procesar', ['class' => 'btn btn-success']) ?>
            </div>
            
        </div>

    </div>
    <?php ActiveForm::end(); ?>

</div>