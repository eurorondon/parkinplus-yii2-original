<?php
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use kartik\date\DatePicker;

    $mes_anterior = date('m', strtotime('-1 month'));
    $ayo = date('Y');
    $dia = date("d", mktime(0,0,0, $mes_anterior+1, 0, $ayo));
     
    $ultimo_dia = date('d-m-Y', mktime(0,0,0, $mes_anterior, $dia, $ayo));
    $primer_dia = date('d-m-Y', mktime(0,0,0, $mes_anterior, 1, $ayo));
?>

<div class="fechas-factura-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'fechas-factura-form',
            'options' => [
                'autocomplete' => 'off',
            ],
        ]);
    ?>

    <div class="row">
        <div class="col-lg-12">
            <div align="justify" class="alert alert-info" style="margin:10px 0px;"><small>Ingrese el rango de fechas para su reporte (DESDE - HASTA). Se generará un reporte en formato .xls</small></div>
            <hr>
        </div>

        <div class="col-lg-6">
            <label class="control-label">DESDE</label>
            <?= DatePicker::widget([
                'name' => 'fecha_desde',
                'value' => $primer_dia,
                'language' => 'es',
                'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy',
                ]
            ]) ?>
        </div>

        <div class="col-lg-6">
            <label class="control-label">HASTA</label>
            <?= DatePicker::widget([
                'name' => 'fecha_hasta',
                'value' => $ultimo_dia,
                'language' => 'es',
                'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy',
                ]
            ]) ?>
        </div>
               
        <div align="right" class="col-lg-12">
            <br><br>
            <div class="form-group">
                <span class="btn btn-danger" style="margin-right:15px" data-dismiss="modal" aria-label="Close">Cerrar</span>
                <?= Html::submitButton('Generar Reporte', ['class' => 'btn btn-success']) ?>
            </div>
            
        </div>

    </div>
    <?php ActiveForm::end(); ?>
</div>