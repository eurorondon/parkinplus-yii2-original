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
  'header' => 'DATOS DE PARADA',
  'id' => 'parada',
  'size' => 'modal-md',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalContent'></div>";

Modal::end();

$this->title = Yii::$app->name.' | Paradas';
$this->params['breadcrumbs'][] = 'Gestión de Paradas';

?>
<div class="paradas-index">
    <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Gestión de Paradas</div>
        <div class="panel-body gs1">
            <div class="row">
                <div align="center" class="col-lg-1">          
                  <?= Html::button('Nueva<br>Parada', [                        
                    'value' => Yii::$app->urlManager->createUrl('/paradas/create'),
                    'class' => 'btn btn-full',
                    'id' => 'BtnModalId',
                    'data-toggle'=> 'modal',
                    'data-target'=> '#parada',

                  ]) ?> 
                </div>

                <div class="col-lg-11 col-md-11 col-xs-12">
                    <div class="panel panel-default busqueda">
                        <div class="panel-body body-busqueda">
                            <div class="subtitulo-reserva">Buscar Paradas</div><br>
                            <?php Pjax::begin(); ?>
                            <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                            <div class="subtitulo-reserva">Listado de Paradas</div><br><br>

                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                //'filterModel' => $searchModel,
                                'columns' => [
                                    [                      
                                        'header' => 'N°',
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
                                        'attribute' => 'descripcion',  
                                        'contentOptions' => ['style' => 'vertical-align:middle'], 
                                        'headerOptions' => ['style' => 'text-align:center !important']
                                    ],
                                    [
                                      'attribute' => 'status',  
                                      'contentOptions' => ['style' => 'vertical-align:middle'], 
                                      'headerOptions' => ['style' => 'text-align:center !important']
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
                                        'controller' => 'paradas', 
                                        'buttons' => [ 
                                            'view' => function ($url, $model, $key) {
                                                return Html::a('<span class="glyphicon glyphicon-search"></span>', '#', 
                                                [
                                                    'class' => 'btn-view',
                                                    'id' => 'view',
                                                    'title' => Yii::t('app', 'Consultar'),
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#parada',
                                                    'data-url' => Url::to(['view', 'id' => $model->id]),
                                                    'data-pjax' => '0',
                                                ]);
                                            },                       
                                            'update' => function ($url, $model, $key) {
                                                return Html::a('<span class="glyphicon glyphicon-edit"></span>', '#', 
                                                [
                                                    'class' => 'btn-update',
                                                    'id' => 'update',
                                                    'title' => Yii::t('app', 'Modificar'),
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#parada',
                                                    'data-url' => Url::to(['update', 'id' => $model->id]),
                                                    'data-pjax' => '0',
                                                ]);
                                            }, 
                                            'delete' => function ($url, $model) { 
                                                return Html::a(
                                                    '<span class="glyphicon glyphicon-trash"></span>', '#', 
                                                [ 
                                                    'class' => 'btn-delete',
                                                    'title' => 'Eliminar Parada',
                                                    'aria-label' => 'Eliminar Parada', 
                                                    'onclick' => "yii.confirm('" . Yii::t(
                                                    'app', '¿Estas seguro de eliminar este elemento?') . "',
                                                    function(){ $.ajax('$url', {type: 'POST'}).done(function(data) { $.pjax.reload('#items-in-event', {timeout : false}).done(function () { $.pjax.reload('#event-invoice-details', {timeout : false}).done(function () { $.pjax.reload('#main-alert-widget', {timeout : false}); }); }); }); }
                                                    );
                                                    return false;",
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
      $('#parada').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });

    $(document).on('click', '#update', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContent').html(data);
          $('#parada').modal();
        }
      );
    }));

    $(document).on('click', '#view', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContent').html(data);
          $('#parada').modal();
        }
      );
    }));
  ");
?>
