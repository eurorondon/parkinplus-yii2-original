<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Reservas */
/* @var $form yii\widgets\ActiveForm */


$this->title = Yii::$app->name . ' | Nueva Reserva';


Modal::begin([
	'header' => 'Información del servicio',
	'id' => 'mas_info_st',
	'size' => 'modal-lg',

]);

echo "<div id='modalContent'>
 <div class='row'>
    <div class='col-lg-12'>

        <p align='justify'>PARKINGPLUS  es la solución a su problema de estacionamiento en el Aeropuerto, evitándole así las molestias de buscar aparcamiento e ir desde el parking a la terminal y viceversa, cargado con su equipaje.</p>

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

        <p align='justify'>T2 Diríjase a la T2 de salidas tomando el ascensor o las rampas mecánicas, siga las indicaciones de chek in- facturación  y sitúese en la puerta 7. </p>

        <p align='justify'>T4 Diríjase a la T4 de salidas. Tome un ascensor de cristal y suba a salidas. Salgan al exterior y cruce los carriles de taxi, giren a la derecha para situarse al final de la terminal en la puerta 5 ò 6.</p>

        <p align='justify'>Hora de devolución. No se preocupe si su vuelo se retrasa, nosotros conocemos que los horarios de vuelos pueden sufrir modificaciones. pero les recordamos que la modificación de horarios y/o vuelos, así como cambios de fecha en su devolución sin aviso previo puede suponer esperas de hasta 2 horas y/o recargos en el importe final.</p>


        <p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Servicio estándar</p>

        <p align='justify'>Servicio de aparcamiento en recinto cerrado al aire libre y vigilado 24hrs.</p>

    </div>
</div> 
  </div>";

Modal::end();

Modal::begin([
	'header' => 'Información del servicio',
	'id' => 'mas_info_pre',
	'size' => 'modal-lg',

]);

echo "<div id='modalContent'>
 <div class='row'>
    <div class='col-lg-12'>

        <p align='justify'>PARKINGPLUS  es la solución a su problema de estacionamiento en el Aeropuerto, evitándole así las molestias de buscar aparcamiento e ir desde el parking a la terminal y viceversa, cargado con su equipaje.</p>

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

        <p align='justify'>T2 Diríjase a la T2 de salidas tomando el ascensor o las rampas mecánicas, siga las indicaciones de chek in- facturación  y sitúese en la puerta 7. </p>

        <p align='justify'>T4 Diríjase a la T4 de salidas. Tome un ascensor de cristal y suba a salidas. Salgan al exterior y cruce los carriles de taxi, giren a la derecha para situarse al final de la terminal en la puerta 5 ò 6.</p>

        <p align='justify'>Hora de devolución. No se preocupe si su vuelo se retrasa, nosotros conocemos que los horarios de vuelos pueden sufrir modificaciones. pero les recordamos que la modificación de horarios y/o vuelos, así como cambios de fecha en su devolución sin aviso previo puede suponer esperas de hasta 2 horas y/o recargos en el importe final.</p>


        <p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Servicio premium</p>

        <p align='justify'>Servicio de aparcamiento en recinto cerrado al aire libre, techado y vigilado 24hrs, le incluye servicio de lavado exterior de cortesía. (si usted requiere una limpieza con más detalle puede contratar el servicio de limpieza exterior lo que supondrá un recargo adicional).</p>

    </div>
</div> 
  </div>";

Modal::end();

Modal::begin([
	'header' => 'Información del servicio',
	'id' => 'mas_info_pri',
	'size' => 'modal-lg',

]);

