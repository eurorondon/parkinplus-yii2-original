<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agencias */

$this->title = 'Update Agencias: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Agencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agencias-update">

    <div class="title-margin-new">
        <span style="display: inline">Modificar Agencia</span> 
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
