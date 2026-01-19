<?php

use yii\helpers\Html;

$char_color = empty($model->coche->matricula) ? '0' : strlen($model->coche->color);

if ($char_color < 3) {
	$color = 'N/D';
} else {
	$color = empty($model->coche->matricula) ? 'N/D' : $model->coche->color;
}

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
if ($model->medio_reserva === 5) {
	$medio = 'organic.png';
}

$tiene_techo = "";

// Planes
$planes = [1 => 'Plan Estandar', 2 => 'Plan Premiun', 3 => 'Plan Priority', 4 => 'Plan Economic'];
$planName = $planes[$model->plan] ?? '';

// Flags
$IS_PREMIUM   = ((int)$model->plan === 2);
$IS_PRIORITY  = ((int)$model->plan === 3);
$TECHADO_ID   = 9;   // "Techado"
$PLAZA_RES_ID = 12;  // "Plaza reservada"

// Detecta si tiene Techado (icono), pero NO lo marca si el plan es Premium
foreach ($servicios as $servicie) {
	$id_s = $servicie->id_servicio ?? 0;
	if (!$IS_PREMIUM && (int)$id_s === $TECHADO_ID) {
		$tiene_techo = 'techado.png';
	}
}
?>

<div style="position: absolute; font-size: 17px; font-weight: bolder; font-family: sans-serif;"><b><?= Html::encode($model->nro_reserva) ?></b></div>
<div style="position: absolute; top: 43px; font-size: 12px; font-weight: bolder; font-family: sans-serif;">
	<b>FC: <?= date('d m Y', strtotime($model->created_at)) ?></b>
</div>
<div style="position: absolute;left: 50px; top: 55px; font-size: 12px; font-weight: bolder; font-family: sans-serif;">
	<b><?= date('H i', strtotime($model->created_at)) ?></b>
</div>

<div style="position: absolute; top: 80px; font-size: 17px; font-weight: bolder; font-family: sans-serif;">
	<?= Html::img('@web/images/' . $medio, ['style' => ['width' => '20px']]); ?>
</div>

<?php if ($tiene_techo !== ""): ?>
	<div style="position: absolute; top: 110px; font-size: 17px; font-weight: bolder; font-family: sans-serif;">
		<?= Html::img('@web/images/' . $tiene_techo, ['style' => ['width' => '25px']]); ?>
	</div>
<?php endif; ?>

<div align="right" style="text-transform: uppercase; font-size: 17px;">
	<span style="font-weight: normal;">Importe :</span> <b><?= Html::encode($model->monto_total) ?> €</b>
	<?php if ($model->cupon != NULL || $model->descuento == 'SI'): ?>
		<br><span style="font-size:9px; font-weight: normal;">(Descuento Aplicado)</span>
	<?php endif; ?>
</div>

<div align="right" style="text-transform: uppercase; font-size: 17px;">
	<span style="font-weight: normal;">Telf:</span> <b><?= Html::encode($model->cliente->movil) ?></b>
</div>

