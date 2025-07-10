<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;

$this->title = Yii::$app->name.' | Configuración - APIS';
$this->params['breadcrumbs'][] = 'Configuración - APIS';

?>

<div class="configura_apis-form">

    <div class="panel panel-default panel-index">
        <div class="panel-heading caja-title">Configuración - APIS</div>
        <div class="panel-body gs1">
            <div class="row"> 
                <?php 
                    $form = ActiveForm::begin([
                        'id' => 'apis-form',
                        'options' => [
                            'autocomplete' => 'off',
                        ],
                    ]);
                ?>
                <div class="col-lg-3">
                    <label class="control-label">Parámetro</label>
                    <?= Select2::widget([
                        'name' => 'periodo',
                        'data' => [
                            'arrival' => 'ENTRADA',
                            'departure' => 'SALIDA',
                            'created_at' => 'CREADA',
                            'updated_at' => 'MODIFICADA',
                            'canceled_at' => 'CANCELADA'
                        ]
                    ]) ?>                                
                </div>

                <div class="col-lg-3">
                    <label class="form-label">Fecha Desde</label>
                    <?= DatePicker::widget([
                        'name' => 'desde',
                        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                        'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                        'language' => 'es',
                        'value' => date('d-m-Y'),
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]) ?>
                </div>

                <div class="col-lg-3">
                    <label class="form-label">Fecha Hasta</label>
                    <?= DatePicker::widget([
                        'name' => 'hasta',
                        'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                        'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                        'language' => 'es',
                        'value' => date('d-m-Y'),
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy'
                        ]
                    ]) ?>
                </div>

                <div align="right" class="col-lg-3" style="margin-top:15px">
                    <div class="form-group">
                        <?= Html::submitButton('Filtrar Datos', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>                    
                <?php ActiveForm::end(); ?>
            </div>

            <br>
            <hr>

            <?php if ($datos != null) { ?>
            <div class="row">
                <div class="col-lg-12">
                    <table class="table table-striped" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Marca - Modelo</th>
                                <th>Matrícula</th>
                                <th>Fecha / Hora de Entrada</th>
                                <th>Fecha / Hora de Salida</th>
                                <th>Días</th>
                                <th>Precio</th>         
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                foreach ($datos as $reserva) { 
                                    $nombre = $reserva['name'];
                                    $telefono = $reserva['phone'];
                                    $modelo = $reserva['car_brand_model'];
                                    $matricula = $reserva['car_license_plate'];
                                    $fecha_entrada = $reserva['arrival_date'];
                                    $hora_entrada = $reserva['arrival_time'];
                                    $fecha_salida = $reserva['departure_date'];
                                    $hora_salida = $reserva['departure_time'];  
                                    $dias = $reserva['days'];   
                                    $precio = $reserva['total_price'];
                            ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= strtoupper($nombre) ?></td>
                                <td><?= $telefono ?></td>
                                <td><?= strtoupper($modelo) ?></td>
                                <td><?= $matricula ?></td>
                                <td><?= date('d-m-Y', strtotime($fecha_entrada)).' / '.$hora_entrada ?></td>
                                <td><?= date('d-m-Y', strtotime($fecha_salida)).' / '.$hora_salida ?></td>
                                <td align="center"><?= $dias ?></td>
                                <td align="center"><?= number_format($precio,2) ?></td>
                            </tr>   
                            <?php 
                                $i++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col-lg-5">        
                        <div class="alert alert-danger" style="margin-left: 0px; margin-top: 15px"> 
                            No existes datos segun los filtros seleccionados
                        </div>
                    </div>
                </div>               
            <?php } ?>            
        </div>
    </div>
    





</div>

<script type="text/javascript">
    
    function muestraValores() {
        agencia = $("#api_name").val();
        if (agencia == 'AG001') {
            $("#agencia01").css('display','block');
        }

    }

</script>