<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */
/* @var $resultado bool */
/* @var $positivo bool */
?>

<div class="encuesta-inicial">
<?php if (isset($resultado) && $resultado): ?>
    <h3>Gracias por su tiempo.</h3>
    <?php if ($positivo): ?>
        <p>Nos alegra saber que está satisfecho con nuestro servicio.</p>
        <p><?= Html::a('Complete la encuesta completa aquí', 'http://bit.ly/2OHM1za', ['target' => '_blank']) ?></p>
    <?php else: ?>
        <p>Trabajaremos para mejorar su experiencia. Gracias por ayudarnos.</p>
    <?php endif; ?>
<?php else: ?>
    <h3>Encuesta de satisfacción inicial</h3>
    <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'respuesta')->radioList([1 => 'Si', 0 => 'No'])->label('¿Está satisfecho con el servicio recibido?') ?>
        <div class="form-group">
            <?= Html::submitButton('Enviar', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>
<?php endif; ?>
</div>