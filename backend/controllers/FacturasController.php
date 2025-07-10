<?php

namespace backend\controllers;


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Yii;
use common\models\Facturas;
use common\models\Factureros;
use common\models\Servicios;
use common\models\TipoPago;
use common\models\Conceptos;
use common\models\Configuracion;
use common\models\ListasPrecios;
use common\models\Clientes;
use common\models\FacturasServicios;
use common\models\FacturasReserva;
use common\models\FacturasSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\db\Query;


use common\models\Reservas;
use frontend\models\UserCliente;

/**
 * FacturasController implements the CRUD actions for Facturas model.
 */
class FacturasController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Facturas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FacturasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize = 10;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Facturas model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Facturas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Facturas();
        $conceptos = new Conceptos();

        $series = Factureros::find()->where(['estatus' => '1'])->one();
        $serie = $series->serie;

        $nro_factura = Facturas::find()->max('nro_factura');
        if ($nro_factura == 0) {
            $facturas = Factureros::find()->where(['estatus' => '1'])->one();
            $nro_factura = $facturas->factura_inicio;
            $proxima_factura = $nro_factura;
        } else {
            $proxima_factura = $nro_factura + 1;
        }

        $servicios = Servicios::find()->where(['estatus' => '1'])->orderBy(['fijo' => SORT_ASC])->all();

        $seguro = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '1'])->all();

        $pagos = TipoPago::find()->where(['estatus' => '1'])->all();
        $tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion');

        $query = new Query;
        $query->select([
            'registro_precios.id_lista',
            'registro_precios.cantidad',
            'registro_precios.costo AS precio',
            'servicios.*'
        ])
            ->from('registro_precios')
            ->join(
                'LEFT JOIN',
                'servicios',
                'registro_precios.id_lista = servicios.id_listas_precios'
            );

        $command = $query->createCommand();
        $precio_diario = $command->queryAll();

        $milista = $precio_diario[0]['id_lista'];

        $buscaAgregado = ListasPrecios::find()->where(['id' => $milista])->all();

        $agregado = $buscaAgregado[0]->agregado;

        //echo "<pre>"; var_dump($agregado); echo "</pre>"; die();         

        $impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
        foreach ($impuestos as $imp) {
            $tipo_imp = $imp->tipo_impuesto;
            if ($tipo_imp == 1) {
                $iva = $imp->valor_numerico;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $conceptos->load(Yii::$app->request->post())) {

            $id_usuario = Yii::$app->user->id;
            $id_factura = Facturas::find()->max('id');
            $num_factura = $id_factura + 1;

            $punitario = $_POST['concepto_punitario'];
            $cant = $_POST['concepto_cantidad'];
            //var_dump($cantidad); die();
            $ptotal = $_POST['concepto_ptotal'];

            //var_dump($punitario); die();


            foreach ($servicios as $ser) {
                $modelSF = new FacturasServicios;
                $precio_unitario = $_POST['precio_unitario' . $ser->id];
                $cantidad = $_POST['cantidad' . $ser->id];
                $precio_total = $_POST['precio_total' . $ser->id];
                $tipo_servicio = $_POST['tipo_servicio' . $ser->id];

                if ($cantidad != 0) {
                    $modelSF->id_factura = $num_factura;
                    $modelSF->id_servicio = $ser->id;
                    $modelSF->cantidad = $cantidad;
                    $modelSF->precio_unitario = $precio_unitario;
                    $modelSF->precio_total = $precio_total;
                    $modelSF->tipo_servicio = $tipo_servicio;
                    $modelSF->save();
                }
            }

            $model->save();

            if ($cant != 0) {
                $conceptos->punitario = $punitario;
                $conceptos->cantidad = $cant;
                $conceptos->ptotal = $ptotal;
                $conceptos->id_factura = $model->id;

                $conceptos->save();
            }

            Yii::$app->session->setFlash('success', 'La Factura se ha generado de manera exitosa.');
            return $this->redirect(['facturas/index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'serie' => $serie,
            'proxima_factura' => $proxima_factura,
            'servicios' => $servicios,
            'seguro' => $seguro,
            'tipos_pago' => $tipos_pago,
            'iva' => $iva,
            'precio_diario' => $precio_diario,
            'agregado' => $agregado,
            'conceptos' => $conceptos,
        ]);
    }

    /**
     * Updates an existing Facturas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        return $this->renderAjax('update', [
            'model' => $this->findModel($id),
        ]);
    }


    //Anular Factura

    public function actionAnular($id)
    {

        $model = Facturas::findOne($id);

        $model->estatus = 0;

        $model->save();


        Yii::$app->session->setFlash('success', 'La Factura fúe Anulada de manera exitosa.');
        return $this->redirect(['facturas/index']);
    }

    /**
     * Deletes an existing Facturas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionViewPdf($id)
    {


        $content = $this->renderPartial('_reportView', ['model' => $this->findModel($id)]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '../web/css/reportes.css',
            'methods' => [
                'SetTitle' => Yii::$app->name . ' | Factura',
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    public function actionSendFactura($id)
    {

        $buscaReserva = FacturasReserva::find()->where(['id_factura' => $id])->one();

        $datosFactura = Facturas::findOne($id);
        $datos['razon_social'] = $datosFactura->razon_social;
        $datos['nro_factura'] = $datosFactura->nro_factura;

        if ($buscaReserva == NULL) {
            $datos['correo_electronico'] = '';
            $datos['msje'] = 'La Factura No tiene reserva Asociada';
        } else {
            $id_cliente = $buscaReserva->reserva->id_cliente;
            $nro_reserva = $buscaReserva->reserva->nro_reserva;

            $datosCliente = Clientes::findOne($id_cliente);
            $datos['correo_electronico'] = $datosCliente->correo;
            $datos['msje'] = 'La Factura posee una reserva asociada. N° de Reserva : ' . $nro_reserva;
        }

        if (Yii::$app->request->post()) {

            try {
                $mail = $_POST['correo'];
                $nro_factura = $_POST['nro_factura'];

                $content = $this->renderPartial('_reportView', ['model' => $this->findModel($id)]);

                $pdf = new Pdf([
                    'mode' => Pdf::MODE_CORE,
                    'format' => Pdf::FORMAT_A4,
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'destination' => Pdf::DEST_FILE,
                    'filename' => '../web/pdf/facturas/factura_' . $nro_factura . '.pdf',
                    'content' => $content,
                    'cssFile' => '../web/css/reportes.css',
                    'options' => ['title' => 'Factura - Parking Plus'],
                    'methods' => [
                        'SetFooter' => ['{PAGENO}'],
                    ]
                ]);

                $pdf->render();

                $correo = Yii::$app->mailer->compose();

                $correo->setTo($mail)
                    ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                    ->setSubject('Factura de Reserva - Parking Plus')
                    ->attach('../web/pdf/facturas/factura_' . $nro_factura . '.pdf')
                    ->send();

                unlink('../web/pdf/facturas/factura_' . $nro_factura . '.pdf');

            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'La factura no se pudo enviar, problema con el servidor de email.');
                return $this->redirect(['reservas/index']);
            }

            Yii::$app->session->setFlash('success', 'La Factura ha sido enviada de manera exitosa.');
            return $this->redirect(['facturas/index']);

        }

        return $this->renderAjax('enviar_factura', [
            'datos' => $datos,
        ]);
    }

    // Fechas para reporte de Facturación
    public function actionRptfacturacion()
    {
        if (Yii::$app->request->post()) {
            $fecha_desde = date('Y-m-d', strtotime($_POST['fecha_desde']));
            $fecha_hasta = date('Y-m-d', strtotime($_POST['fecha_hasta']));

            $facturas = (new \yii\db\Query())
                ->select(['*'])
                ->from('facturas')
                ->where(["between", "date_format(created_at, '%Y-%m-%d')", $fecha_desde, $fecha_hasta])
                ->all();

            if (!empty($facturas)) {

                header('Content-Type:application/vnd.ms-excel; charset=utf-8');
                header('Content-Disposition: attach; filename = facturas.xls');

                $titulos = array(
                    '#',
                    htmlentities('N° de Factura'),
                    'Concepto',
                    'Fecha',
                    htmlentities('Razón Social'),
                    'CIF',
                    'Subtotal',
                    'IVA',
                    'Total'
                );

                for ($i = 0; $i < count($facturas); $i++) {
                    $data[$i]['nro'] = $i + 1;
                    $data[$i]['nro_factura'] = $facturas[$i]['serie'] . '-' . $facturas[$i]['nro_factura'];
                    $data[$i]['concepto'] = 'Servicio de Aparcamiento';
                    $data[$i]['fecha'] = date('d-m-Y', strtotime($facturas[$i]['created_at']));
                    $data[$i]['razon_social'] = htmlentities($facturas[$i]['razon_social']);
                    $data[$i]['cif'] = $facturas[$i]['nif'];
                    $data[$i]['subtotal'] = round($facturas[$i]['monto_factura'], 2);
                    $data[$i]['iva'] = round($facturas[$i]['monto_impuestos'], 2);
                    $data[$i]['total'] = round($facturas[$i]['monto_total'], 2);
                }

                echo '<table style=width:100%><tr style=height:45px>';
                foreach ($titulos as $h) {
                    echo '<th style=background-color:#ccc>' . $h . '</ th>';
                }
                echo '</tr>';
                $i = 0;
                foreach ($data as $row) {
                    if ($i % 2 == 0)
                        $color = "#FFFFF";
                    else
                        $color = "#EFEFEF";

                    echo '<tr style=height:30px>';
                    foreach ($row as $v) {
                        echo '<td style=background-color:' . $color . '>' . $v . '</td>';
                    }
                    echo '</tr>';
                    $i++;
                }
                echo '</table>';
            } else {

                Yii::$app->session->setFlash('info', 'No existen facturas en la fecha seleccionada. Verifique las fechas ingresadas e intente nuevamente !');

                return $this->redirect(['facturas/index']);
            }
            exit;
        }

        return $this->renderAjax('fechas_factura');
    }

    /**
     * Finds the Facturas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Facturas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Facturas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function loadModel($id)
    {
        if (($modelSF = FacturasServicios::find()->where(['id_factura' => $id])->all()) !== null) {
            return $modelSF;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
