<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\time\TimePicker;
use yii\helpers\Url;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model common\models\Reservas */
/* @var $form yii\widgets\ActiveForm */

$Url = Url::to(['site/modifica']);
$UrlAnu = Url::to(['site/anulacion']);

$model->fecha_entrada = date('d-m-Y', strtotime($entrada));
$model->hora_entrada = $hora_e;

$model->fecha_salida = date('d-m-Y', strtotime($salida));
$model->hora_salida = $hora_s;

$this->title = Yii::$app->name;

$cant = count($precio_diario);
$num = 1;
for ($i = 0; $i < $cant; $i++) { ?>
  <input class="form-control" style="margin-bottom: 20px" type="hidden" id="precio-diario<?= $num ?>"
    value="<?= $precio_diario[$i]['precio'] ?>">

<?php $num++;
}

$formato = new IntlDateFormatter(
  'es-ES',
  IntlDateFormatter::FULL,
  IntlDateFormatter::FULL,
  'Europe/Madrid',
  IntlDateFormatter::GREGORIAN,
  "eeee d 'de' LLLL 'de' yyyy"
);


Modal::begin([
  'header' => 'ANULACIÓN DE RESERVA',
  'id' => 'anu_reserva',
  'size' => 'modal-md',

]);

echo "<div id='modalContent'></div>";

Modal::end();


?>



