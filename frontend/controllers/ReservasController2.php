<?php

namespace frontend\controllers;

use Yii;
use common\models\RegistroPrecios;
use common\models\FacturasReserva;
use common\models\FacturasServicios;
use common\models\Facturas;
use common\models\Factureros;
use common\models\Reservas;
use common\models\Clientes;
use common\models\Coches;
use common\models\Servicios;
use common\models\TipoPago;
use common\models\Configuracion;
use common\models\ReservasServicios;
use common\models\ReservasSearch;
use yii\helpers\BaseArrayHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\db\Query;

class ReservasController extends Controller
{
	public function actionCreate()
	{
		
		$entrada = $_GET['entrada'];
		$salida = $_GET['salida'];
		
		$model = new Reservas();
		$modelC = new Clientes();
		$modelV = new Coches();

		$nro_reserva = Reservas::find()->max('nro_reserva');
		if ($nro_reserva == 0) {
			$correlativo = Configuracion::find()->where(['estatus' => '1', 'tipo_campo' => '2'])->one();
			$nro_reserva = $correlativo->valor_numerico;
			$proxima_reserva = intval($nro_reserva); 
		} else {
			$proxima_reserva = intval($nro_reserva) + 1;    
		}	

        $tipo_documento = [
            'NIF'=>'NIF', 'NIE'=>'NIE','Pasaporte'=>'Pasaporte'
        ]; 			     

		$terminales = [
			'TERMINAL 1'=>'TERMINAL 1', 'TERMINAL 2'=>'TERMINAL 2',
			'TERMINAL 3'=>'TERMINAL 3','TERMINAL 4'=>'TERMINAL 4'
		]; 

		$servicios = Servicios::find()->where(['estatus'=>'1'])->andWhere(['fijo'=>'2'])->all();

		$seguro = Servicios::find()->where(['estatus'=>'1'])->andWhere(['fijo'=>'1'])->all();

		$query = new Query;
		$query  ->select([
			'registro_precios.id_lista', 
			'registro_precios.cantidad',
			'registro_precios.costo AS precio',
			'servicios.*']
		)  
		->from('registro_precios')
		->join('LEFT JOIN', 'servicios',
			'registro_precios.id_lista = servicios.id_listas_precios'); 

		$command = $query->createCommand();
		$precio_diario = $command->queryAll();

		$pagos = TipoPago::find()->where(['estatus'=>'1'])->all();
		$tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion');

		$impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
		foreach ($impuestos as $imp) {
			$tipo_imp = $imp->tipo_impuesto;
			if ($tipo_imp == 1) {
				$iva = $imp->valor_numerico;
			}
		}                          

		if ($model->load(Yii::$app->request->post()) && $modelC->load(Yii::$app->request->post()) && $modelV->load(Yii::$app->request->post())) {

			$modelC->save();
			$modelV->save();

			$id_reserva = ReservasServicios::find()->max('id_reserva');
			$num_reserva = $id_reserva + 1;

			$precio_diario = Servicios::find()->where(['estatus'=>'1'])->andWhere(['fijo'=>'0'])->all(); 

			$seguro = Servicios::find()->where(['estatus'=>'1'])->andWhere(['fijo'=>'1'])->all();

			foreach ($servicios as $ser) {

				$modelR = new ReservasServicios;
				$precio_unitario = $_POST['precio_unitario'.$ser->id];
				$cantidad = $_POST['cantidad'.$ser->id];
				$precio_total = $_POST['precio_total'.$ser->id];
				$tipo_servicio = $_POST['tipo_servicio'.$ser->id];

				if ($cantidad != 0) {
					$modelR->id_reserva = $num_reserva;
					$modelR->id_servicio = $ser->id;
					$modelR->cantidad = $cantidad;
					$modelR->precio_unitario = $precio_unitario;
					$modelR->precio_total = $precio_total;
					$modelR->tipo_servicio = $tipo_servicio;
					$modelR->save();
				}
			}

			if ($precio_diario[0]->fijo == 0) {
				$modelR = new ReservasServicios;
				$modelR->id_reserva = $num_reserva;
				$modelR->id_servicio = $precio_diario[0]->id;
				$modelR->cantidad = $_POST['cant_basico'];
				$modelR->precio_unitario = $precio_diario[0]->costo;
				$modelR->precio_total = $model->costo_servicios;
				$modelR->tipo_servicio = 0;
				$modelR->save();              
			}

			if ($seguro[0]->fijo == 1) {
				$modelR = new ReservasServicios;
				$modelR->id_reserva = $num_reserva;
				$modelR->id_servicio = $seguro[0]->id;
				$modelR->cantidad = $_POST['cant_seguro'];
				$modelR->precio_unitario = $seguro[0]->costo;
				$modelR->precio_total = $_POST['total_seguro'];
				$modelR->tipo_servicio = 1;
				$modelR->save();               
			}            


			$fecha1 = $model->fecha_entrada;
			$model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
			$fecha2 = $model->fecha_salida;
			$model->fecha_salida = date("Y-m-d", strtotime($fecha2));

			$model->estatus = 1;
			$model->condiciones = 1;

			$model->save();

			Yii::$app->session->setFlash('success', 'Su reserva ha sido procesada de manera exitosa.');
		}

		return $this->render('index', [
			'model' => $model,
			'modelC' => $modelC,
			'modelV' => $modelV,
			'proxima_reserva' => $proxima_reserva,
			'tipo_documento' => $tipo_documento,
			'terminales' => $terminales,
			'servicios' => $servicios,
			'seguro' => $seguro,
			'precio_diario' => $precio_diario,
			'tipos_pago' => $tipos_pago, 
			'iva' => $iva,  
			'entrada' => $entrada,
			'salida' => $salida,          
		]);
	}	

}

