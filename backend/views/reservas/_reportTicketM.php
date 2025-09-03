<?php

use yii\helpers\Html;

$planes = [1 => 'Plan Estandar', 2 => 'Plan Premiun', 3 => 'Plan Priority'];

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

	if (!empty($model[$i]->coche->modelo)) {
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

	<div style="margin-top: 1cm;font-size: 17px; font-weight: bolder; font-family: sans-serif;"><b><?= $model[$i]->nro_reserva ?></b></div>

	<div style="margin-top: 0.5cm;">
		<?= Html::img('@web/images/' . $medio[$i], ['style' => ['width' => '20px']]); ?>
	</div>

	<div style="margin-top: -1.6cm; font-size: 17px; font-weight: bolder; font-family: sans-serif;">
	</div>

	<div align="right" style="text-transform: uppercase; font-size: 12px">Importe : <b><?= $model[$i]->monto_total ?> €</b></div>
	<div align="right" style="text-transform: uppercase; font-size: 12px">Teléfono : <b><?= $model[$i]->cliente->movil ?></b></div>

	<div align="center">
		<?= Html::img('@web/images/logo_ticket.jfif', ['style' => ['width' => '5cm']]); ?>
	</div>

	<div align="right" style="font-size: 8px; margin-right: .7cm; margin-top: -0.8cm;">
		CIF. B88537345
	</div>

	<hr style="margin: 15px 0px 0px 0px">

	<table style="margin-top: 15px; margin-left: -3px;">
		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
				Matrícula
				<div align="center" style="width: 7cm; font-size: 36px"><?= $model[$i]->coche->matricula ?></div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center" style="width: 3.5cm; text-transform: uppercase; padding-top: 10px">
				Marca - Modelo
				<div align="center" style="width: 3.5cm; font-size: 20px"><?= $model[$i]->coche->marca . ' ' . $model[$i]->coche->modelo ?></div>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px">
				Fecha de Entrada
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
				<div align="center" style="font-size: 22px"><?= $model[$i]->hora_entrada ?></div>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
				<div align="center" style="font-size: 22px"><?= date('d/m/Y', strtotime($model[$i]->fecha_entrada)) ?></div>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px">
				Fecha de Salida
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
				<div align="center" style="font-size: 22px"><?= $model[$i]->hora_salida ?></div>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
				<div align="center" style="font-size: 22px"><?= date('d/m/Y', strtotime($model[$i]->fecha_salida)) ?></div>
			</td>
		</tr>

	</table>

	<hr style="margin: 6px 0px">

	<div><b><?= Html::encode($planName[$i]) ?></b></div>

	<?php if ($contS[$i] > 0) { ?>
		<div style="height: 3cm">
			<b>INCLUYE:</b><br />
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
					echo strtoupper($srvNombre) . "<br />";
				}
			}
			?>
		</div>
	<?php } else { ?>
		<div style="height: 3cm">
		</div>
	<?php } ?>


	<div>
		<?php if ($model[$i]['id_tipo_pago'] == 5) { ?>
			NOTA: LA RESERVA FUÉ PAGADA ONLINE
		<?php } ?>
		<hr style="margin: 0% 0% 2% 0%">
		<div style="font-size: 10px; text-transform: uppercase;">
			Asistencia en el Aeropuerto: <b>+34 603284800</b>
		</div>
		<div style="margin-top: 5px; text-transform: uppercase; text-align: justify; font-size: 8px"><b>El parking no se hace responsable de la rotura de cristales. Daños mecanicos y objetos no declarados.</b></div>
		<div style="margin-top: 5px; text-align: center; font-size: 12px"><b>Gracias por Preferirnos</b></div>
	</div>
<?php }
?>