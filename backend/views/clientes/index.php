<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ClientesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin([
  'header' => 'DATOS DEL CLIENTE',
  'id' => 'cliente',
  'size' => 'modal-md',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalContent'></div>";

Modal::end();


$this->title = Yii::$app->name.' | Clientes';
$this->params['breadcrumbs'][] = 'Gestión de Clientes';

?>
<div class="clientes-index">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Gestión de Clientes</div>
    <div class="panel-body gs1">
      <div class="row">

        <div align="center" class="col-lg-1">          
          <?= Html::button('Agregar<br>Cliente', [                        
            'value' => Yii::$app->urlManager->createUrl('/clientes/create'),
            'class' => 'btn btn-full',
            'id' => 'BtnModalId',
            'data-toggle'=> 'modal',
            'data-target'=> '#cliente',

          ]) ?> 
        </div>

        <div class="col-lg-11 col-md-11 col-xs-12">
          <div class="panel panel-default busqueda">
            <div class="panel-body body-busqueda">
              <div class="subtitulo-reserva">Buscar Cliente</div><br>
              <?php Pjax::begin(); ?>
              <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

              <div class="subtitulo-reserva">Listado de Clientes</div><br><br>

              <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'columns' => [
                  [                      
                    'header' => 'N°',
                    'class' => 'kartik\grid\SerialColumn'
                  ],

                  [
                    'attribute' => 'nombre_completo',
                    'contentOptions' => ['style' => 'text-transform: uppercase; width:350px; white-space: normal; text-align:left'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                  ],

                  [
                    'attribute' => 'correo',
                    'contentOptions' => ['style' => 'width:200px; white-space: normal; text-align:left'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                  ],

                  [
                    'attribute' => 'movil',
                    'contentOptions' => ['style' => 'width:200px; white-space: normal; text-align:left'],
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
                    'controller' => 'clientes', 
                    'buttons' => [ 
                      'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-search"></span>', '#', [
                          'class' => 'btn-view',
                          'id' => 'view',
                          'title' => Yii::t('app', 'Consultar'),
                          'data-toggle' => 'modal',
                          'data-target' => '#cliente',
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
                          'data-target' => '#cliente',
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
      $('#cliente').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });

    $(document).on('click', '#update', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContent').html(data);
          $('#cliente').modal();
        }
      );
    }));

    $(document).on('click', '#view', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContent').html(data);
          $('#cliente').modal();
        }
      );
    }));
  ");
?>
