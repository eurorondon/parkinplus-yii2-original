<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */

$this->title = 'Encuesta de Satisfaccion';
$opciones = [
    1 => '<i class="fa fa-smile-o text-success"></i>',
    2 => '<i class="fa fa-smile-o text-primary"></i>',
    3 => '<i class="fa fa-meh-o text-warning"></i>',
    4 => '<i class="fa fa-frown-o text-danger"></i>',
    5 => '<i class="fa fa-frown-o text-danger"></i>',
];

// CSS para alinear los radios en escritorio y apilarlos en móviles
$this->registerCss("
.radio-inline-custom {
    display: inline-flex;
    align-items: center;
    margin-right: 16px;
    font-weight: 500;
}
.radio-inline-custom input[type=\"radio\"] {
    margin-right: 6px;
}
/* En pantallas pequeñas, cada opción en su propia línea */
@media (max-width: 575.98px) {
    .radio-inline-custom {
        display: flex;
        margin-bottom: 8px;
    }
}
");
?>

<div class="container mt-5 mb-5 pt-3">

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow rounded overflow-hidden">
        <div class="card-header bg-success text-white py-3 px-4">
            <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body p-4">
            <?php $form = ActiveForm::begin(); ?>

            <?= $form->field($model, 'reserva_id')->hiddenInput()->label(false) ?>

            <?php
            // función reutilizable
            $renderRadioList = function ($attribute) use ($form, $model, $opciones) {
                return $form->field($model, $attribute, ['template' => '{input}{error}'])->radioList(
                    $opciones,
                    [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checkedAttr = $checked ? 'checked' : '';
                            return "<label class='radio-inline-custom'>
                                <input type='radio' name='{$name}' value='{$value}' {$checkedAttr}>
                                {$label}
                            </label>";
                        },
                    ]
                );
            };
            ?>

            <div class="mb-4">
                <label class="form-label fw-bold">Tiempo de espera</label>
                <p class="mb-1 text-muted"><?= Html::encode($model->getAttributeLabel('pregunta1')) ?></p>
                <?= $renderRadioList('pregunta1') ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Cuidado del vehículo</label>
                <p class="mb-1 text-muted"><?= Html::encode($model->getAttributeLabel('pregunta2')) ?></p>
                <?= $renderRadioList('pregunta2') ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Recomendación</label>
                <p class="mb-1 text-muted"><?= Html::encode($model->getAttributeLabel('pregunta3')) ?></p>
                <?= $renderRadioList('pregunta3') ?>
            </div>

            <div class="d-grid">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-success btn-lg rounded-pill']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>