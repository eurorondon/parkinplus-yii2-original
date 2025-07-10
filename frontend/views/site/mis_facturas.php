<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\select2\Select2;
use common\models\Clientes;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ReservasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */


$this->title = Yii::$app->name.' | Mis Facturas';

?>
<ul class="breadcrumb">Parking Plus - Mis Facturas</ul>
<div class="reservas-index">
  <div class="row">
    <div class=" col-md-12">
      <div class="mgen">

        <div class="col-lg-12 col-md-6">
          <div class="paneles">
            <div class="panel panel-default" style="padding: 10px; margin-bottom: 0px">
              <div class="panel-heading caja-title">Mis Facturas</div>
              <div class="panel-body">

                <div class="subtitulo-reserva" style="margin-bottom: 20px; margin-top: 10px">Listado de Facturas</div>


                <?php Pjax::begin(); ?>
                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                  'dataProvider' => $dataProvider,
                  'filterModel' => $searchModel,
                  'responsive' => true,
                  'condensed' => true,
                  'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                  'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                  'footerRowOptions' => ['style' => 'text-align: right'],
                  'filterRowOptions' => ['class' => 'kartik-sheet-style'], 
                  'showPageSummary' => true,
                  'persistResize' => false,
                  'toggleDataOptions' => ['minCount' => 10],
                  'itemLabelSingle' => 'Reservas',
                  'itemLabelPlural' => 'Reserva',                                       
                  'columns' => [
                    [                      
                      'header' => 'N°',
                      'class' => 'kartik\grid\SerialColumn'
                    ],

                    [
                      'attribute' => 'nro_factura',  
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '150px',
                    ],  

                    [
                      'attribute' => 'created_at',  
                      'contentOptions' => ['style' => 'text-align:center'],
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '150px',
                    ],

                    [
                      'attribute' => 'monto_factura',  
                      'contentOptions' => ['style' => 'text-align:right'],
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '150px',
                      'format' => ['currency'],
                      'pageSummary' => true,
                    ], 

                    [
                      'attribute' => 'monto_impuestos',  
                      'contentOptions' => ['style' => 'text-align:right'],
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '150px',
                      'format' => ['currency'],
                      'pageSummary' => true,
                    ],  

                    [
                      'attribute' => 'monto_total',  
                      'contentOptions' => ['style' => 'text-align:right'],
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '150px',
                      'format' => ['currency'],
                      'pageSummary' => true,
                    ],

                    [ 
                      'class' => 'kartik\grid\ActionColumn', 
                      'header' => '',
                      'headerOptions' => [
                        'class' => 'text-center'
                      ], 
                      'contentOptions' => [
                        'class' => 'text-center icon_actions'
                      ], 
                      'template' => "{view}", 
                      'controller' => 'reservas', 
                      'buttons' => [ 
                        'view' => function($url, $model) { 
                          return Html::a(
                            'VER',
                            [
                              'reservas/view',
                              'id' => $model->id,
                            ],
                            [
                              'class' => 'btn btn-success btn-xs',
                              'style' => 'vertical-align:middle; margin:0px'
                            ]
                          ); 
                        },                          
                      ] 
                    ]
                  ],
                ]); ?>

                <?php Pjax::end(); ?>
                <div class="col-lg-10"></div>
                <div class="col-md-2" style="padding-right: 0px">
                  <p align="right">
                    <?= Html::a('cancelar', ['panel'], ['class' => 'btn btn-warning btn-block']) ?> 
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
