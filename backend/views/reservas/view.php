<?php

use yii\helpers\Html;
use common\models\Servicios;
use common\models\FacturasReserva;
use common\models\ReservasServicios;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Reservas */

$this->title = Yii::$app->name . ' | Información de Reserva';
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['index']];
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
  'header' => 'GENERAR FACTURA',
  'id' => 'generar_factura',
  'size' => 'modal-sm',
  'bodyOptions' => ['style' => 'top: 0px; padding:20px 15px'],

]); ?>

<input type="hidden" id="id_reserva" value="<?= $model->id; ?>">

<div id="modalContent"></div>

<?php

Modal::end();

$ser = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])->orderBy(['tipo_servicio' => SORT_ASC])->all();

$fecha1 = new DateTime($model->fecha_entrada);
$fecha2 = new DateTime($model->fecha_salida);
$dias = $fecha1->diff($fecha2);

$cant_dias = $dias->days;

if (($cant_dias == 0) && ($model->hora_salida > $model->hora_entrada)) {
  $cant_dias = 1;
}

$fecha_entrada = Yii::$app->formatter->asDate($model->fecha_entrada);
$fecha_salida = Yii::$app->formatter->asDate($model->fecha_salida);

if ($model->factura_equipaje === 0) {
  $factura_equipaje = 'NO';
} else {
  $factura_equipaje = 'SI';
}
if ($model->medio_reserva != 2) {
  if ($model->factura === 0) {
    $nota = 'EL CLIENTE NO REQUIERE FACTURA';
  } else {
    $factura_reserva = FacturasReserva::find()->where(['id_reserva' => $model->id])->one();
    if ($factura_reserva == NULL) {
      $nota = 'EL CLIENTE REQUIERE FACTURA' . Html::button(
        'Generar Factura',
        [
          'value' => Yii::$app->urlManager->createUrl('/reservas/generarf'),
          'class' => 'btn btn-success btn-xs btn-factura',
          'id' => 'generarf',
          'data-toggle' => 'modal',
          'data-target' => '#generar_factura',
        ]
      );
    } else {
      $nota = 'LA RESERVA TIENE UNA FACTURA ASOCIADA' . Html::a(
        'Ver Factura',
        [
          'facturas/view',
          'id' => $factura_reserva->id_factura
        ],
        [
          'class' => 'btn btn-success btn-xs btn-factura'
        ]
      );
    }
  }
} else {
  $nota = 'LA RESERVACIÓN FUÉ REALIZADA POR: ' . $model->agencia;
}

?>