<main class="container">
  <section class="row">
    <?php $form = ActiveForm::begin(); ?>

    <div class="reserva__container flex-md-column flex-lg-row col-12 d-flex" style="margin-top: 15px">

      <?= $form->field($model, 'iva')->hiddenInput(['value' => $iva])->label(false) ?>
      <?= $form->field($model, 'dias')->hiddenInput(['value' => $cant_dias])->label(false) ?>
      <?= $form->field($model, 'monto_impuestos')->hiddenInput()->label(false) ?>
      <?= $form->field($model, 'monto_total')->hiddenInput()->label(false) ?>
      <?= $form->field($model, 'type')->hiddenInput(['id' => 'type_reserva', 'value' => $type_reserva, 'name' => 'type'])->label(false) ?>

      <?= $form->field($model, 'costo_servicios')->hiddenInput(['value' => '0.00'])->label(false) ?>
      <?= $form->field($model, 'total_seguro')->hiddenInput(['value' => $seguro[0]->costo])->label(false) ?>
      <?= $form->field($model, 'costo_servicios_extra')->hiddenInput(['value' => '0.00'])->label(false) ?>



      <input type="hidden" name="is_noc" id="is_noc" value="<?= $nocturno[0]['id'] ?>">
      <input type="hidden" name="servicio_noc_id" value="<?= explode('-', $nocturno[0]['id'])[0] ?>">
      <input type="hidden" id="servicio_noc" name="servicio_noc_costo" value="<?= $nocturno[0]['costo'] ?>">

      <input type="hidden" id="url" value="<?= $Url ?>">
      <input type="hidden" id="urlAnu" value="<?= $UrlAnu ?>">
      <input type="hidden" id="reserva" value="<?= $model->nro_reserva ?>">
      <input type="hidden" name="solicitud_factura" value="<?= $solicitud_factura ?>">
      <input type="hidden" id="precio_dia" name="precio_dia" value="<?= $precio_dia ?>">


      <div class="reserva__ini col-sm-12 col-md-8 col-lg-8">
        <div class="col-12 d-flex">
          <div class="col-md-3 col-lg-3 reserva__ini__picture  d-sm-flex justify-content-center align-items-center">
            <?= Html::img('@web/images/logoParking.png', ['style' => "width: 160px"]); ?>
          </div>
          <div class="col-md-9 col-lg-9">
            <h2>Parkingplus</h2>
            <?php if (!$model->isNewRecord) { ?>
              <p><strong><?= $model->factura == 1 ? 'Solicitar factura ' : 'Modificar ' ?>Reserva: </strong>
                #<?= $model->nro_reserva ?></p>
            <?php } ?>

            <p>
              <?= Html::img('@web/images/map-pin.svg'); ?>
              7 minutos de distancia de Madrid-Barajas
            </p>
            <p>
              <?= Html::img('@web/images/time.svg'); ?>
              Abierto 24/7
              <?= Html::img('@web/images/car.svg'); ?>
              Entrega de su vehiculo
            </p>
            <h4 class="mt-4 mb-2">Tu reserva</h4>
            <p class="lh-base">
              <strong><?= $cant_dias ?></strong> días
              <strong> parking con servicio de recogina del vehiculo </strong>
              desde
              <strong class="arrivalDate"><?= $formato->format(new DateTime($model->fecha_entrada))
                                          ?></strong>
              hasta
              <strong class="arrivalDate"><?= $formato->format(new DateTime($model->fecha_salida))
                                          ?></strong>
            </p>
          </div>
        </div>

        <div class="col-md-12 col-lg-12 d-md-block d-lg-none reserva__price__movil">
          <div class="col-12 p-3" style="border: 1px solid #cccfcf">
            <div class="fs-4 pb-2" style="border-bottom: 1px solid #cccfcf">
              Precio
            </div>
            <div class="col-12 pt-2" style="border-bottom: 1px solid #cccfcf; color: #961007">
              <strong>Tu reserva incluye</strong>
              <p class="pb-3">
                - Explicación detallada y descripción de los servicios.
              </p>
            </div>

            <div class="col-md-12 col-lg-12 d-fle_x py-3">
              <p>
                Parking - Plan Premiun
              </p>
              <p>
                Recogida y entrega de Vehiculo
              </p>
              <?php if (!is_null($nocturno)) { ?>
                <p>
                  Servicio de Nocturnidad
                </p>
              <?php } ?>

              <?php if ($type_reserva == 9) { ?>
                <p>
                  Techado
                </p>
                <p>
                  Lavado Exterior Cortesia
                </p>
              <?php } ?>

              <?php if ($type_reserva == 12) { ?>
                <p>
                  Lavado Interior/Exterior
                </p>
                <p>
                  Parking Interior
                </p>
              <?php } ?>
            </div>
          </div>
          <div class="col-12 text-white p-3 mb-3 fs-4 d-flex justify-content-between align-items-center"
            style="background-color: #000000">
            <strong>Importe a pagar</strong>

            <span class=""><strong class="reserva__detail__monto">0</strong>€</span>
          </div>
        </div>

        <div class="col-md-12 col-lg-12 mt-4 p-2" style="background-color: #fcfcfc">
          <form>
            <h3 class="mb-4 pb-3"
              style="border-bottom: 1px solid #e7eaed; display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              Información de la reserva
            </h3>
            <div class="col-lg-12 text-danger" style="display:none" id="alert_fechas">
              Verifique las fechas y horas seleccionadas
            </div>
            <div class="form-group d-flex mt-1 flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="col-md-6 col-lg-5 control-label">
                Fecha de entrada al parking *
              </label>
              <div class="col-md-6 col-lg-5">
                <!-- <?=
                      $form->field($model, 'fecha_entrada')->textInput([
                        'readonly' => !$model->isNewRecord ? false : true,
                        'style' => 'border-radius: 6px !important;',
                        'class' => 'form-control',
                        'type' => 'date'
                      ])->label(false) ?> -->
                <?= $form->field($model, 'fecha_entrada')->widget(DatePicker::classname(), [
                  'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()', 'style' => 'width: 100%;'],
                  'language' => 'es',
                  'readonly' => !$model->isNewRecord ? false : true,
                  'removeButton' => false,
                  'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy',
                    'startDate' => date('d-m-Y'),
                    'todayHighlight' => true,
                  ],
                ])->label(false); ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5 time">
                Hora de entrada al parking *
              </label>
              <div class="col-md-6 col-lg-5 checkmark-placement contains-select time">

                <!-- <?= $form->field($model, 'hora_entrada')->textInput([
                        'readonly' => !$model->isNewRecord ? false : true,
                        'style' => 'border-radius: 6px !important;',
                        'class' => 'form-control',
                        'type' => 'time'
                      ])->label(false) ?> -->
                <?= $form->field($model, 'hora_entrada')->widget(TimePicker::classname(), [
                  'readonly' => !$model->isNewRecord ? false : true,
                  'pluginOptions' => [
                    'showMeridian' => false,
                  ]
                ])->label(false); ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="col-md-6 col-lg-5 control-label">
                Fecha de salida del parking *
              </label>
              <div class="col-md-6 col-lg-5">
                <!-- <?=
                      $form->field($model, 'fecha_salida')->textInput([
                        'readonly' => !$model->isNewRecord ? false : true,
                        'style' => 'border-radius: 6px !important;',
                        'class' => 'form-control'
                      ])->label(false) ?> -->
                <?= $form->field($model, 'fecha_salida')->widget(DatePicker::classname(), [
                  'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()', 'style' => 'width: 100%;'],
                  'language' => 'es',
                  'readonly' => !$model->isNewRecord ? false : true,
                  'removeButton' => false,
                  'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose' => true,
                    'format' => 'dd-mm-yyyy',
                    'startDate' => date('d-m-Y'),
                    'todayHighlight' => true,
                  ],
                ])->label(false); ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5 time">
                Hora de salida del parking *
              </label>
              <div class="col-md-6 col-lg-5 checkmark-placement contains-select time">
                <!-- <?= $form->field($model, 'hora_salida')->textInput([
                        'readonly' => !$model->isNewRecord ? false : true,
                        'style' => 'border-radius: 6px !important;',
                        'class' => 'form-control'
                      ])->label(false) ?> -->
                <?= $form->field($model, 'hora_salida')->widget(TimePicker::classname(), [
                  'readonly' => !$model->isNewRecord ? false : true,
                  'pluginOptions' => [
                    'showMeridian' => false,
                  ]
                ])->label(false); ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5 time">
                Terminal de entrada
              </label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($model, 'terminal_entrada')->widget(Select2::classname(), [
                  'data' => $terminales,
                  'class' => 'form-control',
                  'options' => ['placeholder' => 'Selecccione'],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ])->label(false); ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5 time">
                Terminal de salida
                <span class="tooltipcurved-content">Terminal en donde te entregaremos tu coche. En caso de no saber la
                  terminal sugerimos seleccionar "AUN NO CONOZCO LA TERMINAL"</span>
                </span>
              </label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($model, 'terminal_salida')->widget(Select2::classname(), [
                  'data' => $terminales,
                  'class' => 'form-control',
                  'options' => ['placeholder' => 'Selecccione'],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ])->label(false); ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5 time">
                Ciudad de procedencia
              </label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($model, 'ciudad_procedencia')->textInput(['maxlength' => true, 'class' => 'form-control'])->label(false); ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5 time">
                Observación
              </label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($model, 'observaciones')->textarea(['rows' => '5', 'class' => 'form-control'])->label(false) ?>
              </div>
            </div>

            <h3 class="pb-3"
              style="border-bottom: 1px solid #e7eaed; margin: 32px ; display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              Información personal
            </h3>

            <div class="form-group d-flex mt-4 flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="col-md-6 col-lg-5 control-label">
                Nombres y apellidos *
              </label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($modelC, 'nombre_completo')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'class' => 'form-control'])->label(false) ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5 time">
                Correo Electrónico *
              </label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($modelC, 'correo')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'class' => 'form-control'])->label(false) ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="col-md-6 col-lg-5 control-label">
                N° de Móvil *
              </label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($modelC, 'movil')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'class' => 'form-control'])->label(false) ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5">
                Marca y Modelo *
              </label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($modelV, 'marca')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'class' => 'form-control'])->label(false) ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5">
                Matricula
              </label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($modelV, 'matricula')->textInput(['maxlength' => true, 'autocomplete' => 'off', 'class' => 'form-control'])->label(false) ?>
              </div>
            </div>

            <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <label for="" class="control-label col-md-6 col-lg-5"></label>
              <div class="col-md-6 col-lg-5">
                <?= $form->field($model, 'factura')->checkbox(['onclick' => 'muestra("facturacion")', 'uncheck' => $model->factura == 1 ? 1 : 0, 'value' => '1'])->label(false) ?>
              </div>
            </div>

            <div class="reserva__factura" id="facturacion"
              style="display:<?= $model->factura == 1 ? 'block !important' : 'none' ?>">

              <h3 class="pb-3" style="border-bottom: 1px solid #e7eaed; margin: 32px 0">
                Información de la factura
              </h3>
              <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item">
                <label for="" class="control-label col-md-6 col-lg-5">NIF</label>
                <div class="col-md-6 col-lg-5">
                  <?= $form->field($model, 'nif')->textInput(['maxlength' => true])->label(false) ?>
                </div>
              </div>

              <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item">
                <label for="" class="control-label col-md-6 col-lg-5">Razón social</label>
                <div class="col-md-6 col-lg-5">
                  <?= $form->field($model, 'razon_social')->textInput(['maxlength' => true])->label(false) ?>
                </div>
              </div>

              <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item">
                <label for="" class="control-label col-md-6 col-lg-5">Dirección</label>
                <div class="col-md-6 col-lg-5">
                  <?= $form->field($model, 'direccion')->textInput(['maxlength' => true])->label(false) ?>
                </div>
              </div>

              <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item">
                <label for="" class="control-label col-md-6 col-lg-5">Código Postal</label>
                <div class="col-md-6 col-lg-5">
                  <?= $form->field($model, 'cod_postal')->textInput(['maxlength' => true])->label(false) ?>
                </div>
              </div>

              <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item">
                <label for="" class="control-label col-md-6 col-lg-5">Ciudad</label>
                <div class="col-md-6 col-lg-5">
                  <?= $form->field($model, 'ciudad')->textInput(['maxlength' => true])->label(false) ?>
                </div>
              </div>

              <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item">
                <label for="" class="control-label col-md-6 col-lg-5">Provincia</label>
                <div class="col-md-6 col-lg-5">
                  <?= $form->field($model, 'provincia')->textInput(['maxlength' => true])->label(false) ?>
                </div>
              </div>

              <div class="form-group mt-2 d-flex flex-sm-column flex-md-row reserva__form__item">
                <label for="" class="control-label col-md-6 col-lg-5">País</label>
                <div class="col-md-6 col-lg-5">
                  <?= $form->field($model, 'pais')->textInput(['maxlength' => true])->label(false) ?>
                </div>
              </div>
              <div id="factura_cliente"></div>
            </div>
            <h3 class="pb-3"
              style="border-bottom: 1px solid #e7eaed; margin: 32px 0;display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              Servicios Extras Disponibles
            </h3>

            <div style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <?php
              foreach ($servicios as $s) {
                $service = array($s->id => $s->nombre_servicio);
                $checked = "";

                if (!$model->isNewRecord) {
                  if ($seleccionados != null) {
                    foreach ($seleccionados as $selec) {
                      if ($selec == $s->id) {
                        $checked = 'true';
                        break;
                      } else {
                        $checked = '';
                      }
                    }
                  }
                }
              ?>



                <div class="form-group mt-2"
                  style="<?= (((in_array($s->id, [7, 9])) && $type_reserva == 9) || (in_array($s->id, [2, 12]) && $type_reserva == 12) || ($s->id == 7 && $type_reserva != 9)) ? 'display:none' : '' ?>">
                  <?= $form->field($model, 'tipo_servicio')->hiddenInput(['id' => 'tipo_servicio' . $s->id, 'value' => $s->fijo, 'name' => 'tipo_servicio' . $s->id])->label(false) ?>

                  <?= $form->field($model, 'cantidad')->hiddenInput(['id' => 'cantidad' . $s->id, 'value' => 0, 'min' => 1, 'name' => 'cantidad' . $s->id])->label(false) ?>

                  <div class="col-12 d-flex ser__extra_item">

                    <?php if ($checked) { ?>
                      <div class="col-sm-9 col-md-9 col-lg-9 d-flex flex-column reserva__ser__extra">
                        <?= $form->field($model, 'servicios')->checkboxList($service, [
                          'separator' => '<br>',
                          'itemOptions' => [
                            'checked' => $checked,
                            'class' => 'servicios form-check-input servi' . $s->id,
                            'precio' => $s->costo,
                            'labelOptions' => ['class' => 'services']
                          ]
                        ])->label(false); ?>
                        <span class="des-reserva-ind"><?= $s->descripcion; ?></span>
                      </div>
                    <?php } else { ?>
                      <div class="col-sm-9 col-md-9 col-lg-9 d-flex flex-column reserva__ser__extra">
                        <?= $form->field($model, 'servicios')->checkboxList($service, [
                          'separator' => '<br>',
                          'itemOptions' => [
                            'class' => 'servicios form-check-input servi' . $s->id,
                            'precio' => $s->costo,
                            'labelOptions' => ['class' => 'services']
                          ]
                        ])->label(false); ?>
                        <span class="des-reserva-ind"><?= $s->descripcion; ?></span>
                      </div>
                    <?php } ?>

                    <div class="col-sm-3 col-md-3 col-lg-3 text-end">
                      <?= $form->field($model, 'precio_unitario', [
                        'template' => '<div class="input-group costos-facturas">{input}
                    <span class="input-group-addon eu">€</span></div>{error}{hint}'
                      ])->textInput(['id' => 'precio_unitario' . $s->id, 'readonly' => true, 'value' => $s->costo, 'class' => 'form-control cantidad', 'name' => 'precio_unitario' . $s->id]) ?>
                    </div>
                  </div>
                </div>
              <?php } ?>
            </div>

            <div class="hide">
              <div class="col-lg-2" style="margin-top:-8px">
                <?= $form->field($model, 'servicio_basico', [
                  'template' => '<div class="input-group costos-facturas">{input}
              <span class="input-group-addon eu">€</span></div>{error}{hint}'
                ])->textInput(['id' => 'servicio_basico', 'readonly' => true, 'value' => $precio_diario[0]['costo'], 'class' => 'form-control cantidad', 'name' => 'servicio_basico']) ?>
              </div>

              <div class="col-lg-1" style="margin-top:-8px">
                <?= $form->field($model, 'cant_basico', [
                  'template' => '<div class="input-group costos-facturas">{input}
              </div>{error}{hint}'
                ])->textInput(['id' => 'cant_basico', 'type' => 'number', 'readonly' => true, 'class' => 'form-control cantidad', 'name' => 'cant_basico', 'value' => $cant_dias]) ?>
              </div>


              <div class="col-lg-2" style="margin-top:-8px">
                <?= $form->field($model, 'seguro')->hiddenInput(['id' => 'seguro', 'readonly' => true, 'value' => $seguro[0]->costo, 'class' => 'form-control cantidad', 'name' => 'seguro'])->label(false) ?>
              </div>

              <div class="col-lg-1" style="margin-top:-8px">
                <?= $form->field($model, 'cant_seguro', [
                  'template' => '<div class="input-group costos-facturas">{input}
              </div>{error}{hint}'
                ])->hiddenInput(['id' => 'cant_seguro', 'readonly' => true, 'class' => 'form-control cantidad', 'name' => 'cant_seguro', 'value' => 1]) ?>
              </div>
            </div>


            <h3 class="pb-3"
              style="border-bottom: 1px solid #e7eaed; margin: 32px 0;display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              Forma de pago
            </h3>

            <div class="form-group mt-2 col-12 d-flex flex-column"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <div class="col-lg-6 col-xs-12">
                <?= $form->field($model, 'id_tipo_pago')->widget(Select2::classname(), [
                  'data' => $tipos_pago,
                  'options' => ['placeholder' => 'Selecccione forma de pago'],
                  'pluginOptions' => [
                    'allowClear' => true,
                  ],
                ]); ?>
              </div>

              <div class="col-lg-7 col-xs-10" style="margin-top: 32px; padding-left: 25px">
                <?= $form->field($model, 'condiciones')->checkbox(['uncheck' => ' ', 'value' => '1'])->label(false) ?>
              </div>
            </div>

            <div class="col-12 mt-4 d-flex justify-content-sm-end reserva__total__pagar"
              style="display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
              <div
                class="col-md-7 col-lg-9 fs-4 text-end mx-2 d-flex justify-content-end align-items-center totales-facturas"
                id="subtotal-factura">
                Total a Pagar
              </div>
              <div class="col-md-3 col-lg-3 d-flex fs-3 justify-content-center align-items-center fw-bold">
                <?= $form->field($model, 'monto_factura', [
                  'template' => '<div class="input-group costos-facturas">{input}
                  <span class="input-group-addon eu">€</span></div>{error}{hint}'
                ])->textInput(['maxlength' => true, 'readonly' => true, 'value' => '0.00']) ?>
              </div>
            </div>


            <div class="col-12 d-flex align-items-center justify-content-end my-4">

              <?php if (!$model->isNewRecord && $model->factura == 0) { ?>
                <?= Html::button('Anular Reserva', [
                  'class' => 'btn text-white fs-6 mx-4',
                  'style' => ['background-color' => '#000'],
                  'id' => 'BtnModalId',
                  'data-toggle' => 'modal',
                  'data-target' => '#anu_reserva',
                ]) ?>

              <?php } else { ?>
                <?= Html::a('Cancelar', ['/site/index'], ['class' => 'btn text-white fs-6 mx-4', 'style' => 'background-color: #000']) ?>
              <?php } ?>

              <?php if ($model->factura == 1) { ?>
                <?= Html::button('Solicitar Factura', ['class' => 'btn text-white p-2', 'id' => 'solFactura', 'style' => 'background-color: #961007']) ?>
              <?php } else { ?>
                <?= Html::submitButton(!$model->isNewRecord ? 'Actualizar Reserva' : 'Procesar Reserva', ['class' => 'btn text-white p-2', 'id' => 'finalizar', 'style' => 'background-color: #961007']) ?>
              <?php } ?>
            </div>
          </form>
        </div>
      </div>


      <!-- Inicio segunda columna -->

      <div class="reserva_form col-sm-12 col-md-4 col-lg-4 p-4">
        <div class="col-sm-12 col-md-12 col-lg-12 p-3 reserva__price d-sm-none d-lg-block"
          style="border: 1px solid #cccfcf; display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
          <div class="fs-4 pb-2" style="border-bottom: 1px solid #cccfcf">
            Precio
          </div>
          <div class="col-12 pt-2" style="border-bottom: 1px solid #cccfcf; color: #961007">
            <strong>Tu reserva incluye</strong>
            <p class="pb-3">
              - Explicación detallada y descripción de los servicios.
            </p>
          </div>

          <div class="col-md-12 col-lg-12 d-flex flex-column pt-3">
            <p>Parking - Plan Standar</p>
            <p>Recogida y entrega de Vehiculo</p>

            <?php if ($type_reserva == 9) { ?>
              <p>Techado</p>
              <p>Lavado Exterior Cortesia</p>
            <?php } ?>

            <?php if ($type_reserva == 12) { ?>
              <p>Lavado Interior/Exterior</p>
              <p>Parking Interior</p>
            <?php } ?>
          </div>
        </div>

        <div
          class="col-12 text-white p-3 mb-3 fs-4 d-flex justify-content-between align-items-center d-sm-none d-lg-flex reserva__price"
          style="background-color: #000000; display:<?= $model->factura == 1 ? 'none !important' : '' ?>">
          <strong>Importe a pagar</strong>

          <span class="">
            <strong class="reserva__detail__monto">0</strong>€
          </span>
        </div>


        <div class="col-12">
          <h4 style="border-bottom: 1px solid #cccfcf; color: #961007" class="pb-2 mt-4">
            ¿Qué pasa después de esto?
          </h4>
          <ol class="stepper">
            <li>Recibiras una factura en tu correo.</li>
            <li>Puedes cambiar tu reserva 24 hrs antes.</li>
            <li>El día del viaje se recoge el vehiculo en el sitio.</li>
            <li class="active">
              El día de llegada se entrega el vehiculo en el sitio.
            </li>
          </ol>
        </div>

        <div class="col-12 mt-4">
          <h4 style="border-bottom: 1px solid #cccfcf; color: #961007" class="pb-2">
            ¿Tiene alguna pregunta?
          </h4>
          <p class="pt-2">
            Nuestro servicio de atención al cliente está abierto los 7 días de la
            semana. Llámanos o escribenos a
            <b>CONTACTO@PARKINGPLUS.ES</b>
          </p>
        </div>
      </div>

    </div>
    <?php ActiveForm::end(); ?>
  </section>
