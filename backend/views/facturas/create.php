<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Facturas */

$this->title = Yii::$app->name.' | Nueva Factura';
$this->params['breadcrumbs'][] = ['label' => 'Facturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Nueva Factura';
?>
<div class="facturas-create">

    <div class="title-margin-new">
        <span style="display: inline">Nueva Factura</span> 
        <span class="datos-factura">Factura: <?= $serie.'-'.$proxima_factura ?></span>
    </div>

	<?= $this->render('_form', [
	    'model' => $model,
	    'serie' => $serie,
	    'proxima_factura' => $proxima_factura,
	    'servicios' => $servicios,
	    'seguro' => $seguro,
	    'tipos_pago' => $tipos_pago,
	    'iva' => $iva,
	    'precio_diario' => $precio_diario,
	    'agregado' => $agregado,
	    'conceptos' => $conceptos,
	]) ?>

</div>
