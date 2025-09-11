<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use frontend\controllers\SiteController;

/* @var $this yii\web\View */
/* @var $model common\models\Reservas */
/* @var $form yii\widgets\ActiveForm */

$this->title = Yii::$app->name . ' | Nueva Reserva';
$nightStart = SiteController::NIGHT_START;
$nightEnd   = SiteController::NIGHT_END;
?>

<style>
	/* --------- Design tokens --------- */
	:root {
		--brand: #961007;
		--brand-600: #7a0c06;
		--text: #1f2937;
		--muted: #6b7280;
		--bg: #ffffff;
		--bg-soft: #fafafa;
		--card: #ffffff;
		--border: #e5e7eb;
		--success: #16a34a;
	}

	/* --------- Layout & Typography --------- */
	.container-reserva {
		margin-top: 96px;
	}

	.heading {
		text-align: center;
		margin: 0 0 20px;
		color: var(--text)
	}

	.heading h3 {
		font-weight: 700;
		letter-spacing: .3px;
	}

	.section {
		border: 1px solid var(--brand);
		border-radius: 12px;
		padding: 16px;
	}

	.muted {
		color: var(--muted);
	}

	.text-brand {
		color: var(--brand);
	}

	.btn-brand {
		background: var(--brand);
		color: #fff;
		border: none;
		font-weight: 600;
		padding: 12px 14px;
		border-radius: 10px;
		transition: .2s;
	}

	.btn-brand:hover {
		background: var(--brand-600);
	}

	.btn-link-soft {
		background: transparent;
		border: 1px solid var(--border);
		color: var(--text);
		padding: 6px 10px;
		border-radius: 8px;
		font-size: 12px;
	}

	/* --------- Cards de planes --------- */
	.plan-card {
		display: flex;
		flex-wrap: wrap;
		align-items: flex-start;
		gap: 16px;
		border: 1px solid var(--border);
		border-radius: 16px;
		padding: 16px;
		background: var(--card);
		box-shadow: 0 1px 6px rgba(0, 0, 0, .04);
		margin-top: 24px;
	}

	.plan-title {
		width: 100%;
		font-weight: 800;
		font-size: 1.25rem;
		letter-spacing: .5px;
		color: var(--text);
		margin-top: 6px;
	}

	.plan-badge {
		display: flex;
		flex-direction: column;
		align-items: center;
		gap: 8px;
		min-width: 120px;
	}

	.plan-badge .label {
		font-weight: 700;
		letter-spacing: .5px;
	}

	.services {
		margin: 0;
		padding-left: 18px;
	}

	.services li {
		margin: 6px 0;
	}

	.price-box {
		display: flex;
		flex-direction: column;
		align-items: center;
		min-width: 190px;
	}

	.price {
		font-weight: 800;
		font-size: 2rem;
		line-height: 1;
		margin: 6px 0 12px;
	}

	/* --------- Loader --------- */
	.loading {
		display: none;
	}

	/* --------- Alert --------- */
	.alert-inline {
		display: none;
		color: #b91c1c;
		font-weight: 700;
		margin-bottom: 8px;
	}

	/* --------- Util --------- */
	.hidden {
		display: none !important;
	}

	.spacer-16 {
		height: 16px;
	}

	.spacer-24 {
		height: 24px;
	}

	/* === Responsive fixes === */
	img,
	.counter__icon {
		max-width: 100%;
		height: auto;
	}

	.plan-badge {
		min-width: 100px;
	}

	@media (max-width: 768px) {
		.plan-badge {
			min-width: auto;
			width: 33%;
			text-align: center;
		}

		.plan-badge .label {
			display: none;
		}

		/* evita duplicar el texto del plan */
		.plan-title {
			text-align: center;
			font-size: 1.15rem;
			margin-top: 0;
		}
	}

	@media (max-width: 768px) {
		.plan-card>div[style*="flex:1"] {
			width: 100% !important;
			order: 4;
			/* servicios debajo de badges */
		}
	}

	@media (max-width: 768px) {
		.price-box {
			width: 100%;
			min-width: 0;
			order: 5;
			text-align: center;
		}

		.price {
			font-size: 1.6rem;
		}

		.btn-brand {
			width: 100%;
		}

		/* CTA full-width en móvil */
	}

	.section#reserva_f,
	.section#fechas_r {
		display: block;
	}

	@media (min-width: 769px) {
		.section#reserva_f {
			display: flex;
			align-items: center;
			gap: 16px;
			flex-wrap: wrap;
		}
	}

	@media (max-width: 768px) {
		.row [class*="col-"] {
			margin-bottom: 10px;
		}
	}

	@media (max-width: 480px) {
		.heading h3 {
			font-size: 1.25rem;
		}

		.services li {
			margin: 4px 0;
		}
	}

	.modal-lg .modal-body {
		padding: 16px;
	}

	@media (max-width: 576px) {
		.modal-dialog {
			margin: 10px;
		}

		.modal-content {
			border-radius: 12px;
		}
	}
