<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;

$this->title = Yii::$app->name.' | Mis Pagos';

?>
<ul class="breadcrumb">Parking Plus - Mis Pagos</ul>
<div class="site-pagos">
	<div class="row">
		<div class="col-lg-12">
			<div class="mgen">
				<div class="panel panel-default panel-admin" style="padding: 10px; margin-bottom: 0px">
					<div class="panel-heading caja-panel">Mis Pagos</div>
					<div class="panel-body">
						<div class="col-lg-12">
							<div class="panel panel-default">
                				<div class="panel-body"> 
									<div class="col-lg-3">
										<br>
										<label class="subdato">N° de Reserva</label>
										<?= Select2::widget([
										    'model' => $model,
										    'attribute' => 'id_reserva',
										    'data' => $lista_reservas,
										    'options' => ['placeholder' => 'Seleccione'],
										    'pluginOptions' => [
										        'allowClear' => true
										    ],
										]); ?>							
							        </div>

							        <div class="col-lg-5">
							        	<br>
							        	<label class="dato">Nombre del Titular</label>
							        	<input type="text" class="form-control" name="cliente" id="cliente">
							        </div>	

							        <div class="col-lg-1"></div>

								    <div class="col-lg-2">
								    	<br>
										<?=Html::img('@web/images/tarjetas.png', ['class'=>'img img-responsive img-tarjetas']); ?>
								    </div>							        

							        <div class="col-lg-12" style="margin-top: 20px"></div>

							        
							        <div class="col-lg-3">
							        	<label class="dato">Fecha de Recogida</label>
							        	<div class="info-view" id="fecha_e">00-00-0000</div>				        	
							        </div>
							        <div class="col-lg-3">
			            				<label class="dato">Fecha de Devolución</label>
							        	<div class="info-view" id="fecha_s">00-00-0000</div>      	
							        </div>	
							        <div class="col-lg-2">
							        	<label class="dato" style="margin-bottom: 10px">Total a Pagar</label>
										<div class="input-group">
											<input type="text" class="form-control" name="monto" id="monto" readonly="true" placeholder="0.00">
						                    <span class="input-group-addon">€</span>
						                </div> 
							        </div>

							        <div class="col-lg-12" style="margin-top: 20px"><hr class="linea"></div>

							        <div class="col-lg-5"></div>

									<div class="col-lg-4">
										<input type="checkbox" id="cbox" class="chbox" value="1" required> <label class="cbox" for="cbox">Acepto la Politica de Privacidad</label>
									</div>

									<div class="col-lg-3" style="margin-top: -60px">
										<div align="right" class="form-group" style="margin-top: 35px">
											<?php 
												$form = ActiveForm::begin([
													'id' => 'pagos-form',
													'options' => [
														'target' => '_blank',
														'autocomplete' => 'off',
														'method' => 'post'
													],
													'action' => 'https://sis-t.redsys.es:25443/sis/realizarPago'
												]); 
											?>											
											<input type="hidden" class="form-control" name="Ds_SignatureVersion" value="HMAC_SHA256_V1"/><br>
											<input type="hidden" class="form-control" name="Ds_MerchantParameters" id="Ds_MerchantParameters"><br>
											<input type="hidden" class="form-control" name="Ds_Signature" id="Ds_Signature">											
											<button type="submit" class="btn btn-success btn-block">Pagar <span class="valor"></span></button>
											<?php ActiveForm::end(); ?>
										</div>
									</div>
							    </div>
							</div>				        			        
				        </div>
				    </div>
				</div>
			</div>
		</div>
	</div>
</div>				       

					<div class="hide">
				        <div class="col-lg-7" style="margin-top: 15px">
				        	<div class="panel panel-default panel-d">
				        		<div class="panel-body" style="padding: 30px 10px 60px 10px">
					        		<div class="col-lg-12">
			        			
								        <div class="col-lg-5">
								        	<label class="dato">N° de Tarjeta</label>
								        	<input type="text" class="form-control" name="nro_tarjeta" id="nro_tarjeta" placeholder="0000-0000-0000-0000" value="4548812049400004">
								        </div>

							        	<div class="col-lg-12" style="margin-top: 15px"></div>

							        	<div class="col-lg-4">
							        		<div class="col-lg-12 row">
							        			<label class="dato">Fecha de Expiración</label>
							        		</div>
							        		<div class="col-lg-12" style="margin-top: 5px"></div>
									        <div class="col-lg-6 row">
									        	<label class="subdato">Mes</label>
									        	<input type="text" class="form-control" name="mes" id="mes" placeholder="MM" value="12">
									        </div>						        	        	
									        <div class="col-lg-6">
									        	<label class="subdato">Año</label>
									        	<input type="text" class="form-control" name="ayo" id="ayo" placeholder="AA" value="20">
									        </div>							        		
							        	</div>
					       				
					       				<div class="col-lg-4" style="margin-left: -56px">
									        <div class="col-lg-12">
								        		<div class="col-lg-12 row">
								        			<label class="dato">Código de Seguridad</label>
								        		</div>
								        		<div class="col-lg-12" style="margin-top: 5px"></div>								        	
									        	<label class="subdato">CVV2</label>
								        		<input type="text" class="form-control" name="cvv" id="cvv" placeholder="000" value="285">				            				
									        </div>
								    	</div>


							    	
									</div>

		
				        		</div>
				        	</div>
				        </div>
				        <input type="hidden" class="form-control" name="pedido" id="pedido"><br>      
				        <input type="hidden" class="form-control" name="fecha_expira" id="fecha_expira"><br>
				    </div>
				        


<?php 
    $this->registerJs(" 
		$(document).ready(function(){
		  $('#nro_tarjeta').mask('0000-0000-0000-0000');
		  $('#nro_cvv').mask('000');
		  $('#mes').mask('00');
		  $('#ayo').mask('00');		  
		});

	    $('#pagosform-id_reserva').on('change', function(e) {
	    	id = $('#pagosform-id_reserva').val();
	        e.preventDefault();
	        $.ajax({
	            type:'POST',
	            url: 'datos',
	            data: { id: id },
	            success: function(data) {
	            	datos = JSON.parse(data)
	            	res = datos.split('/')
	            	valor = ': '+ res[3]+' €';
	            	$('#pedido').val(res[0]);
					$('#fecha_e').html(res[1]);
					$('#fecha_s').html(res[2]);
					$('#monto').val(res[3]);
					$('#cliente').css('text-transform', 'uppercase');
					$('#cliente').val(res[4]);
					$('.valor').html(valor);
	            }            

	        });
	    });

	    $('.chbox').click(function() {
	        $('.chbox:checked').each(function() {
	            id = $(this).val();
	            mes = $('#mes').val();
	            ayo = $('#ayo').val();
	            expired = ayo+mes;
	            $('#fecha_expira').val(expired);
	        });
	    });

	    $('.chbox').on('change', function(e) {
	    	monto = $('#monto').val();
	    	pedido = $('#pedido').val();
	    	nro_tarjeta = $('#nro_tarjeta').val();
	    	fecha_expira = $('#fecha_expira').val();
	    	cvv = $('#cvv').val();
	        e.preventDefault();
	        $.ajax({
	            type:'POST',
	            url: 'cadena',
	            data: { monto: monto, pedido: pedido, nro_tarjeta: nro_tarjeta, fecha_expira: fecha_expira, cvv: cvv },
	            success: function(data) {
	            	dato = data.split('/')
	            	$('#Ds_MerchantParameters').val(dato[0]);
	            	$('#Ds_Signature').val(dato[1]);
	            }            

	        });
	    });	    	    

    ");
?>