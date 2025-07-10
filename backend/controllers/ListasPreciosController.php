<?php

namespace backend\controllers;

use Yii;
use common\models\ListasPrecios;
use common\models\RegistroPrecios;
use common\models\ListasPreciosSearch;
use common\models\Servicios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ListasPreciosController implements the CRUD actions for ListasPrecios model.
 */
class ListasPreciosController extends Controller
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
     * Lists all ListasPrecios models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ListasPreciosSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ListasPrecios model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $modelRP = RegistroPrecios::find()->where(['id_lista' => $id])->all();
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
            'modelRP' => $modelRP,
        ]);
    }

    /**
     * Creates a new ListasPrecios model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ListasPrecios();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->estatus = 1;
            $model->save();

            $idlista = ListasPrecios::find()->max('id');

            $cant = 30; $num = 1;
            
            for ($i=0; $i < $cant ; $i++) { 
                $costo = $_POST['costo'.$i];
                $modelRP = new RegistroPrecios();
                $modelRP->id_lista = $idlista;
                $modelRP->cantidad = $num;
                $modelRP->costo = $costo;
                $modelRP->save();
                $num++;
            }

            return $this->redirect(['index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ListasPrecios model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $modelRP = RegistroPrecios::find()->where(['id_lista' => $id])->all();

        if ($model->load(Yii::$app->request->post())) {
            
            $model->save();

            $cant = count($modelRP);
            
            for ($i=0; $i < $cant ; $i++) { 
                $modelRP[$i]->cantidad = $_POST['cantidad'.$i];
                $modelRP[$i]->costo = $_POST['costo'.$i];
                $modelRP[$i]->save();
            }

            $costo_servicio = $_POST['costo0'];

            $service = Servicios::find()->where(['id_listas_precios' => $id])->one();
            $service->costo = $costo_servicio;
            $service->save();
            
            return $this->redirect(['index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'modelRP' => $modelRP,
        ]);
    }

    /**
     * Deletes an existing ListasPrecios model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $modelRP = RegistroPrecios::find()->where(['id_lista' => $id])->all();

        foreach ($modelRP as $rp) {
            if($rp->id_lista == $id) {
                $rp->delete();
            }
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the ListasPrecios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ListasPrecios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ListasPrecios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
