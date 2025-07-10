<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$ids = json_decode($lista_ids);

$cant_archivos = count($ids);

?>

<style type="text/css">
	.alert-ticket {
		margin: 20px 0px;
	}
</style>

<div class="reservas-ticket-masivos">
	<?php $form = ActiveForm::begin(); ?>
		<div class="row mt-30">
			<div class="col-lg-6">
				<div class="form-group">
					<label>Seleccione Qué desea Imprimir</label>
					<select name="tipo_ticket" class="form-control">
						<option value="1">Tickets</option>
						<option value="2">Sobres</option>
						<option value="3">Tickets Parking</option>
					</select>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12">
				<div class="alert alert-info alert-ticket">
					Información: Se generará la Impresíon de el listado completo del planing (sus tickets o sobres según su selección). Cantidad de Reservas : <?= $cant_archivos ?>
				</div>
			</div>

			<div class="col-lg-12">
				<div align="right" class="form-group">
                    <button onclick="window.location.reload();" type="button" class="btn btn-default" data-dismiss="modal">
                        <span class="glyphicon glyphicon-remove"></span>
                        <span class="hidden-xs">&nbsp;Cerrar</span>
                    </button>
                    &nbsp;&nbsp;
					<button type="submit" class="btn btn-success" formtarget="_blank">
						<span class="glyphicon glyphicon-print"></span>
						<span class="hidden-xs">&nbsp;&nbsp;Imprimir</span>
					</button>
				</div>
			</div>
		</div>
	<?php ActiveForm::end(); ?>
</div>