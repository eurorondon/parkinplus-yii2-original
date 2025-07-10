<?php
use yii\helpers\Html;
use common\models\Servicios;
use common\models\Clientes;
use common\models\ReservasServicios;

$service = ReservasServicios::find()->where(['id_reserva'=> $model->nro_reserva])
	->orderBy(['tipo_servicio' => SORT_ASC])
	->all();

if ($model->factura_equipaje == 0) {
	$factura_equipaje = 'NO';
} else {
	$factura_equipaje = 'SI';
}

$fecha = $model->created_at;
$fechaCompleta = strtotime($fecha);

$fechaF = date("d-m-Y", $fechaCompleta);

$fecha_e = $model->fecha_entrada;
$fecha_e = date("d-m-Y", strtotime($fecha_e));

$fecha_s = $model->fecha_salida;
$fecha_s = date("d-m-Y", strtotime($fecha_s));

$hora_e = $model->hora_entrada;
$hora_s = $model->hora_salida;

foreach ($service as $s) {
	$datos = Servicios::find()->where(['id'=> $s->id_servicio])->one();
	if ($datos->fijo == 2) {
		$lavado = $datos->nombre_servicio;
	} else {
		$lavado = 'N/A';
	}
}

if ($model->ciudad_procedencia == NULL) {
	$ciudad = 'N/D';
} else {
	$ciudad = $model->ciudad_procedencia;
}

if ($model->nro_vuelo_regreso == NULL) {
	$vuelo = 'N/D';
} else {
	$vuelo = $model->nro_vuelo_regreso;
}

?>

<?= Html::img('@web/images/logo_factura.png', ['class'=>'img-logo']);?>
<div class="direccion1">Parkingplus.es<br>Marichal 4 Parking S.L<br>C/ Miguel de Cervantes 10, CP 28860.</div>
<div class="b">
	<div class="direccion">
		<div class="nreserva">N° de Reserva</div>
		<div class="nreserva2"><?= $model->nro_reserva ?></div>
	</div>
</div>

<hr style="margin-top: 8px;">

<table class="table-p">
	<tr>
		<td width="7cm">
			<table class="table-title" width="7cm">
				<tr><td class="title-datosp">Datos del Cliente</td></tr>
			</table>
			<table class="table-datos" width="7cm">
				<tr><td class="texto-title">Nombre Completo</td></tr>
				<tr><td class="titleR1"><?= $model->cliente->nombre_completo ?></td></tr>

				<tr><td class="texto-title">N° de Documento</td></tr>
				<tr><td class="titleR1"><?= $model->cliente->tipo_documento ?> - <?= $model->cliente->nro_documento ?></td></tr>					

				<tr><td class="texto-title">Teléfono</td></tr>
				<tr><td class="titleR1"><?= $model->cliente->movil ?></td></tr>
				
				<tr><td class="texto-title">Marca del Vehículo</td></tr>
				<tr><td class="titleR1"><?= $model->coche->marca ?></td></tr>	
				
				<tr><td class="texto-title">Modelo del Vehículo</td></tr>
				<tr><td class="titleR1"><?= $model->coche->modelo ?></td></tr>	
				
				<tr><td class="texto-title">Matrícula del Vehículo</td></tr>
				<tr><td class="titleR1" style="padding-bottom: 4px;"><?= $model->coche->matricula ?></td></tr>	
			</table>
		</td>
		<td width="13cm">
			<table class="table-title" width="13cm" style="margin-left: 20px">
				<tr><td class="title-datosr">Información de la Reserva</td></tr>
			</table>			
			<table class="table-datos" width="13cm" style="margin-left: 20px">
				<tr>
					<td width="6.5cm" class="texto-title" style="font-size: 18px; padding-top: 12px"><b>Fecha de Llegada</b></td>
					<td class="texto-title" style="font-size: 18px; padding-top: 12px"><b>Fecha de Salida</b></td>
				</tr>
				<tr>
					<td width="6.5cm" class="titleR1" style="font-size: 14px; padding-bottom: 15px"><?= $fecha_e ?> / <?= $hora_e ?></td>
					<td class="titleR1" style="font-size: 14px; padding-bottom: 15px"><?= $fecha_s ?> / <?= $hora_s ?></td>.
					
				</tr>
			</table>

			<table class="table-datos" width="13cm" style="margin-left: 20px">
				<tr>
					<td width="6.5cm" class="texto-title" style="padding-top: 20px">Terminal de Recogida</td>
					<td class="texto-title" style="padding-top: 20px">Terminal de Entrega</td>
				</tr>
				<tr>
					<td width="6.5cm" class="titleR1"><?= $model->terminal_entrada ?></td>
					<td class="titleR1"><?= $model->terminal_salida ?></td>
				</tr>

				<tr>
					<td width="6.5cm" class="texto-title">Ciudad de Procedencia</td>
					<td class="texto-title">N° de Vuelo</td>
				</tr>
				<tr>
					<td width="6.5cm" class="titleR1"><?= $ciudad ?></td>
					<td class="titleR1"><?= $vuelo ?></td>
				</tr>	
				
				<tr>
					<td width="6.5cm" class="texto-title">Factura Equipaje</td>
					<td class="texto-title">Servicio de Lavado</td>
				</tr>
				<tr>
					<td class="titleR1" style="padding-bottom: 14px"><?= $factura_equipaje ?></td>
					<td class="titleR1" style="padding-bottom: 14px"><?= $lavado ?></td>
				</tr>
				<?php if ($lavado == 'N/A') { ?>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<?php } ?>	
		
			</table>			
		</td>
	</tr>	
