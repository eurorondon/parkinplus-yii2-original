<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="solicitarf-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'solicitarf-form',
        ]);
    ?>

    <div class="row">
        <div class="col-lg-12">
            <p>Estimado Cliente. A continuación ingrese los datos solicitados y presione el botón enviar solicitud.</p>
            <p>Proximamente nuestro equipo estará contáctandolo para el envio de la factura solicitada.</p>
        </div>
    </div>

    <div class="row">
        <br>
        <div class="col-lg-4">
            <div class="form-group">
                <label>N° de Reserva</label>
                <input type="text" name="num_reserva" class="form-control" required autofocus>
            </div>
        </div>
    </div>

    <div class="row mt-25">
        <div class="col-lg-4">
            <div class="form-group">
                <label>NIF</label>
                <?= Html::input('text', 'nif', '', ['class' => 'form-control', 'required' => 'required']) ?>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="form-group">
                <label>Razón Social</label>
                <?= Html::input('text', 'razon_social', '', ['class' => 'form-control', 'required' => 'required']) ?>
            </div>
        </div>        
    </div>

    <div class="row mt-25">
        <div class="col-lg-4">
            <div class="form-group">
                <label>Código Postal</label>
                <input type="text" class="form-control" name="cod_postal" required min="5">
            </div>
        </div>
        <div class="col-lg-8">
            <div class="form-group">
                <label>Dirección</label>
                <textarea name="direccion" class="form-control" rows="2" required></textarea>
            </div>
        </div>        
    </div>

    <div class="row mt-25">
        <div class="col-lg-4">
            <div class="form-group">
                <label>Ciudad</label>
                <?= Html::input('text', 'ciudad', '', ['class' => 'form-control', 'required' => 'required']) ?>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label>Provincia</label>
                <?= Html::input('text', 'provincia', '', ['class' => 'form-control', 'required' => 'required']) ?>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group">
                <label>País</label>
                <?= Html::input('text', 'pais', '', ['class' => 'form-control', 'required' => 'required']) ?>
            </div>
        </div>                    
    </div>

    <div class="row mt-25">
        <div align="right" class="col-lg-12">
            <div class="form-group">
                <?= Html::submitButton('Enviar Solicitud', ['id' => 'boton-solicitud', 'class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>       

</div>