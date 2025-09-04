<?php

use yii\helpers\Html;
use common\models\Servicios;
use common\models\Clientes;
use common\models\ReservasServicios;

$service = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])
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

$lavado = 'N/A';

foreach ($service as $s) {
        $datos = Servicios::find()->where(['id' => $s->id_servicio])->one();
        if ($datos->fijo == 2) {
                $lavado = $datos->nombre_servicio;
                break;
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

<?= Html::img('@web/images/logo_factura.png', ['class' => 'img-logo']); ?>
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
				<tr>
					<td class="title-datosp">Datos del Cliente</td>
				</tr>
			</table>
			<table class="table-datos" width="7cm">
				<tr>
					<td class="texto-title">Nombre Completo</td>
				</tr>
				<tr>
					<td class="titleR1"><?= $model->cliente->nombre_completo ?></td>
				</tr>

				<tr>
					<td class="texto-title">Teléfono</td>
				</tr>
				<tr>
					<td class="titleR1"><?= $model->cliente->movil ?></td>
				</tr>

				<tr>
					<td class="texto-title">Marca - Modelo</td>
				</tr>
				<tr>
					<td class="titleR1"><?= $model->coche->marca ?></td>
				</tr>

				<tr>
					<td class="texto-title">Matrícula</td>
				</tr>
				<tr>
					<td class="titleR1" style="padding-bottom: 4px;"><?= $model->coche->matricula ?></td>
				</tr>
			</table>
		</td>
		<td width="13cm">
			<table class="table-title" width="13cm" style="margin-left: 20px">
				<tr>
					<td class="title-datosr">Información de la Reserva</td>
				</tr>
			</table>
			<table class="table-datos" width="13cm" style="margin-left: 20px">
				<tr>
					<td width="6.5cm" class="texto-title" style="font-size: 18px; padding-top: 12px"><b>Fecha de Entrega</b></td>
					<td class="texto-title" style="font-size: 18px; padding-top: 12px"><b>Fecha de Recogida</b></td>
				</tr>
				<tr>
					<td width="6.5cm" class="titleR1" style="font-size: 14px; padding-bottom: 15px"><?= $fecha_e ?> / <?= $hora_e ?></td>
					<td class="titleR1" style="font-size: 14px; padding-bottom: 15px"><?= $fecha_s ?> / <?= $hora_s ?></td>.

				</tr>
			</table>

			<table class="table-datos" width="13cm" style="margin-left: 20px">
				<tr>
					<td width="6.5cm" class="texto-title" style="padding-top: 5px">Terminal de Recogida</td>
					<td class="texto-title" style="padding-top: 5px">Terminal de Entrega</td>
				</tr>
				<tr>
					<td width="6.5cm" class="titleR1"><?= $model->terminal_entrada ?></td>
					<td class="titleR1"><?= $model->terminal_salida ?></td>
				</tr>

				<tr>
					<td width="6.5cm" class="texto-title">Ciudad de Procedencia</td>
					<td class="texto-title">Servicio de Lavado</td>
				</tr>
				<tr>
					<td width="6.5cm" class="titleR1"><?= $ciudad ?></td>
					<td class="titleR1"><?= $lavado ?></td>
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
	<tr>
		<td class="title-datosr">Servicios Contratados</td>
	</tr>
</table>

<table style="margin-bottom: 0px">

	<?php
        $total = 0;
        foreach ($service as $s) {
                if ($s->id_servicio == 12 && (int)$s->precio_total === 0) {
                        continue;
                }
                $datos = Servicios::find()->where(['id' => $s->id_servicio])->one();
                $total = $total + $s->precio_total;
        ?>

		<tr>
			<td colspan="2" width="20cm">
				<div class="titleR"><?= $datos->nombre_servicio ?></div>
			</td>

		</tr>
		<?php if ($datos->fijo == 0) { ?>
			<tr>
				<td width="17cm">
					<div class="texto-des"><?= $datos->descripcion ?> (DESDE: <?= $fecha_e ?> - <?= $hora_e ?> / HASTA: <?= $fecha_s ?> - <?= $hora_s ?>)</div>
				</td>
				<td align="right" width="3cm">
					<div class="titleR"><?= $s->precio_total ?> €</div>
				</td>
			</tr>
		<?php } else { ?>
			<tr>
				<td width="17cm">
					<div class="texto-des"><?= $datos->descripcion ?></div>
				</td>
				<td align="right" width="3cm">
					<div class="titleR"><?= $s->precio_total ?> €</div>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<td colspan="2" width="20cm">
				<hr style="margin-top: 0px; margin-bottom: 5px">
			</td>
		</tr>

	<?php } ?>

</table>

<table class="tableR" style="margin-bottom: 10px">
	<tr>
		<td width="4cm">
			<div class="textoR">Forma de Pago :</div>
		</td>
		<td width="10cm">
			<div class="titleR"><?= $model->tipoPago->descripcion; ?></div>
		</td>
		<td width="3cm">
			<div class="textoR">Total :</div>
		</td>
		<td align="right" width="3cm">
			<div class="textoR"><?= $model->monto_total ?> €</div>
		</td>
	</tr>
</table>


<div class="borde1">
	<?= Html::img('@web/images/ir.png', ['class' => 'img-info']); ?>
	<p class="data-info"><b>Al COMIENZO DE TU VIAJE:</b></p>
	<p align="justify">
		Llama al parking aproximadamente 20 minutos antes de llegar al aeropuerto.
		El teléfono al que debes llamar es el +34 603284800. Durante la llamada,
		una persona te confirmará el punto de encuentro. Al llegar, se realizará una
		inspección de tu vehículo.
	</p>

	<?= Html::img('@web/images/venir.png', ['class' => 'img-info']); ?>
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
	<p align="justify" style="padding-bottom: 44px">
		Se puede modificar/cancelar hasta 24 horas antes de la hora de llegada.
	</p>

</div>



<div class="texto-inf1" style="text-align: justify; margin-top: 15px;">
	<p align="center" style="margin-top: 0px; text-indent: 20px"><b>Reservas :</b> +34 603282660 - <b>Asistencia en Aeropuerto :</b> +34 603284800 - <b>Email :</b> contacto@parkingplus.es</p>
	Sus datos personales serán usados para nuestra relación y poder prestarle nuestros servicios. Dichos datos son necesarios para poder relacionarnos con usted, lo que nos permite el uso de su información dentro de la legalidad. Asimismo, podrán tener conocimiento de su información aquellas entidades que necesiten tener acceso a la misma para que podamos prestarle nuestros servicios. Conservaremos sus datos durante nuestra relación y mientras nos obliguen las leyes aplicables. En cualquier momento puede dirigirse a nosotros para saber qué información tenemos sobre usted, rectificarla si fuese incorrecta y eliminarla una vez finalizada nuestra relación. También tiene derecho a solicitar el traspaso de su información a otra entidad (portabilidad). Para solicitar alguno de estos derechos, deberá realizar una solicitud escrita a nuestra dirección, junto con una fotocopia de su DNI: Marichal 4 Parking S.L. Con dirección en C/ Miguel de Cervantes 10, CP 28860. En caso de que entienda que sus derechos han sido desatendidos, puede formular una reclamación en la Agencia Española de Protección de Datos (www.agpd.es).
</div>

<?php if ($model->medio_reserva == 2) { ?>
	<br><br>
	<div align="right" class="textoR">
		Reserva realizada a través de la Agencia : <?= $model->agencia ?>
	</div>

<?php } ?>



<div class="panel panel-default" style="margin-top: 160px;">
	<div class="panel-heading title-promo" style="font-size: 1em; text-transform: uppercase;">Condiciones Generales de Contrato y Servicio</div>
	<div class="panel-body" style="padding: 10px 30px 20px 30px">
		<div class="row">
			<div class="col-lg-12">

				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">INFORMACIÓN GENERAL</p>

				<p align="justify">El contrato tiene por objeto regular las condiciones generales de prestación de los servicios ofrecidos Marichal 4 Parking S.L, a través de www.parkingplus.es (en adelante, "Parking Plus").</p>

				<p align="left" style="font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007">IDENTIFICACIÓN DE LAS PARTES CONTRATANTES</p>

				<p align="justify">Las presentes condiciones Generales de Contrato y Servicio ofrecido por PARKING PLUS son suscritas de una parte, por la entidad Marichal 4 Parking S.L, CIF B88537345 con domicilio en Calle Pañeria 38 2do. IZQ. CP 28037, Madrid (Madrid). Y, de otra parte, por el cliente (en adelante, "El CLIENTE"), persona física que ha podido realizar la reserva por distintos medios, y que sede la responsabilidad del vehículo a PARKING PLUS en el momento de formalizar el contrato.</p>

				<p align="left" style="font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007">CONDICIONES DE USO Y RESPONSABILIDADES</p>

				<p align="justify">El CLIENTE certifica en todo momento que está capacitado para conceder a PARKING PLUS la responsabilidad del vehículo en todo momento mientras dure el servicio, quedando bajo su responsabilidad el responder por los posibles daños y perjuicios producidos a PARKING PLUS o a un tercero por el incumplimiento de esto. El CLIENTE certifica que en la duración del servicio, y mientras el vehículo se encuentra bajo responsabilidad de PARKING PLUS, el vehículo cumple con todos los aspectos legales que debe cumplir bajo el marco de la legislación Española. Si el CLIENTE incumple esta obligación queda bajo su responsabilidad el responder por los posibles daños y perjuicios producidos a PARKING PLUS o a un tercero.</p>

				<p align="justify">El CLIENTE autoriza los desplazamientos y cuantas acciones sean necesarias para poder realizar estos servicios.</p>

				<p align="left" style="font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007">RECOGIDA</p>

				<p align="justify">PARKING PLUS no será responsable de los retrasos que pueda provocar a los clientes por la espera en la recepción del vehículo, por el tiempo en la formalización del contrato o por la espera en la devolución del vehículo, que puedan ocasionar daños o perjuicios a los clientes, por lo tanto declina toda responsabilidad. Los clientes deben tener en cuenta que tienen que llegar con un margen suficiente para poder realizar todas operaciones con PARKING PLUS sin que estas puedan suponer un perjuicio para ellos.</p>

				<p align="justify">Si el cliente no espera en la recepción del vehículo por PARKING PLUS a la realización del parte de daños, dará por conforme el servicio realizado por nuestro personal. El parte de daños y el CFEV (Certificado Fotográfico del Estado del Vehículo) se realizarán en las instalaciones de PARKING PLUS si las condiciones de luminosidad, climatológicas, de tráfico u otros condicionantes así lo requieren.</p>

				<p align="justify">PARKING PLUS no se hace responsable de los accesorios fijos y/o extraíbles así como los objetos que se encuentran en el interior del vehículo, si su existencia no ha sido comunicada a nuestro personal en el momento de la recepción del vehículo, verificado la existencia y el funcionamiento por nuestro personal y anotada en el contrato antes de la firma del mismo por parte del CLIENTE. Considerando expresamente que no se ha dejado en el vehículo accesorio u objeto algún en caso de no declararse.</p>

				<p align="justify">PARKING PLUS no se hace responsable de ningún daño no susceptible de un siniestro por accidente. PARKING PLUS tampoco se hace responsable de todo lo que no le cubra su seguro.</p>

				<p align="left" style="font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007">SEGURO</p>

				<p align="justify">La estancia de su vehículo está cubierta por nuestro seguro de daños y responsabilidad civil de acuerdo a la ley de guarda y custodia de aparcamientos 40/2002. </p>

				<p align="justify">Ante un eventual accidente en circulación, causado por un tercero, será este quien cubra los daños ocasionados al vehículo. Lo anterior en aplicabilidad de la ley de seguros obligatorios y responsabilidad civil vigentes. PARKING PLUS no se hace responsable ante ningún daño de tipo mecánico del coche, ya que desconocemos el estado mecánico del mismo al momento de recepcionarlo por el conductor en el aeropuerto. Están excluidos de nuestra póliza los objetos o mercancías depositadas en el interior de los vehículos, salvo indicación expresa y descripción detallada por parte del cliente a la entrega del vehículo, los defectos de fábrica, las reparaciones defectuosas o daños ocultos, inapreciables debidos a falta de cuidado en la conservación del vehículo.</p>

				<p align="justify">Están excluidos los desperfectos en pintura que no sean provocados por daños estructurales. Los pequeños arañazos, piquetes y rozaduras que afectan a la pintura y/o piezas del vehículo que no sean consecuencia de un daño estructural del vehículo (golpe y/o roce con algún vehículo, columna u otro objeto, que deformen, abollen y/o desplacen alguna o varias piezas del vehículo). También están excluidos los daños en los bajos del vehículo y los que no sean apreciables por la suciedad del mismo. Por ello y dada la dificultad para detectar pequeños impactos o fisuras en las lunas de los vehículos (piquetes, grietas y/o grupos ópticos) , y la posibilidad de que estos se hagan visibles con el cambio de temperatura producidos en los traslados, lavados, etc.. declinamos toda responsabilidad en lo referente a este tipo de daños. </p>

				<p align="justify">También quedan excluidos desperfectos en piezas del vehículo por rotura o mal funcionamiento por desgaste de las mismas.
					Después de la devolución del vehículo la empresa no admitirá reclamación alguna.</p>


				<p align="left" style="font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007">DEVOLUCIÓN</p>

				<p align="justify">La modificación de horarios y/o vuelos sin aviso previo puede suponer esperas de hasta 2 horas y/o recargos en el importe final. Si la devolución del vehículo es posterior a las 3 horas fijadas para su entrega. Se abonará el importe correspondiente a un día de estancia por nocturnidad. </p>

				<p align="justify">Debido a la concentración de ondas electromagnéticas y radiofrecuencias en la zona aeroportuaria y estaciones de tren no nos hacemos responsables del mal funcionamiento de GPS, llaves y mandos de coches, alarmas, inmovilizadores y otros dispositivos electrónicos.</p>

				<p align="justify" style="margin-bottom: 20px">Después de la retirada del vehículo por parte del CLIENTE PARKING PLUS no admite reclamaciones del estado del vehículo, de los servicios extras realizados y del pago en metálico, no obstante en nuestro afán por ofrecer al cliente el mejor servicio cualquier duda será atendida por correo electrónico <b>contacto p parkingplus01@gmail.com</b>.</p>

				<p align="justify">Si su vuelo sufre un cambio de horario y se extiende al horario de nocturnidad deberá abonar un incremento de €10, lo pagará al conductor al momento de la entrega de su vehículo. El horario de nocturnidad de 00:30 hasta las 6:00 h de la mañana.</p>

				<p align="justify">Al momento de solicitar su coche cuenta con un margen de hasta 30 minutos para llegar a salidas. Si solicita el coche y pasan estos 30 minutos y no llega al punto de encuentro, se llevará el coche de nuevo al parking y deberá esperar disponibilidad de conductor para un próximo envío, así como también podrá suponer un incremento en el servicio ya que deberá abonar el servicio de valet parking adicional €18.</p>

				<p align="justify">Sí al momento de la recogida o devolución del vehículo se produjera un pinchazo, en la rueda no será responsabilidad del parking ya que no depende de las habilidades o destrezas del conductor sino del Estado de la carretera o de las llantas del coche, también queda exento de responsabilidad el parking de las roturas de cristales o daños mecánicos</p>

				<p align="justify">SEn caso de pérdida o extravío de la llave del coche en custodia el parking se hará cargo solo de la copia de la llave y no de los cambios de bombín oh desplazamiento para llevar al cliente a su domicilio ya que es deber del cliente contar con una copia de la llave para casos fortuitos.</p>

				<p align="justify">Sí por motivos de tráfico y congestionamiento de servicios o por otra causa se retrasa la entrega o devolución del coche esto no será causa de incumplimiento de las obligaciones del cliente para pagar y dar por terminado el servicio. El servicio se considera terminado una vez el cliente haya pagado y se le devuelva por parte del operario (valet parking) la llave del coche. En el caso de negativa por parte del cliente de pagar el servicio no se entregará la llave del vehículo en custodia ya que se considera terminado el servicio cuando el cliente pague el importe correspondiente.</p>


				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">DERECHO DE EXCLUSIÓN</p>

				<p align="justify">PARKING PLUS se reserva el derecho a denegar o retirar el acceso al portal y/o los servicios ofrecidos sin necesidad de preaviso, a instancia propia o de un tercero, a aquellos usuarios que incumplan las presentes Condiciones Generales o la legalidad vigente.</p>


				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">CONDICIONES ECONÓMICAS Y FORMA DE PAGO</p>
				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007; font-size: 14px;">1. Precio del servicio</p>

				<p align="justify">PARKING PLUS cobrará al CLIENTE por la prestación del servicio, en virtud de las tarifas vigentes en cada momento en el PORTAL y que aparecerán una vez seleccionados las fechas, horario, lugar y servicio correspondiente.</p>
				<p align="justify">Una vez que se formalice el servicio, ésta quedará en la base de datos de PARKING PLUS, con el importe asignado, enviando una copia al SOLICITANTE de la misma.</p>
				<p align="justify">El pago del servicio de los clientes que realicen su reserva a través del PORTAL o vía telefónica, se efectuarán en metálico o con tarjeta de crédito en el momento de la entrega o de la devolución del vehículo.</p>
				<p align="justify">Los cambios en el servicio que se pudieran ocasionar, como pueden ser ampliaciones de estancia o contratación de otros servicios entre otros, serán cobrados aparte al CLIENTE, en el momento de la devolución de su vehículo PARKING PLUS, no se hace responsable de los pagos efectuados a intermediarios y de sus políticas de devolución y cancelación, por lo que declina cualquier responsabilidad al respecto.</p>


				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007; font-size: 14px;">2. Formas de Pago</p>

				<p align="justify">Los métodos de pago de PARKING PLUS son los siguientes:<br>
					Pago en metálico.<br>
					Pago mediante tarjeta de crédito/débito en tpv físico.</p>

				<p align="justify">PARKING PLUS garantiza que cada una de las transacciones realizadas son 100% segura. Todas las operaciones que implican la transmisión de datos personales o bancarios se realizan utilizando un entorno seguro. PARKING PLUS utiliza un servidor basado en la tecnología de seguridad estándar SSL (Secure Socket Layer). Toda la información que nos transmitas viaja cifrada a través de la red.</p>

				<p align="justify">Asimismo, los datos sobre tu tarjeta de crédito no quedan registrados en ninguna base de datos, sino que van directamente al TPV (Terminal Punto de Venta) del Banco.</p>

				<p align="justify">Además, le informamos que en un esfuerzo por proporcionar mayor seguridad los propietarios de tarjetas de crédito, hemos incorporado en nuestra pasarela de pagos el sistema de pago seguro denominado CES (Comercio Electrónico Seguro). De esta forma, si eres titular de una tarjeta “securizada” siempre podrá efectuar pagos con tarjeta VISA o MASTERCARD en nuestra tienda.</p>

				<p align="justify">En el caso de que tu tarjeta no esté adherida a este sistema de pago, PARKING PLUS sólo admitirá el pago con tarjeta de crédito VISA o MASTERCARD a clientes con antigüedad y fiabilidad demostradas anteriormente.</p>

				<p align="justify">En ambos casos, al pagar con tarjeta VISA o MASTERCARD se solicitarán siempre los siguientes datos: el número de tarjeta, la fecha de caducidad, y un Código de Validación que coincide con las 3 últimas cifras del número impreso en cursiva en el reverso de su tarjeta VISA o MASTERCARD, ofreciendo, de esta forma, más garantías acerca de la seguridad de la transacción.</p>

				<p align="justify"><b>Importante:</b> El fraude con tarjeta de crédito es un delito, y PARKING PLUS ejercerá las acciones judiciales pertinentes contra todo aquel que realice una transacción fraudulenta en nuestra tienda on-line.</p>

				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">
					TRASLADO Y USO COMPARTIDO DE VEHÍCULO
				</p>

				<p align="justify">
					El cliente autoriza expresamente al personal de Marichal 4 Parking S.L. a trasladar su vehículo entre las distintas instalaciones de la empresa (parking A, parking B o zonas vinculadas).
				</p>

				<p align="justify">
					Asimismo, el cliente consiente que, en el marco de dichos traslados, puedan viajar en su vehículo dos trabajadores de la empresa de forma simultánea, únicamente con la finalidad de organizar la logística de entrega y recogida de vehículos entre parkings.
				</p>

				<p align="justify">
					La empresa se compromete a que dichos traslados se realizarán únicamente por personal autorizado, con el debido cuidado, y asume la responsabilidad frente al cliente por cualquier incidencia que pudiera ocurrir durante los mismos.
				</p>

				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">PROPIEDAD INTELECTUAL E INDUSTRIAL</p>

				<p align="justify">PARKING PLUS por sí o como cesionario, es titular de todos los derechos de propiedad intelectual e industrial de su página web, así como de los elementos contenidos en la misma (a título enunciativo, imágenes, sonido, audio, vídeo, software o textos; marcas o logotipos, combinaciones de colores, estructura y diseño, selección de materiales usados, programas de ordenador necesarios para su funcionamiento, acceso y uso, etc.), titularidad de PARKING PLUS o bien de sus licenciantes. Todos los derechos reservados.</p>

				<p align="justify">Cualquier uso no autorizado previamente por PARKING PLUS, será considerado un incumplimiento grave de los derechos de propiedad intelectual o industrial del autor.</p>

				<p align="justify">Quedan expresamente prohibidas la reproducción, la distribución y la comunicación pública, incluida su modalidad de puesta a disposición, de la totalidad o parte de los contenidos de esta página web, con fines comerciales, en cualquier soporte y por cualquier medio técnico, sin la autorización de expresa PARKING PLUS.</p>


				<p align="justify">El CLIENTE se compromete a respetar los derechos de Propiedad Intelectual e Industrial titularidad de PARKING PLUS. Podrá visualizar únicamente los elementos de la web sin posibilidad de imprimirlos, copiarlos o almacenarlos en el disco duro de su ordenador o en cualquier otro soporte físico. El USUARIO deberá abstenerse de suprimir, alterar, eludir o manipular cualquier dispositivo de protección o sistema de seguridad que estuviera instalado en las páginas de PARKING PLUS.</p>



				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">EXONERACIÓN DE RESPONSABILIDADES</p>

				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007; font-size: 14px;">1_RECOGIDA DEL VEHÍCULO POR PARTE DE PARKING PLUS</p>

				<p align="justify">PARKING PLUS no será responsable de los retrasos que pueda provocar a los clientes por la espera en la recepción del vehículo, por el tiempo en la formalización del contrato o por la espera en la devolución del vehículo, que puedan ocasionar daños o perjuicios a los clientes.</p>

				<p align="justify">Los clientes deben tener en cuenta que tienen que llegar con un margen suficiente para poder realizar todas las operaciones con PARKING PLUS sin que estas puedan suponer un perjuicio para ellos. Si el cliente no espera en la recepción del vehículo por PARKING PLUS la realización del parte de daños, dará por conforme el servicio realizado por nuestro personal.</p>

				<p align="justify">La ficha identificativa del cliente y las fotos del estado del vehículo se realizarán en las instalaciones de PARKING PLUS si las condiciones de luminosidad, climatológicas, de tráfico u otros condicionantes así lo requieren.</p>


				<p align="justify">PARKING PLUS no se hace responsable de los accesorios fijos y/o extraíbles, así como los objetos que se encuentran en el interior del vehículo, si su existencia no ha sido comunicada a nuestro personal en el momento de la recepción del vehículo, verificado la existencia y el funcionamiento por nuestro personal y fotografiada por nuestros empleados a la hora de la recepción del vehículo. Considerando expresamente que no se ha dejado en el vehículo accesorio u objeto alguno, en caso de no declararse.</p>

				<p align="justify">PARKING PLUS no se hace responsable de ningún daño no susceptible de un siniestro por accidente.</p>

				<p align="justify">PARKING PLUS tampoco se hace responsable de todo lo que no le cubra su seguro.</p>

				<p align="justify">PARKING PLUS no se hace responsable de los daños en el techo del vehículo ya que al momento de recogerlo no se le toma fotos al techo. Nuestro Parking es descubierto y no hay nada arriba que pueda ocasionar daños a esta parte del coche.</p>



				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007; font-size: 14px;">2_DEVOLUCIÓN DEL VEHÍCULO</p>

				<p align="justify">La modificación de horarios y/o vuelos sin aviso previo puede suponer esperas de hasta 2 horas y/o recargos en el importe final.</p>

				<p align="justify">Si la devolución del vehículo es posterior a las 5 horas fijadas para su entrega, se abonará el importe correspondiente a un día de estancia por nocturnidad.</p>

				<p align="justify">A la entrega del coche al cliente el día de su vuelta, este deberá revisar el coche antes de marcharse para verificar que este en perfecto estado. Una vez que el cliente se retira de la terminal, PARKING PLUS no se hará responsable por los daños que el cliente pueda visualizar posteriormente.</p>


				<p align="justify">Si el cliente suspende su viaje, pierde su vuelo o cualquier otra razón ajena a PARKING PLUS, no se devolverá el pago por el servicio que haya realizado el cliente.</p>

				<p align="justify">Debido a la concentración de ondas electromagnéticas y radiofrecuencias en la zona aeroportuaria y estaciones de tren no nos hacemos responsables del mal funcionamiento de GPS, llaves y mandos de coches, alarmas, inmovilizadores y otros dispositivos electrónicos..</p>


				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">POLITICAS DE CANCELACIÓN Y RESOLUCIONES PREVIAS DEL CONTRATO</p>

				<p align="justify">El cliente puede cancelar los servicios ofrecidos por PARKING PLUS, sin coste, si:</p>

				<p align="justify"><b>Contrata online:</b> avisando con 24 horas de antelación a PARKING PLUS.</p>

				<p align="justify"><b>Contrata telefónicamente:</b> avisando con 5 horas de antelación a PARKING PLUS.</p>


				<p align="justify">En el caso de incumplir lo mencionado en el anterior apartado, PARKING PLUS no realizará el reembolso de los importes abonados por el cliente.</p>

				<p align="justify">Si el cliente suspende su viaje, pierde su vuelo o la aerolínea cancela su vuelo, PARKING PLUS no hará el reembolso del importe abonado.</p>

				<p align="justify">POLITICA DE REEMBOLSO, en caso de cancelar el servicio de la manera estipulada en las presentes Condiciones, PARKING PLUS procederá al reembolso de las cantidades percibidas previamente al servicio usando el mismo método usado para la contratación del servicio. En caso de no poder realizarse por esta vía, se procederá a realizar una Transferencia al CLIENTE o se le reembolsará el dinero en mano, siempre que se disponga de efectivo en las oficinas el momento de la petición de esta política.</p>


				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">TRATAMIENTO DE DATOS PERSONALES</p>

				<p align="justify">Toda información proporcionada durante el proceso de contratación será recogida, almacenada y tratada por PARKING PLUS en su calidad de responsable del tratamiento.</p>


				<p align="justify">En cumplimiento del RGPD, le informamos que sus datos personales serán usados para prestarle nuestros servicios. Dichos datos son necesarios para poder relacionarnos con usted, lo que nos permite su uso dentro de la legalidad. Podrán tener acceso a su información aquellas entidades que necesiten conocerla para poder prestarle nuestros servicios). Se conservarán los datos durante nuestra relación y mientras nos obliguen las leyes. Puede ejercer sus derechos de acceso, rectificación, supresión, portabilidad y limitación del tratamiento, enviando una solicitud escrita, a través de correo electrónico: contacto parkingplus01@gmail.com junto con prueba válida en derecho, como fotocopia del D.N.I. o equivalente, indicando en el asunto «PROTECCIÓN DE DATOS».</p>

				<p align="justify">Si se desatienten sus derechos, puede formular una reclamación en la Agencia Española de Protección de Datos (www.aepd.es).</p>

				<p align="justify">Para darse de baja de envíos de comunicaciones publicitarias puede hacerlo enviando un email a contacto parkingplus01@gmail.com.</p>


				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">CAUSAS DE DISOLUCIÓN DEL CONTRATO Y DESISTIMIENTO</p>

				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007; font-size: 14px;">Derecho de desistimiento</p>

				<p align="justify">En virtud del servicio ofertado, siendo este el aparcamiento de vehículos, y en virtud del artículo 103, del Real Decreto Legislativo 1/2007, de 16 de noviembre, por el que se aprueba el texto refundido de la Ley General para la Defensa de los Consumidores y Usuarios y otras leyes complementarias, le informamos que quedamos exentos de la aplicación del presente derecho de desistimiento una vez se inicie nuestro servicio.</p>

				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007; font-size: 14px;">Disolución del contrato.</p>

				<p align="justify">La disolución del contrato de servicios puede ocurrir en cualquier momento por cualquiera de las dos partes.</p>

				<p align="justify">De acuerdo con el Código Civil español y normas de desarrollo, el Real Decreto Legislativo 1/2007, de 16 de noviembre, por el que se aprueba el texto refundido de la Ley General para la Defensa de los Consumidores y Usuarios y otras leyes complementarias y la jurisprudencia vigente, si se solicita la devolución del precio por la utilización del curso deberán acreditarse y probarse los extremos por los cuales se solicita la devolución del importe.</p>

				<p align="justify">PARKING PLUS se reserva así el derecho a reembolsar o no el importe íntegro o parte del mismo al usuario si se comprueba que las causas que esgrime para la devolución de dicho importe no se corresponden con la realidad o tienen un fin fraudulento.</p>

				<p align="justify">PARKING PLUS puede terminar o suspender cualquier y todos los Servicios contratados con el CLIENTE inmediatamente, sin previo aviso o responsabilidad, en caso de que no cumpla con las condiciones aquí expuestas.</p>

				<p align="justify">A la disolución del contrato, el derecho a utilizar los Servicios cesará inmediatamente.<br>
					Serán causas de disolución de contrato:<br>
					La falsedad, en todo o en parte, de los datos suministrados en el proceso de contratación de cualquier servicio.<br>
					También los casos de abuso de los servicios de soporte.<br>
					Cualquier incumplimiento establecido a lo largo de las condiciones.<br>
					La disolución implica la pérdida de sus derechos sobre el servicio contratado.</p>


				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">RESOLUCION EXTRAJUDICIAL DE CONFLICTOS</p>

				<p align="justify">Asimismo, en los términos que se recogen en el artículo 14 del Reglamento UE 524/2013, sobre resolución de litigios en materia de consumo, se proporciona un enlace directo a la plataforma de resolución de litigios en línea: https://ec.europa.eu/consumers/odr/main/index.cfm.</p>


				<p align="left" style="font-weight: bold; margin-bottom: 15px; color: #921007">LEGISLACIÓN APLICABLE Y JURISDICCIÓN</p>

				<p align="justify">La relación entre PARKING PLUS y el CLIENTE se regirá por la normativa española vigente y cualquier controversia se someterá a los Juzgados y tribunales de la ciudad de Madrid, salvo que la Ley aplicable disponga otra cosa.</p>

			</div>
		</div>
	</div>
</div>