</table>

<table class="table-title" width="18.95cm">
	<tr><td class="title-datosr">Servicios Contratados</td></tr>
</table>

<table style="margin-bottom: 0px">

	<?php 
	    $total = 0;
	    foreach ($service as $s) {
	        if($s->id_servicio != 11) {
	    $datos = Servicios::find()->where(['id'=> $s->id_servicio])->one();
	    $total = $total + $s->precio_total; 
	?>

	<tr>
		<td colspan="2" width="20cm"><div class="titleR"><?= $datos->nombre_servicio ?></div></td>
		
	</tr>
	<?php if ($datos->fijo == 0) { ?>
		<tr>
			<td width="17cm"><div class="texto-des"><?= $datos->descripcion ?> (DESDE: <?= $fecha_e ?> - <?= $hora_e ?> / HASTA: <?= $fecha_s ?> - <?= $hora_s ?>)</div></td>
			<td align="right" width="3cm"><div class="titleR"><?= $s->precio_total ?> €</div></td>
		</tr>			
	<?php } else { ?>
	<tr>
		<td width="17cm"><div class="texto-des"><?= $datos->descripcion ?></div></td>
		<td align="right" width="3cm"><div class="titleR"><?= $s->precio_total ?> €</div></td>
	</tr>
	<?php } ?>	
	<tr>
		<td colspan="2" width="20cm"><hr style="margin-top: 0px; margin-bottom: 5px"></td>
	</tr>

<?php } } ?>

</table>

<table class="tableR" style="margin-bottom: 10px">
	<tr>
		<td width="4cm"><div class="textoR">Forma de Pago :</div></td>
		<td width="10cm"><div class="titleR"><?= $model->tipoPago->descripcion; ?></div></td>
		<td width="3cm"><div class="textoR">Total :</div></td>
		<td align="right" width="3cm"><div class="textoR"><?= $model->monto_total ?> €</div></td>
	</tr>
</table>


	<div class="borde1">
		<?= Html::img('@web/images/ir.png', ['class'=>'img-info']);?>
		<p class="data-info"><b>Al COMIENZO DE TU VIAJE:</b></p>
		<p align="justify">
			Llama al parking aproximadamente 20 minutos antes de llegar al aeropuerto.
			El teléfono al que debes llamar es el +34 603284800. Durante la llamada,
			una persona te confirmará el punto de encuentro. Al llegar, se realizará una
			inspección de tu vehículo.
		</p>

		<?= Html::img('@web/images/venir.png', ['class'=>'img-info']);?>
		<p class="data-info"><b>Al REGRESO DE TU VIAJE:</b></p>
		<p align="justify">
			Llama al parking para solicitar la entrega del vehículo. El teléfono al que
			debes llamar es el +34 603284800. Durante la llamada, una persona te
			confirmará el punto de encuentro. <b>Nota: </b>Todo servicio que se encuentre entre las 00:30 y
			03:45 tendra un incremento de 10€ por costo de nocturnidad. 			
		</p>

	</div>
	<div class="borde2">
		<p style="font-size: 11px; color: #961007"><b>RECUERDA</b></p>
		<p align="justify">
			Llamar con antelación y llegar puntual a la terminal, ya que es posible que solo puedas parar durante un corto periodo de tiempo.
		</p>

		<p style="font-size: 11px; color: #961007"><b>MODIFICACIÓN / CANCELACIÓN</b></p>
		<p align="justify">
			Se puede modificar/cancelar hasta 24 horas antes de la hora de llegada.
		</p>

		<?php if ($model->medio_reserva == 2) { ?>
			<p style="font-size: 11px; color: #961007; text-transform: uppercase; padding-top: 3px;">
				<b>Reserva realizada a través de la Agencia : <?= $model->agencia ?></b>
			</p>
		<?php } ?>		

	</div>



<div class="texto-inf1" style="text-align: justify; margin-top: 15px;">
	<p align="center" style="margin-top: 0px; text-indent: 20px"><b>Reservas :</b> +34 603282660 - <b>Asistencia en Aeropuerto :</b> +34 603284800 - <b>Email :</b> contacto@parkingplus.es</p>
	Sus datos personales serán usados para nuestra relación y poder prestarle nuestros servicios. Dichos datos son necesarios para poder relacionarnos con usted, lo que nos permite el uso de su información dentro de la legalidad. Asimismo, podrán tener conocimiento de su información aquellas entidades que necesiten tener acceso a la misma para que podamos prestarle nuestros servicios. Conservaremos sus datos durante nuestra relación y mientras nos obliguen las leyes aplicables. En cualquier momento puede dirigirse a nosotros para saber qué información tenemos sobre usted, rectificarla si fuese incorrecta y eliminarla una vez finalizada nuestra relación. También tiene derecho a solicitar el traspaso de su información a otra entidad (portabilidad). Para solicitar alguno de estos derechos, deberá realizar una solicitud escrita a nuestra dirección, junto con una fotocopia de su DNI:  Marichal 4 Parking S.L. Con dirección en C/ Miguel de Cervantes 10, CP 28860. En caso de que entienda que sus derechos han sido desatendidos, puede formular una reclamación en la Agencia Española de Protección de Datos (www.agpd.es).
</div>



