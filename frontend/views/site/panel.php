<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\PrereservaForm */

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\ActiveForm;
use frontend\models\UserCliente;
use yii\bootstrap\Alert; 
use kartik\time\TimePicker;
use yii\bootstrap\Modal;

$this->title = Yii::$app->name.' - Aparcamientos Larga Estancia en Barajas';

Modal::begin([
	'header' => 'NUEVO VEHÍCULO',
	'id' => 'nuevo_coche',
	'size' => 'modal-md',

]); ?>	

<div id='modalContent'></div>

<?php

Modal::end();

Modal::begin([
	'header' => 'MODIFICAR DATOS',
	'id' => 'update_cliente',
	'size' => 'modal-md',

]); ?>	

<div id='modalContent'></div>

<?php

Modal::end();

?>

<div class="site-index" style="margin-top: 80px">
	<div class="row">
		<div class=" col-md-12">
			<div class="mgen">
				<div class="panel panel-default panel-admin" style="padding: 10px">
					<div class="panel-heading caja-panel">Panel de Usuario - Bienvenid@ : <span style="text-transform: capitalize;"><?= $name ?></span></div>
					<div class="panel-body" style="padding: 30px 5px 40px 5px">				

						<div class="col-lg-6 col-md-6">
							<div class="panel panel-default panel-admin" style="padding: 10px">
								<div class="panel-heading caja-panel">Datos del Usuario</div>
								<div class="panel-body" style="padding-bottom: 25px">

									<?php if ($user_cliente == NULL) { ?>

										<div class="col-md-12">
											<label class="control-label">Usuario</label>
											<div class="datos-user"><?= $datos->username ?></div>
										</div>

									<?php } else { ?>
										<div class="col-md-8" style="padding: 0px">
											<label class="control-label">Nombre y Apellidos</label>
											<div class="datos-user"><?= $datos->nombre_completo ?></div>
										</div>
										<div class="col-md-4">
											<label class="control-label"><?= $datos->tipo_documento ?></label>
											<div class="datos-user"><?= $datos->nro_documento ?></div>
										</div>										
										<div class="col-md-6" style="padding-left: 0px">
											<label class="control-label">Correo Electrónico</label>
											<div class="datos-user" style="text-transform: lowercase;"><?= $datos->correo ?></div>
										</div>
										<div class="col-md-6 d-u2" style="padding-right: 0px">
											<label class="control-label">Móvil</label>
											<div class="datos-user"><?= $datos->movil ?></div>
										</div>							
									<?php	}	?>

									<div class="col-md-12" style="padding: 0px; margin-top: -10px;">
										<div align="right" class="form-group">
											<br>
											<?= Html::button('Modificar Datos', [                        
												'value' => Yii::$app->urlManager->createUrl('/site/cliente'),
												'class' => 'btn btn-success',
												'id' => 'BtnModalCliente',
												'data-toggle'=> 'modal',
												'data-target'=> '#update_cliente',
											]) ?>	
										</div>
									</div>
								</div>
							</div>

							<div class="panel panel-default panel-admin" style="padding: 10px; margin-bottom: 0px">
								<div class="panel-heading caja-panel">Reservas</div>
								<div class="panel-body" style="padding-bottom: 25px">
									<table class="table table-responsive table-striped table-bordered">
										<tr style="background-color: #f5f5f5; font-weight: bold;">
											<td align="center">N°</td>
											<td align="center">Reserva</td>
											<td align="center">Fecha</td>
											<td align="center">Recogida</td>
											<td align="center">Devolución</td>
											<td></td>
										</tr>

										<?php 
										$num = 1;
										$cantR = count($reservas);
										for ($i=0; $i < $cantR ; $i++) {
											$fecha_creacion = date('d-m-Y', strtotime($reservas[$i]->created_at));
											$recogida = date('d-m-Y', strtotime($reservas[$i]->fecha_entrada));
											$devolucion = date('d-m-Y', strtotime($reservas[$i]->fecha_salida));
											?>
											<tr>
												<td width="8%" align="center" style="vertical-align: middle; font-weight: bold;"><?= $num ?>
											</td>
											<td width="8%" align="center" style="vertical-align: middle; padding-left: 20px;"><?= $reservas[$i]->nro_reserva ?>
										</td>
										<td width="15%" align="center" style="vertical-align: middle"><?= $fecha_creacion ?>
									</td>
									<td width="15%" align="center" style="vertical-align: middle"><?= $recogida ?>
								</td>
								<td width="15%" align="center" style="vertical-align: middle"><?= $devolucion ?>
							</td>
							<td width="5%" align="center" style="vertical-align: middle;"><?= Html::a('Ver', ['view', 'id' => $reservas[$i]->id], ['class' => 'btn btn-xs btn-warning', 'style' => 'margin-top: 0px']) ?>
						</td>
					</tr>

					<?php $num++; }	?>
				</table>
				<div align="right">
					<?= Html::a('Ver reservas', ['site/reservas'], ['class' => 'btn btn-success']) ?>
				</div>
			</div>
		</div>
	</div>	

	<div class="col-md-6">
		<div class="panel panel-default panel-admin" style="padding: 10px">
			<div class="panel-heading caja-panel">Vehículos</div>
			<div class="panel-body" style="padding-bottom: 25px;">
				<table class="table table-responsive table-bordered table-striped">
					<tr style="background-color: #f5f5f5; font-weight: bold">
						<td align="center">N°</td>
						<td align="center">Matrícula</td>
						<td align="center">Marca</td>
						<td align="center">Modelo</td>
						<td align="center">Color</td>
						<td></td>
					</tr>									
					<?php 
					$nro_coches = count($coches); $num = 1; 
					for ($i=0; $i < $nro_coches ; $i++) { ?>
						<tr>
							<td align="center" style="font-weight: bold;"><?= $num ?></td>
							<td align="center"><?= $coches[$i]->matricula ?></td>
							<td align="center"><?= $coches[$i]->marca ?></td>
							<td align="center"><?= $coches[$i]->modelo ?></td>
							<td align="center"><?= $coches[$i]->color ?></td>
							<td width="5%" align="center" style="vertical-align: middle;"><?= Html::a('Ver', ['viewc', 'id' => $coches[$i]->id], ['class' => 'btn btn-xs btn-warning', 'style' => 'margin-top: 0px']) ?>
						</td>												
					</tr>
					<?php $num++; } ?>
				</table>
				<div align="right">
					<?= Html::button('Agregar Vehículo', [                        
						'value' => Yii::$app->urlManager->createUrl('/site/coches'),
						'class' => 'btn btn-success',
						'id' => 'BtnModalId',
						'data-toggle'=> 'modal',
						'data-target'=> '#nuevo_coche',

					]) ?>	
				</div>									
			</div>
		</div>
		<div class="panel panel-default panel-admin" style="padding: 10px; margin-bottom: 0px">
			<div class="panel-heading caja-panel">Facturas</div>
			<div class="panel-body" style="padding-bottom: 25px">
				<?php 
				$num = 1;
				$cantF = count($facturas);
				if ($cantF != 0) { ?>
					<table class="table table-responsive table-bordered">
						<tr style="background-color: #f5f5f5">
							<td align="center">N°</td>
							<td align="center">Factura</td>
							<td align="center" class="nif">NIF</td>
							<td align="center">Razón Social</td>
							<td></td>
						</tr>

						<?php for ($i=0; $i < $cantF ; $i++) { ?>
							<tr>
								<td width="8%" align="center" style="vertical-align: middle;"><?= $num ?></td>
								<td width="15%" style="vertical-align: middle; padding-left: 20px;"><?= $facturas[$i]->nro_factura ?></td>
								<td width="25%" align="center" style="vertical-align: middle" class="nif" ><?= $facturas[$i]->nif ?></td>
								<td style="vertical-align: middle; padding-left: 20px;"><?= $facturas[$i]->razon_social ?></td>
								<td width="5%" align="center" style="vertical-align: middle;"><?= Html::a('Ver', ['view', 'id' => $facturas[$i]->id], ['class' => 'btn btn-xs btn-warning', 'style' => 'margin-top: 0px']) ?></td>
							</tr>
							<?php $num++; }  ?>
						</table>
						<div align="right">
							<?= Html::a('Ver Todas sus facturas', ['site/mis_facturas'], ['class' => 'btn btn-success']) ?>
						</div>
					<?php } else {
						echo Alert::widget([
							'options' => [
								'class' => 'alert-info',
								'style' => 'margin:0px; top:0px'
							],
							'body' => 'Usted NO Dispone de Facturas Asociadas a su Cuenta',
						]);
					}	?>
				</div>
			</div>
		</div>





</div>
</div>
	</div>
</div>


</div>




</div>

<?php 
$this->registerJs(" 

	$('#BtnModalId').click(function(e){    
		e.preventDefault();
		$('#nuevo_coche').modal('show')
		.find('#modalContent')
		.load($(this).attr('value'));
		return false;
		});

		$('#BtnModalCliente').click(function(e){    
			e.preventDefault();
			$('#update_cliente').modal('show')
			.find('#modalContent')
			.load($(this).attr('value'));
			return false;
			});					
			");
			?>