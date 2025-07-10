<?php

namespace backend\controllers;

use Yii;
use common\models\Paradas;
use common\models\ParadaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ParadasController implements the CRUD actions for Paradas model.
 */
class ParadasController extends Controller
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
     * Lists all Paradas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ParadaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Paradas model.
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
     * Creates a new Paradas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Paradas();

        if ($model->load(Yii::$app->request->post())) {

            $fecha_ini = date('Y-m-d', strtotime($model->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($model->fecha_fin));

            $model->fecha_inicio = $fecha_ini;    
            $model->fecha_fin = $fecha_fin;
            $model->save();

            Yii::$app->session->setFlash('success', 'El Registro de Parada ha sido agregado de manera exitosa.');
            return $this->redirect(['paradas/index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Paradas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->fecha_inicio = date('d-m-Y', strtotime($model->fecha_inicio));
        $model->fecha_fin = date('d-m-Y', strtotime($model->fecha_fin));

        if ($model->load(Yii::$app->request->post())) {
            $fecha_ini = date('Y-m-d', strtotime($model->fecha_inicio));
            $fecha_fin = date('Y-m-d', strtotime($model->fecha_fin));

            $model->fecha_inicio = $fecha_ini;    
            $model->fecha_fin = $fecha_fin;
            $model->save();

            Yii::$app->session->setFlash('success', 'El Registro de Parada ha sido modificado de manera exitosa.');
            return $this->redirect(['paradas/index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Paradas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'El Registro de Parada ha sido eliminado de manera exitosa.');
        return $this->redirect(['paradas/index']);
    }

    /**
     * Finds the Paradas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Paradas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Paradas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
