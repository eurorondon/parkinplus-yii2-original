<?php

use yii\helpers\Html;
use common\models\Servicios;
use common\models\FacturasReserva;
use common\models\ReservasServicios;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Reservas */

$this->title = Yii::$app->name.' | Información de Reserva';
$this->params['breadcrumbs'][] = ['label' => 'Mis Reservas', 'url' => ['reservas']];
$this->params['breadcrumbs'][] = 'Información de Reserva';
\yii\web\YiiAsset::register($this);


if ($model->estatus === 0) {
    $estatus = 'Cancelada';
} 
if ($model->estatus === 1) {
    $estatus = 'Pendiente';
}
if ($model->estatus === 2) {
    $estatus = 'Finalizada';
}
if ($model->estatus === 3) {
    $estatus = 'Activa';
}

Modal::begin([
  'header' => 'Confirmación',
  'id' => 'generar_factura',
  'size' => 'modal-sm',

]); ?>

<input type="hidden" id="id_reserva" value="<?= $model->id; ?>">

<div id="modalContent"></div>

<?php

Modal::end();

$ser = ReservasServicios::find()->where(['id_reserva'=> $model->nro_reserva])->orderBy(['id_servicio' => SORT_DESC])->all();

$fecha1= new DateTime($model->fecha_entrada);
$fecha2= new DateTime($model->fecha_salida);
$dias = $fecha1->diff($fecha2);

$fecha_entrada = Yii::$app->formatter->asDate($model->fecha_entrada);
$fecha_salida = Yii::$app->formatter->asDate($model->fecha_salida);

if ($model->factura_equipaje === 0) {
  $factura_equipaje = 'NO';
} else {
  $factura_equipaje = 'SI';
}

if ($model->factura === 0) {
  $nota = 'UD. NO REQUIERE FACTURA';
}

?>

