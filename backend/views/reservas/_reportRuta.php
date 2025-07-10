<?php
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Clientes;
use yii\helpers\Html;
use yii\helpers\Url;

$fecha = date('d-m-Y', strtotime($fecha));

?>

<?= Html::img('@web/images/logo_factura.png', ['style'=> ['width' => '250px']]);?>
<div style="position: absolute; top: 0.8cm; left: 15.2cm; font-size: 12px; text-transform: normal">Parkingplus.es<br>Marichal 4 Parking S.L<br>C/Pañeria 38 2do IZQ. CP 28037.<br>Madrid (Madrid).</div>

<br><br>

<div style="margin-top: -10px; font-size: 14px;	text-align: center;	font-weight: bold;
	text-transform: uppercase;">Planning de Reservas - Simplificado (<?= $fecha ?>)</div>

<hr>

<?php 
	$reservas_entrantes = $dataProvider->getModels();

	foreach ($reservas_entrantes as $rs1) {
		$horas_entradas[] = $rs1->hora_entrada;
	}

	$reservas_salientes = $dataProvider1->getModels();

	foreach ($reservas_salientes as $rs2) {
		$horas_salidas[] = $rs2->hora_salida;
	}

	$hora[0] = '00:00:00'; $hora[1] = '01:00:00'; $hora[2] = '02:00:00'; $hora[3] = '03:00:00';
	$hora[4] = '04:00:00'; $hora[5] = '05:00:00'; $hora[6] = '06:00:00'; $hora[7] = '07:00:00';
	$hora[8] = '08:00:00'; $hora[9] = '09:00:00'; $hora[10] = '10:00:00'; $hora[11] = '11:00:00';
	$hora[12] = '12:00:00'; $hora[13] = '13:00:00'; $hora[14] = '14:00:00'; $hora[15] = '15:00:00';
	$hora[16] = '16:00:00'; $hora[17] = '17:00:00'; $hora[18] = '18:00:00'; $hora[19] = '19:00:00';
	$hora[20] = '20:00:00'; $hora[21] = '21:00:00'; $hora[22] = '22:00:00'; $hora[23] = '23:00:00'; 
	$hora[24] = '24:00:00';

	for ($i=0; $i < 25 ; $i++) { 
		$h[$i] = 0;
		$h2[$i] = 0;
	}

	$total_entrada = 0;
	$total_salida = 0;
	$netos = 0;
	$color = "";

	foreach ($horas_entradas as $he) {
		for ($i=0; $i < 25; $i++) {
			if ($i != 24) {
				if ($he >= $hora[$i] && $he < $hora[$i+1]) $h[$i] = $h[$i]+1;
			} else {
				$h[$i] = $e0;
			}
			if ($i == 0) $h[$i] = 0; 
		}
	}

	foreach ($horas_salidas as $hs) {
		for ($i=0; $i < 25; $i++) {
			if ($i != 24) {
				if ($hs >= $hora[$i] && $hs < $hora[$i+1]) $h2[$i] = $h2[$i]+1;
			} else {
				$h2[$i] = $s0;
			}
			if ($i == 0) $h2[$i] = 0;
		}
	}
?>

<div class="table" style="margin-top: 0.5cm">
<table width="60%" class="table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<td width="20%" style="font-weight: bold; background-color: #ede9e9">HORAS</td>
			<td width="20%" align="center" style="font-weight: bold; background-color: #ede9e9;">ENTRADAS</td>
			<td width="20%" align="center" style="font-weight: bold; background-color: #ede9e9;">SALIDAS</td>
			<td width="20%" align="center" style="font-weight: bold; background-color: #ede9e9;">TOTAL</td>
		</tr>
	</thead>
	<tbody>
		<?php for ($i=0; $i < 25; $i++) { 
			$total[$i] = $h[$i]+$h2[$i];
			$total_entrada += $h[$i];
			$total_salida += $h2[$i];
			$netos += $total[$i];
			if ($i == 24) $hora[$i] = '00:00:00';
			

			if (($i%2) == 0) {
				$color = '#FFF';
			} else {
				$color = '#f3f3f3';
			}
			if ($h[$i] == 0) $valor1 = '';
			else $valor1 = $h[$i];

			if ($h2[$i] == 0) $valor2 = '';
			else $valor2 = $h2[$i];

			if ($total[$i] == 0) $valor3 = '';
			else $valor3 = $total[$i];						
		?>
		<tr>
			<?php if ($i > 0) { ?>
			<td style="background-color: <?=$color?>;"><b><?= $hora[$i] ?></b></td>
			<td align="center" style="font-size:13px; background-color: <?=$color?>;">
				<b><?= $valor1 ?></b>
			</td>
			<td align="center" style="font-size:13px; background-color: <?=$color?>;">
				<b><?= $valor2 ?></b>
			</td>
			<td align="center" style="font-size:13px; background-color: <?=$color?>;">
				<b><?= $valor3 ?></b>
			</td>
			<?php } ?>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<td style="background-color: #ede9e9"><b>TOTALES</b></td>
			<td style="font-size: 13px; background-color: #ede9e9" align="center">
				<b><?= $total_entrada ?></b>
			</td>
			<td style="font-size: 13px; background-color: #ede9e9" align="center">
				<b><?= $total_salida ?></b>
			</td>
			<td style="font-size: 13px; background-color: #ede9e9" align="center">
				<b><?= $netos ?></b>
			</td>
		</tr>
	</tfoot>
</table>
</div>






