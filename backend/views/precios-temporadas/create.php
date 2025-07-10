<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Paradas */

$this->title = 'Create Precio Temporada';
$this->params['breadcrumbs'][] = ['label' => 'Precio Temporada', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paradas-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
