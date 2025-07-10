<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */

$this->title = 'Encuesta de Satisfacción';
?>
<div class="encuesta-form mt-5">
    <h2><?= Html::encode($this->title) ?></h2>

    <?php $form = ActiveForm::begin(); ?>

    <!-- Campo oculto para la reserva -->
    <?= $form->field($model, 'reserva_id')->hiddenInput()->label(false) ?>

    <!-- Pregunta principal -->
    <?= $form->field($model, 'respuesta')->radioList([
        1 => 'Sí, estoy satisfecho(a)',
        0 => 'No, tengo una sugerencia',
    ]) ?>

    <!-- Sugerencias (campo siempre visible) -->
    <div id="div-sugerencias">
        <?= $form->field($model, 'sugerencias')->textarea([
            'rows' => 4,
            'placeholder' => 'Cuéntanos cómo podemos mejorar...',
        ]) ?>
    </div>

    <!-- Botón enviar -->
    <div class="form-group">
        <?= Html::submitButton('Enviar respuesta', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
// El campo de sugerencias ahora siempre se muestra, por lo que el script de
// visibilidad ha sido deshabilitado.
/*
$this->registerJs('
    $("input[name=\'EncuestaInicial[respuesta]\']").change(function(){
        if ($(this).val() == "0") {
            $("#div-sugerencias").slideDown();
        } else {
            $("#div-sugerencias").slideUp();
        }
    }).trigger("change");
');
*/
?>
