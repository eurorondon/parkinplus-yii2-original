<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Paradas */

$this->title = 'Create Paradas';
$this->params['breadcrumbs'][] = ['label' => 'Paradas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paradas-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
