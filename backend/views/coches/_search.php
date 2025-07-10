<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Clientes;


$clientes = ArrayHelper::map(Clientes::find()->orderBy('nombre_completo')->all(), 'id', 'nombre_completo')
/* @var $this yii\web\View */
/* @var $model common\models\CochesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="coches-search">
    <div class="row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>

        <div class="col-lg-6 col-md-6 col-xs-12">
            <label>Nombre del Cliente o Propietario</label>
            <?= $form->field($model, 'id_cliente')->widget(Select2::classname(), [
                'data' => $clientes,
                'options' => ['placeholder' => 'Selecccione un Cliente o Propietario'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>
        </div>

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Matrícula</label>
            <?= $form->field($model, 'matricula')->label(false) ?>
        </div>              

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Marca - Modelo</label>
            <?= $form->field($model, 'marca')->label(false) ?>
        </div>                

        <div align="right" class="col-lg-12 col-md-12 col-xs-12" style="margin-top: 10px">
            <?= Html::resetButton('LIMPIAR', ['class' => 'btn btn-warning']) ?>
            &nbsp;&nbsp;&nbsp;
            <?= Html::submitButton('BUSCAR VEHÍCULO', ['class' => 'btn btn-success']) ?>
        </div> 

        <div class="col-lg-12 col-md-12 col-xs-12"><hr class="linea"></div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