</style>

<!-- =================== Modales =================== -->
<?php
Modal::begin([
	'header' => '<strong>Información del servicio</strong>',
	'id' => 'info_economic',
	'size' => 'modal-lg',
]); ?>
<div class="row">
	<div class="col-lg-12">
		<p class="text-justify">PARKINGPLUS es la solución económica para aparcar cerca del Aeropuerto: aparque en nuestro recinto y le llevamos a su terminal en nuestra lanzadera.</p>
		<p class="text-justify">Máxima seguridad: acceso restringido, plazas limitadas, vigilancia 24h, llaves en cajas de seguridad y CCTV conectado a central de alarmas.</p>

		<h4 class="text-brand" style="margin-top:24px;">¿Cómo funciona?</h4>
		<p class="text-justify"><strong>Llegada al parking:</strong> veinte minutos antes de llegar a nuestras instalaciones, llame al 603 284 800 para coordinar su check-in y la salida de la lanzadera.</p>
		<p class="text-justify"><strong>Recepción:</strong> se emite ticket y se realiza el CFEV (control fotográfico del estado del vehículo). Si no es posible por condiciones, se hace dentro del recinto.</p>
		<p class="text-justify"><strong>Traslado a la terminal:</strong> suba a nuestra lanzadera PARKINGPLUS. El trayecto habitual es de 8–12 minutos (puede variar según tráfico). Le dejamos en Salidas de su terminal.</p>

		<h4 class="text-brand" style="margin-top:24px;">Puntos de encuentro (vuelta)</h4>
		<p class="text-justify">T1: puertas 4–5. T2: puerta 7. T4: puertas 5–6.</p>
		<p class="text-justify"><strong>Hora de recogida (vuelta):</strong> cuando aterrice y tenga su equipaje, llame al 603 284 800 y diríjase al punto de encuentro indicado para la lanzadera.</p>

		<h4 class="text-brand" style="margin-top:24px;">Devolución</h4>
		<p class="text-justify">Le llevamos de vuelta al parking para la entrega de su vehículo. Si su vuelo se retrasa, lo sabemos; cambios no avisados pueden suponer esperas de hasta 2h y/o recargos.</p>

		<h4 class="text-brand" style="margin-top:24px;">Plan Economic</h4>
		<p class="text-justify">Aparcamiento en recinto cerrado al aire libre y vigilado 24h + lanzadera ida y vuelta con la terminal.</p>
	</div>

</div>
<?php Modal::end(); ?>

