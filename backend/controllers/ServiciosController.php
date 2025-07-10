<?php

namespace backend\controllers;

use Yii;
use common\models\Servicios;
use common\models\ListasPrecios;
use common\models\ServiciosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * ServiciosController implements the CRUD actions for Servicios model.
 */
class ServiciosController extends Controller
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
     * Lists all Servicios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiciosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Servicios model.
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
     * Creates a new Servicios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Servicios();

        $estatus = [
            '0'=>'Inactivo', '1'=>'Activo'
        ];  

        $modo = [
            '0'=>'Servicio Opcional', '1'=>'Servicio Fijo', '2'=>'Servicio Extra'
        ]; 

        $listas = ListasPrecios::find()->all();

        $listas_precios = ArrayHelper::map($listas, 'id', 'nombre');

        if ($model->load(Yii::$app->request->post()) ) {

            $model->save();

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'El Servicio ha sido agregado de manera exitosa.');
                return $this->redirect(['servicios/index']);                
            } else {

                Yii::$app->session->setFlash('error', 'El Servicio NO pudo ser agregado.');
                return $this->redirect(['servicios/index']);
            } 
        }      

        return $this->renderAjax('create', [
            'model' => $model,
            'estatus' => $estatus,
            'modo' => $modo,
            'listas_precios' => $listas_precios,
        ]);
    }

    /**
     * Updates an existing Servicios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $estatus = [
            '0'=>'INACTIVO', '1'=>'ACTIVO'
        ]; 

        $modo = [
            '0'=>'Servicio Opcional', '1'=>'Servicio Fijo', '2'=>'Servicio Extra'
        ];    

        $listas = ListasPrecios::find()->all();

        $listas_precios = ArrayHelper::map($listas, 'id', 'nombre');             

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'estatus' => $estatus,
            'modo' => $modo,
            'listas_precios' => $listas_precios,
        ]);
    }

    /**
     * Deletes an existing Servicios model.
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

    /**
     * Finds the Servicios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Servicios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Servicios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
