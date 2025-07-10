<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Facturas */

$this->title = Yii::$app->name.' | Modificar Factura';
$this->params['breadcrumbs'][] = ['label' => 'Facturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Factura N°: '.$model->nro_factura, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar Factura';
?>
<div class="facturas-update">

    <div class="title-margin">
        <h3 style="display: inline">Modificar Factura</h3>
        <span class="datos-factura">Factura: <?= $serie.'-'.$model->nro_factura ?></span> 
    </div>
	
	<div class="text-index">
	    <?= $this->render('_form', [
	        'model' => $model,
	        'serie' => $serie,
	        'proxima_factura' => $proxima_factura,
	        'servicios' => $servicios,
	        'seguro' => $seguro,
	        'tipos_pago' => $tipos_pago,
	    ]) ?>
	</div>

</div>