<?php
Modal::begin([
	'header' => '<strong>Información del servicio</strong>',
	'id' => 'info_standard',
	'size' => 'modal-lg',
]); ?>
<div id='modalContent'>
	<div class='row'>
		<div class='col-lg-12'>

			<p align='justify'>PARKINGPLUS es la solución a su problema de estacionamiento en el Aeropuerto, evitándole así las molestias de buscar aparcamiento e ir desde el parking a la terminal y viceversa, cargado con su equipaje.</p>

			<p align='justify'>Además ofrecemos la máxima seguridad, ya que, los aparcamientos son de acceso restringido, con plazas limitadas, están vigilados las 24h, las llaves de los vehículos se depositan en cajas de seguridad y poseen CCTV conectados a central de alarmas. </p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>¿Cómo funciona?</p>

			<p align='justify'>Recogida. veinte minutos antes de llegar a la terminal de salida debe llamar a los conductores al 603284800 para que procedan a la recogida de su vehículo.
			</p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Puntos de encuentro </p>

			<p align='justify'>T1 Diríjase a la T1 de salidas y sitúese en la puerta 4. </p>

			<p align='justify'>T2 Diríjase a la T2 de salidas y sitúese en la puerta 7.</p>

			<p align='justify'>T4 Diríjase a la T4 de salidas y sitúese frente a la puerta 5 ò 6.</p>

			<p align='justify'>Hora de recogida. La hora que aparece en su reserva es una hora aproximada, no se preocupe si llega antes o después, llame al conductor y procederá a la recogida de su vehículo..</p>

			<p align='justify'>Información de recepción. A la recepción de su vehículo, Parking plus le entregara un tiket de confirmación de recogida, compruébe los datos. El conductor realizara el parte de daños y el CFEV (Certificado Fotográfico del Estado del Vehículo), salvo que las condiciones de luminosidad, climatológicas, de tráfico u otras condicionantes no lo permitieran, entonces este se realizará en las instalaciones de PARKING PLUS.
				Devolución. Al regreso de su viaje, y una vez tenga recogido todo el equipaje debe llamar al teléfono de los conductores 603284800 y dirigirse al punto de encuentro de la devolución.</p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Punto de encuentro</p>

			<p align='justify'>T1 Diríjase a la T1 de salidas, suban por los ascensores o rampas mecánicas a la planta superior, salgan al exterior y sitúese al final de la terminal en la puerta 4 ò 5. </p>

			<p align='justify'>T2 Diríjase a la T2 de salidas tomando el ascensor o las rampas mecánicas, siga las indicaciones de chek in- facturación y sitúese en la puerta 7. </p>

			<p align='justify'>T4 Diríjase a la T4 de salidas. Tome un ascensor de cristal y suba a salidas. Salgan al exterior y cruce los carriles de taxi, giren a la derecha para situarse al final de la terminal en la puerta 5 ò 6.</p>

			<p align='justify'>Hora de devolución. No se preocupe si su vuelo se retrasa, nosotros conocemos que los horarios de vuelos pueden sufrir modificaciones. pero les recordamos que la modificación de horarios y/o vuelos, así como cambios de fecha en su devolución sin aviso previo puede suponer esperas de hasta 2 horas y/o recargos en el importe final.</p>


			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Servicio estándar</p>

			<p align='justify'>Servicio de aparcamiento en recinto cerrado al aire libre y vigilado 24hrs.</p>

		</div>
	</div>
</div>
<?php Modal::end(); ?>

<?php
Modal::begin([
	'header' => '<strong>Información del servicio</strong>',
	'id' => 'info_premium',
	'size' => 'modal-lg',
]); ?>
<div id='modalContent'>
	<div class='row'>
		<div class='col-lg-12'>

			<p align='justify'>PARKINGPLUS es la solución a su problema de estacionamiento en el Aeropuerto, evitándole así las molestias de buscar aparcamiento e ir desde el parking a la terminal y viceversa, cargado con su equipaje.</p>

			<p align='justify'>Además ofrecemos la máxima seguridad, ya que, los aparcamientos son de acceso restringido, con plazas limitadas, están vigilados las 24h, las llaves de los vehículos se depositan en cajas de seguridad y poseen CCTV conectados a central de alarmas. </p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>¿Cómo funciona?</p>

			<p align='justify'>Recogida. veinte minutos antes de llegar a la terminal de salida debe llamar a los conductores al 603284800 para que procedan a la recogida de su vehículo.
			</p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Puntos de encuentro </p>

			<p align='justify'>T1 Diríjase a la T1 de salidas y sitúese en la puerta 4. </p>

			<p align='justify'>T2 Diríjase a la T2 de salidas y sitúese en la puerta 7.</p>

			<p align='justify'>T4 Diríjase a la T4 de salidas y sitúese frente a la puerta 5 ò 6.</p>

			<p align='justify'>Hora de recogida. La hora que aparece en su reserva es una hora aproximada, no se preocupe si llega antes o después, llame al conductor y procederá a la recogida de su vehículo..</p>

			<p align='justify'>Información de recepción. A la recepción de su vehículo, Parking plus le entregara un tiket de confirmación de recogida, compruébe los datos. El conductor realizara el parte de daños y el CFEV (Certificado Fotográfico del Estado del Vehículo), salvo que las condiciones de luminosidad, climatológicas, de tráfico u otras condicionantes no lo permitieran, entonces este se realizará en las instalaciones de PARKING PLUS.
				Devolución. Al regreso de su viaje, y una vez tenga recogido todo el equipaje debe llamar al teléfono de los conductores 603284800 y dirigirse al punto de encuentro de la devolución.</p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Punto de encuentro</p>

			<p align='justify'>T1 Diríjase a la T1 de salidas, suban por los ascensores o rampas mecánicas a la planta superior, salgan al exterior y sitúese al final de la terminal en la puerta 4 ò 5. </p>

			<p align='justify'>T2 Diríjase a la T2 de salidas tomando el ascensor o las rampas mecánicas, siga las indicaciones de chek in- facturación y sitúese en la puerta 7. </p>

			<p align='justify'>T4 Diríjase a la T4 de salidas. Tome un ascensor de cristal y suba a salidas. Salgan al exterior y cruce los carriles de taxi, giren a la derecha para situarse al final de la terminal en la puerta 5 ò 6.</p>

			<p align='justify'>Hora de devolución. No se preocupe si su vuelo se retrasa, nosotros conocemos que los horarios de vuelos pueden sufrir modificaciones. pero les recordamos que la modificación de horarios y/o vuelos, así como cambios de fecha en su devolución sin aviso previo puede suponer esperas de hasta 2 horas y/o recargos en el importe final.</p>


			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Servicio premium</p>

			<p align='justify'>Servicio de aparcamiento en recinto cerrado al aire libre y vigilado 24hrs, le incluye servicio de lavado exterior. (si usted requiere una limpieza con más detalle puede contratar el servicio de limpieza exterior lo que supondrá un recargo adicional).</p>

		</div>
	</div>