echo "<div id='modalContent'>
 <div class='row'>
    <div class='col-lg-12'>

        <p align='justify'>PARKINGPLUS  es la solución a su problema de estacionamiento en el Aeropuerto, evitándole así las molestias de buscar aparcamiento e ir desde el parking a la terminal y viceversa, cargado con su equipaje.</p>

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

        <p align='justify'>T2 Diríjase a la T2 de salidas tomando el ascensor o las rampas mecánicas, siga las indicaciones de chek in- facturación  y sitúese en la puerta 7. </p>

        <p align='justify'>T4 Diríjase a la T4 de salidas. Tome un ascensor de cristal y suba a salidas. Salgan al exterior y cruce los carriles de taxi, giren a la derecha para situarse al final de la terminal en la puerta 5 ò 6.</p>

        <p align='justify'>Hora de devolución. No se preocupe si su vuelo se retrasa, nosotros conocemos que los horarios de vuelos pueden sufrir modificaciones. pero les recordamos que la modificación de horarios y/o vuelos, así como cambios de fecha en su devolución sin aviso previo puede suponer esperas de hasta 2 horas y/o recargos en el importe final.</p>


        <p align='left' style='font-weight: bold; margin-bottom: 15px; margin-top: 30px; color: #921007'>Servicio priority</p>

        <p align='justify'>Servicio de aparcamiento en recinto cerrado y vigilado 24hrs, le incluye servicio de lavado completo interior y exterior.</p>

    </div>
</div> 
  </div>";

Modal::end();

$cant = count($precio_diario);
$num = 1;
for ($i = 0; $i < $cant; $i++) { ?>
	<input class="form-control" style="margin-bottom: 20px" type="hidden" id="precio-diario<?= $num ?>"
		value="<?= $precio_diario[$i]['precio'] ?>">
<?php $num++;
} ?>

<?php foreach ($servicios as $ser) { ?>
	<input class="form-control" style="margin-bottom: 20px" type="hidden" id="servicio-<?= $ser['id'] ?>"
		value="<?= $ser['costo'] ?>">
<?php
}
if (!is_null($temporada)) {
?>
	<input class="form-control" style="margin-bottom: 20px" type="hidden" id="pre_dia_temp"
		value="<?= $temporada->precio ?>">
	<input class="form-control" style="margin-bottom: 20px" type="hidden" id="fecha_ini_temp"
		value="<?= $temporada->fecha_inicio . ' ' . $temporada->hora_inicio ?>">
	<input class="form-control" style="margin-bottom: 20px" type="hidden" id="fecha_fin_temp"
		value="<?= $temporada->fecha_fin . ' ' . $temporada->hora_fin ?>">
<?php } ?>


