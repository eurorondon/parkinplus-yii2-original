<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\widgets\MaskedInput;
use common\models\Reservas;

$this->title = Yii::$app->name.' | Mis Pagos';

$modelR = Reservas:: find()->where(['nro_reserva' => $reserva])->one();

$fechai = date('d-m-Y', strtotime($modelR->fecha_entrada));
$fechaf = date('d-m-Y', strtotime($modelR->fecha_salida));

?>
<div class="site-pagos">
	<div class="row">
		<div class="col-lg-4 col-lg-offset-4">
			<div class="mgen">
				<div class="panel panel-default panel-admin" style="padding: 10px; margin-bottom: 0px; margin-top: 50px">
					<div class="panel-heading caja-panel">Procesar Pago : Reserva N° <?= $reserva ?></div>
					<div class="panel-body" style="padding: 0px">
						

							<div class="col-lg-6">
								<br>
								<label class="subdato">N° de Reserva</label>
								<input readonly="true" type="text" class="form-control" name="pedido" id="pedido" value="<?= $modelR->nro_reserva ?>">
							</div>

							<div class="col-lg-6">
								<br>
								<label class="subdato">Total a Pagar</label>
								<div class="input-group">
									<input type="text" class="form-control" name="monto" id="monto" readonly="true" value="<?= $monto ?>">
									<span class="input-group-addon">€</span>
								</div> 
							</div>

							<div class="col-lg-12" style="margin-top: 20px"><hr class="linea"></div>

							<div class="col-lg-12">
								<input type="hidden" id="cbox" class="chbox" value="1" required>
								<br><br><br>
							</div>

							<div class="col-lg-12" style="margin-top: -110px">
								<div align="right" class="form-group">
									<?php 
									$form = ActiveForm::begin([
										'id' => 'pagos-form',
										'options' => [
											'autocomplete' => 'off',
											'method' => 'post'
										],
										'action' => 'https://sis.redsys.es/sis/realizarPago'
									]); 
									?>											
									<input type="hidden" class="form-control" name="Ds_SignatureVersion" value="HMAC_SHA256_V1"/><br>
									<input type="hidden" class="form-control" name="Ds_MerchantParameters" id="Ds_MerchantParameters"><br>
									<input type="hidden" class="form-control" name="Ds_Signature" id="Ds_Signature">											
									<button id="btn-pagar" type="submit" class="btn btn-success btn-block">Pagar <?= $modelR->monto_total ?></span></button>
									<?php ActiveForm::end(); ?>
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
	$( document ).ready(function() { 
		$('.chbox').click();
	});

	$('.chbox').on('click', function(e) {
		monto = $('#monto').val();
		pedido = $('#pedido').val();
		e.preventDefault();
		$.ajax({
			type:'POST',
			url: 'cadena',
			data: { monto: monto, pedido: pedido },
			success: function(data) {
				dato = data.split('/')
				$('#Ds_MerchantParameters').val(dato[0]);
				$('#Ds_Signature').val(dato[1]);
			}            
		});

	});	    	    
");	
?>