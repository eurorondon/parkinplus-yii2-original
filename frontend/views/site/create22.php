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

$fecha_s = $model->fecha_salida;
$hora_s = $model->hora_salida;

$fecha1= new DateTime($entrada);
$fecha2= new DateTime($salida);
$dias = $fecha1->diff($fecha2);

$cant_dias = $dias->days;

if (($cant_dias == 0) && ($model->hora_salida > $model->hora_entrada)) {
    $cant_dias = 1;
}

$this->title = Yii::$app->name.' | Nueva Reserva';

$cant = count($precio_diario); $num = 1;
for ($i=0; $i < $cant ; $i++) { ?>
  <input class="form-control" style="margin-bottom: 20px" type="hidden" id="precio-diario<?= $num ?>" value="<?= $precio_diario[$i]['precio'] ?>">           

  <?php $num++; } 
  ?>

  <div class="reservas-form">
    
    <div class="col-lg-12">
      <div class="title-top">Formulario de Reserva - Solicitud de Servicio</div>
    </div>

    <div class="text-index" style="padding: 10px 15px 0px 15px">    
      <?php $form = ActiveForm::begin(); ?>
      <div class="row">

        <?= $form->field($model, 'iva')->hiddenInput(['value'=> $iva])->label(false) ?>
        <?= $form->field($model, 'dias')->hiddenInput(['value'=> $cant_dias])->label(false) ?>

        <div class="col-lg-6">
          <div class="panel panel-default panel-d">
            <div class="panel-body panel-datos pnel">
              <div class="col-lg-6 col-xs-12">
                <div class="col-lg-12 col-xs-12">    
                  <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos de Recogida</div>
                </div>
                <div class="col-lg-6 col-xs-6">
                  <?= $form->field($model, 'fecha_entrada')->textInput([
                    'readonly'=> true,
                    'style' => 'border-radius: 6px !important;'                        
                  ]) ?>
                </div>
                <div class="col-lg-6 col-xs-6">
                  <?= $form->field($model, 'hora_entrada')->textInput([
                    'readonly'=> true,
                    'style' => 'border-radius: 6px !important;'
                  ]) ?>
                </div>
              </div>

              <div class="col-lg-6 col-xs-12">
                <div class="col-lg-12 col-xs-12">
                  <div class="subtitulo-reserva toc" style="margin-bottom: 20px">Datos de Devolución</div>
                </div>                          

                <div class="col-lg-6 col-xs-6">
                  <?= $form->field($model, 'fecha_salida')->textInput([
                    'readonly'=> true,
                    'style' => 'border-radius: 6px !important;'                        
                  ]) ?>
                </div> 
                <div class="col-lg-6 col-xs-6">
                  <?= $form->field($model, 'hora_salida')->textInput([
                    'readonly'=> true, 
                    'style' => 'border-top-right-radius: 6px !important;border-bottom-right-radius: 8px !important;'
                  ]) ?>
                </div>
              </div> 
            </div>
          </div> 


          <div class="panel panel-default panel-d">
            <div class="panel-body panel-datos">
              <div class="col-lg-12">
                <div class="subtitulo-reserva" style="margin-bottom: 20px">Información de Reserva</div>
              </div> 

              <div class="col-lg-4">
                <?= $form->field($model, 'terminal_entrada')->widget(Select2::classname(), [
                  'data' => $terminales,
                  'options' => ['placeholder' => 'Selecccione'],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ]); ?>        
              </div>

              <div class="col-lg-4">
                <?= $form->field($model, 'terminal_salida')->widget(Select2::classname(), [
                  'data' => $terminales,
                  'options' => ['placeholder' => 'Selecccione'],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ]); ?>            
              </div>

              <div class="col-lg-4">
                <?= $form->field($model, 'factura_equipaje')->radioList([1 => 'SI', 0 => 'NO'])->label('Facturará Equipaje ?'); ?>
              </div>

              <div class="col-lg-12" id="marine"></div>

              <div class="col-lg-6">
                <?= $form->field($model, 'nro_vuelo_regreso')->textInput(['maxlength' => true]) ?>
              </div>

              <div class="col-lg-6">
                <?= $form->field($model, 'ciudad_procedencia')->textInput(['maxlength' => true]) ?>
              </div>

              <div class="col-lg-12" id="marine"></div>

              <div class="col-lg-12">
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

              <div class="col-lg-6">
                <?= $form->field($modelC, 'nombre_completo')->textInput(['maxlength'=> true, 'autocomplete' => 'off']) ?>
              </div>            

              <div class="col-lg-6">
                <?= $form->field($modelC, 'correo')->textInput(['maxlength'=> true, 'autocomplete' => 'off']) ?>
              </div>       

              <div class="col-lg-4">
                <?= $form->field($modelC, 'tipo_documento')->widget(Select2::classname(), [
                  'data' => $tipo_documento,
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ]); ?>
              </div>

              <div class="col-lg-4">
                <?= $form->field($modelC, 'nro_documento')->textInput(['maxlength'=> true, 'autocomplete' => 'off']) ?>
              </div>

              <div class="col-lg-4">
                <?= $form->field($modelC, 'movil')->textInput(['maxlength'=> true, 'autocomplete' => 'off']) ?>
              </div>
            </div>
          </div>

          <div class="panel panel-default panel-d">
            <div class="panel-body panel-datos">

              <div class="col-lg-12">
                <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos del Vehículo</div>
              </div>  


              <div class="col-lg-3">
                <?= $form->field($modelV, 'matricula')->textInput(['maxlength'=> true]) ?>
              </div>

              <div class="col-lg-3">
                <?= $form->field($modelV, 'marca')->textInput(['maxlength'=> true]) ?>
              </div>

              <div class="col-lg-3">
                <?= $form->field($modelV, 'modelo')->textInput(['maxlength'=> true]) ?>
              </div>  

              <div class="col-lg-3">
                <?= $form->field($modelV, 'color')->textInput(['maxlength'=> true]) ?>
              </div>
            </div>
          </div>

          <div class="panel panel-default panel-d" style="margin-top: -10px">
            <div class="panel-body" style="padding: 0px 15px">
              <div class="col-lg-12" style="margin-top: 24px; margin-bottom: 10px">
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

                <div class="col-lg-12">
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
                $service = array($s->id => $s->nombre_servicio);
              ?> 

              <?= $form->field($model, 'tipo_servicio')->hiddenInput(['id' => 'tipo_servicio'.$s->id, 'value'=> $s->fijo, 'name' => 'tipo_servicio'.$s->id])->label(false) ?>
              
              <?= $form->field($model, 'cantidad')->hiddenInput(['id' => 'cantidad'.$s->id, 'min'=>1, 'name' => 'cantidad'.$s->id])->label(false) ?>               

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
                  ])->textInput(['id' => 'precio_unitario'.$s->id, 'readonly' =>true, 'value' => $s->costo, 'class'=>'form-control cantidad', 'name' => 'precio_unitario'.$s->id]) ?> 
                </div> 

                <div class="col-lg-2 na" style="margin-top:-8px">
                  <?= $form->field($model, 'precio_total', [
                    'template' => '<div class="input-group costos-facturas">{input}
                    <span class="input-group-addon eu">€</span></div>{error}{hint}'
                  ])->textInput(['id' => 'precio_total'.$s->id, 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'precio_total'.$s->id]) ?> 
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
                  ])->textInput(['readonly' =>true, 'class'=>'form-control cantidad', 'value' => '0.00']) ?>
                </div>

                <div class="col-lg-12 col-xs-12"><hr style="margin-top: 20px; margin-bottom: 15px"></div>

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
                  ])->textInput(['id' => 'total_seguro', 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'total_seguro', 'value' => $seguro[0]->costo]) ?> 
                </div> 

                <div class="col-lg-12 col-xs-12"><hr style="margin-top: 20px; margin-bottom: 15px"></div>                     

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
                  ])->textInput(['readonly' =>true, 'class'=>'form-control cantidad', 'value' => '0.00']) ?>
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
            ])->textInput(['id' => 'servicio_basico', 'readonly' =>true, 'value' => $precio_diario[0]['costo'], 'class'=>'form-control cantidad', 'name' => 'servicio_basico']) ?> 
          </div> 

          <div class="col-lg-1" style="margin-top:-8px">
            <?= $form->field($model, 'cant_basico', [
              'template' => '<div class="input-group costos-facturas">{input}
              </div>{error}{hint}'
            ])->textInput(['id' => 'cant_basico', 'type' => 'number', 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'cant_basico', 'value' => $cant_dias]) ?> 
          </div>
                

          <div class="col-lg-2" style="margin-top:-8px">
            <?= $form->field($model, 'seguro')->hiddenInput(['id' => 'seguro', 'readonly' =>true, 'value' => $seguro[0]->costo, 'class'=>'form-control cantidad', 'name' => 'seguro'])->label(false) ?> 
          </div> 

          <div class="col-lg-1" style="margin-top:-8px">
            <?= $form->field($model, 'cant_seguro', [
              'template' => '<div class="input-group costos-facturas">{input}
              </div>{error}{hint}'
            ])->hiddenInput(['id' => 'cant_seguro', 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'cant_seguro', 'value' => 1]) ?> 
          </div> 
        </div>

        <div class="col-lg-12 col-xs-12">
          <div class="panel panel-default panel-d d2" style="margin-bottom: 0px">
            <div class="panel-body panel-datos otherp">

              <div class="col-lg-3 col-xs-12">
                <?= $form->field($model, 'id_tipo_pago')->widget(Select2::classname(), [
                  'data' => $tipos_pago,
                  'options' => ['placeholder' => 'Selecccione forma de pago'],
                  'pluginOptions' => [
                    'allowClear' => true
                  ],
                ]); ?>
              </div>

              <div class="col-lg-5 col-xs-10" style="margin-top: 32px; padding-left: 25px">
                <?= $form->field($model, 'condiciones')->checkbox(['uncheck' => ' ', 'value' => '1'])->label(false) ?>
              </div>             

              <div class="col-lg-2 col-xs-7" style="margin-top: 10px">
                <div id="subtotal-factura" class="totales-facturas">Monto Total</div>
              </div> 

              <div class="col-lg-2 col-xs-5" style="margin-top: 10px">
                <?= $form->field($model, 'monto_factura', [
                  'template' => '<div class="input-group costos-facturas">{input}
                  <span class="input-group-addon eu">€</span></div>{error}{hint}'
                ])->textInput(['maxlength' => true, 'readonly' => true, 'value' => '0.00']) ?> 
              </div> 

                <?= $form->field($model, 'monto_impuestos')->hiddenInput()->label(false) ?> 
                <?= $form->field($model, 'monto_total')->hiddenInput()->label(false) ?>  

                <div class="col-lg-12"><hr style="border-top: 2px dashed #ccc"></div>
        
                <div class="col-lg-8">
                  <div class="hide" style="text-transform: uppercase; color: red; font-size: 0.8em">Estimado Usuario la forma de pago Online se encuentra en periodo de pruebas. <br>NO seleccione este medio de pago</div>
                </div>

                <div id="cancelar" align="right" class="col-lg-2 col-xs-12" style="margin-top: 5px; margin-bottom: 25px">
                  <?= Html::a('Cancelar', ['/site/index'], ['class' => 'btn btn-warning btn-block']) ?>
                </div>

                <div id="guardar" align="right" class="col-lg-2 col-xs-12" style="margin-top: 5px; margin-bottom: 25px">
                  <div class="form-group">
                    <?= Html::submitButton('Finalizar Reserva', ['class' => 'btn btn-success btn-block']) ?>
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
        var total = precio_relativo + (cant_dias * 1.5); 
      }

      $('#reservas-costo_servicios').val(total.toFixed(2));
      $('.totales-facturas').click();
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
                        var stotal_reserva = monto_subtotal + parseFloat(total_seguro) + parseFloat(costo_servicios);


                        $('#reservas-monto_factura').val(stotal_reserva.toFixed(2));
                        var impuestos = 0;
                        $('#reservas-monto_impuestos').val(impuestos.toFixed(2));
                        var total_monto = parseFloat(stotal_reserva) + parseFloat(impuestos);
                        $('#reservas-monto_total').val(total_monto.toFixed(2));
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

                          window.onload = function() {
                            muestra('facturacion');
                          }

                          function buscarCliente() {
                            $('#reservas-correo').prop('readonly',false);
                            $('#reservas-tipo_documento').prop('readonly',false);
                            $('#reservas-nro_documento').prop('readonly',false);
                            $('#reservas-movil').prop('readonly',false);
                            var id_cliente = $("#reservas-id_cliente").val()

                            $.ajax({
                              url: '<?php echo \Yii::$app->getUrlManager()->createUrl('reservas/clientes') ?>',
                              type: 'post',
                              data: { 
                                id: id_cliente
                              },
                              success: function (data) {
                                correo = data.datos['correo'];
                                tipo_documento = data.datos['tipo_documento'];
                                nro_documento = data.datos['nro_documento'];
                                movil = data.datos['movil'];
                                $("#reservas-correo").val(correo);
                                $("#reservas-tipo_documento").val(tipo_documento);
                                $("#reservas-nro_documento").val(nro_documento);
                                $("#reservas-movil").val(movil); 
                              },
                              error: function(){
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
                              success: function (data) {
                                matricula = data.datos['matricula'];
                                color = data.datos['color'];
                                $("#reservas-matricula").val(matricula);
                                $("#reservas-color").css("background-color", color);
                              },
                              error: function(){
                                console.log("failure");
                              }            
                            });
                          }    

                        </script>