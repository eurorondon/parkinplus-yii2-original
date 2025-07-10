<?php

use yii\helpers\Html;

for ($i=0; $i <count($model); $i++) { 

	$char_color[$i] = strlen($model[$i]->coche->color);

	if ($char_color[$i] < 3 ) {
	    $color[$i] = 'N/D';
	} else {
	    $color[$i] = $model[$i]->coche->color;
	}

	if (empty($model[$i]->coche->matricula)) {
	    $model[$i]->coche->matricula = 'N/D';
	}

	if (empty($model[$i]->coche->marca)) {
	    $model[$i]->coche->marca = 'N/D';
	}

	if (empty($model[$i]->coche->modelo)) {
	    $model[$i]->coche->modelo = 'N/D';
	} else {
		$model[$i]->coche->modelo = substr($model[$i]->coche->modelo, 0, 7);
	}

	if (empty($model[$i]->cliente->movil)) {
	    $model[$i]->cliente->movil = 'N/D';
	}

	if ($model[$i]->medio_reserva === 1) {
		$medio[$i] = 'phone.png';
	}   
	if ($model[$i]->medio_reserva === 2) {
		$medio[$i] = 'tags.png';
	}
	if ($model[$i]->medio_reserva === 3) {
		$medio[$i] = 'globe.png';
	}
	if ($model[$i]->medio_reserva === 4) {
		$medio[$i] = 'afiliado.png';
	}
}

?>

<?php
	for ($i=0; $i <count($model); $i++) { ?> 
		<table style="margin-top: 30px; margin-left: -3px;">
			<tr>
				<td align="center" style="width: 7cm; text-transform: uppercase;">
					Matrícula
				</td>
			</tr>
			<tr>
				<td align="center" style="padding-top: 15px;">
					<div style="width: 7cm; font-size: 28px;"><?= $model[$i]->coche->matricula ?></div>
				</td>
			</tr>

			<tr>
				<td align="center" style="width: 7cm; text-transform: uppercase; padding-top: 25px;">
					Fecha de Salida
				</td>
			</tr>

			<tr>
				<td align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px;">
					<span style="font-size: 30px"><?= date('d/m/Y', strtotime($model[$i]->fecha_salida)) ?></span>
				</td>		
			</tr>

			<tr>
				<td align="center" style="width: 7cm; text-transform: uppercase;">
					<span style="font-size: 30px"><?= $model[$i]->hora_salida ?></span>
				</td>
			</tr>				
		</table>
	<?php }
?>

