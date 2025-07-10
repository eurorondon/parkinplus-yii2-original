<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$Url = Url::to(['reservas/check']);

?>

<div class="fechas-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'checkin-form',
            'options' => [
                'autocomplete' => 'off',
            ],
        ]);
    ?>

    <div class="row">

        <div class="col-lg-4"> 
            <label class="control-label">N° de Reserva</label>
            <?= Select2::widget([
                'name' => 'id_reserva',
                'data' => $listaReservas,
                'options' => ['id' => 'id_reserva', 'placeholder' => 'Selecccione'],
                'pluginOptions' => [
                    'allowClear' => true,
					'dropdownParent' => new yii\web\JsExpression('$("#checkin")')
                ],
            ]) ?>
        </div>

        <div class="col-lg-8">
            <label class="control-label">Cliente</label>
            <input type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" disabled="true">         
        </div>

        <div class="col-lg-12"><br></div> 

        <div class="col-lg-7">  
            <input type="hidden" id="url" value="<?= $Url ?>">
            <label class="control-label">Correo Electrónico</label>
            <input type="text" class="form-control" name="correo" id="correo" disabled="true">
        </div>
        
        <input type="hidden" class="form-control" name="id_cliente" id="id_cliente">

        <div align="right" class="col-lg-5" style="margin-top: 15px">
            <div class="form-group">
                <?= Html::submitButton('Enviar Confirmación', ['class' => 'btn btn-success']) ?>
            </div>
            
        </div>
        <div class="col-lg-12"><br></div> 

    </div>
    <?php ActiveForm::end(); ?>

</div>


<?php 
  $this->registerJs(" 

    $('#id_reserva').on('change', function(e) {
      url = $('#url').val();
      id_reserva = $('#id_reserva').val();
      e.preventDefault();
      $.ajax({
        type:'POST',
        url: url,
        data: { id_reserva: id_reserva },
        success: function(data) {
          $('#correo').prop('disabled', false);
          $('#id_reserva').prop('disabled', false);               
          dato = data.split('/')
          $('#nombre_cliente').val(dato[0]);
          $('#correo').val(dato[1]);          
          $('#id_cliente').val(dato[2]);
        }            
      });
    }); 

  ");
?>
