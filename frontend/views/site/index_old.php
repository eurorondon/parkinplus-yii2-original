<?php

// Si han aceptado la política
if (isset($_REQUEST['politica-cookies'])) {
    // Calculamos la caducidad, en este caso un año
    $caducidad = time() + (60 * 60 * 24 * 365);
    // Crea una cookie con la caducidad
    setcookie('politica', '1', $caducidad);
}

use yii\helpers\Html;
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use kartik\time\TimePicker;
use yii\bootstrap\Modal;
use yii\captcha\Captcha;

Modal::begin([
    'header' => 'SERVICIO DE PARKING',
    'id' => 'parking',
    'size' => 'modal-lg',
    'headerOptions' => ['style' => 'display:block']
]); ?>
<div id='modalContent'>
	<div class="row">
		<div class="img-min" style="color: #fff !important;padding-top: 18px;font-size: 1em;border-radius: 10px;width: 47vw;height: 100%;display:flex;">
			<div class="costo" style="background-color: transparent; color:#FFF; border: 0px">
				Aparcamadrid es operado por ParkingPlus, bajo las normas y condiciones generales de contrato y servicios de Parking Plus.
			</div> 
		</div>                
	</div>
</div>
<?php
Modal::end();
Modal::begin([
    'header' => 'COSTO POR SERVICIO',
    'id' => 'fecha_reserva',
    'size' => 'modal-sm',

]); ?>

<input type="hidden" id="fecha-in" name="fechainicio">
<input type="hidden" id="fecha-out" name="fechafin">
<input type="hidden" id="hora-in" name="horainicio">
<input type="hidden" id="hora-out" name="horafin">
<input type="hidden" id="costo-servicio" name="costo_service">
<input type="hidden" id="cdias" name="cdias">
<div id='modalContent'></div>

