<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ParadaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin([
  'header' => 'DATOS DEL PRECIO TEMPORADA',
  'id' => 'temporada',
  'size' => 'modal-md',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalContent'></div>";

Modal::end();

$this->title = Yii::$app->name . ' | Precio Temporadas';
$this->params['breadcrumbs'][] = 'Gesti贸n de Precio de Temporadas';

?>
<div class="paradas-index">
    <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Gesti贸n de Precio de Temporadas</div>
        <div class="panel-body gs1">
            <div class="row">
                <div align="center" class="col-lg-1">          
                  <?= Html::button('Nuevo<br>Precio', [
                    'value' => Yii::$app->urlManager->createUrl('/precios-temporadas/create'),
                    'class' => 'btn btn-full',
                    'id' => 'BtnModalId',
                    'data-toggle' => 'modal',
                    'data-target' => '#temporada',

                  ]) ?> 
                </div>

                <div class="col-lg-11 col-md-11 col-xs-12">
                    <div class="panel panel-default busqueda">
                        <div class="panel-body body-busqueda">
                            <div class="subtitulo-reserva">Buscar Precio Temporada</div><br>
                            <?php Pjax::begin(); ?>
                            <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                            <div class="subtitulo-reserva">Listado de Precio de Temporadas</div><br><br>

                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                //'filterModel' => $searchModel,
                                'columns' => [
                                    [
                                        'header' => 'N掳',
                                        'class' => 'kartik\grid\SerialColumn'
                                    ],

                                    [
                                      'attribute' => 'fecha_inicio',
                                      'format' => ['date', 'php:d/m/Y'],
                                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'],
                                      'headerOptions' => ['style' => 'text-align:center !important']
                                    ],

                                    [
                                      'attribute' => 'hora_inicio',
                                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'],
                                      'headerOptions' => ['style' => 'text-align:center !important']
                                    ],

                                    [
                                      'attribute' => 'fecha_fin',
                                      'format' => ['date', 'php:d/m/Y'],
                                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'],
                                      'headerOptions' => ['style' => 'text-align:center !important']
                                    ],

                                    [
                                      'attribute' => 'hora_fin',
                                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'],
                                      'headerOptions' => ['style' => 'text-align:center !important']
                                    ],
                                    [
                                      'attribute' => 'precio',
                                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'],
                                      'headerOptions' => ['style' => 'text-align:center !important']
                                    ],

                                    [
                                        'attribute' => 'descripcion',
                                        'contentOptions' => ['style' => 'vertical-align:middle'],
                                        'headerOptions' => ['style' => 'text-align:center !important']
                                    ],
                                    [
                                      'attribute' => 'status',
                                      'format' => 'raw',
                                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'],
                                      'headerOptions' => ['style' => 'text-align:center !important'],
                                      'value' => function ($model) {
                                          if ($model->status === 'inactivo') {
                                              return Html::tag('label', Html::encode('Inactivo'), ['class' => 'label label-danger lbl']);
                                          }
                                          if ($model->status === 'activo') {
                                              return Html::tag('label', Html::encode('Activo'), ['class' => 'label label-primary lbl']);
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
                                        'template' => "{view} &nbsp; {update} &nbsp;{delete} &nbsp;{status}",
                                        'controller' => 'precios-temporadas',
                                        'buttons' => [
                                            'view' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="glyphicon glyphicon-search"></span>',
                                                    '#',
                                                    [
                                                    'class' => 'btn-view',
                                                    'id' => 'view',
                                                    'title' => Yii::t('app', 'Consultar'),
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#temporada',
                                                    'data-url' => Url::to(['view', 'id' => $model->id]),
                                                    'data-pjax' => '0',
                                                ]
                                                );
                                            },
                                            'update' => function ($url, $model, $key) {
                                                return Html::a(
                                                    '<span class="glyphicon glyphicon-edit"></span>',
                                                    '#',
                                                    [
                                                    'class' => 'btn-update',
                                                    'id' => 'update',
                                                    'title' => Yii::t('app', 'Modificar'),
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#temporada',
                                                    'data-url' => Url::to(['update', 'id' => $model->id]),
                                                    'data-pjax' => '0',
                                                ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(
                                                    '<span class="glyphicon glyphicon-trash"></span>',
                                                    '#',
                                                    [
                                                    'class' => 'btn-delete',
                                                    'title' => 'Eliminar Precio',
                                                    'aria-label' => 'Eliminar PRecio',
                                                    'onclick' => "yii.confirm('" . Yii::t(
                                                        'app',
                                                        '驴Estas seguro de eliminar este elemento?'
                                                    ) . "',
                                                    function(){ $.ajax('$url', {type: 'POST'}).done(function(data) { $.pjax.reload('#items-in-event', {timeout : false}).done(function () { $.pjax.reload('#event-invoice-details', {timeout : false}).done(function () { $.pjax.reload('#main-alert-widget', {timeout : false}); }); }); }); }
                                                    );
                                                    return false;",
                                                ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(
                                                    '<span class="glyphicon glyphicon-trash"></span>',
                                                    '#',
                                                    [
                                                    'class' => 'btn-delete',
                                                    'title' => 'Eliminar Precio',
                                                    'aria-label' => 'Eliminar PRecio',
                                                    'onclick' => "yii.confirm('" . Yii::t(
                                                        'app',
                                                        '¿Estas seguro de eliminar este elemento?'
                                                    ) . "',
                                                    function(){ $.ajax('$url', {type: 'POST'}).done(function(data) { $.pjax.reload('#items-in-event', {timeout : false}).done(function () { $.pjax.reload('#event-invoice-details', {timeout : false}).done(function () { $.pjax.reload('#main-alert-widget', {timeout : false}); }); }); }); }
                                                    );
                                                    return false;",
                                                ]
                                                );
                                            },
                                            'status' => function ($url, $model) {
                                              return Html::a(
                                                  '<span class="glyphicon glyphicon-check"></span>',
                                                  '#',
                                                  [
                                                  'class' => 'btn-view',
                                                  'title' => 'Activar/Desactivar Precio',
                                                  'aria-label' => 'Activar/Desactivar Precio',
                                                  'onclick' => "yii.confirm('" . Yii::t(
                                                      'app',
                                                      '¿Estas seguro de activar/desactivar este elemento?'
                                                  ) . "',
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
      $('#temporada').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });

    $(document).on('click', '#update', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContent').html(data);
          $('#temporada').modal();
        }
      );
    }));

    $(document).on('click', '#view', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContent').html(data);
          $('#temporada').modal();
        }
      );
    }));
  ");
?>
