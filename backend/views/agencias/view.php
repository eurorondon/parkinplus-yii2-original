<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Agencias */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Agencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

if ($model->estatus === 1) {
    $estatus = 'ACTIVA';
} else {
    $estatus = 'INACTIVA';
}

?>
<div class="agencias-view">

    <div class="title-margin-new">
        <span style="display: inline"><?= $model->nombre; ?></span> 
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="text-view">Nombre de la Agencia</div>      
            <div class="info-view"><?= $model->nombre; ?></div>
        </div>
        <div class="col-lg-6">
            <div class="text-view">Dirección</div>
            <div class="info-view" style="min-height: 46px;"><?= $model->direccion; ?></div> 
        </div>
        <div class="col-lg-12"><br></div>

        <div class="col-lg-3">
            <div class="text-view">Teléfono</div>
            <div class="info-view"><?= $model->telefono ?></div>      
        </div>              

        <div class="col-lg-3">
            <div class="text-view">Móvil</div>
            <div class="info-view"><?= $model->movil ?></div>      
        </div> 

        <div class="col-lg-6">
            <div class="text-view">Contacto</div>
            <div class="info-view"><?= $model->contacto ?></div>      
        </div> 

        <div class="col-lg-12"><br></div>

        <div class="col-lg-3">
            <div class="text-view">Estátus</div>
            <div class="info-view"><?= $estatus ?></div> 
        </div>     

        <div align="right" class="col-lg-9" style="margin-top: 15px">
            <div class="form-group">
                <?= Html::button('Cerrar', ['class' => 'btn btn-success', 'data-dismiss' => 'modal']) ?>
            </div> 
        </div>

        <div class="col-lg-12"><br></div>
    </div> 

</div>
