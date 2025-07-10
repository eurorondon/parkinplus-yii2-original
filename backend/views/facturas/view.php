<?php

use yii\helpers\Html;
use common\models\Servicios;
use common\models\FacturasServicios;
use common\models\FacturasReserva;
use common\models\Configuracion;
use common\models\Reservas;
use common\models\Conceptos;
use kartik\popover\PopoverX;
use kartik\mpdf\Pdf;

/* @var $this yii\web\View */
/* @var $model common\models\Facturas */

$this->title = Yii::$app->name.' | Información de Factura';
$this->params['breadcrumbs'][] = ['label' => 'Facturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Factura: '.$model->nro_factura;
\yii\web\YiiAsset::register($this);

$ser = FacturasServicios::find()->where(['id_factura'=> $model->id])->orderBy(['id_servicio' => SORT_DESC])->all();

$id_reserva = FacturasReserva::find()->where(['id_factura' => $model->id])->one();

if ($id_reserva != NULL) {

    $idr = $id_reserva->id_reserva;

    $datoR = Reservas::find()->where(['id' => $idr])->one();

    $fecha_e = $datoR->fecha_entrada;
    $fecha_e = date("d-m-Y", strtotime($fecha_e));

    $fecha_s = $datoR->fecha_salida;
    $fecha_s = date("d-m-Y", strtotime($fecha_s));

} 

$other_service = Conceptos::find()->where(['id_factura' => $model->id])->one();

if ($model->estatus === 0) {
    $estatus = 'Cancelada';
} 
if ($model->estatus === 1) {
    $estatus = 'Activa';
}
if ($model->estatus === 2) {
    $estatus = 'Pendiente';
}

$model->monto_total = number_format((float)$model->monto_total,2,'.','');

?>
<div class="facturas-view">

    <div class="title-margin-new">
        <span style="display: inline">Información de Factura</span>
        <span class="datos-factura">Factura N° : <?= $model->nro_factura ?></span>
    </div>

        <div class="row">
            <div class="col-lg-12 subtitulo-reserva" style="margin: 20px 0px">Datos del Cliente</div>
            <br>
            <div class="col-lg-4">
                <div class="datos-reserva">Razón Social</div>      
                <div class="info-view"><?= $model->razon_social; ?></div>
            </div>

            <div class="col-lg-2">
                <div class="datos-reserva">NIF</div>      
                <div class="info-view"><?= $model->nif; ?></div>
            </div>

            <div class="col-lg-12">
                <div class="datos-reserva">Dirección</div>      
                <div class="info-view" style="min-height: 53px"><?= $model->direccion; ?></div>
            </div>

            <div class="col-lg-12" style="margin-bottom: 10px;"></div>

            <div class="col-lg-2">
                <div class="datos-reserva">Código Postal</div>      
                <div class="info-view"><?= $model->cod_postal; ?></div>
            </div> 
            <div class="col-lg-3">
                <div class="datos-reserva">Ciudad</div>      
                <div class="info-view"><?= $model->ciudad; ?></div>
            </div>
            <div class="col-lg-3">
                <div class="datos-reserva">Provincia</div>      
                <div class="info-view"><?= $model->provincia; ?></div>
            </div>
            <div class="col-lg-3">
                <div class="datos-reserva">País</div>      
                <div class="info-view"><?= $model->pais; ?></div>
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
                $total = 0;
                foreach ($ser as $s) {
                $datos = Servicios::find()->where(['id'=> $s->id_servicio])->one();
                $total = $total + $s->precio_total; 

                $buscaiva = Configuracion::find()->where(['tipo_campo' => 1])->one();
                $iva = $buscaiva->valor_numerico;

                $punitario = $s->precio_unitario;
                $ptotal = $s->precio_total;                
            ?>

            <div class="col-lg-7">
                <div class="datos-reserva">
                    <li><?= $datos->nombre_servicio; ?></li>
                </div>
                <?php if ($datos->fijo == 0) { 
                    if ($id_reserva != NULL) { ?>
                        <div class="des-reserva-indent" style="margin-left: 20px"><?= $datos->descripcion; ?> (DESDE: <?= $fecha_e ?>  - HASTA: <?= $fecha_s ?>)</div>
                    <?php } else { ?>
                        <div class="des-reserva-indent" style="margin-left: 20px"><?= $datos->descripcion; ?></div>
                    <?php } } else { ?>
                    <div class="des-reserva-indent" style="margin-left: 20px"><?= $datos->descripcion; ?></div>
                <?php } ?>
            </div> 

            <div class="col-lg-2">
                <div class="form-group field-facturas-monto_factura required">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= round($punitario,2); ?>" disabled="true">
                        <span class="input-group-addon">€</span>
                    </div>
                </div>                
            </div>             

            <div class="col-lg-1">
                <div class="form-group field-facturas-monto_factura required">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" style="text-align: center; border-radius: 8px !important" class="form-control" value="<?= $s->cantidad; ?>" disabled="true">
                    </div>
                </div>               
            </div>              

            <div class="col-lg-2">
                <div class="form-group field-facturas-monto_factura required">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= round($ptotal,2); ?>" disabled="true">
                        <span class="input-group-addon">€</span>
                    </div>
                </div>                
            </div> 

            <div class="col-lg-12"><br></div>                      
                
            <?php  }  

            if (count($ser) === 0) { ?>
                
                <div class="col-lg-7">
                    <div class="datos-reserva">
                        <?= $other_service->descripcion; ?>
                    </div>
                </div> 

                <div class="col-lg-2">
                    <div class="form-group field-facturas-monto_factura required">
                        <div class="input-group costos-facturas" style="margin-top: 0px">
                            <input type="text" id="facturas-monto_factura" class="form-control" value="<?= round($other_service->punitario,2) ?>" disabled="true">
                            <span class="input-group-addon">€</span>
                        </div>
                    </div>                
                </div>             

                <div class="col-lg-1">
                    <div class="form-group field-facturas-monto_factura required">
                        <div class="input-group costos-facturas">
                            <input type="text" id="facturas-cantidad" style="margin-top: -15px; text-align: center; border-radius: 8px !important" class="form-control" value="<?= round($other_service->cantidad); ?>" disabled="true">
                        </div>
                    </div>               
                </div>              

                <div class="col-lg-2">
                    <div class="form-group field-facturas-monto_factura required">
                        <div class="input-group costos-facturas" style="margin-top: 0px">
                            <input type="text" id="facturas-monto_total" class="form-control" value="<?= round($other_service->ptotal,2) ?>" disabled="true">
                            <span class="input-group-addon">€</span>
                        </div>
                    </div>                
                </div> 

                <div class="col-lg-12"><br></div>  

            <?php } ?>

            <div class="col-lg-12"><hr></div>   

            <div class="col-lg-7">
                <div style="padding-top: 5px;">
                    <div class="col-lg-7">
                        <div class="datos-reserva">Forma de Pago</div>      
                        <div class="info-view"><?= $model->tipoPago->descripcion; ?></div>
                    </div> 
                    <div class="col-lg-5">
                        <div class="datos-reserva">Estátus de la Factura</div>      
                        <div class="info-view"><?= $estatus; ?></div>
                    </div> 
                    <div style="margin-top: 35px;">
                        <div class="col-lg-12">
                            <div class="datos-reserva" style="margin-top: 15px">Observación</div> 
                            <div class="info-view" style="min-height: 50px; text-transform: uppercase; font-size: 0.85em"><?= $model->observacion; ?></div>
                        </div>                
                    </div>                                                        
                </div>
            </div>
            <div class="col-lg-3">
                <div class="totales-facturas">Subtotal</div>
                <div class="totales-facturas">I.V.A (21%)</div>
                <div class="totales-facturas">Total a Pagar</div>
            </div> 

            <div class="col-lg-2">
                <div class="form-group field-facturas-monto_factura required has-success">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= round($model->monto_factura,2); ?>" disabled="true">
                        <span class="input-group-addon">€</span>
                    </div>
                </div>
                <div class="form-group field-facturas-monto_factura required has-success">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= round($model->monto_impuestos,2); ?>" disabled="true">
                        <span class="input-group-addon">€</span>
                    </div>
                </div>                
                <div class="form-group field-facturas-monto_factura required has-success">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $model->monto_total; ?>" disabled="true">
                        <span class="input-group-addon">€</span>
                    </div>
                </div>
            </div>                          

            <div class="col-lg-12"><hr style="margin-bottom: 5px; margin-top: 40px;"></div>

            <div align="right" class="col-lg-12" style="margin-top: 25px">
                <?= Html::a('Cancelar', ['facturas/index'], ['class' => 'btn btn-warning']) ?>
                &nbsp; &nbsp; &nbsp;
                <?= Html::a('Imprimir Factura', [
                        '/facturas/view-pdf',
                        'id'=> $model->id,
                    ], 

                    [
                        'class'=>'btn btn-success', 
                        'target'=>'_blank', 
                        'data-toggle'=>'tooltip', 
                        'title'=>'Imprimir Factura'
                    ]) 
                ?>
            </div>                                  

        </div>
</div>
