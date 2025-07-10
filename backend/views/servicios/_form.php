<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Servicios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="servicios-form">
	<?php $form = ActiveForm::begin(); ?>
    <div class="row">
    	<div class="col-lg-7">      
			<?= $form->field($model, 'nombre_servicio')->textInput(['maxlength' => true]) ?>
		</div>
        <div class="col-lg-5">
            <?= $form->field($model, 'estatus')->widget(Select2::classname(), [
                'data' => $estatus,
                'options' => ['placeholder' => 'Selecccione Estado'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>        
		<div class="col-lg-12">
    		<?= $form->field($model, 'descripcion')->textInput(['maxlength' => true])->textarea(['rows' => '2']) ?>
    	</div>

        <div class="col-lg-6">
            <?= $form->field($model, 'fijo')->widget(Select2::classname(), [
                'data' => $modo,
                'options' => ['placeholder' => 'Selecccione Tipo de Servicio'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>

        <?php if($model->isNewRecord) { ?>
        <div id="costo" class="hidden">
        	<div class="col-lg-4">
    			<?= $form->field($model, 'costo', [
                	'template' => '<label class="nowrong">Precio</label><div class="input-group">{input}
                	<span class="input-group-addon">€</span></div>{error}{hint}'
            	])->textInput(['maxlength' => true]) ?>    		
        	</div>
        </div>
        <div id="lista" class="hidden">
            <div class="col-lg-4">
                <?= $form->field($model, 'id_listas_precios')->widget(Select2::classname(), [
                    'data' => $listas_precios,
                    'options' => ['placeholder' => 'Selecccione Plan'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div> 
        <?php } else { if ($model->id_listas_precios == 0) { ?>

        <div id="costo" class="">
            <div class="col-lg-4">
                <?= $form->field($model, 'costo', [
                    'template' => '<label class="nowrong">Precio</label><div class="input-group">{input}
                    <span class="input-group-addon">€</span></div>{error}{hint}'
                ])->textInput(['maxlength' => true]) ?>         
            </div>
        </div>
        <?php } else { ?>
        <div id="lista" class="">
            <div class="col-lg-4">
                <?= $form->field($model, 'id_listas_precios')->widget(Select2::classname(), [
                    'data' => $listas_precios,
                    'options' => ['placeholder' => 'Selecccione Plan'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
        <?php } } ?>                   	       	

        <div align="right" class="col-lg-12" style="margin-top: 10px">
            <div class="form-group">
                <?= Html::submitButton('Guardar Datos', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
	</div>
    <?php ActiveForm::end(); ?>
</div>

<?php   
    $this->registerJs("  

        $('#servicios-fijo').change(function() {
            var id = $(this).val();
            if (id == 0) {
                $('#lista').prop('class','');
                $('#costo').prop('class','hidden');
            } else {
                $('#lista').prop('class','hidden');
                $('#costo').prop('class','');
            }       
        }) 
        
    ");
?>