</main>


<?php
$this->registerJs(" 

    $( document ).ready(function() {
	
              $('#BtnModalId').click(function(e){    
                e.preventDefault();

                $.ajax({
                  type: 'GET',
                  url: $('#urlAnu').val(),
                  data: {
                    reserva: $('#reserva').val()
                  },
                  success: function (data) {
                    $('#anu_reserva').modal('show')
                    .find('#modalContent')
                    .html(data);
                  }
                });

                
                return false;
              });

        $('.servicios').each(function() {
            
            if($(this).val() == $('#type_reserva').val()){
              $(this).prop('checked',true);
              $(this).prop('disabled',true);
                
              var id = $(this).val();
              var tipo_servicio = $('#tipo_servicio'+ id).val();
              var precio = $('#precio_unitario'+ id).val();
              $('#cantidad'+ id).prop('readonly',false);
              cant = $('#cantidad'+ id).val();               
              if (cant == 0) {
                $('#cantidad'+ id).val(1);
              } 
              
              if($('#type_reserva').val() == 12){
                $('.servi2').prop('checked',true);
                $('.servi2').prop('disabled',true);
                var tipo_servicio = $('#tipo_servicio2').val();
                var precio = $('#precio_unitario2').val();
                $('#cantidad2').prop('readonly',false);
                cant = $('#cantidad2').val();               
                if (cant == 0) {
                  $('#cantidad2').val(1);
                } 
              }
              
              if(Number($('#type_reserva').val()) == 9){
              
                $('.servi7').prop('checked',true);
                $('.servi7').prop('disabled',true);
                var tipo_servicio = $('#tipo_servicio7').val();
                
                var precio = $('#precio_unitario7').val();
                $('#cantidad7').prop('readonly',false);
                cant = $('#cantidad7').val();               
                if (cant == 0) {
                  $('#cantidad7').val(1);
                  $('#precio_total7').val(precio);
                } 
              }
          
              $('.totales-facturas').click();
            }
        });
			
        precio_dia = $('#precio_dia').val();

      cant = $('#cant_basico').val();
      precio1 = $('#precio-diario1').val();
      precio2 = $('#precio-diario2').val();
      precio3 = $('#precio-diario3').val();
      precio4 = $('#precio-diario4').val();
      precio5 = $('#precio-diario5').val();
      precio6 = $('#precio-diario6').val();
      precio7 = $('#precio-diario7').val();
      precio8 = $('#precio-diario8').val();
      precio9 = $('#precio-diario9').val();
      precio10 = $('#precio-diario10').val();

      precio11 = $('#precio-diario11').val();
      precio12 = $('#precio-diario12').val();
      precio13 = $('#precio-diario13').val();
      precio14 = $('#precio-diario14').val();
      precio15 = $('#precio-diario15').val();
      precio16 = $('#precio-diario16').val();
      precio17 = $('#precio-diario17').val();
      precio18 = $('#precio-diario18').val();
      precio19 = $('#precio-diario19').val();
      precio20 = $('#precio-diario20').val();

      precio21 = $('#precio-diario21').val();
      precio22 = $('#precio-diario22').val(); 
      precio23 = $('#precio-diario23').val(); 
      precio24 = $('#precio-diario24').val(); 
      precio25 = $('#precio-diario25').val(); 
      precio26 = $('#precio-diario26').val(); 
      precio27 = $('#precio-diario27').val(); 
      precio28 = $('#precio-diario28').val(); 
      precio29 = $('#precio-diario29').val(); 
      precio30 = $('#precio-diario30').val(); 
      
      var total = 0;
      if (cant == 1) { total = parseFloat(precio1); }                     
      if (cant == 2) { total = parseFloat(precio2); }
      if (cant == 3) { total = parseFloat(precio3); }
      if (cant == 4) { total = parseFloat(precio4); }
      if (cant == 5) { total = parseFloat(precio5); }
      if (cant == 6) { total = parseFloat(precio6); }
      if (cant == 7) { total = parseFloat(precio7); }
      if (cant == 8) { total = parseFloat(precio8); }
      if (cant == 9) { total = parseFloat(precio9); }
      if (cant == 10) { total = parseFloat(precio10); }

      if (cant == 11) { total = parseFloat(precio11); }                     
      if (cant == 12) { total = parseFloat(precio12); }
      if (cant == 13) { total = parseFloat(precio13); }
      if (cant == 14) { total = parseFloat(precio14); }
      if (cant == 15) { total = parseFloat(precio15); }
      if (cant == 16) { total = parseFloat(precio16); }
      if (cant == 17) { total = parseFloat(precio17); }
      if (cant == 18) { total = parseFloat(precio18); }
      if (cant == 19) { total = parseFloat(precio19); }
      if (cant == 20) { total = parseFloat(precio20); }

      if (cant == 21) { total = parseFloat(precio21); }                     
      if (cant == 22) { total = parseFloat(precio22); }
      if (cant == 23) { total = parseFloat(precio23); }
      if (cant == 24) { total = parseFloat(precio24); }
      if (cant == 25) { total = parseFloat(precio25); }
      if (cant == 26) { total = parseFloat(precio26); }
      if (cant == 27) { total = parseFloat(precio27); }
      if (cant == 28) { total = parseFloat(precio28); }
      if (cant == 29) { total = parseFloat(precio29); }
      if (cant == 30) { total = parseFloat(precio30); }       
	  

      /*if (cant > 30) { 
        var cant_dias = cant - 30;
        var precio_relativo = parseFloat(precio30);
        var total = precio_relativo + (cant_dias * parseFloat(precio_dia)); 
      }*/

      if(cant > 30){
        while (cant > 30) {
          total +=  parseFloat(precio30);
          cant =  cant - 30;

          /*console.log(cant);
          console.log(total);*/
        }

        if(cant >= 18){
          total +=  parseFloat(precio30);
          //console.log(total);
        }else {
          total += (cant * parseFloat(precio_dia));
          //console.log(total);
        }
      }

      $('#reservas-costo_servicios').val(total.toFixed(2));
      $('.totales-facturas').click();
      });      
	  
  $('#solFactura').on('click', function(){
      
      if($('#reservas-nif').val() === '' || 
      $('#reservas-razon_social').val() === '' || 
      $('#reservas-direccion').val() === '' ||
      $('#reservas-ciudad').val() === '' || 
      $('#reservas-provincia').val() === '' || 
      $('#reservas-pais').val() === ''){
				$('#factura_cliente').text('').append('<div class=\"subtitulo-reserva\" style=\"text-decoration: none;padding: 12px 0;\">* Debe llenar los campos de facturación</div>')
				$('html, body').animate({ scrollTop: $('#facturacion').offset().top }, 1000);
        return;
			}else {
			  $(this)
				.text('')
				.removeClass('btn-success')
				.addClass('btn-primary')
				.html('<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span> Espere...')
				.attr('disabled', 'disabled')
				.trigger('submit');
			}
		  });

		$('#finalizar').on('click', function(){
			if($('#reservas-factura').is(':checked') && $('#reservas-nif').val() === '' || $('#reservas-razon_social').val() === '' || $('#reservas-direccion').val() === '' || $('#reservas-ciudad').val() === '' || $('#reservas-provincias').val() === '' || $('#reservas-pais').val() === ''){
				$('#factura_cliente').text('').append('<div class=\"subtitulo-reserva\" style=\"text-decoration: none;padding: 12px 0;\">* Debe llenar los campos de facturación</div>')
				$('html, body').animate({ scrollTop: $('#facturacion').offset().top }, 1000);
			}else if($('#reservas-id_tipo_pago').val() !== '' && $('#reservas-condiciones').is(':checked') && $('#reservas-id_tipo_pago').val() !== '' && $('#clientes-correo').val() !== '' && $('#clientes-movil').val() !== ''){
			  $(this)
				.text('')
				.removeClass('btn-success')
				.addClass('btn-primary')
				.html('<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span> Espere...')
				.attr('disabled', 'disabled')
				.trigger('submit');
			}  
		  });
		  
      $('#checkAll').change(function() {
        $('.select:checked').each(function() {
          $('.servicios').click();
          }); 
          $('.select:checkbox:not(:checked)').each(function() { 
            $('.servicios').click();           
            });
            })
			
			
            
			$('.servicios').change(function() {

              $('.servicios:checked').each(function() {
			  
					var id = $(this).val();
					var tipo_servicio = $('#tipo_servicio'+ id).val();
					var precio = $('#precio_unitario'+ id).val();
					$('#cantidad'+ id).prop('readonly',false);
					cant = $('#cantidad'+ id).val();        
					if (tipo_servicio == 1) {
					  $('#cantidad'+ id).prop('readonly',true);
					}

					if (cant == 0) {
					  $('#cantidad'+ id).val(1);
					  $('#precio_total'+ id).val(precio).css('background','blue');
					} 
					$('.totales-facturas').click();
                });

                var id = $(this).val();
                var tipo_servicio = $('#tipo_servicio'+ id).val();

                $('#cantidad'+ id).change(function() {

                  var cant = $('#cantidad'+ id).val(); 
                  var precio = $('#precio_unitario'+ id).val();
                  var precio_new = parseFloat(precio) * cant;
                  $('#precio_total'+ id).val(precio_new.toFixed(2)); 
                  $('.totales-facturas').click();                

                  })           

                  $('.servicios:checkbox:not(:checked)').each(function() {
                    var id = $(this).val();
                    $('#cantidad'+ id).val(0);
                    $('#cantidad'+ id).prop('readonly',true);
                    $('#precio_total'+ id).val(0);
                    $('.totales-facturas').click();
                    });           

                    }) 

                    $('#reservas-fecha_entrada').on('change', function(e) {
                      recalcular();
                      $('.totales-facturas').click();
                    })

                    $('#reservas-hora_entrada').on('change', function(e) {
                      recalcular()
                      $('.totales-facturas').click();
                    })  

                    $('#reservas-fecha_salida').on('change', function(e) {
                      recalcular()
                      $('.totales-facturas').click();
                    })

                    $('#reservas-hora_salida').on('change', function(e) {
                      recalcular()
                      $('.totales-facturas').click();
                    })   

                    $('#subtotal-factura').click(function() {

                      var monto_subtotal = 0;
                      var imp = $('#reservas-iva').val();
                      $('.servicios:checked').each(function() {
                        var id = $(this).val();
						            var precio = $('#precio_unitario'+ id).val();
                        monto_subtotal = parseFloat(monto_subtotal) + parseFloat(precio);
                      });             
	
	
                        $('#reservas-costo_servicios_extra').val(monto_subtotal.toFixed(2));
                        var total_seguro = $('#reservas-total_seguro').val();
                        var costo_servicios = $('#reservas-costo_servicios').val();
                        var stotal_reserva = monto_subtotal + parseFloat(total_seguro) + parseFloat(costo_servicios);

                        
                        $('#reservas-monto_factura').val(stotal_reserva.toFixed(2));
                        var impuestos = 0;
                        $('#reservas-monto_impuestos').val(impuestos.toFixed(2));
                        var total_monto = parseFloat(stotal_reserva) + parseFloat(impuestos);
                        $('#reservas-monto_total').val(total_monto.toFixed(2));
						            $('.reserva__detail__monto').html('').append(total_monto.toFixed(2));
                        }); 
                        
                        $('#reservas-factura').click(function(){ 
                            if( $('#reservas-factura').prop('checked') ) {
                                $('#reservas-nif').prop('required', true);
                                $('#reservas-razon_social').prop('required', true);
                                $('#reservas-direccion').prop('required', true);
                                $('#reservas-cod_postal').prop('required', true);
                                $('#reservas-ciudad').prop('required', true);
                                $('#reservas-provincias').prop('required', true);
                                $('#reservas-pais').prop('required', true);
                                console.log('si')
                            }else{
                                $('#reservas-nif').removeAttr('required');
                                $('#reservas-razon_social').removeAttr('required');
                                $('#reservas-direccion').removeAttr('required');
                                $('#reservas-cod_postal').removeAttr('required');
                                $('#reservas-ciudad').removeAttr('required');
                                $('#reservas-provincias').removeAttr('required');
                                $('#reservas-pais').removeAttr('required');
                                console.log('no')
                            } 
                        });

                        

");
?>

<script>
  function muestra(id) {
    if (document.getElementById) {
      var contenido = document.getElementById(id);
      contenido.style.display = (contenido.style.display == 'none') ? 'block' : 'none';
    }
  }

  /*window.onload = function () {
    muestra('facturacion');
  }*/

  function recalcular() {
    fecha_entrada = $('#reservas-fecha_entrada').val();
    hora_entrada = $('#reservas-hora_entrada').val();
    fecha_salida = $('#reservas-fecha_salida').val();
    hora_salida = $('#reservas-hora_salida').val();

    anio_in = String(fecha_entrada).substring(6, 10);
    dia_in = String(fecha_entrada).substring(0, 2);
    mes_in = String(fecha_entrada).substring(3, 5);
    f1 = new Date(anio_in, mes_in, dia_in);

    anio_out = String(fecha_salida).substring(6, 10);
    dia_out = String(fecha_salida).substring(0, 2);
    mes_out = String(fecha_salida).substring(3, 5);
    f2 = new Date(anio_out, mes_out, dia_out);

    var invalid = false;
    if (f1 > f2) {
      invalid = true;
    } else if (f1 == f2 && hora_entrada > hora_salida) {
      invalid = true;
    }

    if (invalid) {
      $('#alert_fechas').css('display', 'block');
      $('#finalizar').prop('disabled', true);
    } else {
      $('#alert_fechas').css('display', 'none');
      $('#finalizar').prop('disabled', false);
    }

    $.ajax({
      type: 'POST',
      url: '<?php echo \Yii::$app->getUrlManager()->createUrl('site/modifica') ?>',
      data: {
        fecha_entrada: fecha_entrada,
        hora_entrada: hora_entrada,
        fecha_salida: fecha_salida,
        hora_salida: hora_salida
      },
      success: function(data) {
        $('#cant_basico').val(data);
        dias = $('#cant_basico').val();
        precio_dia = $('#precio_dia').val();
        var total = 0;
        if (dias == 1) {
          total = parseFloat(precio1);
        }
        if (dias == 2) {
          total = parseFloat(precio2);
        }
        if (dias == 3) {
          total = parseFloat(precio3);
        }
        if (dias == 4) {
          total = parseFloat(precio4);
        }
        if (dias == 5) {
          total = parseFloat(precio5);
        }
        if (dias == 6) {
          total = parseFloat(precio6);
        }
        if (dias == 7) {
          total = parseFloat(precio7);
        }
        if (dias == 8) {
          total = parseFloat(precio8);
        }
        if (dias == 9) {
          total = parseFloat(precio9);
        }
        if (dias == 10) {
          total = parseFloat(precio10);
        }

        if (dias == 11) {
          total = parseFloat(precio11);
        }
        if (dias == 12) {
          total = parseFloat(precio12);
        }
        if (dias == 13) {
          total = parseFloat(precio13);
        }
        if (dias == 14) {
          total = parseFloat(precio14);
        }
        if (dias == 15) {
          total = parseFloat(precio15);
        }
        if (dias == 16) {
          total = parseFloat(precio16);
        }
        if (dias == 17) {
          total = parseFloat(precio17);
        }
        if (dias == 18) {
          total = parseFloat(precio18);
        }
        if (dias == 19) {
          total = parseFloat(precio19);
        }
        if (dias == 20) {
          total = parseFloat(precio20);
        }

        if (dias == 21) {
          total = parseFloat(precio21);
        }
        if (dias == 22) {
          total = parseFloat(precio22);
        }
        if (dias == 23) {
          total = parseFloat(precio23);
        }
        if (dias == 24) {
          total = parseFloat(precio24);
        }
        if (dias == 25) {
          total = parseFloat(precio25);
        }
        if (dias == 26) {
          total = parseFloat(precio26);
        }
        if (dias == 27) {
          total = parseFloat(precio27);
        }
        if (dias == 28) {
          total = parseFloat(precio28);
        }
        if (dias == 29) {
          total = parseFloat(precio29);
        }
        if (dias == 30) {
          total = parseFloat(precio30);
        }

        /*if (dias > 30) {
          var cant_dias = dias - 30;
          var precio_relativo = parseFloat(precio30);
          var total = precio_relativo + (cant_dias * parseFloat(precio_dia));
        }*/

        if (dias > 30) {
          while (dias > 30) {
            total += parseFloat(precio30);
            dias = dias - 30;

            /*console.log(dias);
            console.log(total);*/
          }

          if (dias >= 18) {
            total += parseFloat(precio30);
            //console.log(total);
          } else {
            total += (dias * parseFloat(precio_dia));
            //console.log(total);
          }
        } else {
          total = parseFloat($('#precio-diario' + dias).val());
        }

        $('#reservas-costo_servicios').val(total.toFixed(2));

        console.log(hora_entrada >= '00:30');
        if ((hora_entrada >= '00:30' && hora_entrada <= '03:45') || (hora_salida >= '00:30' && hora_salida <= '03:45')) {
          $('#is_noc').val('11-1');
          $('#nocturnidad').css('display', 'block');
        } else {
          $('#is_noc').val('11-0');
          $('#nocturnidad').css('display', 'none');
        }

        $('#subtotal-factura').click();
      }
    });
  }

  function buscarCliente() {
    $('#reservas-correo').prop('readonly', false);
    $('#reservas-tipo_documento').prop('readonly', false);
    $('#reservas-nro_documento').prop('readonly', false);
    $('#reservas-movil').prop('readonly', false);
    var id_cliente = $("#reservas-id_cliente").val()

    $.ajax({
      url: '<?php echo \Yii::$app->getUrlManager()->createUrl('reservas/clientes') ?>',
      type: 'post',
      data: {
        id: id_cliente
      },
      success: function(data) {
        correo = data.datos['correo'];
        tipo_documento = data.datos['tipo_documento'];
        nro_documento = data.datos['nro_documento'];
        movil = data.datos['movil'];
        $("#reservas-correo").val(correo);
        $("#reservas-tipo_documento").val(tipo_documento);
        $("#reservas-nro_documento").val(nro_documento);
        $("#reservas-movil").val(movil);
      },
      error: function() {
        console.log("failure");
      }
    });
  }

  function buscarCoche() {

    $("#reservas-color").css("background-color", '#eeeeee');
    var id_coche = $("#reservas-id_coche").val()

    $.ajax({
      url: '<?php echo \Yii::$app->getUrlManager()->createUrl('reservas/vehiculos') ?>',
      type: 'post',
      data: {
        id: id_coche
      },
      success: function(data) {
        matricula = data.datos['matricula'];
        color = data.datos['color'];
        $("#reservas-matricula").val(matricula);
        $("#reservas-color").css("background-color", color);
      },
      error: function() {
        console.log("failure");
      }
    });
  }
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  setTimeout(() => {
    var extraNocturno = $('#is_noc').val();

    if (extraNocturno == '11-1') {
      var total = $('#reservas-monto_total').val();
      total = parseFloat(total) + parseFloat($('#servicio_noc').val());

      $('#reservas-monto_factura').val(total.toFixed(2));
      $('.reserva__detail__monto').html('').append(total.toFixed(2));
    }
  }, 1000);
</script>