<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;

$Url = Url::to(['site/parada']);

?>

<div class="fechas-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'fechas-form',
            'options' => [
                'autocomplete' => 'off',
            ],
        ]);
    ?>

    <input type="hidden" id="cantdias" name="cantdias">
    <input type="hidden" id="url" value="<?= $Url ?>">

    <div class="row">

        <div align="center" id="tarifa" class="col-lg-12" style="margin-bottom: 20px; font-weight: bold;">TARIFA PREMIUM</div>

        <div class="col-lg-8 col-xs-8">
            <label id="lbl" class="control-label lb" style="font-size: 0.8em">Fecha de Recogida</label>
        </div>

        <div class="col-lg-4 col-xs-4" style="margin-left: -20px">
            <label id="lbl1" class="control-label lb h" style="font-size: 0.8em">Hora</label>
        </div>        

        <div class="col-lg-7 col-xs-7"> 
            <?= $form->field($model, 'fecha_entrada')->textInput(['readonly' => true])->label(false) ?>
        </div>

        <div class="col-lg-5 col-xs-5">  
            <?= $form->field($model, 'hora_entrada')->textInput(['readonly' => true])->label(false) ?>
        </div> 

        <div class="col-lg-8 col-xs-8" style="margin-top: 10px">
            <label id="lbl2" class="control-label lb" style="font-size: 0.8em">Fecha de Devolución</label>
        </div>
        <div class="col-lg-4 col-xs-4" style="margin-left: -20px; margin-top: 10px">
            <label id="lbl3" class="control-label lb h" style="font-size: 0.8em">Hora</label>
        </div>          

        <div class="col-lg-7 col-xs-7">   
            <?= $form->field($model, 'fecha_salida')->textInput(['readonly' => true])->label(false) ?>
        </div>

        <div class="col-lg-5 col-xs-5">   
            <?= $form->field($model, 'hora_salida')->textInput(['readonly' => true])->label(false) ?>
        </div>

        <hr id="hr" style="border:none; margin-top: 100px; width: 250px position: absolute; border-radius: 4px">

        <div class="img-min" id="precio-tarifa" style="color: #fff; margin-top: -70px; padding-top: 18px; font-size: 2em; border-radius: 10px; width: 180px; height: 90px">
            <div id="costo" style="display: inline;"></div> €</div>                


        <div align="right" class="col-lg-12">
            <br>
            <div class="form-group">
                <?= Html::submitButton('Procesar Reserva', ['id' => 'boton-reserva', 'class' => 'btn btn-success btn-block']) ?>
            </div>
            <?= Html::img('@web/images/alert.png', ['class'=>'img img-responsive img-alert', 'style' => 'display:none']) ?><div class="informe-texto" id="informe" style="display: none">Revise las fechas seleccionadas</div>

            <?= Html::img('@web/images/alert.png', ['class'=>'img img-responsive img-alert-parada', 'style' => 'display:none']) ?><div class="informe-texto" id="informe_parada" style="display: none">No Existe Disponibilidad para las Fechas</div>            
            
        </div>

    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php 
    $this->registerJs(" 

        $( document ).ready(function() { 

            valor = $('#costo-servicio').val();
            
            
            fecha_e = $('#fecha-in').val();
            fecha_s = $('#fecha-out').val();
            hora_e = $('#hora-in').val();
            hora_s = $('#hora-out').val();

            cdias = $('#cdias').val();

            $('#cantdias').val(cdias);

            $('#reservas-fecha_entrada').val(fecha_e);
            $('#reservas-fecha_salida').val(fecha_s);

            $('#reservas-hora_entrada').val(hora_e);
            $('#reservas-hora_salida').val(hora_s);            

            url = $('#url').val();

            $.ajax({
                type:'POST',
                url: url,
                data: { fecha_e: fecha_e, hora_e: hora_e, fecha_s: fecha_s, hora_s: hora_s },
                success: function(data) {
                    dato = data;
                    console.log(valor);
                    if (data == 1) {
                        if ( (isNaN(valor)) || (valor == 0.00)  ) {
                            valor = '0.00'
                            $('#costo').html(valor);
                            document.getElementById('boton-reserva').style.display='none';
                            document.getElementById('reservas-fecha_entrada').style.display='none';
                            document.getElementById('reservas-fecha_salida').style.display='none';
                            document.getElementById('reservas-hora_entrada').style.display='none';
                            document.getElementById('reservas-hora_salida').style.display='none';
                            document.getElementById('precio-tarifa').style.display='none';
                            document.getElementById('hr').style.display='none';
                            document.getElementById('tarifa').style.display='none';
                            document.getElementById('lbl').style.display='none';
                            document.getElementById('lbl1').style.display='none';
                            document.getElementById('lbl2').style.display='none';
                            document.getElementById('lbl3').style.display='none';
                            $('#fecha_reserva').css('margin', '220px auto');
                            document.getElementById('informe').style.display='block';
                            $('.img-alert').css('display', 'block');
                        } else {
                            $('#costo').html(valor);
                            $('#fecha_reserva').css('margin', '50px auto');
                        }
                    } else {
                        if ( (isNaN(valor)) || (valor == 0.00)  ) {
                            document.getElementById('boton-reserva').style.display='none';
                            document.getElementById('reservas-fecha_entrada').style.display='none';
                            document.getElementById('reservas-fecha_salida').style.display='none';
                            document.getElementById('reservas-hora_entrada').style.display='none';
                            document.getElementById('reservas-hora_salida').style.display='none';
                            document.getElementById('precio-tarifa').style.display='none';
                            document.getElementById('hr').style.display='none';
                            document.getElementById('tarifa').style.display='none';
                            document.getElementById('lbl').style.display='none';
                            document.getElementById('lbl1').style.display='none';
                            document.getElementById('lbl2').style.display='none';
                            document.getElementById('lbl3').style.display='none';
                            $('#fecha_reserva').css('margin', '220px auto');
                            document.getElementById('informe').innerHTML=data;
                            document.getElementById('informe').style.display='block';
                            $('.img-alert').css('display', 'block');                            
                        } else {
                            document.getElementById('boton-reserva').style.display='none';
                            document.getElementById('reservas-fecha_entrada').style.display='none';
                            document.getElementById('reservas-fecha_salida').style.display='none';
                            document.getElementById('reservas-hora_entrada').style.display='none';
                            document.getElementById('reservas-hora_salida').style.display='none';
                            document.getElementById('precio-tarifa').style.display='none';
                            document.getElementById('hr').style.display='none';
                            document.getElementById('tarifa').style.display='none';
                            document.getElementById('lbl').style.display='none';
                            document.getElementById('lbl1').style.display='none';
                            document.getElementById('lbl2').style.display='none';
                            document.getElementById('lbl3').style.display='none'; 
                            $('#fecha_reserva').css('margin', '220px auto'); 
                            document.getElementById('informe_parada').innerHTML=data;
                            document.getElementById('informe_parada').style.display='block';
                            $('.img-alert-parada').css('display', 'block'); 
                        }
                    }
                }            
            });
        });

    ");
?>
