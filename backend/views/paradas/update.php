<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Paradas */

$this->title = 'Update Paradas: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Paradas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="paradas-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
