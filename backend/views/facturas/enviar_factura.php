<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
 ?>

<div class="facturas-enviar_factura">

    <?php $form = ActiveForm::begin(); ?>

    	<div class="row" style="margin-bottom: 20px">
    		<div class="col-lg-12">
    			<label>Razón Social</label>
    			<input type="text" class="form-control" readonly="true" name="razon_social" value="<?= $datos['razon_social'] ?>">
    		</div>
    	</div>

    	<div class="row" style="margin-bottom:20px">
    		<div class="col-lg-12">
    			<label>N° de Factura</label>
    			<input type="text" class="form-control" readonly="true" name="nro_factura" value="<?= $datos['nro_factura'] ?>">
    		</div>
    	</div>     	

    	<div class="row" style="margin-bottom:20px">
    		<div class="col-lg-12">
    			<div class="msje"><?= $datos['msje'] ?></div>
    		</div>
    	</div>    	

    	<div class="row" style="margin-bottom:20px">
    		<div class="col-lg-12">
    			<label>Correo Electrónico</label>
    			<input type="text" class="form-control" name="correo" value="<?= $datos['correo_electronico'] ?>">
    		</div>
    	</div>

    	<div class="row">
    		<div class="col-lg-12">
    			<div align="right" class="form-group">
                	<?= Html::submitButton('Enviar Factura', ['class' => 'btn btn-success']) ?>
            	</div>
            </div>
    	</div>

    <?php ActiveForm::end(); ?>
</div>