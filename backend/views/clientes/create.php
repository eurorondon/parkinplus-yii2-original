<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Clientes */

$this->title = Yii::$app->name.' | Agregar Cliente';
$this->params['breadcrumbs'][] = ['label' => 'Clientes', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Agregar Cliente';
?>
<div class="clientes-create">

    <div class="title-margin-new">
        <span style="display: inline">Agregar Cliente</span> 
    </div>

	<?= $this->render('_form', [
	    'model' => $model,
	    'tipo_documento' => $tipo_documento,
	]) ?>

</div>
