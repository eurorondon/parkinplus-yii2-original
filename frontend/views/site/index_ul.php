<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\PrereservaForm */
/* @var $model \frontend\models\ContactForm */


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
	'header' => '<h1 class="modal-title">COSTO DEL SERVICIO</h1>',
	'id' => 'fecha_reserva',
	'size' => 'modal-xl',

]); ?>

<!--
<input type="hidden" id="id_reserva" value="<?= $model->id; ?>">
-->

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

<div class="site-index">
	<section id="hero" class="d-flex align-items-center">

		<div class="container">
			<div class="row">
				<div class="col-lg-12 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1"
					data-aos="fade-up" data-aos-delay="200">
					<p class="h2generada"> TU VUELAS</p> <br />
					<h1>NOSOTROS APARCAMOS</h1>
					<h2>Aereopuerto de Madrid - Barajas <br /><br />
						Recogeremos tu coche en el aeropuerto el día y a la hora que nos digas. A tu vuelta, te
						estaremos esperando
						en la puerta de la terminal.</h2>
				</div>
				<div class="col-lg-12 order-1 order-lg-2 hero-img" style="color: white;" data-aos="zoom-in"
					data-aos-delay="200">
					<!-- <?= Html::img('@web/images/hero-img.png', ['class' => 'img-logo animated']) ?> -->
					<h3> <i>Reservas en solo <i class="bi bi-2-circle"></i> minutos <i class="bi bi-stopwatch"></i> </i>
					</h3>
					<h3><i> No necesitas registrar ninguna Tarjeta de Crédito <i class="bi bi-window-dash"></i></i></h3>

					<?php
					$form = ActiveForm::begin([
						'action' => Yii::$app->urlManager->createUrl('/site/reserva'),
						'id' => 'fechas-form',
						'options' => [
							'autocomplete' => 'off',
						],
					]);
					?>
					<div class="row d-flex align-items-center" id="calc_reserva" style="">
						<div class="col-sm-12 col-lg-12">
							<div class="card">
								<div
									class="card-body1 col-12 d-flex align-items-center flex-sm-column flex-md-column flex-lg-row">
									<div class="col-sm-12 col-md-5 col-lg-5 d-flex  flex-column">
										<div class="col-sm-4">
											<div class="calculadora">
												<p class="centrado">RECOGIDA</p>
											</div>
										</div>
										<div class="d-flex col-12 cal__reserva__input">
											<div class="col-sm-6 col-md-6 col-lg-6 caja_">
												<div class="input-group mb-2">
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
																],
															])->label(false); ?>
													</div>
												</div>
											</div>
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="input-group mb-2" style="">
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

									<div class="col-sm-12 col-md-5 col-lg-5 d-flex flex-column">
										<div class="col-sm-4 col-lg-4">
											<div class="calculadora">
												<p class="centrado">ENTREGA</p>
											</div>
										</div>
										<div class="d-flex col-12 cal__reserva__input">
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="input-group mb-2" style="">
													<div class="input-group-prepend" id="rec">
														<span class="input-group-text1" id="basic-addon1">
															<?= $form->field($model, 'fechas')->widget(DatePicker::classname(), [
																'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
																'language' => 'es',
																'removeButton' => false,
																'pluginOptions' => [
																	'minDate' => 0,
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
											<div class="col-sm-6 col-md-6 col-lg-6">
												<div class="input-group mb-2" style="">
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

									<div class="col-sm-12 col-md-2 col-lg-2" style="">
										<?= Html::submitButton('CALCULAR TARIFA', [
											'class' => 'calcular btn',
											'style' => 'background-color: #961007;border: none; height: 50px',
											'disabled' => true,
										]) ?>
									</div>
								</div>
								<div id="msg-fechas" class="text-danger py-2 px-4" style="display:none">
									Verifique las fechas y horas seleccionadas. Por favor ...
								</div>
							</div>
						</div>
						<!-- <div class="col-sm-12 col-lg-4">
							<div class="card">
								<div class="card-body1">
									<div class="row">
										<div class="col-sm-12 d-flex flex-column">
											<div class="col-sm-4 col-lg-4">
												<div class="calculadora">
													<p class="centrado">ENTREGA</p>
												</div>
											</div>
											<div class="d-flex col-12">
												<div class="col-sm-6 col-md-6 col-lg-6">
													<div class="input-group mb-4" style="bottom: -16px;">
														<div class="input-group-prepend" id="rec">
															<span class="input-group-text1" id="basic-addon1">
																<?= $form->field($model, 'fechas')->widget(DatePicker::classname(), [
																	'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
																	'language' => 'es',
																	'removeButton' => false,
																	'pluginOptions' => [
																		'minDate' => 0,
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
												<div class="col-sm-4 col-md-6 col-lg-6">
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
						</div> -->


					</div>
					<div class="row" style="margin-top: 5%;"></div>
				</div>
				<?php ActiveForm::end(); ?>

			</div>
		</div>
	</section><!-- End Hero -->

	<?php if (!isset($_REQUEST['politica-cookies']) && !isset($_COOKIE['politica'])): ?>
		<!-- Mensaje de cookies -->
		<div class="cookies">
			<!-- Descripción con enlace -->
			<p align="center">Utilizamos cookies propias y de terceros para obtener datos estadísticos de la navegación de
				nuestros usuarios y mejorar nuestros servicios <br />ofreciendo una experiencia de navegación personalizada.
				Te recomendamos aceptarlas, ya que de lo contrario no podrás recibir correctamente algunos contenidos y
				servicios de nuestra Web. <br />Puedes activar/desactivar las cookies en nuestra <!-- Botón para aceptar -->
				<?= Html::a('Política de Uso de Cookies', ['/site/cookies'], ['target' => '_blank', 'style' => 'color: white;']) ?>
				?.
			</p>
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

		<section id="skills" class="price container">
			<h3 class="subtitle" data-aos="fade-up">¿Como funciona?</h3>

			<div class="price__table">
				<div class="price__element" data-aos="fade-down">
					<div class="price__picture">
						<?= Html::img('@web/images/reserva.png', ['class' => 'price__img']) ?>
					</div>
					<p class="price__name">Paso 1</p>
					<h3 class="price__price">Reserva</h3>

					<div class="price__items">
						<p class="price__features">
							Selecciona las fechas e ingresa tus datos, te enviaremos la confirmación del servicio de
							manera automatica.
						</p>
					</div>

					<!--<a href="#" class="price__cta">Empieza ahora</a> -->
				</div>


				<div class="price__element price__element--best" data-aos="fade-up">
					<div class="price__picture">
						<?= Html::img('@web/images/recogida.png', ['class' => 'price__img']) ?>
					</div>
					<p class="price__name price__name__text">Paso 2</p>
					<h3 class="price__price price__price__text">Recogida</h3>

					<div class="price__items price__items__text">
						<p class="price__features">Nuestro Personal recogerá su vehículo en la puerta del Terminal. Con
							nuestros conductores debidamente identificados por la empresa.</p>
					</div>

					<!--<a href="#" class="price__cta">Empieza ahora</a> -->
				</div>


				<div class="price__element" data-aos="fade-down">
					<div class="price__picture">
						<?= Html::img('@web/images/entrega.png', ['class' => 'price__img']) ?>
					</div>
					<p class="price__name">Paso 3</p>
					<h3 class="price__price">Entrega</h3>

					<div class="price__items">
						<p class="price__features">Nuestro personal entregará el vehículo en la puerta del terminal a su
							previa confirmación de llegada.<br><br></p>
					</div>

				</div>

			</div>
		</section>

		<section class="counter">
			<div class="container counter__grid">
				<div class="counter_container" data-aos="zoom-in">
					<div class="counter__text">
						<div class="icon"><?= Html::img('@web/images/home.svg', ['class' => 'counter__icon']) ?></div>
						<div class="counter__letter">Cómodo</div>
					</div>
					<div class="counter__description">Desde la pag <br />www.parkingplus.es</div>
				</div>
				<div class="counter_container" data-aos="zoom-in-up">
					<div class="counter__text">
						<div class="icon"><?= Html::img('@web/images/fast.svg', ['class' => 'counter__icon']) ?></div>
						<div class="counter__letter">Rápido</div>
					</div>
					<div class="counter__description">
						Tardas 2 min<br />
						en reservar
					</div>
				</div>
				<div class="counter_container" data-aos="zoom-in-down">
					<div class="counter__text">
						<div class=""><?= Html::img('@web/images/money.svg', ['class' => 'counter__icon']) ?></div>
						<div class="counter__letter">Económico</div>
					</div>
					<div class="counter__description">Ahorra cada <br />vez que aparcas</div>
				</div>
				<div class="counter_container" data-aos="zoom-in">
					<div class="counter__text">
						<div class="icon"><?= Html::img('@web/images/lock.svg', ['class' => 'counter__icon']) ?></div>
						<div class="counter__letter">Seguro</div>
					</div>
					<div class="counter__description"> Paga en la terminal <br /></div>
				</div>
			</div>
		</section>

		<section class="beneficios magicpattern">
			<h3 class="beneficios__title" data-aos="slide-right">Beneficios ParkingPlus</h3>
			<div class="beneficios__container container">
				<!-- <figure class="beneficios__picture" data-aos="slide-right">
					<?= Html::img('@web/images/f3.jpeg', ['class' => 'beneficio__img']) ?>
				</figure> -->

				<div class="beneficio__content">
					<div class="beneficio" data-aos="slide-left">
						<h3 class="subtitle__one">Atención</h3>
						<p class="beneficio__paragraph">
							Contando siempre con la ayuda de un Servicio de Atención al
							Cliente, tanto telefónico como vía e-mail.
						</p>
					</div>
					<div class="beneficio" data-aos="slide-left">
						<h3 class="subtitle__one">Profesionalismo</h3>
						<p class="beneficio__paragraph">
							Con un equipo de profesionales perfectamente identificados que realizarán el servicio en el
							propio Aeropuerto.
						</p>
					</div>
					<div class="beneficio" data-aos="slide-left">
						<h3 class="subtitle__one">Seguridad</h3>
						<p class="beneficio__paragraph">
							Con las máximas garantías, seguros para cualquier tipo de desperfectos. Seguridad 24 horas
							los 365 días del año.
						</p>
					</div>
					<div class="beneficio" data-aos="slide-left">
						<h3 class="subtitle__one">24 Horas</h3>
						<p class="beneficio__paragraph">
							Nuestro servicio funciona las 24 horas continuamente, sea cual sea el horario de su vuelo
							tanto en la ida como a su regreso con Aparcabarajas nunca tendrá problemas de esperas.
						</p>
					</div>
				</div>
			</div>
		</section>

		<!-- <section class="promo container">
			<h3 class="subtitle" data-aos="zoom-in">Servicios de Calidad Garantizados</h3>
			<div class="promo__container container flex-md-column">
				<div class="promo__picture" data-aos="slide-up" data-aos-easing="ease-in-out" data-aos-duration="500">
					<?= Html::img('@web/images/f1.jpeg', ['class' => 'promo__img']) ?>
				</div>
				<div class="promo__picture" data-aos="slide-up" data-aos-easing="ease-in-out" data-aos-duration="600">
					<?= Html::img('@web/images/2d6.jpg', ['class' => 'promo__img']) ?>
				</div>
				<div class="promo__picture" data-aos="slide-up" data-aos-easing="ease-in-out" data-aos-duration="700">
					<?= Html::img('@web/images/f4.jpeg', ['class' => 'promo__img']) ?>
				</div>
			</div>
			<div class="promo__container container flex-md-column">
				<div class="promo__picture" data-aos="slide-up" data-aos-easing="ease-in-out" data-aos-duration="500">
					<?= Html::img('@web/images/f5.jpeg', ['class' => 'promo__img']) ?>
				</div>
				<div class="promo__picture" data-aos="slide-up" data-aos-easing="ease-in-out" data-aos-duration="600">
					<?= Html::img('@web/images/f6.jpeg', ['class' => 'promo__img']) ?>
				</div>
				<div class="promo__picture" data-aos="slide-up" data-aos-easing="ease-in-out" data-aos-duration="700">
					<?= Html::img('@web/images/3d6.jpg', ['class' => 'promo__img']) ?>
				</div>
			</div>

		</section> -->

		<!-- ======= Otros servicios Section ======= -->
		<section id="services_ex" class="testimonials">

			<div class="container" data-aos="fade-right">
				<div class="row">
					<div class="con">
						<div class="col-md-12">
							<div class="subtitle">
								Servicios Extras para su Coche
							</div>
							<h4 class="extra__subtitle">Contamos con servicios adicionales de los que podrá beneficiarse
								a precios magníficos. Así mientras Ud
								está
								de viaje su vehículo estará reluciente a su regreso.</h4>
						</div>
					</div>
				</div>
				<div class="row">
					<!-- Swiper -->
					<div class="swiper-container">
						<div class="swiper-wrapper">
							<div class="swiper-slide">
								<div class="row">
									<div class="col-md-4" data-aos="slide-left">
										<div class="card">
											<div class="card-img">
												<?= Html::img('@web/images/lavado_auto.jpg', ['class' => 'sli_img']) ?>
											</div>
											<div class="card-body" style="height: 350px;">
												<div>
													<h3>10.00&#8364;</h3>
												</div>
												<div>
													<h3>LIMPIEZA EXTERIOR</h4>
												</div>
												<div>
													<h4> Lavado a presión de la carrocería, llantas y cristales de todo
														el exterior.</h4>
												</div>
												<div>
													<a href="#rec" class="btn btn-parking2  btn-lg service_card"
														style="width: 90%;">
														SOLICITAR
													</a>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4" data-aos="slide-left">
										<div class="card">
											<div class="card-img">
												<?= Html::img('@web/images/interior.jpg', ['class' => 'sli_img']) ?>
											</div>
											<div class="card-body" style="height: 350px;">
												<h3>24.00&#8364;</h3>
												<h3>LIMPIEZA INTERIOR / EXTERIOR</h4>
													<h4> Lavado a presión de la carrocería, llantas y cristales.
														Limpieza interior salpicadero,
														aspirado completo incluído maletero. Aplicación de brillo en
														neumáticos.
														No incluye tapicerías.
														<a href="#rec" class="btn btn-parking2  btn-lg service_card"
															style="width: 90%;">
															SOLICITAR
														</a>
											</div>
										</div>
									</div>
									<div class="col-md-4" data-aos="slide-left">
										<div class="card">
											<div class="card-img">
												<?= Html::img('@web/images/limpieza_interior.jpg', ['class' => 'sli_img']) ?>
											</div>
											<div class="card-body" style="height: 350px;">
												<h3>80.00&#8364;</h3>
												<h3>LIMPIEZA COMPLETA Y TAPICERIA(Solo asientos)</h4>
													<h4> Lavado a presión de la carrocería, llantas y cristales.Limpieza
														interior salpicadero, aspirado
														completo incluído maletero.
														Aplicación de brillo en neumáticos. Limpieza Tapicería SOLO
														ASIENTOS.
														Tratamiento higiene habitáculo. Acaros y Bacterias</h4>
													<a href="#rec" class="btn btn-parking2  btn-lg service_card"
														style="width: 90%;">
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

		<!-- ======= Frequently Asked Questions Section ======= -->
		<section id="faq" class="faq magicpattern__1">
			<div class="container" data-aos="fade-up">
				<div class="row">
					<div class="col-12">
						<div class="como_f2">
							Preguntas Frecuentes
							<br />
							<br />
							<h4>Nuestro Parking situado junto al Aeropuerto de Madrid Barajas, cuenta con todo lo
								necesario para que
								viaje tranquilo y su vehículo esté siempre seguro.A precios tan económicos y de calidad.
								¿Por qué pagar
								más?</h4>
						</div>
					</div>
				</div>
			</div>
			<div id="container-main">
				<div class="accordion-container" data-aos="slide-left">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>¿POR QUÉ ELEGIRNOS?<span
							class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-12">
								<p class="content_atencion">Nuestro Parking situado junto al aeropuerto de Madrid
									Barajas,
									cuenta con todo lo necesario para que viaje tranquilo y su vehículo esté siempre
									seguro.
									A precios tan económicos y de calidad. ¿Por qué pagar más?.
									Nuestros conductores le atenderán amablemente con mucha profesionalidad y
									responsabilidad,
									por ello contamos solo con personal altamente cualificado.
									Contamos con servicios adicionales de los que podrá beneficiarse a precios
									magníficos.
									Así mientras Ud está de viaje su vehículo estará reluciente a su regreso.</p>
							</div>
							<!--<div class="col-md-4">
								<?= Html::img('@web/images/reserva.png') ?>
							</div>-->
						</div>
					</div>
				</div>

				<div class="accordion-container" data-aos="slide-right">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>RECEPCIÓN<span
							class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-12">
								<p class="content_atencion">Contamos con varios medios para reservar, a través de
									nuestra página web <b>parkingplus.es</b> o a través de nuestro número de contacto
									<a href="tel:+34603282660">603282660</a> ò <a href="tel:+34912128659">912128659</a>.
									<br />
									Podéis reservar hasta 3 horas antes de llegar a la terminal para 1 o varios coches
									te llegará un mail de confirmación al correo previamente indicado,
									en el caso de no recibir este mail podéis llamar al número de reservas <a
										href="tel:+34912128659">912128659</a>,
									igualmente para cualquier cambio o cancelación que desees realizar,
									no es necesario que imprimas tu reserva solo indicando la matrícula de tu coche los
									conductores te proveerán del justificante de recogida,
									igualmente si vienes con un coche diferente o tu vuelo ha sido cambiado, debéis de
									contactar al <a href="tel:+34603284800">603284800</a> para que el personal de
									asistencia
									tome nota de tu requerimiento.
								</p>
							</div>
							<!--div class="col-md-4">
								<?= Html::img('@web/images/reserva.png') ?>
							</div-->
						</div>
					</div>
				</div>

				<div class="accordion-container" data-aos="slide-left">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>RECEPCION y DEVOLUCION<span
							class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-12">
								<p class="content_atencion">Una vez efectuada la reserva, debes llamar 20 min antes de
									llegar a la terminal al
									número <a href="tel:+34603284800">603284800</a> indicando su matrícula, el conductor
									le esperará en la misma terminal a la que usted viaje,
									debidamente identificado con chaleco reflectante y contará con un resguardo que le
									entregara para su mayor tranquilidad,
									deberá llamar al mismo número de teléfono para avisar de su llegada y la posterior
									entrega de su coche el de su regreso.</p>
							</div>
							<!--div class="col-md-4">
								<?= Html::img('@web/images/reserva.png') ?>
							</div-->
						</div>
					</div>
				</div>

				<div class="accordion-container" data-aos="slide-right">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>¿SABÍAS QUE?<span
							class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-12">
								<p class="content_atencion">
									<b> ¿CÓMO PUEDO HACER EL PAGO Y CUÁNDO?</b><br />
									Tenemos diferentes métodos de pago: Tpv, efectivo, transferencia o bizum, el pago lo
									puedes realizar al finalizar tu viaje.
								</p><br />
								<p class="content_atencion">
									<b>¿PUEDO DEJAR EL COCHE EN UNA TERMINAL Y RECOGERLO EN UNA DISTINTA?</b><br />
									Si, siempre pensando en tu comodidad se realizar el servicio en la terminal que lo
									requieras.
								</p><br />
								<p class="content_atencion">
									<b>¿SI CONTRATO UN PARKING DE LARGA ESTANCIA MI VEHÍCULO LO ARRANCAN CADA CIERTO
										PERIODO DE TIEMPO?</b><br />
									Nuestro personal se encargará cada cierto periodo de tiempo en arrancar, revisar los
									neumáticos y mover su vehículo por las instalaciones para que cuando vuelva esté
									listo.
								</p><br />
							</div>
							<!--div class="col-md-4">
								<?= Html::img('@web/images/reserva.png') ?>
							</div-->
						</div>
					</div>
				</div>

				<div class="accordion-container" data-aos="slide-left">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>SERVICIOS EXTRAS<span
							class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-12">
								<p class="content_atencion">Para aprovechar el tiempo de tu viaje y brindarte mayor
									confort a la hora de volver te
									ofrecemos un servicio de limpieza interior, exterior, profunda repostaje itv.</p>
							</div>
						</div>
					</div>
				</div>

				<div class="accordion-container" data-aos="slide-right">
					<a class="accordion-titulo"><i class="bx bx-help-circle icon-help"></i>RECEPCION DE VEHICULO <span
							class="toggle-icon"></span></a>
					<div class="accordion-content">
						<div class="row">
							<div class="col-md-12">
								<p class="content_atencion">En parkingplus cuidamos tu coche por lo que al momento de
									recogerlo realizaremos una
									inspección fotográfica en el parking asegurando la integridad de tu coche. </p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 boton_centrado">
					<button class="btn btn-parking2 btn-lg" data-aos="fade-in">
						<a href="#rec" style="color:white"> RESERVA AHORA MISMO </a>
					</button>
				</div>
			</div>

		</section><!-- End Frequently Asked Questions Section -->



		<!-- ======= Why Us Section ======= -->
		<section id="atencion_cliente" class="why-us">
			<div class="container">

				<div class="row flex-sm-column flex-md-column flex-lg-row flex-xl-row align-items-sm-center">

					<div class="col-sm-8 col-md-4 col-lg-4 d-flex flex-sm-column justify-content-center align-items-stretch"
						data-aos="fade-up" data-aos-offset="200">
						<?= Html::img('@web/images/ac.webp', ['class' => 'cliente__img']) ?>
					</div>

					<div class="col-sm-8 col-md-8  col-lg-8 align-items-stretch order-1 order-lg-2"
						style="text-align: center;">
						<div class="row" style="margin-bottom: 5%;">
							<div class="subtitle" data-aos="flip-up" data-aos-offset="200">
								Atención al Cliente
							</div>
							<h4 class="" data-aos="slide-up" data-aos-offset="200">Contando siempre con la ayuda de un
								Servicio de Atención al Cliente, tanto telefónico como vía e-mail.
							</h4>
						</div>
						<div class="row" style="margin-bottom: 5%;">
							<div class="col-md-6" data-aos="zoom-in" data-aos-offset="100">
								<h6 class="reserva">Reservas (SOLO PARA RESERVAS)</h6>
								<button class="btn btn-parking2 btn-lg" style="width: 100%;">
									<a class="reborlink" href="tel:+34613910571">
										<p class="atencion" style="margin: 0px; color:#FFF; font-weight: 500;">+34 912
											12 86 59</p>
									</a>
								</button>
							</div>
							<div class="col-md-6" data-aos="zoom-in" data-aos-offset="100">
								<h6 class="reserva">SOLICITUD DE FACTURAS</h6>
								<?= Html::button('SOLICÍTALA AQUÍ', [
									'value' => Yii::$app->urlManager->createUrl('/site/solicitarf'),
									'style' => 'background: #FFF; border: 1px solid #000; color: #000',
									'class' => 'btn btn-parkin btn-lg',
									'id' => 'BtnModalSolicitud',
									'data-toggle' => 'modal',
									'data-target' => '#solicitud_factura',
								]) ?>
							</div>
						</div>
						<div class="row" style="margin-bottom: 5%;">
							<div class="col-md-6" data-aos="zoom-in" data-aos-offset="100">
								<h6 class="reserva">Asistencia en Aeropuerto</h6>
								<button class="btn btn-parkin btn-lg" style="background: #FFF; border: 1px solid #000">
									<a class="reborlink" href="tel:+34603284800">
										<p class="atencion" style="margin: 0px; color:#000; font-weight: 500;">+34 603
											28 48 00</p>
									</a>
								</button>
							</div>
							<div class="col-md-6" data-aos="zoom-in" data-aos-offset="100">
								<h6 class="reserva">contacto@parkingplus.es</h6>
								<button class="btn btn-parking2 btn-lg" style="width: 100%;">
									<a class="reborlink" href="mailto:contacto@aparcabarajas.es">
										<p class="atencion" style="margin: 0px; color:#FFF; font-weight: 500;">
											ESCRIBENOS</p>
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
					<div class="col-sm-12" data-aos="slide-down">
						<div class="subtitle">
							Contactanos
						</div>
						<h5 class="extra_subtitle"
							style="margin: 0 auto;color: #a3a4a3;text-align: center;padding-bottom: 50px;">Ante
							cualquier consulta o problema no dude en contactarnos vía telefónica o vía email.</h5>

					</div>
				</div>
				<div class="row">
					<div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch" data-aos="slide-right">
						<div class="php-email-form">
							<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>
							<div class="row">
								<div class="form-group col-md-6">
									<?= $form->field($model, 'name')->label('Nombre')->textInput() ?>
								</div>
								<div class="form-group col-md-6">
									<?= $form->field($model, 'subject')->label('Título')->textInput() ?>
								</div>
							</div>
							<div class="form-group">
								<?= $form->field($model, 'email') ?>
							</div>
							<div class="form-group">
								<?= $form->field($model, 'body')->textarea(['rows' => 6])->label('Descripción') ?>
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
					<div class="col-lg-5" data-aos="slide-left">
						<!-- <div class="mapouter">
							<div class="gmap_canvas">
								<iframe
									src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3034.996140010268!2d-3.600244923558174!3d40.4753504082883!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422e20f8f29515%3A0x51a2a2186f4cfb70!2sCalle%20de%20la%20Playa%20de%20Riazor%2C%2014%2C%20Barajas%2C%2028042%20Madrid%2C%20Espa%C3%B1a!5e0!3m2!1ses-419!2sve!4v1709047233932!5m2!1ses-419!2sve"
									width="550" height="790" style="border:0;" allowfullscreen="" loading="lazy"
									referrerpolicy="no-referrer-when-downgrade"></iframe>
								<style>
									.gmap_canvas {
										overflow: hidden;
										background: none !important;
										height: 790px;
										width: 550px;
									}
								</style>
							</div>
						</div> -->

						<div class="mapouter">
							<div class="gmap_canvas"><iframe width="550" height="790" id="gmap_canvas"
									src="https://maps.google.com/maps?q=Calle+Miguel+de+Cervantes+10.+CP+28860&t=&z=17&ie=UTF8&iwloc=&output=embed"
									frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
								<style>
									.mapouter {
										position: relative;
										text-align: right;
										height: 790px;
										width: 550px;
									}
								</style>
							</div>
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
				
		if (window.innerWidth >= 768) {
			$('#aviso').click();
		}
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

			if(cant >= 0){
				$('.calcular').attr('disabled', false)
				$('#msg-fechas').css('display', 'none');
			}else{
				$('.calcular').attr('disabled', true)
				$('#msg-fechas').css('display', 'block');
			}
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
		    if (hour > 0) {
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

			if(cant >= 0){
				$('.calcular').attr('disabled', false)
				$('#msg-fechas').css('display', 'none');
			}else{
				$('.calcular').attr('disabled', true)
				$('#msg-fechas').css('display', 'block');
			}
			
			if (((hora_out <= hora_in) && (fecha_in === fecha_out)) ||(fechaInicio > fechaFin)) {
            	$('.calcular').attr('disabled', true);	
				$('#msg-fechas').css('display', 'block');	
            } else {
            	$('.calcular').attr('disabled', false);
				$('#msg-fechas').css('display', 'none');
            } 

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
		    if (hour > 0) {
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
		    if (hour > 0) {
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
		    if (hour > 0) {
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

			if (((hora_out <= hora_in) && (fecha_in === fecha_out)) || (fechaInicio > fechaFin)) {
            	$('#msg-fechas').css('display', 'block');		
				$('.calcular').attr('disabled', true);
            } else {
            	$('#msg-fechas').css('display', 'none');
				$('.calcular').attr('disabled', false);
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

		$('.close').css('order', 1);
									
		$('.calcular').on('click' ,function()
		{
			var f1 = new Date($('#reservas-fechae').val() + ' '+ $('#reservas-horae').val()); 
			var f2 = new Date($('#reservas-fechas').val() + ' '+ $('#reservas-horas').val()); 
			
			if(f1 > f2 || f1.getTime() == f2.getTime()){
				$('.msg-fechas').css('display', 'block');
			} else{			
				$(this)
				.text('')
				.html('<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span> Espere por favor...')
				.attr('disabled', 'disabled')
				setTimeout(() => {
					$('#fechas-form').submit();
				}, 1000);
				
				
			}
		});

		/*$('#reservas-fechae').change(function(){
			console.log($(this).val());
			$('#reservas-fechas').val($(this).val());
		});*/
		
		function convertDateFormat(string) {
			var info = string.split('-');
			return info[2] + '-' + info[1] + '-' + info[0];
		}
		
    ");
?>