<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */

$this->title = 'Sugerencias';
?>
<div class="container mt-5 mb-5 pt-3">
    <div class="card shadow rounded overflow-hidden">
        <div class="card-header bg-success text-white py-3 px-4">
            <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body p-4">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'reserva_id')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'pregunta1')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'pregunta2')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'pregunta3')->hiddenInput()->label(false) ?>

            <?= $form->field($model, 'sugerencias')->textarea(['rows' => 4]) ?>

            <div class="d-grid">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-success btn-lg rounded-pill']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
