<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use kartik\select2\Select2;
use common\models\Clientes;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ReservasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin([
  'header' => 'FECHAS DE RESERVA',
  'id' => 'fecha_reserva',
  'size' => 'modal-md',

]);

echo "<div id='modalContent'></div>";

Modal::end();

Modal::begin([
  'header' => 'IMPRESIÓN DE TICKETS / SOBRES - ENTRADAS',
  'id' => 'tickets_masivos',
  'size' => 'modal-md',
  'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
]);

echo "<div id='modalT'></div>";

Modal::end();

Modal::begin([
  'header' => 'IMPRESIÓN DE TICKETS / SOBRES - SALIDAS',
  'id' => 'tickets_masivos_out',
  'size' => 'modal-md',
  'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
]);

echo "<div id='modalTS'></div>";

Modal::end();

Modal::begin([
  'header' => 'DATOS DE RESERVA',
  'id' => 'reserva',
  'size' => 'modal-lg',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalContenedor'></div>";

Modal::end();

Modal::begin([
  'header' => 'IMPRIMIR TICKET',
  'id' => 'ticket',
  'size' => 'modal-md',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalTicket'></div>";

Modal::end();

$this->title = Yii::$app->name.' | Planning de Reservas';
$this->params['breadcrumbs'][] = ['label' => 'Gestión de Reservas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Planning de Reservas';

?>
<div class="reservas-index">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Planning de Reservas</div>
    <div class="panel-body gs1 pad-mob">
      <div class="row">
        
        <div align="center" class="col-lg-1">
          <?= Html::a('Listado de<br>Reservas', ['/reservas/index'], ['class' => 'btn btn-full', 'style' => 'padding-top: 10px; padding-left:15px']) ?>

          <?= Html::button('Agregar<br>Reserva', [                        
            'value' => Yii::$app->urlManager->createUrl('/reservas/fechas'),
            'class' => 'btn btn-full',
            'id' => 'BtnModalId',
            'data-toggle'=> 'modal',
            'data-target'=> '#fecha_reserva',

          ]) ?>        
        </div>

        <div class="col-lg-11 col-md-11 col-xs-12 pad-0">
          <div class="panel panel-default busqueda">
            <div class="panel-body body-busqueda b2">
              <div class="subtitulo-reserva">Ingrese la Fecha</div><br>
              <?php Pjax::begin(); ?>
                <?php  echo $this->render('_searchPlanning', ['model' => $searchModel]); ?>

                <div class="row">
                  <div class="col-lg-8">
                    <div class="subtitulo-reserva pad-0">Listado de Reservas - Entradas</div>
                  </div>
                  <div align="right" class="col-lg-4">

                    <?php
                      $reservas_entrada = $dataProvider->getModels();
                      for ($i=0; $i < count($reservas_entrada); $i++) { 
                        $lista_ids[$i] = $reservas_entrada[$i]->id;  
                      } ?>
                      
                      <?php
                      $reservas_entrada_1 = $dataProvider3->getModels();

                      for ($i=0; $i < count($reservas_entrada_1); $i++) { 
                        $lista_ids_1[$i] = $reservas_entrada_1[$i]->id;  
                      } 
                      $ids_imprime = json_encode($lista_ids_1);
                      ?>

                      <div class="hide"><?php var_dump($lista_ids); ?></div>

                      <?php                       
                      $ids = json_encode($lista_ids);
                    ?>

                    <?= Html::button('Imprimir Tickets & Sobres - Reservas Entrantes', [                        
                      'value' => Yii::$app->urlManager->createUrl(['/reservas/ticket-planning', 'ids' => $ids_imprime]),
                      'class' => 'btn btn-primary',
                      'id' => 'BtnTickets',
                      'data-toggle'=> 'modal',
                      'data-target'=> '#tickets_masivos',                    
                    ]) ?>  
                  </div>
                </div>


          <?=
         GridView::widget([
              'dataProvider' => $dataProvider,
              //'filterModel' => $searchModel,
              'responsive' => true,
              'condensed' => true,
              'responsiveWrap' => false,
              'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
              'headerRowOptions' => ['class' => 'kartik-sheet-style'],
              'footerRowOptions' => ['style' => 'text-align: right'],
              'filterRowOptions' => ['class' => 'kartik-sheet-style'], 
              'showPageSummary' => false,
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
                  'contentOptions' => ['style' => 'text-align:center; width:150px; white-space: normal;'],
                  'attribute' => 'nro_reserva',  
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '100px',
                ],                

                [
                  'contentOptions' => ['style' => 'width:400px; white-space: normal; text-transform:uppercase'],
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'attribute' => 'id_cliente',    
                  'value' => 'cliente.nombre_completo',
                  'format' => 'text',
                  'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_cliente',
                    'data' => ArrayHelper::map(Clientes::find()->orderBy('nombre_completo')->all(), 'id', 'nombre_completo'),
                    'options' => [
                      'placeholder' => '',
                      'class' => 'form-control',
                    ],
                    'pluginOptions' => [ 
                      'allowClear' => true,
                    ],
                  ]),
                  'width' => '450px',
                ],

                [
                  'contentOptions' => ['style' => 'text-align:center; width:150px; white-space: normal;'],
                  'attribute' => 'terminal_entrada', 
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '250px',
                ],

                [
                  'contentOptions' => ['style' => 'text-align:center; width:150px; white-space: normal;'],
                  'attribute' => 'hora_entrada', 
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '100px',
                ],  

                [
                  'contentOptions' => ['style' => 'text-align:center; width:150px; white-space: normal;'],
                  'attribute' => 'coche.matricula', 
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '150px',
                ],

                [
                  'attribute' => 'coche.marca',
                  'label' => 'Marca - Modelo', 
                  'contentOptions' => ['style' => 'vertical-align:middle'], 
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '50px',
                  'value' => function($model) {
                    return ($model->coche->marca.' '.$model->coche->modelo);
                  },                  
                ],
                // ADD ER 29-06
                [
                    'attribute' => 'monto_total',
                    'label' => 'Monto',
                    'format' => ['decimal', 2], // para mostrar 2 decimales
                    'contentOptions' => ['style' => 'text-align:right; vertical-align:middle; white-space:nowrap'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                    'value' => function ($model) {
                      return $model->monto_total;
                    },
                  ],
                                                                    

                    [ 
                      'class' => 'kartik\grid\ActionColumn', 
                      'header' => '',
                      'headerOptions' => [
                        'class' => 'text-center'
                      ], 
                      'contentOptions' => [
                        'class' => 'text-center icon_actions',
                        
                      ], 
                      'template' => "{view} &nbsp; {update} &nbsp; {delete} &nbsp;&nbsp;&nbsp; {ticket}", 
                      'controller' => 'reservas', 
                      'buttons' => [ 
                        'view' => function ($url, $model, $key) {
                          return Html::a('<span class="glyphicon glyphicon-search"></span>', '#', [
                            'class' => 'btn-view',
                            'id' => 'view',
                            'title' => Yii::t('app', 'Consultar'),
                            'data-toggle' => 'modal',
                            'data-target' => '#reserva',
                            'data-url' => Url::to(['view', 'id' => $model->id]),
                            'data-pjax' => '0',
                          ]);
                        },                       
                        'update' => function ($url, $model, $key) {
                          return Html::a('<span class="glyphicon glyphicon-edit"></span>', '#', [
                            'class' => 'btn-update',
                            'id' => 'update',
                            'title' => Yii::t('app', 'Modificar'),
                            'data-toggle' => 'modal',
                            'data-target' => '#reserva',
                            'data-url' => Url::to(['update', 'id' => $model->id]),
                            'data-pjax' => '0',
                          ]);
                        }, 
                        'delete' => function ($url, $model) { 
                          return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', '#', 
                            [ 
                              'class' => 'btn-delete',
                              'title' => Yii::t('yii', 'Delete'),
                              'aria-label' => Yii::t('yii', 'Delete'), 
                              'onclick' => "yii.confirm('" . Yii::t(
                                'app', '¿Estas seguro de eliminar este elemento?') . "',
                              function(){ $.ajax('$url', {type: 'POST'}).done(function(data) { $.pjax.reload('#items-in-event', {timeout : false}).done(function () { $.pjax.reload('#event-invoice-details', {timeout : false}).done(function () { $.pjax.reload('#main-alert-widget', {timeout : false}); }); }); }); }
                              );
                              return false;",
                            ]
                          ); 
                        },
                        'ticket' => function ($url, $model, $key) {
                          return Html::a('<span class="glyphicon glyphicon-print"></span>', '#', [
                            'class' => 'btn-ticket',
                            'id' => 'print-ticket',
                            'title' => Yii::t('app', 'Imprimir Ticket'),
                            'data-toggle' => 'modal',
                            'data-target' => '#ticket',
                            'data-url' => Url::to(['ticket', 'id' => $model->id]),
                            'data-pjax' => '0',
                          ]);
                        },                          
                      ] 
                    ]
                  ],
                ]); ?>

                <hr class="linea"><br>

                <div class="row">
                  <div class="col-lg-8">
                    <div class="subtitulo-reserva">Listado de Reservas - Salidas</div>
                  </div>
                  <div align="right" class="col-lg-4">

                    <?php
                      $reservas_salida = $dataProvider1->getModels();

                      for ($i=0; $i < count($reservas_salida); $i++) { 
                        $lista_salida_ids[$i] = $reservas_salida[$i]->id;  
                      } ?>
                      
                      <?php
                      $reservas_salida_1 = $dataProvider2->getModels();

                      for ($i=0; $i < count($reservas_salida_1); $i++) { 
                        $lista_salida_ids_1[$i] = $reservas_salida_1[$i]->id;  
                      } 
                      $ids_salida_imprime = json_encode($lista_salida_ids_1);
                      ?>

                      <div class="hide"><?php var_dump($lista_salida_ids); ?></div>

                      <?php 
                      $ids_salida = json_encode($lista_salida_ids);
                    ?>

                    <?= Html::button('Imprimir Tickets & Sobres - Reservas Salientes', [                        
                      'value' => Yii::$app->urlManager->createUrl(['/reservas/ticket-planning', 'ids' => $ids_salida_imprime]),
                      'class' => 'btn btn-primary',
                      'id' => 'BtnTickets_Salida',
                      'data-toggle'=> 'modal',
                      'data-target'=> '#tickets_masivos_out',
                    ]) ?>  
                  </div>
                </div>


          <?=             

            GridView::widget([
              'dataProvider' => $dataProvider1,
              //'filterModel' => $searchModel,
              'responsive' => true,
              'condensed' => true,
              'responsiveWrap' => false,
              'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
              'headerRowOptions' => ['class' => 'kartik-sheet-style'],
              'footerRowOptions' => ['style' => 'text-align: right'],
              'filterRowOptions' => ['class' => 'kartik-sheet-style'], 
              'showPageSummary' => false,
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
                  'contentOptions' => ['style' => 'text-align:center; width:150px; white-space: normal;'],
                  'attribute' => 'nro_reserva',  
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '100px',
                ],                

                [
                  'contentOptions' => ['style' => 'width:400px; white-space: normal; text-transform:uppercase'],
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'attribute' => 'id_cliente',    
                  'value' => 'cliente.nombre_completo',
                  'format' => 'text',
                  'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'id_cliente',
                    'data' => ArrayHelper::map(Clientes::find()->orderBy('nombre_completo')->all(), 'id', 'nombre_completo'),
                    'options' => [
                      'placeholder' => '',
                      'class' => 'form-control',
                    ],
                    'pluginOptions' => [ 
                      'allowClear' => true,
                    ],
                  ]),
                  'width' => '450px',
                ],


                [
                  'contentOptions' => ['style' => 'text-align:center; width:150px; white-space: normal;'],
                  'attribute' => 'terminal_salida',  
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '250px',
                ], 

                [
                  'contentOptions' => ['style' => 'text-align:center; width:150px; white-space: normal;'],
                  'attribute' => 'hora_salida', 
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '100px',
                ],  

                [
                  'contentOptions' => ['style' => 'text-align:center; width:150px; white-space: normal;'],
                  'attribute' => 'coche.matricula', 
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '150px',
                ],

                [
                  'attribute' => 'coche.marca',
                  'label' => 'Marca - Modelo', 
                  'contentOptions' => ['style' => 'vertical-align:middle'], 
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'width' => '50px',
                  'value' => function($model) {
                    return ($model->coche->marca.' '.$model->coche->modelo);
                  },                  
                ], 
                
                // ADD ER 29-06
                
                [
                    'attribute' => 'monto_total',
                    'label' => 'Monto',
                    'format' => ['decimal', 2], // para mostrar 2 decimales
                    'contentOptions' => ['style' => 'text-align:right; vertical-align:middle; white-space:nowrap'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                    'value' => function ($model) {
                      return $model->monto_total;
                    },
                  ],

                    [ 
                      'class' => 'kartik\grid\ActionColumn', 
                      'header' => '',
                      'headerOptions' => [
                        'class' => 'text-center'
                      ], 
                      'contentOptions' => [
                        'class' => 'text-center icon_actions',
                      ], 
                      'template' => "{view} &nbsp; {update} &nbsp; {delete} &nbsp;&nbsp;&nbsp; {ticket}",
                      'controller' => 'reservas', 
                      'buttons' => [ 
                        'view' => function ($url, $model, $key) {
                          return Html::a('<span class="glyphicon glyphicon-search"></span>', '#', [
                            'class' => 'btn-view',
                            'id' => 'view',
                            'title' => Yii::t('app', 'Consultar'),
                            'data-toggle' => 'modal',
                            'data-target' => '#reserva',
                            'data-url' => Url::to(['view', 'id' => $model->id]),
                            'data-pjax' => '0',
                          ]);
                        },                       
                        'update' => function ($url, $model, $key) {
                          return Html::a('<span class="glyphicon glyphicon-edit"></span>', '#', [
                            'class' => 'btn-update',
                            'id' => 'update',
                            'title' => Yii::t('app', 'Modificar'),
                            'data-toggle' => 'modal',
                            'data-target' => '#reserva',
                            'data-url' => Url::to(['update', 'id' => $model->id]),
                            'data-pjax' => '0',
                          ]);
                        }, 
                        'delete' => function ($url, $model) { 
                          return Html::a(
                            '<span class="glyphicon glyphicon-trash"></span>', '#', 
                            [ 
                              'class' => 'btn-delete',
                              'title' => Yii::t('yii', 'Delete'),
                              'aria-label' => Yii::t('yii', 'Delete'), 
                              'onclick' => "yii.confirm('" . Yii::t(
                                'app', '¿Estas seguro de eliminar este elemento?') . "',
                              function(){ $.ajax('$url', {type: 'POST'}).done(function(data) { $.pjax.reload('#items-in-event', {timeout : false}).done(function () { $.pjax.reload('#event-invoice-details', {timeout : false}).done(function () { $.pjax.reload('#main-alert-widget', {timeout : false}); }); }); }); }
                              );
                              return false;",
                            ]
                          ); 
                        },
                        'ticket' => function ($url, $model, $key) {
                          return Html::a('<span class="glyphicon glyphicon-print"></span>', '#', [
                            'class' => 'btn-ticket',
                            'id' => 'print-ticket',
                            'title' => Yii::t('app', 'Imprimir Ticket'),
                            'data-toggle' => 'modal',
                            'data-target' => '#ticket',
                            'data-url' => Url::to(['ticket', 'id' => $model->id]),
                            'data-pjax' => '0',
                          ]);
                        },                          
                      ] 
                    ]
                  ],
                ]); ?>                               

              <?php Pjax::end(); ?>
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
      $('#fecha_reserva').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });

    $('#BtnTickets').click(function(e){    
      e.preventDefault();
      $('#tickets_masivos').modal('show')
      .find('#modalT')
      .load($(this).attr('value'));
      return false;
    });

    $('#BtnTickets_Salida').click(function(e){    
      e.preventDefault();
      $('#tickets_masivos_out').modal('show')
      .find('#modalTS')
      .load($(this).attr('value'));
      return false;
    });    

    $(document).on('click', '#update', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContenedor').html(data);
          $('#reserva').modal();
        }
      );
    }));

    $(document).on('click', '#view', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContenedor').html(data);
          $('#reserva').modal();
        }
      );
    }));

    $(document).on('click', '#print-ticket', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalTicket').html(data);
          $('#ticket').modal();
        }
      );
    }));
  ");
?>
