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
        <li>Pregunta 1: <?= Html::encode($labels[$model->pregunta1] ?? $model->pregunta1) ?></li>
        <li>Pregunta 2: <?= Html::encode($labels[$model->pregunta2] ?? $model->pregunta2) ?></li>
        <li>Pregunta 3: <?= Html::encode($labels[$model->pregunta3] ?? $model->pregunta3) ?></li>
        <li>Pregunta 4: <?= Html::encode($labels[$model->pregunta4] ?? $model->pregunta4) ?></li>
        <li>Pregunta 5: <?= Html::encode($labels[$model->pregunta5] ?? $model->pregunta5) ?></li>
    </ul>
</div>
