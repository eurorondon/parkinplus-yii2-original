<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $valoracion common\models\EncuestaInicial */

$this->title = 'Valoración Completada';
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
            <p><strong>Estado:</strong> <?= $valoracion->respuesta == 1 ? 'Satisfecho' : 'No satisfecho' ?></p>
            <?php if (isset($valoracion->created_at)) : ?>
                <p><strong>Fecha:</strong> <?= Yii::$app->formatter->asDate($valoracion->created_at, 'php:d/m/Y') ?></p>
            <?php endif; ?>
            <p>Su opinión es muy importante para nosotros y ya ha sido registrada.</p>
        </div>
    </div>
</div>
