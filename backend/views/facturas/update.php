<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\Servicios;
use common\models\TipoPago;
use common\models\FacturasServicios;
use kartik\select2\Select2;
use kartik\popover\PopoverX;
use kartik\mpdf\Pdf;

/* @var $this yii\web\View */
/* @var $model common\models\Facturas */

$this->title = Yii::$app->name.' | Modificar Factura';
$this->params['breadcrumbs'][] = ['label' => 'Facturas', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Factura N°: '.$model->nro_factura, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Modificar Factura';
\yii\web\YiiAsset::register($this);

$url_estatus = Url::to(['anular', 'id' => $model->id]);

$content = '<p class="text-justify">' .
    'Si procede a la anulación de la presente factura, tendrá que manifestar el motivo de su anulación, una vez anulada no podrá volver a cambiar su estatus.' . 
    '</p>';

$ser = FacturasServicios::find()->where(['id_factura'=> $model->id])->orderBy(['id_servicio' => SORT_DESC])->all();

$pagos = TipoPago::find()->where(['estatus'=>'1'])->all();
$tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion'); 

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

    <input type="text" id="url" value="<?= $url_estatus ?>">
    <div class="title-margin-new">
        <span style="display: inline">Modificar Factura</span>
        <span class="datos-factura">Factura: <?= $model->serie.'-'.$model->nro_factura ?></span>
    </div>

        <div class="row">
            <br>
            <div class="col-lg-12 subtitulo-reserva">Datos del Cliente</div>

            <div class="col-lg-2">
                <div class="datos-reserva">NIF</div>      
                <div class="info-reserva"><?= $model->nif; ?></div>
            </div>
            <div class="col-lg-4">
                <div class="datos-reserva">Razón Social</div>      
                <div class="info-reserva"><?= $model->razon_social; ?></div>
            </div>
            <div class="col-lg-6">
                <div class="datos-reserva">Dirección</div>      
                <div class="info-reserva" style="min-height: 53px"><?= $model->direccion; ?></div>
            </div>

            <div class="col-lg-12" style="margin-bottom: 10px;"></div>

            <div class="col-lg-2">
                <div class="datos-reserva">Código Postal</div>      
                <div class="info-reserva"><?= $model->cod_postal; ?></div>
            </div> 
            <div class="col-lg-3">
                <div class="datos-reserva">Ciudad</div>      
                <div class="info-reserva"><?= $model->ciudad; ?></div>
            </div>
            <div class="col-lg-3">
                <div class="datos-reserva">Provincia</div>      
                <div class="info-reserva"><?= $model->provincia; ?></div>
            </div>
            <div class="col-lg-3">
                <div class="datos-reserva">País</div>      
                <div class="info-reserva"><?= $model->pais; ?></div>
            </div>

            <div class="col-lg-12"><hr style="margin-top: 35px;"></div>

            <div class="col-lg-7 space" style="margin-bottom: 30px">
                <div class="subtitulo-reserva">Descripción del Servicio</div>
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
                $total = 0;
                foreach ($ser as $s) {
                $datos = Servicios::find()->where(['id'=> $s->id_servicio])->one();
                $total = $total + $s->precio_total; 
            ?>

            <div class="col-lg-7">
                <div class="datos-reserva">
                    <li><?= $datos->nombre_servicio; ?></li>
                </div>
                <div class="des-reserva-indent"><?= $datos->descripcion; ?></div>
            </div> 

            <div class="col-lg-2">
                <div class="form-group field-facturas-monto_factura required">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $s->precio_unitario; ?>" disabled="true">
                        <span class="input-group-addon">€</span>
                    </div>
                </div>                
            </div>             

            <div class="col-lg-1">
                <div class="form-group field-facturas-monto_factura required">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $s->cantidad; ?>" disabled="true">
                    </div>
                </div>               
            </div>              

            <div class="col-lg-2">
                <div class="form-group field-facturas-monto_factura required">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $s->precio_total; ?>" disabled="true">
                        <span class="input-group-addon">€</span>
                    </div>
                </div>                
            </div> 

            <div class="col-lg-12"><br></div>                      
                
            <?php } ?>                 

            <div class="col-lg-12"><hr></div>   

            <div class="col-lg-8">
                <div class="observacion" style="padding-top: 25px;">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="col-lg-6">

                            <?= $form->field($model, 'id_tipo_pago')->widget(Select2::classname(), [
                                'data' => $tipos_pago,
                                'options' => ['placeholder' => 'Selecccione la Forma de Pago'],
                                'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]); ?>

                    </div> 
                    <div class="col-lg-1"></div>
                    <div class="col-lg-5">
                        <div class="datos-reserva">Estátus de la Factura</div>      
                        <div class="info-reserva"><?= $estatus; ?></div>
                    </div> 
                    <?php ActiveForm::end(); ?>                                    
                </div>
            </div>
            <div class="col-lg-2">
                <div class="totales-facturas">Subtotal</div>
                <div class="totales-facturas">Impuestos</div>
                <div class="totales-facturas">Monto Total</div>
            </div> 

            <div class="col-lg-2">
                <div class="form-group field-facturas-monto_factura required has-success">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $model->monto_factura; ?>" disabled="true">
                        <span class="input-group-addon">€</span>
                    </div>
                </div>
                <div class="form-group field-facturas-monto_factura required has-success">
                    <div class="input-group costos-facturas">
                        <input type="text" id="facturas-monto_factura" class="form-control" value="<?= $model->monto_impuestos; ?>" disabled="true">
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
            <div align="right"id="info" class="col-lg-9" style="margin-top: 25px">
                <a id="anular" class="btn btn-danger">Anular esta Factura</a>
            </div> 
            <div align="right" class="col-lg-3" style="margin-top: 25px">
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


<?php
$js = <<< JS
    $("#anular").on("click", function() {
        krajeeDialog.confirm("Una vez que la factura sea anulada no podrá volver a cambiar su estatus. <br> <br> ¿Seguro que desea continuar? . ", function (result) {

            if (result) {
               url = $('#url').val();
               window.location.href = url;
            } 
        });
    });
JS;
// register your javascript
$this->registerJs($js);

?>