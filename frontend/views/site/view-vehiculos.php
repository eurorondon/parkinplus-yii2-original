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

$fecha_registro = date('d-m-Y',strtotime($model->created_at));

?>
<div class="coches-view">
    <div class="row">
        <div class="col-lg-12">
            <div class="mgen">
                <div class="panel panel-default panel-admin" style="padding: 10px; margin-bottom: 0px">
                    <div class="panel-heading caja-panel">Información del Vehículo</div>
                    <div class="panel-body"> 

                        <div class="col-lg-4">
                            <div class="text-view">Nombre del Propietario</div>      
                            <div class="info-view"><?= $model->cliente->nombre_completo; ?></div>
                        </div>

                        <div class="col-lg-2">
                            <div class="text-view">Matrícula</div>
                            <div class="info-view"><?= $model->matricula; ?></div> 
                        </div>

                        <div class="col-lg-3">
                            <div class="text-view">Marca</div>
                            <div class="info-view"><?= $model->marca; ?></div>        
                        </div>    
                        
                        <div class="col-lg-3">
                            <div class="text-view">Modelo</div>
                            <div class="info-view"><?= $model->modelo ?></div> 
                        </div>

                        <div class="col-lg-12" style="margin-top: 35px"></div>
                        
                        <div class="col-lg-2">
                            <div class="text-view">Color</div>
                            <div class="info-view"><?= $model->color ?></div> 
                        </div>

                        <div class="col-lg-2">
                            <div class="text-view">Estado</div>
                            <div class="info-view"><?= $estatus ?></div>        
                        </div>

                        <div class="col-lg-2">
                            <div class="text-view">Fecha de Registro</div>
                            <div class="info-view"><?= $fecha_registro ?></div>        
                        </div>                    

                        <div class="col-lg-12" style="margin-top: 35px"><hr></div>

                        <div class="col-lg-10"></div>
                        <div class="col-md-2" style="padding-right: 0px">
                            <p align="right">
                              <?= Html::a('cancelar', ['vehiculos'], ['class' => 'btn btn-warning btn-block']) ?> 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>                
    </div>  
</div>
