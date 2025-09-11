<?php

use yii\helpers\Html;

$planes = [1 => 'Plan Estandar', 2 => 'Plan Premiun', 3 => 'Plan Priority', 4 => 'Plan Economic'];

for ($i = 0; $i < count($model); $i++) {

	$char_color[$i] = strlen($model[$i]->coche->color);

	if ($char_color[$i] < 3) {
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
	if ($model[$i]->medio_reserva === 5) {
		$medio[$i] = 'organic.png';
	}

	$planName[$i] = $planes[(int)$model[$i]->plan] ?? '';
}

?>

<?php
for ($i = 0; $i < count($model); $i++) {
	$IS_PREMIUM   = ((int)$model[$i]->plan === 2);
	$IS_PRIORITY  = ((int)$model[$i]->plan === 3);
	$TECHADO_ID   = 9;
	$PLAZA_RES_ID = 12;
?>

	<div style="margin-top: 1cm;font-size: 17px; font-weight: bolder; font-family: sans-serif;">
		<b><?= $model[$i]->nro_reserva ?></b>
	</div>

	<div style="margin-top: 1cm;">
		<?= Html::img('@web/images/' . $medio[$i], ['style' => ['width' => '20px']]); ?>
	</div>

	<div style="margin-top: -1.6cm; font-size: 17px; font-weight: bolder; font-family: sans-serif;">
	</div>

	<div style="position: absolute; top: 43px; font-size: 12px; font-weight: bolder; font-family: sans-serif;">
		<b>FC: <?= date('d m Y', strtotime($model[$i]->created_at)) ?></b>
	</div>
	<div style="position: absolute;left: 50px; top: 55px; font-size: 12px; font-weight: bolder; font-family: sans-serif;">
		<b><?= date('H i', strtotime($model[$i]->created_at)) ?></b>
	</div>

	<div align="right" style="text-transform: uppercase; font-size: 12px">Importe : <b><?= $model[$i]->monto_total ?> €</b>
		<?php if ($model[$i]->cupon != NULL || $model[$i]->descuento == 'SI') { ?>
			<br><span style="font-size:9px;">(Descuento Aplicado)</span>
		<?php } ?>
	</div>
	<div align="right" style="text-transform: uppercase; font-size: 12px">Teléfono :
		<b><?= $model[$i]->cliente->movil ?></b>
	</div>


	<table style="margin-top: 30px; margin-left: -3px;">
		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
				Matrícula
				<div align="center" style="width: 7cm; font-size: 36px"><?= $model[$i]->coche->matricula ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" style="width: 3.5cm; text-transform: uppercase; padding-top: 10px">
				Marca - Modelo
				<div align="center" style="width: 3.5cm; font-size: 20px">
					<?= $model[$i]->coche->marca . " " . $model[$i]->coche->modelo ?>
				</div>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px">
				Fecha de Entrada
			</td>
		</tr>

		<tr>
			<td style="width: 7cm; text-transform: uppercase; padding-left: 50px;">
				<span align="center"
					style="font-size: 22px;"><?= date('d/m/Y', strtotime($model[$i]->fecha_entrada)) ?></span>
			</td>
			<td rowspan="2">
				<span style="font-size: 22px; margin-left: 15px;">
					<?php
					if (empty($model[$i]->terminal_entrada)) {
						echo "T&nbsp;&nbsp;";
					} else {
						$term = explode(" ", $model[$i]->terminal_entrada);
						echo "T" . $term[1];
					}
					?>
				</span>
			</td>
		</tr>

		<tr>
			<td style="width: 7cm; text-transform: uppercase; padding-left: 50px;">
				<span align="center" style="font-size: 22px"><?= $model[$i]->hora_entrada ?></span>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px">
				Fecha de Salida
			</td>
		</tr>

		<tr>
			<td style="width: 7cm; text-transform: uppercase; padding-left: 50px;">
				<span align="center"
					style="font-size: 22px"><?= date('d/m/Y', strtotime($model[$i]->fecha_salida)) ?></span>
			</td>
			<td rowspan="2" style="padding-right: 25px">
				<span style="font-size: 22px; margin-left: 15px;">
					<?php
					if (empty($model[$i]->terminal_salida)) {
						echo "T&nbsp;&nbsp;";
					} else {
						$term = explode(" ", $model[$i]->terminal_salida);
						echo "T" . $term[1];
					}
					?>
				</span>
			</td>
		</tr>

		<tr>
			<td style="width: 7cm; text-transform: uppercase; padding-left: 50px;">
				<span align="center" style="font-size: 22px"><?= $model[$i]->hora_salida ?></span>
			</td>
		</tr>

	</table>

	<hr style="margin: 5px 0px">
	<div style="margin-bottom: 10px"><b><?= Html::encode($planName[$i]) ?></b></div>
	<?php if ($contS[$i] > 0) { ?>
		<div style="margin-bottom: 10px"><b>INCLUYE:</b></div>

		<?php
		for ($l = 0; $l < count($servicios[$i]); $l++) {
			$srv = $servicios[$i][$l]->servicios ?? null;
			if (!$srv) {
				continue;
			}

			$srvId     = isset($srv->id) ? (int)$srv->id : 0;
			$srvNombre = isset($srv->nombre_servicio) ? trim((string)$srv->nombre_servicio) : '';
			$srvFijo   = isset($srv->fijo) ? (int)$srv->fijo : 0;

			if ($IS_PREMIUM && ($srvId === $TECHADO_ID || strcasecmp($srvNombre, 'Techado') === 0)) {
				continue;
			}
			if ($IS_PRIORITY && ($srvId === $PLAZA_RES_ID || strcasecmp($srvNombre, 'Plaza reservada') === 0)) {
				continue;
			}

			if ($srvFijo == 2) {
		?>
				<div style="margin-bottom: 5px; text-transform: uppercase; font-size: 10px;">
					<?= $srvNombre ?>
				</div>

		<?php }
		}
		?>

	<?php } ?>

	<?php
	if ($contS[$i] == 0) {
		echo "<br><br><br><br><br><br><br><br><br><br>";
	}
	if ($contS[$i] == 1) {
		echo "<br><br><br><br><br><br><br>";
	}
	if ($contS[$i] == 2) {
		echo "<br><br><br><br><br>";
	}
	?>
	<div style="position: absolute; bottom: 0.5cm; font-size:10px; margin-right:20px">
		<?php if ($model[$i]['id_tipo_pago'] == 5) { ?>
			NOTA: LA RESERVA FUÉ PAGADA ONLINE
		<?php } ?>
		<hr style="margin: 2% 0%">
		Cliente: <?= $model[$i]->cliente->nombre_completo ?>
	</div>

<?php }
?>