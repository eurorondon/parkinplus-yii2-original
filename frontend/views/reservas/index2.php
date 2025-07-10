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

$datos1 = explode(" ", $entrada);
$fecha_e = $datos1[0];
$hora_e = $datos1[1];

$datos2 = explode(" ", $salida);
$fecha_s = $datos2[0];
$hora_s = $datos2[1];

$model->fecha_entrada = $fecha_e;
$model->hora_entrada = $hora_e;
$model->fecha_salida = $fecha_s;
$model->hora_salida = $hora_s;

$fecha1= new DateTime($fecha_e);
$fecha2= new DateTime($fecha_s);
$dias = $fecha1->diff($fecha2);

$cant_dias = $dias->days;

$this->title = Yii::$app->name.' | Nueva Reserva';
$this->params['breadcrumbs'][] = 'Nueva Reserva';

?>

<div class="reservas-form">
    <div class="title-margin">
        <h3 style="display: inline">Nueva Reserva</h3>
        <span class="datos-factura">Reserva N° : <?= $proxima_reserva ?></span>
    </div>

    <div class="text-index">    

        <?php $form = ActiveForm::begin(); ?>
        <div class="row">

            <?= $form->field($model, 'nro_reserva')->hiddenInput(['value'=> $proxima_reserva])->label(false) ?>
            <?= $form->field($model, 'iva')->hiddenInput(['value'=> $iva])->label(false) ?>
            <?= $form->field($model, 'dias')->hiddenInput(['value'=> $cant_dias])->label(false) ?>

            <div class="col-lg-6">
                <div class="subtitulo-reserva" style="margin-bottom: 15px">Datos de Entrada</div>
                <div class="col-lg-5" style="padding-left: 0px">
                    <?= $form->field($model, 'fecha_entrada')->textInput(['readonly'=> true]) ?>
                </div>
                <div class="col-lg-1"></div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'hora_entrada')->textInput(['readonly'=> true]) ?>
                </div>            
            </div>

            <div class="col-lg-6">
                <div class="subtitulo-reserva" style="margin-bottom: 15px">Datos de Salida</div>
                <div class="col-lg-5" style="padding-left: 0px">
                    <?= $form->field($model, 'fecha_salida')->textInput(['readonly'=> true]) ?>
                </div> 
                <div class="col-lg-1"></div>
                <div class="col-lg-3">
                    <?= $form->field($model, 'hora_salida')->textInput(['readonly'=> true]) ?>
                </div>              
            </div>
            
            <div class="col-lg-12"><hr style="margin-top: 25px;"></div>

            <div class="col-lg-12 space"><div class="subtitulo-reserva">Datos del Cliente</div></div> 

            <div class="col-lg-6">
                <?= $form->field($modelC, 'nombre_completo')->textInput(['maxlength'=> true, 'autocomplete' => 'off']) ?>
            </div>            

            <div class="col-lg-6">
                <?= $form->field($modelC, 'correo')->textInput(['maxlength'=> true, 'autocomplete' => 'off']) ?>
            </div> 

            <div class="col-lg-12"><br></div>        

            <div class="col-lg-3">
                <?= $form->field($modelC, 'tipo_documento')->widget(Select2::classname(), [
                    'data' => $tipo_documento,
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>

            <div class="col-lg-3">
                <?= $form->field($modelC, 'nro_documento')->textInput(['maxlength'=> true, 'autocomplete' => 'off']) ?>
            </div>

            <div class="col-lg-2">
                <?= $form->field($modelC, 'movil')->textInput(['maxlength'=> true, 'autocomplete' => 'off']) ?>
            </div>          

            <div class="col-lg-4"></div>

            <div class="col-lg-12"><hr style="margin-top: 35px;"></div>

            <div class="col-lg-12 space"><div class="subtitulo-reserva">Datos del Vehículo</div></div>  


            <div class="col-lg-2">
                <?= $form->field($modelV, 'matricula')->textInput(['maxlength'=> true]) ?>
            </div>

            <div class="col-lg-3">
                <?= $form->field($modelV, 'marca')->textInput(['maxlength'=> true]) ?>
            </div>

            <div class="col-lg-2">
                <?= $form->field($modelV, 'modelo')->textInput(['maxlength'=> true]) ?>
            </div>                      

            <div class="col-lg-2">
                <?= $form->field($modelV, 'color')->widget(ColorInput::classname(), [
                    'options' => [
                        'class' => 'color',
                    ],
                    'pluginOptions' => [
                        'showInput' => true,
                        'showInitial' => true,
                        'showPalette' => true,
                        'showPaletteOnly' => true,
                        'showSelectionPalette' => true,
                        'showAlpha' => false,
                        'allowEmpty' => false,
                        'preferredFormat' => 'name',

                    ],                
                ]); ?>
            </div>                

            <div class="col-lg-3"></div>

            <div class="col-lg-12"><hr style="margin-top: 35px;"></div>

            <?php 
            $cant = count($precio_diario); $num = 1;
            for ($i=0; $i < $cant ; $i++) { ?>
                <div class="col-lg-2">
                    <input class="form-control" style="margin-bottom: 20px" type="hidden" id="precio-diario<?= $num ?>" value="<?= $precio_diario[$i]['precio'] ?>">
                </div>            
                <?php $num++; } ?>

                <div class="col-lg-12 space"><div class="subtitulo-reserva">Información de Reserva</div></div>        

                <div class="col-lg-3">
                    <?= $form->field($model, 'terminal_entrada')->widget(Select2::classname(), [
                        'data' => $terminales,
                        'options' => ['placeholder' => 'Selecccione Terminal'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>        
                </div>

                <div class="col-lg-3">
                    <?= $form->field($model, 'terminal_salida')->widget(Select2::classname(), [
                        'data' => $terminales,
                        'options' => ['placeholder' => 'Selecccione Terminal'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>            
                </div>

                <div class="col-lg-3">
                    <?= $form->field($model, 'nro_vuelo_regreso')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-lg-3">
                    <?= $form->field($model, 'ciudad_procedencia')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-lg-12"><br></div>

                <div class="col-lg-3">
                    <?= $form->field($model, 'factura_equipaje')->radioList([1 => 'SI', 0 => 'NO'])->label('Facturará Equipaje ?'); ?>
                    <?= $form->field($model, 'factura')->checkbox(['onclick' => 'muestra("facturacion")', 'uncheck' => '0', 'value' => '1']) ?>            
                </div>

                <div class="col-lg-9">
                    <?= $form->field($model, 'observaciones')->textarea(['rows' => '2']) ?>
                </div>                 

                <div id="facturacion">
                    <div class="col-lg-12"><hr style="margin-top: 35px;"></div>

                    <div class="col-lg-12 space"><div class="subtitulo-reserva">Información de Facturación</div></div>                        
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

                <div class="col-lg-12"><hr style="margin-top: 35px;"></div>

                <div class="col-lg-7 space" style="margin-bottom: 0px;">
                    <div class="subtitulo-reserva">Servicios Extras Disponibles</div>
                    <label class="select-extras"><input class="select" type="checkbox" id="checkAll">Seleccionar Todos</label>
                </div>  

                <div align="center" class="col-lg-2 space">
                    <div class="subtitulo-reserva">Precio Unitario</div>
                </div> 

                <div align="center" class="col-lg-1 space">
                    <div class="subtitulo-reserva">Cant</div>
                </div>

                <div align="center" class="col-lg-2 space">
                    <div class="subtitulo-reserva">Total</div>
                </div>

                <?php 
                foreach ($servicios as $s) {
                    $service = array($s->id => $s->nombre_servicio);
                    ?> 

                    <div class="col-lg-7">
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

                    <div class="col-lg-2" style="margin-top:-8px">
                        <?= $form->field($model, 'precio_unitario', [
                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                        ])->textInput(['id' => 'precio_unitario'.$s->id, 'readonly' =>true, 'value' => $s->costo, 'class'=>'form-control cantidad', 'name' => 'precio_unitario'.$s->id]) ?> 
                    </div> 

                    <div class="col-lg-1" style="margin-top:-8px">
                        <?= $form->field($model, 'cantidad', [
                            'template' => '<div class="input-group costos-facturas">{input}
                            </div>{error}{hint}'
                        ])->textInput(['id' => 'cantidad'.$s->id, 'type' => 'number', 'min'=>1, 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'cantidad'.$s->id]) ?> 
                    </div> 

                    <div class="col-lg-2" style="margin-top:-8px">
                        <?= $form->field($model, 'precio_total', [
                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                        ])->textInput(['id' => 'precio_total'.$s->id, 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'precio_total'.$s->id]) ?> 
                    </div>        

                    <div class="col-lg-12"></div>                       

                <?php } ?>        

                <div class="col-lg-12"><hr style="margin-top: 35px;"></div>

                <div class="col-lg-7 space" style="margin-bottom: 0px;">
                    <div class="subtitulo-reserva">Total Costos de Reserva</div>
                </div>

                <div align="center" class="col-lg-2 space">
                    <div class="subtitulo-reserva">Precio Unitario</div>
                </div>            
                <div align="center" class="col-lg-1 space">
                    <div class="subtitulo-reserva">Cant</div>
                </div>
                <div align="center" class="col-lg-2 space">
                    <div class="subtitulo-reserva">Total</div>
                </div>   

                <div class="col-lg-7">
                    <label class="num">1</label><label class="service-reserva"><?= $precio_diario[0]['nombre_servicio'] ?></label>
                    <div class="des-reserva-ind"><?= $precio_diario[0]['descripcion'] ?></div>
                </div>

                <div class="col-lg-2" style="margin-top:-8px">
                    <?= $form->field($model, 'servicio_basico', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        <span class="input-group-addon">€</span></div>{error}{hint}'
                    ])->textInput(['id' => 'servicio_basico', 'readonly' =>true, 'value' => $precio_diario[0]['costo'], 'class'=>'form-control cantidad', 'name' => 'servicio_basico']) ?> 
                </div> 

                <div class="col-lg-1" style="margin-top:-8px">
                    <?= $form->field($model, 'cant_basico', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        </div>{error}{hint}'
                    ])->textInput(['id' => 'cant_basico', 'type' => 'number', 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'cant_basico', 'value' => $cant_dias]) ?> 
                </div>

                <div class="col-lg-2" style="margin-top:-8px">
                    <?= $form->field($model, 'costo_servicios', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        <span class="input-group-addon">€</span></div>{error}{hint}'
                    ])->textInput(['readonly' =>true, 'class'=>'form-control cantidad', 'value' => '0.00']) ?> 
                </div>                  

                <div class="col-lg-12"><br></div>

                <div class="col-lg-7">
                    <label class="num">2</label><label class="service-reserva"><?= $seguro[0]->nombre_servicio ?></label>
                    <div class="des-reserva-ind"><?= $seguro[0]->descripcion ?></div>
                </div> 

                <div class="col-lg-2" style="margin-top:-8px">
                    <?= $form->field($model, 'seguro', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        <span class="input-group-addon">€</span></div>{error}{hint}'
                    ])->textInput(['id' => 'seguro', 'readonly' =>true, 'value' => $seguro[0]->costo, 'class'=>'form-control cantidad', 'name' => 'seguro']) ?> 
                </div> 

                <div class="col-lg-1" style="margin-top:-8px">
                    <?= $form->field($model, 'cant_seguro', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        </div>{error}{hint}'
                    ])->textInput(['id' => 'cant_seguro', 'type' => 'number', 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'cant_seguro', 'value' => 1]) ?> 
                </div>

                <div class="col-lg-2" style="margin-top:-8px">
                    <?= $form->field($model, 'total_seguro', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        <span class="input-group-addon">€</span></div>{error}{hint}'
                    ])->textInput(['id' => 'total_seguro', 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'total_seguro', 'value' => $seguro[0]->costo]) ?> 
                </div>   

                <div class="col-lg-12"><br></div>                  

                <div class="col-lg-7">
                    <label class="num">3</label><label class="service-reserva">Servicios Extras Seleccionados</label>
                    <div class="des-reserva-ind">Otros servicios extras</div>
                </div> 

                <div class="col-lg-2" style="margin-top:-8px">
                    <?= $form->field($model, 'servicios_extras', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        <span class="input-group-addon">€</span></div>{error}{hint}'
                    ])->textInput(['id' => 'servicios_extras', 'readonly' =>true, 'value' => 'N/A', 'class'=>'form-control cantidad', 'name' => 'servicios_extras']) ?> 
                </div> 

                <div class="col-lg-1" style="margin-top:-8px">
                    <?= $form->field($model, 'cant_extras', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        </div>{error}{hint}'
                    ])->textInput(['id' => 'cant_extras', 'type' => 'number', 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'cant_extras', 'value' => 0]) ?> 
                </div>

                <div class="col-lg-2" style="margin-top:-8px">
                    <?= $form->field($model, 'costo_servicios_extra', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        <span class="input-group-addon">€</span></div>{error}{hint}'
                    ])->textInput(['readonly' =>true, 'class'=>'form-control cantidad', 'value' => '0.00']) ?> 
                </div>  

                <div class="col-lg-12"><hr style="margin-top: 40px"></div> 

                <div class="col-lg-8">
                    <div class="observacion" style="padding-top: 25px;">
                        <div class="col-lg-6">
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
                    <div id="subtotal-factura" class="totales-facturas">Subtotal</div>
                    <div id="impuestos-factura" class="totales-facturas">Impuestos</div>
                    <div id="total-factura" class="totales-facturas">Monto Total</div>
                </div> 

                <div class="col-lg-2">
                    <?= $form->field($model, 'monto_factura', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        <span class="input-group-addon">€</span></div>{error}{hint}'
                    ])->textInput(['maxlength' => true, 'readonly' => true, 'value' => '0.00']) ?> 

                    <?= $form->field($model, 'monto_impuestos', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        <span class="input-group-addon">€</span></div>{error}{hint}'
                    ])->textInput(['maxlength' => true, 'readonly' => true, 'value' => '0.00']) ?>

                    <?= $form->field($model, 'monto_total', [
                        'template' => '<div class="input-group costos-facturas">{input}
                        <span class="input-group-addon">€</span></div>{error}{hint}'
                    ])->textInput(['maxlength' => true, 'readonly' => true, 'value' => '0.00']) ?>
                </div> 

                <div class="col-lg-12"><hr style="margin-bottom: 5px;"></div>

                <div align="right" class="col-lg-10" style="margin-top: 25px">
                    <?= Html::a('Cancelar', ['/site/index'], ['class' => 'btn btn-warning']) ?>
                </div>
                <div align="right" class="col-lg-2" style="margin-top: 25px">
                    <div class="form-group">
                        <?= Html::submitButton('Guardar Datos', ['class' => 'btn btn-success']) ?>
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
                                                var impuestos = stotal_reserva * imp;
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