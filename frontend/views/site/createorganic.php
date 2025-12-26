<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\depdrop\DepDrop;
use common\models\Coches;
use kartik\color\ColorInput;

/* @var $this yii\web\View */
/* @var $model common\models\Reservas */
/* @var $form yii\widgets\ActiveForm */

$model->fecha_entrada = $entrada;
$model->hora_entrada = $hora_e;

$model->fecha_salida = $salida;
$model->hora_salida = $hora_s;

$this->title = Yii::$app->name . ' | Nueva Reserva';

$cant = count($precio_diario);
$num = 1;
for ($i = 0; $i < $cant; $i++) { ?>
  <input class="form-control" style="margin-bottom: 20px" type="hidden" id="precio-diario<?= $num ?>" value="<?= $precio_diario[$i]['precio'] ?>">

<?php $num++;
}
?>

<div class="reservas-form">

  <div class="col-lg-12">
    <div class="title-top">Formulario de Reserva - Solicitud de Servicio</div>
  </div>

  <div class="text-index" style="padding: 10px 15px 0px 15px">
    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

      <?= $form->field($model, 'iva')->hiddenInput(['value' => $iva])->label(false) ?>
      <?= $form->field($model, 'dias')->hiddenInput(['value' => $cant_dias])->label(false) ?>

      <div class="col-lg-6">
        <div class="panel panel-default panel-d">
          <div class="panel-body panel-datos pnel">
            <div class="col-lg-12">
              <div class="subtitulo-reserva" style="margin-bottom: 20px;">Información de Reserva</div>
            </div>
            <div class="col-lg-12 text-danger" style="<?= $paradaActiva ? '' : 'display:none' ?>" id="alert_parada">
              Para la fecha de entrada o salida no tenemos plazas disponibles. Por favor selecciona otras fechas.
            </div>
            <div class="form-group">
              <div class="col-lg-4">
                <? $recogida ='<span>Recogida &nbsp;</span><span class="tooltipcurved tooltipcurved-west">
                  <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg></span>
                  <span class="tooltipcurved-content">Día y Hora que dejarás el coche con uno de nuestros conductores calificados.</span>
                </span>'; ?>
                <?=
                 $form->field($model, 'fecha_entrada')->textInput([
                  'readonly' => true,
                  'style' => 'border-radius: 6px !important;'
                ])->label($recogida) ?>
              </div>
              <div class="col-lg-2">
                <?= $form->field($model, 'hora_entrada')->textInput([
                  'readonly' => true,
                  'style' => 'border-radius: 6px !important;'
                ]) ?>
              </div>

              <div class="col-lg-4">
                <? $devolucion ='<span>Devolución &nbsp;</span><span class="tooltipcurved tooltipcurved-west">
                  <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg></span>
                  <span class="tooltipcurved-content">Día y Hora en el que te devolveremos tu coche.</span>
                </span>'; ?>
                <?= $form->field($model, 'fecha_salida')->textInput([
                  'readonly' => true,
                  'style' => 'border-radius: 6px !important;'
                ])->label($devolucion); ?>
              </div>
              <div class="col-lg-2">
                <?= $form->field($model, 'hora_salida')->textInput([
                  'readonly' => true,
                  'style' => 'border-top-right-radius: 6px !important;border-bottom-right-radius: 8px !important;'
                ]) ?>
              </div>
            </div>
            <div class="col-lg-12"><br></div>

            <div class="col-lg-6">
              <? $terminal_entrada ='<span>Terminal de Entrada &nbsp;</span><span class="tooltipcurved tooltipcurved-west">
                  <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg></span>
                  <span class="tooltipcurved-content">Terminal en donde nos dejarás tu coche. En caso de no saber la terminal sugerimos seleccionar "AUN NO CONOZCO LA TERMINAL"</span>
                </span>'; ?>
              <?= $form->field($model, 'terminal_entrada')->widget(Select2::classname(), [
                'data' => $terminales,
                'options' => ['placeholder' => 'Selecccione'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
              ])->label($terminal_entrada); ?>
            </div>

            <div class="col-lg-6">
              <? $terminal_salida ='<span>Terminal de Salida &nbsp;</span><span class="tooltipcurved tooltipcurved-west">
                  <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg></span>
                  <span class="tooltipcurved-content">Terminal en donde te entregaremos tu coche. En caso de no saber la terminal sugerimos seleccionar "AUN NO CONOZCO LA TERMINAL"</span>
                </span>'; ?>
              <?= $form->field($model, 'terminal_salida')->widget(Select2::classname(), [
                'data' => $terminales,
                'options' => ['placeholder' => 'Selecccione'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
              ])->label($terminal_salida); ?>
            </div>

            <div class="col-lg-12"><br></div>

            <div class="col-lg-5">
              <? $ciudad_procedencia ='<span>Ciudad de Procedencia &nbsp;</span><span class="tooltipcurved tooltipcurved-west">
                  <span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                    </svg></span>
                  <span class="tooltipcurved-content">Rellena con el nombre de la ciudad de salida de tu viaje de regreso. ¿Haces escala? En ese caso, completa la ultima ciudad de la que salgas.</span>
                </span>'; ?>
              <?= $form->field($model, 'ciudad_procedencia')->textInput(['maxlength' => true])->label($ciudad_procedencia); ?>
            </div>

            <div class="col-lg-7">
              <?= $form->field($model, 'observaciones')->textarea(['rows' => '2']) ?>
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

            <div class="form-group">
              <div class="col-lg-6">
                <?= $form->field($modelC, 'nombre_completo')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
              </div>

              <div class="col-lg-6">
                <?= $form->field($modelC, 'correo')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
              </div>
            </div>

            <div class="form-group">
              <div class="col-lg-6">
                <?= $form->field($modelC, 'movil')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
              </div>

              <div class="col-lg-6">
                <label class="control-label" for="coches-marca">Marca - Modelo</label>
                <?= $form->field($modelV, 'marca')->textInput(['maxlength' => true])->label(false) ?>
              </div>
            </div>
            
            <!--<div class="col-lg-6">-->
            <!--    <?= $form->field($modelV, 'matricula')->textInput(['maxlength'=> true]) ?>-->
            <!--  </div>-->

           <div class="col-lg-6">
    <?= $form->field($modelV, 'matricula')->textInput([
        'maxlength' => true,
        'value' => $modelV->matricula ?: 'N/D',  // Si 'matricula' está vacío, se establecerá "N/D"
    ]) ?>
</div>

            <div class="col-lg-12" style="margin-top: 23px; margin-bottom: 5px">
              <?= $form->field($model, 'factura')->checkbox(['onclick' => 'muestra("facturacion")', 'uncheck' => '0', 'value' => '1'])->label(false) ?>
            </div>


          </div>
        </div>
      </div>

      <div class="col-lg-12" id="marine"></div>

      <div id="facturacion">
        <div class="col-lg-12">
          <div class="panel panel-default panel-d">
            <div class="panel-body panel-datos">

              <div class="col-lg-12" id="factura_cliente">
                <div class="subtitulo-reserva" style="margin-bottom: 20px">Información de Facturación</div>
              </div>

              <div class="col-lg-2">
                <?= $form->field($model, 'nif')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-lg-4">
                <?= $form->field($model, 'razon_social')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-lg-6">
                <?= $form->field($model, 'direccion')->textInput(['maxlength' => true]) ?>
              </div>

              <div class="col-lg-12"><br></div>

              <div class="col-lg-3">
                <?= $form->field($model, 'cod_postal')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-lg-3">
                <?= $form->field($model, 'ciudad')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-lg-3">
                <?= $form->field($model, 'provincia')->textInput(['maxlength' => true]) ?>
              </div>
              <div class="col-lg-3">
                <?= $form->field($model, 'pais')->textInput(['maxlength' => true]) ?>
              </div>

            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-12">
        <div class="panel panel-default panel-d">
          <div class="panel-body panel-datos">

            <div class="col-lg-12">
              <div class="subtitulo-reserva" style="margin-bottom: 20px">Servicios Extras Disponibles</div>
            </div>
            <div class="col-lg-8 col-xs-8">
              <div align="center" class="subtitulo-reserva sub-reserva">Descripción</div>
            </div>
            <div class="col-lg-2 col-xs-4">
              <div align="center" class="subtitulo-reserva sub-reserva">Precio</div>
            </div>
            <div class="col-lg-2">
              <div align="center" class="subtitulo-reserva sub-reserva na">Total</div>
            </div>

            <?php
            foreach ($servicios as $s) {
              if (in_array($s->id, [9, 12])) {
                continue; // omitir “Plaza reservada” y “Techado”
              }
              $service = array($s->id => $s->nombre_servicio);
            ?>

              <?= $form->field($model, 'tipo_servicio')->hiddenInput(['id' => 'tipo_servicio' . $s->id, 'value' => $s->fijo, 'name' => 'tipo_servicio' . $s->id])->label(false) ?>

              <?= $form->field($model, 'cantidad')->hiddenInput(['id' => 'cantidad' . $s->id, 'min' => 1, 'name' => 'cantidad' . $s->id, 'value' => 0])->label(false) ?>

              <div id="mobile">

                <div class="col-lg-8 col-xs-8">
                  <?= $form->field($model, 'servicios')->checkboxList($service, [
                    'separator' => '<br>',
                    'itemOptions' => [
                      'class' => 'servicios',
                      'precio' => $s->costo,
                      'labelOptions' => ['class' => 'services']
                    ]
                  ])->label(false);

                  ?>
                  <div class="des-reserva-ind"><?= $s->descripcion; ?></div><br>
                </div>

                <div class="col-lg-2 col-xs-4" style="margin-top:-8px">
                  <?= $form->field($model, 'precio_unitario', [
                    'template' => '<div class="input-group costos-facturas">{input}
                    <span class="input-group-addon eu">€</span></div>{error}{hint}'
                  ])->textInput(['id' => 'precio_unitario' . $s->id, 'readonly' => true, 'value' => $s->costo, 'class' => 'form-control cantidad', 'name' => 'precio_unitario' . $s->id]) ?>
                </div>

                <div class="col-lg-2 na" style="margin-top:-8px">
                  <?= $form->field($model, 'precio_total', [
                    'template' => '<div class="input-group costos-facturas">{input}
                    <span class="input-group-addon eu">€</span></div>{error}{hint}'
                  ])->textInput(['id' => 'precio_total' . $s->id, 'readonly' => true, 'class' => 'form-control cantidad', 'name' => 'precio_total' . $s->id]) ?>
                </div>

              </div>

            <?php } ?>

          </div>
        </div>
      </div>


      <div class="col-lg-12">
        <div class="panel panel-default panel-d">
          <div class="panel-body panel-datos">

            <div class="col-lg-12">
              <div class="subtitulo-reserva" style="margin-bottom: 20px">Total Costos de Reserva</div>
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

            <div id="mobile">
            
            <input type="hidden"  name="servicio_noc_id" value="<?= !is_null($nocturno) ? $nocturno[0]['id'] : 0 ?>">
            <input type="hidden" id="servicio_noc" name="servicio_noc_costo" value="<?= !is_null($nocturno) ? $nocturno[0]['costo'] : 0 ?>">
            <?php if(!is_null($nocturno)) { ?>
              <div class="col-lg-1 col-xs-1 s">
                <label class="num">SN</label>
              </div>

              <div class="col-lg-9 col-xs-8">
                <label class="service-reserva"><?= $nocturno[0]['nombre_servicio'] ?></label>
                <div class="des-reserva-ind mb" style="margin-left: 0px"><?= $nocturno[0]['descripcion'] ?></div>
              </div>

              <div class="col-lg-2 col-xs-4" style="margin-top:-8px">
                <?= $form->field($model, 'servicio_nocturno', [
                'template' => '<div class="input-group costos-facturas">{input}
                    <span class="input-group-addon eu">€</span></div>{error}{hint}'
                ])->textInput(['readonly' => true, 'class' => 'form-control cantidad', 'value' => $nocturno[0]['costo']]) ?>
              </div>

              <div class="col-lg-12 col-xs-12">
                <hr style="margin-top: 20px; margin-bottom: 15px">
              </div>
              <?php } ?>
              
              <div class="col-lg-1 col-xs-1 s">
                <label class="num">1</label>
              </div>

              <div class="col-lg-9 col-xs-8">
                <label class="service-reserva"><?= $precio_diario[0]['nombre_servicio'] ?></label>
                <div class="des-reserva-ind mb" style="margin-left: 0px"><?= $precio_diario[0]['descripcion'] ?></div>
              </div>

              <div class="col-lg-2 col-xs-4" style="margin-top:-8px">
                <?= $form->field($model, 'costo_servicios', [
                  'template' => '<div class="input-group costos-facturas">{input}
                    <span class="input-group-addon eu">€</span></div>{error}{hint}'
                ])->textInput(['readonly' => true, 'class' => 'form-control cantidad', 'value' => '0.00']) ?>
              </div>

              <div class="col-lg-12 col-xs-12">
                <hr style="margin-top: 20px; margin-bottom: 15px">
              </div>

              <div class="col-lg-1 col-xs-1 s">
                <label class="num">2</label>
              </div>

              <div class="col-lg-9 col-xs-8">
                <label class="service-reserva"><?= $seguro[0]->nombre_servicio ?></label>
                <div class="des-reserva-ind mb" style="margin-left: 0px"><?= $seguro[0]->descripcion ?></div>
              </div>

              <div class="col-lg-2 col-xs-4">
                <?= $form->field($model, 'total_seguro', [
                  'template' => '<div class="input-group costos-facturas">{input}
                    <span class="input-group-addon eu">€</span></div>{error}{hint}'
                ])->textInput(['id' => 'total_seguro', 'readonly' => true, 'class' => 'form-control cantidad', 'name' => 'total_seguro', 'value' => $seguro[0]->costo]) ?>
              </div>

              <div class="col-lg-12 col-xs-12">
                <hr style="margin-top: 20px; margin-bottom: 15px">
              </div>

              <div class="col-lg-1 col-xs-1 s">
                <label class="num">3</label>
              </div>

              <div class="col-lg-9 col-xs-8">
                <label class="service-reserva">Servicios Extras Seleccionados</label>
                <div class="des-reserva-ind mb" style="margin-left: 0px">Otros servicios extras</div>
              </div>

              <div class="col-lg-2 col-xs-4">
                <?= $form->field($model, 'costo_servicios_extra', [
                  'template' => '<div class="input-group costos-facturas">{input}
                    <span class="input-group-addon eu">€</span></div>{error}{hint}'
                ])->textInput(['readonly' => true, 'class' => 'form-control cantidad', 'value' => '0.00']) ?>
              </div>
            </div>
          </div>
        </div>
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

      <div class="col-lg-12 col-xs-12">
        <div class="panel panel-default panel-d d2" style="margin-bottom: 0px">
          <div class="panel-body panel-datos otherp">

            <div class="col-lg-12 col-xs-12">
              <?= $form->field($model, 'id_tipo_pago')->widget(Select2::classname(), [
                'data' => $tipos_pago,
                'options' => ['placeholder' => 'Selecccione forma de pago...'],
                'pluginOptions' => [
                  'allowClear' => true
                ],
              ]); ?>
            </div>

            <div class="col-lg-5 col-xs-10" style="margin-top: 32px; padding-left: 25px">
              <?= $form->field($model, 'condiciones')->checkbox(['uncheck' => ' ', 'value' => '1'])->label(false) ?>
            </div>

            <div class="col-lg-2 col-xs-7" style="margin-top: 10px">
              <div id="subtotal-factura" class="totales-facturas">Total a Pagar</div>
            </div>

            <div class="col-lg-2 col-xs-5" style="margin-top: 10px">
              <?= $form->field($model, 'monto_factura', [
                'template' => '<div class="input-group costos-facturas">{input}
                  <span class="input-group-addon eu">€</span></div>{error}{hint}'
              ])->textInput(['maxlength' => true, 'readonly' => true, 'value' => '0.00']) ?>
            </div>

            <?= $form->field($model, 'monto_impuestos')->hiddenInput()->label(false) ?>
            <?= $form->field($model, 'monto_total')->hiddenInput()->label(false) ?>

            <div class="col-lg-12">
              <hr style="border-top: 2px dashed #ccc">
            </div>

            <div class="col-lg-8">
              <div class="hide" style="text-transform: uppercase; color: red; font-size: 0.8em">Estimado Usuario la forma de pago Online se encuentra en periodo de pruebas. <br>NO seleccione este medio de pago</div>
            </div>

            <div id="cancelar" align="right" class="col-lg-2 col-xs-12" style="margin-top: 5px; margin-bottom: 25px">
              <?= Html::a('Cancelar', ['/site/index'], ['class' => 'btn btn-warning btn-block']) ?>
            </div>

            <div id="guardar" align="right" class="col-lg-2 col-xs-12" style="margin-top: 5px; margin-bottom: 25px">
              <div class="form-group">
                <?= Html::submitButton('Finalizar Reserva', ['class' => 'btn btn-success btn-block', 'id' => 'finalizar', 'disabled' => $paradaActiva]) ?>
              </div>
            </div>



          </div>
        </div>
      </div>

    </div>
    <?php ActiveForm::end(); ?>
  </div>
</div>

<?php
$this->registerJs(" 

    $( document ).ready(function() {
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

      if (cant == 1) { var total = parseFloat(precio1); }                     
      if (cant == 2) { var total = parseFloat(precio2); }
      if (cant == 3) { var total = parseFloat(precio3); }
      if (cant == 4) { var total = parseFloat(precio4); }
      if (cant == 5) { var total = parseFloat(precio5); }
      if (cant == 6) { var total = parseFloat(precio6); }
      if (cant == 7) { var total = parseFloat(precio7); }
      if (cant == 8) { var total = parseFloat(precio8); }
      if (cant == 9) { var total = parseFloat(precio9); }
      if (cant == 10) { var total = parseFloat(precio10); }

      if (cant == 11) { var total = parseFloat(precio11); }                     
      if (cant == 12) { var total = parseFloat(precio12); }
      if (cant == 13) { var total = parseFloat(precio13); }
      if (cant == 14) { var total = parseFloat(precio14); }
      if (cant == 15) { var total = parseFloat(precio15); }
      if (cant == 16) { var total = parseFloat(precio16); }
      if (cant == 17) { var total = parseFloat(precio17); }
      if (cant == 18) { var total = parseFloat(precio18); }
      if (cant == 19) { var total = parseFloat(precio19); }
      if (cant == 20) { var total = parseFloat(precio20); }

      if (cant == 21) { var total = parseFloat(precio21); }                     
      if (cant == 22) { var total = parseFloat(precio22); }
      if (cant == 23) { var total = parseFloat(precio23); }
      if (cant == 24) { var total = parseFloat(precio24); }
      if (cant == 25) { var total = parseFloat(precio25); }
      if (cant == 26) { var total = parseFloat(precio26); }
      if (cant == 27) { var total = parseFloat(precio27); }
      if (cant == 28) { var total = parseFloat(precio28); }
      if (cant == 29) { var total = parseFloat(precio29); }
      if (cant == 30) { var total = parseFloat(precio30); }                        

      if (cant > 30) { 
        var cant_dias = cant - 30;
        var precio_relativo = parseFloat(precio30);
        var total = precio_relativo + (cant_dias * 3); 
      }

      $('#reservas-costo_servicios').val(total.toFixed(2));
      $('.totales-facturas').click();
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
      })
  
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
                  $('#precio_total'+ id).val(precio);
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

                    $('#subtotal-factura').click(function() {
                      var monto_subtotal = 0;
                      var imp = $('#reservas-iva').val();
                      $('.servicios:checked').each(function() {
                        var id = $(this).val();
                        var precio = $('#precio_total'+ id).val();
                        
                        monto_subtotal = parseFloat(monto_subtotal) + parseFloat(precio);
                        });             

                        $('#reservas-costo_servicios_extra').val(monto_subtotal.toFixed(2));
                        var total_seguro = $('#total_seguro').val();
                        var costo_servicios = $('#reservas-costo_servicios').val();
                        
                        var nocturnidad = $('#servicio_noc').val();
                        var stotal_reserva = monto_subtotal + parseFloat(total_seguro) + parseFloat(costo_servicios)  + parseFloat(nocturnidad);
    
 

                        $('#reservas-monto_factura').val(stotal_reserva.toFixed(2));
                        var impuestos = 0;
                        $('#reservas-monto_impuestos').val(impuestos.toFixed(2));
                        var total_monto = parseFloat(stotal_reserva) + parseFloat(impuestos);
                        
                        $('#reservas-monto_total').val(total_monto.toFixed(2));
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

  var paradaActiva = <?= $paradaActiva ? 'true' : 'false' ?>;

  if (paradaActiva) {
    $('#finalizar').prop('disabled', true);
    $('#alert_parada').show();
  }



  function muestra(id) {
    if (document.getElementById) {
      var contenido = document.getElementById(id);
      contenido.style.display = (contenido.style.display == 'none') ? 'block' : 'none';
    }
  }

  window.onload = function() {
    muestra('facturacion');
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
  
 /* setTimeout(() => {
      var extraNocturno = $('#servicio_noc').val();
      
      if(extraNocturno != 0){
          var total = $('#reservas-monto_total').val();
          total = parseFloat(total) + parseFloat(extraNocturno);
          $('#reservas-monto_total').val(total.toFixed(2));
          $('#reservas-monto_factura').val(total.toFixed(2));
      }
  }, 1000);*/
</script>