<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

$Url = Url::to(['reservas/opinion']);

?>

<div class="fechas-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'valoracion-form',
            'options' => [
                'autocomplete' => 'off',
            ],
        ]);
    ?>

    <div class="row">

        <div class="col-lg-7"> 
            <label class="control-label">Cliente</label>
            <?= Select2::widget([
                'name' => 'id_cliente',
                'data' => $listaClientes,
                'options' => ['id' => 'id_cliente', 'placeholder' => 'Selecccione un Cliente'],
            ]) ?>
        </div>

        <div class="col-lg-5">
            <label class="control-label">N° de Reserva</label>
            <select name="reserva" id="reserva" class="form-control" disabled="true"></select>            
        </div>

        <div class="col-lg-12"><br></div> 

        <div class="col-lg-7">  
            <input type="hidden" id="url" value="<?= $Url ?>">
            <label class="control-label">Correo Electrónico</label>
            <input type="text" class="form-control" name="correo" id="correo" disabled="true">
        </div>

        <div align="right" class="col-lg-5" style="margin-top: 15px">
            <div class="form-group">
                <?= Html::submitButton('Enviar Enlace', ['class' => 'btn btn-success']) ?>
            </div>
            
        </div>
        <div class="col-lg-12"><br></div> 

    </div>
    <?php ActiveForm::end(); ?>

</div>


<?php 
  $this->registerJs(" 

    $('#id_cliente').on('change', function(e) {
      $('option', '#reserva').remove(); 
      url = $('#url').val();
      id_cliente = $('#id_cliente').val();
      e.preventDefault();
      $.ajax({
        type:'POST',
        url: url,
        data: { id_cliente: id_cliente },
        success: function(data) {
          $('#correo').prop('disabled', false);
          $('#reserva').prop('disabled', false);               
          dato = data.split('/')
          $('#correo').val(dato[0]);
          lista = JSON.parse(dato[1]);
          $.each(lista, function(id, reserva) {
            $('#reserva').append('<option value=' + reserva.id + '>' + reserva.reserva + '</option>');
          });          
        }            
      });
    }); 

  ");
?>
