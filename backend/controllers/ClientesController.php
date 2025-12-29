<?php

namespace backend\controllers;

use Yii;
use common\models\Clientes;
use common\models\ClientesSearch;
use common\models\Coches;
use common\models\Reservas;
use frontend\models\UserCliente;
use backend\models\ClienteMergeForm;
use backend\models\ClienteMergePhoneForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * ClientesController implements the CRUD actions for Clientes model.
 */
class ClientesController extends Controller
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

    public function actionCliente($movil)
    {
        $cliente = Clientes::find()->where(['movil' => $movil])->with('coches')->one();

        if (!$cliente) {
            echo Json::encode(['success' => false, 'message' => 'No existe el cliente.']);
            return;
        }

        $coches = array_map(static function ($coche) {
            return [
                'id' => $coche->id,
                'marca' => $coche->marca,
                'matricula' => $coche->matricula,
            ];
        }, $cliente->coches);

        echo Json::encode([
            'success' => true,
            'cliente' => $cliente,
            // "coches" is the preferred key; "coche" is kept for backwards compatibility with existing views.
            'coches' => $coches,
            'coche' => $coches,
        ]);
    }

    /**
     * Lists all Clientes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMerge()
    {
        $model = new ClienteMergeForm();
        $mergeByPhoneModel = new ClienteMergePhoneForm();
        $clientes = Clientes::find()->orderBy(['nombre_completo' => SORT_ASC])->all();
        $listaClientes = ArrayHelper::map($clientes, 'id', function ($cliente) {
            $datos = [$cliente->nombre_completo];

            if (!empty($cliente->correo)) {
                $datos[] = $cliente->correo;
            }

            if (!empty($cliente->movil)) {
                $datos[] = $cliente->movil;
            }

            return implode(' | ', $datos);
        });

        if ($mergeByPhoneModel->load(Yii::$app->request->post()) && $mergeByPhoneModel->validate()) {
            $clientesDuplicados = Clientes::find()
                ->where(['movil' => $mergeByPhoneModel->movil])
                ->orderBy(['created_at' => SORT_DESC])
                ->all();

            if (count($clientesDuplicados) < 2) {
                Yii::$app->session->setFlash('error', 'No se encontraron clientes duplicados con ese teléfono.');
            } else {
                $clientePrincipal = array_shift($clientesDuplicados);

                $this->mergeClienteRecords($clientePrincipal, $clientesDuplicados);

                Yii::$app->session->setFlash('success', 'Los clientes con el mismo teléfono fueron unificados correctamente.');

                return $this->redirect(['index']);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $clientePrincipal = Clientes::findOne($model->primary_id);
            $clienteDuplicado = Clientes::findOne($model->duplicate_id);

            if (is_null($clientePrincipal) || is_null($clienteDuplicado)) {
                throw new NotFoundHttpException('El cliente seleccionado no existe.');
            }

            $this->mergeClienteRecords($clientePrincipal, [$clienteDuplicado]);

            Yii::$app->session->setFlash('success', 'Los datos del cliente duplicado fueron unificados correctamente.');

            return $this->redirect(['index']);
        }

        return $this->render('merge', [
            'model' => $model,
            'listaClientes' => $listaClientes,
            'mergeByPhoneModel' => $mergeByPhoneModel,
        ]);
    }

    /**
     * Displays a single Clientes model.
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
     * Creates a new Clientes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Clientes();

        $tipo_documento = [
            'NIF' => 'NIF',
            'NIE' => 'NIE',
            'Pasaporte' => 'Pasaporte'
        ];

        if ($model->load(Yii::$app->request->post())) {
            $cliente = Clientes::find()->where(['movil' => $model->movil])->one();

            if (!is_null($cliente)) {
                Yii::$app->session->setFlash('error', 'El Cliente ya existe, verifique.');
                return $this->redirect(['clientes/index']);
            }

            $model->save();
            Yii::$app->session->setFlash('success', 'El Cliente ha sido agregado de manera exitosa.');
            return $this->redirect(['clientes/index']);
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'tipo_documento' => $tipo_documento,
        ]);
    }

    /**
     * Updates an existing Clientes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $tipo_documento = [
            'NIF' => 'NIF',
            'NIE' => 'NIE',
            'Pasaporte' => 'Pasaporte'
        ];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'El Cliente ha sido modificado de manera exitosa.');
            return $this->redirect(['clientes/index']);
        }

        return $this->renderAjax('update', [
            'model' => $model,
            'tipo_documento' => $tipo_documento,
        ]);
    }

    /**
     * Deletes an existing Clientes model.
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
     * Finds the Clientes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Clientes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Clientes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param Clientes   $clientePrincipal
     * @param Clientes[] $clientesDuplicados
     * @return void
     * @throws \Throwable
     */
    protected function mergeClienteRecords(Clientes $clientePrincipal, array $clientesDuplicados): void
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($clientesDuplicados as $clienteDuplicado) {
                Reservas::updateAll(['id_cliente' => $clientePrincipal->id], ['id_cliente' => $clienteDuplicado->id]);
                Coches::updateAll(['id_cliente' => $clientePrincipal->id], ['id_cliente' => $clienteDuplicado->id]);
                UserCliente::updateAll(['id_cliente' => $clientePrincipal->id], ['id_cliente' => $clienteDuplicado->id]);

                foreach (['correo', 'tipo_documento', 'nro_documento', 'movil', 'nombre_completo'] as $atributo) {
                    if (empty($clientePrincipal->$atributo) && !empty($clienteDuplicado->$atributo)) {
                        $clientePrincipal->$atributo = $clienteDuplicado->$atributo;
                    }
                }

                $clienteDuplicado->delete();
            }

            $clientePrincipal->updated_at = date('Y-m-d H:i:s');
            $clientePrincipal->updated_by = Yii::$app->user->id;
            $clientePrincipal->save(false);

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();

            throw $e;
        }
    }
}
