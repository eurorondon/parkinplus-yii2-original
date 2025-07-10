<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $searchModel common\models\ListasPreciosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

Modal::begin([
  'header' => 'PLANES DE SERVICIO - NUEVO PLAN',
  'id' => 'crear-plan',
  'size' => 'modal-lg',

]);

echo "<div id='modalContent'></div>";

Modal::end();

Modal::begin([
  'header' => 'DATOS DEL PLAN',
  'id' => 'plan',
  'size' => 'modal-lg',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalContenedor'></div>";

Modal::end();

Modal::begin([
  'header' => '¿ QUÉ ES UN PLAN ?',
  'id' => 'ayuda-plan',
  'size' => 'modal-md',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalCont'>
<b>Plan de Servicio :</b><div style='display:inline' align='justify'> Es un registro de precios que dede ser asociado al servicio de aparcamiento.</div><br><br>
<div align='justify'>El Plan de Servicio debe tener un nombre, una cuota por día, que será el monto que se le adicionará al costo del servicio pasado los 30 días y el registro de precios de los primeros 30 días del mes.</div><br>
<div align='justify'>Si el Cliente requiere por ejemplo: 35 días de parking el sistema tomará el precio registrado por 30 días y los 5 días restantes los multiplicará por la cuota diaria, sumados ambos montos el sistema reflejará el costo del servicio.</div><br>
<div align='justify'>Si desea agregar un nuevo plan registre los datos del mismo y recuerde, si el estado es activo, el sistema pasará de forma automática cualquier otro plan activo a inactivo. Un plan podrá ser eliminado solo si se encuentra con estado inactivo.</div>
</div>";

Modal::end();

$this->title = Yii::$app->name.' | Planes';
$this->params['breadcrumbs'][] = 'Planes de Servicio';
?>
<div class="listas-precios-index">

  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Planes de Servicio</div>
    <div class="panel-body gs1">
      <div class="row">

        <div align="center" class="col-lg-1">        
          <?= Html::button('Agregar<br>Plan', [                        
            'value' => Yii::$app->urlManager->createUrl('/listas-precios/create'),
            'class' => 'btn btn-full',
            'id' => 'BtnModalId',
            'data-toggle'=> 'modal',
            'data-target'=> '#crear-plan',

          ]) ?> 

          <?= Html::button('Qué es un<br>Plan ?', [                        
            //'value' => Yii::$app->urlManager->createUrl('/listas-precios/create'),
            'class' => 'btn btn-full',
            'id' => 'modalPlan',
            'data-toggle'=> 'modal',
            'data-target'=> '#ayuda-plan',

          ]) ?>          
        </div>

        <div class="col-lg-11 col-md-11 col-xs-12">
          <div class="panel panel-default busqueda">
            <div class="panel-body body-busqueda">
              <div class="subtitulo-reserva">Buscar Plan de Servicio</div><br>
              <?php Pjax::begin(); ?>
              <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

              <div class="subtitulo-reserva">Listado de Planes</div><br><br>

              <?= GridView::widget([
                'dataProvider' => $dataProvider,
                  //'filterModel' => $searchModel,
                'columns' => [
                  [                      
                    'header' => 'N°',
                    'class' => 'kartik\grid\SerialColumn'
                  ],

                  [
                    'attribute' => 'nombre',
                    'contentOptions' => ['style' => 'width:350px; white-space: normal; text-align:left'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                  ], 

                  [
                    'attribute' => 'agregado',  
                    'contentOptions' => ['style' => 'text-align:right'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                    'width' => '150px',
                    'format' => ['currency'],
                    'pageSummary' => true,
                  ],        

                  [
                    'contentOptions' => ['style' => 'width:230px; white-space: normal;'],
                    'headerOptions' => ['style' => 'text-align:center !important'],
                    'attribute' => 'estatus',
                    'value' => function($model) {
                      if ($model->estatus === 0) {
                        return ('Inactivo');
                      }
                      if ($model->estatus === 1) {
                        return ('Activo');
                      }                                               
                    },
                    'format' => 'text',
                    'filter' => Select2::widget([
                      'model' => $searchModel,
                      'attribute' => 'estatus',
                      'data' => ['0'=>'Inactivo', '1'=>'Activo'],
                      'options' => [
                        'placeholder' => 'Seleccione',
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
                    'controller' => 'listas-precios', 
                    'buttons' => [ 
                      'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-search"></span>', '#', [
                          'class' => 'btn-view',
                          'id' => 'view',
                          'title' => Yii::t('app', 'Consultar'),
                          'data-toggle' => 'modal',
                          'data-target' => '#plan',
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
                          'data-target' => '#plan',
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
</div>

<?php 
  $this->registerJs(" 
    $('#BtnModalId').click(function(e){    
      e.preventDefault();
      $('#crear-plan').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });    
    $(document).on('click', '#update', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalContenedor').html(data);
          $('#plan').modal();
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

    $(document).on('click', '#modalPlan', (function() {

          $('#ayuda-plan').modal('show');

    }));    
  ");
?>
