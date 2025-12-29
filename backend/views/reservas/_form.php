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

$Url = Url::to(['reservas/modifica']);

$model->fecha_entrada = $entrada;
$model->hora_entrada = $hora_e;

$model->fecha_salida = $salida;
$model->hora_salida = $hora_s;

$model->medio_reserva = $medio;
$model->agencia = $agencia;

$fecha_s = $model->fecha_salida;
$hora_s = $model->hora_salida;

$day1 = $entrada . ' ' . $hora_e;
$day1 = strtotime($day1);
$day2 = $salida . ' ' . $hora_s;
$day2 = strtotime($day2);

$diffHours = round(($day2 - $day1) / 3600);

$dias = $diffHours / 24;

$partes = explode('.', $dias);

if (count($partes) == 1) {
    $cant_dias = $dias;
} else {
    $cant_dias = intval($dias) + 1;
}



/*

$fecha1= new DateTime($entrada);
$fecha2= new DateTime($salida);

$dias = $fecha1->diff($fecha2);

$cant_dias = $dias->days;

if (($cant_dias == 0) && ($model->hora_salida > $model->hora_entrada)) {
    $cant_dias = 1;
}
*/

$serviciosReservaMap = $serviciosReservaMap ?? [];
$formatCurrency = static function ($value) {
    return number_format((float) $value, 2, '.', '');
};

$servicioBasicoId = $precio_diario[0]['id'] ?? null;
$servicioBasicoDatos = ($servicioBasicoId !== null && isset($serviciosReservaMap[$servicioBasicoId])) ? $serviciosReservaMap[$servicioBasicoId] : null;
$valorServicioBasico = $servicioBasicoDatos ? $formatCurrency($servicioBasicoDatos['precio_total']) : $formatCurrency($model->costo_servicios);
$cantidadServicioBasico = $servicioBasicoDatos ? $servicioBasicoDatos['cantidad'] : $cant_dias;

$valorSeguroPrincipal = isset($seguro[0]) ? $formatCurrency($seguro[0]->costo) : '0.00';
$cantidadSeguroPrincipal = 1;
if (isset($seguro[0])) {
    $seguroPrincipalId = $seguro[0]->id;
    if (isset($serviciosReservaMap[$seguroPrincipalId])) {
        $valorSeguroPrincipal = $formatCurrency($serviciosReservaMap[$seguroPrincipalId]['precio_total']);
        $cantidadSeguroPrincipal = $serviciosReservaMap[$seguroPrincipalId]['cantidad'];
    }
}

$valorTechado = isset($seguro[1]) ? $formatCurrency($seguro[1]->costo) : '0.00';
if (isset($seguro[1])) {
    $seguroTechadoId = $seguro[1]->id;
    if (isset($serviciosReservaMap[$seguroTechadoId])) {
        $valorTechado = $formatCurrency($serviciosReservaMap[$seguroTechadoId]['precio_total']);
    }
}

$totalServiciosExtras = 0.0;
$cantidadServiciosExtras = 0;
foreach ($servicios as $servicioExtra) {
    if (isset($serviciosReservaMap[$servicioExtra->id])) {
        $totalServiciosExtras += (float) $serviciosReservaMap[$servicioExtra->id]['precio_total'];
        $cantidadServiciosExtras += (int) $serviciosReservaMap[$servicioExtra->id]['cantidad'];
    }
}
$costoServiciosExtra = $formatCurrency($totalServiciosExtras);

$valorNocturno = isset($nocturno[0]['costo']) ? $formatCurrency($nocturno[0]['costo']) : '0.00';
$nocturnoServicioId = null;
if (!empty($nocturno[0]['id'])) {
    $nocturnoIdParts = explode('-', $nocturno[0]['id']);
    $nocturnoServicioId = isset($nocturnoIdParts[0]) ? (int) $nocturnoIdParts[0] : null;
    if ($nocturnoServicioId && isset($serviciosReservaMap[$nocturnoServicioId])) {
        $valorNocturno = $formatCurrency($serviciosReservaMap[$nocturnoServicioId]['precio_total']);
    }
}

$tipo_documento = [
    'NIF' => 'NIF',
    'NIE' => 'NIE',
    'Pasaporte' => 'Pasaporte'
];

?>