</div>
<?php Modal::end(); ?>

<?php
Modal::begin([
	'header' => '<strong>Información del servicio</strong>',
	'id' => 'info_priority',
	'size' => 'modal-lg',
]); ?>
<div id='modalContent'>
	<div class='row'>
		<div class='col-lg-12'>

			<p align='justify'>PARKINGPLUS es la solución a su problema de estacionamiento en el Aeropuerto, evitándole así las molestias de buscar aparcamiento e ir desde el parking a la terminal y viceversa, cargado con su equipaje.</p>

			<p align='justify'>Además ofrecemos la máxima seguridad, ya que, los aparcamientos son de acceso restringido, con plazas limitadas, están vigilados las 24h, las llaves de los vehículos se depositan en cajas de seguridad y poseen CCTV conectados a central de alarmas. </p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>¿Cómo funciona?</p>

			<p align='justify'>Recogida. veinte minutos antes de llegar a la terminal de salida debe llamar a los conductores al 603284800 para que procedan a la recogida de su vehículo.
			</p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Puntos de encuentro </p>

			<p align='justify'>T1 Diríjase a la T1 de salidas y sitúese en la puerta 4. </p>

			<p align='justify'>T2 Diríjase a la T2 de salidas y sitúese en la puerta 7.</p>

			<p align='justify'>T4 Diríjase a la T4 de salidas y sitúese frente a la puerta 5 ò 6.</p>

			<p align='justify'>Hora de recogida. La hora que aparece en su reserva es una hora aproximada, no se preocupe si llega antes o después, llame al conductor y procederá a la recogida de su vehículo..</p>

			<p align='justify'>Información de recepción. A la recepción de su vehículo, Parking plus le entregara un tiket de confirmación de recogida, compruébe los datos. El conductor realizara el parte de daños y el CFEV (Certificado Fotográfico del Estado del Vehículo), salvo que las condiciones de luminosidad, climatológicas, de tráfico u otras condicionantes no lo permitieran, entonces este se realizará en las instalaciones de PARKING PLUS.
				Devolución. Al regreso de su viaje, y una vez tenga recogido todo el equipaje debe llamar al teléfono de los conductores 603284800 y dirigirse al punto de encuentro de la devolución.</p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Punto de encuentro</p>

			<p align='justify'>T1 Diríjase a la T1 de salidas, suban por los ascensores o rampas mecánicas a la planta superior, salgan al exterior y sitúese al final de la terminal en la puerta 4 ò 5. </p>

			<p align='justify'>T2 Diríjase a la T2 de salidas tomando el ascensor o las rampas mecánicas, siga las indicaciones de chek in- facturación y sitúese en la puerta 7. </p>

			<p align='justify'>T4 Diríjase a la T4 de salidas. Tome un ascensor de cristal y suba a salidas. Salgan al exterior y cruce los carriles de taxi, giren a la derecha para situarse al final de la terminal en la puerta 5 ò 6.</p>

			<p align='justify'>Hora de devolución. No se preocupe si su vuelo se retrasa, nosotros conocemos que los horarios de vuelos pueden sufrir modificaciones. pero les recordamos que la modificación de horarios y/o vuelos, así como cambios de fecha en su devolución sin aviso previo puede suponer esperas de hasta 2 horas y/o recargos en el importe final.</p>

			<p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Servicio priority</p>

			<p align='justify'>Servicio de aparcamiento en recinto cerrado y vigilado 24hrs, le incluye servicio de lavado completo interior y exterior.</p>

		</div>
	</div>
