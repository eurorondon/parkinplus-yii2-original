<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="generarf-form">

  <?php 
    $form = ActiveForm::begin([
      'id' => 'generarf-form',
      'options' => [
        'autocomplete' => 'off',
      ],
    ]);
  ?>   
  <div class="col-lg-4"> 
    <?= $form->field($model, 'id_reserva')->hiddenInput(['readonly'=> true])->label(false)?>
  </div>

  <div class="row">
    <div class="col-lg-12">
      Realmente desea generar la factura ?
    </div>

    <div align="right" class="form-group col-lg-12" style="margin-top: 15px">
      <?= Html::submitButton('Sí', ['class' => 'btn btn-success', 'style' => 'border-radius:4px']) ?>
      <?= Html::button('No', ['id' => 'no', 'class' => 'btn btn-warning', 'data-dismiss' => 'modal', 'style' => 'border-radius:4px']) ?>
    </div>
  </div>
  <?php ActiveForm::end(); ?>

</div>

<?php   
  $this->registerJs(" 
    $( document ).ready(function() {
      id = $('#id_reserva').val();
      $('#facturasreserva-id_reserva').val(id);
    });
  ");
?>