<div class="reservas-form container mb-4" style="margin-top: 105px">
	<div>
		<?php $form = ActiveForm::begin([
			'action' => Yii::$app->urlManager->createUrl('/site/fechas'),
		]); ?>
		<input class="form-control" style="margin-bottom: 20px" type="hidden" id="nocturno"
			value="<?= $nocturno->costo ?>">

		<input class="form-control" style="margin-bottom: 20px" type="hidden" id="temporada"
			value="<?= !is_null($temporada) ? 1 : 0 ?>">

		<input type="hidden" id="cantdias" name="cantdias" value="<?= $cant_dias ?>">
		<input type="hidden" id="type_service" name="type">
		<input type="hidden" id="precio_dia" name="precio_dia" value="<?= $precio_dia ?>">
		<div class="container">

			<div class="row">
				<div class="col-lg-12">
					<div class="subtitulo-reserva d-flex justify-content-center align-items-center"
						style="margin-bottom: 20px;">
						<h3>Información de Reserva</h3>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12 flex-sm-column flex-md-column flex-lg-row" id="reserva_f"
					style="display:flex; border: 1px solid #961007; line-height: 50px; font-weight: 600;">
					<div class="col-sm-12 col-md-4 d-flex justify-content-sm-center">
						<i>Desde: </i>
						<span id="fentrada" style="padding-left: 5px;"> <?= $model->fecha_entrada ?></span>
						<span id="hentrada" style="padding-left: 5px;"><?= $model->hora_entrada ?></span>
					</div>

					<div class="col-sm-12 col-md-4  d-flex justify-content-sm-center">
						<i>Hasta: </i>
						<span id="fsalida" style="padding-left: 5px;"> <?= $model->fecha_salida ?></span>
						<span id="hsalida" style="padding-left: 5px;"><?= $model->hora_salida ?></span>
					</div>

					<div class="col-sm-12 col-md-4  d-flex justify-content-sm-center my-2">
						<button type="button" id="change_f" class="btn"
							style="background-color:#961007; margin-left:10px; color:#fff">
							<span class="glyphicon glyphicon-calendar"></span>
							Cambiar fechas
						</button>
					</div>
				</div>
			</div>

			<div class="row"
				style="display:none; border: 1px solid #961007; font-weight: 600; height: 100px; align-items: center"
				id="fechas_r">
				<div class="col-lg-12 text-danger" style="display:none" id="alert_fechas">
					Verifique las fechas y horas seleccionadas
				</div>
				<div class="col-lg-3">
					<?php $recogida = '<span>Recogida &nbsp;</span><span class="tooltipcurved tooltipcurved-west">
				<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
					<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
				</svg></span>
				<span class="tooltipcurved-content">Día y Hora que dejarás el coche con uno de nuestros conductores calificados.</span>
			</span>'; ?>

					<?= $form->field($model, 'fecha_entrada')->widget(DatePicker::classname(), [
						'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
						'language' => 'es',
						'pluginOptions' => [
							'orientation' => 'bottom left',
							'autoclose' => true,
							'format' => 'dd-mm-yyyy',
							'startDate' => date('d-m-Y'),
						]
					])->label($recogida); ?>
				</div>
				<div class="col-lg-2">
					<?= $form->field($model, 'hora_entrada')->widget(TimePicker::classname(), [
						'options' => ['style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
						'pluginOptions' => [
							'showMeridian' => false,
						]
					]); ?>
				</div>

				<div class="col-lg-3">
					<?php $devolucion = '<span>Devolución &nbsp;</span><span class="tooltipcurved tooltipcurved-west">
				<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
					<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
				</svg></span>
				<span class="tooltipcurved-content">Día y Hora en el que te devolveremos tu coche.</span>
			</span>'; ?>
					<?= $form->field($model, 'fecha_salida')->widget(DatePicker::classname(), [
						'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
						'language' => 'es',
						'pluginOptions' => [
							'orientation' => 'bottom left',
							'autoclose' => true,
							'format' => 'dd-mm-yyyy',
							'startDate' => date('d-m-Y'),
						]
					])->label($devolucion); ?>
				</div>
				<div class="col-lg-2">
					<?= $form->field($model, 'hora_salida')->widget(TimePicker::classname(), [
						'options' => ['style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
						'pluginOptions' => [
							'showMeridian' => false,
						]
					]); ?>
				</div>
				<div class="col-lg-2" style="display: flex; justify-content: center; align-items: center; height: 90%;">
					<button type="button" id="fecha_update" class="btn"
						style="background-color:#961007; color:#fff">Actualizar fechas</button>
				</div>
			</div>

			<div class="row mt-4 p-4"
				style="border: 1px solid #961007; border-radius: 5px; display: flex;align-items: center;justify-content: space-between; background: #eee;">

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<div class="counter_container"
						style="border: 1px solid #961007;    width: 160px;height: 160px;background: #fff;">
						<div class="counter__text">
							<div class="icon">
								<?= Html::img('@web/images/bronce.png', ['class' => 'counter__icon']) ?>
							</div>
						</div>
						<div class="counter__description">STANDAR</div>
					</div>
				</div>

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<div class="counter_container"
						style="border: 1px solid #961007;    width: 160px;height: 160px;background: #fff;">
						<div class="counter__text">
							<div class="icon">
								<?= Html::img('@web/images/chofer1.png', ['class' => 'counter__icon']) ?>
							</div>

						</div>
						<div class="counter__description">Recogida por<br> chofer</div>
					</div>
				</div>

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<div class="counter_container"
						style="border: 1px solid #961007;    width: 160px;height: 160px;background: #fff;">
						<div class="counter__text">
							<div class="icon">
								<?= Html::img('@web/images/car.png', ['class' => 'counter__icon']) ?>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<h4>Servicios</h4>
					<p>- Plaza reservada</p>
					<p class="noctur" style="display:none">- Especial nocturnidad entre 00:30 y 03:45</p>
					<?= Html::button('más inf +', [
						'class' => 'btn btn-default btn-sm text-dark',
						'style' => ['font-size' => '12px'],
						'id' => 'BtnModalId',
						'data-toggle' => 'modal',
						'data-target' => '#mas_info_st',
					]) ?>
				</div>

				<div class="col-lg-3"
					style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
					<svg width="48" height="48" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="#000"
						style="display:none" class="loading">
						<g fill="none" fill-rule="evenodd" transform="translate(1 1)" stroke-width="2">
							<circle cx="22" cy="22" r="6" stroke-opacity="0">
								<animate attributeName="r" begin="1.5s" dur="3s" values="6;22" calcMode="linear"
									repeatCount="indefinite" />
								<animate attributeName="stroke-opacity" begin="1.5s" dur="3s" values="1;0"
									calcMode="linear" repeatCount="indefinite" />
								<animate attributeName="stroke-width" begin="1.5s" dur="3s" values="2;0"
									calcMode="linear" repeatCount="indefinite" />
							</circle>
							<circle cx="22" cy="22" r="6" stroke-opacity="0">
								<animate attributeName="r" begin="3s" dur="3s" values="6;22" calcMode="linear"
									repeatCount="indefinite" />
								<animate attributeName="stroke-opacity" begin="3s" dur="3s" values="1;0"
									calcMode="linear" repeatCount="indefinite" />
								<animate attributeName="stroke-width" begin="3s" dur="3s" values="2;0" calcMode="linear"
									repeatCount="indefinite" />
							</circle>
							<circle cx="22" cy="22" r="8">
								<animate attributeName="r" begin="0s" dur="1.5s" values="6;1;2;3;4;5;6"
									calcMode="linear" repeatCount="indefinite" />
							</circle>
						</g>
					</svg>
					<div id="costo" style="font-weight: 700; margin:5px 0px; font-size: 2rem;"></div>
					<button type="submit" id="bronce" class="btn"
						style="background-color:#961007; color:#FFF; padding: 15px 10px">Proceder a Reservar <span
							class="glyphicon glyphicon-send"></span></button>
				</div>

			</div>

			<div class="row mt-4 p-4"
				style="border: 1px solid #961007; border-radius: 5px; display: flex;align-items: center;justify-content: space-between; background: #eee;">

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<div class="counter_container "
						style="border: 1px solid #961007;    width: 160px;height: 160px;background: #fff;">
						<div class="counter__text">
							<div class="icon">
								<?= Html::img('@web/images/plata.png', ['class' => 'counter__icon']) ?>
							</div>
						</div>
						<div class="counter__description">PREMIUM</div>
					</div>
				</div>

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<div class="counter_container"
						style="border: 1px solid #961007;    width: 160px;height: 160px;background: #fff;">
						<div class="counter__text">
							<div class="icon">
								<?= Html::img('@web/images/chofer1.png', ['class' => 'counter__icon']) ?>
							</div>

						</div>
						<div class="counter__description">Recogida por<br> chofer</div>
					</div>
				</div>

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<div class="counter_container"
						style="border: 1px solid #6c757d;    width: 160px;height: 160px;background: #fff;">
						<div class="counter__text">
							<div class="icon">
								<?= Html::img('@web/images/garage-car.png', ['class' => 'counter__icon']) ?>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<h4>Servicios</h4>
					<p>- Lavado exterior cortesia</p>
					<p>- Parking techado</p>
					<p class="noctur" style="display:none">- Especial nocturnidad entre 00:30 y 03:45</p>
					<?= Html::button('más inf +', [
						'class' => 'btn btn-default btn-sm text-dark',
						'style' => ['font-size' => '12px'],
						'id' => 'BtnModalId',
						'data-toggle' => 'modal',
						'data-target' => '#mas_info_pre',
					]) ?>
				</div>

				<div class="col-lg-3"
					style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
					<svg width="48" height="48" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="#000"
						style="display:none" class="loading">
						<g fill="none" fill-rule="evenodd" transform="translate(1 1)" stroke-width="2">
							<circle cx="22" cy="22" r="6" stroke-opacity="0">
								<animate attributeName="r" begin="1.5s" dur="3s" values="6;22" calcMode="linear"
									repeatCount="indefinite" />
								<animate attributeName="stroke-opacity" begin="1.5s" dur="3s" values="1;0"
									calcMode="linear" repeatCount="indefinite" />
								<animate attributeName="stroke-width" begin="1.5s" dur="3s" values="2;0"
									calcMode="linear" repeatCount="indefinite" />
							</circle>
							<circle cx="22" cy="22" r="6" stroke-opacity="0">
								<animate attributeName="r" begin="3s" dur="3s" values="6;22" calcMode="linear"
									repeatCount="indefinite" />
								<animate attributeName="stroke-opacity" begin="3s" dur="3s" values="1;0"
									calcMode="linear" repeatCount="indefinite" />
								<animate attributeName="stroke-width" begin="3s" dur="3s" values="2;0" calcMode="linear"
									repeatCount="indefinite" />
							</circle>
							<circle cx="22" cy="22" r="8">
								<animate attributeName="r" begin="0s" dur="1.5s" values="6;1;2;3;4;5;6"
									calcMode="linear" repeatCount="indefinite" />
							</circle>
						</g>
					</svg>
					<div id="costo_t" style="font-weight: 700; margin:5px 0px; font-size: 2rem;"></div>
					<button type="submit" id="plata" class="btn"
						style="background-color:#961007; color:#FFF; padding: 15px 10px">Proceder a Reservar <span
							class="glyphicon glyphicon-send"></span></button>
				</div>

			</div>

			<div class="row mt-4 p-4"
				style="border: 1px solid #961007; border-radius: 5px; display: flex;align-items: center;justify-content: space-between; background: #eee;">

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<div class="counter_container"
						style="border: 1px solid #961007;    width: 160px;height: 160px;background: #fff;">
						<div class="counter__text">
							<div class="icon">
								<?= Html::img('@web/images/oro.png', ['class' => 'counter__icon']) ?>
							</div>
						</div>
						<div class="counter__description">
							PRIORITY
						</div>
					</div>
				</div>

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<div class="counter_container"
						style="border: 1px solid #961007;    width: 160px;height: 160px;background: #fff;">
						<div class="counter__text">
							<div class="icon">
								<?= Html::img('@web/images/chofer1.png', ['class' => 'counter__icon']) ?>
							</div>

						</div>
						<div class="counter__description">Recogida por<br> chofer</div>
					</div>
				</div>

				<div class="col-lg-2"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">

					<div class="counter_container"
						style="border: 1px solid #961007;    width: 160px;height: 160px;background: #fff;">
						<div class="counter__text">
							<div class="icon">
								<?= Html::img('@web/images/llaves.png', ['class' => 'counter__icon']) ?>
							</div>
						</div>
					</div>
				</div>

				<div class="col-lg-2 text-left"
					style="display: flex;align-items: center;justify-content: center;flex-direction: column;">
					<h4>Servicios</h4>
					<p>- Lavado interior</p>
					<p>- Lavado exterior</p>
					<p>- Custodia de llaves</p>
					<p class="noctur" style="display:none">- Especial nocturnidad entre 00:30 y 03:45</p>
					<?= Html::button('más inf +', [
						'class' => 'btn btn-default btn-sm text-dark',
						'style' => ['font-size' => '12px'],
						'id' => 'BtnModalId',
						'data-toggle' => 'modal',
						'data-target' => '#mas_info_pri',
					]) ?>
				</div>

				<div class="col-lg-3"
					style="display: flex;flex-direction: column;justify-content: center;align-items: center;">
					<svg width="48" height="48" viewBox="0 0 45 45" xmlns="http://www.w3.org/2000/svg" stroke="#000"
						style="display:none" class="loading">
						<g fill="none" fill-rule="evenodd" transform="translate(1 1)" stroke-width="2">
							<circle cx="22" cy="22" r="6" stroke-opacity="0">
								<animate attributeName="r" begin="1.5s" dur="3s" values="6;22" calcMode="linear"
									repeatCount="indefinite" />
								<animate attributeName="stroke-opacity" begin="1.5s" dur="3s" values="1;0"
									calcMode="linear" repeatCount="indefinite" />
								<animate attributeName="stroke-width" begin="1.5s" dur="3s" values="2;0"
									calcMode="linear" repeatCount="indefinite" />
							</circle>
							<circle cx="22" cy="22" r="6" stroke-opacity="0">
								<animate attributeName="r" begin="3s" dur="3s" values="6;22" calcMode="linear"
									repeatCount="indefinite" />
								<animate attributeName="stroke-opacity" begin="3s" dur="3s" values="1;0"
									calcMode="linear" repeatCount="indefinite" />
								<animate attributeName="stroke-width" begin="3s" dur="3s" values="2;0" calcMode="linear"
									repeatCount="indefinite" />
							</circle>
							<circle cx="22" cy="22" r="8">
								<animate attributeName="r" begin="0s" dur="1.5s" values="6;1;2;3;4;5;6"
									calcMode="linear" repeatCount="indefinite" />
							</circle>
						</g>
					</svg>
					<div id="costo_i" style="font-weight: 700; margin:5px 0px; font-size: 2rem;"></div>
					<button type="submit" id="oro" class="btn"
						style="background-color:#961007; color:#fff; padding: 15px 10px">Proceder a Reservar <span
							class="glyphicon glyphicon-send"></span></button>
				</div>
			</div>

			<div class="col-lg-12">
				<div class="col-lg-12"><br></div>
			</div>

		</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>

<?php
$this->registerJs(" 

    $( document ).ready(function() {

	  precio_dia = $('#precio_dia').val();
      cant = $('#cantdias').val();
      precio1 = $('#precio-diario1').val();
      precio2 = $('#precio-diario2').val();
      precio3 = $('#precio-diario3').val();
      precio4 = $('#precio-diario4').val();
      precio5 = $('#precio-diario5').val();
      precio6 = $('#precio-diario6').val();
      precio7 = $('#precio-diario7').val();
      precio8 = $('#precio-diario8').val();
      precio9 = $('#precio-diario9').val();
      precio10 = $('#precio-diario10').val();

      precio11 = $('#precio-diario11').val();
      precio12 = $('#precio-diario12').val();
      precio13 = $('#precio-diario13').val();
      precio14 = $('#precio-diario14').val();
      precio15 = $('#precio-diario15').val();
      precio16 = $('#precio-diario16').val();
      precio17 = $('#precio-diario17').val();
      precio18 = $('#precio-diario18').val();
      precio19 = $('#precio-diario19').val();
      precio20 = $('#precio-diario20').val();

      precio21 = $('#precio-diario21').val();
      precio22 = $('#precio-diario22').val(); 
      precio23 = $('#precio-diario23').val(); 
      precio24 = $('#precio-diario24').val(); 
      precio25 = $('#precio-diario25').val(); 
      precio26 = $('#precio-diario26').val(); 
      precio27 = $('#precio-diario27').val(); 
      precio28 = $('#precio-diario28').val(); 
      precio29 = $('#precio-diario29').val(); 
      precio30 = $('#precio-diario30').val(); 

	  var total = 0;

      if (cant == 1) { total = parseFloat(precio1); }                     
      if (cant == 2) { total = parseFloat(precio2); }
      if (cant == 3) { total = parseFloat(precio3); }
      if (cant == 4) { total = parseFloat(precio4); }
      if (cant == 5) { total = parseFloat(precio5); }
      if (cant == 6) { total = parseFloat(precio6); }
      if (cant == 7) { total = parseFloat(precio7); }
      if (cant == 8) { total = parseFloat(precio8); }
      if (cant == 9) { total = parseFloat(precio9); }
      if (cant == 10) { total = parseFloat(precio10); }

      if (cant == 11) { total = parseFloat(precio11); }                     
      if (cant == 12) { total = parseFloat(precio12); }
      if (cant == 13) { total = parseFloat(precio13); }
      if (cant == 14) { total = parseFloat(precio14); }
      if (cant == 15) { total = parseFloat(precio15); }
      if (cant == 16) { total = parseFloat(precio16); }
      if (cant == 17) { total = parseFloat(precio17); }
      if (cant == 18) { total = parseFloat(precio18); }
      if (cant == 19) { total = parseFloat(precio19); }
      if (cant == 20) { total = parseFloat(precio20); }

      if (cant == 21) { total = parseFloat(precio21); }                     
      if (cant == 22) { total = parseFloat(precio22); }
      if (cant == 23) { total = parseFloat(precio23); }
      if (cant == 24) { total = parseFloat(precio24); }
      if (cant == 25) { total = parseFloat(precio25); }
      if (cant == 26) { total = parseFloat(precio26); }
      if (cant == 27) { total = parseFloat(precio27); }
      if (cant == 28) { total = parseFloat(precio28); }
      if (cant == 29) { total = parseFloat(precio29); }
      if (cant == 30) { total = parseFloat(precio30); }                        

      /*if (cant > 30) { 
        var cant_dias = cant - 30;
        var precio_relativo = parseFloat(precio30);
        var total = precio_relativo + (cant_dias * parseFloat(precio_dia)); 
      }*/
	
	    if(cant > 30){
			while (cant > 30) {
				total +=  parseFloat(precio30);
				cant =  cant - 30;

				/*console.log(cant);
				console.log(total);*/
			}

			if(cant >= 18){
				total +=  parseFloat(precio30);
				//console.log(total);
			}else {
				total += (cant * parseFloat(precio_dia));
				//console.log(total);
			}
		}

		var nocturnidad = 0;
	  if(($('#reservas-hora_entrada').val() >= '00:30' && $('#reservas-hora_entrada').val() <= '03:45') || ($('#reservas-hora_salida').val() >= '00:30' && $('#reservas-hora_salida').val() <= '03:45')){
		nocturnidad = parseFloat($('#nocturno').val());
		$('.noctur').css('display', 'block');
	  } 
		var costo = total + nocturnidad;
		var costo_t = parseFloat($('#servicio-9').val()) + nocturnidad + total;

            var costo_i = parseFloat($('#servicio-12').val()) + nocturnidad + total;
	  
		/*console.log(parseFloat($('#servicio-12').val()));
		console.log(nocturnidad );
		console.log(parseFloat($('#servicio-2').val()));*/
		
	  $('#costo').append(costo.toFixed(2)+ '€');
	  $('#costo_t').append( costo_t.toFixed(2) + '€');
	  $('#costo_i').append(costo_i.toFixed(2)+ '€');
    });      
	
	$('#change_f').on('click', function(){
		$('#reserva_f').css('display', 'none');
		$('#alert_fechas').css('display', 'none');
		$('#fechas_r').css('display', 'flex');
	});
	
	
	$('#fecha_update').on('click', function () {
		var fecha_in = $('#reservas-fecha_entrada').val();
		var fecha_out = $('#reservas-fecha_salida').val();
		var hora_in = $('#reservas-hora_entrada').val();
		var hora_out = $('#reservas-hora_salida').val();
		var precio_dia = $('#precio_dia').val();

		if(fecha_in !== '' || fecha_out !== ''){
			$('#reservas-fecha_salida').css('border', '1px solid #ccc');
		}
		
		if(fecha_in === ''){
			$('#alert_fechas').css('display', 'block');
			$('#reservas-fecha_entrada').css('border', '1px solid red');
			return;
		} 
		
		if(fecha_out === ''){
			$('#alert_fechas').css('display', 'block');
			$('#reservas-fecha_salida').css('border', '1px solid red');
			return;
		}
			
		  var total_servicio = 0;
		  var total = 0;
		  var nocturnidad = 0;
		  
		  fechaE = convertDateFormat(fecha_in);
		  fechaS = convertDateFormat(fecha_out);

		  fechaInicio = new Date(fechaE).getTime();
		  fechaFin = new Date(fechaS).getTime();
		  diff = fechaFin - fechaInicio;
		  cant = diff / (1000 * 60 * 60 * 24);

		  inh = hora_in.split(':');
		  outh = hora_out.split(':');
		  min = outh[1] - inh[1];
		  hour_carry = 0;
		  if (min < 0) {
			min += 60;
			hour_carry += 1;
		  }
		  hour = outh[0] - inh[0] - hour_carry;
		  min = ((min / 60) * 100).toString();
		  diffh = hour + ':' + min.substring(0, 2);
		  if (hour > 0) {
			cant = cant + 1;
		  }
		  
		  
		  if(cant <= 0){
		  	$('#alert_fechas').css('display', 'block');
			return;
		  }
		  
		  $('#reserva_f').css('display', 'flex');
		  $('#fechas_r').css('display', 'none');
		  $('.loading').css('display', 'block');
		  
		  $('#cantdias').val(cant);

		 if(cant > 30){
			while (cant > 30) {
				total +=  parseFloat(precio30);
				cant =  cant - 30;

				/*console.log(cant);
				console.log(total);*/
			}

			if(cant >= 18){
				total +=  parseFloat(precio30);
				//console.log(total);
			}else {
				total += (cant * parseFloat(precio_dia));
				//console.log(total);
			}
		}else{
			total = parseFloat($('#precio-diario'+cant).val());
		}

		  
		
		  if((hora_in >= '00:30' && hora_in <= '03:45') || (hora_out >= '00:30' && hora_out <= '03:45')){
			nocturnidad = parseFloat($('#nocturno').val());
			$('.noctur').css('display', 'block');
		  } else {
		  	$('.noctur').css('display', 'none');
		  }
					
		  var temp = Number($('#temporada').val()) == 1 ? $('#pre_dia_temp').val() : 0;


		  var costo = total + nocturnidad
		  var costo_t = parseFloat($('#servicio-9').val()) + nocturnidad + total;
              var costo_i = parseFloat($('#servicio-12').val()) + nocturnidad + total;
		    
		  $('#costo, #costo_t, #costo_i').html('');
		  $('#fentrada').html('').append(fecha_in);
		  $('#hentrada').html('').append(hora_in);
		  $('#fsalida').html('').append(fecha_out);
		  $('#hsalida').html('').append(hora_out);
		  
		  setTimeout(() => {
		  		$('.loading').css('display', 'none');
			  $('#costo').append(costo.toFixed(2)+ '€');
			  $('#costo_t').append( costo_t.toFixed(2) + '€');
			  $('#costo_i').append(costo_i.toFixed(2)+ '€');
		  }, 1000);
  
		});
	
		$('#bronce').click(function(e){
			e.preventDefault();
				/*$('#oro, #plata').css('background-color', '#000');
				$(this).css('background-color', '#b0cb21');*/
				$('#type_service').val(0);
				//$('#boton-reserva').attr('disabled', false);
			$(this)
				.text('')
				.removeClass('btn-success')
				.addClass('btn-primary')
				.html('<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span> Espere por favor...')
				.attr('disabled', 'disabled')
				.trigger('submit');
		  
			});
			$('#plata').click(function(e){
				e.preventDefault();
				$(this)
				.text('')
				.removeClass('btn-success')
				.addClass('btn-primary')
				.html('<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span> Espere por favor...')
				.attr('disabled', 'disabled')
				.trigger('submit');
				/*$('#oro, #bronce').css('background-color', '#000');
				$(this).css('background-color', '#b0cb21');*/
				$('#type_service').val(9);
				//$('#boton-reserva').attr('disabled', false);
			});

			$('#oro').click(function(e){
				e.preventDefault();
				$(this)
				.text('')
				.removeClass('btn-success')
				.addClass('btn-primary')
				.html('<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span> Espere por favor...')
				.attr('disabled', 'disabled')
				.trigger('submit');
				/*$('#plata, #bronce').css('background-color', '#000');
				$(this).css('background-color', '#b0cb21');*/
				$('#type_service').val(12);
				//$('#boton-reserva').attr('disabled', false);
			});
			
		function convertDateFormat(string) {
			var info = string.split('-');
			return info[2] + '-' + info[1] + '-' + info[0];
		}	

		$('#finalizar').on('click', function(){
			if($('#reservas-factura').is(':checked') && $('#reservas-nif').val() === '' || $('#reservas-razon_social').val() === '' || $('#reservas-direccion').val() === '' || $('#reservas-ciudad').val() === '' || $('#reservas-provincias').val() === '' || $('#reservas-pais').val() === ''){
				$('#factura_cliente').text('').append('<div class=\"subtitulo-reserva\" style=\"text-decoration: none;padding: 12px 0;\">* Debe llenar los campos de facturación</div>')
				$('html, body').animate({ scrollTop: $('#facturacion').offset().top }, 1000);
			}else if($('#reservas-id_tipo_pago').val() !== '' && $('#reservas-condiciones').is(':checked') && $('#reservas-id_tipo_pago').val() !== '' && $('#clientes-correo').val() !== '' && $('#clientes-movil').val() !== ''){
			  $(this)
				.text('')
				.removeClass('btn-success')
				.addClass('btn-primary')
				.html('<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span> Espere...')
				.attr('disabled', 'disabled')
				.trigger('submit');
			}  
		  });
	");
?>