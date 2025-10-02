<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\EncuestaInicialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $totalPositivas int */
/* @var $totalNegativas int */

$this->title = 'Sugerencias de Clientes';
?>
<div class="sugerencias-index">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Sugerencias de Clientes</div>
    <div class="panel-body gs1">
      <div class="alert alert-info">
        <strong>Resumen global:</strong>
        <span>Sugerencias positivas: <?= Html::encode($totalPositivas) ?></span>
        <span class="m-l-15">Sugerencias negativas: <?= Html::encode($totalNegativas) ?></span>
      </div>
      <p class="help-block">Los totales mostrados corresponden al acumulado general de encuestas, independientemente de los filtros aplicados.</p>
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
            'value' => function ($model) {
              return $model->sugerencias ?: 'Sin comentarios (sugerencia positiva)';
            },
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
