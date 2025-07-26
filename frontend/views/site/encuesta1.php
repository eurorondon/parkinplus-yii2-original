<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */

$this->title = 'Encuesta de Satisfaccion';
$opciones = [
    1 => 'excelente',
    2 => 'buena',
    3 => 'normal',
    4 => 'mala',
    5 => 'muy mala',
];
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

            <div class="mb-4">
                <label class="form-label fw-bold">Tiempo de espera</label>
                <?= $form->field($model, 'pregunta1', ['template' => '{input}{error}'])->radioList($opciones) ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Cuidado del vehículo</label>
                <?= $form->field($model, 'pregunta2', ['template' => '{input}{error}'])->radioList($opciones) ?>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Recomendación</label>
                <?= $form->field($model, 'pregunta3', ['template' => '{input}{error}'])->radioList($opciones) ?>
            </div>


            <div class="d-grid">
                <?= Html::submitButton('Enviar', ['class' => 'btn btn-success btn-lg rounded-pill']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

