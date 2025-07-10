<?php 

use yii\helpers\Html;

$this->title = 'Parkout - Aparcamientos | Reserva Procesada';

?>

<div class="site-procesada">
    <section id="procesada" class="section-procesada">
        <div class="row">
            <div class="content-politicas">
                <div class="title-process mt-30">
                	<?= 'Su Reserva ha sido Procesada' ?>
                </div>
            </div>
            <div class="subtitle-privacy"><?= 'N° de Reserva : '.$model->nro_reserva ?></div>
        </div>
    </section>

    <div class="container-page">
		<div class="politicas">
			<div class="row">
				<div class="col-lg-5 content-servicios m-proccess">
					<div class="title-section-config"><?= 'Información de la Reserva' ?></div>

					<table class="table table-responsive table-striped mt-35">
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'Nombre del Parking'?>
								</div>								
							</td>
							<td>
								<div class="text-title-datos">
									
								</div>								
							</td>
						</tr>
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'Tipo de Plaza'?>
								</div>								
							</td>
							<td>
								<div class="text-title-datos">
									
								</div>								
							</td>
						</tr>																
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'N° de Reserva'?>
								</div>								
							</td>
							<td>
								<div class="text-title-datos">
									<span><?= $model->nro_reserva ?></span>
								</div>								
							</td>
						</tr>
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'Fecha de Entrada' ?>
								</div>
							</td>
							<td>
								<div class="text-title-datos">
									<span><?= date('d/m/Y', strtotime($model->fecha_entrada)).' - '.$model->hora_entrada ?></span>
								</div>								
							</td>
						</tr>
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'Fecha de Salida' ?>
								</div>
							</td>
							<td>
								<div class="text-title-datos">
									<span><?= date('d/m/Y', strtotime($model->fecha_salida)).' - '.$model->hora_salida ?></span>
								</div>								
							</td>
						</tr>
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'Nombre y Apellidos' ?>
								</div>
							</td>
							<td>
								<div class="text-title-datos">
									<span><?= $model->cliente->nombre_completo ?></span>
								</div>								
							</td>
						</tr>
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'Teléfono' ?>
								</div>
							</td>
							<td>
								<div class="text-title-datos">
									<span style="text-transform:none">0424</span>
								</div>								
							</td>
						</tr>
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'Correo Electrónico' ?>
								</div>
							</td>
							<td>
								<div class="text-title-datos">
									<span style="text-transform:none"><?= $model->cliente->correo ?></span>
								</div>								
							</td>
						</tr>						
					</table>
				</div>

				<div class="col-lg-3 content-servicios m-proccess">
					<div class="title-section-config"><?= 'Datos del Vehículo' ?></div>

					<table class="table table-responsive table-striped mt-35">
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'Matrícula' ?>
								</div>								
							</td>
							<td>
								<div class="text-title-datos">
									<span><?= $model->coche->matricula ?></span>
								</div>								
							</td>
						</tr>
						<tr>
							<td>
								<div class="text-title-datos">
									<?= 'Marca & Modelo' ?>
								</div>								
							</td>
							<td>
								<div class="text-title-datos">
									<span><?= $model->coche->marca ?></span>
								</div>								
							</td>
						</tr>
					</table>
					<br>
					<hr class="line-primary">
					<div class="title-section-config mt-20"><?= 'Tipo de Servicio' ?></div>

					<table class="table table-responsive table-striped mt-20">
						<tr>
							<td>
								<div class="text-title-datos">
									<span>ajaja</span>
								</div>								
							</td>
						</tr>
					</table>
				</div>

				


				<div class="col-lg-3 content-servicios cs-0 m-proccess pb-10">
					<div class="row">
						<div class="col-lg-7">
							<div class="title-section-config" style="margin-top: 5px"><?= 'Importe' ?></div>
						</div>
						<div class="col-lg-5">
							<div class="mount"><?= $model->monto_total ?> €</div>
						</div>
					</div>
				</div>

				<div class="col-lg-3 content-servicios cs-0 m-proccess">
					<div class="row">
						<div class="col-lg-12">
							<?= Html::a('Descargar Comprobante - PDF', ['site/view-pdf', 'id' => $model->id], ['class' => 'btn btn-primary btn-block']) ?>
						</div>
					</div>
				</div>
			</div>

			<?php if($PayerID != 'NULL') { ?>
			<div class="row mt-15">
				<div class="col-lg-12" style="padding-right: 35px">
					<div class="content-servicios cs-0">
						<div class="title-section-config"><?= 'Detalles de Pago' ?></div>

						<div class="msje-pagado"><?= 'Su pago ha sido realizado de Manera Exitosa' ?></div>

						<div class="row">
							<div class="col-lg-6">
								<table class="table table-responsive table-striped table-bordered mt-14">
									<tr>
										<td>
											<div class="text-title-datos">
												<?= 'Tipo de Pago' ?>
											</div>								
										</td>
										<td>
											<div class="text-title-datos">
												<span>online</span>
											</div>								
										</td>
									</tr>
									
									<tr>
										<td>
											<div class="text-title-datos">
												<?= 'Método de Pago' ?>
											</div>								
										</td>
										<td>
											<div class="text-title-datos">
												<span>Paypal</span>
											</div>								
										</td>
									</tr>
								</table>
							</div>
							
							<div class="col-lg-6">
								<table class="table table-responsive table-striped table-bordered mt-14">
									<tr>
										<td>
											<div class="text-title-datos">
												<?= 'ID del Pago' ?>
											</div>								
										</td>
										<td>
											<div class="text-title-datos">
												<span><?= $paymentId ?></span>
											</div>								
										</td>
									</tr>

									<tr>
										<td>
											<div class="text-title-datos">
												<?= 'ID del Pagador' ?>
											</div>								
										</td>
										<td>
											<div class="text-title-datos">
												<span><?= $PayerID ?></span>
											</div>								
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>

			<?php if (($signatureRecibida != 'NULL') && ($signatureCalculada != 'NULL')) { ?>
			<div class="row mt-15">
				<div class="col-lg-12" style="padding-right: 35px">
					<div class="content-servicios cs-0">
						<div class="title-section-config"><?= 'Detalles de Pago' ?></div>

						<div class="msje-pagado"><?= 'Su pago ha sido realizado de Manera Exitosa' ?></div>

						<div class="row">
							<div class="col-lg-12">
								<table class="table table-responsive table-striped table-bordered mt-14">
									<tr>
										<td>
											<div class="text-title-datos">
												<?= 'Tipo de Pago' ?>
											</div>								
										</td>
										<td>
											<div class="text-title-datos">
												<span>tpv</span>
											</div>								
										</td>
									</tr>
									
									<tr>
										<td>
											<div class="text-title-datos">
												<?= 'Método de Pago' ?>
											</div>								
										</td>
										<td>
											<div class="text-title-datos">
												<span><?= 'Pago con Tarjeta' ?></span>
											</div>								
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>


			<div class="row mt-15">
				<div class="col-lg-12" style="padding-right: 35px">
					<div class="content-servicios cs-0">
						<div class="row">
							<div class="col-lg-9">
								<div class="title-section-config text-green">
									<?= 'El Comprobante de su reserva ha sido enviado a su dirección de correo electrónico' ?>
								</div>
							</div>
							<div align="right" class="col-lg-3">
								<?= Html::a('Volver a la página de Inicio', ['site/index'], ['class' => 'btn btn-secondary btn-lg']) ?>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>