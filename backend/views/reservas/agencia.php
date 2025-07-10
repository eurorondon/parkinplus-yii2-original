<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\time\TimePicker;
use kartik\depdrop\DepDrop;
use common\models\Coches;
use common\models\Clientes;
use common\models\Agencias;

/* @var $this yii\web\View */
/* @var $model common\models\Reservas */
/* @var $form yii\widgets\ActiveForm */

$model->fecha_entrada = $entrada;
$model->hora_entrada = $hora_e;

$model->fecha_salida = $salida;
$model->hora_salida = $hora_s;

$model->medio_reserva = $medio;
$model->agencia = $agencia;

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
$this->params['breadcrumbs'][] = ['label' => 'Reservas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Nueva Reserva';

?>

<div class="reservas-form">
  <div class="panel panel-default panel-index">
    <div class="panel-heading caja-title">Nueva Reserva</div>
    <div class="panel-body gs1">
        <div class="row">
        <div class="title-margin-new">
            <span style="display: inline">Nueva Reserva</span>
            <span class="datos-factura">Reserva N° : <?= $proxima_reserva ?></span>
        </div>
        <br>
        <?php $form = ActiveForm::begin(); ?>

        <div class="col-lg-6" style="padding: 0px">
            <div class="panel panel-default panel-d">
                <div class="panel-body panel-dates pnel">
                    <div class="col-lg-6 col-xs-12">
                        <div class="col-lg-12 col-xs-12">    
                          <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos de Recogida</div>
                        </div>
                        <div class="col-lg-6 col-xs-6">
                          <?= $form->field($model, 'fecha_entrada')->widget(DatePicker::classname(), [
                              'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                              'language' => 'es',
                              'type' => DatePicker::TYPE_INPUT,
                              'pluginOptions' => [
                                  'orientation' => 'bottom left',
                                  'autoclose'=>true,
                                  'format' => 'dd-mm-yyyy',
                                  'startDate'=> date('d-m-Y'),                        
                              ]
                          ]) ?>  
                        </div>
                        <div class="col-lg-6 col-xs-6">
                          <?= $form->field($model, 'hora_entrada')->widget(TimePicker::classname(), [
                              'options' => ['style' => 'border-radius:6px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
                              'pluginOptions' => [
                                  'showMeridian' => false,
                              ]
                          ]) ?>
                        </div>
                    </div>

                    <div class="col-lg-6 col-xs-12">
                        <div class="col-lg-12 col-xs-12">
                          <div class="subtitulo-reserva toc" style="margin-bottom: 20px">Datos de Devolución</div>
                        </div>                          

                        <div class="col-lg-6 col-xs-6">
                          <?= $form->field($model, 'fecha_salida')->widget(DatePicker::classname(), [
                              'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                              'language' => 'es',
                              'type' => DatePicker::TYPE_INPUT,
                              'pluginOptions' => [
                                  'orientation' => 'bottom left',
                                  'autoclose'=>true,
                                  'format' => 'dd-mm-yyyy',
                                  'startDate'=> date('d-m-Y'),
                              ]
                          ]) ?>
                        </div> 
                        <div class="col-lg-6 col-xs-6">
                          <?= $form->field($model, 'hora_salida')->widget(TimePicker::classname(), [
                              'options' => ['style' => 'border-radius:6px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
                              'pluginOptions' => [
                                  'showMeridian' => false,
                              ]
                          ]) ?>
                        </div>
                    </div> 
                </div>
            </div>
            <div class="panel panel-default panel-d" style="margin-top: 30px">
                <div class="panel-body panel-dates">
                    <div class="col-lg-12">
                        <div class="subtitulo-reserva" style="margin-bottom: 20px">Información de Reserva</div>
                    </div> 

                    <div class="col-lg-6">
                        <?= $form->field($model, 'terminal_entrada')->widget(Select2::classname(), [
                          'data' => $terminales,
                          'options' => ['placeholder' => 'Selecccione'],
                          'pluginOptions' => [
                            'allowClear' => true
                          ],
                        ]); ?>        
                    </div>

                    <div class="col-lg-6">
                        <?= $form->field($model, 'terminal_salida')->widget(Select2::classname(), [
                            'data' => $terminales,
                            'options' => ['placeholder' => 'Selecccione'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ]); ?>            
                    </div>

                    <div class="col-lg-12" id="marine"></div>

                    <div class="col-lg-6">
                        <?= $form->field($model, 'ciudad_procedencia')->textInput(['maxlength' => true]) ?>
                    </div>                         
                </div>
            </div>
        </div>  

        <?= $form->field($model, 'nro_reserva')->hiddenInput(['value'=> $proxima_reserva])->label(false) ?>
        <?= $form->field($model, 'iva')->hiddenInput(['value'=> $iva])->label(false) ?>
        <?= $form->field($model, 'dias')->hiddenInput(['value'=> $cant_dias])->label(false) ?>

        <div class="col-lg-6">
            <div class="panel panel-default panel-d" style="margin-left: 15px">
                <div class="panel-body panel-dates">            
                    <div class="col-lg-12">
                        <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos del Cliente</div>
                    </div> 
             
                    <div class="col-lg-6"> 
                        <?= $form->field($clientes, 'nombre_completo')->textInput() ?>
                    </div> 

                    <div class="col-lg-6">
                        <?= $form->field($clientes, 'movil')->textInput() ?>
                    </div> 
                </div>
            </div>
            
            <div class="panel panel-default panel-d" style="margin-left: 15px">
                <div class="panel-body panel-dates">

                    <div class="col-lg-12">
                        <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos del Vehículo</div>
                    </div>         
                            
                    <div class="col-lg-6">
                        <?= $form->field($coches, 'marca')->textInput() ?>
                    </div>                   

                    <div class="col-lg-6">
                        <?= $form->field($coches, 'matricula')->textInput() ?>
                    </div>                 

                </div>
            </div>
        </div>

        <!-- Lista de Precios Escondidad -->
        <?php 
            $cant = count($precio_diario); $num = 1;
            for ($i=0; $i < $cant ; $i++) { ?>
            <div class="col-lg-2">
                <input class="form-control" style="margin-bottom: 20px" type="hidden" id="precio-diario<?= $num ?>" value="<?= $precio_diario[$i]['precio'] ?>">
            </div>            
        <?php $num++; } ?>
        <!-- Fin de Lista de Precios Escondidad -->  

        <div class="col-lg-12 pad-0" style="padding-left: 0px">
            <div class="panel panel-default panel-d">
                <div class="panel-body panel-dates">  

                    <div class="col-lg-12">
                        <div class="subtitulo-reserva" style="margin-bottom: 30px">Servicios Extras Disponibles</div>
                    </div> 
                    <div class="col-lg-7 col-xs-7 dn">
                        <div align="center" class="subtitulo-reserva sub-reserva">Descripción</div>    
                    </div>
                    <div class="col-lg-2 col-xs-2 dn">
                        <div align="center" class="subtitulo-reserva sub-reserva">Precio</div>
                    </div>
                    <div class="col-lg-1 col-xs-1 dn">
                        <div align="center" class="subtitulo-reserva sub-reserva">Cant</div>
                    </div>              
                    <div class="col-lg-2 col-xs-2 dn">
                        <div align="center" class="subtitulo-reserva sub-reserva na">Total</div>
                    </div>

                    <?php 
                        foreach ($servicios as $s) {
                        $service = array($s->id => $s->nombre_servicio);
                    ?> 

                    <div class="col-lg-7" style="margin-top: 20px">
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

                    <?= $form->field($model, 'tipo_servicio')->hiddenInput(['id' => 'tipo_servicio'.$s->id, 'value'=> $s->fijo, 'name' => 'tipo_servicio'.$s->id])->label(false) ?>

                    <div class="col-lg-2" style="margin-top:10px">
                        <?= $form->field($model, 'precio_unitario', [
                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                        ])->textInput(['id' => 'precio_unitario'.$s->id, 'readonly' =>true, 'value' => $s->costo, 'class'=>'form-control cantidad', 'name' => 'precio_unitario'.$s->id]) ?> 
                    </div> 

                    <div class="col-lg-1" style="margin-top:10px">
                        <?= $form->field($model, 'cantidad', [
                            'template' => '<div class="input-group costos-facturas">{input}
                            </div>{error}{hint}'
                        ])->textInput(['id' => 'cantidad'.$s->id, 'type' => 'number', 'min'=>1, 'readonly' =>true, 'class'=>'form-control cantidad', 'style' => 'border-radius:8px !important; text-align:center !important', 'name' => 'cantidad'.$s->id]) ?> 
                    </div> 

                    <div class="col-lg-2" style="margin-top:10px">
                        <?= $form->field($model, 'precio_total', [
                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                        ])->textInput(['id' => 'precio_total'.$s->id, 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'precio_total'.$s->id]) ?> 
                    </div>        

                    <div class="col-lg-12"></div>                       

                    <?php } ?>
                </div>
            </div>
        </div> 

        <div class="col-lg-12" style="padding-left: 0px">
            <div class="panel panel-default panel-d">
                <div class="panel-body panel-dates"> 

                    <div class="col-lg-12">
                        <div class="subtitulo-reserva" style="margin-bottom: 30px">Total Costos de Reserva</div>
                    </div> 
                    <div class="col-lg-1 col-xs-1">
                        <div align="center" class="subtitulo-reserva sub-reserva">Items</div>
                    </div>        
                    <div class="col-lg-9 col-xs-9">
                        <div align="center" class="subtitulo-reserva sub-reserva">Descripción</div>    
                    </div>            
                    <div class="col-lg-2 col-xs-2">
                        <div align="center" class="subtitulo-reserva sub-reserva na">Total</div>
                    </div>  

                    <div class="col-lg-1 s" style="margin-top: 20px">
                        <label class="num">1</label>
                    </div>

                    <div class="col-lg-6" style="margin-top: 18px">
                        <label class="service-reserva"><?= $precio_diario[0]['nombre_servicio'] ?></label>
                        <div class="des-reserva-ind" style="margin-left: 0px"><?= $precio_diario[0]['descripcion'] ?></div>
                    </div>

                    <input type="hidden" id="servicio_basico" name="servicio_basico" value="<?= $precio_diario[0]['costo'] ?>"> 
                    <input type="hidden" id="cant_basico" name="cant_basico" value="<?= $cant_dias ?>">
                    <input type="hidden" class="btn-success" id="actualiza_montos" name="actualiza_montos">                    

                    <div class="col-lg-3"></div>

                    <div class="col-lg-2" style="margin-top:10px">
                        <?= $form->field($model, 'costo_servicios', [
                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                        ])->textInput(['class'=>'form-control cantidad']) ?> 
                    </div> 

                    <div class="col-lg-12"><br></div>

                    <div class="col-lg-1 dn">
                        <label class="num">2</label>
                    </div>


                    <div class="col-lg-6">
                        <label class="service-reserva">Servicios Extras Seleccionados</label>
                        <div class="des-reserva-ind" style="margin-left: 0px">Otros servicios extras</div>
                    </div> 

                    <div class="col-lg-3"></div>

                    <input type="hidden" id="cant_extras" name="cant_extras" value="0">       

                    <div class="col-lg-2" style="margin-top:-8px">
                        <?= $form->field($model, 'costo_servicios_extra', [
                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                        ])->textInput(['class'=>'form-control cantidad']) ?> 
                    </div>                    
                </div>
            </div>
        </div> 

        <div class="col-lg-12 col-xs-12" style="padding-left: 0px">
            <div class="panel panel-default panel-d d2" style="margin-bottom: 0px">
                <div class="panel-body panel-dates otherp">
                    <div class="col-lg-3">
                        <div style="padding-top: 10px;">
                            <div class="col-lg-12">
                                <?= $form->field($model, 'id_tipo_pago')->widget(Select2::classname(), [
                                    'data' => $tipos_pago,
                                    'options' => ['placeholder' => 'Selecccione la Forma de Pago'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                ]); ?>
                            </div>                
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <input type="hidden" name="medio_reserva" value="<?= $medio ?>">
                    </div>

                    <div class="col-lg-2">
                        <input type="hidden" name="agencia" value="<?= $agencia ?>">
                    </div>                    

                    <div class="col-lg-3 col-xs-12">
                        <div id="subtotal-factura" class="totales-facturas hide">Subtotal</div>
                        <div id="impuestos-factura" class="totales-facturas hide">Impuestos</div>
                        <div id="total-factura" class="totales-facturas" style="margin-top: 34px">Monto Total</div>
                    </div>

                    <div class="col-lg-2" style="margin-top:15px">
                        <input type="hidden" name="monto_factura" value="<?= $model->monto_factura ?>">
                        <input type="hidden" name="monto_impuestos" value="<?= $model->monto_impuestos ?>">

                        <?= $form->field($model, 'monto_total', [
                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                        ])->textInput(['maxlength' => true, 'readonly' => true, 'value' => '0.00']) ?>
                    </div>

                    <div class="col-lg-12"><hr class="linea"></div>

                    <div class="col-lg-8 col-xs-8"></div>
                    <div align="right" class="col-lg-2" style="margin-top: -10px; margin-bottom: 20px">
                        <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-warning btn-block']) ?>
                    </div>
                    <div align="right" class="col-lg-2" style="margin-top: -10px; margin-bottom: 20px">
                        <div class="form-group">
                            <?= Html::submitButton('Guardar Datos', ['class' => 'btn btn-success btn-block']) ?>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>             
</div>


<?php   
    $this->registerJs(" 

    $( document ).ready(function() {

      });             


        $('#reservas-costo_servicios_extra').change(function() {
            costo = parseFloat($('#reservas-costo_servicios').val());
            extras = parseFloat($('#reservas-costo_servicios_extra').val());
            resultado = costo + extras;
            $('#reservas-costo_servicios').val(costo.toFixed(2));
            $('#reservas-monto_total').val(resultado.toFixed(2));
            $('#reservas-costo_servicios_extra').val(extras.toFixed(2));
        });

        $('#reservas-costo_servicios').change(function() {
            costo = parseFloat($('#reservas-costo_servicios').val());
            extras = parseFloat($('#reservas-costo_servicios_extra').val());
            resultado = costo + extras;
            $('#reservas-costo_servicios').val(costo.toFixed(2));
            $('#reservas-monto_total').val(resultado.toFixed(2));
        });

        $('#checkAll').change(function() {
            $('.select:checked').each(function() {
                $('.servicios').click();
            }); 
            $('.select:checkbox:not(:checked)').each(function() { 
                $('.servicios').click();           
            });
        });

        $('.servicios').change(function() {

            $('.servicios:checked').each(function() {
                id = $(this).val();
                tipo_servicio = $('#tipo_servicio'+ id).val();
                precio = $('#precio_unitario'+ id).val();
                cant = $('#cantidad'+ id).val();               
                if (tipo_servicio == 1) {
                  $('#cantidad'+ id).prop('readonly',true);
                }
                if (cant == 0) {
                  $('#cantidad'+ id).val(1);
                  precio = 0.00;
                  $('#precio_total'+ id).val(precio);
                } 
                $('.totales-facturas').click();
            });        

            id = $(this).val();
            tipo_servicio = $('#tipo_servicio'+ id).val();

            $('.servicios:checkbox:not(:checked)').each(function() {
                id = $(this).val();
                $('#cantidad'+ id).val(0);
                $('#cantidad'+ id).prop('readonly',true);
                $('#precio_total'+ id).val(0);
                $('.totales-facturas').click();
            });           
        }) 

        $('#subtotal-factura').click(function() {
            monto_subtotal = 0;
            imp = $('#reservas-iva').val();
            
            $('.servicios:checked').each(function() {
                id = $(this).val();
                precio = $('#precio_total'+ id).val();
                monto_subtotal = parseFloat(monto_subtotal) + parseFloat(precio);
            });           

            $('#reservas-costo_servicios_extra').val(monto_subtotal.toFixed(2));
            costo_servicios = $('#reservas-costo_servicios').val();
            stotal_reserva = monto_subtotal + parseFloat(costo_servicios);



            $('#reservas-monto_factura').val(stotal_reserva.toFixed(2));
            impuestos = 0;
            $('#reservas-monto_impuestos').val(impuestos.toFixed(2));
            total_monto = parseFloat(stotal_reserva) + parseFloat(impuestos);
            $('#reservas-monto_total').val(total_monto.toFixed(2));
                        
        });              






    ");
?>