<?php
Modal::end();
Modal::begin([
    'header' => 'SOLICITUD DE FACTURA',
    'id' => 'solicitud_factura',
    'size' => 'modal-md',
    'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);
echo "<div id='modalFactura'></div>";
Modal::end();
$this->title = Yii::$app->name . ' - Aparcamientos Larga Estancia en Barajas';
?>
<button id="aviso" type="button" class="display:none" data-toggle="modal" data-target="#parking">Open Aviso</button>
<div class="site-index">
	<section id="hero" class="d-flex align-items-center">
		<div class="container">
			<div class="row">
				<div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
					<p class="h2generada"> TU VUELAS</p> <br />
					<h1>NOSOTROS APARCAMOS</h1>
					<h2>Aereopuerto de Madrid - Barajas <br /><br />
						Recogeremos tu coche en el aeropuerto el día y a la hora que nos digas. A tu vuelta, te estaremos esperando
						en la puerta de la terminal.</h2>
				</div>
				<div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
					<?= Html::img('@web/images/hero-img.png', ['class' => 'img-logo animated', 'loading' => 'lazy']) ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 aviso animated">
					<h3> <i>Reservas en solo <i class="bi bi-2-circle"></i> minutos <i class="bi bi-stopwatch"></i> </i></h3>
					<h3><i> No necesitas registrar ninguna Tarjeta de Crédito <i class="bi bi-window-dash"></i></i></h3>
				</div>
			</div>
			<?php
            $form = ActiveForm::begin([
                'id' => 'fechas-form',
                'options' => [
                    'autocomplete' => 'off',
                ],
            ]);
?>
			<div class="row" id="calc_reserva">
				<div class="col-sm-12 col-lg-5">
					<div class="card">
						<div class="card-body1">
							<div class="row">
								<div class="col-sm-12">
									<div class="col-sm-4">
										<div class="calculadora">
											<p class="centrado">RECOGIDA</p>
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4 caja_reser">
										<div class="input-group mb-4" style="bottom: -16px;">
											<div class="input-group-prepend" id="rec">
												<span class="input-group-text1" id="basic-addon1">
													<?= $form->field($model, 'fechae')->widget(DatePicker::classname(), [
                                            'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()', 'style' => 'width: 100%;'],
                                            'language' => 'es',
                                            'removeButton' => false,
                                            'pluginOptions' => [
                                                'orientation' => 'bottom left',
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                                'startDate' => date('d-m-Y'),
                                                'todayHighlight' => true,
                                            ]
                                        ])->label(false); ?>
											</div>
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="input-group mb-4" style="bottom: -16px;">
											<div class="input-group-prepend">
												<span class="input-group-text1" id="basic-addon1">
													<?= $form->field($model, 'horae')->widget(TimePicker::classname(), [
                                            'pluginOptions' => [
                                                'showMeridian' => false,
                                            ]
                                        ])->label(false); ?>
												</span>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-lg-5 separa">
					<div class="card">
						<div class="card-body1">
							<div class="row">
								<div class="col-sm-12">
									<div class="col-sm-4 col-lg-4">
										<div class="calculadora">
											<p class="centrado">ENTREGA</p>
										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4 caja_reser">
										<div class="input-group mb-4" style="bottom: -16px;">
											<div class="input-group-prepend" id="rec">
												<span class="input-group-text1" id="basic-addon1">
													<?= $form->field($model, 'fechas')->widget(DatePicker::classname(), [
                                            'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                                            'language' => 'es',
                                            'removeButton' => false,
                                            'pluginOptions' => [
                                                'orientation' => 'bottom left',
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                                'startDate' => date('d-m-Y'),
                                                'todayHighlight' => true,
                                            ]
                                        ])->label(false); ?>
											</div>

										</div>
									</div>
									<div class="col-sm-4 col-md-4 col-lg-4">
										<div class="input-group mb-4" style="bottom: -16px;">
											<div class="input-group-prepend">
												<span class="input-group-text1" id="basic-addon1">
													<?= $form->field($model, 'horas')->widget(TimePicker::classname(), [
                                            'pluginOptions' => [
                                                'showMeridian' => false,
                                            ]
                                        ])->label(false); ?>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12 col-lg-2 separa" style="background: blue; display:flex;">
					<?= Html::button('CALCULAR TARIFA', [
            'value' => Yii::$app->urlManager->createUrl('/site/fechas'),
            'class' => 'calcular',
            'id' => 'BtnModalId',
            'data-toggle' => 'modal',
            'data-target' => '#fecha_reserva',
            'style' => 'background-color: blue;border: none;'
        ]) ?>
				</div>
			</div>
			<div class="row" style="margin-top: 5%;"></div>
		</div>
		<?php ActiveForm::end(); ?>
	</section><!-- End Hero -->
		<?php if (!isset($_REQUEST['politica-cookies']) && !isset($_COOKIE['politica'])) : ?>
		<!-- Mensaje de cookies -->
		<div class="cookies">
			<!-- Descripción con enlace -->
			<p align="center">Utilizamos cookies propias y de terceros para obtener datos estadísticos de la navegación de nuestros usuarios y mejorar nuestros servicios <br/>ofreciendo una experiencia de navegación personalizada. Te recomendamos aceptarlas, ya que de lo contrario no podrás recibir correctamente algunos contenidos y servicios de nuestra Web. <br/>Puedes activar/desactivar las cookies en nuestra <!-- Botón para aceptar -->
				<?= Html::a('Política de Uso de Cookies', ['/site/cookies'], ['target' => '_blank','style' => 'color: white;']) ?> ?.</p>
				<div class="btn-group" role="group" aria-label="Basic mixed styles example">
					<?= Html::a('RECHAZAR TODAS LAS COOKIES', '?politica-cookies=0', ['class' => 'btn btn-danger']) ?>
					<?= Html::a('ACEPTAR TODAS LAS COOKIES', '?politica-cookies=1', ['class' => 'btn btn-success']) ?>
				</div>
		</div>
	<?php endif; ?>
	<?php
    $cant = count($precio_diario);
$num = 1;
for ($i = 0; $i < $cant; $i++) { ?>
		<input class="form-control" type="hidden" id="precio-diario<?= $num ?>" value="<?= $precio_diario[$i]['precio'] ?>">
	<?php $num++;
} ?>
	<main id="main">
		<!-- ======= Skills Section ======= -->
		<section id="skills" class="skills">
			<div class="container" data-aos="fade-up">
				<div class="row">
					<div class="col-sm-12 col-md-12 col-lg-8 d-flex align-items-center" data-aos="fade-right" data-aos-delay="100">
						<div class="cont">
							<div class="contento">
								<h2 class="como_f">¿Como funciona?</h2>
							</div>
							<div id="image-line">
								<div class="card card_edit">
									<div class="row no-gutters pas1">
										<div class="col-sm-6 col-md-6">
											<p class="pasoTitle">
												PASO 1
											</p> <br />
											<p class="pasoBoby">
												RESERVA <br />
												SeLECCIONA LAS FECHAS E INGRESA TUS DATOS, TE ENVIAREMOS LA CONFIRMACIÓN DEL SERVICIO DE MANERA
												AUTOMÁTICA.
											</p>
										</div>
										<div class="col-sm-4 col-md-4">
											<?= Html::img('@web/images/reserva.png', ['class' => 'reservar', 'loading' => 'lazy']) ?>
										</div>
									</div>
								</div>
								<div class="card card_edit2">
									<div class="row no-gutters pas2">
										<div class="col-sm-1 col-md-1"></div>
										<div class="col-sm-4 col-md-4">
											<?= Html::img('@web/images/recogida.png', ['class' => 'reservar', 'loading' => 'lazy']) ?>
										</div>
										<div class="col-sm-6 col-md-6">
											<p class="pasoTitle">
												PASO 2
											</p> <br />
											<p class="pasoBoby">
												RECOGIDA <br />
												NUESTRO PERSONAL RECOGERÁ SU VEHÍCULO EN LA PUERTA DEL TERMINAL. con nuestros conductores
												debidamente identificados por la empresa.
											</p>
										</div>
									</div>
								</div>
								<div class="card card_edit3">
									<div class="row no-gutters pas3">
										<div class="col-sm-1 col-md-1"></div>
										<div class="col-sm-6 col-md-6">
											<p class="pasoTitle">
												PASO 3
											</p> <br />
											<p class="pasoBoby">
												ENTREGA <br />
												NUESTRO PERSONAL ENTREGARÁ EL VEHÍCULO EN LA PUERTA DEL TERMINAL A SU PREVIA CONFIRMACIÓN DE
												LLEGADA.
											</p>
										</div>
										<div class="col-sm-4 col-md-4">
											<?= Html::img('@web/images/entrega.png', ['class' => 'reservar']) ?>
										</div>
									</div>
								</div>
								<button class="btn btn-parkin1 btn-lg">
									<a href="#rec" style="color:white"> RESERVA AHORA MISMO </a>
								</button>
							</div>
						</div>
					</div>
					<div class="col-sm-12 col-md-12 col-lg-4 pt-4 pt-lg-0 content" data-aos="fade-left" data-aos-delay="100">
						<div class="cont">
							<div class="contento">
								<h2 class="ben">Beneficios Parking Plus</h2>
							</div>
							<div class="card card_bene">
								<div class="card-body contenido_borde">
									<h5 class="card-title info_bene"><?= Html::img('@web/images/aetncion.png', ['loading' => 'lazy']) ?>&nbsp;&nbsp;&nbsp; ATENCIÓN
									</h5>
									<p class="card-text content_atencion">Contando siempre con la ayuda de un Servicio de Atención al
										Cliente, tanto telefónico como vía e-mail.</p>
								</div>
							</div>
							<div class="card card_bene">
								<div class="card-body contenido_borde">
									<h5 class="card-title info_bene"><?= Html::img('@web/images/profesional.png', ['loading' => 'lazy']) ?>&nbsp;&nbsp;&nbsp;PROFESIONALISMO</h5>
									<p class="card-text content_atencion">Con un equipo de profesionales perfectamente identificados que realizarán el servicio en el propio Aeropuerto.</p>
								</div>
							</div>
							<div class="card card_bene">
								<div class="card-body contenido_borde">
									<h5 class="card-title info_bene"><?= Html::img('@web/images/seguro.png', ['loading' => 'lazy']) ?>&nbsp;&nbsp;&nbsp;SEGURIDAD
									</h5>
									<p class="card-text content_atencion">Con las máximas garantías, seguros para cualquier tipo de desperfectos. Seguridad 24 horas los 365 días del año.</p>
								</div>
							</div>
							<div class="card card_bene">
								<div class="card-body contenido_borde">
									<h5 class="card-title info_bene"><?= Html::img('@web/images/horas.png', ['loading' => 'lazy']) ?>&nbsp;&nbsp;&nbsp;> HORAS</h5>
									<p class="card-text content_atencion">Nuestro servicio funciona las 24 horas continuamente, sea cual sea el horario de su vuelo tanto en la ida como a su regreso con Parking Plus nunca tendrá problemas de esperas.</p>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</section><!-- End Skills Section -->

		<!-- ======= Testimonials Section ======= -->
		<section id="testimonials" class="testimonials">

			<div class="container">
				<div class="row text-center mb-3">
					<div class="col-md-12">
						<h2 class="como_f">Disfruta de Nuestras Promociones</h2>
						<hr>
					</div>
				</div>
				<div class="row">
					<!-- Swiper -->
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<div class="row">
									<div class="col-md-4">
										<div class="card">
											<div><?= Html::img('@web/images/propa1.jpeg', ['class' => 'sli_img', 'loading' => 'lazy']) ?></div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="card">
											<div class="card-img"><?= Html::img('@web/images/propa2.jpeg', ['class' => 'sli_img', 'loading' => 'lazy']) ?></div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="card">
											<div class="card-img"><?= Html::img('@web/images/propa3.jpeg', ['class' => 'sli_img', 'loading' => 'lazy']) ?></div>
										</div>
									</div>
								</div>
							</div>
							<div class="swiper-slide">
								<div class="row">
									<div class="col-md-4">
										<div class="card">
											<div class="card-img"><?= Html::img('@web/images/barajas.jpg', ['class' => 'sli_img', 'loading' => 'lazy']) ?></div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="card">
											<div class="card-img"><?= Html::img('@web/images/3-A.jpg', ['class' => 'sli_img', 'loading' => 'lazy']) ?></div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="card">
											<div class="card-img"><?= Html::img('@web/images/333.jpg', ['class' => 'sli_img', 'loading' => 'lazy']) ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Add Pagination -->
						<div class="swiper-pagination"></div>
						<!-- Add Arrows -->
						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
					</div>
				</div>
			</div>
		</section><!-- End Testimonials Section -->

		<!-- ======= Frequently Asked Questions Section ======= -->
		<section id="faq" class="faq section-bg">
			<div class="container" data-aos="fade-up">
				<div class="row">
					<div class="col-sm-6">
						<div class="como_f2">
							Preguntas Frecuentes
							<br />
							<br />
							<h4>Nuestro Parking situado junto al Aeropuerto de Madrid Barajas, cuenta con todo lo necesario para que
								viaje tranquilo y su vehículo esté siempre seguro.A precios tan económicos y de calidad. ¿Por qué pagar
								más?</h4>
						</div>
					</div>
				</div>
			</div>
			<div id="container-main">
				<div class="accordion-container">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>¿POR QUÉ ELEGIRNOS?<span class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-8">
								<p class="content_atencion">Nuestro Parking situado junto al aeropuerto de Madrid Barajas,
									cuenta con todo lo necesario para que viaje tranquilo y su vehículo esté siempre seguro.
									A precios tan económicos y de calidad. ¿Por qué pagar más?.
									Nuestros conductores le atenderán amablemente con mucha profesionalidad y responsabilidad,
									por ello contamos solo con personal altamente cualificado.
									Contamos con servicios adicionales de los que podrá beneficiarse a precios magníficos.
									Así mientras Ud está de viaje su vehículo estará reluciente a su regreso.</p>
							</div>
							<div class="col-md-4">
								<?= Html::img('@web/images/reserva.png', ['loading' => 'lazy']) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion-container">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>RECEPCIÓN<span class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-8">
								<p class="content_atencion">Contamos con varios medios para reservar, a través de nuestra página web <b>parkingplus.es</b> o a través de nuestro número de contacto
									<a href="tel:+34603282660">603282660</a> ò <a href="tel:+34912128659">912128659</a>. <br />
									Podéis reservar hasta 3 horas antes de llegar a la terminal para 1 o varios coches te llegará un mail de confirmación al correo previamente indicado,
									en el caso de no recibir este mail podéis llamar al número de reservas <a href="tel:+34603282660">603282660</a>,
									igualmente para cualquier cambio o cancelación que desees realizar,
									no es necesario que imprimas tu reserva solo indicando la matrícula de tu coche los conductores te proveerán del justificante de recogida,
									igualmente si vienes con un coche diferente o tu vuelo ha sido cambiado, debéis de contactar al <a href="tel:+34603284800">603284800</a> para que el personal de asistencia
									tome nota de tu requerimiento.
								</p>
							</div>
							<div class="col-md-4">
								<?= Html::img('@web/images/reserva.png', ['loading' => 'lazy']) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion-container">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>RECEPCION y DEVOLUCION<span class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-8">
								<p class="content_atencion">Una vez efectuada la reserva, debes llamar 20 min antes de llegar a la terminal al
									número <a href="tel:+34603284800">603284800</a> indicando su matrícula, el conductor le esperará en la misma terminal a la que usted viaje,
									debidamente identificado con chaleco reflectante y contará con un resguardo que le entregara para su mayor tranquilidad,
									deberá llamar al mismo número de teléfono para avisar de su llegada y la posterior entrega de su coche el de su regreso.</p>
							</div>
							<div class="col-md-4">
								<?= Html::img('@web/images/reserva.png', ['loading' => 'lazy']) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion-container">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>¿SABÍAS QUE?<span class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-8">
								<p class="content_atencion">
									<b> ¿CÓMO PUEDO HACER EL PAGO Y CUÁNDO?</b><br />
									Tenemos diferentes métodos de pago: Tpv, efectivo, transferencia o bizum, el pago lo puedes realizar al finalizar tu viaje.
								</p><br />
								<p class="content_atencion">
									<b>¿PUEDO DEJAR EL COCHE EN UNA TERMINAL Y RECOGERLO EN UNA DISTINTA?</b><br />
									Si, siempre pensando en tu comodidad se realizar el servicio en la terminal que lo requieras.
								</p><br />
								<p class="content_atencion">
									<b>¿SI CONTRATO UN PARKING DE LARGA ESTANCIA MI VEHÍCULO LO ARRANCAN CADA CIERTO PERIODO DE TIEMPO?</b><br />
									Nuestro personal se encargará cada cierto periodo de tiempo en arrancar, revisar los neumáticos y mover su vehículo por las instalaciones para que cuando vuelva esté listo.
								</p><br />
							</div>
							<div class="col-md-4">
								<?= Html::img('@web/images/reserva.png', ['loading' => 'lazy']) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion-container">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>SERVICIOS EXTRAS<span class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-8">
								<p class="content_atencion">Para aprovechar el tiempo de tu viaje y brindarte mayor confort a la hora de volver te
									ofrecemos un servicio de limpieza interior, exterior, profunda repostaje YTV.</p>
							</div>
							<div class="col-md-4">
								<?= Html::img('@web/images/reserva.png', ['loading' => 'lazy']) ?>
							</div>
						</div>
					</div>
				</div>
				<div class="accordion-container">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>RECEPCION DE VEHICULO <span class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-8">
								<p class="content_atencion">En parking plus cuidamos tu coche por lo que al momento de recogerlo realizaremos una
									inspección fotográfica en el parking asegurando la integridad de tu coche. </p>
							</div>
							<div class="col-md-4">
								<?= Html::img('@web/images/reserva.png', ['loading' => 'lazy']) ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 boton_centrado">
					<button class="btn btn-parking2 btn-lg">
						<a href="#rec" style="color:white"> RESERVA AHORA MISMO </a>
					</button>
				</div>
			</div>
		</section><!-- End Frequently Asked Questions Section -->
		<!-- ======= Otros servicios Section ======= -->
		<section id="services_ex" class="testimonials">
			<div class="container" data-aos="fade-up">
				<div class="row">
					<div class="con">
						<div class="col-md-6">
							<div class="como_f">
								Servicios Extras para su Coche
							</div>
							<h4>Contamos con servicios adicionales de los que podrá beneficiarse a precios magníficos. Así mientras Ud
								está
								de viaje su vehículo estará reluciente a su regreso.</h6>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- Swiper -->
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<div class="row">
									<div class="col-md-4">
										<div class="card">
											<div class="card-img"><?= Html::img('@web/images/lavado_auto.jpg', ['class' => 'sli_img', 'loading' => 'lazy']) ?></div>
											<div class="card-body" style="height: 320px;">
												<div>
													<h3>10.00&#8364;</h3>
												</div>
												<div>
													<h3>LIMPIEZA EXTERIOR</h4>
												</div>
												<div>
													<h4> Lavado a presión de la carrocería, llantas y cristales de todo el exterior.</h4>
												</div>
												<div>
													<a href="#rec"class="btn btn-otroservice btn-lg service_card">
														SOLICITAR
													</a>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="card">
											<div class="card-img"><?= Html::img('@web/images/interior.jpg', ['class' => 'sli_img', 'loading' => 'lazy']) ?></div>
											<div class="card-body" style="height: 320px;">
												<h3>24.00&#8364;</h3>
												<h3>LIMPIEZA INTERIOR / EXTERIOR</h4>
													<h4> Lavado a presión de la carrocería, llantas y cristales. Limpieza interior salpicadero,
														aspirado completo incluído maletero. Aplicación de brillo en neumáticos.
														No incluye tapicerías.
														<a href="#rec" class="btn btn-otroservice btn-lg service_card">
															SOLICITAR
														</a>
											</div>
										</div>
									</div>
									<div class="col-md-4">
										<div class="card">
											<div class="card-img"><?= Html::img('@web/images/limpieza_interior.jpg', ['class' => 'sli_img', 'loading' => 'lazy']) ?></div>
											<div class="card-body" style="height: 320px;">
												<h3>80.00&#8364;</h3>
												<h3>LIMPIEZA COMPLETA Y TAPICERIA(Solo asientos)</h4>
													<h4> Lavado a presión de la carrocería, llantas y cristales.Limpieza interior salpicadero, aspirado
														completo incluído maletero.
														Aplicación de brillo en neumáticos. Limpieza Tapicería SOLO ASIENTOS.
														Tratamiento higiene habitáculo. Acaros y Bacterias</h4>
													<a href="#rec" class="btn btn-otroservice btn-lg service_card">
														SOLICITAR
													</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Add Pagination -->
						<div class="swiper-pagination"></div>
						<!-- Add Arrows -->
						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
					</div>
				</div>
			</div>

		</section><!-- End otros servicios -->

		<!-- ======= Why Us Section ======= -->
		<section id="atencion_cliente" class="why-us section-at">
			<div class="container" data-aos="fade-up">
				<div class="row">
					<div class="col-sm-4 col-lg-4 d-flex flex-column justify-content-center align-items-stretch">
						<?= Html::img('@web/images/atencion_cliente.png', ['loading' => 'lazy']) ?>
					</div>
					<div class="col-sm-8 col-lg-8 align-items-stretch order-1 order-lg-2" style="text-align: center;">
						<div class="row" style="margin-bottom: 5%;">
							<div class="como_f">
								Atención al Cliente
							</div>
							<h4>Contando siempre con la ayuda de un Servicio de Atención al Cliente, tanto telefónico como vía e-mail.
							</h4>
						</div>
						<div class="row" style="margin-bottom: 5%;">
							<div class="col-md-6">
								<h6 class="reserva">Reservas (SOLO PARA RESERVAS)</h6>
								<button class="btn btn-parkin btn-lg">
									<a class="reborlink" href="tel:++34912128659">
										<p class="atencion">+34 912 12 86 59</p>
									</a>
								</button>
							</div>
							<div class="col-md-6">
								<h6 class="reserva">SOLICITUD DE FACTURAS</h6>
								<?= Html::button('SOLICÍTALA AQUÍ', [
                                'value' => Yii::$app->urlManager->createUrl('/site/solicitarf'),
                                'class' => 'btn btn-parkin btn-lg',
                                'id' => 'BtnModalSolicitud',
                                'data-toggle' => 'modal',
                                'data-target' => '#solicitud_factura',
                            ]) ?>
							</div>
						</div>
						<div class="row" style="margin-bottom: 5%;">
							<div class="col-md-6">
								<h6 class="reserva">Asistencia en Aeropuerto</h6>
								<button class="btn btn-parkin btn-lg">
									<a class="reborlink" href="tel:+34912128659">
										<p class="atencion">+34 603 28 48 00</p>
									</a>
								</button>
							</div>
							<div class="col-md-6">
								<h6 class="reserva">contacto@parkingplus.es</h6>
								<button class="btn btn-parkin btn-lg">
									<a class="reborlink" href="mailto:contacto@parkingplus.es">
										<p class="atencion">ESCRIBENOS</p>
									</a>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section><!-- End Why Us Section -->

		<!-- ======= Contact Section ======= -->
		<section id="contact" class="contact">
			<div class="container">
				<div class="row">
					<div class="col-sm-6">
						<div class="como_f">
							Contactanos
						</div>
						<h5>Ante cualquier consulta o problema no dude en contactarnos vía telefónica o vía email.</h5>
						<br />
						<br />
					</div>
				</div>
				<div class="row">
					<div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
						<div class="php-email-form">
							<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
							<div class="row">
								<div class="form-group col-md-6">
									<?= $form->field($model, 'name')->textInput() ?>
								</div>
								<div class="form-group col-md-6">
									<?= $form->field($model, 'subject') ?>
								</div>
							</div>
							<div class="form-group">
								<?= $form->field($model, 'email') ?>
							</div>
							<div class="form-group">
								<?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                                        'template' => '<div class="row"><div class="col-lg-6">{image}</div><div class="col-lg-6" style ="margin-left: 15px; margin-top: 7px">{input}</div></div>',
                                    ]) ?>
									</div>
								</div>
							</div>
							<div class="my-3">
								<div class="loading">Loading</div>
								<div class="error-message"></div>
								<div class="sent-message">Your message has been sent. Thank you!</div>
							</div>
							<?= Html::submitButton('Enviar Mensaje', ['class' => 'btn btn-parkin btn-lg', 'name' => 'contact-button']) ?>
							<?php ActiveForm::end(); ?>
						</div>
					</div>
					<div class="col-lg-5">
						<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
							<div class="carousel-indicators">
								<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
								<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
								<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
							</div>
							<div class="carousel-inner">
								<div class="carousel-item active">
									<?= Html::img('@web/images/propa1.jpeg', ['class' => 'd-block w-100', 'loading' => 'lazy']) ?>
								</div>
								<div class="carousel-item">
									<?= Html::img('@web/images/propa2.jpeg', ['class' => 'd-block w-100', 'loading' => 'lazy']) ?>
								</div>
								<div class="carousel-item">
									<?= Html::img('@web/images/propa3.jpeg', ['class' => 'd-block w-100', 'loading' => 'lazy']) ?>
								</div>
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
						</div>
					</div>
				</div>
			</div>
		</section><!-- End Contact Section -->
	</main><!-- End #main -->
