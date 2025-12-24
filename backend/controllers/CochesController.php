<?php

namespace backend\controllers;

use Yii;
use common\models\Coches;
use common\models\Clientes;
use common\models\CochesSearch;
use common\models\Reservas;
use backend\models\CocheMergeForm;
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

        $dataProvider->pagination->pageSize = 15;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMerge()
    {
        $model = new CocheMergeForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $matricula = strtoupper($model->matricula);

            $coches = Coches::find()
                ->where('LOWER(matricula) = :matricula', [':matricula' => strtolower($matricula)])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();

            if (empty($coches)) {
                Yii::$app->session->setFlash('error', 'No se encontraron vehículos con esa matrícula.');
            } else {
                $cochePrincipal = array_shift($coches);

                $this->mergeCoches($cochePrincipal, $coches, $model->marca, $matricula);

                Yii::$app->session->setFlash('success', 'Los vehículos con la misma matrícula fueron unificados correctamente.');

                return $this->redirect(['index']);
            }
        }

        return $this->render('merge', [
            'model' => $model,
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

    /**
     * @param Coches   $cochePrincipal
     * @param Coches[] $cochesDuplicados
     * @param string   $marcaDefinitiva
     * @param string   $matriculaNormalizada
     * @return void
     * @throws \Throwable
     */
    protected function mergeCoches(Coches $cochePrincipal, array $cochesDuplicados, string $marcaDefinitiva, string $matriculaNormalizada): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($cochesDuplicados as $cocheDuplicado) {
                Reservas::updateAll(['id_coche' => $cochePrincipal->id], ['id_coche' => $cocheDuplicado->id]);

                foreach (['modelo', 'color'] as $atributo) {
                    if (empty($cochePrincipal->$atributo) && !empty($cocheDuplicado->$atributo)) {
                        $cochePrincipal->$atributo = $cocheDuplicado->$atributo;
                    }
                }

                $cocheDuplicado->delete();
            }

            $cochePrincipal->marca = $marcaDefinitiva;
            $cochePrincipal->matricula = $matriculaNormalizada;
            $cochePrincipal->updated_at = date('Y-m-d H:i:s');
            $cochePrincipal->updated_by = Yii::$app->user->id;
            $cochePrincipal->save(false);

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();

            throw $e;
        }
    }
}
