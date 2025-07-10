<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Reservas */

$char_color = empty($model->coche->matricula) ? '0' : strlen($model->coche->color);

if ($char_color < 3 ) {
    $color = 'N/D';
} else {
    $color = empty($model->coche->matricula) ? 'N/D' : $model->coche->color;
}

/*if (empty($model->coche->matricula)) {
    $model->coche->matricula = 'N/D';
}

if (empty($model->coche->marca)) {
    $model->coche->marca = 'N/D';
}

if (empty($model->coche->modelo)) {
    $model->coche->modelo = 'N/D';
}*/

if (empty($model->cliente->movil)) {
    $model->cliente->movil = 'N/D';
}

?>

<style type="text/css">
    .title-campo {
        margin-top: 8px;
        text-transform: uppercase;
    }
    .marco-campo {
        border:  1px solid #ccc;
        padding: 8px 12px;
        border-radius: 4px;
        font-weight: bold;
    }
</style>

<div class="reservas-ticket">
    
    <div class="row mt-4">
        <div class="col-lg-3">
            <div class="title-campo">N° de Reserva</div>
        </div>
        <div class="col-lg-3">
            <div class="marco-campo"><?= $model->nro_reserva ?></div>
        </div>

        <div class="col-lg-3">
            <div class="title-campo">Matrícula</div>
        </div>
        <div class="col-lg-3">
            <div class="marco-campo"><?= empty($model->coche->matricula) ? 'N/D' : $model->coche->matricula ?></div>
        </div>        
    </div>

    <br>
    
    <div class="row">
        <div class="col-lg-6">
            <div align="right" class="title-campo">Marca - Modelo</div>
        </div>
        <div class="col-lg-6">
            <div class="marco-campo"><?= empty($model->coche->matricula) ? 'N/D' : $model->coche->marca ?></div>
        </div>        
    </div>

    <br> 

    <div class="row">
        <div class="col-lg-6">
            <div align="right" class="title-campo">Ida</div>
        </div>
        <div class="col-lg-6">
            <div class="marco-campo"><?= date('d/m/Y', strtotime($model->fecha_entrada)).' - '.$model->hora_entrada ?></div>
        </div>        
    </div>

    <br> 

    <div class="row">
        <div class="col-lg-6">
            <div align="right" class="title-campo">Vuelta</div>
        </div>
        <div class="col-lg-6">
            <div class="marco-campo"><?= date('d/m/Y', strtotime($model->fecha_salida)).' - '.$model->hora_salida ?></div>
        </div>        
    </div> 

    <hr style="margin-top: 35px">

    <div class="row">
        <div class="col-lg-2">
            <div class="title-campo">Teléfono</div>
        </div>
        <div class="col-lg-4">
            <div class="marco-campo"><?= $model->cliente->movil ?></div>
        </div>       

        <div class="col-lg-3">
            <div class="title-campo"><b>Importe</b></div>
        </div>
        <div class="col-lg-3">
            <div class="marco-campo"><?= $model->monto_total ?> €</div>
        </div>        
    </div> 

    <hr>

    <div class="row mt-4">
        <div class="col-lg-4">
            <?= Html::a('Ticket - Parking', ['print-ticket-park', 'id' => $model->id], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>                  
        </div>
        <div align="right" class="col-lg-8">    
            <?= Html::a('Imprimir Ticket', ['print-ticket', 'id' => $model->id], ['class' => 'btn btn-success', 'target' => '_blank']) ?>      
            &nbsp;&nbsp;
            <?= Html::a('Imprimir Sobre', ['print-sobre', 'id' => $model->id], ['class' => 'btn btn-primary', 'target' => '_blank']) ?>
        </div>
    </div>
</div>
