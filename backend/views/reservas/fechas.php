<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use kartik\time\TimePicker;
use kartik\select2\Select2;
use common\models\UserAfiliados;

$id_usuario = Yii::$app->user->id;

$buscarAfiliado = UserAfiliados::find()->where(['user_id' => $id_usuario])->one();
if (!empty($buscarAfiliado)) {
    $tipo_afiliado = $buscarAfiliado['tipo_afiliado'];
} else {
    $tipo_afiliado = 0;     
}

if ($tipo_afiliado == 0) {
    $medios = [
        1 =>'Secretaria', 2 =>'Agencia', 3=>'Web', 4=>'Universidad'
    ];
} else {
    $medios = [
        4 =>'Universidad'
    ];    
}

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

    <div class="row">

        <div class="col-lg-7"> 
            <label class="control-label">Fecha de Recogida</label>
            <?= $form->field($model, 'fecha_entrada')->widget(DatePicker::classname(), [
                'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                'language' => 'es',
                'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy',
                    'startDate'=> date('d-m-Y'),
                    'todayHighlight' => true,                                        
                ]
            ])->label(false); ?>
        </div>

        <div class="col-lg-5">
            <label class="control-label">Hora</label>
            <?= $form->field($model, 'hora_entrada')->widget(TimePicker::classname(), [
                'options' => ['style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
                'pluginOptions' => [
                    'showMeridian' => false,
                ]
            ])->label(false);?>
        </div>

        <div class="col-lg-7" style="margin-top: 15px"> 
            <label class="control-label">Fecha de Devolución</label>
            <?= $form->field($model, 'fecha_salida')->widget(DatePicker::classname(), [
                'options' => ['autocomplete' => 'off', 'onfocus' => 'blur()'],
                'language' => 'es',
                'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy',
                    'startDate'=> date('d-m-Y'),
                    'todayHighlight' => true,                                        
                ]
            ])->label(false); ?>
        </div>

        <div class="col-lg-5" style="margin-top: 15px">
            <label class="control-label">Hora</label>
            <?= $form->field($model, 'hora_salida')->widget(TimePicker::classname(), [
                'options' => ['style' => 'border-radius:8px !important; border-top-right-radius:0px !important; border-bottom-right-radius:0px !important'],
                'pluginOptions' => [
                    'showMeridian' => false,
                ]
            ])->label(false);?>
        </div>

        <div class="col-lg-7" style="margin-top: 15px">
            <?= $form->field($model, 'medio_reserva')->widget(Select2::classname(), [
                'data' => $medios,
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]); ?>
        </div>         

        <div class="col-lg-5" style="margin-top: 15px">
            <div id="listaAg">
                <?= $form->field($model, 'agencia')->widget(Select2::classname(), [
                    'data' => $listaAgencias,
                    'options' => ['placeholder' => 'Selecccione la Agencia'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>                

        <div align="right" class="col-lg-12">
            <br>
            <div class="form-group">
                <?= Html::submitButton('Procesar Reserva', ['class' => 'btn btn-success']) ?>
            </div>
            
        </div>

    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php   
    $this->registerJs(" 

      $( document ).ready(function() {
        $('#listaAg').css('display', 'none');

      });

      $('#reservas-medio_reserva').change(function() {
        id = $('#reservas-medio_reserva').val();
        if (id == 2) {
            $('#listaAg').css('display', 'block');
        } else {
            $('#listaAg').css('display', 'none');
        }
      });


    ");
?>    