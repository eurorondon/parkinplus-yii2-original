<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Facturas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="facturas-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">

        <?= $form->field($model, 'serie')->hiddenInput(['value'=> $serie])->label(false) ?>
        <?= $form->field($model, 'nro_factura')->hiddenInput(['value'=> $proxima_factura])->label(false) ?>
        <?= $form->field($model, 'iva')->hiddenInput(['value'=> $iva])->label(false) ?>

        <div class="col-lg-12 subtitulo-reserva" style="margin-top: 0px; margin-bottom: 20px ">Datos del Cliente</div><br>

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

        <div class="col-lg-12"><hr style="margin-top: 35px; margin-bottom: 35px"></div>

        <input type="hidden" name="cuota_dia" id="cuota_dia" value="<?= $agregado ?>">

        <?php 
            $cant = count($precio_diario); $num = 1;
            for ($i=0; $i < $cant ; $i++) { 
                //$price = $precio_diario[$i]['precio'] / ($iva + 1);
                //$precio[$i] = round($price,3); 
                ?>
            <div class="hide col-lg-2">
                <input class="form-control" style="margin-bottom: 20px" type="text" id="precio-diario<?= $num ?>" value="<?= $precio_diario[$i]['precio'] ?>">
            </div>            
        <?php $num++; } ?>

        <div class="col-lg-7 space" style="margin-bottom: 0px;">
            <div class="subtitulo-reserva">Servicios Disponibles</div>            
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
            $costoservicio = $s->costo;
            $s->costo = round($s->costo,2);
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
            ])->textInput(['id' => 'cantidad'.$s->id, 'value' => 0, 'type' => 'number', 'min'=>1, 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'cantidad'.$s->id]) ?> 
        </div> 

        <div class="col-lg-2" style="margin-top:-8px">
            <?= $form->field($model, 'precio_total', [
                'template' => '<div class="input-group costos-facturas">{input}
                <span class="input-group-addon">€</span></div>{error}{hint}'
            ])->textInput(['id' => 'precio_total'.$s->id, 'readonly' =>true, 'class'=>'form-control cantidad', 'name' => 'precio_total'.$s->id]) ?> 
        </div>        

        <div class="col-lg-12"></div>                       

        <?php } ?>

        <div class="col-lg-12"><hr></div>

        <div class="col-lg-12 subtitulo-reserva" style="margin-top: 0px; margin-bottom: 20px ">Otros Conceptos</div><br>

        <div class="col-lg-7">
            <?= $form->field($conceptos, 'descripcion')->textarea(['rows' => '2'])->label(false) ?>
        </div>
        <div class="col-lg-2">
            <?= $form->field($conceptos, 'punitario', [
                'template' => '<div class="input-group costos-facturas">{input}
                <span class="input-group-addon">€</span></div>{error}{hint}'
            ])->textInput(['id' => 'concepto_punitario', 'class'=>'form-control cantidad', 'name' => 'concepto_punitario']) ?>
        </div>

        <div class="col-lg-1">
            <?= $form->field($conceptos, 'cantidad', [
                'template' => '<div class="input-group costos-facturas">{input}
                </div>{error}{hint}'
            ])->textInput(['id' => 'concepto_cantidad', 'type' => 'number', 'min'=>1, 'class'=>'form-control cantidad', 'name' => 'concepto_cantidad']) ?> 
        </div>

        <div class="col-lg-2">
            <?= $form->field($conceptos, 'ptotal', [
                'template' => '<div class="input-group costos-facturas">{input}
                <span class="input-group-addon">€</span></div>{error}{hint}'
            ])->textInput(['id' => 'concepto_ptotal', 'class'=>'form-control cantidad', 'name' => 'concepto_ptotal']) ?>            
        </div>

        <div class="col-lg-12"><hr></div> 

        <div class="col-lg-7">
            <div style="padding-top: 5px;">
                <div class="col-lg-7">
                    <?= $form->field($model, 'id_tipo_pago')->widget(Select2::classname(), [
                        'data' => $tipos_pago,
                        'options' => ['placeholder' => 'Selecccione la Forma de Pago'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>                
            </div>
            <div style="margin-top: 35px;">
                <div class="col-lg-12">
                    <?= $form->field($model, 'observacion')->textarea(['rows' => '2']) ?>
                </div>                
            </div>            
        </div>

        <div class="col-lg-3">
            <div id="subtotal-factura" class="totales-facturas">Subtotal</div>
            <div id="impuestos-factura" class="totales-facturas">I.V.A (21%)</div>
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
            <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-warning']) ?>
        </div>
        <div align="right" class="col-lg-2" style="margin-top: 25px">
            <div class="form-group">
                <?= Html::submitButton('Guardar Datos', ['class' => 'btn btn-success']) ?>
            </div>
        </div> 
    </div>       
    <?php ActiveForm::end(); ?>

</div>

<?php   
    $this->registerJs(" 

        $('#concepto_punitario').change(function() {
            preu = $('#concepto_punitario').val()
            $('#concepto_cantidad').val(1)
            $('#concepto_ptotal').val(preu)
           
            imp = $('#facturas-iva').val();
           
            var sub_total = parseFloat($('#concepto_ptotal').val() / imp);
            var valorimpuesto = parseFloat($('#concepto_ptotal').val() - sub_total.toFixed(2) );

            $('#facturas-monto_impuestos').val(valorimpuesto.toFixed(2))
            $('#facturas-monto_factura').val(sub_total.toFixed(2))
            
            mtotal = sub_total + valorimpuesto;
            
             
           $('#facturas-monto_total').val(mtotal.toFixed(2));

        })

        $('#concepto_cantidad').change(function() {
            pu = $('#concepto_punitario').val()
            c = $('#concepto_cantidad').val()
            concepto_total = (pu*c) 
            $('#concepto_ptotal').val(concepto_total.toFixed(2))
            imp = $('#facturas-iva').val();

            var sub_total = parseFloat($('#concepto_ptotal').val() / imp);
            valorimpuesto = parseFloat($('#concepto_ptotal').val() - sub_total.toFixed(2) );

            $('#facturas-monto_impuestos').val(valorimpuesto.toFixed(2))
            $('#facturas-monto_factura').val(sub_total.toFixed(2))
            var montot = sub_total + valorimpuesto;
            $('#facturas-monto_total').val(montot.toFixed(2));

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
                
                if (tipo_servicio == 0) {

                    var precio = $('#precio_unitario'+ id).val();
                    
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
                        var cuota_dia = $('#cuota_dia').val();
                        var imp = $('#facturas-iva').val();
                        var cuotaiva = parseFloat(cuota_dia) / (parseFloat(imp) + 1)
                        var nueva_cuota = cuotaiva.toFixed(3)
                        alert(nueva_cuota)
                        var precio_relativo = parseFloat(precio30);
                        var total = precio_relativo + (cant_dias * nueva_cuota); 
                    }

                    $('#precio_total'+ id).val(total.toFixed(2));
                    $('.totales-facturas').click(); 

                } else {
                    var precio = $('#precio_unitario'+ id).val();
                    var precio_new = parseFloat(precio) * cant;
                    $('#precio_total'+ id).val(precio_new.toFixed(3)); 
                    $('.totales-facturas').click();                
                }                     
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
            var imp = $('#facturas-iva').val();
            $('.servicios:checked').each(function() {
                var id = $(this).val();
                var precio = $('#precio_total'+ id).val();
                monto_subtotal = parseFloat(monto_subtotal) + parseFloat(precio);
            });             


            var impuestos = parseFloat(monto_subtotal - (monto_subtotal / imp));
            var sub_total = parseFloat(monto_subtotal.toFixed(2) - impuestos.toFixed(2));
            $('#facturas-monto_factura').val(sub_total.toFixed(2));
            
            /*$('#facturas-monto_factura').val(monto_subtotal.toFixed(2));
            var impuestos = monto_subtotal * imp;
            $('#facturas-monto_impuestos').val(impuestos.toFixed(3));*/
            
            /*var impuestos = parseFloat( monto_subtotal - (monto_subtotal / imp));*/
            $('#facturas-monto_impuestos').val(impuestos.toFixed(2));
            
            var total_monto = parseFloat(sub_total) + parseFloat(impuestos);
            $('#facturas-monto_total').val(total_monto.toFixed(2));


        }); 
 
    ");
?>


    <div class="hide">

        <div class="col-lg-1">
            <?= $form->field($model, 'serie')->textInput(['value' => $serie, 'readonly' => true, 'style' => 'text-align: center']) ?>
        </div> 
        <div class="col-lg-2">
            <?= $form->field($model, 'nro_factura')->textInput(['value' => $proxima_factura, 'readonly' => true]) ?>
        </div>


        <?= $form->field($model, 'monto_factura')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'monto_impuestos')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'monto_total')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'created_by')->textInput() ?>

        <?= $form->field($model, 'updated_at')->textInput() ?>

        <?= $form->field($model, 'updated_by')->textInput() ?>
    </div>