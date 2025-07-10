<?php

use yii\helpers\Html;

$char_color = empty($model->coche->matricula) ? '0' : strlen($model->coche->color);

if ($char_color < 3 ) {
    $color = 'N/D';
} else {
    $color = empty($model->coche->matricula) ? 'N/D' : $model->coche->color;
}

/*if (empty($model->coche->matricula)) {
    $model->coche->matricula = 'N/D';
}

if (empty($model->coche->marca)) {
    $model->coche->marca = 'N/D';
}
*/
if (empty($model->cliente->movil)) {
    $model->cliente->movil = 'N/D';
}

if ($model->medio_reserva === 1) {
	$medio = 'phone.png';
}   
if ($model->medio_reserva === 2) {
	$medio = 'tags.png';
}
if ($model->medio_reserva === 3) {
	$medio = 'globe.png';
}
if ($model->medio_reserva === 4) {
	$medio = 'afiliado.png';
}

?>

<div style="position: absolute; font-size: 17px; font-weight: bolder; font-family: sans-serif;"><b><?= $model->nro_reserva ?></b></div>

<div style="position: absolute; top: 60px; font-size: 17px; font-weight: bolder; font-family: sans-serif;">
	<?= Html::img('@web/images/'.$medio, ['style'=> ['width' => '20px']]);?>	
</div>

<div align="right" style="text-transform: uppercase; font-size: 12px">Importe : <b><?= $model->monto_total ?> €</b></div>
<div align="right" style="text-transform: uppercase; font-size: 12px">Teléfono : <b><?= $model->cliente->movil ?></b></div>

<div style="position: absolute; font-size: 8px; top: 5.5cm; right: 1.2cm;">
	CIF. B88537345
</div>
<div align="center">
	<?= Html::img('@web/images/logo_ticket.jfif', ['style'=> ['width' => '5cm']]);?>
</div>

<hr style="margin: -15px 0px 0px 0px">

<table style="margin-top: -15px; margin-left: -3px;">
	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
			Matrícula
			<div align="center" style="width: 7cm; font-size: 36px"><?= empty($model->coche->matricula) ? 'N/D' : $model->coche->matricula ?></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" style="width: 3.5cm; text-transform: uppercase; padding-top: 10px">
			Marca - Modelo
			<div align="center" style="width: 3.5cm; font-size: 20px"><?= empty($model->coche->matricula) ? 'N/D' : $model->coche->marca." ".$model->coche->modelo ?></div>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px">
			Fecha de Entrada
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
			<div align="center" style="font-size: 22px"><?= $model->hora_entrada ?></div>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
			<div align="center" style="font-size: 22px"><?= date('d/m/Y', strtotime($model->fecha_entrada)) ?></div>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px">
			Fecha de Salida
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
			<div align="center" style="font-size: 22px"><?= $model->hora_salida ?></div>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
			<div align="center" style="font-size: 22px"><?= date('d/m/Y', strtotime($model->fecha_salida)) ?></div>
		</td>
	</tr>		
				
</table>

<hr style="margin: 5px 0px">
<?php if ($contS > 0) { ?>
<div style="margin-bottom: 12px"><b>INCLUYE:</b></div>


<?php for ($i=0; $i < count($servicios) ; $i++) { 
	if ($servicios[$i]->servicios->fijo == 2) { ?>
	<div style="margin-bottom: 10px; text-transform: uppercase;"><?= $servicios[$i]->servicios->nombre_servicio ?></div>
<?php } } ?>

<?php } ?>

<div style="position: absolute; bottom: 0.5cm; left: 0.5cm; right: 0.5cm">
	<?php if ($model['id_tipo_pago'] == 5) { ?>
		NOTA: LA RESERVA FUÉ PAGADA ONLINE
	<?php } ?>
	<hr style="margin: 0px 0px 10px 0px">
	<div style="font-size: 10px; text-transform: uppercase;">
		Asistencia en el Aeropuerto: <b>+34 603284800</b>
	</div>	
	<div style="margin-top: 5px; text-transform: uppercase; text-align: justify; font-size: 8px"><b>El parking no se hace responsable de la rotura de cristales. Daños mecanicos y objetos no declarados.</b></div>
	<div style="margin-top: 15px; text-align: center; font-size: 12px"><b>Gracias por Preferirnos</b></div>
</div>

