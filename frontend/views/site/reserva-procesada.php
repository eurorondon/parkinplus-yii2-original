<?php

use yii\helpers\Html;
use common\models\Servicios;
use common\models\Clientes;
use common\models\ReservasServicios;

$service = ReservasServicios::find()->where(['id_reserva' => $reserva->nro_reserva])
    ->orderBy(['tipo_servicio' => SORT_ASC])
    ->all();

if ($reserva->factura_equipaje == 0) {
    $factura_equipaje = 'NO';
} else {
    $factura_equipaje = 'SI';
}

$fecha = $reserva->created_at;
$fechaCompleta = strtotime($fecha);

$fechaF = date("d-m-Y", $fechaCompleta);

$fecha_e = $reserva->fecha_entrada;
$fecha_e = date("d-m-Y", strtotime($fecha_e));

$fecha_s = $reserva->fecha_salida;
$fecha_s = date("d-m-Y", strtotime($fecha_s));

$hora_e = $reserva->hora_entrada;
$hora_s = $reserva->hora_salida;

$paymentNotice = Yii::$app->session->getFlash('payment_notice');

foreach ($service as $s) {
    $datos = Servicios::find()->where(['id' => $s->id_servicio])->one();
    if ($s->id_servicio == 12 && (int)$s->precio_total === 0) {
        continue;
    }
    if ($datos->fijo == 2) {
        $lavado = $datos->nombre_servicio;
    } else {
        $lavado = 'N/A';
    }
}

if ($reserva->ciudad_procedencia == null) {
    $ciudad = 'N/D';
} else {
    $ciudad = $reserva->ciudad_procedencia;
}

if ($reserva->nro_vuelo_regreso == null) {
    $vuelo = 'N/D';
} else {
    $vuelo = $reserva->nro_vuelo_regreso;
}

$this->title = Yii::$app->name . ' | Reserva Procesada';
?>

