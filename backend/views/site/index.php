<?php

use yii\helpers\Url;
use yii\helpers\Html;
use dosamigos\highcharts\HighCharts;

/* @var $this yii\web\View */

$this->title = Yii::$app->name.' | Inicio';

//var_dump($datos); die();

$fecha_actual = date('Y-m-d');
$mes_escrito= Yii::$app->formatter->asDate($fecha_actual, 'php:F');

$perc_sec = ($secretaria * 100)/ $totales;
$perc_age = ($agencia * 100)/ $totales;
$perc_web = ($online * 100)/ $totales;

//DATA CON VALORES 
$data1 = array('name' => 'Secretaria', 'y' => $secretaria);
$data2 = array('name' => 'Agencias', 'y' => $agencia);
$data3 = array('name' => 'Página Web', 'y' => $online);

//DATA CON % 
$p_sec = array('name' => 'Secretaria', 'y' => round($perc_sec, 2));
$p_age = array('name' => 'Agencias', 'y' => round($perc_age, 2));
$p_web = array('name' => 'Página Web', 'y' => round($perc_web, 2));

//DATA CON VALORES POR MES Y AÑO ACTUAL 
$m_sec = array('name' => 'Secretaria', 'y' => $sec_mes);
$m_age = array('name' => 'Agencias', 'y' => $ag_mes);
$m_web = array('name' => 'Página Web', 'y' => $web_mes);

?>

<ul class="breadcrumb">Menú Principal - Panel Administrativo</ul>

<div class="site-index">
	<?php if ($tipo_afiliado == 1) { ?>
	<div class="col-lg-12">
		<div class="panel panel-default panel-index">
			<div class="panel-heading caja-title">Bienvenid@</div>
			<div class="panel-body gs">
				<div class="row">
					<div class="col-md-4">
						<?= Html::img('@web/images/logo_factura.png', ['class' => 'img-responsive logo-panel']) ?>
					</div>
					<div class="col-md-3">
						<br>
						<div align="center">
							<?= Html::a('Listado de Reservas', ['/reservas/index'], ['class'=>'btn btn-full btn-ali pt pb']) ?>
						</div>
					</div>
					<div class="col-md-5">
						<?= Html::img('@web/images/left_arrow.png', ['class' => 'img-responsive arrow-panel']) ?>
						<div class="text-info-al">Haz Click para ver el listado de reservas ! Tambien podrás generar nuevas reservas, visualizar el planning y mucho más...</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<?php } else { ?>


	<div class="col-lg-12">
		<div class="panel panel-default panel-index">
			<div class="panel-heading caja-title">Reporte Gráfico - Medios de Reservación</div>
			<div class="panel-body gs">	
				<div class="col-lg-4">	
				<div id="container"></div>	
					<?= HighCharts::widget([
						'clientOptions' => [
							'chart' => [
								'type' => 'pie'							
							],
							'credits' => [
								'enabled' => false
							],
							'title' => [
								'text' => 'Reservas <br> Totales',
								'align' => 'center',
								'verticalAlign' => 'middle',
								'y' => 30
							],
							'series' => [
								[
									'dataLabels' => [
										'enabled' => true,
										'distance' => -50,
										'style' => [
											'fontFamily' => 'Exo',
											'fontSize' => '10px',
											'textTransform' => 'uppercase',
											'fontWeight' => 'bold',
											'color' => 'white'
										]
									],									
									'showInLegend' => true,
									'innerSize' => '50%', 
									'startAngle' => -90,
									'endAngle' => 90,
									'center' => ['50%', '75%'],
									'size' => '100%',									
									'name' => 'Reservas', 
									'data' => [
										$data1,										
										$data2,
										$data3
									]
								]            
							]
						]
					]) ?>
				</div>

				<div class="col-lg-4">	
					<div class="form-group">
						<select class="form-control select-mes" id="select-mes" onchange="actualizar()">
							<option value="<?= date('m'); ?>"><?= $mes_escrito; ?></option>
							<option value="01">Enero</option>
							<option value="02">Febrero</option>
							<option value="03">Marzo</option>
							<option value="04">Abril</option>
							<option value="05">Mayo</option>
							<option value="06">Junio</option>
							<option value="07">Julio</option>
							<option value="08">Agosto</option>
							<option value="09">Septiembre</option>
							<option value="10">Octubre</option>
							<option value="11">Noviembre</option>
							<option value="12">Diciembre</option>						    					    
						</select>
					</div>
					<div id="chart-update" style="display: none"></div>
					<div id="chart-mes" style="margin-top: 0px">
						<?= HighCharts::widget([
							'clientOptions' => [
								'chart' => [
									'type' => 'pie'							
								],
								'credits' => [
									'enabled' => false
								],
								'title' => [
									'text' => 'Reservas por<br> Mes',
									'align' => 'center',
									'verticalAlign' => 'middle',
									'y' => 30
								],
								'series' => [
									[
										'dataLabels' => [
											'enabled' => true,
											'distance' => -50,
											'style' => [
												'fontFamily' => 'Exo',
												'fontSize' => '10px',
												'textTransform' => 'uppercase',
												'fontWeight' => 'bold',
												'color' => 'white'
											]
										],									
										'showInLegend' => true,
										'innerSize' => '50%', 
										'startAngle' => -90,
										'endAngle' => 90,
										'center' => ['50%', '75%'],
										'size' => '100%',									
										'name' => 'Reservas', 
										'data' => [
											$m_sec,										
											$m_age,
											$m_web
										]
									]            
								]
							]
						]) ?>
					</div>
				</div>	

				<div class="col-lg-4">	
					<div id="chart-upd-ag" style="display: none;"></div>
					<div id="chart-mes-ag">
						<?= HighCharts::widget([
							'clientOptions' => [
								'chart' => [
									'type' => 'pie'							
								],
								'credits' => [
									'enabled' => false
								],
								'title' => [
									'text' => 'Reservas por<br> Agencia',
									'align' => 'center',
									'verticalAlign' => 'middle',
									'y' => 30
								],
								'series' => [
									[
										'dataLabels' => [
											'enabled' => true,
											'distance' => -50,
											'style' => [
												'fontFamily' => 'Exo',
												'fontSize' => '10px',
												'textTransform' => 'uppercase',
												'fontWeight' => 'bold',
												'color' => 'white'
											]
										],									
										'showInLegend' => true,
										'innerSize' => '50%', 
										'startAngle' => -90,
										'endAngle' => 90,
										'center' => ['50%', '75%'],
										'size' => '100%',									
										'name' => 'Reservas', 
										'data' => [
											$datos[0],
											$datos[1]
										]
									]            
								]
							]
						]) ?>
					</div>							
				</div>
			</div>
		</div>
		<br>	
	</div>

	<div class="col-lg-6">
		<div class="panel panel-default panel-index">
			<div class="panel-heading caja-title">Clientes / Vehículos</div>
			<div class="panel-body gs">	
				<div class="row">
					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Clientes', ['/clientes/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div>
					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Vehículos', ['/coches/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div>			
					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Precios Temp', ['/precios-temporadas/index'], ['class' => 'btn btn-full pt pb']) ?>
					</div>	
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-default panel-index">
			<div class="panel-heading caja-title">Reservas y Facturación</div>
			<div class="panel-body gs">	
				<div class="row">
					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Reservas', ['/reservas/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div>

					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Facturas', ['/facturas/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div>

					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('API PARKOS', ['/reservas/apis'], ['class'=>'btn btn-full pt pb']) ?>
					</div> 
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-12"><br></div>

	<div class="col-lg-6">
		<div class="panel panel-default panel-index">
			<div class="panel-heading caja-title">Planes y Servicios</div>
			<div class="panel-body gs">	
				<div class="row">
					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Planes', ['/listas-precios/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div>

					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Servicios', ['/servicios/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div> 

					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Paradas', ['/paradas/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel panel-default panel-index">
			<div class="panel-heading caja-title">Configuración</div>
			<div class="panel-body gs">	
				<div class="row">
					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Agencias', ['/agencias/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div>  

					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Factureros', ['/factureros/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div>

					<div align="center" class="col-lg-4 col-md-6 col-xs-6">
						<?= Html::a('Config', ['/otros/index'], ['class'=>'btn btn-full pt pb']) ?>
					</div>    
				</div>
			</div>
		</div>
	</div>

	<?php } ?>	