<div class="reservas-form">
    <div class="panel panel-default panel-index">
        <?php if ($model->isNewRecord) { ?>
            <div class="panel-heading caja-title">Nueva Reserva</div>
        <?php } else { ?>
            <div class="panel-heading caja-title">Modificar Reserva</div>
        <?php } ?>
        <div class="panel-body gs1">
            <div class="row">
                <div class="title-margin-new">
                    <input type="hidden" name="precio_dia" id="precio_dia" value="<?= $precio_dia ?>">
                    <?php if ($model->isNewRecord) { ?>
                        <span style="display: inline">Nueva Reserva</span>
                        <input type="hidden" name="dcto" id="dcto" value="0">
                    <?php } else { ?>
                        <span style="display: inline">Modificar Reserva</span>
                        <input type="hidden" name="dcto" id="dcto" value="<?= $descuento ?>">
                        <input type="hidden" name="techa" id="techa" value="<?= $sel_techado ?>">
                        <input type="hidden" name="res_techado" id="res_techado" value="0">
                    <?php } ?>

                    <input type="hidden" name="nueva_reserva" id="nueva_reserva"
                        value="<?= $model->isNewRecord ? 0 : 1 ?>">
                    <input type="hidden" name="monto_serv_p" id="monto_serv_p"
                        value="<?= $model->isNewRecord ? 0 : explode('-', $proxima_reserva)[1] ?>">
                    <span class="datos-factura">Reserva N° :
                        <?= $model->isNewRecord ? $proxima_reserva : explode('-', $proxima_reserva)[0] ?></span>
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
                                <div class="col-lg-12 col-xs-12">
                                    <label class="control-label">Fecha de Recogida</label>
                                    <?= $form->field($model, 'fecha_entrada')->widget(DatePicker::classname(), [
                                        'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                                        'language' => 'es',
                                        'pluginOptions' => [
                                            'orientation' => 'bottom left',
                                            'autoclose' => true,
                                            'format' => 'dd-mm-yyyy',
                                            'startDate' => date('d-m-Y'),
                                        ]
                                    ])->label(false); ?>
                                </div>
                                <div class="col-lg-8 col-xs-8">
                                    <label class="control-label">Hora</label>
                                    <?= $form->field($model, 'hora_entrada')->widget(TimePicker::classname(), [
                                        'options' => ['style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
                                        'pluginOptions' => [
                                            'showMeridian' => false,
                                        ]
                                    ])->label(false); ?>
                                </div>
                            </div>

                            <div class="col-lg-6 col-xs-12">
                                <div class="col-lg-12 col-xs-12">
                                    <div class="subtitulo-reserva toc st" style="margin-bottom: 20px">Datos de
                                        Devolución</div>
                                </div>

                                <div class="col-lg-12 col-xs-12">
                                    <label class="control-label">Fecha de Devolución</label>
                                    <?= $form->field($model, 'fecha_salida')->widget(DatePicker::classname(), [
                                        'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                                        'language' => 'es',
                                        'pluginOptions' => [
                                            'orientation' => 'bottom left',
                                            'autoclose' => true,
                                            'format' => 'dd-mm-yyyy',
                                            'startDate' => date('d-m-Y'),
                                        ]
                                    ])->label(false); ?>
                                </div>
                                <div class="col-lg-8 col-xs-8">
                                    <label class="control-label">Hora</label>
                                    <?= $form->field($model, 'hora_salida')->widget(TimePicker::classname(), [
                                        'options' => ['style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
                                        'pluginOptions' => [
                                            'showMeridian' => false,
                                        ]
                                    ])->label(false); ?>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="url" value="<?= $Url ?>">
                        <div id="msg-fechas" class="text-danger" style="display:none"></div>
                    </div>

                    <div class="panel panel-default panel-d">
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
                                <?= $form->field($model, 'ciudad_procedencia')->textInput(['maxlength' => true, 'style' => ['text-transform' => 'uppercase']]) ?>
                            </div>

                            <div class="col-lg-6">
                                <?= $form->field($model, 'observaciones')->textarea(['rows' => '2']) ?>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group field-clientes-coches">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <?= $form->field($model, 'nro_reserva')->hiddenInput(['value' => $model->isNewRecord ? $proxima_reserva : explode('-', $proxima_reserva)[0]])->label(false) ?>
                <?= $form->field($model, 'iva')->hiddenInput(['value' => $iva])->label(false) ?>
                <?= $form->field($model, 'dias')->hiddenInput(['value' => $cant_dias])->label(false) ?>

                <div class="col-lg-6 pad-0">
                    <div class="panel panel-default panel-d marl-0" style="margin-left: 15px">
                        <div class="panel-body panel-dates">
                            <div class="col-lg-12">
                                <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos del Cliente</div>
                            </div>

                            <?php if ($model->isNewRecord) { ?>

                                <div class="col-lg-6">
                                    <?= $form->field($clientes, 'movil')->textInput() ?>
                                </div>

                                <div class="col-lg-6">
                                    <?= $form->field($clientes, 'nombre_completo')->textInput(['style' => ['text-transform' => 'uppercase']]) ?>
                                </div>

                                <div class="col-lg-6">
                                    <?= $form->field($clientes, 'correo')->textInput() ?>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group clientes-coches">
                                    </div>
                                </div>

                            <?php } else { ?>

                                <div class="col-lg-6">
                                    <?= $form->field($clientes, 'nombre_completo')->textInput(['value' => $clientes->nombre_completo]) ?>
                                </div>

                                <div class="col-lg-6">
                                    <?= $form->field($clientes, 'correo')->textInput(['value' => $model->cliente->correo]) ?>
                                </div>

                                <div class="col-lg-6">
                                    <?= $form->field($clientes, 'movil')->textInput(['value' => $clientes->movil]) ?>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group clientes-coches">
                                    </div>
                                </div>

                            <?php } ?>
                        </div>
                    </div>

                    <div class="panel panel-default panel-d marl-0" style="margin-left: 15px">
                        <div class="panel-body panel-dates">

                            <div class="col-lg-12">
                                <div class="subtitulo-reserva" style="margin-bottom: 20px">Datos del Vehículo</div>
                            </div>

                            <?php if ($model->isNewRecord) { ?>
                                <div class="col-lg-6">
                                    <label class="control-label">Marca - Modelo</label>
                                    <?= $form->field($coches, 'marca')->textInput(['style' => ['text-transform' => 'uppercase']])->label(false) ?>
                                </div>

                                <div class="col-lg-6">
                                    <?= $form->field($coches, 'matricula')->textInput(['style' => ['text-transform' => 'uppercase']]) ?>
                                </div>

                            <?php } else { ?>

                                <div class="col-lg-6">
                                    <label class="control-label">Marca - Modelo</label>
                                    <?= $form->field($coches, 'marca')->textInput()->label(false) ?>
                                </div>

                                <div class="col-lg-6">
                                    <?= $form->field($coches, 'matricula')->textInput() ?>
                                </div>

                            <?php } ?>
                        </div>
                    </div>

                    <div class="panel panel-default panel-d marl-0" style="margin-left: 15px">
                        <div class="panel-body" style="padding: 0px 15px">
                            <div class="col-lg-4" style="margin-top: 15px; margin-bottom: 5px">
                                <?= $form->field($model, 'factura')->checkbox(['onclick' => 'muestra("facturacion")', 'uncheck' => '0', 'value' => '1']) ?>
                            </div>
                            <!-- <div-- class="col-lg-4" style="margin-top: 15px; margin-bottom: 5px">
                        <?= $form->field($model, 'cortesia')->checkbox(['onclick' => 'muestra("cortesia")', 'uncheck' => '0']) ?>            
                    </div-->
                            <div class="col-lg-4" style="margin-top: 15px; margin-bottom: 5px">
                                <?= $form->field($model, 'techado')->checkbox(['onclick' => 'muestra("techado")', 'uncheck' => '0']) ?>
                            </div>
                            <div class="col-lg-4" style="margin-top: 15px; margin-bottom: 5px">
                                <div class="form-group field-reservas-envio-email">
                                    <label>
                                        <input type="checkbox" id="envio_email" name="envio_email" value="0"
                                            onclick="enviar_email()">
                                        Enviar email
                                    </label>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Lista de Precios Escondidad -->
                <?php
                $cant = count($precio_diario);
                $num = 1;
                for ($i = 0; $i < $cant; $i++) { ?>
                    <div class="col-lg-2">
                        <input class="form-control" style="margin-bottom: 20px" type="hidden" id="precio-diario<?= $num ?>"
                            value="<?= $precio_diario[$i]['precio'] ?>">
                    </div>
                <?php $num++;
                } ?>
                <!-- Fin de Lista de Precios Escondidad -->
                <div id="facturacion">
                    <div class="col-lg-12" style="padding-left: 0px">
                        <div class="panel panel-default panel-d" style="margin-left: 0px">
                            <div class="panel-body panel-dates">

                                <div class="col-lg-12">
                                    <div class="subtitulo-reserva" style="margin-bottom: 20px">Información de
                                        Facturación</div>
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

                <div class="col-lg-12 pad-0" style="padding-left: 0px">
                    <div class="panel panel-default panel-d">
                        <div class="panel-body panel-dates">
                            <div class="col-lg-12">
                                <div class="subtitulo-reserva" style="margin-bottom: 30px">Servicios Extras Disponibles
                                </div>
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
                                $checked = "";

                                $servicioExtraDatos = isset($serviciosReservaMap[$s->id]) ? $serviciosReservaMap[$s->id] : null;
                                $cantidadExtra = $servicioExtraDatos ? $servicioExtraDatos['cantidad'] : 0;
                                $precioTotalExtra = $servicioExtraDatos ? $formatCurrency($servicioExtraDatos['precio_total']) : '0.00';

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

                                if ($checked) { ?>
                                    <div class="col-lg-7" style="margin-top: 20px">
                                        <?= $form->field($model, 'servicios')->checkboxList($service, [
                                            'separator' => '<br>',
                                            'itemOptions' => [
                                                'checked' => $checked,
                                                'class' => 'servicios',
                                                'precio' => $s->costo,
                                                'labelOptions' => [
                                                    'class' => 'services',
                                                ]
                                            ]

                                        ])->label(false);

                                        ?>
                                        <div class="des-reserva-ind"><?= $s->descripcion; ?></div><br>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-lg-7" style="margin-top: 20px">
                                        <?= $form->field($model, 'servicios')->checkboxList($service, [
                                            'separator' => '<br>',
                                            'itemOptions' => [
                                                'class' => 'servicios',
                                                'precio' => $s->costo,
                                                'labelOptions' => [
                                                    'class' => 'services',
                                                ]
                                            ]

                                        ])->label(false);

                                        ?>
                                        <div class="des-reserva-ind"><?= $s->descripcion; ?></div><br>
                                    </div>
                                <?php }
                                ?>

                                <?= $form->field($model, 'tipo_servicio')->hiddenInput(['id' => 'tipo_servicio' . $s->id, 'value' => $s->fijo, 'name' => 'tipo_servicio' . $s->id])->label(false) ?>

                                <div class="col-lg-2" style="margin-top:10px">
                                    <?= $form->field($model, 'precio_unitario', [
                                        'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                                    ])->textInput(['id' => 'precio_unitario' . $s->id, 'readonly' => true, 'value' => $s->costo, 'class' => 'form-control cantidad', 'name' => 'precio_unitario' . $s->id]) ?>
                                </div>

                                <div class="col-lg-1" style="margin-top:10px">
                                    <?= $form->field($model, 'cantidad', [
                                        'template' => '<div class="input-group costos-facturas">{input}
                            </div>{error}{hint}'
                                    ])->textInput(['id' => 'cantidad' . $s->id, 'type' => 'number', 'min' => 1, 'readonly' => true, 'class' => 'form-control cantidad', 'style' => 'border-radius:8px !important; text-align:center !important', 'name' => 'cantidad' . $s->id, 'value' => $cantidadExtra]) ?>
                                </div>

                                <div class="col-lg-2" style="margin-top:10px">
                                    <?= $form->field($model, 'precio_total', [
                                        'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                                    ])->textInput(['id' => 'precio_total' . $s->id, 'readonly' => true, 'class' => 'form-control cantidad', 'name' => 'precio_total' . $s->id, 'value' => $precioTotalExtra]) ?>
                                </div>

                                <div class="col-lg-12"></div>

                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 pad-0" style="padding-left: 0px">
                    <div class="panel panel-default panel-d">
                        <div class="panel-body panel-dates">

                            <div class="col-lg-12">
                                <div class="subtitulo-reserva" style="margin-bottom: 30px">Total Costos de Reserva</div>
                            </div>
                            <div class="col-lg-1 col-xs-1">
                                <div align="center" class="subtitulo-reserva sub-reserva dn">Items</div>
                            </div>
                            <div class="col-lg-9 col-xs-9">
                                <div align="center" class="subtitulo-reserva sub-reserva dn">Descripción</div>
                            </div>
                            <div class="col-lg-2 col-xs-2">
                                <div align="center" class="subtitulo-reserva sub-reserva na dn">Total</div>
                            </div>


                            <input type="hidden" name="is_noc" id="is_noc" value="<?= $nocturno[0]['id'] ?>">
                            <input type="hidden" name="servicio_noc_id" value="<?= explode('-', $nocturno[0]['id'])[0] ?>">
                            <input type="hidden" id="servicio_noc" name="servicio_noc_costo"
                                value="<?= $valorNocturno ?>">

                            <?php //print_r($nocturno);
                            if ($nocturno[0]['id'] === '11-1') { ?>
                                <div class="col-12" id="nocturnidad">
                                <?php } else { ?>
                                    <div class="col-12" id="nocturnidad" style="display:none">
                                    <?php } ?>
                                    <div class="col-lg-1 s dn" style="margin-top: 20px">
                                        <label class="num">SN</label>
                                    </div>

                                    <div class="col-lg-6" style="margin-top: 18px">
                                        <label class="service-reserva"><?= $nocturno[0]['nombre_servicio'] ?></label>
                                        <div class="des-reserva-ind" style="margin-left: 0px">
                                            <?= $nocturno[0]['descripcion'] ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-3"></div>


                                    <div class="col-lg-2" style="margin-top:10px">
                                        <div class="form-group field-reservas-costo_servicios required">
                                            <div class="input-group costos-facturas">
                                                <input type="text" class="form-control cantidad" name=""
                                                    value="<?= $nocturno[0]['costo'] ?>" readonly=""
                                                    aria-required="true">
                                                <span class="input-group-addon">€</span>
                                            </div>
                                            <div class="help-block"></div>
                                        </div>
                                    </div>

                                    <br>
                                    </div>



                                    <div class="col-lg-1 s dn" style="margin-top: 20px">
                                        <label class="num">1</label>
                                    </div>

                                    <div class="col-lg-6" style="margin-top: 18px">
                                        <label class="service-reserva"><?= $precio_diario[0]['nombre_servicio'] ?></label>
                                        <div class="des-reserva-ind" style="margin-left: 0px">
                                            <?= $precio_diario[0]['descripcion'] ?>
                                        </div>
                                    </div>

                                    <input type="hidden" id="servicio_basico" name="servicio_basico"
                                        value="<?= $precio_diario[0]['costo'] ?>">
                                    <input type="hidden" id="cant_basico" name="cant_basico" value="<?= $cantidadServicioBasico ?>">
                                    <input type="hidden" class="btn-success" id="actualiza_montos" name="actualiza_montos">
                                    <input type="hidden" id="cambiar_costo_servicio" value="0"
                                        name="cambiar_costo_servicio">
                                    <div class="col-lg-3"></div>

                                    <div class="col-lg-2" style="margin-top:10px">
                                        <?= $form->field($model, 'costo_servicios', [
                                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                                        ])->textInput(['onblur' => 'calcular_monto_total()', 'step' => 'any', 'type' => 'number', 'readonly' => false, 'class' => 'form-control cantidad', 'value' => $valorServicioBasico, 'data-from-storage' => $servicioBasicoDatos ? 1 : 0]) ?>
                                    </div>

                                    <div class="col-lg-12"><br></div>

                                    <div class="col-lg-1 dn">
                                        <label class="num">2</label>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="service-reserva"><?= $seguro[0]->nombre_servicio ?></label>
                                        <div class="des-reserva-ind" style="margin-left: 0px"><?= $seguro[0]->descripcion ?>
                                        </div>
                                    </div>

                                    <input type="hidden" id="seguro" name="seguro" value="<?= $seguro[0]['costo'] ?>">
                                    <input type="hidden" id="cant_seguro" name="cant_seguro" value="<?= $cantidadSeguroPrincipal ?>">

                                    <div class="col-lg-3"></div>

                                    <div class="col-lg-2" style="margin-top:-8px">
                                        <?= $form->field($model, 'total_seguro', [
                                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                                        ])->textInput(['id' => 'total_seguro', 'readonly' => true, 'class' => 'form-control cantidad', 'name' => 'total_seguro', 'value' => $valorSeguroPrincipal]) ?>
                                    </div>

                                    <div class="col-lg-12"><br></div>

                                    <div class="col-lg-1 dn">
                                        <label class="num">3</label>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="service-reserva">Servicios Extras Seleccionados</label>
                                        <div class="des-reserva-ind" style="margin-left: 0px">Otros servicios extras</div>
                                    </div>

                                    <div class="col-lg-3"></div>

                                    <input type="hidden" id="cant_extras" name="cant_extras" value="<?= $cantidadServiciosExtras ?>">

                                    <div class="col-lg-2" style="margin-top:-8px">
                                        <?= $form->field($model, 'costo_servicios_extra', [
                                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                                        ])->textInput(['readonly' => true, 'class' => 'form-control cantidad', 'value' => $costoServiciosExtra]) ?>
                                    </div>

                                    <div id="techado">
                                        <div class="col-lg-12"><br></div>
                                        <div class="col-lg-1">
                                            <label class="num">4</label>
                                        </div>

                                        <div class="col-lg-6">
                                            <label class="service-reserva"><?= $seguro[1]->nombre_servicio ?></label>
                                            <div class="des-reserva-ind mb" style="margin-left: 0px">
                                                <?= $seguro[1]->descripcion ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-3"></div>

                                        <div class="col-lg-2">
                                            <?= $form->field($model, 'total_seguro', [
                                                'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon eu">€</span></div>{error}{hint}'
                                            ])->textInput(['id' => 'total_techado', 'readonly' => true, 'class' => 'form-control cantidad', 'name' => 'total_techado', 'value' => $valorTechado]) ?>
                                        </div>
                                    </div>

                                    <!-- <div id="cortesia">
                                    <div class="col-lg-12"><br></div>
                                    <div class="col-lg-1">
                                        <label class="num">4</label>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="service-reserva"><?= $seguro[1]->nombre_servicio ?></label>
                                        <div class="des-reserva-ind mb" style="margin-left: 0px">
                                            <?= $seguro[1]->descripcion ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-3"></div>

                                    <div class="col-lg-2">
                                        <?= $form->field($model, 'total_seguro', [
                                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon eu">€</span></div>{error}{hint}'
                                        ])->textInput(['id' => 'total_seguro', 'readonly' => true, 'class' => 'form-control cantidad', 'name' => 'total_seguro', 'value' => $seguro[1]->costo]) ?>
                                    </div>
                                </div>

                                 <div id="techado">
                                    <div class="col-lg-12"><br></div>
                                    <div class="col-lg-1">
                                        <label class="num">5</label>
                                    </div>

                                    <div class="col-lg-6">
                                        <label class="service-reserva"><?= $seguro[2]->nombre_servicio ?></label>
                                        <div class="des-reserva-ind mb" style="margin-left: 0px">
                                            <?= $seguro[2]->descripcion ?>
                                        </div>
                                    </div>

                                    <div class="col-lg-3"></div>

                                    <div class="col-lg-2">
                                        <?= $form->field($model, 'total_seguro', [
                                            'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon eu">€</span></div>{error}{hint}'
                                        ])->textInput(['id' => 'total_techado', 'readonly' => true, 'class' => 'form-control cantidad', 'name' => 'total_techado', 'value' => $seguro[2]->costo]) ?>
                                    </div>
                                </div> -->
                                </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-xs-12 pad-0" style="padding-left: 0px">
                        <div class="panel panel-default panel-d d2" style="margin-bottom: 0px">
                            <div class="panel-body panel-dates otherp">
                                <div class="col-lg-3">
                                    <div style="padding-top: 10px;">
                                        <div class="col-lg-12 pad-0" style="padding: 0px">
                                            <label>FORMA DE PAGO</label>
                                            <?= $form->field($model, 'id_tipo_pago')->widget(Select2::classname(), [
                                                'data' => $tipos_pago,
                                                'options' => ['placeholder' => 'Selecccione la Forma de Pago'],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label(false); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <input type="hidden" name="medio_reserva" value="<?= $medio ?>">
                                    <div style="padding-top: 10px;">
                                        <div class="col-lg-12 pad-0" style="padding: 0px">
                                            <label>DESCUENTO</label>
                                            <?= $form->field($model, 'descuento')->widget(Select2::classname(), [
                                                'data' => ['SI' => 'SI', 'NO' => 'NO'],
                                                'options' => ['placeholder' => 'Selecccione'],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                            ])->label(false); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-2">
                                    <input type="hidden" name="agencia" value="<?= $agencia ?>">
                                    <div style="padding-top: 10px;">
                                        <div class="col-lg-12 pad-0"
                                            style="padding: 0px; <?= $model->descuento == 'SI' ? '' : 'display:none' ?>"
                                            id="monto_des">
                                            <label>MONTO DESCUENTO</label>
                                            <?= $form->field($model, 'monto_des')->textInput(['type' => 'number', 'min' => 0, 'max' => 100])->label(false) ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-xs-12">
                                    <div id="subtotal-factura" class="totales-facturas hide">Subtotal</div>
                                    <div id="impuestos-factura" class="totales-facturas hide">Impuestos</div>
                                    <div id="total-factura" class="totales-facturas" style="margin-top: 34px">Monto
                                        Total</div>
                                </div>

                                <div class="col-lg-2" style="margin-top:15px">
                                    <input type="hidden" id="montofactura" name="monto_factura"
                                        value="<?= $model->monto_factura ?>">
                                    <input type="hidden" id="montoimp" name="monto_impuestos"
                                        value="<?= $model->monto_impuestos ?>">

                                    <?= $form->field($model, 'monto_total', [
                                        'template' => '<div class="input-group costos-facturas">{input}
                            <span class="input-group-addon">€</span></div>{error}{hint}'
                                    ])->textInput(['maxlength' => true, 'readonly' => true, 'value' => $formatCurrency($model->monto_total)]) ?>
                                </div>

                                <div class="col-lg-12">
                                    <hr class="linea">
                                </div>

                                <div class="col-lg-8 col-xs-8"></div>
                                <div class="col-lg-2 col-xs-12" style="margin-top: -10px; margin-bottom: 20px">
                                    <?= Html::a('Cancelar', ['index'], ['class' => 'btn btn-warning btn-block']) ?>
                                </div>
                                <div align="right" class="col-lg-2 col-xs-12"
                                    style="margin-top: -10px; margin-bottom: 20px">
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

    $(document).ready(function() {

      var isUpdate = Number($('#nueva_reserva').val()) === 1;
      var baseServiceFromStorage = $('#reservas-costo_servicios').data('from-storage') === 1;

      techa = $('#techa').val();
      if (techa == '1') {
        $('#reservas-techado').click();                
      } else {
        $('#total_techado').prop('disabled',true);
      } 
      
      $('.servicios').change();                  

      precio_dia = $('#precio_dia').val();
      cant = parseInt($('#cant_basico').val());
      cant = isNaN(cant) ? 0 : cant;
      descuento = $('#dcto').val();
      precio1 = $('#precio-diario1').val() - descuento;
      precio2 = $('#precio-diario2').val() - descuento;
      precio3 = $('#precio-diario3').val() - descuento;
      precio4 = $('#precio-diario4').val() - descuento;
      precio5 = $('#precio-diario5').val() - descuento;
      precio6 = $('#precio-diario6').val() - descuento;
      precio7 = $('#precio-diario7').val() - descuento;
      precio8 = $('#precio-diario8').val() - descuento;
      precio9 = $('#precio-diario9').val() - descuento;
      precio10 = $('#precio-diario10').val() - descuento;

      precio11 = $('#precio-diario11').val() - descuento;
      precio12 = $('#precio-diario12').val() - descuento;
      precio13 = $('#precio-diario13').val() - descuento;
      precio14 = $('#precio-diario14').val() - descuento;
      precio15 = $('#precio-diario15').val() - descuento;
      precio16 = $('#precio-diario16').val() - descuento;
      precio17 = $('#precio-diario17').val() - descuento;
      precio18 = $('#precio-diario18').val() - descuento;
      precio19 = $('#precio-diario19').val() - descuento;
      precio20 = $('#precio-diario20').val() - descuento;

      precio21 = $('#precio-diario21').val() - descuento;
      precio22 = $('#precio-diario22').val() - descuento; 
      precio23 = $('#precio-diario23').val() - descuento; 
      precio24 = $('#precio-diario24').val() - descuento; 
      precio25 = $('#precio-diario25').val() - descuento; 
      precio26 = $('#precio-diario26').val() - descuento; 
      precio27 = $('#precio-diario27').val() - descuento; 
      precio28 = $('#precio-diario28').val() - descuento; 
      precio29 = $('#precio-diario29').val() - descuento; 
      precio30 = $('#precio-diario30').val() - descuento; 

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

      if (cant > 30) {
        var cantDias = cant - 30;
        var precioRelativo = parseFloat(precio30);
        total = precioRelativo + (cantDias * parseFloat(precio_dia));
      }

      var currentBase = parseFloat($('#reservas-costo_servicios').val());
      if (!isUpdate || !baseServiceFromStorage || $('#reservas-costo_servicios').val() === '' || isNaN(currentBase)) {
        $('#reservas-costo_servicios').val(total.toFixed(2));
      }
      $('.totales-facturas').click();
      });            

      $('#condiciones-servicio').click(function() {
        $('#BtnModalCondiciones').click();
      }); 

      $('#reservas-fecha_entrada').on('change', function(e) {
        $('#actualiza_montos').click();
      })

      $('#reservas-hora_entrada').on('change', function(e) {
        $('#actualiza_montos').click();
      })  

      $('#reservas-fecha_salida').on('change', function(e) {
        $('#actualiza_montos').click();
      })

      $('#reservas-hora_salida').on('change', function(e) {
        $('#actualiza_montos').click();
      })      

      $('#actualiza_montos').on('click', function(e) {
        url = $('#url').val();
        fecha_entrada = $('#reservas-fecha_entrada').val();
        hora_entrada = $('#reservas-hora_entrada').val();
        fecha_salida = $('#reservas-fecha_salida').val();
        hora_salida = $('#reservas-hora_salida').val();
    
        anio_in =String(fecha_entrada).substring(6,10);
        dia_in = String(fecha_entrada).substring(0,2); 
        mes_in = String(fecha_entrada).substring(3,5);
        f1 = new Date(anio_in,mes_in,dia_in);

        anio_out =String(fecha_salida).substring(6,10);
        dia_out = String(fecha_salida).substring(0,2); 
        mes_out = String(fecha_salida).substring(3,5);
        f2 = new Date(anio_out,mes_out,dia_out);

        var invalidDates = false;
        if (f1 > f2) {
            invalidDates = true;
        }

        if (f1 >= f2 && hora_entrada > hora_salida) {
            invalidDates = true;
        }

        if (invalidDates) {
            $('#msg-fechas').text('Verifique las fechas y horas seleccionadas').show();
            $('.btn-success').prop('disabled', true);
        } else {
            $('#msg-fechas').hide();
            $('.btn-success').prop('disabled', false);
        }

        e.preventDefault();
        $.ajax({
          type:'POST',
          url: url,
          data: { fecha_entrada: fecha_entrada, hora_entrada: hora_entrada, fecha_salida: fecha_salida, hora_salida: hora_salida },
          success: function(data) {
            dato = data
            $('#cant_basico').val(dato);
            dias = $('#cant_basico').val();
            precio_dia = $('#precio_dia').val();
            if (dias == 0) { var total = 0; }  
            if (dias == 1) { var total = parseFloat(precio1); }                     
            if (dias == 2) { var total = parseFloat(precio2); }
            if (dias == 3) { var total = parseFloat(precio3); }
            if (dias == 4) { var total = parseFloat(precio4); }
            if (dias == 5) { var total = parseFloat(precio5); }
            if (dias == 6) { var total = parseFloat(precio6); }
            if (dias == 7) { var total = parseFloat(precio7); }
            if (dias == 8) { var total = parseFloat(precio8); }
            if (dias == 9) { var total = parseFloat(precio9); }
            if (dias == 10) { var total = parseFloat(precio10); }

            if (dias == 11) { var total = parseFloat(precio11); }                     
            if (dias == 12) { var total = parseFloat(precio12); }
            if (dias == 13) { var total = parseFloat(precio13); }
            if (dias == 14) { var total = parseFloat(precio14); }
            if (dias == 15) { var total = parseFloat(precio15); }
            if (dias == 16) { var total = parseFloat(precio16); }
            if (dias == 17) { var total = parseFloat(precio17); }
            if (dias == 18) { var total = parseFloat(precio18); }
            if (dias == 19) { var total = parseFloat(precio19); }
            if (dias == 20) { var total = parseFloat(precio20); }

            if (dias == 21) { var total = parseFloat(precio21); }                     
            if (dias == 22) { var total = parseFloat(precio22); }
            if (dias == 23) { var total = parseFloat(precio23); }
            if (dias == 24) { var total = parseFloat(precio24); }
            if (dias == 25) { var total = parseFloat(precio25); }
            if (dias == 26) { var total = parseFloat(precio26); }
            if (dias == 27) { var total = parseFloat(precio27); }
            if (dias == 28) { var total = parseFloat(precio28); }
            if (dias == 29) { var total = parseFloat(precio29); }
            if (dias == 30) { var total = parseFloat(precio30); } 
            if (dias > 30) { 
              var cant_dias = dias - 30;
              var precio_relativo = parseFloat(precio30);
              var total = precio_relativo + (cant_dias * parseFloat(precio_dia)); 
            }
            
            $('#reservas-costo_servicios').val(total.toFixed(2));
            
            if((hora_entrada >= '00:30' && hora_entrada <= '03:45') || (hora_salida >= '00:30' && hora_salida <= '03:45')){
				$('#is_noc').val('11-1');
				$('#nocturnidad').css('display', 'block');
			} else {
			    $('#is_noc').val('11-0');
			    $('#nocturnidad').css('display', 'none');
            }
            
            $('#subtotal-factura').click();  
          }            
        });
      });                  
      

  function selectCarro(marca, matricula){
      
  }
    $('#reservas-descuento').on('change', function(){
        if($(this).val() === 'SI'){
            $('#monto_des').css('display', 'block');
        } else {
         var is_noc = $('#is_noc').val() == '11-1' ? $('#servicio_noc').val() : 0;
            var monto_total = parseFloat($('#reservas-costo_servicios').val()) + parseFloat($('#total_seguro').val()) + parseFloat($('#reservas-costo_servicios_extra').val()) + is_noc;
            $('#monto_des').css('display', 'none');
            $('#reservas-monto_des').val(0);
            $('#reservas-monto_total').val(monto_total.toFixed(2));
        }
    });
    
    $('#reservas-monto_des').on('change', function(){
	
	//const mt = $(this).val() !== 0 ? (parseFloat($('#reservas-costo_servicios').val()) + parseFloat($('#total_seguro').val()) + parseFloat($('#reservas-costo_servicios_extra').val()) +  parseFloat($('#servicio_noc').val())) : parseFloat($('#reservas-monto_total').val());
	
	
	/*console.log(parseFloat($('#reservas-costo_servicios').val()));
	console.log(parseFloat($('#total_seguro').val()));
	console.log(parseFloat($('#reservas-costo_servicios_extra').val()));
	console.log(parseFloat($('#servicio_noc').val()));
	
	console.log($('#reservas-monto_total').val());
	console.log(parseFloat(($(this).val()/100)));
	console.log($('#reservas-monto_total').val());*/
	
	const valor= $('#reservas-monto_total').val() - parseFloat(($(this).val()/100) * $('#reservas-monto_total').val());	
        $('#reservas-monto_total').val(valor.toFixed(2));
    })
    
      $('#clientes-movil').on('blur', function(e){
    
        var movil =  $('#clientes-movil').val();

        if(movil !== ''){
            $.get('/aparcamiento/backend/web/index.php?r=clientes%2Fcliente', { movil: movil} )
                .done(function( data ) {
                     var content = JSON.parse(data);
                    if(content.success){

                        $('#clientes-nombre_completo').val(content.cliente.nombre_completo);
                        $('#clientes-correo').val(content.cliente.correo);

                        const cochesList = content.coches || content.coche || [];

                        if(cochesList.length > 0){
                            var coches = '<table style=\"width:100%;text-align: center\">';
                            coches += '<tr><th>Marca</th> <th>Matricula</th><th>Seleccionar</th></tr>';
                            cochesList.forEach((c) => {
                                coches +='<input type=\"hidden\" value=\"'+c.marca+'\" id=\"marca'+c.id +'\" />';
                                coches +='<input type=\"hidden\" value=\"'+c.matricula+'\" id=\"matricula'+c.id +'\" />';
                                coches += '<tr><td>' +c.marca +'</td> <td>'+ c.matricula+ '</td><td><span class=\"cocheSelect glyphicon glyphicon-check\" style=\"color:#f0ad4e;cursor:pointer\"  id='+c.id+' onclick=\"selectCarro('+c.id+')\"></span></td></tr>';
                            });
                            coches += '</table>';

                            $('.clientes-coches').html(coches);
                        } else {
                            $('.clientes-coches').html('');
                            $('#coches-marca').val('');
                            $('#coches-matricula').val('');
                        }

                    } else {
                        $('.clientes-coches').html('');
                        $('#clientes-nombre_completo').val('');
                        $('#clientes-correo').val('');

                        $('#coches-marca').val('');
                        $('#coches-matricula').val('');
                    }
                });
        }else {
            $('#clientes-nombre_completo').val('');
            $('#clientes-correo').val('');
            
            $('#coches-marca').val('');
            $('#coches-matricula').val('');
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
                      var imp = $('#reservas-iva').val
                      precio_techado = 0;
                      $('.servicios:checked').each(function() {
                        var id = $(this).val();
                        var precio = $('#precio_total'+ id).val();
                        monto_subtotal = parseFloat(monto_subtotal) + parseFloat(precio);
                      });

                     /*$('#reservas-techado:checked').each(function() {
                        
                        if(this.checked){
                            monto_subtotal = parseFloat(monto_subtotal) + parseFloat(precio_techado);
                        } else if(monto_subtotal > 0){
                            monto_subtotal = parseFloat(monto_subtotal) - parseFloat(precio_techado);
                        }
                        
                      });     */

                        if($('#reservas-techado').prop('checked') ) {
                            precio_techado = parseFloat($('#total_techado').val());
                        }

                      $('#reservas-costo_servicios_extra').val(monto_subtotal.toFixed(2));
                      var total_seguro = $('#total_seguro').val();
                      var costo_servicios = $('#reservas-costo_servicios').val();
                      
                      var stotal_reserva = monto_subtotal + parseFloat(total_seguro) + parseFloat(costo_servicios) + precio_techado;
                        
                      var is_noc = $('#is_noc').val() === '11-1' ? $('#servicio_noc').val() : 0;

                                          
                      $('#reservas-monto_factura').val(stotal_reserva.toFixed(2));
                      var impuestos = 0;
                      $('#reservas-monto_impuestos').val(impuestos.toFixed(2));
                      var total_monto = parseFloat(stotal_reserva) + parseFloat(impuestos) + parseFloat(is_noc);
                      
                                        console.log(total_monto);
                      if( $('#reservas-monto_des').val() > 0){
                          var desc = parseFloat($('#reservas-monto_des').val());
                          total_monto = (total_monto - ((desc/100) * total_monto)); 
                      }
                      
                      $('#reservas-monto_total').val(total_monto.toFixed(2));
                    });

                      $('#reservas-techado').click(function() {
                        $('#reservas-techado:checked').each(function() { 
                          totalito = $('#reservas-monto_total').val();
                          techado = $('#total_techado').val();
                          monto_totalito = parseFloat(totalito) + parseFloat(techado);
                          $('#reservas-monto_total').val(monto_totalito.toFixed(2));
                          $('#total_techado').prop('disabled',false);
                        });

                        $('#reservas-techado:checkbox:not(:checked)').each(function() {
                          monto_totalito = parseFloat($('#reservas-monto_total').val()) - parseFloat($('#total_techado').val());
                          $('#reservas-monto_total').val(monto_totalito.toFixed(2));
                          $('#total_techado').prop('disabled',true);
                        });                
                      });

                      
                      if(isUpdate){
                      var parking = parseFloat($('#monto_serv_p').val());
                      var currentBaseParking = parseFloat($('#reservas-costo_servicios').val());

                      if(!baseServiceFromStorage && !isNaN(parking) && (isNaN(currentBaseParking) || currentBaseParking === 0)) {
                        setTimeout(() =>{
                          $('#reservas-costo_servicios').val(parking.toFixed(2)).blur();
                        }, 500);
                      }

                    }
                        ");
?>

<script>
    function enviar_email() {
        var valor = $("#envio_email").val() == 0 ? 1 : 0;
        $("#envio_email").val(valor);
    }

    function calcular_monto_total() {
        var costo_servicios = parseFloat($("#reservas-costo_servicios").val());

        var precio_techado = 0;
        if ($('#reservas-techado').prop('checked')) {
            precio_techado = parseFloat($('#total_techado').val());
        }

        var serv_extra = parseFloat($('#reservas-costo_servicios_extra').val());
        var total_seguro = parseFloat($('#total_seguro').val());

        var stotal_reserva = serv_extra + total_seguro + costo_servicios + precio_techado;

        var is_noc = $('#is_noc').val() === '11-1' ? 10 : 0;


        $('#reservas-monto_factura').val(stotal_reserva.toFixed(2));

        var impuestos = 0;
        $('#reservas-monto_impuestos').val(impuestos.toFixed(2));

        var total_monto = stotal_reserva + parseFloat(impuestos) + parseFloat(is_noc);

        $('#reservas-monto_total').val(total_monto.toFixed(2));
        $("#cambiar_costo_servicio").val(1);
    }

    function selectCarro(c) {
        $('#coches-marca').val($('#marca' + c).val());
        $('#coches-matricula').val($('#matricula' + c).val());
    }

    function muestra(id) {

        if (document.getElementById) {
            var contenido = document.getElementById(id);
            contenido.style.display = (contenido.style.display == 'none') ? 'block' : 'none';
        }
    }

    window.onload = function() {
        muestra('facturacion');
        //muestra('cortesia');
        muestra('techado');
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
                $("#reservas-color").val(color);
            },
            error: function() {
                console.log("failure");
            }
        });
    }
</script>