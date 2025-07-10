<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */

$this->title = 'Encuesta de Satisfacción';
?>

<div class="container mt-5 mb-5 pt-3">
    <div class="card shadow rounded overflow-hidden">
        <div class="card-header bg-success text-white py-3 px-4">
            <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body p-4">
            <?php $form = ActiveForm::begin(); ?>

            <!-- Campo oculto para la reserva -->
            <?= $form->field($model, 'reserva_id')->hiddenInput()->label(false) ?>

            <!-- Pregunta de satisfacción con separación amplia -->
            <div class="mb-4">
                <label class="form-label fw-bold">¿Estás satisfecho con el servicio recibido?</label>

                <?= $form->field($model, 'respuesta', [
                    'template' => '{input}{error}',
                ])->radioList([
                    1 => '✅ Sí, estoy satisfecho(a)',
                    0 => '⚠️ No, tengo una sugerencia',
                ], [
                    'item' => function ($index, $label, $name, $checked, $value) {
                        $checkedAttr = $checked ? 'checked' : '';
                        return "
                            <div class='form-check mb-4'>
                                <input class='form-check-input' type='radio' name='{$name}' value='{$value}' id='respuesta{$index}' {$checkedAttr}>
                                <label class='form-check-label ms-2' for='respuesta{$index}'>{$label}</label>
                            </div>
                        ";
                    }
                ]) ?>
            </div>

            <!-- Campo de sugerencias (oculto por defecto) -->
            <div id="div-sugerencias" class="mb-4" style="display: none;">
                <?= $form->field($model, 'sugerencias')->textarea([
                    'rows' => 4,
                    'placeholder' => 'Cuéntanos cómo podemos mejorar...',
                    'class' => 'form-control'
                ])->label('Tus sugerencias') ?>
            </div>

            <!-- Botón de envío -->
            <div class="d-grid">
                <?= Html::submitButton('✅ Enviar respuesta', ['class' => 'btn btn-success btn-lg rounded-pill']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<?php
// Script para mostrar el campo de sugerencias si se selecciona "No"
$this->registerJs("
    function toggleSugerencias() {
        const value = $('input[name=\"EncuestaInicial[respuesta]\"]:checked').val();
        if (value == '0') {
            $('#div-sugerencias').slideDown();
        } else {
            $('#div-sugerencias').slideUp();
        }
    }

    $('input[name=\"EncuestaInicial[respuesta]\"]').on('change', toggleSugerencias);
    toggleSugerencias();
");
?>