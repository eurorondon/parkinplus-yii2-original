<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AgenciasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin([
  'header' => 'AGENCIA - REGISTRAR NUEVA AGENCIA',
  'id' => 'crear-agencia',
  'size' => 'modal-lg',

]);

echo "<div id='modalContent'></div>";

Modal::end();

Modal::begin([
  'header' => 'DATOS DE AGENCIA',
  'id' => 'agencia',
  'size' => 'modal-lg',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalContenedor'></div>";

Modal::end();

$this->title = Yii::$app->name.' | Agencias';
$this->params['breadcrumbs'][] = 'Agencias';
?>
<div class="agencias-index">

  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Agencias</div>
    <div class="panel-body gs1">
      <div class="row">

        <div align="center" class="col-lg-1">        
          <?= Html::button('Agregar<br>Agencia', [                        
            'value' => Yii::$app->urlManager->createUrl('/agencias/create'),
            'class' => 'btn btn-full',
            'id' => 'BtnModalId',
            'data-toggle'=> 'modal',
            'data-target'=> '#crear-agencia',

          ]) ?>          
        </div>

        <div class="col-lg-11 col-md-11 col-xs-12">
          <div class="panel panel-default busqueda">
            <div class="panel-body body-busqueda">
              <div class="subtitulo-reserva">Buscar Agencia</div><br>
              <?php Pjax::begin(); ?>
              <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

              <div class="subtitulo-reserva">Listado de Agencias</div><br><br>

              <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'responsive' => true,
                'responsiveWrap' => false,
                'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                'footerRowOptions' => ['style' => 'text-align: right'],
                'filterRowOptions' => ['class' => 'kartik-sheet-style'], 
                'persistResize' => false,
                'toggleDataOptions' => ['minCount' => 10],
                'itemLabelSingle' => 'Agencias',
                'itemLabelPlural' => 'Agencia',                 
                'columns' => [
                  [                      
                    'header' => 'N°',
                    'class' => 'kartik\grid\SerialColumn'
                  ],

                  [
                    'attribute' => 'nombre',
                    'contentOptions' => ['style' => 'white-space: normal;'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                  ],
                  [
                    'attribute' => 'telefono',
                    'contentOptions' => ['style' => 'white-space: normal;'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                  ],
                  [
                    'attribute' => 'movil',
                    'contentOptions' => ['style' => 'white-space: normal;'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                  ],
                  [
                    'attribute' => 'contacto',
                    'contentOptions' => ['style' => 'white-space: normal;'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
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
                    'template' => "{view} &nbsp; {update} &nbsp;{delete}", 
                    'controller' => 'agencias', 
                    'buttons' => [ 
                      'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-search"></span>', '#', [
                          'class' => 'btn-view',
                          'id' => 'view',
                          'title' => Yii::t('app', 'Consultar'),
                          'data-toggle' => 'modal',
                          'data-target' => '#agencia',
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
                          'data-target' => '#agencia',
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
      $('#crear-agencia').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });    
    $(document).on('click', '#update', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContenedor').html(data);
          $('#agencia').modal();
        }
      );
    }));

    $(document).on('click', '#view', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContenedor').html(data);
          $('#plan').modal();
        }
      );
    }));
    
  ");
?>