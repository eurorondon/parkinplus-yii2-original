<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Coches */

$this->title = Yii::$app->name.' | Agregar Vehículo';
$this->params['breadcrumbs'][] = ['label' => 'Vehículos', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Agregar Vehículo';
?>
<div class="coches-create">

    <div class="title-margin-new">
        <span style="display: inline">Agregar Vehículo</span> 
    </div>

    <?= $this->render('_form', [
	    'model' => $model,
	    'listaClientes' => $listaClientes,
	]) ?>

</div>
