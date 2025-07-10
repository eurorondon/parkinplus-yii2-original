<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Reservas;
use common\models\Agencias;
use common\models\UserAfiliados;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {   
        $id_usuario = Yii::$app->user->id;

        $buscarAfiliado = UserAfiliados::find()->where(['user_id' => $id_usuario])->one();
        if (!empty($buscarAfiliado)) {
            $tipo_afiliado = $buscarAfiliado['tipo_afiliado'];
        } else {
            $tipo_afiliado = 0;     
        }

        $age = Agencias::find()->all();
        foreach ($age as $a) {
            $nom = $a->nombre;
            $cant_ag = count(Reservas::find()->where(['agencia' => $nom])->all());
            $datos[] = array('name' => $nom, 'y' => $cant_ag);
        }

        $totales = count(Reservas::find()->all());
        $medio1 = Reservas::find()->where(['medio_reserva' => 1])->all();
        $secretaria = count($medio1);
        $medio2 = Reservas::find()->where(['medio_reserva' => 2])->all();
        $agencia = count($medio2);
        $medio3 = Reservas::find()->where(['medio_reserva' => 3])->all();
        $online = count($medio3);

        $mes_actual = date('m'); $ayo_actual = date('Y');
        $sec_mes = 0; $ag_mes = 0; $web_mes = 0;
        foreach ($medio1 as $sec) {
            $fecha_reserva = $sec->created_at;
            $fecha = strtotime($fecha_reserva);
            $mes_reserva = date("m", $fecha);
            $ayo_reserva = date("Y", $fecha);
            if (($mes_reserva == $mes_actual) AND ($ayo_reserva == $ayo_actual)) {
                $sec_mes = $sec_mes + 1;
            }
        }
        foreach ($medio2 as $ag) {
            $fecha_reserva = $ag->created_at;
            $fecha = strtotime($fecha_reserva);
            $mes_reserva = date("m", $fecha);
            $ayo_reserva = date("Y", $fecha);
            if (($mes_reserva == $mes_actual) AND ($ayo_reserva == $ayo_actual)) {
                $ag_mes = $ag_mes + 1;
            }
        } 
        foreach ($medio3 as $web) {
            $fecha_reserva = $web->created_at;
            $fecha = strtotime($fecha_reserva);
            $mes_reserva = date("m", $fecha);
            $ayo_reserva = date("Y", $fecha);
            if (($mes_reserva == $mes_actual) AND ($ayo_reserva == $ayo_actual)) {
                $web_mes = $web_mes + 1;
            }
        }

        return $this->render('index', [
            'totales' => $totales,
            'secretaria' => $secretaria,
            'agencia' => $agencia,
            'online' => $online,
            'sec_mes' => $sec_mes,
            'ag_mes' => $ag_mes,
            'web_mes' => $web_mes,
            'datos' => $datos,
            'tipo_afiliado'  => $tipo_afiliado,
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {

        $this->layout = 'login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