</div>
<?php
$this->registerJs(" 

        let navegador = navigator.userAgent;
        
       /* if (window.innerWidth >= 768) {
            $('#aviso').click();
        }*/
        
        $('.close').css('order', 1);
        
    	$( document ).ready(function() {
			var hoy = new Date();
			var dd = hoy.getDate();
			var mm = hoy.getMonth()+1;
			var yyyy = hoy.getFullYear();
			if (dd < 10) {
				dd = '0'+dd; 
			}
			if (mm < 10) {
				mm = '0'+mm;				
			}

    		hoy = dd+'-'+mm+'-'+yyyy;
			$('#reservas-fechae').val(hoy);
			$('#reservas-fechas').val(hoy);

			fecha_in = $('#reservas-fechae').val();
			fecha_out = $('#reservas-fechas').val();			

			hora_in = $('#reservas-horae').val();
			hora_out = $('#reservas-horas').val();

			$('#fecha-in').val(fecha_in);
            $('#fecha-out').val(fecha_out);

			$('#hora-in').val(hora_in);
            $('#hora-out').val(hora_out);

            if (fecha_in == fecha_out) {
            	$('#costo-servicio').val(0.00);
            }
            
    	});		

    	$('#reservas-fechae').change(function() {
    		var total_servicio = 0; var total = 0;
    		fecha_in = $('#reservas-fechae').val();
    		fecha_out = $('#reservas-fechas').val();
    		$('#fecha-in').val(fecha_in);

            fechaE = convertDateFormat(fecha_in)
            fechaS = convertDateFormat(fecha_out)

			fechaInicio = new Date(fechaE).getTime();
			fechaFin    = new Date(fechaS).getTime();
			diff = fechaFin - fechaInicio;
			cant = (diff/(1000*60*60*24));		

    		hora_in = $('#reservas-horae').val();
    		hora_out = $('#reservas-horas').val();	

			$('#hora-in').val(hora_in);
            $('#hora-out').val(hora_out);	    				

		    inh = hora_in.split(':');
		    outh = hora_out.split(':');

		    min = outh[1]-inh[1];
		    hour_carry = 0;
		    if(min < 0){
		        min += 60;
		        hour_carry += 1;
		    }
		    hour = outh[0]-inh[0]-hour_carry;
		    min = ((min/60)*100).toString()
		    diffh = hour + ':' + min.substring(0,2);

		    if (hour > 0 || Number(min) > 0) {
		    	cant = cant + 1;
		    }

		    $('#cdias').val(cant);

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

            if (cant > 30) { 
                var cant_dias = cant - 30;
                var precio_relativo = parseFloat(precio30);
                var cuota = $('#cuota_dia').val();
                var total = precio_relativo + (cant_dias * 3); 
            }

            total_servicio  = total.toFixed(2);			
			
            if ((hora_out > hora_in) && (fecha_in === fecha_out)) {
            	var valorcosto = $('#precio-diario1').val();
            	$('#costo-servicio').val(valorcosto);		
            } else {
            	$('#costo-servicio').val(total_servicio);
            }
    	});

    	$('#reservas-fechas').change(function() {

			hora_in = $('#reservas-horae').val();
			hora_out = $('#reservas-horas').val();	

    		var total_servicio = 0; var total = 0;
    		fecha_in = $('#reservas-fechae').val();
    		fecha_out = $('#reservas-fechas').val();
    		$('#fecha-out').val(fecha_out);

            fechaE = convertDateFormat(fecha_in)
            fechaS = convertDateFormat(fecha_out)

			fechaInicio = new Date(fechaE).getTime();
			fechaFin    = new Date(fechaS).getTime();
			diff = fechaFin - fechaInicio;
			cant = (diff/(1000*60*60*24));

    		hora_in = $('#reservas-horae').val();
    		hora_out = $('#reservas-horas').val();			

			$('#hora-in').val(hora_in);
            $('#hora-out').val(hora_out);

		    inh = hora_in.split(':');
		    outh = hora_out.split(':');

		    min = outh[1]-inh[1];
		    hour_carry = 0;
		    if(min < 0){
		        min += 60;
		        hour_carry += 1;
		    }
		    hour = outh[0]-inh[0]-hour_carry;
		    min = ((min/60)*100).toString()
		    diffh = hour + ':' + min.substring(0,2);

		    if (hour > 0 || Number(min) > 0) {
		    	cant = cant + 1;
		    }	
    
		    $('#cdias').val(cant);		

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



            if (cant > 30) { 
            	total_servicio = 0; total = 0;
                var cant_dias = cant - 30;
                var precio_relativo = parseFloat(precio30);
                var cuota = $('#cuota_dia').val();
                total = precio_relativo + (cant_dias * 3); 
            }
            total_servicio  = total.toFixed(2);


            if ((hora_out > hora_in) && (fecha_in === fecha_out)) {
            	var valorcosto = $('#precio-diario1').val();
            	$('#costo-servicio').val(valorcosto);		
            } else {
            	$('#costo-servicio').val(total_servicio);
            } 


    	});

		$('#reservas-horae').change(function() { 

			hora_in = $('#reservas-horae').val();
			hora_out = $('#reservas-horas').val();	

    		var total_servicio = 0; var total = 0;
    		fecha_in = $('#reservas-fechae').val();
    		fecha_out = $('#reservas-fechas').val();
    		$('#fecha-out').val(fecha_out);

            fechaE = convertDateFormat(fecha_in)
            fechaS = convertDateFormat(fecha_out)

			fechaInicio = new Date(fechaE).getTime();
			fechaFin    = new Date(fechaS).getTime();
			diff = fechaFin - fechaInicio;
			cant = (diff/(1000*60*60*24));

    		hora_in = $('#reservas-horae').val();
    		hora_out = $('#reservas-horas').val();			

			$('#hora-in').val(hora_in);
            $('#hora-out').val(hora_out);

		    inh = hora_in.split(':');
		    outh = hora_out.split(':');

		    min = outh[1]-inh[1];
		    hour_carry = 0;
		    if(min < 0){
		        min += 60;
		        hour_carry += 1;
		    }
		    hour = outh[0]-inh[0]-hour_carry;
		    min = ((min/60)*100).toString()
		    diffh = hour + ':' + min.substring(0,2);


		    if (hour > 0 || Number(min) > 0 ) {
		    	cant = cant + 1;
		    }	

		    $('#cdias').val(cant);		

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

            if (cant > 30) { 
            	total_servicio = 0; total = 0;
                var cant_dias = cant - 30;
                var precio_relativo = parseFloat(precio30);
                var cuota = $('#cuota_dia').val();
                total = precio_relativo + (cant_dias * cuota); 
            }
            total_servicio  = total.toFixed(2);

            if ((hora_out > hora_in) && (fecha_in === fecha_out)) {
            	var valorcosto = $('#precio-diario1').val();
            	$('#costo-servicio').val(valorcosto);		
            } else {
            	$('#costo-servicio').val(total_servicio);
            } 
            
		});

		$('#reservas-horas').change(function() { 

			hora_in = $('#reservas-horae').val();
			hora_out = $('#reservas-horas').val();	

    		var total_servicio = 0; var total = 0;
    		fecha_in = $('#reservas-fechae').val();
    		fecha_out = $('#reservas-fechas').val();
    		$('#fecha-out').val(fecha_out);

            fechaE = convertDateFormat(fecha_in)
            fechaS = convertDateFormat(fecha_out)

			fechaInicio = new Date(fechaE).getTime();
			fechaFin    = new Date(fechaS).getTime();
			diff = fechaFin - fechaInicio;
			cant = (diff/(1000*60*60*24));

    		hora_in = $('#reservas-horae').val();
    		hora_out = $('#reservas-horas').val();			

			$('#hora-in').val(hora_in);
            $('#hora-out').val(hora_out);

		    inh = hora_in.split(':');
		    outh = hora_out.split(':');

		    min = outh[1]-inh[1];
		    hour_carry = 0;
		    if(min < 0){
		        min += 60;
		        hour_carry += 1;
		    }
		    hour = outh[0]-inh[0]-hour_carry;
		    min = ((min/60)*100).toString()
		    diffh = hour + ':' + min.substring(0,2);
		    
		    if (hour > 0 || Number(min) > 0) {
		    	cant = cant + 1;
		    }
		    
		    $('#cdias').val(cant);			

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

            if (cant > 30) { 
            	total_servicio = 0; total = 0;
                var cant_dias = cant - 30;
                var precio_relativo = parseFloat(precio30);
                var cuota = $('#cuota_dia').val();
                total = precio_relativo + (cant_dias * cuota); 
            }
            total_servicio  = total.toFixed(2);

            if ((hora_out > hora_in) && (fecha_in === fecha_out)) {
            	var valorcosto = $('#precio-diario1').val();
            	$('#costo-servicio').val(valorcosto);		
            } else {
            	$('#costo-servicio').val(total_servicio);
            } 		                                  
    
            if((hora_in >= '00:30' && hora_in <= '03:45') || (hora_out >= '00:30' && hora_out <= '03:45')){
				    valorcosto = parseFloat($('#costo-servicio').val()) + parseFloat('10,00');
				    $('#costo-servicio').val(valorcosto.toFixed(2));
            } 
		});


        $('#BtnModalId').click(function(e){    
            e.preventDefault();
            $('#fecha_reserva').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
            return false;
        });

        $('#BtnModalSolicitud').click(function(e){    
            e.preventDefault();
            $('#solicitud_factura').modal('show')
            .find('#modalFactura')
            .load($(this).attr('value'));
            return false;
        });        

		function convertDateFormat(string) {
			var info = string.split('-');
			return info[2] + '-' + info[1] + '-' + info[0];
		}		    
    ");
?>