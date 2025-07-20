<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $valoracion common\models\EncuestaInicial */

$this->title = 'Valoración Completada';
$labels = [
    1 => 'excelente',
    2 => 'buena',
    3 => 'normal',
    4 => 'mala',
    5 => 'muy mala',
];
?>
<div class="container mt-5 mb-5 pt-3">
    <div class="card shadow rounded overflow-hidden">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><?= Html::encode($this->title) ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Reserva N°:</strong> <?= Html::encode($valoracion->reserva_id) ?></p>
            <p>¡Gracias por su valoración!</p>
            <p>Esta reserva ya ha sido valorada anteriormente.</p>
            <?php if (isset($valoracion->created_at)) : ?>
                <p><strong>Fecha:</strong> <?= Yii::$app->formatter->asDate($valoracion->created_at, 'php:d/m/Y') ?></p>
            <?php endif; ?>
            <ul>
                <li>Pregunta 1: <?= Html::encode($labels[$valoracion->pregunta1] ?? $valoracion->pregunta1) ?></li>
                <li>Pregunta 2: <?= Html::encode($labels[$valoracion->pregunta2] ?? $valoracion->pregunta2) ?></li>
                <li>Pregunta 3: <?= Html::encode($labels[$valoracion->pregunta3] ?? $valoracion->pregunta3) ?></li>
                <li>Pregunta 4: <?= Html::encode($labels[$valoracion->pregunta4] ?? $valoracion->pregunta4) ?></li>
                <li>Pregunta 5: <?= Html::encode($labels[$valoracion->pregunta5] ?? $valoracion->pregunta5) ?></li>
            </ul>
            <p>Su opinión es muy importante para nosotros y ya ha sido registrada.</p>
        </div>
    </div>
</div>
