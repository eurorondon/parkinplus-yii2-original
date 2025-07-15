<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use kartik\select2\Select2;
use common\models\Clientes;
use common\models\Reservas;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;

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
  'header' => 'REGISTRAR CUPÓN DE DESCUENTO',
  'id' => 'descuento',
  'size' => 'modal-md',

]);

echo "<div id='modalD'></div>";

Modal::end();

Modal::begin([
  'header' => 'CAMBIAR ESTADO',
  'id' => 'cambiar_estado',
  'size' => 'modal-md',

]);

echo "<div id='modalCE'></div>";

Modal::end();

Modal::begin([
  'header' => 'EVALUACIÓN DEL SERVICIO PRESTADO',
  'id' => 'valoracion',
  'size' => 'modal-md',

]);

echo "<div id='modalO'></div>";

Modal::end();

Modal::begin([
  'header' => 'CONFIRMACIÓN DE RECEPCIÓN - COCHE CLIENTE',
  'id' => 'checkin',
  'size' => 'modal-md',

]);

echo "<div id='modalCheckin'></div>";

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
  'header' => 'IMPRIMIR TICKET / SOBRE',
  'id' => 'ticket',
  'size' => 'modal-md',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]);

echo "<div id='modalTicket'></div>";

Modal::end();

Modal::begin([
  'header' => 'REENVIAR RESERVA',
  'id' => 'envia_reserva',
  'size' => 'modal-md',

]);

echo "<div id='modalEnvio'></div>";

Modal::end();


$ajaxUrl = Url::to(['reservas/total']);

