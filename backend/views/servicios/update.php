<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Servicios */

$this->title = Yii::$app->name.' | Modificar Servicio';
$this->params['breadcrumbs'][] = ['label' => 'Servicios', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre_servicio, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar Servicio';
?>
<div class="servicios-update">

    <div class="title-margin-new">
        <span style="display: inline">Modificar Servicio</span> 
    </div>
	
	<?= $this->render('_form', [
        'model' => $model,
        'estatus' => $estatus,
        'modo' => $modo,
        'listas_precios' => $listas_precios,
	]) ?>

</div>
