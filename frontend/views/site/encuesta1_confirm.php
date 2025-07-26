<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model common\models\EncuestaInicial */

$this->title = 'Gracias';
$labels = [
    1 => 'excelente',
    2 => 'buena',
    3 => 'normal',
    4 => 'mala',
    5 => 'muy mala',
];
?>
<div class="site-encuesta-confirm">
    <div class="alert alert-success mb-3">
        Gracias por ayudarnos a mejorar.
    </div>
    <ul>
        <li>Tiempo de espera: <?= Html::encode($labels[$model->pregunta1] ?? $model->pregunta1) ?></li>
        <li>Cuidado del vehículo: <?= Html::encode($labels[$model->pregunta2] ?? $model->pregunta2) ?></li>
        <li>Recomendación: <?= Html::encode($labels[$model->pregunta3] ?? $model->pregunta3) ?></li>
    </ul>
    <?php if (!empty($model->sugerencias)) : ?>
        <p><strong>Sugerencias:</strong> <?= Html::encode($model->sugerencias) ?></p>
    <?php endif; ?>
</div>