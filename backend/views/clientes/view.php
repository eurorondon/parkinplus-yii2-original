<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Clientes */

$this->title = Yii::$app->name.' | Datos del Cliente : '.$model->nombre_completo;
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Datos del Cliente : '.$model->nombre_completo;
\yii\web\YiiAsset::register($this);

if ($model->estatus === 1) {
    $estatus = 'Activo';
} else {
    $estatus = 'Inactivo';
}

?>
<div class="clientes-view">

    <div class="title-margin-new">
        <span style="display: inline">Cliente : <?= $model->nombre_completo; ?></span> 
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="text-view">Nombres y Apellidos</div>      
            <div class="info-view"><?= $model->nombre_completo; ?></div>
        </div>
        <div class="col-lg-6">
            <div class="text-view">Correo Electrónico</div>
            <div class="info-view"><?= $model->correo; ?></div> 
        </div>

        <div class="col-lg-12"><br></div> 

        <div class="col-lg-6">
            <div class="text-view">Tipo de Documento</div>
            <div class="info-view"><?= $model->tipo_documento; ?></div>        
        </div> 
        <div class="col-lg-6">
            <div class="text-view">N° de Documento</div>
            <div class="info-view"><?= $model->nro_documento; ?></div>        
        </div>

        <div class="col-lg-12"><br></div>

        <div class="col-lg-6">
            <div class="text-view">Móvil</div>
            <div class="info-view"><?= $model->movil ?></div> 
        </div>
        <div class="col-lg-6">
            <div class="text-view">Estado</div>
            <div class="info-view"><?= $estatus ?></div> 
        </div>

        <div align="right" class="col-lg-12" style="margin-top: 15px">
            <div class="form-group">
                <?= Html::button('Cerrar', ['class' => 'btn btn-success', 'data-dismiss' => 'modal']) ?>
            </div>
            
        </div>

    </div>        

</div>
