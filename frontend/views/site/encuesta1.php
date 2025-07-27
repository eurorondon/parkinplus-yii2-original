<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */

$this->title = 'Encuesta de Satisfacción';

// Íconos únicos por pregunta (Font Awesome 5+)
$opcionesPregunta1 = [
    1 => '<i class="fa fa-grin-beam text-success"></i>',       // Excelente
    2 => '<i class="fa fa-smile text-primary"></i>',           // Buena
    3 => '<i class="fa fa-meh text-warning"></i>',             // Normal
    4 => '<i class="fa fa-frown text-danger"></i>',            // Mala
    5 => '<i class="fa fa-angry text-danger"></i>',            // Muy mala
];

$opcionesPregunta2 = [
    1 => '<i class="fa fa-grin-hearts text-success"></i>',     // Excelente
    2 => '<i class="fa fa-laugh text-primary"></i>',           // Buena
    3 => '<i class="fa fa-meh-rolling-eyes text-warning"></i>',// Normal
    4 => '<i class="fa fa-sad-tear text-danger"></i>',         // Mala
    5 => '<i class="fa fa-dizzy text-danger"></i>',            // Muy mala
];

$opcionesPregunta3 = [
    1 => '<i class="fa fa-grin-beam-sweat text-success"></i>', // Excelente
    2 => '<i class="fa fa-grin-squint text-primary"></i>',     // Buena
    3 => '<i class="fa fa-grin text-warning"></i>',            // Normal
    4 => '<i class="fa fa-sad-cry text-danger"></i>',          // Mala
    5 => '<i class="fa fa-tired text-danger"></i>',            // Muy mala
];

// CSS adaptado
$this->registerCss("
.radio-group-custom {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.radio-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 50px;
}

.radio-option i {
    font-size: 1.6rem;
    line-height: 1;
    display: block;
    text-align: center;
    margin-bottom: 6px;
}

.radio-option input[type='radio'] {
    display: block;
    margin: 0 auto;
    transform: scale(1.1);
}

/* Alinear a la izquierda solo en pantallas grandes */
@media (min-width: 768px) {
    .radio-group-custom {
        justify-content: flex-start;
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
            $renderRadioList = function ($attribute, $opciones) use ($form, $model) {
                return $form->field($model, $attribute, ['template' => '{input}{error}'])->radioList(
                    $opciones,
                    [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checkedAttr = $checked ? 'checked' : '';
                            return "<div class='radio-option'>
                                {$label}
                                <input type='radio' name='{$name}' value='{$value}' {$checkedAttr}>
                            </div>";
                        },
                        'separator' => '',
                        'class' => 'radio-group-custom'
                    ]
                );
            };
            ?>

            <div class="mb-4">
                <label class="form-label fw-bold">Tiempo de espera</label>
                <p class="mb-1 text-muted">¿Cómo calificarías la eficiencia del servicio de recogida y devolución de tu coche?</p>
                <?= $renderRadioList('pregunta1', $opcionesPregunta1) ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Cuidado del vehículo</label>
                <p class="mb-1 text-muted">¿Consideras que su vehículo fue tratado con cuidado durante el tiempo que estuvo bajo custodia del servicio?</p>
                <?= $renderRadioList('pregunta2', $opcionesPregunta2) ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Recomendación</label>
                <p class="mb-1 text-muted">¿Recomendarías nuestro servicio a otros clientes?</p>
                <?= $renderRadioList('pregunta3', $opcionesPregunta3) ?>
            </div>

            <div class="d-grid">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-success btn-lg rounded-pill']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>