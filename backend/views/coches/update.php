<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Coches */

$this->title = Yii::$app->name.' | Modificar Vehículo';
$this->params['breadcrumbs'][] = ['label' => 'Vehículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->marca.' ('.$model->modelo.')', 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar Vehículo';
?>
<div class="coches-update">

    <div class="title-margin-new">
        <span style="display: inline">Modificar Vehículo</span> 
    </div>

    <?= $this->render('_form', [
	    'model' => $model,
	    'listaClientes' => $listaClientes,
	]) ?>
	
</div>
