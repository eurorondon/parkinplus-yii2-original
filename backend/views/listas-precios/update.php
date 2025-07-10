<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ListasPrecios */

$this->title = Yii::$app->name.' | Modificar Plan de Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Planes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar Plan de Servicio';
?>
<div class="listas-precios-update">

    <div class="title-margin-new">
        <span style="display: inline">Modificar Plan de Servicio</span> 
    </div>

	<?= $this->render('_form', [
	    'model' => $model,
	    'modelRP' => $modelRP,
	]) ?>

</div>
