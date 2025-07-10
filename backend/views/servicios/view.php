<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\ListasPrecios;

/* @var $this yii\web\View */
/* @var $model common\models\Servicios */

$this->title = Yii::$app->name.' | Datos del Servicio : '.$model->nombre_servicio;
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Datos del Servicio : '.$model->nombre_servicio;
\yii\web\YiiAsset::register($this);

if ($model->estatus === 1) {
    $estatus = 'ACTIVO';
} else {
    $estatus = 'INACTIVO';
}

if ($model->fijo === 0) {
    $modo = 'SERVICIO OPCIONAL';
}
if ($model->fijo === 1) {
    $modo = 'SERVICIO FIJO';
}
if ($model->fijo === 2) {
    $modo = 'SERVICIO EXTRA';
}

$lista = ListasPrecios::find()->where(['id' => $model->id_listas_precios])->one();

?>
<div class="servicios-view">

    <div class="title-margin-new">
        <span style="display: inline"><?= $model->nombre_servicio; ?></span> 
    </div>


        <div class="row">
            <div class="col-lg-6">
                <div class="text-view">Nombre del Servicio</div>      
                <div class="info-view"><?= $model->nombre_servicio; ?></div>
            </div>
            <div class="col-lg-6">
                <div class="text-view">Descripción</div>
                <div class="info-view" style="min-height: 46px;"><?= $model->descripcion; ?></div> 
            </div>
            <div class="col-lg-12"><br></div>
            <div class="col-lg-5">
                <div class="text-view">Tipo de Servicio</div>
                <div class="info-view"><?= $modo ?></div>      
            </div>              
            <?php if ($model->fijo != 0) { ?>
            <div class="col-lg-4">
                <div class="text-view">Costo del Servicio</div>
                <div class="info-view"><?= $model->costo; ?> €</div>        
            </div> 
            <?php } else { ?>
            <div class="col-lg-4">
                <div class="text-view">Plan Asociado</div>
                <div class="info-view"><?= $lista->nombre; ?></div>        
            </div>

            <div class="col-lg-3" style="margin-top: -20px">
                <?= Html::a('Ver Plan', [
                        'listas-precios/view',
                        'id'=> $lista->id,
                    ], 
                    [
                        'class'=>'btn btn-xs btn-default', 
                        'style' => 'margin-top:47px; padding: 0px 10px',
                        'target' => '_blank'
                    ]
                ) ?>
            </div>
            <?php } ?>
                       
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