if($tipo_afiliado == 0) {

    $totales = count(Reservas::find()->where(['!=','estatus','10'])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all());

    $canceladas = Reservas::find()->where(['estatus' => '0'])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_can = count($canceladas);

    $pendientes = Reservas::find()->where(['estatus' => '1'])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_pen = count($pendientes);

    $finalizadas = Reservas::find()->where(['estatus' => '2'])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_fin = count($finalizadas);

    $activas = Reservas::find()->where(['estatus' => '3'])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_act = count($activas);

    $rezagadas = Reservas::find()->where(['estatus' => '4'])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_res = count($rezagadas);


} else {
    $totales = count(Reservas::find()->where(['!=','estatus','10'])->andWhere(['medio_reserva' => 4])->where(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all());

    $canceladas = Reservas::find()->where(['estatus' => '0'])->andWhere(['medio_reserva' => 4])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_can = count($canceladas);

    $pendientes = Reservas::find()->where(['estatus' => '1'])->andWhere(['medio_reserva' => 4])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_pen = count($pendientes);

    $finalizadas = Reservas::find()->where(['estatus' => '2'])->andWhere(['medio_reserva' => 4])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_fin = count($finalizadas);

    $activas = Reservas::find()->where(['estatus' => '3'])->andWhere(['medio_reserva' => 4])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_act = count($activas);

    $rezagadas = Reservas::find()->where(['estatus' => '4'])->andWhere(['medio_reserva' => 4])->andWhere(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 15 days")), date('Y-m-d')])->all();
    $nro_res = count($rezagadas);
}

$meses = ['01' => "ENERO", '02' => "FEBRERO", '03' => "MARZO", '04' => "ABRIL", '05' => "MAYO", '06' => "JUNIO", '07' => "JULIO", '08' => "AGOSTO", '09' => "SEPTIEMBRE", '10' => "OCTUBRE", '11' => "NOVIEMBRE", '12' => "DICIEMBRE"];

$this->title = Yii::$app->name.' | Reservas';
$this->params['breadcrumbs'][] = 'Gestión de Reservas';

?>
<div class="reservas-index">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Gestión de Reservas</div>
    <!--MODIF-->
    <?php if (!empty($reservasConErrores)): ?>
      <button class="btn btn-warning  " type="button" data-toggle="collapse" data-target="#erroresReservas" aria-expanded="false" aria-controls="erroresReservas">
        ⚠️ Mostrar reservas con posibles errores
      </button>
      
         <div class="collapse panel-body gs1 pad-mob" id="erroresReservas">
        <div class="card card-body">
          <h4>⚠️ Reservas con posibles errores en servicios extra</h4>
          <table class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>Estatus</th>
                <th>Nro Reserva</th>
                <th>Costo Servicios Extra</th>
                <th>Total Servicios</th>
                <th>Fecha Entrada</th>
                <th>Fecha Salida</th>
                <th>Creado</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $hoy = date('Y-m-d');
              foreach ($reservasConErrores as $res):
                $yaPaso = $res['fecha_salida'] < $hoy;
                $class = $yaPaso ? 'text-muted' : '';
                $estatus = $yaPaso
                  ? '<span class="text-success">FINALIZADA ✅</span>'
                  : '<span class="text-warning font-weight-bold">VIGENTE ⚠️</span>';
              ?>
                <tr class="<?= $class ?>">
                  <td><?= $estatus ?></td>
                  <td><?= $res['nro_reserva'] ?></td>
                  <td><?= Yii::$app->formatter->asCurrency($res['costo_servicios_extra'], 'EUR') ?></td>
                  <td><?= $res['total_servicios'] ?></td>
                  <td><?= Yii::$app->formatter->asDate($res['fecha_entrada'], 'php:d-m-Y') ?></td>
                  <td><?= Yii::$app->formatter->asDate($res['fecha_salida'], 'php:d-m-Y') ?></td>
                  <td><?= Yii::$app->formatter->asDatetime($res['created_at'], 'php:d-m-Y H:i') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php else: ?>
      <!--<div class="alert alert-success">-->
      <!--  No se encontraron reservas con errores en los últimos 30 días.-->
      <!--</div>-->
    <?php endif; ?>

    <?php if (!empty($actualizadasFront)): ?>
      <div class="alert alert-info mt-2">
        <p>Reservas actualizadas desde la web:</p>
        <ul class="mb-0">
          <?php foreach ($actualizadasFront as $res): ?>
            <li>
              ID <?= $res->id ?> - Nº <?= $res->nro_reserva ?>
              <?= Html::a('Marcar revisada', ['reservas/marcar-actualizada', 'id' => $res->id], ['class' => 'btn btn-xs btn-primary']) ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
      
      <!--VISUALIZAR RESERVAS QUE NO SE ACTUALIZARON-->
        <?php if (!empty($pendientesSinActualizar)): ?>
        <div class="alert alert-warning mt-4">
          <h4>⚠️ Reservas vencidas sin estatus actualizado</h4>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nro Reserva</th>
                <th>Fecha Salida</th>
                <th>Estatus</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pendientesSinActualizar as $res): ?>
                <tr>
                  <td><?= $res['id'] ?></td>
                  <td><?= $res['nro_reserva'] ?></td>
                  <td><?= Yii::$app->formatter->asDate($res['fecha_salida']) ?></td>
                  <?php
                  $estatusNombre = 'Otro';
                  if ($res['estatus'] == '1') {
                    $estatusNombre = 'Pendiente';
                  } elseif ($res['estatus'] == '3') {
                    $estatusNombre = 'Activa';
                  }
                  ?>
                  <td><?= $estatusNombre ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
      <!--  <div class="alert alert-success mt-4">-->
      <!--    ✅ Todas las reservas vencidas están correctamente actualizadas.-->
      <!--  </div>-->
      <!--<?php endif; ?>-->
      <!--END RESERVAS QUE NO SE-->

   
    
    <!--END-->
    <div class="panel-body gs1 pad-mob">
      <div class="row">
        
        <div class="col-lg-2 col-xs-6">
          <?= Html::a('Planning de<br>Reservas', ['/reservas/planning'], [
            'class' => 'btn btn-full',
            'style' => ['margin-right' => '50px'],
          ]) ?>
        </div>
        <div class="col-lg-2 col-xs-6">
          <?= Html::button('Agregar<br>Reserva', [                        
            'value' => Yii::$app->urlManager->createUrl('/reservas/fechas'),
            'class' => 'btn btn-full',
            'style' => ['margin-right'=> '50px'],
            'id' => 'BtnModalId',
            'data-toggle'=> 'modal',
            'data-target'=> '#fecha_reserva',
          ]) ?>
        </div>
        <div class="col-lg-2 col-xs-6">
          <?= Html::button('Registrar<br>Descuento', [                        
            'value' => Yii::$app->urlManager->createUrl('/reservas/descuento'),
            'class' => 'btn btn-full',
            'style' => ['margin-right' => '50px'],
            'id' => 'BtnModalD',
            'data-toggle'=> 'modal',
            'data-target'=> '#descuento',
          ]) ?>
        </div>
        <div class="col-lg-2 col-xs-6">
          <?= Html::button('Estado de<br>Reserva', [                        
            'value' => Yii::$app->urlManager->createUrl('/reservas/estatus'),
            'class' => 'btn btn-full',
            'style' => ['margin-right' => '50px'],
            'id' => 'BtnModalCE',
            'data-toggle'=> 'modal',
            'data-target'=> '#cambiar_estado',
          ]) ?> 
        </div>
        <div class="col-lg-2 col-xs-6">
          <?= Html::button('Dános tu<br>Opinión', [                        
            'value' => Yii::$app->urlManager->createUrl('/reservas/valoracion'),
            'class' => 'btn btn-full',
            'style' => ['margin-right' => '50px'],
            'id' => 'BtnModalO',
            'data-toggle'=> 'modal',
            'data-target'=> '#valoracion',
          ]) ?> 
        </div>
        <div class="col-lg-2 col-xs-6">
          <?= Html::button('Check<br>In', [                        
            'value' => Yii::$app->urlManager->createUrl('/reservas/checkin'),
            'class' => 'btn btn-full',
            'style' => ['margin-right' => '50px'],
            'id' => 'BtnModalCheck',
            'data-toggle'=> 'modal',
            'data-target'=> '#checkin',
          ]) ?>                                       
        </div>

        <div class="col-lg-12 col-md-12 col-xs-12">
          <div class="panel panel-default busqueda" style="margin: 0">
            <div class="panel-body body-busqueda">
              <div class="row col-lg-3" style="margin-top: -20px; margin-bottom: 15px">
                <?= Select2::widget([
                    'name' => 'mes',
                    'data' => $meses,
                    'options' => ['class' => 'chbox', 'id' => 'mes', 'placeholder' => 'SELECCIONE MES'],
                ]); ?>        
              </div>
              <div class="col-lg-2" style="margin-top: -20px; margin-bottom: 15px">
                <?= Select2::widget([
                    'name' => 'ayo',
                    'data' => $anios,
                    'options' => ['class' => 'chbox', 'id' => 'ayo'],
                ]); ?>                 
              </div>
              <div class="col-lg-3"></div>
              <div class="col-lg-4">
                <div class="restotal" id="restotal">CANTIDAD DE RESERVAS EN LOS ULTIMO 15 DIAS: <?= $totales ?></div>
              </div>

              <div class="col-lg-12"><hr class="linea" style="margin: 10px -15px"></div>
      
              <div class="col-lg-12">   
              <input type="hidden" id="dir" value="<?= $ajaxUrl ?>">

                <div class="col-lg-2">
                  <div class="separa">
                    <div class="lbl-primary" id="pendientes" align="center"><?= $nro_pen ?></div>
                    <div align="center">
                      <label class="label label-primary lbel" id="pend">Pendientes</label>
                    </div>  
                  </div>                
                </div>
                <div class="col-lg-2">
                  <div class="separa">
                    <div class="lbl-success" id="activas" align="center"><?= $nro_act ?></div>
                    <div align="center">
                      <label class="label label-success lbel" id="act">Activas</label>
                    </div>
                  </div>
                </div>
                <div class="col-lg-2">
                  <div class="separa">
                    <div class="lbl-default" id="rezagadas" align="center"><?= $nro_res ?></div>
                    <div align="center">
                      <label class="label label-warning lbel" id="res">Rezagadas</label>
                    </div>
                  </div>
                </div>                
                <div class="col-lg-3">
                  <div class="separa">
                    <div class="lbl-default" id="finalizadas" align="center"><?= $nro_fin ?></div>
                    <div align="center">
                      <label class="label label-default lbel" id="fin">Finalizadas</label>
                    </div>
                  </div>                  
                </div>
                <div class="col-lg-3">
                  <div class="sinsepara">
                    <div class="lbl-danger" id="canceladas" align="center"><?= $nro_can ?></div>
                    <div align="center">
                      <label class="label label-danger lbel" id="canc">Canceladas</label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-12"><hr class="linea" style="margin: 10px -15px 60px -15px"></div>

              <?php Pjax::begin(); ?>
              <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

              <div class="subtitulo-reserva">Listado de Reservas</div><br><br>

                <?= GridView::widget([
                  'dataProvider' => $dataProvider,
                  //'filterModel' => $searchModel,
                  'responsive' => true,
                  'condensed' => true,
                  'responsiveWrap' => false,
                  'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                  'headerRowOptions' => ['class' => 'kartik-sheet-style', 'style'=> ['text-transform' => 'uppercase', 'font-size' => '0.95em']],
                  'footerRowOptions' => ['style' => 'text-align: right'],
                  'filterRowOptions' => ['class' => 'kartik-sheet-style'], 
                  //'showPageSummary' => true,
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
                      'attribute' => 'medio_reserva',
                      'format' => 'raw',
                      'width' => '50px', 
                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'], 
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'value' => function($model) {
                        if ($model->medio_reserva === 1) {
                          return Html::tag('span', '', ['style' => ['color' => '#961007'], 'class' => 'glyphicon glyphicon-phone-alt']);
                        }   
                        if ($model->medio_reserva === 2) {
                          return Html::tag('span', '', ['style' => ['color' => '#f0ad4e'], 'class' => 'glyphicon glyphicon-tags']);
                        }
                        if ($model->medio_reserva === 3) {
                          return Html::tag('span', '', ['style' => ['color' => '#398439'], 'class' => 'glyphicon glyphicon-globe']);
                        }
                        if ($model->medio_reserva === 4) {
                          return Html::tag('span', '', ['style' => ['color' => '#286090'], 'class' => 'glyphicon glyphicon-education']);
                        } 
                      },                
                    ],                     

                    [
                      'attribute' => 'nro_reserva',  
                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'], 
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '100px',
                    ],               

                    [
                      'attribute' => 'created_at',
                      'format' => ['date', 'php:d/m/Y H:i'], 
                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'], 
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '250px',
                    ],

                    [
                      'attribute' => 'fecha_entrada', 
                      'format' => ['date', 'php:d/m/Y'],
                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'], 
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '100px',
                    ],

                    [
                      'attribute' => 'fecha_salida',
                      'format' => ['date', 'php:d/m/Y'],
                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'],  
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '100px',
                    ], 

                    [
                      'contentOptions' => ['style' => 'width:400px; white-space: normal; text-transform:uppercase; vertical-align:middle'],
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
                      'width' => '500px',
                    ], 

                    [
                      'attribute' => 'estatus',
                      'format' => 'raw', 
                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'], 
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'value' => function($model) {
                        if ($model->estatus === 0) {
                          return Html::tag('label', Html::encode('Cancelada'), ['class' => 'label label-danger lbl']);
                        }
                        if ($model->estatus === 1) {
                          return Html::tag('label', Html::encode('Pendiente'), ['class' => 'label label-primary lbl']);
                        }   
                        if ($model->estatus === 2) {
                          return Html::tag('label', Html::encode('Finalizada'), ['class' => 'label label-default lbl']);
                        }
                        if ($model->estatus === 3) {
                          return Html::tag('label', Html::encode('Activa'), ['class' => 'label label-success lbl']);
                        } 
                        if ($model->estatus === 4) {
                          return Html::tag('label', Html::encode('Rezagada'), ['class' => 'label label-warning lbl']);
                        }                         
                      },                
                    ],              

                    [
                      'attribute' => 'factura',
                      'label' => 'Factura', 
                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'], 
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '50px',
                      'value' => function($model) {
                        if ($model->factura === 0) {
                          return ('NO');
                        }
                        if ($model->factura === 1) {
                          return ('SI');
                        }                                               
                      },                  
                    ],

                    [
                      'attribute' => 'impreso', 
                      'format' => 'raw', 
                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'], 
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '50px',
                      'value' => function($model) {
                        if ($model->impreso === 'NO') {
                          return Html::tag('label', Html::encode('NO'), ['class' => 'label label-danger lbl']);
                        }
                        if ($model->impreso === 'SI') {
                          return Html::tag('label', Html::encode('SI'), ['class' => 'label label-success lbl']);
                        }                                                                                     
                      },                      
                    ],
                    [
                      'attribute' => 'canceled_by', 
                      'format' => 'raw', 
                      'contentOptions' => ['style' => 'text-align:center; vertical-align:middle'], 
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '50px',
                      'value' => function($model) {
                        if ($model->canceled_by === 0) {
                          return 'N/D';
                        }else {
                          return Html::tag('label', Html::encode(strtoupper($model->canceledBy->username)), ['class' => 'label label-danger lbl']);
                        }                                                                                     
                      },                      
                    ],                                     


      /*



                    [
                      'attribute' => 'terminal_entrada',
                      'contentOptions' => ['style' => 'text-align:center'],  
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '200px',
                    ],

                    [
                      'pageSummary' => 'TOTAL',
                      'attribute' => 'terminal_salida',
                      'contentOptions' => ['style' => 'text-align:center'],  
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '200px',
                    ], 
                                  

                    [
                      'attribute' => 'monto_total',  
                      'contentOptions' => ['style' => 'text-align:right'],
                      'headerOptions' => ['style' => 'text-align:center !important'],
                      'width' => '150px',
                      'format' => ['currency'],
                      'pageSummary' => true,
                    ], */                                        

                        [ 
                          'class' => 'kartik\grid\ActionColumn', 
                          'header' => '',
                          'headerOptions' => [
                            'class' => 'text-center'
                          ], 
                          'contentOptions' => [
                            'class' => 'text-center icon_actions'
                          ], 
                          'template' => "{view} &nbsp; {update} &nbsp; {delete} &nbsp;&nbsp;&nbsp; {ticket} &nbsp;&nbsp;&nbsp; {enviar}", 
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
                            'update' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-edit"></span>',
                                    Yii::$app->urlManager->createUrl(['reservas/update', 'id' => $model->id]),
                                    ['class' => 'btn-update', 'title' => 'Modificar']
                                );
                            },
                            'delete' => function ($url, $model) { 
                              
                              return Yii::$app->user->id == 7 ? Html::a(
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
                              ) : ''; 
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
                            'enviar' => function ($url, $model, $key) {
                              return Html::a('<span class="glyphicon glyphicon-send"></span>', '#', [
                                'class' => 'btn-envio',
                                'id' => 'enviar-reserva',
                                'title' => Yii::t('app', 'Reenviar Reserva'),
                                'data-toggle' => 'modal',
                                'data-target' => '#envia_reserva',
                                'data-url' => Url::to(['envia-reserva', 'id' => $model->id]),
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

    $('#BtnModalD').click(function(e){    
      e.preventDefault();
      $('#descuento').modal('show')
      .find('#modalD')
      .load($(this).attr('value'));
      return false;
    });   

    $('#BtnModalCE').click(function(e){    
      e.preventDefault();
      $('#cambiar_estado').modal('show')
      .find('#modalCE')
      .load($(this).attr('value'));
      return false;
    }); 

    $('#BtnModalO').click(function(e){    
      e.preventDefault();
      $('#valoracion').modal('show')
      .find('#modalO')
      .load($(this).attr('value'));
      return false;
    });          

    $('#BtnModalCheck').click(function(e){    
      e.preventDefault();
      $('#checkin').modal('show')
      .find('#modalCheckin')
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

    $(document).on('click', '#enviar-reserva', (function() {
      $.get(
        $(this).data('url'),
        function (data) {
          $('#modalEnvio').html(data);
          $('#envia_reserva').modal();
        }
      );
    }));        

    $('.chbox').on('change', function(e) {
      dir = $('#dir').val();
      mes = $('#mes').val();
      ayo = $('#ayo').val();
      e.preventDefault();
      $.ajax({
        type:'POST',
        url: dir,
        data: { mes: mes, ayo: ayo },
        success: function(data) {
          dato = data.split('/')
          $('#restotal').html('CANTIDAD DE RESERVAS : '+ dato[0]);
          $('#pendientes').html(dato[1]);
          $('#activas').html(dato[2]);
          $('#rezagadas').html(dato[3]);          
          $('#finalizadas').html(dato[4]);
          $('#canceladas').html(dato[5]);
        }            
      });
    }); 

    $('#canc').click(function(e){    
      $('#reservassearch-estatus').val(0);
      $('#buscar').click();
    }); 
    $('#pend').click(function(e){    
      $('#reservassearch-estatus').val(1);
      $('#buscar').click();
    });         
    $('#fin').click(function(e){    
      $('#reservassearch-estatus').val(2);
      $('#buscar').click();
    });

    $('#act').click(function(e){    
      $('#reservassearch-estatus').val(3);
      $('#buscar').click();
    }); 

    $('#res').click(function(e){    
      $('#reservassearch-estatus').val(4);
      $('#buscar').click();
    });              

  ");
?>
