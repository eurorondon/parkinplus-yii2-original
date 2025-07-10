<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Clientes;
use common\models\Agencias;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use common\models\UserAfiliados;
/* @var $this yii\web\View */
/* @var $model common\models\ReservasSearch */
/* @var $form yii\widgets\ActiveForm */
$terminales = [
    'TERMINAL 1'=>'TERMINAL 1', 'TERMINAL 2'=>'TERMINAL 2',
    'TERMINAL 3'=>'TERMINAL 3','TERMINAL 4'=>'TERMINAL 4'
];
$estados = [
    0 =>'Canceladas', 1 =>'Pendientes', 
    2 =>'Finalizadas', 3 =>'Activas',
    4 =>'Rezagadas'
];

$clientes = ArrayHelper::map(Clientes::find()->orderBy('nombre_completo')->all(), 'id', 'nombre_completo');

$agencias = ArrayHelper::map(Agencias::find()->orderBy('nombre')->all(), 'nombre', 'nombre');

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

<div class="reservas-search">

    <div class="row">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>

        <div class="col-xs-12">
            <div class="subtitulo-reserva">Buscar Reserva</div><br>
        </div>

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>N° de Reserva</label>
            <?= $form->field($model, 'nro_reserva')->label(false) ?>
        </div>
        <div class="col-lg-4 col-md-4 col-xs-12">
            <label>Nombre del Cliente</label>
            <?= $form->field($model, 'id_cliente')->widget(Select2::classname(), [
                'data' => $clientes,
                'options' => ['placeholder' => 'Selecccione un Cliente o Propietario'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>
        </div>        
        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Fecha de Entrada</label>
            <?= $form->field($model, 'fecha_entrada')->widget(DatePicker::classname(), [
                'options' => ['autocomplete' => 'off'],
                'language' => 'es',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,                                       
                ]
            ])->label(false); ?>
        </div>
        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Fecha de Salida</label>
            <?= $form->field($model, 'fecha_salida')->widget(DatePicker::classname(), [
                'options' => ['autocomplete' => 'off'],
                'language' => 'es',
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true,                                       
                ]
            ])->label(false); ?>
        </div>
        <div class="col-lg-2 col-md-2 col-xs-12"></div>

        <div class="col-lg-12"></div>

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Terminal de Entrada</label>
            <?= $form->field($model, 'terminal_entrada')->widget(Select2::classname(), [
                'data' => $terminales,
                'options' => ['placeholder' => 'Selecccione Terminal'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>  
        </div>

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Terminal de Salida</label>
            <?= $form->field($model, 'terminal_salida')->widget(Select2::classname(), [
                'data' => $terminales,
                'options' => ['placeholder' => 'Selecccione Terminal'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>  
        </div>

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>Matrícula</label>
            <?= $form->field($model, 'matricula')->label(false) ?>
        </div>

        <div class="col-lg-1 col-md-1"></div>      

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>Estado</label>
            <?= $form->field($model, 'estatus')->widget(Select2::classname(), [
                'data' => $estados,
                'options' => ['placeholder' => 'Selecccione'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>  
        </div> 

        <div class="col-lg-12"></div>

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Medio de Reserva</label>
            <?= $form->field($model, 'medio_reserva')->widget(Select2::classname(), [
                'data' => $medios,
                'options' => ['placeholder' => 'Selecccione Medio de Reserva'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>  
        </div>     

        <div class="col-lg-3 col-md-3 col-xs-12">
            <label>Agencia</label>
            <?= $form->field($model, 'agencia')->widget(Select2::classname(), [
                'data' => $agencias,
                'options' => ['placeholder' => 'Selecccione Agencia'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>  
        </div>

        <div class="col-lg-2 col-md-2 col-xs-12">
            <label>Factura</label>
            <?= $form->field($model, 'factura')->widget(Select2::classname(), [
                'data' => ['0' => 'NO', '1' => 'SI'],
                'options' => ['placeholder' => 'Selecccione'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ])->label(false); ?>  
        </div>

        <div align="right" class="col-lg-2 col-md-2 col-xs-12" style="margin-top: 15px">
            <?= Html::a ('LIMPIAR', ['index'], ['class' => 'btn btn-warning']) ?>
        </div>

        <div align="right" class="col-lg-2 col-md-2 col-xs-12" style="margin-top: 15px">
            <?= Html::submitButton('BUSCAR RESERVA', ['id' => 'buscar', 'class' => 'btn btn-success']) ?>
        </div> 

        <div class="col-lg-12 col-md-12 col-xs-12"><hr class="linea" style="margin-bottom: 40px"></div>       
        
        <?php ActiveForm::end(); ?>
    </div>

</div>
