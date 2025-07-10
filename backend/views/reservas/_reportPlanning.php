<?php
use kartik\grid\GridView;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\Clientes;
use yii\helpers\Html;
use yii\helpers\Url;

$fecha = date('d-m-Y', strtotime($fecha));

?>

<?= Html::img('@web/images/logo_factura.png', ['style'=> ['width' => '250px']]);?>
<div style="position: absolute; top: 0.8cm; left: 15.2cm; font-size: 12px; text-transform: normal">Parkingplus.es<br>Marichal 4 Parking S.L<br>C/Pañeria 38 2do IZQ. CP 28037.<br>Madrid (Madrid).</div>

<br><br>

<div style="margin-top: -10px; font-size: 14px;	text-align: center;	font-weight: bold;
	text-transform: uppercase;">Planning de Reservas (<?= $fecha ?>)</div>
<hr style="margin-bottom: 3px; margin-top: 1cm">
<table>
	<tr>
		<td width="20cm"><div style="text-transform: uppercase;	font-size: 11px; 	font-weight: bold;"><b>Listado de Reservas a Recibir</b></div></td>
	</tr>
</table>
<hr style="margin-top: 3px;">

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'summary' => '', 
	'striped' => true,
	'condensed' => true,
	'responsive' => true,
	'itemLabelSingle' => 'Reserva',
	'itemLabelPlural' => 'Reservas',
	'columns' => [
		[                      
			'header' => 'N°',
			'class' => 'kartik\grid\SerialColumn',
			'headerOptions' => ['style' => 'text-align:center'],
			'contentOptions' => ['style' => 'white-space: normal; text-align:center; vertical-align:middle'],			
		],

		[
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'contentOptions' => ['style' => 'white-space: normal; text-align:center'],
			'attribute' => 'nro_reserva', 
			'width' => '0.5cm',
		],

		[
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'contentOptions' => ['style' => 'white-space: normal; text-transform:uppercase'],
			'attribute' => 'id_cliente', 
			'label' => 'Cliente',   
			'value' => 'cliente.nombre_completo',
			'width' => '4cm',
		],

		[		
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'attribute' => 'terminal_entrada', 
			'label' => 'Terminal',
			'width' => '2cm',
		],

		[
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'attribute' => 'hora_entrada', 
			'width' => '1cm',
		],		
		
		[
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'contentOptions' => ['style' => 'white-space: normal; text-transform:uppercase; text-align:center; font-weight:bold;'],		
			'attribute' => 'coche.matricula', 
			'width' => '1cm',
		],

        [
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'contentOptions' => ['style' => 'white-space: normal; text-transform:uppercase'],
			'attribute' => 'coche.marca',
            'width' => '2cm',
            'value' => function($model) {
            	return $model->coche->marca.' '.$model->coche->modelo;
            },                
        ], 
        
        // ADD ER 29-06
        [
        	'header' => 'Monto',
        	'attribute' => 'monto_total',
        	'format' => ['decimal', 2],
        	'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
        	'contentOptions' => ['style' => 'text-align:right; white-space: nowrap'],
        	'value' => function ($model) {
        		return $model->monto_total ?? 0;
        	},
        	'width' => '2cm',
        ],

        
	],
]); ?>

<pagebreak/>

<hr style="margin-bottom: 3px;">
<table>
	<tr>
		<td width="20cm"><div style="text-transform: uppercase;	font-size: 11px; 	font-weight: bold;"><b>Listado de Reservas a Entregar</b></div></td>
	</tr>
</table>
<hr style="margin-top: 3px;">

<?= GridView::widget([
	'dataProvider' => $dataProvider1,
	'summary' => '', 
	'striped' => true,
	'condensed' => true,
	'responsive' => true,
	'itemLabelSingle' => 'Reserva',
	'itemLabelPlural' => 'Reservas',
	'columns' => [
		[                      
			'header' => 'N°',
			'class' => 'kartik\grid\SerialColumn',
			'headerOptions' => ['style' => 'text-align:center'],
			'contentOptions' => ['style' => 'white-space: normal; text-align:center'],			
		],

		[
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'contentOptions' => ['style' => 'white-space: normal; text-align:center'],
			'attribute' => 'nro_reserva', 
			'width' => '0.5cm',
		],

		[
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'contentOptions' => ['style' => 'white-space: normal; text-transform:uppercase'],
			'attribute' => 'id_cliente', 
			'label' => 'Cliente',   
			'value' => 'cliente.nombre_completo',
			'width' => '4cm',
		],

		[		
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'attribute' => 'terminal_salida', 
			'label' => 'Terminal',
			'width' => '2cm',
		],

		[
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'attribute' => 'hora_salida', 
			'width' => '1cm',
		],		
		
		[
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'contentOptions' => ['style' => 'white-space: normal; text-transform:uppercase; text-align:center; font-weight:bold;'],			
			'attribute' => 'coche.matricula', 
			'width' => '1cm',
		],

        [
			'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
			'contentOptions' => ['style' => 'white-space: normal; text-transform:uppercase'],
			'attribute' => 'coche.marca',
            'width' => '2cm',
            'value' => function($model) {
            	return $model->coche->marca.' '.$model->coche->modelo;
            },                
        ],
        
        // ADD ER 29-06
        
        [
        	'header' => 'Monto',
        	'attribute' => 'monto_total',
        	'format' => ['decimal', 2],
        	'headerOptions' => ['style' => 'text-align:center; text-transform: uppercase'],
        	'contentOptions' => ['style' => 'text-align:right; white-space: nowrap'],
        	'value' => function ($model) {
        		return $model->monto_total ?? 0;
        	},
        	'width' => '2cm',
        ],

	],
]); ?>






