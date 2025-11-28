<?php
use yii\helpers\Html;
use common\models\Servicios;
use common\models\Reservas;
use common\models\Clientes;
use common\models\FacturasServicios;
use common\models\FacturasReserva;
use common\models\Configuracion;
use common\models\Conceptos;
use frontend\models\UserCliente;

$service = FacturasServicios::find()->where(['id_factura'=> $model->id])
	->orderBy(['id_servicio' => SORT_DESC])
	->all();

$datocliente = UserCliente::find()->where(['id_usuario' => $model->created_by])->one();

if ($datocliente == NULL) {
	$nombrecreador = 'Administración - Parking Plus';
} else {
	$usuario = Clientes::find()->where(['id' => $datocliente->id_cliente])->one();
	$nombrecreador = $usuario->nombre_completo;
}

$id_reserva = FacturasReserva::find()->where(['id_factura' => $model->id])->one();

$other_service = Conceptos::find()->where(['id_factura' => $model->id])->one();

if ($id_reserva != NULL) {
	$idr = $id_reserva->id_reserva;

	$datoR = Reservas::find()->where(['id' => $idr])->one();

	$movil = $datoR->cliente->movil;

	$fecha_e = $datoR->fecha_entrada;
	$fecha_e = date("d-m-Y", strtotime($fecha_e));

	$fecha_s = $datoR->fecha_salida;
	$fecha_s = date("d-m-Y", strtotime($fecha_s));
} else {
	$movil = '&nbsp; &nbsp; &nbsp;';	
}

$fecha = $model->created_at;
$fechaCompleta = strtotime($fecha);

$dia = date("d", $fechaCompleta);
$mes = date("m", $fechaCompleta);
$ayo = date("Y", $fechaCompleta);

?>

<?= Html::img('@web/images/logo_factura.png', ['class'=>'img-logo']);?>

<div class="fecha">
	<table>
		<tr>
			<td width="4cm" class="fecha-emision" colspan="3" style="background-color: #efefef;">Fecha de Emisión</td>
		</tr>			
		<tr>
			<td class="fecha-emision">Día</td>
			<td class="fecha-emision">Mes</td>
			<td class="fecha-emision">Año</td>
		</tr>	
		<tr>
			<td class="fecha-emision"><?= $dia; ?></td>
			<td class="fecha-emision"><?= $mes; ?></td>
			<td class="fecha-emision"><?= $ayo; ?></td>
		</tr>
	</table>
</div>
<div class="factura">
	<table>
		<tr>
			<td><b>FACTURA <?= $model->estatus === 0 ? 'CANCELADA N°' : 'N°'?></b></td>
		</tr>
		<tr>
			<td class="nro-factura"><?= $model->nro_factura ?></td>
		</tr>		
	</table>
</div>
<div class="linea" style="margin-top: 10px; margin-bottom: 10px"></div>

<div class="titleFR">Datos de Facturacón</div>

<table>
	<tr>
		<td width="10cm"><div class="texto">Nombre del Cliente :</div></td>
		<td width="5cm"><div class="texto">NIF :</div></td>
		<td width="5cm"><div class="texto">Móvil :</div></td>
	</tr>
</table>

<div class="title-section1" style="width: 8cm;"><?= $model->razon_social ?></div>
<div class="title-section2" style="width: 3cm;"><?= $model->nif ?></div>
<div class="title-section3" style="width: 3cm;"><?= $movil ?></div>

<br><br><br>
<table>
	<tr>
		<td width="15cm"><div class="texto">Domicilio del Cliente :</div></td>
		<td width="5cm"><div class="texto">Código Postal :</div></td>
	</tr>
</table>

<div class="title-section1" style="width: 12.5cm;"><?= $model->direccion.' - '.$model->provincia ?></div>
<div class="title-section3" style="width: 3cm;"><?= $model->cod_postal ?></div>

<div class="linea"></div>

<table>
	<tr>
		<td width="10cm"><div class="texto">Reserva realizada por :</div></td>
		<td width="10cm"><div class="texto">Forma de Pago :</div></td>
	</tr>
</table>

<div class="title-section1" style="width: 8cm;"><?= $nombrecreador ?></div>
<div class="title-section2" style="width: 6cm;"><?= $model->tipoPago->descripcion; ?></div>

<div class="linea" style="margin-bottom: 10px"></div>

<div class="titleFR">Servicios Contratados</div>