<table style="margin-top: 30px; margin-left: -3px;">
	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase;">
			Matrícula
			<div align="center" style="width: 7cm; font-size: 36px"><?= empty($model->coche->matricula) ? 'N/D' : Html::encode($model->coche->matricula) ?></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center" style="width: 3.5cm; text-transform: uppercase; padding-top: 10px">
			Marca - Modelo
			<div align="center" style="width: 3.5cm; font-size: 20px"><?= empty($model->coche->matricula) ? 'N/D' : Html::encode($model->coche->marca . " " . $model->coche->modelo) ?></div>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px">
			Fecha de Entrada
		</td>
	</tr>

	<tr>
		<td style="width: 7cm; text-transform: uppercase; padding-left: 50px;">
			<span align="center" style="font-size: 22px;"><?= date('d/m/Y', strtotime($model->fecha_entrada)) ?></span>
		</td>
		<td rowspan="2">
			<span style="font-size: 22px; margin-left: 15px;">
				<?php
				if (empty($model->terminal_entrada) || $model->terminal_salida == "AUN NO CONOZCO LA TERMINAL") {
					echo "T&nbsp;&nbsp;";
				} else {
					$term = explode(" ", $model->terminal_entrada);
					echo "T" . $term[1];
				}
				?>
			</span>
		</td>
	</tr>

	<tr>
		<td style="width: 7cm; text-transform: uppercase; padding-left: 50px;">
			<span align="center" style="font-size: 22px"><?= Html::encode($model->hora_entrada) ?></span>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center" style="width: 7cm; text-transform: uppercase; padding-top: 15px">
			Fecha de Salida
		</td>
	</tr>

	<tr>
		<td style="width: 7cm; text-transform: uppercase; padding-left: 50px;">
			<span align="center" style="font-size: 22px"><?= date('d/m/Y', strtotime($model->fecha_salida)) ?></span>
		</td>
		<td rowspan="2" style="padding-right: 25px">
			<span style="font-size: 22px; margin-left: 15px;">
				<?php
				if (empty($model->terminal_salida) || $model->terminal_salida == "AUN NO CONOZCO LA TERMINAL") {
					echo "T&nbsp;&nbsp;";
				} else {
					$term = explode(" ", $model->terminal_salida);
					echo "T" . $term[1];
				}
				?>
			</span>
		</td>
	</tr>

	<tr>
		<td style="width: 7cm; text-transform: uppercase; padding-left: 50px;">
			<span align="center" style="font-size: 22px"><?= Html::encode($model->hora_salida) ?></span>
		</td>
	</tr>

</table>

<hr style="margin: 5px 0px">
<div style="margin-bottom: 10px"><b><?= Html::encode($planName) ?></b></div>

<?php if ($contS > 0): ?>
	<div style="margin-bottom: 10px"><b>INCLUYE:</b></div>

	<?php for ($i = 0; $i < count($servicios); $i++):
		$srv = $servicios[$i]->servicios ?? null;
		if (!$srv) continue;

		$srvId   = isset($srv->id) ? (int)$srv->id : 0;
		$srvNombre = isset($srv->nombre_servicio) ? trim((string)$srv->nombre_servicio) : '';
		$srvFijo   = isset($srv->fijo) ? (int)$srv->fijo : 0;

		// Ocultar "Techado" (id 9 o nombre) cuando el plan es Premium
		if ($IS_PREMIUM && ($srvId === $TECHADO_ID || strcasecmp($srvNombre, 'Techado') === 0)) {
			continue;
		}
		// Ocultar "Plaza reservada" (id 12 o nombre) cuando el plan es Priority
		if ($IS_PRIORITY && ($srvId === $PLAZA_RES_ID || strcasecmp($srvNombre, 'Plaza reservada') === 0)) {
			continue;
		}

		if ($srvFijo === 2): ?>
			<div style="margin-bottom: 5px; text-transform: uppercase; font-size: 10px;">
				<?= Html::encode($srvNombre) ?>
			</div>
		<?php endif; ?>
	<?php endfor; ?>

<?php endif; ?>

<div style="position: absolute; bottom: 0.5cm; font-size:10px; margin-right: 20px;">
	<?php
	$tipoPagoDescripcion = '';
	if (isset($model->tipoPago) && isset($model->tipoPago->descripcion)) {
		$tipoPagoDescripcion = strtolower((string)$model->tipoPago->descripcion);
	}
	$pagoConfirmado = isset($model->pago_confirmado) && (int)$model->pago_confirmado === 1;
	$isOnline = ((int)$model['id_tipo_pago'] === 5) || (strpos($tipoPagoDescripcion, 'online') !== false);
	$isBizum = (strpos($tipoPagoDescripcion, 'bizum') !== false);
	$paymentLabel = $isOnline && $isBizum ? 'ONLINE/BIZUM' : ($isBizum ? 'BIZUM' : 'ONLINE');
	?>
	<?php if ($pagoConfirmado && ($isOnline || $isBizum)): ?>
		NOTA: LA RESERVA FUÉ PAGADA <?= $paymentLabel ?>
	<?php endif; ?>
	<hr style="margin: 10px 0px">
	Cliente: <?= Html::encode($model->cliente->nombre_completo) ?>
</div>
