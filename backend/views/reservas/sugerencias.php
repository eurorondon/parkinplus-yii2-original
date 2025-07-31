<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\EncuestaInicialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Sugerencias de Clientes';
?>
<div class="sugerencias-index">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Sugerencias de Clientes</div>
    <div class="panel-body gs1">
      <?php Pjax::begin(); ?>
      <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'columns' => [
          ['class' => 'kartik\\grid\\SerialColumn'],
          'reserva_id',
          [
            'attribute' => 'sugerencias',
            'format' => 'ntext',
            'contentOptions' => ['style' => 'white-space: normal'],
          ],
          [
            'attribute' => 'created_at',
            'format' => ['date', 'php:d/m/Y'],
          ],
        ],
      ]); ?>
      <?php Pjax::end(); ?>
    </div>
  </div>
</div>
