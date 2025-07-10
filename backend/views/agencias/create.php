<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agencias */

$this->title = 'Create Agencias';
$this->params['breadcrumbs'][] = ['label' => 'Agencias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agencias-create">
   	<div class="title-margin-new">
        <span style="display: inline">Registrar Nueva Agencia</span> 
    </div>	

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
