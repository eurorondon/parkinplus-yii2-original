<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use common\models\Facturas;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel common\models\FacturasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin([
  'header' => 'FACTURACIÓN - NUEVA FACTURA',
  'id' => 'crear-factura',
  'size' => 'modal-lg',

]);

echo "<div id='modalContent'></div>";

Modal::end();

Modal::begin([
  'header' => 'REPORTE DE FACTURACIÓN',
  'id' => 'reporte-xls',
  'size' => 'modal-md',

]);

echo "<div id='modalReport'></div>";

Modal::end();

Modal::begin([
  'header' => 'DATOS DE FACTURA',
  'id' => 'factura',
  'size' => 'modal-lg',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalContenedor'></div>";

Modal::end();

Modal::begin([
  'header' => 'ENVIAR FACTURA',
  'id' => 'enviar_factura',
  'size' => 'modal-sm',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalEnviar'></div>";

Modal::end();

$this->title = Yii::$app->name.' | Facturas';
$this->params['breadcrumbs'][] = 'Gestión de Facturas';

?>
<div class="facturas-index">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Gestión de Facturas</div>
    <div class="panel-body gs1">
      <div class="row">
        
        <div align="center" class="col-lg-1">        
          <?= Html::button('Nueva<br>Factura', [                        
            'value' => Yii::$app->urlManager->createUrl('/facturas/create'),
            'class' => 'btn btn-full',
            'id' => 'BtnModalId',
            'data-toggle'=> 'modal',
            'data-target'=> '#crear-factura',

          ]) ?> 

          <?= Html::button('&nbsp; Reporte de<br>Facturación', [                        
            'value' => Yii::$app->urlManager->createUrl('/facturas/rptfacturacion'),
            'class' => 'btn btn-full',
            'id' => 'BtnModalXls',
            'data-toggle'=> 'modal',
            'data-target'=> '#reporte-xls',

          ]) ?> 
        </div>

        <div class="col-lg-11 col-md-11 col-xs-12">
          <div class="panel panel-default busqueda">
            <div class="panel-body body-busqueda">
              <div class="subtitulo-reserva">Buscar Factura</div><br>
              <?php Pjax::begin(); ?>
              <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

              <div class="subtitulo-reserva">Listado de Facturas</div><br><br>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            //'filterModel' => $searchModel,
            'responsive' => true,
            'condensed' => true,
            'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'footerRowOptions' => ['style' => 'text-align: right'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'], 
            'showPageSummary' => true,
            'persistResize' => false,
            'toggleDataOptions' => ['minCount' => 10],
            'itemLabelSingle' => 'Facturas',
            'itemLabelPlural' => 'Factura',             
            'columns' => [
                [                      
                    'header' => 'N°',
                    'class' => 'kartik\grid\SerialColumn'
                ],

                [
                    'attribute' => 'nro_factura', 
                    'contentOptions' => ['style' => 'text-align:center'], 
                    'headerOptions' => ['style' => 'text-align:center !important'],
                    'width' => '150px',
                ],                  

                [
                    'contentOptions' => ['style' => 'width:400px; white-space: normal;'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                    'attribute' => 'razon_social',    
                    'value' => 'razon_social',
                    'format' => 'text',
                    'filter' => Select2::widget([
                        'model' => $searchModel,
                        'attribute' => 'razon_social',
                        'data' => ArrayHelper::map(Facturas::find()->orderBy('razon_social')->all(), 'razon_social', 'razon_social'),
                        'options' => [
                            'placeholder' => '',
                            'class' => 'form-control',
                        ],
                        'pluginOptions' => [ 
                            'allowClear' => true,
                        ],
                    ]),
                    'width' => '420px',
                ],  

                [
                    'attribute' => 'nif',  
                    'headerOptions' => ['style' => 'text-align:center !important'],
                    'width' => '150px',
                    'pageSummary' => 'TOTALES',
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
                  'attribute' => 'estatus',
                  'format' => 'raw',
                  'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'],
                  'headerOptions' => ['style' => 'text-align:center !important'],
                  'value' => function ($model) {
                      if ($model->estatus === 0) {
                          return Html::tag('label', Html::encode('Cancelada'), ['class' => 'label label-danger lbl']);
                      }
                      if ($model->estatus === 1) {
                          return Html::tag('label', Html::encode('Activa'), ['class' => 'label label-success lbl']);
                      }
                      if ($model->estatus === 2) {
                          return Html::tag('label', Html::encode('Pendiente'), ['class' => 'label label-primary lbl']);
                      }

                  },
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
                    'template' => "{view} &nbsp; {update} &nbsp; {send} &nbsp; {anular}", 
                    'controller' => 'facturas', 
                    'buttons' => [ 
                      'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-search"></span>', '#', [
                          'class' => 'btn-view',
                          'id' => 'view',
                          'title' => Yii::t('app', 'Consultar'),
                          'data-toggle' => 'modal',
                          'data-target' => '#factura',
                          'data-url' => Url::to(['view', 'id' => $model->id]),
                          'data-pjax' => '0',
                        ]);
                      },                       
                      'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-edit"></span>', '#', [
                          'class' => 'btn-update',
                          'id' => 'update',
                          'title' => 'Modificar',
                          'data-toggle' => 'modal',
                          'data-target' => '#factura',
                          'data-url' => Url::to(['update', 'id' => $model->id]),
                          'data-pjax' => '0',
                        ]);
                      },
                      'send' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-envelope"></span>', '#', [
                          'class' => 'btn-ticket',
                          'id' => 'enviar',
                          'title' => 'Enviar Factura',
                          'data-toggle' => 'modal',
                          'data-target' => '#enviar_factura',
                          'data-url' => Url::to(['send-factura', 'id' => $model->id]),
                          'data-pjax' => '0',
                        ]);
                      },                       
                      'anular' => function ($url, $model) { 
                        return Html::a(
                          '<span class="glyphicon glyphicon-trash"></span>', '#', 
                          [ 
                            'class' => 'btn-delete',
                            'title' => Yii::t('yii', 'Delete'),
                            'aria-label' => Yii::t('yii', 'Delete'), 
                            'onclick' => "yii.confirm('" . Yii::t(
                              'app', '¿Estas seguro de anular este elemento?') . "',
                            function(){ $.ajax('$url', {type: 'POST'}).done(function(data) { $.pjax.reload('#items-in-event', {timeout : false}).done(function () { $.pjax.reload('#event-invoice-details', {timeout : false}).done(function () { $.pjax.reload('#main-alert-widget', {timeout : false}); }); }); }); }
                            );
                            return false;",
                          ]
                        ); 
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
      $('#crear-factura').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });

    $('#BtnModalXls').click(function(e){    
      e.preventDefault();
      $('#reporte-xls').modal('show')
      .find('#modalReport')
      .load($(this).attr('value'));
      return false;
    });    

    $(document).on('click', '#update', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContenedor').html(data);
          $('#factura').modal();
        }
      );
    }));

    $(document).on('click', '#view', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContenedor').html(data);
          $('#factura').modal();
        }
      );
    }));

    $(document).on('click', '#enviar', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalEnviar').html(data);
          $('#enviar_factura').modal();
        }
      );
    }));

  ");
?>