</div>
<?php Modal::end(); ?>

<?php
/* =================== Campos ocultos =================== */
$cant = count($precio_diario);
$num = 1;
for ($i = 0; $i < $cant; $i++): ?>
	<input type="hidden" id="precio-diario<?= $num ?>" value="<?= $precio_diario[$i]['precio'] ?>">
<?php $num++;
endfor; ?>

<?php foreach ($servicios as $ser): ?>
	<input type="hidden" id="servicio-<?= $ser['id'] ?>" value="<?= $ser['costo'] ?>">
<?php endforeach; ?>

<?php if (!is_null($temporada)): ?>
	<input type="hidden" id="pre_dia_temp" value="<?= $temporada->precio ?>">
	<input type="hidden" id="fecha_ini_temp" value="<?= $temporada->fecha_inicio . ' ' . $temporada->hora_inicio ?>">
	<input type="hidden" id="fecha_fin_temp" value="<?= $temporada->fecha_fin . ' ' . $temporada->hora_fin ?>">
<?php endif; ?>

<div class="reservas-form container container-reserva">
	<?php $form = ActiveForm::begin(['action' => Yii::$app->urlManager->createUrl('/site/fechas')]); ?>

	<input type="hidden" id="nocturno" value="<?= $nocturno->costo ?>">
	<input type="hidden" id="temporada" value="<?= !is_null($temporada) ? 1 : 0 ?>">
	<input type="hidden" id="cantdias" name="cantdias" value="<?= $cant_dias ?>">
	<input type="hidden" id="type_service" name="type">
	<input type="hidden" id="plan" name="plan">
	<input type="hidden" id="precio_dia" name="precio_dia" value="<?= $precio_dia ?>">

	<div class="heading">
		<h3>Información de Reserva</h3>
		<p class="muted">Verifica tus fechas y elige tu plan</p>
	</div>

	<!-- Resumen fechas -->
	<div class="section" id="reserva_f" style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
		<div><strong>Desde:</strong> <span id="fentrada"><?= $model->fecha_entrada ?></span> <span id="hentrada"><?= $model->hora_entrada ?></span></div>
		<div><strong>Hasta:</strong> <span id="fsalida"><?= $model->fecha_salida ?></span> <span id="hsalida"><?= $model->hora_salida ?></span></div>
		<div class="spacer-16"></div>
		<button type="button" id="change_f" class="btn-brand">
			<span class="glyphicon glyphicon-calendar"></span> Cambiar fechas
		</button>
	</div>

	<!-- Editor fechas -->
	<div class="section hidden" id="fechas_r" style="align-items:center;">
		<div id="alert_fechas" class="alert-inline">Verifique las fechas y horas seleccionadas</div>
		<div class="row" style="align-items:flex-end">
			<div class="col-lg-3">
				<?php
				$recogidaLabel = '<span>Recogida</span>';
				echo $form->field($model, 'fecha_entrada')->widget(DatePicker::classname(), [
					'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
					'language' => 'es',
					'pluginOptions' => [
						'orientation' => 'bottom left',
						'autoclose' => true,
						'format' => 'dd-mm-yyyy',
						'startDate' => date('d-m-Y'),
					]
				])->label($recogidaLabel);
				?>
			</div>
			<div class="col-lg-2">
				<?= $form->field($model, 'hora_entrada')->widget(TimePicker::classname(), [
					'pluginOptions' => ['showMeridian' => false],
				]); ?>
			</div>
			<div class="col-lg-3">
				<?php
				$devolucionLabel = '<span>Devolución</span>';
				echo $form->field($model, 'fecha_salida')->widget(DatePicker::classname(), [
					'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
					'language' => 'es',
					'pluginOptions' => [
						'orientation' => 'bottom left',
						'autoclose' => true,
						'format' => 'dd-mm-yyyy',
						'startDate' => date('d-m-Y'),
					]
				])->label($devolucionLabel);
				?>
			</div>
			<div class="col-lg-2">
				<?= $form->field($model, 'hora_salida')->widget(TimePicker::classname(), [
					'pluginOptions' => ['showMeridian' => false],
				]); ?>
			</div>
			<div class="col-lg-2" style="display:flex; justify-content:center;">
				<button type="button" id="fecha_update" class="btn-brand">Actualizar fechas</button>
			</div>
		</div>
	</div>

	<!-- PLAN: ECONOMIC -->
	<div class="plan-card">
		<div class="plan-title">ECONOMIC</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/economico.png', ['style' => 'width:68px;height:68px;']) ?>
			<div class="label">ECONOMIC</div>
		</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/minivan.png', ['style' => 'width:68px;height:68px;']) ?>
			<div class="muted" style="text-align:center">Traslado a<br>terminal</div>
		</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/car.png', ['style' => 'width:48px;height:48px;']) ?>
		</div>
		<div style="flex:1;">
			<h4>Servicios</h4>
			<ul class="services">
				<!-- <li>Plaza reservada</li> -->
				<li>Llegas a nuestro aparcamiento</li>
				<li>Traslado aparcamiento ⇄ terminal</li>
                                <li class="noctur hidden">Especial nocturnidad (<?= $nightStart ?>–<?= $nightEnd ?>)</li>
			</ul>
			<?= Html::button('Más info', [
				'class' => 'btn-link-soft',
				'data-toggle' => 'modal',
				'data-target' => '#info_economic',
			]) ?>
		</div>
		<div class="price-box">
			<svg width="48" height="48" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="#000" class="loading"></svg>
			<div id="costo_e" class="price"></div>
			<button type="submit" id="economic" class="btn-brand" aria-label="Reservar plan Economic">
				Reservar Economic <span class="glyphicon glyphicon-send"></span>
			</button>
		</div>
	</div>

	<!-- PLAN: STANDARD -->
	<div class="plan-card">
		<div class="plan-title">STANDARD</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/bronce.png', ['style' => 'width:64px;height:64px;']) ?>
			<div class="label">STANDARD</div>
		</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/chofer1.png', ['style' => 'width:48px;height:48px;']) ?>
			<div class="muted" style="text-align:center">Recogida por<br>chofer</div>
		</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/car.png', ['style' => 'width:48px;height:48px;']) ?>
		</div>
		<div style="flex:1;">
			<h4>Servicios</h4>
			<ul class="services">
				<li>Plaza reservada</li>
				<li>Recogida y entrega en terminal</li>
                                <li class="noctur hidden">Especial nocturnidad (<?= $nightStart ?>–<?= $nightEnd ?>)</li>
			</ul>
			<?= Html::button('Más info', [
				'class' => 'btn-link-soft',
				'data-toggle' => 'modal',
				'data-target' => '#info_standard',
			]) ?>
		</div>
		<div class="price-box">
			<svg width="48" height="48" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="#000" class="loading"></svg>
			<div id="costo" class="price"></div>
			<button type="submit" id="bronce" class="btn-brand" aria-label="Reservar plan Standard">
				Reservar Standard <span class="glyphicon glyphicon-send"></span>
			</button>
		</div>
	</div>

	<!-- PLAN: PREMIUM -->
	<div class="plan-card">
		<div class="plan-title">PREMIUM</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/plata.png', ['style' => 'width:64px;height:64px;']) ?>
			<div class="label">PREMIUM</div>
		</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/chofer1.png', ['style' => 'width:48px;height:48px;']) ?>
			<div class="muted" style="text-align:center">Recogida por<br>chofer</div>
		</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/limpiezaext.png', ['style' => 'width:58px;height:58px;']) ?>
		</div>
		<div style="flex:1;">
			<h4>Servicios</h4>
			<ul class="services">
				<li>Plaza reservada</li>
				<li>Lavado exterior</li>
                                <li class="noctur hidden">Especial nocturnidad (<?= $nightStart ?>–<?= $nightEnd ?>)</li>
			</ul>
			<?= Html::button('Más info', [
				'class' => 'btn-link-soft',
				'data-toggle' => 'modal',
				'data-target' => '#info_premium',
			]) ?>
		</div>
		<div class="price-box">
			<svg width="48" height="48" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="#000" class="loading"></svg>
			<div id="costo_t" class="price"></div>
			<button type="submit" id="plata" class="btn-brand" aria-label="Reservar plan Premium">
				Reservar Premium <span class="glyphicon glyphicon-send"></span>
			</button>
		</div>
	</div>

	<!-- PLAN: PRIORITY -->
	<div class="plan-card">
		<div class="plan-title">PRIORITY</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/oro.png', ['style' => 'width:64px;height:64px;']) ?>
			<div class="label">PRIORITY</div>
		</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/chofer1.png', ['style' => 'width:48px;height:48px;']) ?>
			<div class="muted" style="text-align:center">Recogida por<br>chofer</div>
		</div>
		<div class="plan-badge">
			<?= Html::img('@web/images/llaves.png', ['style' => 'width:58px;height:58px;']) ?>
		</div>
		<div style="flex:1;">
			<h4>Servicios</h4>
			<ul class="services">
				<li>Plaza reservada</li>
				<li>Lavado interior</li>
				<li>Lavado exterior</li>
				<li>Custodia de llaves</li>
                                <li class="noctur hidden">Especial nocturnidad (<?= $nightStart ?>–<?= $nightEnd ?>)</li>
			</ul>
			<?= Html::button('Más info', [
				'class' => 'btn-link-soft',
				'data-toggle' => 'modal',
				'data-target' => '#info_priority',
			]) ?>
		</div>
		<div class="price-box">
			<svg width="48" height="48" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="#000" class="loading"></svg>
			<div id="costo_i" class="price"></div>
			<button type="submit" id="oro" class="btn-brand" aria-label="Reservar plan Priority">
				Reservar Priority <span class="glyphicon glyphicon-send"></span>
			</button>
		</div>
	</div>

	<div class="spacer-24"></div>

	<?php ActiveForm::end(); ?>
