<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$Url = Url::to(['reservas/check']);

?>

<div class="fechas-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'reenvio-comprobante-form',
            'options' => [
                'autocomplete' => 'off',
            ],
        ]);
    ?>

    <div class="row">
        <div class="col-lg-4"> 
            <label class="control-label">N° de Reserva</label>
            <input type="text" class="form-control" name="nro_reserva" id="nro_reserva" disabled="true" value="<?= $reserva->nro_reserva ?>">
        </div>

        <div class="col-lg-8">
            <label class="control-label">Cliente</label>
            <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" disabled="true" value="<?= $reserva->cliente->nombre_completo ?>">         
        </div>

        <div class="col-lg-12"><br></div> 

        <div class="col-lg-7">  
            <input type="hidden" id="url" value="<?= $Url ?>">
            <label class="control-label">Correo Electrónico</label>
            <input type="text" class="form-control" name="correo" id="correo" value="<?= $reserva->cliente->correo ?>">
        </div>

        <div align="right" class="col-lg-5" style="margin-top: 15px">
            <div class="form-group">
                <?= Html::submitButton('Enviar Comprobante', ['class' => 'btn btn-success']) ?>
            </div>
            
        </div>
        <div class="col-lg-12"><br></div> 

    </div>
    <?php ActiveForm::end(); ?>

</div>
