<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\ListasPrecios */

$this->title = Yii::$app->name.' | Agregar Plan de Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Planes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Agregar Plan de Servicio';
?>

<div class="listas-precios-create">
   	<div class="title-margin-new">
        <span style="display: inline">Agregar Plan de Servicio</span> 
    </div>

	<?= $this->render('_form', [
	    'model' => $model,
	]) ?>
</div>
