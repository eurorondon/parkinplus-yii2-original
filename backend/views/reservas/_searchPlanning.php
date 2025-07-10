<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Clientes;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\ReservasSearch */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="reservas-search">

    <div class="row">
        <?php $form = ActiveForm::begin([
            'action' => ['planning'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>

        <div class="col-lg-3 col-md-3 col-xs-12" style="margin-top: 10px;">
            <?= $form->field($model, 'fecha_busca')->widget(DatePicker::classname(), [
                'options' => ['id' => 'fecha_busca', 'autocomplete' => 'off', 'onfocus' => 'blur()'],
                'language' => 'es',
                'pluginOptions' => [
                    'orientation' => 'bottom left',
                    'autoclose'=>true,
                    'format' => 'dd-mm-yyyy',                                       
                ]
            ])->label(false); ?>


        </div>

        <div class="col-lg-2 col-md-2 col-xs-12 tright">
            <div class="form-group">
                <?= Html::submitButton('BUSCAR LISTAS', ['class' => 'btn btn-success', 'id' => 'lista']) ?>
            </div>
        </div>

        <div class="col-lg-1 col-md-1 col-xs-4 mtop">
            <div class="form-group">
                <?= Html::submitButton('<span class="glyphicon glyphicon-hand-left" aria-hidden="true"></span> &nbsp;&nbsp;&nbsp;AYER', ['class' => 'btn btn-primary', 'id' => 'lista_ayer']) ?>
            </div>
        </div> 

        <div class="col-lg-1 col-md-1 col-xs-3 mtop">
            <div class="form-group">
                <?= Html::submitButton('&nbsp; HOY &nbsp;', ['class' => 'btn btn-success', 'id' => 'lista_hoy']) ?>
            </div>
        </div>

        <div class="col-lg-1 col-md-1 col-xs-5 mlmay mtop">
            <div class="form-group">
                <?= Html::submitButton('MAÑANA &nbsp;&nbsp;&nbsp; <span class="glyphicon glyphicon-hand-right" aria-hidden="true"></span>', ['class' => 'btn btn-primary', 'id' => 'lista_mayana']) ?>
            </div>
        </div>                          

        <div class="col-lg-3 col-md-3 col-xs-12"></div>

        <div align="right" class="col-lg-4 col-md-4 col-xs-12 mtop">
            <div id="pdf" style="display: none">
                <div class="form-group">
                    <?= Html::a('Planning', [
                        '/reservas/generar-pdf',
                        'fecha' => $model->fecha_busca,
                        'tipo' => 1,
                    ], [
                        'class' => 'btn btn-warning',
                        'data-pjax' => 0,
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Imprimir Planning'
                    ]) ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?= Html::a('Rutas', [
                        '/reservas/generar-pdf',
                        'fecha' => $model->fecha_busca,
                        'tipo' => 2,
                    ], [
                        'class' => 'btn btn-warning',
                        'data-pjax' => 0,
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Imprimir Control de Rutas'
                    ]) ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?= Html::a('Reporte 2', [
                        '/reservas/generar-pdf',
                        'fecha' => $model->fecha_busca,
                        'tipo' => 3,
                    ], [
                        'class' => 'btn btn-warning',
                        'data-pjax' => 0,
                        'target' => '_blank',
                        'data-toggle' => 'tooltip',
                        'title' => 'Resumen con columnas personalizadas'
                    ]) ?>
                </div>
            </div>
        </div>
        

        <div class="col-lg-12 col-md-12 col-xs-12"><hr class="linea"></div>       
        
        <?php ActiveForm::end(); ?>
    </div>

</div>

<?php   
    $this->registerJs(" 
      $( document ).ready(function() {
        fecha = $('#reservassearch-fecha_busca').val();
        if (fecha != '') {
            $('#pdf').css('display', 'block');
        } else {
            $('#pdf').css('display', 'none');
        }

      });

      $('#lista').click(function() {
        fecha = $('#reservassearch-fecha_busca').val();
        if (fecha != '') {
            $('#pdf').css('display', 'block');
        } else {
            $('#pdf').css('display', 'none');
        }
      });

      $('#lista_ayer').click(function() {
        hoy = new Date();
        DIA_EN_MILISEGUNDOS = 24 * 60 * 60 * 1000;
        fecha_ayer = new Date(hoy.getTime() - DIA_EN_MILISEGUNDOS);
        month = fecha_ayer.getMonth()+1;
        day = fecha_ayer.getDate();
        ayer = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + fecha_ayer.getFullYear();
        $('#fecha_busca').val(ayer);
        fecha = $('#reservassearch-fecha_busca').val();
        if (fecha != '') {
            $('#pdf').css('display', 'block');
        } else {
            $('#pdf').css('display', 'none');
        }
      });

      $('#lista_hoy').click(function() {
        d = new Date();
        month = d.getMonth()+1;
        day = d.getDate();
        hoy = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + d.getFullYear();
        $('#fecha_busca').val(hoy);
        fecha = $('#reservassearch-fecha_busca').val();
        if (fecha != '') {
            $('#pdf').css('display', 'block');
        } else {
            $('#pdf').css('display', 'none');
        }
      });

      $('#lista_mayana').click(function() {
        hoy = new Date();
        DIA_EN_MILISEGUNDOS = 24 * 60 * 60 * 1000;
        fecha_mayana = new Date(hoy.getTime() + DIA_EN_MILISEGUNDOS);
        month = fecha_mayana.getMonth()+1;
        day = fecha_mayana.getDate();
        mayana = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + fecha_mayana.getFullYear();
        $('#fecha_busca').val(mayana);
        fecha = $('#reservassearch-fecha_busca').val();
        if (fecha != '') {
            $('#pdf').css('display', 'block');
        } else {
            $('#pdf').css('display', 'none');
        }
      });           

    ");
?>        