<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ServiciosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin([
  'header' => 'DATOS DEL SERVICIO',
  'id' => 'servicio',
  'size' => 'modal-md',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalContent'></div>";

Modal::end();

$this->title = Yii::$app->name.' | Servicios';
$this->params['breadcrumbs'][] = 'Gestión de Servicios';

?>
<div class="servicios-index">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Gestión de Servicios</div>
    <div class="panel-body gs1">
      <div class="row">

        <div align="center" class="col-lg-1">          
          <?= Html::button('Agregar<br>Servicio', [                        
            'value' => Yii::$app->urlManager->createUrl('/servicios/create'),
            'class' => 'btn btn-full',
            'id' => 'BtnModalId',
            'data-toggle'=> 'modal',
            'data-target'=> '#servicios',

          ]) ?> 
        </div>

        <div class="col-lg-11 col-md-11 col-xs-12">
          <div class="panel panel-default busqueda">
            <div class="panel-body body-busqueda">
              <div class="subtitulo-reserva">Buscar Servicio</div><br>
              <?php Pjax::begin(); ?>
              <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

              <div class="subtitulo-reserva">Listado de Servicios</div><br><br> 

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
                'itemLabelSingle' => 'Servicios',
                'itemLabelPlural' => 'Servicio',                 
                'columns' => [
                  [                      
                    'header' => 'N°',
                    'class' => 'kartik\grid\SerialColumn'
                  ],

                  [
                    'attribute' => 'nombre_servicio',
                    'contentOptions' => ['style' => 'width:510px; white-space: normal;'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                  ],

                  [
                    'attribute' => 'descripcion',
                    'contentOptions' => ['style' => 'width:510px; white-space: normal;'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                  ],                  

                  [
                    'attribute' => 'costo',
                    'contentOptions' => ['style' => 'width:140px; white-space: normal; text-align:right'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                    'format' => ['currency'],
                  ],

                  [
                    'contentOptions' => ['style' => 'width:230px; white-space: normal;'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                    'attribute' => 'fijo',
                    'value' => function($model) {
                      if ($model->fijo === 0) {
                        return ('Servicio Opcional');
                      }
                      if ($model->fijo === 1) {
                        return ('Servicio Fijo');
                      }
                      if ($model->fijo === 2) {
                        return ('Servicio Extra');
                      }                                                
                    },
                    'format' => 'text',
                    'filter' => Select2::widget([
                      'model' => $searchModel,
                      'attribute' => 'fijo',
                      'data' => ['0'=>'Servicio Opcional', '1'=>'Servicio Fijo', '2'=>'Servicio Extra'],
                      'options' => [
                        'placeholder' => 'Seleccione Tipo de Servicio',
                      ],
                      'pluginOptions' => [ 
                        'allowClear' => true,
                      ],
                    ]),
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
                    'controller' => 'servicios', 
                    'buttons' => [ 
                      'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-search"></span>', '#', [
                          'class' => 'btn-view',
                          'id' => 'view',
                          'title' => Yii::t('app', 'Consultar'),
                          'data-toggle' => 'modal',
                          'data-target' => '#servicio',
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
                          'data-target' => '#servicio',
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
      $('#servicio').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });

    $(document).on('click', '#update', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContent').html(data);
          $('#servicio').modal();
        }
      );
    }));

    $(document).on('click', '#view', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContent').html(data);
          $('#servicio').modal();
        }
      );
    }));
  ");
?>
