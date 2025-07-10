<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Clientes */

$this->title = Yii::$app->name.' | Modificar Cliente';
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nombre_completo, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar Cliente';
?>
<div class="clientes-update">

    <div class="title-margin-new">
        <span style="display: inline">Modificar Cliente</span> 
    </div>

	<?= $this->render('_form', [
	    'model' => $model,
	    'tipo_documento' => $tipo_documento,
	]) ?>

</div>
