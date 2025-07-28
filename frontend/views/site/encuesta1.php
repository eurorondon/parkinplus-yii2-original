<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */

$this->title = 'Encuesta de Satisfacción';

$opciones = [
    1 => '<i class="fa fa-grin-hearts text-success"></i>',
    2 => '<i class="fa fa-grin-beam" style="color:#4CAF50"></i>',
    3 => '<i class="fa fa-smile text-warning"></i>',
    4 => '<i class="fa fa-frown" style="color:#ff8c00"></i>',
    5 => '<i class="fa fa-angry text-danger"></i>',
];

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

.radio-option label {
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
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

/* Tamaño más grande en móvil */
@media (max-width: 767.98px) {
    .radio-option i {
        font-size: 2.2rem;
    }
}

/* Escritorio */
@media (min-width: 768px) {
    .radio-group-custom {
        justify-content: flex-start;
    }
    .radio-option i {
        font-size: 2rem;
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
            $renderRadioList = function ($attribute) use ($form, $model, $opciones) {
                return $form->field($model, $attribute, ['template' => '{input}{error}'])->radioList(
                    $opciones,
                    [
                        'item' => function ($index, $label, $name, $checked, $value) use ($attribute) {
                            $id = $attribute . '-' . $value;
                            $checkedAttr = $checked ? 'checked' : '';
                            return "
                            <div class='radio-option'>
                                <label for='{$id}'>
                                    {$label}
                                    <input type='radio' id='{$id}' name='{$name}' value='{$value}' {$checkedAttr}>
                                </label>
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
                <?= $renderRadioList('pregunta1') ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Cuidado del vehículo</label>
                <p class="mb-1 text-muted">¿Consideras que su vehículo fue tratado con cuidado durante el tiempo que estuvo bajo custodia del servicio?</p>
                <?= $renderRadioList('pregunta2') ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Recomendación</label>
                <p class="mb-1 text-muted">¿Recomendarías nuestro servicio a otros clientes?</p>
                <?= $renderRadioList('pregunta3') ?>
            </div>

            <div class="d-grid">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-success btn-lg rounded-pill']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>