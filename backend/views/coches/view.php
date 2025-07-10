<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Coches */

$this->title = Yii::$app->name.' | Datos del Vehículo : '.$model->marca.' ('.$model->modelo.')';
$this->params['breadcrumbs'][] = ['label' => 'Vehículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Datos del Vehículo : '.$model->marca.' ('.$model->modelo.')';
\yii\web\YiiAsset::register($this);

if ($model->estatus === 1) {
  $estatus = 'Activo';
} else {
  $estatus = 'Inactivo';
}

?>
<div class="coches-view">

    <div class="title-margin-new">
        <span style="display: inline">Vehículo: <?= $model->marca.' ('.$model->modelo.')'; ?></span> 
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="text-view">Cliente - Propietario</div>      
            <div class="info-view"><?= $model->cliente->nombre_completo; ?></div>
        </div>

        <div class="col-lg-4">
            <div class="text-view">Matrícula</div>
            <div class="info-view"><?= $model->matricula; ?></div> 
        </div>

        <div class="col-lg-12"><br></div>

        <div class="col-lg-4">
            <div class="text-view">Marca</div>
            <div class="info-view"><?= $model->marca; ?></div>        
        </div>    
        
        <div class="col-lg-4">
            <div class="text-view">Modelo</div>
            <div class="info-view"><?= $model->modelo ?></div> 
        </div>
        
        <div class="col-lg-4">
            <div class="text-view">Color</div>
            <div class="info-view"><?= $model->color ?></div> 
        </div>

        <div class="col-lg-12"><br></div>

        <div class="col-lg-4">
            <div class="text-view">Estado</div>
            <div class="info-view"><?= $estatus ?></div>        
        </div>

        <div align="right" class="col-lg-8" style="margin-top: 15px">
            <div class="form-group">
                <?= Html::button('Cerrar', ['class' => 'btn btn-success', 'data-dismiss' => 'modal']) ?>
            </div> 
        </div>

        <div class="col-lg-12"><br></div>

    </div>  
</div>
