<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Paradas */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Paradas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="paradas-view">

    <div class="title-margin-new">
        <span style="display: inline"><b>Desde : <?= date('d/m/Y',strtotime($model->fecha_inicio)) ?> Hasta : <?= date('d/m/Y',strtotime($model->fecha_fin)) ?></b></span> 
    </div>    

    <div class="row">
        <div class="col-lg-6">
            <div class="text-view">Fecha de Inicio</div>      
            <div class="info-view"><?= date('d/m/Y',strtotime($model->fecha_inicio)) ?></div>
        </div>
        <div class="col-lg-6">
            <div class="text-view">Hora de Inicio</div>
            <div class="info-view"><?= $model->hora_inicio ?></div> 
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-lg-6">
            <div class="text-view">Fecha Fin</div>
            <div class="info-view"><?= date('d/m/Y',strtotime($model->fecha_fin)) ?></div> 
        </div>
        <div class="col-lg-6">
            <div class="text-view">Hora de Fin</div>
            <div class="info-view"><?= $model->hora_fin ?></div> 
        </div>
    </div>

    <br>    

    <div class="row">
        <div class="col-lg-12" style="margin-top: 15px">
            <div class="text-view">Descripción / Motivo de Parada</div>
            <div class="info-view" style="min-height: 60px;"><?= $model->descripcion ?></div> 
        </div>

        <div align="right" class="col-lg-12" style="margin-top: 15px">
            <div class="form-group">
                <?= Html::button('Cerrar', ['class' => 'btn btn-success', 'data-dismiss' => 'modal']) ?>
            </div>
            
        </div>
    </div>

</div>
