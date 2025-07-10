<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PrecioTemporada*/

$this->title = 'Update Precio Temporada: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Precio Temporada', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="paradas-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