<div class="site-reserva-procesada">
    <div class="container">
        <div class="row row-movil" style="margin-top: 100px">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-2">
                        <?= Html::img('@web/images/succes.gif', ['class' => 'img img-responsive img-informa']); ?>
                    </div>
                    <div class="col-lg-10 col-md-10 col-xs-12">
                        <?php if ($paymentNotice): ?>
                            <div class="alert alert-warning" style="margin-bottom: 10px;">
                                <?= Html::decode($paymentNotice) ?>
                            </div>
                        <?php else: ?>
                            Su reserva ha sido procesada de manera exitosa. Revise su correo electrónico para mayor información
                            <p class="parrafo-m" style="margin-top: 5px">Si no recibe nuestro correo revise su carpeta de <b>SPAM</b> o comuniquesé con nosotros:</p>
                            <p align="right" style="margin-top: -35px; margin-right: 120px;"><a class="reborde phone-min" style="font-size: 0.9em;" href="tel:+34912147984"> +34 603 28 48 00</a></p>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>



        <div class="row" style="">
            <div class="col-lg-12">
                <div class="title-m" align="center" style="margin-bottom: 15px">
                    <h3>Resumen de Reserva - Parking Plus</h3>
                </div>
                <div class="panel panel-default panel-m">
                    <div class="panel-body panel-min" style="padding: 20px 40px">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="subtitulo-reserva" style="margin-top: 0;">Información de la Reserva</div>
                                    </div>
                                </div>

                                <div class="row mt-30">
                                    <div class="col-lg-12">
                                        <b>Fecha de Entrada</b>
                                    </div>
                                    <div class="col-lg-12">
                                        <?= $fecha_e ?> / <?= $hora_e ?>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <b>Fecha de Salida</b>
                                    </div>
                                    <div class="col-lg-12">
                                        <?= $fecha_s ?> / <?= $hora_s ?>
                                    </div>
                                </div>

                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <b>Terminal de Entrada</b>
                                    </div>
                                    <div class="col-lg-12">
                                        <?= $reserva->terminal_entrada ?>
                                    </div>
                                </div>

                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <b>Terminal de Salida</b>
                                    </div>
                                    <div class="col-lg-12">
                                        <?= $reserva->terminal_salida ?>
                                    </div>
                                </div>



                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <b>Ciudad de Procedencia</b>
                                    </div>
                                    <div class="col-lg-12">
                                        <?= $ciudad ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-12">
                                <div class="row mtx-30">
                                    <div class="col-lg-12">
                                        <div class="subtitulo-reserva" style="margin-top: 0;">Datos del Cliente</div>
                                    </div>
                                </div>

                                <div class="row mt-30">
                                    <div class="col-lg-12">
                                        <b>Nombre y Apellidos</b>
                                    </div>
                                    <div class="col-lg-12">
                                        <?= $reserva->cliente->nombre_completo ?>
                                    </div>
                                </div>

                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <b>Teléfono</b>
                                    </div>
                                    <div class="col-lg-12">
                                        <?= $reserva->cliente->movil ?>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12">
                                        <b>Correo Electrónico</b>
                                    </div>
                                    <div class="col-lg-12">
                                        <?= $reserva->cliente->correo ?>
                                    </div>
                                </div>

                                <div class="row mt-30">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <div class="subtitulo-reserva" style="margin-top: 0;">Datos del Vehículo</div>
                                    </div>
                                </div>

                                <div class="row mt-20">
                                    <div class="col-lg-6 col-md-6 col-xs-8">
                                        <b>Matrícula</b>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-4">
                                        <b>Marca - Modelo</b>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-8">
                                        <?= !empty($reserva->coche->matricula) ? $reserva->coche->matricula : 'N/D' ?>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-4">
                                        <?php
                                        $marca = trim($reserva->coche->marca ?? '');
                                        $modelo = trim($reserva->coche->modelo ?? '');
                                        echo (!empty($marca) || !empty($modelo)) ? "$marca $modelo" : 'N/D';
                                        ?>
                                    </div>
                                </div>


                            </div>

                            <div class="col-lg-4 col-md-4 col-xs-12">
                                <div class="row mtx-30">
                                    <div class="col-lg-12">
                                        <div class="subtitulo-reserva" style="margin-top: 0;">Servicios Contratados</div>
                                    </div>
                                </div>

                                <?php
                                $total = 0;
                                foreach ($service as $s) {
                                    $datos = Servicios::find()->where(['id' => $s->id_servicio])->one();
                                    if ($s->id_servicio == 12 && (int)$s->precio_total === 0) {
                                        continue;
                                    }
                                    if (stripos($datos->nombre_servicio, 'techado') !== false && (int)$s->precio_total === 0) {
                                        continue;
                                    }
                                    $total = $total + $s->precio_total;
                                ?>

                                    <div class="row mt-20">
                                        <div class="col-lg-12">
                                            - <?= $datos->nombre_servicio ?>
                                        </div>
                                    </div>

                                <?php } ?>

                                <div class="row mt-20">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <hr>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-xs-8">
                                        <div class="subtitulo-reserva" style="margin-top: 0;">N° de Reserva</div>
                                    </div>

                                    <div class="col-lg-6 col-md-6 col-xs-4">
                                        <div class="subtitulo-reserva" style="margin-top: 0;">Precio del Servicio</div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-8">
                                        <div class="num-reservation"><?= $reserva->nro_reserva ?></div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-4">
                                        <div class="num-reservation"><?= $reserva->monto_total ?> €</div>
                                    </div>
                                </div>
                                <div class="row mt-20">
                                    <div class="col-lg-12 col-md-12 col-xs-8">
                                        <div class="subtitulo-reserva" style="margin-top: 0;">Forma de Pago</div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-4">
                                        <div class="mt-20"><b><?= $reserva->tipoPago->descripcion ?></b></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div align="right" style="margin-top: 30px;">
                    <?= Html::a('Volver a la página de Inicio', ['site/index'], ['class' => 'btn btn-success btn-big', 'style' => ['font-size' => '0.85em !important', 'margin-top' => '10px', 'margin-right' => '20px']]) ?>

                    <?= Html::a('Descargar la Reserva en PDF', '@web/pdf/comprobante_' . $reserva->nro_reserva . '.pdf', ['target' => '_blank', 'class' => 'btn btn-success']) ?>

                </div>

            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">




            </div>
        </div>

    </div>



</div>


<script>
    window.onload = function() {
        setTimeout("goTo('index')", 60000000);
    }

    function goTo(url) {
        window.location = url;
    }
</script>
