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

Modal::begin([
  'header' => 'Nuevo Vehículo',
  'id' => 'nuevo_coche',
  'size' => 'modal-md',

]); ?>

<div id='modalContent'></div>

<?php

Modal::end();

Modal::begin([
  'header' => 'TARIFAS',
  'id' => 'fecha_reserva7',
  'size' => 'modal-md',

]); ?>

<div id='modalContent'></div>

<?php

Modal::end();

$this->title = Yii::$app->name.' | Panel de Usuario | Mis Reservas';
$this->params['breadcrumbs'][] = 'Mis Reservas';

?>
<div class="reservas-index">
  <div class="row">
    <div class=" col-md-12">
      <div class="mgen">

        <div class="panel panel-default panel-admin" style="padding: 10px; margin-bottom: 0px">
          <div class="panel-heading caja-panel">Mis Reservas</div>
          <div class="panel-body">         
            <div class="col-lg-1 col-md-1">
              <?= Html::button('Nueva<br>Reserva', [                        
                'value' => Yii::$app->urlManager->createUrl('/site/fechas'),
                'class' => 'btn btn-admin',
                'id' => 'fechas',
                'data-toggle'=> 'modal',
                'data-target'=> '#fecha_reserva',

              ]) ?> 

              <?= Html::a('Mis<br>Vehículos', [
                  '/site/vehiculos'
                ], 
                [
                  'class' => 'btn btn-admin',
                  'style' => 'padding-top: 30px; padding-left:10px; margin-top:50px', 
                ]
              )?>  

              <?= Html::button('Mis<br>Facturas', [                        
                'value' => Yii::$app->urlManager->createUrl('/reservas/fechas'),
                'class' => 'btn btn-admin',
                'style' => 'margin-top:50px; margin-bottom:20px',
                'id' => 'BtnModalId8',
                'data-toggle'=> 'modal',
                'data-target'=> '#fecha_reserva',

              ]) ?>                                     
            </div>

            <div class="col-lg-11 col-md-11">
            
              <div class="panel panel-default panel-busqueda">
                <div class="panel-body">             

                  <?php Pjax::begin(); ?>
                  <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

                  <br><div class="subtitulo-reserva">Listado de Reservas</div><br>

                  <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //'filterModel' => $searchModel,
                    'responsive' => true,
                    'responsiveWrap' => false,
                    'condensed' => true,
                    'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                    'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                    'footerRowOptions' => ['style' => 'text-align: right'],
                    'filterRowOptions' => ['class' => 'kartik-sheet-style'], 
                    'showPageSummary' => true,
                    'persistResize' => false,
                    'toggleDataOptions' => ['minCount' => 10],
                    'itemLabelSingle' => 'Reserva',
                    'itemLabelPlural' => 'Reservas',                                       
                    'columns' => [
                      [                      
                        'header' => 'N°',
                        'class' => 'kartik\grid\SerialColumn'
                      ],

                      [
                        'attribute' => 'nro_reserva',  
                        'contentOptions' => ['style' => 'text-align:center'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '200px',
                      ],  

                      [
                        'attribute' => 'created_at', 
                        'format' => ['date', 'php:d/m/Y'],  
                        'contentOptions' => ['style' => 'text-align:center'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '250px',
                      ],

                      [
                        'attribute' => 'fecha_entrada', 
                        'format' => ['date', 'php:d/m/Y'],  
                        'contentOptions' => ['style' => 'text-align:center'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '250px',
                      ],

                      [
                        'attribute' => 'fecha_salida',
                        'format' => ['date', 'php:d/m/Y'],   
                        'contentOptions' => ['style' => 'text-align:center'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '250px',
                      ],                                                                                   

                      [
                        'attribute' => 'monto_total',  
                        'contentOptions' => ['style' => 'text-align:right'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '50px',
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
                        'controller' => 'site', 
                        'buttons' => [ 
                          'view' => function($url, $model) { 
                            return Html::a(
                              'VER',
                              [
                                'site/view',
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
</div>

      <?php 
      $this->registerJs(" 

        $('#BtnModalId').click(function(e){    
          e.preventDefault();
          $('#nuevo_coche').modal('show')
          .find('#modalContent')
          .load($(this).attr('value'));
          return false;
          });  

        $('#fechas').click(function(e){    
          e.preventDefault();
          $('#fecha_reserva').modal('show')
          .find('#modalContent')
          .load($(this).attr('value'));
          return false;
          });                
      ");
      ?>
