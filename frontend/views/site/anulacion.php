<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="fechas-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'descuento-form',
            'options' => [
                'autocomplete' => 'off',
            ],
        ]);
    ?>

    <div class="row">

        <div class="col-lg-12 my-3"> 
            <label class="control-label">Se encuentra usted seguro de anular la reserva ?</label>
            <input type="hidden" name="reserva" value="<?= $reserva ?>">
        </div>
      

        <div align="right" class="col-lg-12">
            <div class="form-group">
                <?= Html::submitButton('Si, Anular', ['class' => 'btn btn-success']) ?>
            </div>
            
        </div>

    </div>
    <?php ActiveForm::end(); ?>

</div>

  