<div class="reservas-view">

  <div class="col-lg-12">
    <div class="title-top" style="margin-top: 0px">Información de Reserva<span class="datos-factura">Reserva N° : <?= $model->nro_reserva ?></span></div>
  </div>

  <div class="text-index" style="padding: 10px 15px 0px 15px"> 
    <div class="row">

        <div class="col-lg-6">
          <div class="panel panel-default panel-d">
            <div class="panel-body panel-datos pnel">
              <div class="col-lg-6 col-xs-12" style="padding-left: 0px;">
                <div class="col-lg-12 col-xs-12">    
                  <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos de Recogida</div>
                </div>
                <div class="col-lg-6 col-xs-6">
                  <div class="datos-reserva">Fecha</div>      
                  <div class="info-reserva" style="margin-bottom: 15px"><?= $fecha_entrada; ?></div>
                </div>
                <div class="col-lg-5 col-xs-6">
                  <div class="datos-reserva">Hora</div>      
                  <div class="info-reserva" style="margin-bottom: 15px"><?= $model->hora_entrada; ?></div>
                </div>
                <div class="col-lg-1"></div>
              </div>

              <div class="col-lg-6 col-xs-12">
                <div class="col-lg-12 col-xs-12">
                  <div class="subtitulo-reserva toc" style="margin-bottom: 20px">Datos de Devolución</div>
                </div>                          

                <div class="col-lg-6 col-xs-6">
                  <div class="datos-reserva">Fecha</div>      
                  <div class="info-reserva" style="margin-bottom: 15px"><?= $fecha_salida; ?></div>
                </div> 
                <div class="col-lg-5 col-xs-6">
                  <div class="datos-reserva">Hora</div>      
                  <div class="info-reserva" style="margin-bottom: 15px"><?= $model->hora_salida; ?></div>
                </div>

                <div class="col-lg-1"></div>
              </div> 
            </div>
          </div> 

          <div class="panel panel-default panel-d">
            <div class="panel-body panel-datos">
              <div class="col-lg-12">
                <div class="subtitulo-reserva" style="margin-bottom: 20px">Información de Reserva</div>
              </div> 

              <div class="col-lg-4">
                <div class="datos-reserva">Terminal de Recogida</div>      
                <div class="info-reserva" style="margin-bottom: 20px"><?= $model->terminal_entrada; ?></div>
              </div>  

              <div class="col-lg-4">
                <div class="datos-reserva">Terminal de Entrega</div>      
                <div class="info-reserva" style="margin-bottom: 20px"><?= $model->terminal_salida; ?></div>
              </div>

              <div class="col-lg-4">
                <div class="datos-reserva">Factura Equipaje</div>      
                <div class="info-reserva" style="margin-bottom: 20px"><?= $factura_equipaje; ?></div>
              </div>              

              <div class="col-lg-6">
                <div class="datos-reserva">N° Vuelo de Regreso</div>      
                <div class="info-reserva" style="margin-bottom: 20px"><?= $model->nro_vuelo_regreso; ?></div>
              </div>

              <div class="col-lg-6">
                <div class="datos-reserva">Ciudad de Procedencia</div>      
                <div class="info-reserva" style="margin-bottom: 20px"><?= $model->ciudad_procedencia; ?></div>
              </div>

              <div class="col-lg-12">
                <div class="datos-reserva">Observaciones</div>      
                <div class="info-reserva" style="min-height: 64px; margin-bottom: 15px"><?= $model->observaciones; ?></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="panel panel-default panel-d">
            <div class="panel-body panel-datos">            
              <div class="col-lg-12">
                <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos del Cliente</div>
              </div>                                   

              <div class="col-lg-6">
                <div class="datos-reserva">Nombre y Apellidos</div>      
                <div class="info-reserva" style="margin-bottom: 20px"><?= $model->cliente->nombre_completo; ?></div>
              </div>
              <div class="col-lg-6">
                <div class="datos-reserva">Correo Electrónico</div>      
                <div class="info-reserva" style="margin-bottom: 20px"><?= $model->cliente->correo; ?></div>
              </div>              
              <div class="col-lg-4">
                <div class="datos-reserva">Tipo de Documento</div>      
                <div class="info-reserva" style="margin-bottom: 15px"><?= $model->cliente->tipo_documento; ?></div>
              </div>
              <div class="col-lg-4">
                <div class="datos-reserva">N° de Documento</div>      
                <div class="info-reserva" style="margin-bottom: 15px"><?= $model->cliente->nro_documento; ?></div>
              </div> 
              <div class="col-lg-4">
                <div class="datos-reserva">Móvil</div>      
                <div class="info-reserva" style="margin-bottom: 15px"><?= $model->cliente->movil; ?></div>
              </div>
            </div>
          </div>

          <div class="panel panel-default panel-d">
            <div class="panel-body panel-datos">

              <div class="col-lg-12">
                <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos del Vehículo</div>
              </div>           

              <div class="col-lg-3">
                <div class="datos-reserva">Marca</div>      
                <div class="info-reserva" style="margin-bottom: 15px"><?= $!empty($model->coche->marca) ? $model->coche->marca : 'N/D' ?></div>
              </div> 
              <div class="col-lg-3">
                <div class="datos-reserva">Modelo</div>      
                <div class="info-reserva" style="margin-bottom: 15px"><?= !empty($model->coche->modelo) ? $model->coche->modelo : 'N/D' ?></div>
              </div>
              <div class="col-lg-3">
                <div class="datos-reserva">Matrícula</div>      
                <div class="info-reserva" style="margin-bottom: 15px"><?= !empty($model->coche->matricula) ? $model->coche->matricula : 'N/D' ?></div>
              </div>       
              <div class="col-lg-3">
                <div class="datos-reserva">Color</div>      
                <div class="info-reserva" style="margin-bottom: 15px"><?= !empty($model->coche->color) ? $model->coche->color : 'N/D' ?></div>
              </div> 
            </div>
          </div>

          <div class="panel panel-default panel-d" style="margin-top: -10px">
            <div class="panel-body panel-datos">
              <div class="col-lg-12" style="margin-top: -20px; margin-bottom: 5px">
                <div class="subtitulo-reserva" style="display: inline;">Facturación</div>
                <span class="info-reserva" style="margin-left: 25px"><?= $nota; ?></span>
              </div>                
            </div>
          </div>

        </div>

        <div class="col-lg-12">
          <div class="panel panel-default panel-d">
            <div class="panel-body panel-datos"> 

              <div class="col-lg-12">
                <div class="subtitulo-reserva" style="margin-bottom: 20px">Servicios Contratados</div>
              </div> 
              <div class="col-lg-1 col-xs-1 s">
                <div align="center" class="subtitulo-reserva sub-reserva">Items</div>    
              </div>              
              <div class="col-lg-9 col-xs-8">
                <div align="center" class="subtitulo-reserva sub-reserva">Descripción</div>    
              </div>
              <div class="col-lg-2 col-xs-4">
                <div align="center" class="subtitulo-reserva sub-reserva">Total</div>
              </div>                       

              <?php 
              $num = 1;
              foreach ($ser as $s): $datos = Servicios::find()->where(['id'=> $s->id_servicio])->one(); 
                if ($datos->fijo === 0) {
                  ?>
              
              <div class="col-lg-1 col-xs-1 s">
                <label class="num"><?= $num ?></label>    
              </div> 
              <div class="col-lg-9">
                <div class="service-reserva">
                    <?= $datos->nombre_servicio; ?><?= ' - '.$dias->days.' día(s)' ?>
                    <span class="detalles-plan">Desde: <?= $fecha_entrada.' - Hasta: '.$fecha_salida ?></span>
                </div>
                <div class="des-reserva-ind mb" style="margin-left: 0px"><?= $datos->descripcion; ?></div><br>
              </div>             

              <div class="col-lg-2">
                <div class="form-group field-facturas-monto_factura required">
                  <div class="input-group costos-facturas">
                    <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $s->precio_total; ?>" disabled="true">
                    <span class="input-group-addon">€</span>
                  </div>
                </div>                
              </div> 

              <div class="col-lg-12"><hr style="margin-top: 12px"></div>        

              <?php $num++; } else { ?>

              <div class="col-lg-1 col-xs-1 s">
                <label class="num"><?= $num ?></label>    
              </div>                 

              <div class="col-lg-9">
                <div class="service-reserva">
                  <?= $datos->nombre_servicio; ?>
                </div>
                <div class="des-reserva-ind mb" style="margin-left: 0px"><?= $datos->descripcion; ?></div><br>
              </div>             

              <div class="col-lg-2">
                <div class="form-group field-facturas-monto_factura required">
                  <div class="input-group costos-facturas">
                    <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $s->precio_total; ?>" disabled="true">
                    <span class="input-group-addon">€</span>
                  </div>
                </div>                
              </div>

              <div class="col-lg-12"><hr style="margin-top: 12px"></div>             

              <?php $num++;} endforeach; ?>  
            </div>
          </div>
        </div>

        <div class="col-lg-12 col-xs-12">
          <div class="panel panel-default panel-d d2">
            <div class="panel-body panel-datos otherp" style="padding-bottom: 40px">        
              <div class="col-lg-2">
                  <div class="datos-reserva">Forma de Pago</div>      
                  <div class="info-reserva"><?= $model->tipoPago->descripcion; ?></div>
              </div>
              <div class="col-lg-2">
                  <div class="datos-reserva">Estátus de la Reserva</div>      
                  <div class="info-reserva"><?= $estatus; ?></div>
              </div>              

              <div class="col-lg-6">
                <div align="right" class="totales-facturas">Monto Total</div>
              </div>

              <div class="col-lg-2">
                <div class="form-group field-facturas-monto_factura required has-success">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $model->monto_total; ?>" disabled="true">
                        <span class="input-group-addon">€</span>
                    </div>
                </div>
              </div>              
                
            </div>
          </div>
        </div>
                        
        <div class="col-lg-8"></div>

        <div id="cancelar" align="right" class="col-lg-2 col-xs-12" style="margin-top: 25px">
          <?= Html::a('Cancelar', ['reservas'], ['class' => 'btn btn-warning btn-block']) ?>
        </div>

        <div id="guardar" align="right" class="col-lg-2 col-xs-12" style="margin-top: 25px">
          <div class="form-group">
              <?= Html::a('Imprimir Comprobante', [
                  '/site/pdf',
                  'id'=> $model->id,
                  ],   [
                  'class'=>'btn btn-success btn-block', 
                  'target'=>'_blank', 
                  'data-toggle'=>'tooltip', 
                  'title'=>'Imprimir Comprobante'
                  ]) 
              ?>
          </div>
        </div>
          
      </div>           
    </div>
  </div>

<?php 
  $this->registerJs(" 
    $('#BtnModalId').click(function(e){    
      e.preventDefault();
      $('#generar_factura').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });
  ");
?>