</div>

<?php
/* =================== JS =================== */
$this->registerJs(
	<<<'JS'
(function(){
  function parseFloatSafe(v){ var n = parseFloat(v); return isNaN(n) ? 0 : n; }

  // Lee precios diarios en un array: index 1..30
  function readDailyPrices(){
    var prices = [];
    for (var i=1;i<=30;i++){
      var el = document.getElementById('precio-diario'+i);
      prices[i] = el ? parseFloatSafe(el.value) : 0;
    }
    return prices;
  }

  // Precio para X días:
  // - Si días <= 30: toma el precio exacto del array
  // - Si días > 30: suma bloques de 30 usando precio30 y el resto a precio_dia (con la política >=18)
  function priceForDays(days, prices, precioDia){
    days = parseInt(days,10);
    if (days <= 0) return 0;

    var total = 0;
    if (days <= 30) return parseFloatSafe(prices[days]);

    // bloques de 30
    while (days > 30){
      total += parseFloatSafe(prices[30]);
      days -= 30;
    }
    // resto
    if (days >= 18){
      total += parseFloatSafe(prices[30]);
    }else{
      total += days * parseFloatSafe(precioDia);
    }
    return total;
  }

  function isNocturnal(hhmm){ return (hhmm >= '<?= $nightStart ?>' && hhmm <= '<?= $nightEnd ?>'); }

  function convertDateFormat(dstr){
    var info = (dstr||'').split('-'); // dd-mm-YYYY
    if (info.length !== 3) return dstr;
    return info[2] + '-' + info[1] + '-' + info[0];
  }

  function diffDays(fechaIn, fechaOut, horaIn, horaOut){
    var E = new Date(convertDateFormat(fechaIn)).getTime();
    var S = new Date(convertDateFormat(fechaOut)).getTime();
    var diff = (S - E) / (1000*60*60*24); // días enteros
    // Si hay horas positivas, sumamos 1 día según regla original
    if (horaIn && horaOut){
      var inh = horaIn.split(':'), outh = horaOut.split(':');
      var min = parseInt(outh[1],10) - parseInt(inh[1],10);
      var carry = 0; if (min < 0){ min += 60; carry = 1; }
      var hour = parseInt(outh[0],10) - parseInt(inh[0],10) - carry;
      if (hour > 0) diff += 1;
    }
    return Math.floor(diff);
  }

  function showNocturnity(hIn, hOut){
    var noct = (isNocturnal(hIn) || isNocturnal(hOut));
    var items = document.querySelectorAll('.noctur');
    items.forEach(function(el){ el.classList.toggle('hidden', !noct); });
    return noct;
  }

  // Estado inicial
  var precioDia = parseFloatSafe(document.getElementById('precio_dia').value);
  var prices = readDailyPrices();

  function recalcAndPaint(cantBase, horaIn, horaOut){
    var totalBase = priceForDays(cantBase, prices, precioDia);

    var nocturnidad = 0;
    if (showNocturnity(horaIn, horaOut)){
      nocturnidad = parseFloatSafe(document.getElementById('nocturno').value);
    }

    // Costos
    var costoStandard = totalBase + nocturnidad;
    var costoPremium  = parseFloatSafe(document.getElementById('servicio-9')?.value) + nocturnidad + totalBase;
    var costoPriority = parseFloatSafe(document.getElementById('servicio-2')?.value) + nocturnidad + totalBase;

    // Económico = costoStandard - días base (regla de descuento)
    var costoEconomic = (totalBase + nocturnidad) - Math.min(cantBase, 18);

    // Pintar
    function put(id, val){
      var el = document.getElementById(id);
      if (el){ el.innerHTML = (parseFloatSafe(val)).toFixed(2) + '€'; }
    }
    put('costo_e', costoEconomic);
    put('costo',   costoStandard);
    put('costo_t', costoPremium);
    put('costo_i', costoPriority);
  }

  // INIT (al cargar)
  (function init(){
    var cant = parseInt(document.getElementById('cantdias').value,10);
    var hIn  = document.getElementById('reservas-hora_entrada')?.value || '';
    var hOut = document.getElementById('reservas-hora_salida')?.value || '';
    recalcAndPaint(cant, hIn, hOut);
  })();

  // Mostrar editor de fechas
  document.getElementById('change_f').addEventListener('click', function(){
    document.getElementById('reserva_f').classList.add('hidden');
    document.getElementById('alert_fechas').style.display = 'none';
    document.getElementById('fechas_r').classList.remove('hidden');
  });

  // Recalcular con nuevas fechas
  document.getElementById('fecha_update').addEventListener('click', function(){
    var fechaIn  = document.getElementById('reservas-fecha_entrada').value;
    var fechaOut = document.getElementById('reservas-fecha_salida').value;
    var horaIn   = document.getElementById('reservas-hora_entrada').value;
    var horaOut  = document.getElementById('reservas-hora_salida').value;

    var alertBox = document.getElementById('alert_fechas');

    if (!fechaIn || !fechaOut){
      alertBox.style.display = 'block';
      return;
    }

    var cant = diffDays(fechaIn, fechaOut, horaIn, horaOut);
    if (cant <= 0){
      alertBox.style.display = 'block';
      return;
    }

    // Guardar días
    document.getElementById('cantdias').value = cant;

    // UI loading
    document.querySelectorAll('.loading').forEach(function(el){ el.style.display = 'block'; });

    // Actualizar resumen visible
    document.getElementById('fentrada').innerText = fechaIn;
    document.getElementById('hentrada').innerText = horaIn;
    document.getElementById('fsalida').innerText  = fechaOut;
    document.getElementById('hsalida').innerText  = horaOut;

    // Pintar costos
    setTimeout(function(){
      recalcAndPaint(cant, horaIn, horaOut);
      document.getElementById('fechas_r').classList.add('hidden');
      document.getElementById('reserva_f').classList.remove('hidden');
      document.querySelectorAll('.loading').forEach(function(el){ el.style.display = 'none'; });
    }, 500);
  });

  // Handlers de envío (mantienen tu flujo actual)
  function lockAndSubmit(btnId, type, plan){
    var btn = document.getElementById(btnId);
    btn.setAttribute('disabled','disabled');
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Espere por favor...';

    document.getElementById('type_service').value = type;
    document.getElementById('plan').value = plan;

    // Disparar submit del form
    btn.closest('form').submit();
  }

  document.getElementById('economic').addEventListener('click', function(e){
    e.preventDefault(); lockAndSubmit('economic', 0, 4);
  });
  document.getElementById('bronce').addEventListener('click', function(e){
    e.preventDefault(); lockAndSubmit('bronce', 0, 1);
  });
  document.getElementById('plata').addEventListener('click', function(e){
    e.preventDefault(); lockAndSubmit('plata', 9, 2);
  });
  document.getElementById('oro').addEventListener('click', function(e){
    e.preventDefault(); lockAndSubmit('oro', 12, 3);
  });

})();
JS
);
?>