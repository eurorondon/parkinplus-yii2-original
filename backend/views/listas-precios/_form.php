<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\ListasPrecios */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="listas-precios-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="row">       
    <div class="col-lg-3">
      <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="col-lg-2">
      <?= $form->field($model, 'agregado', [
        'template' => '<label class="nowrong">Cuota por Día</label><div class="input-group">{input}
        <span class="input-group-addon">€</span></div>{error}{hint}'
      ])->textInput(['maxlength' => true]) ?>         
    </div>

    <div class="col-lg-3">
      <?= $form->field($model, 'estatus')->widget(Select2::classname(), [
          'data' => ['0' => 'Inactivo', '1' => 'Activo'],
          'pluginOptions' => [
              'allowClear' => true
          ],
      ]); ?>
    </div>
    
    <div class="col-lg-4"></div>

    <div class="col-lg-12"><hr style="margin-top: 30px"></div>

    <div class="col-lg-12 subtitulo-reserva" style="margin-top: 15px; margin-bottom: 20px ">Registro de Precios
    </div> 

    <?php 
    if($model->isNewRecord) {
      $cant = 30; $num = 1;
      for ($i=0; $i < $cant ; $i++) { 
        ?> 
        <div class="col-lg-2" style="padding-left: 5px">    
          <div class="col-lg-4" style="padding-left: 10px; padding-right: 10px">         
            <label class="num-price"><?= $num; ?></label>
            <?= $form->field($model, 'cantidad')->hiddenInput(['name' => 'cantidad'.$i, 'id' => 'cantidad'.$i, 'value' => $num])->label(false) ?>                
          </div>
          <div class="col-lg-8" style="margin-top: 15px; padding-left: 0px">
            <?= $form->field($model, 'costo', [
              'template' => '<div class="input-group">{input}
              <span class="input-group-addon">€</span></div>{error}{hint}'
            ])->textInput(['name' => 'costo'.$i, 'id' => 'costo'.$i]) ?>
          </div>
        </div>
        <?php $num++; } } else {  $cant = count($modelRP); for ($i=0; $i < $cant; $i++) { ?> 
          <div class="col-lg-2" style="padding-left: 5px;">    
            <div class="col-lg-4" style="padding-left: 10px; padding-right: 10px">         
              <label class="num-price"><?= $modelRP[$i]->cantidad; ?></label>
              <?= $form->field($model, 'cantidad')->hiddenInput(['value' => $modelRP[$i]->cantidad, 'name' => 'cantidad'.$i, 'id' => 'cantidad'.$i])->label(false) ?>                
            </div>
            <div class="col-lg-8" style="margin-top: 15px; padding-left: 0px ">
              <?= $form->field($model, 'costo', [
                'template' => '<div class="input-group" style="width:110px">{input}
                <span class="input-group-addon">€</span></div>{error}{hint}'
              ])->textInput(['value' => $modelRP[$i]->costo, 'name' => 'costo'.$i, 'id' => 'costo'.$i]) ?>
            </div>
          </div>
        <?php } } ?>

        <div class="col-lg-12"><hr style="margin-bottom: 5px;"></div>

        <div align="right" class="col-lg-10" style="margin-top: 25px">
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-warning']) ?>
        </div>
        <div align="right" class="col-lg-2" style="margin-top: 25px">
            <div class="form-group">
                <?= Html::submitButton('Guardar Datos', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
      </div>
      <?php ActiveForm::end(); ?>

    </div>
