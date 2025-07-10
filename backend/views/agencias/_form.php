<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Agencias */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agencias-form">

  <?php $form = ActiveForm::begin(); ?>
  <div class="row">       
    <div class="col-lg-6">
      <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
    </div> 

    <div class="col-lg-3">
      <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>
    </div> 

    <div class="col-lg-3">
      <?= $form->field($model, 'movil')->textInput(['maxlength' => true]) ?>
    </div>  

    <div class="col-lg-6">
      <?= $form->field($model, 'contacto')->textInput(['maxlength' => true]) ?>
    </div>   

    <div class="col-lg-6">
      <?= $form->field($model, 'direccion')->textInput(['maxlength' => true])->textarea(['rows' => '2']) ?>
    </div>                 

    <div class="col-lg-12"><br><hr style="margin-bottom: 5px;"></div>

    <div align="right" class="col-lg-10" style="margin-top: 25px">
      <?= Html::button('Cancelar', ['class' => 'btn btn-warning', 'data-dismiss' => 'modal']) ?>
    </div>
    
    <div align="right" class="col-lg-2" style="margin-top: 25px">
      <div class="form-group">
        <?= Html::submitButton('Guardar Datos', ['class' => 'btn btn-success']) ?>
      </div>
    </div>
    

    <?php ActiveForm::end(); ?>

</div>