<table>
	<tr>
		<td class="title-table" align="center" width="1.5cm"><div class="texto">N°</div></td>
		<td class="title-table" align="center" width="16cm"><div class="texto">Descripción</div></td>
		<td class="title-table" align="center" width="2.5cm"><div class="texto">Total</div></td>
	</tr>
	<?php 
	    $items = 0;
	    foreach ($service as $s) {
            $datos = Servicios::find()->where(['id'=> $s->id_servicio])->one();

            $nombreServicio = $datos->nombre_servicio ?? '';
            $ptotal = $s->precio_total;

            $esReservaCero = (
                strcasecmp(trim($nombreServicio), 'Plaza reservada') === 0
                || strcasecmp(trim($nombreServicio), 'Parking reservada') === 0
            ) && ((float)$ptotal === 0.0);

            if ($esReservaCero) {
                continue;
            }

            $items = $items + 1;

        $buscaiva = Configuracion::find()->where(['tipo_campo' => 1])->one();
        $iva = $buscaiva->valor_numerico;

        $punitario = $s->precio_unitario;
        //$montoiva = $datos_reserva->monto_factura - $montosiniva;	    
	?>

	<tr>
		<td class="title-section-t" align="center" width="1.5cm">
			<div class="texto"><?= $items ?></div>
		</td>
		<td class="title-section-t" align="left" width="16cm">
			<div class="texto"><?= $datos->nombre_servicio ?></div>
			<?php if ($datos->fijo == 0) { 
				if ($id_reserva != NULL) { ?>
					<div class="texto-min"><?= $datos->descripcion ?> (DESDE: <?= $fecha_e ?>  - HASTA: <?= $fecha_s ?>)</div>
				<?php } else { ?>
					<div class="texto-min"><?= $datos->descripcion ?></div>
				<?php } } else { ?>
				<div class="texto-min"><?= $datos->descripcion ?></div>
			<?php } ?>
		</td>
		</td>		
		<td class="title-section-t" align="right" width="2.5cm">
			<div class="texto"><?= round($ptotal,2) ?> €</div>
		</td>
	</tr>
<?php } 
if (count($service) === 0) { ?>

	<tr>
		<td class="title-section-t" align="center" width="1.5cm">
			<div class="texto">1</div>
		</td>
		<td class="title-section-t" align="left" width="16cm">
			<div class="texto"><?= $other_service->descripcion ?></div>
		</td>
		</td>	
		<td class="title-section-t" align="right" width="2.5cm">
			<div class="texto"><?= $other_service->ptotal ?> €</div>
		</td>
	</tr>
<?php } ?>

</table>

<table>
	<tr>
		<td rowspan="3" class="title-section-t" width="16.7cm" style="padding: 15px">
			<div class="tit-obs">Observación :</div><br>
			<div class="texto-total"><?= $model->observacion ?></div>
		</td>
		<td align="right" class="title-table" width="4.9cm">
			<div class="texto-total">Subtotal</div>
		</td>
		<td align="right" class="title-section-t" width="3.1cm" style="border-top: 1px solid">
			<div class="texto-total"><?= round($model->monto_factura,2) ?> €</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="title-table" width="5cm">
			<div class="texto-total">I.V.A (21%)</div>
		</td>
		<td align="right" class="title-section-t" width="3cm">
			<div class="texto-total"><?= round($model->monto_impuestos,2) ?> €</div>
		</td>
	</tr>
	<tr>
		<td align="right" class="title-table" width="5cm">
			<div class="texto-total">Monto Total</div>
		</td>
		<td align="right" class="title-section-t" width="3cm" style="border-top: 2px solid">
			<div class="texto-total" style="font-weight: bold;"><?= $model->monto_total ?> €</div>
		</td>
	</tr>		
</table>

<div class="linea"></div>

<div class="aviso">
	<div class="texto-aviso">AVISO DE CONFIDENCIALIDAD</div>
	<div class="texto-av">A los efectos de lo establecido en la Ley de Protección de Datos (LOPD15/1999) y las (LSSIyCE 34/2002), garantizamos la confidencialidad de sus datos.</div>
</div>

<table class="otros">
	<tr>
		<td class="title-otros" align="center" width="19cm"><div class="texto-otros">Puede realizar su pago por Transferencia Bancaria al siguiente número de cuenta :</div></td>
	</tr>
	<tr>
		<td class="title-section-t" align="center" width="19cm">
			<div class="texto-min2">ES7201822082120201656721</div>
		</td>	
	</tr>
</table>

<div class="linea"></div>

<div class="direccionf">Marichal 4 Parking S.L<br>Calle Miguel de Cervantes 10. CP 28860. / NIF B88537345</div>