</div>

<script>
	function actualizar() {
		$("#chart-update").css("display", "block");
		$("#chart-mes").css("display", "none");
		$("#chart-upd-ag").css("display", "block");
		$("#chart-mes-ag").css("display", "none");		
		mes = $("#select-mes").val()

		$.ajax({
			url: '<?php echo \Yii::$app->getUrlManager()->createUrl('reservas/chart') ?>',
			type: 'post',
			data: { 
				mes: mes
			},
			success: function (data) {
				data1 = data.datos['m_sec'];
				data2 = data.datos['m_age'];
				data3 = data.datos['m_web'];
				data4 = data.datos['agencias'];

				Highcharts.chart('chart-update', {

			    title: {
			      text: 'Reservas por<br> Mes',
			      align: 'center',
			      verticalAlign: 'middle',
			      y: 30
			    },
			    credits: {
			    	enabled: false
			    },
			    plotOptions: {
			      pie: {
			        dataLabels: {
			          enabled: true,
			          distance: -50,
			          style: {
									fontFamily: 'Exo',
									fontSize: '10px',
									textTransform: 'uppercase',
			      	    fontWeight: 'bold',
			            color: 'white'
			          }
			        },
			        startAngle: -90,
			        endAngle: 90,
			        center: ['50%', '75%'],
			        size: '100%'
			      }
			    },
			    series: [{
			    	showInLegend: true,
			      type: 'pie',
			      name: 'Reservas',
			      innerSize: '50%',
			      data: [
			      	data1,
			      	data2,
			       	data3
			      ]
			    }]
				});

				Highcharts.chart('chart-upd-ag', {

			    title: {
			      text: 'Reservas por<br> Agencia',
			      align: 'center',
			      verticalAlign: 'middle',
			      y: 30
			    },
			    credits: {
			    	enabled: false
			    },
			    plotOptions: {
			      pie: {
			        dataLabels: {
			          enabled: true,
			          distance: -50,
			          style: {
									fontFamily: 'Exo',
									fontSize: '10px',
									textTransform: 'uppercase',
			      	    fontWeight: 'bold',
			            color: 'white'
			          }
			        },
			        startAngle: -90,
			        endAngle: 90,
			        center: ['50%', '75%'],
			        size: '100%'
			      }
			    },
			    series: [{
			    	showInLegend: true,
			      type: 'pie',
			      name: 'Reservas',
			      innerSize: '50%',
			      data: [
			      	data4[0],
			      	data4[1]
			      ]
			    }]
				});

			},
			error: function(){
			console.log("failure");
			}            
		});
	}
</script>