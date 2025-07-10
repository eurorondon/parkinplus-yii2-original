<?php

namespace backend\controllers;

use Yii;
use common\models\Coches;
use common\models\Clientes;
use common\models\CochesSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CochesController implements the CRUD actions for Coches model.
 */
class CochesController extends Controller
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
     * Lists all Coches models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CochesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize=15;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Coches model.
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
     * Creates a new Coches model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coches();

        $clientes = Clientes::find()->all();
        $listaClientes = ArrayHelper::map($clientes, 'id', 'nombre_completo');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'El Vehículo ha sido agregado de manera exitosa.');
            return $this->redirect(['coches/index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'listaClientes' => $listaClientes,
        ]);
    }

    /**
     * Updates an existing Coches model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $clientes = Clientes::find()->all();
        $listaClientes = ArrayHelper::map($clientes, 'id', 'nombre_completo');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'El Vehículo ha sido modificado de manera exitosa.');
            return $this->redirect(['coches/index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'listaClientes' => $listaClientes,
        ]);
    }

    /**
     * Deletes an existing Coches model.
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
     * Finds the Coches model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coches the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coches::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
