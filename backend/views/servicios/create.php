<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Servicios */

$this->title = Yii::$app->name.' | Agregar Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Agregar Servicio';
?>
<div class="servicios-create">

    <div class="title-margin-new">
        <span style="display: inline">Agregar Servicio</span> 
    </div>

	<?= $this->render('_form', [
	    'model' => $model,
	    'estatus' => $estatus,
	    'modo' => $modo,
	    'listas_precios' => $listas_precios,
	]) ?>

</div>
