<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model backend\models\ClienteMergeForm */
/* @var $listaClientes array */
/* @var $mergeByPhoneModel backend\models\ClienteMergePhoneForm */

$this->title = Yii::$app->name.' | Unificar Clientes';
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Unificar Clientes';
?>

<div class="clientes-merge">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Unificación automática por teléfono</div>
    <div class="panel-body gs1">
      <p>Ingrese un número de teléfono para unificar de forma automática todos los clientes que lo compartan. Se conservará el cliente con la fecha de creación más reciente.</p>

      <?php $phoneForm = ActiveForm::begin(); ?>

      <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
          <?= $phoneForm->field($mergeByPhoneModel, 'movil')->textInput(['maxlength' => true, 'placeholder' => 'Teléfono']) ?>
        </div>
      </div>

      <div class="form-group">
        <?= Html::submitButton('Unificar por teléfono', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-default']) ?>
      </div>

      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
