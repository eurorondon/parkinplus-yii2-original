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
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]); ?>

<div id='modalContent'></div>

<?php

Modal::end();

$this->title = Yii::$app->name.' | Panel de Usuario | Mis Vehículos';
$this->params['breadcrumbs'][] = 'Mis Vehículos';

?>
<div class="coches-index">
  <div class="row">
    <div class=" col-md-12">
      <div class="mgen">

        <div class="panel panel-default panel-admin" style="padding: 10px; margin-bottom: 0px">
          <div class="panel-heading caja-panel">Mis Vehículos</div>
          <div class="panel-body">         
            <div class="col-lg-1 col-md-1">
              <?= Html::button('Nuevo<br>Vehículo', [                        
                'value' => Yii::$app->urlManager->createUrl('/site/coches'),
                'class' => 'btn btn-admin',
                'id' => 'BtnModalId',
                'data-toggle'=> 'modal',
                'data-target'=> '#nuevo_coche',

              ]) ?> 

              <?= Html::a('Mis<br>Reservas', [
                  '/site/reservas'
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
                'id' => 'BtnModalId',
                'data-toggle'=> 'modal',
                'data-target'=> '#fecha_reserva',

              ]) ?>                                     
            </div>

            <div class="col-lg-11 col-md-11">  

              <div class="panel panel-default panel-busqueda">
                <div class="panel-body">                          

                  <?php Pjax::begin(); ?>
                  <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

                  <br><div class="subtitulo-reserva">Listado de Vehículos</div><br>

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
                        'attribute' => 'matricula',  
                        'contentOptions' => ['style' => 'text-align:left'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '250px',
                      ],

                      [
                        'attribute' => 'marca',  
                        'contentOptions' => ['style' => 'text-align:left'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '250px',
                      ],   

                      [
                        'attribute' => 'modelo',  
                        'contentOptions' => ['style' => 'text-align:left'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '250px',
                      ], 

                      [
                        'attribute' => 'color',  
                        'contentOptions' => ['style' => 'text-align:left'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '250px',
                      ],

                      [
                        'attribute' => 'created_at',  
                        'format' => ['date', 'php:d/m/Y'], 
                        'contentOptions' => ['style' => 'text-align:center'],
                        'headerOptions' => ['style' => 'text-align:center !important'],
                        'width' => '250px',
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
                                'site/viewc',
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
      ");
      ?>