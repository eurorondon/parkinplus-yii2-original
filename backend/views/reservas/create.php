<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Reservas */

$this->title = Yii::$app->name.' | Nueva Reserva';
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Nueva Reserva';
?>
<div class="reservas-create">
	<?= $this->render('_form', [
   'model' => $model,
   'proxima_reserva' => $proxima_reserva,
   'clientes' => $clientes,
   'coches' => $coches,
   'terminales' => $terminales,
   'servicios' => $servicios,
   'seguro' => $seguro,
   'precio_diario' => $precio_diario,
   'tipos_pago' => $tipos_pago, 
   'iva' => $iva, 
   'entrada' => $entrada,
   'salida' => $salida, 
   'hora_e' => $hora_e,
   'hora_s' => $hora_s,
   'medio' => $medio,
   'agencia' => $agencia,            
   'nocturno' => $nocturno,
   'precio_dia' => $precio_dia
 ]) ?>
</div>