<div class="reservas-view">

  <div class="title-margin-new">
    <span style="display: inline">Información de Reserva</span>
    <span class="datos-factura">Reserva N° : <?= $model->nro_reserva ?></span>
  </div>

  <div class="row">
    <div class="col-lg-12 subtitulo-reserva" style="margin: 0 0 20px 0">Datos del Cliente</div>
  </div>

  <div class="row">
    <div class="col-lg-5">
      <div class="datos-reserva">Nombre y Apellidos</div>
      <div class="info-view"><?= $model->cliente->nombre_completo; ?></div>
    </div>
    <div class="col-lg-3">
      <div class="datos-reserva">Móvil</div>
      <div class="info-view"><?= $model->cliente->movil; ?></div>
    </div>
  </div>
  <br>

  <div class="row">
    <div class="col-lg-5">
      <div class="datos-reserva">Marca - Modelo</div>
      <div class="info-view"><?= $model->coche->marca . " - " . $model->coche->modelo; ?></div>
    </div>
    <div class="col-lg-3">
      <div class="datos-reserva">Matrícula</div>
      <div class="info-view"><?= $model->coche->matricula; ?></div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12">
      <hr class="space"><br>
      <div class="subtitulo-reserva">Información de Reserva</div>
      <br>
    </div>

    <div class="col-lg-3">
      <div class="datos-reserva">Terminal de Recogida</div>
      <div class="info-view"><?= $model->terminal_entrada; ?></div>
    </div>

    <div class="col-lg-3">
      <div class="datos-reserva">Terminal de Entrega</div>
      <div class="info-view"><?= $model->terminal_salida; ?></div>
    </div>
    <div class="col-lg-3">
      <div class="datos-reserva">Ciudad de Procedencia</div>
      <div class="info-view"><?= $model->ciudad_procedencia; ?></div>
    </div>

    <div class="col-lg-12" style="margin-top: 15px"></div>

    <div class="col-lg-9">
      <div class="datos-reserva">Observaciones</div>
      <div class="info-view" style="min-height: 42px"><?= $model->observaciones; ?></div>
    </div>

    <div class="col-lg-12">
      <hr class="space"><br>
      <div class="subtitulo-reserva">Servicios Contratados</div>
      <br>
    </div>

    <div class="col-lg-7">
      <div align="center" class="datos-reserva">Descripción del Servicio</div>
      <hr style="margin-bottom: 35px">
    </div>

    <div class="col-lg-2">
      <div align="center" class="datos-reserva">Precio Unitario</div>
      <hr style="margin-bottom: 35px">
    </div>

    <div class="col-lg-1">
      <div align="center" class="datos-reserva">Cant</div>
      <hr style="margin-bottom: 35px">
    </div>

    <div class="col-lg-2">
      <div align="center" class="datos-reserva">Total</div>
      <hr style="margin-bottom: 35px">
    </div>

    <?php
    foreach ($ser as $s): $datos = Servicios::find()->where(['id' => $s->id_servicio])->one();
      if ($datos->fijo === 0) {
    ?>

        <div class="col-lg-7">
          <div class="datos-reserva">
            <li>
              <?= $datos->nombre_servicio; ?><?= ' - ' . $cant_dias . ' día(s)' ?>
              <span class="detalles-plan"><?= $fecha_entrada . ' / ' . $fecha_salida ?></span>
            </li>
          </div>
          <div class="des-reserva-indent" style="margin-left: 17px">
            <?= $datos->descripcion; ?>
          </div>
          <br>
        </div>

        <div class="col-lg-2" style="margin-top: -16px">
          <div class="form-group field-facturas-monto_factura required">
            <div class="input-group costos-facturas">
              <input type="text" id="facturas-precio_unitario" class="form-control" value="<?= $s->precio_unitario; ?>" disabled="true">
              <span class="input-group-addon">€</span>
            </div>
          </div>
        </div>

        <div class="col-lg-1" style="margin-top: -16px">
          <div class="form-group field-facturas-monto_factura required">
            <div class="input-group costos-facturas">
              <input type="text" id="facturas-cantidad" style="text-align: center; border-radius: 8px !important" class="form-control" value="<?= $s->cantidad; ?>" disabled="true">
            </div>
          </div>
        </div>

        <div class="col-lg-2" style="margin-top: -16px">
          <div class="form-group field-facturas-monto_factura required">
            <div class="input-group costos-facturas">
              <input type="text" id="facturas-precio_total" class="form-control" value="<?= $s->precio_total; ?>" disabled="true">
              <span class="input-group-addon">€</span>
            </div>
          </div>
        </div>

      <?php } else { ?>

        <div class="col-lg-7">
          <div class="datos-reserva">
            <li><?= $datos->nombre_servicio; ?></li>
          </div>
          <div class="des-reserva-indent" style="margin-left: 17px">
            <?= $datos->descripcion; ?>
          </div>
          <br>
        </div>

        <div class="col-lg-2" style="margin-top: -16px">
          <div class="form-group field-facturas-monto_factura required">
            <div class="input-group costos-facturas">
              <input type="text" id="facturas-precio_unitario" class="form-control" value="<?= $s->precio_unitario; ?>" disabled="true">
              <span class="input-group-addon">€</span>
            </div>
          </div>
        </div>

        <div class="col-lg-1" style="margin-top: -16px">
          <div class="form-group field-facturas-monto_factura required">
            <div class="input-group costos-facturas">
              <input type="text" id="facturas-cantidad" style="text-align: center; border-radius: 8px !important" class="form-control" value="<?= $s->cantidad; ?>" disabled="true">
            </div>
          </div>
        </div>

        <div class="col-lg-2" style="margin-top: -16px">
          <div class="form-group field-facturas-monto_factura required">
            <div class="input-group costos-facturas">
              <input type="text" id="facturas-precio_total" class="form-control" value="<?= $s->precio_total; ?>" disabled="true">
              <span class="input-group-addon">€</span>
            </div>
          </div>
        </div>

    <?php }
    endforeach; ?>

    <div class="col-lg-12">
      <hr style="margin-top: 10px">
    </div>

    <div class="col-lg-7">
      <div style="padding-top: 5px;">
        <div class="col-lg-6">
          <div class="datos-reserva">Forma de Pago</div>
          <div class="info-view"><?= $model->tipoPago->descripcion; ?></div>
        </div>
        <div class="col-lg-1"></div>
        <div class="col-lg-5">
          <div class="datos-reserva">Estátus de la Reserva</div>
          <div class="info-view"><?= $estatus; ?></div>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="totales-facturas" style="margin-top: 34px">Monto Total</div>
    </div>

    <div class="col-lg-2">
      <div class="form-group field-facturas-monto_factura required has-success" style="margin-top: 30px">
        <div class="input-group costos-facturas">
          <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $model->monto_total; ?>" disabled="true">
          <span class="input-group-addon">€</span>
        </div>
      </div>
    </div>


    <div class="col-lg-12">
      <hr style="margin-bottom: 5px; margin-top: 40px;">
    </div>

    <?php if ($model->actualizada && $model->medio_reserva == 3 && $model->cambios): ?>
      <div class="col-lg-12">
        <div class="alert alert-info">
          <p>Cambios solicitados por el cliente:</p>
          <ul class="mb-0">
            <?php foreach ($model->cambios as $chg): ?>
              <li><?= Html::encode($chg->campo) ?>: <?= Html::encode($chg->valor_anterior) ?> → <?= Html::encode($chg->valor_nuevo) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    <?php endif; ?>

    <div class="col-lg-4">
      <div class="nota-titulo">Nota:</div>
      <div class="nota-descripcion">
        <li><?= $nota; ?></li>
      </div>
    </div>
    <?php if ($model->canceled_by != 0) : ?>
      <div class="col-lg-3">
        <div class="nota-titulo">Usuario cancelo:</div>
        <div class="nota-descripcion">
          <li><?= $model->canceledBy->username; ?></li>
        </div>
      </div>
    <?php endif; ?>

    <div align="right" class="col-lg-5" style="margin-top: 25px">
      <?= Html::button('Cerrar', ['class' => 'btn btn-warning', 'data-dismiss' => 'modal']) ?>
      &nbsp; &nbsp; &nbsp;
      <?php if ($model->actualizada && $model->medio_reserva == 3): ?>
        <?= Html::a('Marcar revisada', ['/reservas/marcar-actualizada', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        &nbsp; &nbsp; &nbsp;
      <?php endif; ?>
      <?= Html::a('Imprimir Comprobante', [
        '/reservas/view-pdf',
        'id' => $model->id,
      ],   [
        'class' => 'btn btn-success',
        'target' => '_blank',
        'data-toggle' => 'tooltip',
        'title' => 'Imprimir Comprobante'
      ])
      ?>
    </div>
  </div>
</div>

<?php
$this->registerJs("     
    $('#generarf').click(function(e){    
      e.preventDefault();
      $('#generar_factura').modal('show')
      .find('#modalContent')
      .load($(this).attr('value'));
      return false;
    });
  ");
?>