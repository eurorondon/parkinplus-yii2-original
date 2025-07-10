<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\PrereservaForm */

// Si han aceptado la política
if(isset($_REQUEST['politica-cookies'])) {
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

Modal::begin([
    'header' => 'COSTO POR SERVICIO',
    'id' => 'fecha_reserva',
    'size' => 'modal-sm',

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

$this->title = Yii::$app->name.' - Aparcamientos Larga Estancia en Barajas';
?>

<div class="site-index">
	<div class="section-reserva">
		<div class="row">
			<div class=" col-lg-12 col-xs-12">
				
				<div class="col-lg-7 col-md-12 col-xs-12"></div>
				<!--
				<div class="col-lg-3 col-md-12 col-xs-12">
							<div align="center" class="offer" style="margin-top: 200px">
								<?= Html::img('@web/images/oferta.png', ['class' => 'img-responsive']) ?>
							</div>

						
				</div>			
				-->
				<div class="col-lg-5 col-md-12 col-xs-12">
					<div class="panel panel-default reservation">
						<?php 
						$form = ActiveForm::begin([
							'id' => 'fechas-form',
							'options' => [
								'autocomplete' => 'off',
							],
						]);
						?>
						<div class="panel-heading caja-title">Haga Su Reserva</div>
						<div class="panel-body caja-reserva">

							<div class="col-lg-12 col-xs-12">
								<p class="bienv">Recogeremos tu coche en el aeropuerto el día y a la hora que nos digas. A tu vuelta, te estaremos esperando en la puerta de la terminal.</p>
								<hr>
							</div>
							<div class="col-lg-7 col-xs-7 v" style="float: none;">
								<label class="control-label">Fecha de Recogida</label>
								<?= $form->field($model, 'fechae')->widget(DatePicker::classname(), [
								    'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
								    'language' => 'es',
								    'pluginOptions' => [
								    	'orientation' => 'bottom left',
								        'autoclose'=>true,
										'format' => 'dd-mm-yyyy',
										'startDate'=> date('d-m-Y'),								        
								    ]
								])->label(false); ?>
							</div>
							<div class="col-lg-5 col-xs-5" style="float: right; margin-top: -72px">
								<label class="control-label">Hora</label>
									<?= $form->field($model, 'horae')->widget(TimePicker::classname(), [
								    'pluginOptions' => [
								        'showMeridian' => false,
								    ]
								])->label(false);?>
							</div>

							<div class="col-lg-12"><br></div>

							<div class="col-lg-7 col-xs-7 v" style="float: none;">
								<label class="control-label">Fecha de Devolución</label>
								<?= $form->field($model, 'fechas')->widget(DatePicker::classname(), [
								    'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
								    'language' => 'es',
								    'pluginOptions' => [
								    	'orientation' => 'bottom left',
								        'autoclose'=>true,
										'format' => 'dd-mm-yyyy',
										'startDate'=> date('d-m-Y'),								        
								    ]
								])->label(false); ?>
							</div>

							<div class="col-lg-5 col-xs-5" style="float: right; margin-top: -72px">
								<label class="control-label">Hora</label>
								<?= $form->field($model, 'horas')->widget(TimePicker::classname(), [
								    'pluginOptions' => [
								        'showMeridian' => false,
								    ]
								])->label(false);?>
							</div>					

							<div class="row">	
								<div class="col-lg-12"><br><hr class="dash"></div>
								<input type="hidden" name="cuota_dia" id="cuota_dia" value="<?= $agregado ?>">
							</div>

							<div class="col-lg-12">
								<div align="right" class="form-group">
									<br>
						            <?= Html::button('Calcule su Tarifa', [                        
						                 'value' => Yii::$app->urlManager->createUrl('/site/fechas'),
						                 'class' => 'btn btn-success btn-block btn-big',
						                 'id' => 'BtnModalId',
						                 'data-toggle'=> 'modal',
						                 'data-target'=> '#fecha_reserva',

						            ]) ?>
								</div>
							</div>
						</div>
						<?php ActiveForm::end(); ?>	
					</div>
				</div>


			</div>
		</div>
	</div>

	<div class="col-lg-12">

		<div class="title-s-extras" style="margin-bottom: 60px; margin-top: -15px; padding-bottom: 20px">
			<h3 style="display: inline">Bienvenidos a Parking Plus</h3> <br>
			<div align="center" style="display: inline-block; font-size: 1.1em; margin-top: 15px; color: #961007">
				<b>MEDIDAS ANTI-COVID : "Desinfección con Ozono gratuita para tu coche"</b>
			</div>
		</div>
        <?php 
            $cant = count($precio_diario); $num = 1;
            for ($i=0; $i < $cant ; $i++) { ?>
            <div class="col-lg-2">
                <input class="form-control" style="margin-bottom: 20px" type="hidden" id="precio-diario<?= $num ?>" value="<?= $precio_diario[$i]['precio'] ?>">
            </div>            
        <?php $num++; } ?>

	</div>	

	<div class="col-lg-6">
		<div class="col-lg-12">
			<div class="panel panel-default full-panel" style="margin-top: 0px">
				<div class="panel-body">				
					<div class="title-margin">
						<h3 style="display: inline">Nuestros Servicios</h3> 
					</div>

					<div class="col-lg-4">
						<div class="title-min">Servicio Premium</div>
					</div>
					<div class="col-lg-8">
						<div class="hrs">Recogida y entrega de su coche en la puerta de la Terminal del Aeropuerto Barajas. El mejor precio del mercado con servicio de conductores en la puerta del aeropuerto de cualquiera de las terminales de Madrid T1, T2 ó T4.</div>
					</div>

					<div class="col-lg-12"><hr class="dash1"></div>

					<div class="col-lg-4">
						<div class="title-min">Otros Servicios</div>
					</div>

					<div class="col-lg-8">
						<div class="hrs">
							<div class="checkmark">&nbsp;</div>
							<p>Limpieza del Coche</p>
							<div class="checkmark">&nbsp;</div>
							<p>Seguridad los 365 días</p>
							<div class="checkmark">&nbsp;</div>
							<p>Atención Telefónica los 365 días</p>
							<div class="checkmark">&nbsp;</div>
							<p>Pago con Tarjetas (TPV)</p>
							<div class="checkmark">&nbsp;</div>
							<p>Choferes Identificados y Capacitados</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-12">
			<div class="panel panel-default full-panel">
				<div class="panel-body" style="padding-bottom: 40px">	
					<div class="title-margin">
						<h3 style="display: inline">Atención al Cliente</h3> 
					</div>
					<div class="col-lg-4">
						<?= Html::img('@web/images/telefonia2.png', ['class'=>'img img-responsive img-min']);?>
					</div>
					<div class="col-lg-8">
						<div class="text-lateral">
							<i class="glyphicon glyphicon-phone-alt iconsc"></i>
							<div class="text-datos" style="margin: -40px 0px 50px 35px;">Reservas:<br>
								<span class="reborde">
									<a class="reborlink" href="tel:+34912128659">+34 912 12 86 59</a>
								</span>	

							</div>
							<p class="unico">Solo para Reservas</p>
							<i class="glyphicon glyphicon-phone iconsc"></i>
							<div class="text-datos" style="margin: -30px 0px 20px 35px;">
								Asistencia en Aeropuerto: <br>+34 603 28 48 00
							</div>
							<i class="fa fa-envelope iconsc"></i>
							<div class="text-datos" style="margin: -30px 0px 0px 35px;">
								contacto@parkingplus.es
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="col-lg-6">
		<div class="panel panel-default" style="margin-left: -5px; margin-right: -5px">
			<div class="panel-body">			
				<div class="title-margin">
					<h3 style="display: inline">Cercanía al Terminal</h3>		    	
				</div>
				<div class="text-index">
					<p>Nuestro Parking situado junto al aeropuerto de Madrid Barajas, cuenta con todo lo necesario para que viaje tranquilo y su vehículo esté siempre seguro. A precios tan económicos y de calidad. ¿Por qué pagar más?.</p>
					<p>Nuestros conductores le atenderán amablemente con mucha profesionalidad y responsabilidad, por ello contamos solo con personal altamente cualificado.</p><br>
				</div>
				<!--
				<iframe class="mapa-index" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2992.912753717569!2d-3.5649989847675942!3d40.44421876189145!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd423038e8e8ea8f%3A0x28a446f5ef13e474!2sCalle%20Mayo%2C%2080%2C%2028022%20Madrid%2C%20Espa%C3%B1a!5e1!3m2!1ses!2sve!4v1584390094214!5m2!1ses!2sve" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
				<iframe class="mapa-index" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2991.52595823635!2d-3.597820184766702!3d40.47535455999609!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd422fcf3cba8035%3A0x86e010c61baa2eb5!2sparkingplus!5e1!3m2!1ses-419!2sve!4v1576263934383!5m2!1ses-419!2sve" frameborder="0" allowfullscreen=""></iframe>	
				-->			 		    
			</div>
		</div>

		<div class="panel panel-default" style="margin-left: -5px; margin-right: -5px">
			<div class="panel-body">			
				<div class="title-margin">
					<h3 style="display: inline">Parking en Solo 3 Pasos</h3>		    	
				</div>
				<div class="text-index" style="margin-bottom: 20px">
					<div class="row">
						<div class="col-lg-3">
							<?= Html::img('@web/images/car-phone.jpg', ['class' => 'img-responsive img-rew']) ?>
						</div>
						<div class="col-lg-9"><span class="num-x">1</span><b>RESERVA</b>
							<div class="text-num">Selecciona las fechas e ingresa tus datos y te enviaremos la confirmación del servicio de manera automática.</div>
						</div>
					</div>
					<div class="row" style="margin-top: 30px">
						<div class="col-lg-3">
							<?= Html::img('@web/images/car-key.png', ['class' => 'img-responsive img-rew']) ?>
						</div>
						<div class="col-lg-9"><span class="num-x">2</span><b>RECOGIDA</b>
							<div class="text-num">Nuestro personal recojerá su vehículo en la puerta del terminal.</div>
						</div>
					</div>
					<div class="row" style="margin-top: 30px">
						<div class="col-lg-3">
							<?= Html::img('@web/images/car-front.png', ['class' => 'img-responsive img-rew']) ?>
						</div>
						<div class="col-lg-9"><span class="num-x">3</span><b>ENTREGA</b>
							<div class="text-num">Nuestro personal entregará el vehículo en la puerta del terminal a su previa confirmación de llegada.</div>
						</div>						
					</div>										
				</div>		 		    
			</div>
		</div>
	</div>

	<div class="col-lg-12">
		<?php if (!isset($_REQUEST['politica-cookies']) && !isset($_COOKIE['politica'])): ?>
		<!-- Mensaje de cookies -->
		<div class="cookies">
		    <!-- Descripción con enlace -->
		    <p align="center" style="margin-bottom: 0px">Utilizamos cookies propias y de terceros, para realizar el análisis de la navegación de los usuarios.<span align="right"> ¿&nbsp;Aceptas nuestra&nbsp;&nbsp; 
		    	<!-- Botón para aceptar -->
                    <?= Html::button('Política de Uso de Cookies &nbsp;', [                        
                        'class' => 'btn-modal-cookies',
                        'id' => 'BtnModalCookies',
                        'data-toggle'=> 'modal',
                        'data-target'=> '#cookies',

                    ]) ?> ?

		    	<a class="btn btn-warning btn-xs btn-coockies" href="?politica-cookies=1">SÍ</a>
		    </p>
		</div>
		<?php endif; ?>
	</div>

</div>	

<?php 
    $this->registerJs(" 

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
                var total = precio_relativo + (cant_dias * cuota); 
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

		});


        $('#BtnModalId').click(function(e){    
            e.preventDefault();
            $('#fecha_reserva').modal('show')
            .find('#modalContent')
            .load($(this).attr('value'));
            return false;
        });

		function convertDateFormat(string) {
			var info = string.split('-');
			return info[2] + '-' + info[1] + '-' + info[0];
		}		    

    ");
?>
