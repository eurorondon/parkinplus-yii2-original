<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ListasPrecios */

$this->title = Yii::$app->name.' | Planes de Servicio : '.$model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Planes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->nombre;
\yii\web\YiiAsset::register($this);

if ($model->estatus === 1) {
  $estatus = 'Activo';
} else {
  $estatus = 'Inactivo';
}

?>
<div class="listas-precios-view">

  <div class="title-margin-new">
    <span style="display: inline"><?= $model->nombre; ?></span> 
  </div>

    <div class="row">
      <div class="col-lg-4" style="margin-top: 20px 0px">
        <div class="text-view">Nombre del Plan</div>      
        <div class="info-view"><?= $model->nombre; ?></div>
      </div>
      <div class="col-lg-2" style="margin-top: 20px 0px">
        <div class="text-view">Cuota por Día</div>
        <div class="info-view"><?= $model->agregado; ?> €</div> 
      </div>

      <div class="col-lg-2" style="margin-top: 20px 0px">
        <div class="text-view">Estado</div>
        <div class="info-view"><?= $estatus; ?></div> 
      </div>

      <div class="col-lg-4"></div>

      <div class="col-lg-12"><br><hr></div>

      <div class="col-lg-12 subtitulo-reserva" style="margin-top: 15px; margin-bottom: 20px ">Registro de Precios
      </div>

      <?php 
      $cant = count($modelRP);
      for ($i=0; $i < $cant ; $i++) { 
        ?> 
        <div class="col-lg-2" style="padding-left: 5px;">
          <div class="col-lg-4" style="padding-left: 10px; padding-right: 10px">
            <label class="num-price"><?= $modelRP[$i]->cantidad; ?></label>                
          </div>
          <div class="col-lg-8" style="padding-left: 0px ">
            <div class="info-price"><?= $modelRP[$i]->costo; ?></div>
          </div>
        </div>
      <?php } ?>

            <div class="col-lg-12"><hr style="margin-bottom: 5px; margin-top: 40px;"></div>

            <div align="right" class="col-lg-12" style="margin-top: 25px">
                <?= Html::a('Cancelar', ['listas-precios/index'], ['class' => 'btn btn-warning']) ?>
            </div>
      
    </div>
  </div>
