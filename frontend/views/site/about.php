<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = Yii::$app->name.' | Quiénes Sómos';
?>
<div class="site-about">

	<div class="section-reserva">
		<div class="row">
			<div class=" col-lg-12">

				<div class="col-lg-7 col-md-12 col-xs-12"></div>          

				<div class="col-lg-5 col-md-12 col-xs-12">
					<div class="panel panel-default reservation">
						<div class="panel-heading caja-title">¿Quiénes Sómos?</div>
						<div class="panel-body caja">  
							<p>Parking Plus ha sido creada para entregar a nuestros clientes el mejor servicio a precios muy económicos de corta y larga estancia en el Aeropuerto de Madrid Barajas.</p>

							<br>

							<p>Con un servicio de entrega y recogida de vehículo en el propio aeropuerto, evitando largos e incómodos traslados desde otros aparcamientos alejados. Optimizando al máximo su tiempo para hacer su viaje más tranquilo.</p>

							<br>

							<p>Disponemos de un proceso de Reserva Online muy rápido y sencillo.</p>

						</div>
					</div>

				</div>


			</div>
		</div>
	</div>

	<div class="row">

		<div class="col-lg-12">
			<div class="title-s-extras">
				<h3 style="display: inline">Parking Plus, una Empresa de Aparcamientos Larga Estancia en Barajas</h3> 
			</div>
		</div>

		<div class="col-lg-12">
			<br> 
			<div class="col-lg-4">
				<div class="panel-separator3" style="border-left: 2px dashed #ccc">

					<?= Html::img('@web/images/telef.png', ['class'=>'img img-responsive img-min']);?>
					<div class="pcontent-services">
						<p style="font-size: 1.1em">Contando siempre con la ayuda de un Servicio de Atención al Cliente, tanto telefónico como vía e-mail.</p>
					</div>

				</div>							
			</div>

			<div class="col-lg-4">
				<div class="panel-separator1">
					<?= Html::img('@web/images/chofer.png', ['class'=>'img img-responsive img-min']);?>
					<div class="pcontent-services2">
						<p style="font-size: 1.1em">Con un equipo de profesionales perfectamente identificados que realizarán el servicio en el propio Aeropuerto.</p>
					</div>
				</div>				
			</div>

			<div class="col-lg-4">
				<div class="panel-separator1">
					<?= Html::img('@web/images/garantia.png', ['class'=>'img img-responsive img-min']);?>
					<div class="pcontent-services2">
						<p style="font-size: 1.1em">Con las máximas garantías, seguros para cualquier tipo de desperfectos. Seguridad 24 horas los 365 días del año.</p>
					</div>
				</div>					
			</div>

			<div class="col-lg-12"><br><br></div>

		</div>	
	</div>	
</div>
