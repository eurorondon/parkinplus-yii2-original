<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\CocheMergeForm */

$this->title = Yii::$app->name.' | Unificar Vehículos';
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Vehículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Unificar Vehículos';
?>

<div class="coches-merge">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Unificar por matrícula</div>
    <div class="panel-body gs1">
      <p>Ingrese la matrícula y la marca correcta para conservar un único registro y reasignar todas las reservas.</p>

      <?php $form = ActiveForm::begin(); ?>

      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <?= $form->field($model, 'matricula')->textInput(['maxlength' => true, 'placeholder' => 'Matrícula'])->label('Matrícula a unificar') ?>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
          <?= $form->field($model, 'marca')->textInput(['maxlength' => true, 'placeholder' => 'Marca definitiva']) ?>
        </div>
      </div>

      <div class="form-group">
        <?= Html::submitButton('Unificar matrícula', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
      </div>

      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
