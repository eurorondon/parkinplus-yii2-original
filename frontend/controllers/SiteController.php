<?php

namespace frontend\controllers;

use Yii;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\PagosForm;
use frontend\models\UserCliente;
use common\redsys\RedsysAPI;
use common\models\LoginForm;
use common\models\User;
use common\models\RegistroPrecios;
use common\models\FacturasReserva;
use common\models\FacturasServicios;
use common\models\Facturas;
use common\models\FacturasSearch;
use common\models\Factureros;
use common\models\ListasPrecios;
use common\models\Reservas;
use common\models\Clientes;
use common\models\Coches;
use common\models\Servicios;
use common\models\TipoPago;
use common\models\Configuracion;
use common\models\ReservasServicios;
use common\models\ReservasLogCambios;
use common\models\ReservasSearch;
use common\models\CochesSearch;
use common\models\Paradas;
use common\models\PrecioTemporada;
use common\models\EncuestaInicial;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use kartik\mpdf\Pdf;
use yii\db\Query;
use DateTime;

use yii\base\ErrorException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    private function getRedsysConfig(): array
    {
        $redsysConfig = Yii::$app->params['redsys'] ?? [];
        $requiredKeys = ['paymentUrl', 'merchantKey', 'fuc', 'terminal', 'currency'];
        $missingKeys = array_filter($requiredKeys, static function ($key) use ($redsysConfig) {
            return empty($redsysConfig[$key]);
        });

        if ($missingKeys) {
            throw new InvalidConfigException(
                'Faltan parámetros de Redsys en params-local: ' . implode(', ', $missingKeys)
            );
        }

        return $redsysConfig;
    }

    private function isBizumPayment(Reservas $reserva): bool
    {
        $tipoPago = $reserva->tipoPago ?: TipoPago::findOne($reserva->id_tipo_pago);
        if ($tipoPago === null) {
            return false;
        }

        return stripos((string)$tipoPago->descripcion, 'bizum') !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Aparcamiento Larga Estancia Madrid'
        ]);

        \Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'parkingplus,parkingplus.es,aparcamiento larga estancia,parking barajas,madrid,terminal,T1,T2,T3,T4'
        ]);

        \Yii::$app->view->registerMetaTag([
            'name' => 'author',
            'content' => 'Christian Goncalves'
        ]);

        $model = new Reservas();

        $query = new Query();
        $query->select(
            [
                'registro_precios2.id_lista',
                'registro_precios2.cantidad',
                'registro_precios2.costo AS precio',
                'servicios.*'
            ]
        )
            ->from('registro_precios2')
            ->join(
                'LEFT JOIN',
                'servicios',
                'registro_precios2.id_lista = servicios.id_listas_precios'
            );

        $command = $query->createCommand();
        $precio_diario = $command->queryAll();

        $milista = $precio_diario[0]['id_lista'];

        $buscaAgregado = ListasPrecios::find()->where(['id' => $milista])->all();

        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->all();

        if (count($precioTemporada) > 0) {
            foreach ($precio_diario as $key => $data) {
                $precio_diario[$key]['precio'] = $precio_diario[$key]['costo'] + ($precio_diario[$key]['cantidad'] * $precioTemporada[0]['precio']);
            }
        }

        $agregado = $buscaAgregado[0]->agregado;

        return $this->render('index', [
            'model' => $model,
            'precio_diario' => $precio_diario,
            'agregado' => $agregado,
        ]);
    }

    public function actionOrganic()
    {

        \Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => 'Aparcamiento Larga Estancia Madrid'
        ]);

        \Yii::$app->view->registerMetaTag([
            'name' => 'keywords',
            'content' => 'parkingplus,parkingplus.es,aparcamiento larga estancia,parking barajas,madrid,terminal,T1,T2,T3,T4'
        ]);

        \Yii::$app->view->registerMetaTag([
            'name' => 'author',
            'content' => 'Christian Goncalves'
        ]);

        $model = new Reservas();

        $query = new Query();
        $query->select(
            [
                'registro_precios2.id_lista',
                'registro_precios2.cantidad',
                'registro_precios2.costo AS precio',
                'servicios.*'
            ]
        )
            ->from('registro_precios2')
            ->join(
                'LEFT JOIN',
                'servicios',
                'registro_precios2.id_lista = servicios.id_listas_precios'
            );

        $command = $query->createCommand();
        $precio_diario = $command->queryAll();

        $milista = $precio_diario[0]['id_lista'];

        $buscaAgregado = ListasPrecios::find()->where(['id' => $milista])->all();

        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->all();

        if (count($precioTemporada) > 0) {
            foreach ($precio_diario as $key => $data) {
                $precio_diario[$key]['precio'] = $precio_diario[$key]['costo'] + ($precio_diario[$key]['cantidad'] * $precioTemporada[0]['precio']);
            }
        }

        $agregado = $buscaAgregado[0]->agregado;

        return $this->render('organic', [
            'model' => $model,
            'precio_diario' => $precio_diario,
            'agregado' => $agregado,
        ]);
    }

    public function actionFechas()
    {
        $model = new Reservas();
        if ($model->load(Yii::$app->request->post())) {
            $cdias = $_POST['cantdias'];
            $type = $_POST['type'];
            $plan = $_POST['plan'];
            $entrada = $model->fecha_entrada;
            $salida = $model->fecha_salida;
            $hora_e = $model->hora_entrada;
            $hora_s = $model->hora_salida;

            return Yii::$app->response->redirect(['site/create', 'cdias' => $cdias, 'entrada' => $entrada, 'salida' => $salida, 'hora_e' => $hora_e, 'hora_s' => $hora_s, 'type' => $type, 'plan' => $plan])->send();
        }
        return $this->renderAjax('fechas', [
            'model' => $model,
        ]);
    }

    // agg ER
    public function actionFechasorganic()
    {
        $model = new Reservas();
        if ($model->load(Yii::$app->request->post())) {
            $cdias = $_POST['cantdias'];
            $type = $_POST['type'];
            $plan = $_POST['plan'];
            $entrada = $model->fecha_entrada;
            $salida = $model->fecha_salida;
            $hora_e = $model->hora_entrada;
            $hora_s = $model->hora_salida;

            return Yii::$app->response->redirect([
                'site/createorganic',
                'cdias' => $cdias,
                'entrada' => $entrada,
                'salida' => $salida,
                'hora_e' => $hora_e,
                'hora_s' => $hora_s,
                'type' => $type,
                'plan' => $plan
            ])->send();
        }
        return $this->renderAjax('fechas', [
            'model' => $model,
        ]);
    }
    // end ER

    public function actionParada()
    {
        if (Yii::$app->request->post()) {

            $fecha_entrada = date('Y-m-d', strtotime($_POST['fecha_e']));
            $hora_entrada = $_POST['hora_e'];
            $fecha_salida = date('Y-m-d', strtotime($_POST['fecha_s']));
            $hora_salida = $_POST['hora_s'];

            return $this->tieneParadaActiva($fecha_entrada, $hora_entrada, $fecha_salida, $hora_salida) ? 1 : 0;
        }
    }

    private function tieneParadaActiva($fechaEntrada, $horaEntrada, $fechaSalida, $horaSalida)
    {
        $in = date('Y-m-d', strtotime($fechaEntrada)) . ' ' . $horaEntrada;
        $fecha_in = new DateTime($in);
        $fecha_in = $fecha_in->format('Y-m-d H:i:s');

        $fin = date('Y-m-d', strtotime($fechaSalida)) . ' ' . $horaSalida;
        $fecha_finc = new DateTime($fin);
        $fecha_finc = $fecha_finc->format('Y-m-d H:i:s');

        $buscaParadas = Paradas::find()->where(['status' => 'activo'])->all();

        foreach ($buscaParadas as $parada) {
            $fecha_inicio = $parada['fecha_inicio'];
            $hora_inicio = $parada['hora_inicio'];
            $fecha_fin = $parada['fecha_fin'];
            $hora_fin = $parada['hora_fin'];

            $date_ini = $fecha_inicio . ' ' . $hora_inicio;
            $fecha_i = new DateTime($date_ini);
            $fecha_i = $fecha_i->format('Y-m-d H:i:s');

            $date_fin = $fecha_fin . ' ' . $hora_fin;
            $fecha_f = new DateTime($date_fin);
            $fecha_f = $fecha_f->format('Y-m-d H:i:s');

            if (($fecha_in >= $fecha_i) && ($fecha_in <= $fecha_f)) {
                return true;
            }

            if (($fecha_finc >= $fecha_i) && ($fecha_finc <= $fecha_f)) {
                return true;
            }
        }

        return false;
    }


    /**
     * Displays homepage Loged.
     *
     * @return mixed
     */
    public function actionPanel()
    {
        $model = new Reservas();

        $id = Yii::$app->user->id;
        $user_cliente = UserCliente::find()->where(['id_usuario' => $id])->one();
        if ($user_cliente == null) {
            $usuario = User::find()->where(['id' => $id])->one();
            $name = $usuario->username;
            $datos = $usuario;
        } else {
            $idcliente = $user_cliente->id_cliente;
            $cliente = Clientes::find()->where(['id' => $idcliente])->one();
            $name = $cliente->nombre_completo;
            $datos = $cliente;
        }

        $reservas = Reservas::find()->where(['id_cliente' => $datos->id])->limit(5)->all();
        $cantR = count($reservas);

        $facturas = null;

        for ($i = 0; $i < $cantR; $i++) {
            $buscaFactura[$i] = FacturasReserva::find()->where(['id_reserva' => $reservas[$i]->id])->one();

            if ($buscaFactura[$i] === null) {
                $facturas = null;
            } else {
                $facturas[$i] = Facturas::find()->where(['id' => $buscaFactura[$i]->id_factura])->one();
            }
        }

        $coches = Coches::find()->where(['id_cliente' => $datos->id])->limit(5)->all();

        if ($model->load(Yii::$app->request->post())) {
            if (($model->fecha_entrada == null) || ($model->fecha_salida == null)) {
                Yii::$app->session->setFlash('error', 'Debe ingresar la fecha de entrada y de salida para poder calcular su tarifa de reserva.');
                return $this->redirect(['site/index']);
            } else {
                $entrada = $model->fecha_entrada;
                $salida = $model->fecha_salida;
                $hora_e = $model->hora_entrada;
                $hora_s = $model->hora_salida;
                return Yii::$app->response->redirect(['site/create', 'entrada' => $entrada, 'salida' => $salida, 'hora_e' => $hora_e, 'hora_s' => $hora_s])->send();
            }
        }

        return $this->render('panel', [
            'model' => $model,
            'datos' => $datos,
            'name' => $name,
            'user_cliente' => $user_cliente,
            'reservas' => $reservas,
            'facturas' => $facturas,
            'coches' => $coches,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {

        $this->layout = 'login';

        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            //return $this->goBack();
            return $this->redirect(['panel']);
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['index']);
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail($model->email)) {
                Yii::$app->session->setFlash('success', 'Gracias por contactarnos. Nosotros responderemos a la mayor brevedad posible.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al enviar tu mensaje.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays Notification Pay page.
     *
     * @return mixed
     */

    public function actionNotificacion()
    {
        return $this->render('notificacion');
    }


    public function actionDatos()
    {
        if (Yii::$app->request->post()) {
            $id = $_POST['id'];
            $reserva = Reservas::find()->where(['id' => $id])->one();
            $id_cliente = $reserva->id_cliente;

            $cliente = Clientes::find()->where(['id' => $id_cliente])->one();
            $nombre = $cliente->nombre_completo;

            $fecha_e = $reserva->fecha_entrada;
            $fecha_e = date('d-m-Y', strtotime($fecha_e));
            $fecha_s = $reserva->fecha_salida;
            $fecha_s = date('d-m-Y', strtotime($fecha_s));
            $monto = $reserva->monto_total;
            $numero_reserva = $reserva->nro_reserva;
            $datos = $numero_reserva . '/' . $fecha_e . '/' . $fecha_s . '/' . $monto . '/' . $nombre;
        } else {
            $datos = "Error en Carga de Datos";
        }

        return Json::encode($datos);
    }

    public function encrypt_3DES($message, $key)
    {
        $bytes = array(0, 0, 0, 0, 0, 0, 0, 0);
        $iv = implode(array_map("chr", $bytes));
        $ciphertext = mcrypt_encrypt(MCRYPT_3DES, $key, $message, MCRYPT_MODE_CBC, $iv);
        return $ciphertext;
    }

    public function mac256($ent, $key)
    {
        $res = hash_hmac('sha256', $ent, $key, true);
        return $res;
    }


    public function actionCadena()
    {

        if (Yii::$app->request->post()) {

            $MONTO = $_POST['monto'];
            $AMOUNT = str_replace('.', '', $MONTO);
            $ORDER = $_POST['pedido'];
            $MERCHANTCODE = '350165395';
            $CURRENCY = '978';
            $TRANSACTIONTYPE = '0';
            $TERMINAL = '1';
            $URL = 'http://parkingplus.es/aparcamiento/site/notificacion';
            //$PAN = $_POST['nro_tarjeta'];
            //$PAN = str_replace('-', '', $PAN);
            //$EXPIRYDATE = $_POST['fecha_expira'];
            //$CVV2 = $_POST['cvv'];
            //CLAVE DEL COMERCIO TEST
            //$COMERCIO = '';
            //CLAVE DEL COMERCIO PRODUCCION
            $COMERCIO = 'X/5rYzzA5kZeS2RQge9+yxdgpL/5r+nO';
            //$AUTORIZACION = '123456';

            $cadena = array(
                'DS_MERCHANT_AMOUNT' => $AMOUNT,
                'DS_MERCHANT_ORDER' => $ORDER,
                'DS_MERCHANT_MERCHANTCODE' => $MERCHANTCODE,
                'DS_MERCHANT_CURRENCY' => $CURRENCY,
                'DS_MERCHANT_TRANSACTIONTYPE' => $TRANSACTIONTYPE,
                'DS_MERCHANT_TERMINAL' => $TERMINAL,
                'DS_MERCHANT_MERCHANTURL' => $URL,
                //'DS_MERCHANT_PAN' => $PAN,
                //'DS_MERCHANT_EXPIRYDATE' => $EXPIRYDATE,
                //'DS_MERCHANT_CVV2' => $CVV2,
                //'DS_MERCHANT_AUTHORISATIONCODE' => $AUTORIZACION,
            );

            $cadena = json_encode($cadena);
            $datos = base64_encode($cadena);



            $key_comercio = base64_decode($COMERCIO);

            $key = $this->encrypt_3DES($ORDER, $key_comercio);

            $crea_signature = $this->mac256($datos, $key);

            $signature = base64_encode($crea_signature);
        } else {
            $datos = "Error en Carga de Datos";
        }
        return ($datos . '/' . $signature);
    }

    /**
     * Displays services page.
     *
     * @return mixed
     */
    public function actionServicios()
    {
        return $this->render('servicios');
    }

    /**
     * Displays precios page.
     *
     * @return mixed
     */
    public function actionPrecios()
    {
        $model = Servicios::find()->all();

        return $this->render('precios', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Displays mis coches page.
     *
     * @return mixed
     */
    public function actionVehiculos()
    {

        $searchModel = new CochesSearch();
        $dataProvider = $searchModel->searchCoche(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize = 10;

        return $this->render('mis_coches', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Create NEW Coche.
     *
     * @return mixed
     */
    public function actionCoches()
    {

        $model = new Coches();

        $id = Yii::$app->user->id;

        $user_cliente = UserCliente::find()->where(['id_usuario' => $id])->one();
        $idcliente = $user_cliente->id_cliente;

        $cliente = Clientes::find()->where(['id' => $idcliente])->one();

        if ($model->load(Yii::$app->request->post())) {

            $model->id_cliente = $idcliente;
            $model->estatus = 1;
            $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = $id;

            $model->save();

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Su vehículo ha sido agregado de manera exitosa.');
                return $this->redirect(['site/panel']);
            }
        }

        return $this->renderAjax('create-coche', [
            'model' => $model,
            'cliente' => $cliente,
        ]);
    }

    /**
     * Updates an existing Clientes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionCliente()
    {

        $id = Yii::$app->user->id;

        $user_cliente = UserCliente::find()->where(['id_usuario' => $id])->one();
        $idcliente = $user_cliente->id_cliente;

        $model = Clientes::find()->where(['id' => $idcliente])->one();

        $datos_user = User::find()->where(['id' => $id])->one();

        $tipo_documento = [
            'NIF' => 'NIF',
            'NIE' => 'NIE',
            'Pasaporte' => 'Pasaporte'
        ];

        if ($model->load(Yii::$app->request->post())) {

            $model->updated_at = date('Y-m-d H:i:s');
            $model->updated_by = $id;
            $model->save();

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Su Datos han sido modificados de manera exitosa.');
                return $this->redirect(['site/panel']);
            }
        }

        return $this->renderAjax('update-cliente', [
            'model' => $model,
            'tipo_documento' => $tipo_documento,
            'datos_user' => $datos_user,
        ]);
    }


    /**
     * Displays mis reservas page.
     *
     * @return mixed
     */
    public function actionReservas()
    {

        $searchModel = new ReservasSearch();
        $dataProvider = $searchModel->searchRes(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize = 10;

        return $this->render('mis_reservas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays mis facturas page.
     *
     * @return mixed
     */
    public function actionFacturas()
    {

        $searchModel = new FacturasSearch();
        $dataProvider = $searchModel->searchFac(Yii::$app->request->queryParams);

        return $this->render('mis_facturas', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Gracias por registrarte. Verifique su bandeja de entrada para el correo electrónico de verificación.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /*
    public function actionProcesada()
    {
        $mens = 'Su reserva ha sido procesada de manera exitosa. Revise su correo electrónico para mayor información';
        return $this->render('reserva-procesada', [
            'mens' => $mens,
        ]);
    }
    */

    public function actionCondiciones()
    {
        return $this->render('condiciones');
    }

    public function actionPrivacidad()
    {
        return $this->render('privacidad');
    }

    public function actionPolitica()
    {
        return $this->render('politica');
    }

    public function actionCookies()
    {
        return $this->render('cookies');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $this->layout = 'login';

        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Haz relizado la petición para recuperar tu contraseña. Revise su correo electrónico para obtener más instrucciones');
                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Lo sentimos, no podemos restablecer la contraseña de la dirección de correo electrónico proporcionada.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {

        $this->layout = 'login';

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Su contraseña ha sido cambiada de manera exitosa.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Reservas model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'id' => $id,
        ]);
    }

    /**
     * Displays a single Coches model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionViewc($id)
    {
        $modelC = Coches::find()->where(['id' => $id])->one();
        return $this->render('view-vehiculos', [
            'model' => $modelC,
            'id' => $id,
        ]);
    }

    public function actionPdf($id)
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
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }


    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {

        $this->layout = 'login';

        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', '¡Tu correo ha sido confirmado!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Lo sentimos, no podemos verificar su cuenta con el token proporcionado.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {

        $this->layout = 'login';

        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Se ha reenviado un correo electrónico de verificación. Revise su correo electrónico para obtener más instrucciones.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Lo sentimos, no podemos reenviar el correo electrónico de verificación para la dirección de correo electrónico proporcionada.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }

    /**
     * Finds the Reservas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Reservas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */

    protected function findModel($id)
    {
        if (($model = Reservas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    /**
     * Inicio del Formulario de Reserva
     *
     * @return mixed
     */

    public function actionCreate()
    {
        $entrada = $_GET['entrada'];
        $salida = $_GET['salida'];

        $hora_e = $_GET['hora_e'];
        $hora_s = $_GET['hora_s'];

        $cant_dias = $_GET['cdias'];
        $type_reserva = $_GET['type'];
        $plan = $_GET['plan'];
        $plan = $_GET['plan'];

        $fecha_entrada = strtotime($entrada . ' ' . $hora_e);
        $fecha_salida = strtotime($salida . ' ' . $hora_s);

        $fhne  = strtotime($entrada . ' 00:30:00');
        $fhnes = strtotime($entrada . ' 03:45:00');

        $fhns  = strtotime($salida . ' 00:30:00');
        $fhnss = strtotime($salida . ' 03:45:00');

        $extraNocturno = Servicios::find()->where(['id' => '11'])->all();
        $extraNocturno[0]['id'] .= (($fecha_entrada >= $fhne && $fecha_entrada <= $fhnes) || ($fecha_salida >= $fhns && $fecha_salida <= $fhnss)) ? '-1' : '-0';

        $model = new Reservas();
        $model->plan = $plan;
        $modelC = new Clientes();
        $modelV = new Coches();

        $model->cod_valid = $this->Obtener_token(48);

        $tipo_documento = [
            'NIF' => 'NIF',
            'NIE' => 'NIE',
            'Pasaporte' => 'Pasaporte'
        ];

        $terminales = [
            'TERMINAL 1' => 'TERMINAL 1',
            'TERMINAL 2' => 'TERMINAL 2',
            'TERMINAL 3' => 'TERMINAL 3',
            'TERMINAL 4' => 'TERMINAL 4',
            'AUN NO CONOZCO LA TERMINAL' => 'AUN NO CONOZCO LA TERMINAL'
        ];

        $servicios = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '2'])->all();
        $seguro    = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '1'])->all();

        $query = new Query();
        $query->select(
            [
                'registro_precios2.id_lista',
                'registro_precios2.cantidad',
                'registro_precios2.costo AS precio',
                'servicios.*'
            ]
        )
            ->from('registro_precios2')
            ->join(
                'LEFT JOIN',
                'servicios',
                'registro_precios2.id_lista = servicios.id_listas_precios'
            );

        $command = $query->createCommand();
        $precio_diario = $command->queryAll();

        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->all();

        $day1 = strtotime($entrada . ' ' . $hora_e);
        $day2 = strtotime($salida  . ' ' . $hora_s);
        $diffHours = round(($day2 - $day1) / 3600);
        $dias = $diffHours / 24;
        $partes = explode('.', $dias);

        if (count($partes) == 1) {
            $cant_dias = $dias;
        } else {
            $cant_dias = intval($dias) + 1;
        }

        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->one();
        if (!is_null($precioTemporada)) {
            foreach ($precio_diario as $key => $diario) {
                $precio_diario[$key]['precio'] = $precio_diario[$key]['costo'] + ($precio_diario[$key]['cantidad'] * $precioTemporada->precio);
            }
        }

        $paradaActiva = $this->tieneParadaActiva($entrada, $hora_e, $salida, $hora_s);

        $pagos = TipoPago::find()->where(['estatus' => '1'])->all();
        $tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion');

        $impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
        foreach ($impuestos as $imp) {
            $tipo_imp = $imp->tipo_impuesto;
            if ($tipo_imp == 1) {
                $iva = $imp->valor_numerico;
            }
        }

        $precio_dia_cfg = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '0'])->one();

        if ($model->load(Yii::$app->request->post()) && $modelC->load(Yii::$app->request->post()) && $modelV->load(Yii::$app->request->post())) {
            $model->plan = Yii::$app->request->post('Reservas')['plan'];

            $paradaActiva = $this->tieneParadaActiva($model->fecha_entrada, $model->hora_entrada, $model->fecha_salida, $model->hora_salida);
            if ($paradaActiva) {
                Yii::$app->session->setFlash('error', 'Para la fecha de entrada o salida no tenemos plazas disponibles.');
                return $this->redirect(['site/index']);
            }

            // Eliminando Lavado cortesia si existe lavado completo (mantener lógica del main)
            $cantidad1 = Yii::$app->request->post('cantidad1', 0);
            $cantidad2 = Yii::$app->request->post('cantidad2', 0);
            $cantidad7 = Yii::$app->request->post('cantidad7', 0);
            if (($cantidad2 > 0 && $cantidad7 > 0) || ($cantidad1 > 0 && $cantidad7 > 0)) {
                unset($servicios[3]);
            }

            $num_reserva = substr(strtotime(date('Y-m-d H:i:s')), 2, 10);
            $buscarReserva = Reservas::find()->where(['nro_reserva' => $num_reserva])->one();
            if ($buscarReserva != null) {
                $num_reserva = substr(strtotime(date('Y-m-d H:i:s')), 2, 10);
            }
            $model->nro_reserva = $num_reserva;

            $precio_dia = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '0'])->all();
            $seguro     = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '1'])->all();

            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));

            $model->estatus = 1;
            $modelC->estatus = 1;

            $idmax = UserCliente::find()->max('id');
            $iduc = $idmax + 1;
            $fecha_creacion = date('Y-m-d H:i:s');

            $client = Clientes::find()->where(['movil' => $modelC->movil])->one();

            if ($client == null) {
                $modelC->estatus = 1;
                $modelC->created_at = $fecha_creacion;
                $modelC->updated_at = $fecha_creacion;
                $modelC->created_by = $iduc;
                $modelC->save();

                $errorC = count($modelC->getErrors());
                if ($errorC > 0) {
                    Yii::$app->session->setFlash('error', 'Error en Carga de Datos');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                $model->id_cliente = $modelC->id;
                $modelV->id_cliente = $modelC->id;
            } else {
                $model->id_cliente = $client->id;
                $modelV->id_cliente = $client->id;
            }

            $modelV->estatus_coche = 1;

            $u = UserCliente::find()->where(['id_cliente' => $modelV->id_cliente])->one();
            $busca_user = User::find()->where(['email' => $modelC->correo])->one();

            $fecha_creacion = date('Y-m-d H:i:s');
            $modelV->created_at = $fecha_creacion;
            $modelV->updated_at = $fecha_creacion;

            if ($u == null) {
                $idmax = UserCliente::find()->max('id');
                $iduc = $idmax + 1;
                $modelV->created_by = $iduc;
            } else {
                $modelV->created_by = $u->id;
            }

            $model->id_coche = $this->resolveCarId($modelV);

            // === Bucle de servicios (igual que main, pero sin "Undefined index") ===
            foreach ($servicios as $ser) {
                $modelR = new ReservasServicios();

                // Leer con defaults para evitar Undefined index
                $precio_unitario = Yii::$app->request->post('precio_unitario' . $ser->id, 0);
                $cantidad        = Yii::$app->request->post('cantidad'        . $ser->id, 0);
                // En main: $precio_total = $_POST['precio_unitario{id}']; replicamos el comportamiento
                $precio_total    = Yii::$app->request->post('precio_total'    . $ser->id, 0);
                $tipo_servicio   = Yii::$app->request->post('tipo_servicio'   . $ser->id, 0);

                // Compatibilidad con main si no llega precio_total: igualarlo al unitario
                if ($precio_total === 0 && $precio_unitario > 0) {
                    $precio_total = $precio_unitario;
                }

                if ($cantidad != 0) {
                    $modelR->id_reserva      = $model->nro_reserva;
                    $modelR->id_servicio     = $ser->id;
                    $modelR->cantidad        = $cantidad;
                    $modelR->precio_unitario = $precio_unitario;
                    $modelR->precio_total    = $precio_total;
                    $modelR->tipo_servicio   = $tipo_servicio;
                    $modelR->save();
                }
            }

            if ($precio_dia && isset($precio_dia[0]) && $precio_dia[0]->fijo == 0) {
                $modelR = new ReservasServicios();
                $modelR->id_reserva      = $model->nro_reserva;
                $modelR->id_servicio     = $precio_dia[0]->id;
                $modelR->cantidad        = isset($_POST['cant_basico']) ? (int)$_POST['cant_basico'] : 0;
                $modelR->precio_unitario = $precio_dia[0]->costo;
                $modelR->precio_total    = $model->costo_servicios;
                $modelR->tipo_servicio   = 0;
                $modelR->save();
            }

            if ($seguro && isset($seguro[0]) && $seguro[0]->fijo == 1) {
                $modelR = new ReservasServicios();
                $modelR->id_reserva      = $model->nro_reserva;
                $modelR->id_servicio     = $seguro[0]->id;
                $modelR->cantidad        = isset($_POST['cant_seguro']) ? (int)$_POST['cant_seguro'] : 0;
                $modelR->precio_unitario = $seguro[0]->costo;
                $modelR->precio_total    = $seguro[0]->costo;
                $modelR->tipo_servicio   = 1;
                $modelR->save();
            }

            if (isset($_POST['is_noc']) && $_POST['is_noc'] == '11-1') {
                $modelR = new ReservasServicios();
                $modelR->id_reserva      = $model->nro_reserva;
                $modelR->id_servicio     = $_POST['servicio_noc_id'];
                $modelR->cantidad        = 1;
                $modelR->precio_unitario = $_POST['servicio_noc_costo'];
                $modelR->precio_total    = $_POST['servicio_noc_costo'];
                $modelR->tipo_servicio   = 2;
                $modelR->save();

                // El monto total ya incluye el costo del servicio nocturno,
                // por lo que no se debe sumar nuevamente.
            }

            $fecha_creacion = date('Y-m-d H:i:s');
            $model->created_at = $fecha_creacion;
            $model->updated_at = $fecha_creacion;
            $model->medio_reserva = 3;

            $model->save();

            if ($model->save()) {
                if (($u == null) && ($busca_user == null)) {
                    $modelU = new User();
                    $user_name = $modelC->correo;
                    $modelU->username = $user_name;
                    $modelU->email = $modelC->correo;
                    $modelU->setPassword($modelC->nro_documento);
                    $modelU->generateAuthKey();
                    $modelU->generateEmailVerificationToken();
                    $modelU->status = 10;
                    $modelU->save();

                    $modelUC = new UserCliente();
                    $modelUC->id_usuario = $modelU->id;
                    $modelUC->id_cliente = $modelV->id_cliente;
                    $modelUC->save();

                    $model->created_by = $modelU->id;
                } else {
                    $modelUC = new UserCliente();
                    $modelUC->id_usuario = $busca_user->id;
                    $modelUC->id_cliente = $modelV->id_cliente;
                    $modelUC->save();
                    $model->created_by = $modelC->id;
                }

                $fecha_creacion = date('Y-m-d H:i:s');
                $model->created_at = $fecha_creacion;
                $model->updated_at = $fecha_creacion;
                $model->medio_reserva = 3;

                // Mantener EXACTO el comportamiento del main:
                $model->actualizada = 0;
                $model->save();

                if ($model->factura == 1) {
                    $correo = Yii::$app->mailer->compose(
                        ['html' => 'emailFactura-html'],
                        ['nro_reserva' => $model->nro_reserva]
                    );
                    $correo->setTo("parkingplus01@gmail.com")
                        ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                        ->setSubject('Reservación ParkingPlus con Factura')
                        ->send();
                }

                $isBizum = $this->isBizumPayment($model);
                if ((int)$model->id_tipo_pago === 5 || $isBizum) {
                    $this->layout = 'secondary';

                    \Yii::$app->session->open();
                    \Yii::$app->session['reserva'] = $model;
                    \Yii::$app->session->close();

                    $miObj = new RedsysAPI();

                    $version = "HMAC_SHA256_V1";

                    $redsysConfig = $this->getRedsysConfig();
                    $url_tpv = (string)$redsysConfig['paymentUrl'];
                    $merchantKey = (string)$redsysConfig['merchantKey'];

                    $name = 'PARKING PLUS';
                    $code = (string)$redsysConfig['fuc'];
                    $terminal = (string)$redsysConfig['terminal'];
                    $order = $model->nro_reserva;
                    $amount = $model->monto_total * 100;

                    $currency = (string)$redsysConfig['currency'];
                    $consumerlng = '001';
                    $transactionType = '0';
                    $urlMerchant = 'https://www.parkingplus.es/';
                    $frontendBaseUrl = $this->normalizeFrontendBaseUrl(Yii::$app->params['frontendBaseUrl']);
                    $urlweb_ok = $frontendBaseUrl . '/site/tpvok';
                    $urlweb_ko = $frontendBaseUrl . '/site/tpvko';

                    $miObj->setParameter("DS_MERCHANT_AMOUNT", (string)$amount);
                    $miObj->setParameter("DS_MERCHANT_CURRENCY", $currency);
                    $miObj->setParameter("DS_MERCHANT_MERCHANTCODE", $code);
                    $miObj->setParameter("DS_MERCHANT_MERCHANTURL", $urlMerchant);
                    $miObj->setParameter("DS_MERCHANT_ORDER", $order);
                    $miObj->setParameter("DS_MERCHANT_TERMINAL", $terminal);
                    $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $transactionType);
                    $miObj->setParameter("DS_MERCHANT_URLKO", $urlweb_ko);
                    $miObj->setParameter("DS_MERCHANT_URLOK", $urlweb_ok);
                    $miObj->setParameter("DS_MERCHANT_MERCHANTNAME", $name);
                    $miObj->setParameter("DS_MERCHANT_CONSUMERLANGUAGE", $consumerlng);

                    if ($isBizum) {
                        $miObj->setParameter("DS_MERCHANT_PAYMETHODS", "z");
                    }

                    $params = $miObj->createMerchantParameters();
                    $signature = $miObj->createMerchantSignature($merchantKey);
                    return $this->render('procesar-pago', [
                        'url_tpv'   => $url_tpv,
                        'version'   => $version,
                        'params'    => $params,
                        'signature' => $signature,
                    ]);
                } else {
                    $content = $this->renderPartial('_reportView', ['model' => $this->findModel($model->id)]);

                    $pdf = new Pdf([
                        'mode' => Pdf::MODE_UTF8,
                        'format' => Pdf::FORMAT_A4,
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'destination' => Pdf::DEST_FILE,
                        'filename' => '../web/pdf/comprobante_' . $model->nro_reserva . '.pdf',
                        'content' => $content,
                        'cssFile' => '../web/css/reportes.css',
                        'options' => ['title' => 'Comprobante de Reserva'],
                        'methods' => [
                            'SetFooter' => ['{PAGENO}'],
                        ]
                    ]);

                    $pdf->render();

                    if ($modelC->correo != null) {
                        try {
                            $correo = Yii::$app->mailer->compose(
                                [
                                    'html' => 'emailReserva2-html',
                                    'text' => 'emailReserva-text'
                                ],
                                [
                                    'nro_reserva'     => $model->nro_reserva,
                                    'coche_matricula' => $modelV->matricula,
                                    'fecha_entrada'   => $fecha1,
                                    'hora_entrada'    => $model->hora_entrada,
                                    'fecha_salida'    => $fecha2,
                                    'hora_salida'     => $model->hora_salida,
                                    'token'           => $model->cod_valid
                                ]
                            );

                            $correo->setTo($modelC->correo)
                                ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                                ->setSubject('Reservación Parking Plus')
                                ->attach('../web/pdf/comprobante_' . $model->nro_reserva . '.pdf')
                                ->send();

                            $correo2 = Yii::$app->mailer->compose(
                                [
                                    'html' => 'emailReserva2-html',
                                    'text' => 'emailReserva-text'
                                ],
                                [
                                    'nro_reserva'   => $model->nro_reserva,
                                    'fecha_entrada' => $fecha1,
                                    'hora_entrada'  => $model->hora_entrada,
                                    'fecha_salida'  => $fecha2,
                                    'hora_salida'   => $model->hora_salida,
                                ]
                            );
                            $correo2->setTo('asistenciaplus00@gmail.com')
                                ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                                ->setSubject('Reservación Parking Plus')
                                ->attach('../web/pdf/comprobante_' . $model->nro_reserva . '.pdf')
                                ->send();
                        } catch (\Exception $e) {
                            return $this->redirect(['finalizada', 'reserva' => $model->nro_reserva]);
                        }
                    }

                    return $this->redirect([
                        'finalizada',
                        'reserva' => $model->nro_reserva,
                    ]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Su reserva no pudo ser procesada. Disculpe las molestias ocasionadas.');
            }

            return $this->redirect(['site/index']);
        }

        // Render inicial
        return $this->render('createN', [
            'model'         => $model,
            'modelC'        => $modelC,
            'modelV'        => $modelV,
            'tipo_documento' => $tipo_documento,
            'terminales'    => $terminales,
            'servicios'     => $servicios,
            'seguro'        => $seguro,
            'precio_diario' => $precio_diario,
            'tipos_pago'    => $tipos_pago,
            'iva'           => $iva,
            'entrada'       => $entrada,
            'salida'        => $salida,
            'hora_e'        => $hora_e,
            'hora_s'        => $hora_s,
            'cant_dias'     => $cant_dias,
            'nocturno'      => $extraNocturno,
            'type_reserva'  => $type_reserva,
            'precio_dia'    => $precio_dia_cfg->valor_numerico,
            'plan'          => $plan,
            'paradaActiva'  => $paradaActiva
        ]);
    }


    // agg ER
    public function actionCreateorganic()
    {
        $req = Yii::$app->request;

        // === GET params ===
        $entrada = $req->get('entrada');
        $salida  = $req->get('salida');

        $hora_e = $req->get('hora_e');
        $hora_s = $req->get('hora_s');

        $paradaActiva = $this->tieneParadaActiva($entrada, $hora_e, $salida, $hora_s);

        $cant_dias    = $req->get('cdias');
        $type_reserva = $req->get('type');
        $plan         = $req->get('plan');

        // === Cálculos de horas/nocturnos ===
        $fecha_entrada = strtotime($entrada . ' ' . $hora_e);
        $fecha_salida  = strtotime($salida  . ' ' . $hora_s);

        $fhne  = strtotime($entrada . ' 00:30:00');
        $fhnes = strtotime($entrada . ' 03:45:00');

        $fhns  = strtotime($salida . ' 00:30:00');
        $fhnss = strtotime($salida . ' 03:45:00');

        $extraNocturno = Servicios::find()->where(['id' => '11'])->all();
        if (!empty($extraNocturno) && isset($extraNocturno[0])) {
            $extraNocturno[0]['id'] .= (
                ($fecha_entrada >= $fhne && $fecha_entrada <= $fhnes) ||
                ($fecha_salida  >= $fhns && $fecha_salida  <= $fhnss)
            ) ? '-1' : '-0';
        }

        // === Modelos base ===
        $model  = new Reservas();
        $modelC = new Clientes();
        $modelV = new Coches();

        $model->plan      = $plan;
        $model->cod_valid = $this->Obtener_token(48);

        // === Catálogos ===
        $tipo_documento = [
            'NIF' => 'NIF',
            'NIE' => 'NIE',
            'Pasaporte' => 'Pasaporte'
        ];

        $terminales = [
            'TERMINAL 1' => 'TERMINAL 1',
            'TERMINAL 2' => 'TERMINAL 2',
            'TERMINAL 3' => 'TERMINAL 3',
            'TERMINAL 4' => 'TERMINAL 4',
            'AUN NO CONOZCO LA TERMINAL' => 'AUN NO CONOZCO LA TERMINAL'
        ];

        // Servicios variables (fijo=2) y seguro (fijo=1)
        $servicios       = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '2'])->all();
        $seguroServices  = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '1'])->all();

        // === Lista de precios diaria (join) ===
        $query = new Query();
        $query->select([
            'registro_precios2.id_lista',
            'registro_precios2.cantidad',
            'registro_precios2.costo AS precio',
            'servicios.*'
        ])
            ->from('registro_precios2')
            ->join('LEFT JOIN', 'servicios', 'registro_precios2.id_lista = servicios.id_listas_precios');

        $command       = $query->createCommand();
        $precio_diario = $command->queryAll();

        // === Cálculo de días ===
        $day1 = strtotime($entrada . ' ' . $hora_e);
        $day2 = strtotime($salida  . ' ' . $hora_s);
        $diffHours = round(($day2 - $day1) / 3600);
        $dias = $diffHours / 24;
        $partes = explode('.', (string)$dias);

        if (count($partes) == 1) {
            $cant_dias = $dias;
        } else {
            $cant_dias = intval($dias) + 1;
        }

        // === Ajuste por temporada ===
        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->one();
        if (!is_null($precioTemporada)) {
            foreach ($precio_diario as $key => $diario) {
                // OJO: aquí 'costo' proviene de servicios.* del join
                $precio_diario[$key]['precio'] = $precio_diario[$key]['costo'] +
                    ($precio_diario[$key]['cantidad'] * $precioTemporada->precio);
            }
        }

        // === Tipos de pago / IVA / precio_dia cfg ===
        $pagos      = TipoPago::find()->where(['estatus' => '1'])->all();
        $tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion');

        $iva = 0;
        $impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
        foreach ($impuestos as $imp) {
            if ((int)$imp->tipo_impuesto === 1) {
                $iva = $imp->valor_numerico;
            }
        }

        $precio_dia_cfg = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '0'])->one();

        // === POST: guardar ===
        if ($model->load($req->post()) && $modelC->load($req->post()) && $modelV->load($req->post())) {
            $model->plan = $req->post('Reservas')['plan'] ?? $plan;

            $paradaActiva = $this->tieneParadaActiva($model->fecha_entrada, $model->hora_entrada, $model->fecha_salida, $model->hora_salida);
            if ($paradaActiva) {
                Yii::$app->session->setFlash('error', 'Para la fecha de entrada o salida no tenemos plazas disponibles.');
                return $this->redirect(['site/organic']);
            }

            // Eliminar lavado cortesía si existe lavado completo (con defaults seguros)
            $cantidad1 = (int)$req->post('cantidad1', 0);
            $cantidad2 = (int)$req->post('cantidad2', 0);
            $cantidad7 = (int)$req->post('cantidad7', 0);
            if (($cantidad2 > 0 && $cantidad7 > 0) || ($cantidad1 > 0 && $cantidad7 > 0)) {
                unset($servicios[3]);
            }

            // Nro de reserva único
            $num_reserva = substr(strtotime(date('Y-m-d H:i:s')), 2, 10);
            $buscarReserva = Reservas::find()->where(['nro_reserva' => $num_reserva])->one();
            if ($buscarReserva !== null) {
                $num_reserva = substr(strtotime(date('Y-m-d H:i:s')), 2, 10);
            }
            $model->nro_reserva = $num_reserva;

            // Releer servicios día/seguro (nombres distintos para no pisar)
            $precioDiaServices = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '0'])->all();
            $seguroServices    = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '1'])->all();

            // Normalizar fechas al formato de BD
            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));

            $model->estatus  = 1;
            $modelC->estatus = 1;

            $idmax  = UserCliente::find()->max('id');
            $iduc   = $idmax + 1;
            $ahora  = date('Y-m-d H:i:s');

            // Cliente por móvil
            $client = Clientes::find()->where(['movil' => $modelC->movil])->one();
            if ($client === null) {
                $modelC->estatus     = 1;
                $modelC->created_at  = $ahora;
                $modelC->updated_at  = $ahora;
                $modelC->created_by  = $iduc;
                $modelC->save();

                if ($modelC->hasErrors()) {
                    Yii::$app->session->setFlash('error', 'Error en Carga de Datos');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                $model->id_cliente = $modelC->id;
                $modelV->id_cliente = $modelC->id;
            } else {
                $model->id_cliente  = $client->id;
                $modelV->id_cliente = $client->id;
            }

            $modelV->estatus_coche = 1;

            $u          = UserCliente::find()->where(['id_cliente' => $modelV->id_cliente])->one();
            $busca_user = User::find()->where(['email' => $modelC->correo])->one();

            $modelV->created_at = $ahora;
            $modelV->updated_at = $ahora;
            $modelV->created_by = ($u === null) ? (UserCliente::find()->max('id') + 1) : $u->id;

            // Vincular coche
            $model->id_coche = $this->resolveCarId($modelV);

            // === Bucle de servicios (con defaults seguros) ===
            foreach ($servicios as $ser) {
                $precio_unitario = (float)$req->post('precio_unitario' . $ser->id, 0);
                $cantidad        = (int)$req->post('cantidad' . $ser->id, 0);
                $precio_total    = (float)$req->post('precio_total' . $ser->id, 0);
                $tipo_servicio   = (int)$req->post('tipo_servicio' . $ser->id, 0);

                if ($precio_total == 0 && $precio_unitario > 0) {
                    $precio_total = $precio_unitario; // compat con main
                }

                if ($cantidad !== 0) {
                    $modelR = new ReservasServicios();
                    $modelR->id_reserva      = $model->nro_reserva;
                    $modelR->id_servicio     = $ser->id;
                    $modelR->cantidad        = $cantidad;
                    $modelR->precio_unitario = $precio_unitario;
                    $modelR->precio_total    = $precio_total;
                    $modelR->tipo_servicio   = $tipo_servicio;
                    $modelR->save();
                }
            }

            // Servicio precio por día (si existe)
            if (!empty($precioDiaServices) && isset($precioDiaServices[0]) && (int)$precioDiaServices[0]->fijo === 0) {
                $modelR = new ReservasServicios();
                $modelR->id_reserva      = $model->nro_reserva;
                $modelR->id_servicio     = $precioDiaServices[0]->id;
                $modelR->cantidad        = (int)$req->post('cant_basico', 0);
                $modelR->precio_unitario = $precioDiaServices[0]->costo;
                $modelR->precio_total    = $model->costo_servicios;
                $modelR->tipo_servicio   = 0;
                $modelR->save();
            }

            // Seguro (si existe)
            if (!empty($seguroServices) && isset($seguroServices[0]) && (int)$seguroServices[0]->fijo === 1) {
                $modelR = new ReservasServicios();
                $modelR->id_reserva      = $model->nro_reserva;
                $modelR->id_servicio     = $seguroServices[0]->id;
                $modelR->cantidad        = (int)$req->post('cant_seguro', 0);
                $modelR->precio_unitario = $seguroServices[0]->costo;
                $modelR->precio_total    = $seguroServices[0]->costo;
                $modelR->tipo_servicio   = 1;
                $modelR->save();
            }

            // Extra nocturno (si aplica)
            $isNoc = ($req->post('is_noc', '') === '11-1');
            if ($isNoc) {
                $servicio_noc_id    = (int)$req->post('servicio_noc_id', 0);
                $servicio_noc_costo = (float)$req->post('servicio_noc_costo', 0);

                if ($servicio_noc_id > 0 && $servicio_noc_costo >= 0) {
                    $modelR = new ReservasServicios();
                    $modelR->id_reserva      = $model->nro_reserva;
                    $modelR->id_servicio     = $servicio_noc_id;
                    $modelR->cantidad        = 1;
                    $modelR->precio_unitario = $servicio_noc_costo;
                    $modelR->precio_total    = $servicio_noc_costo;
                    $modelR->tipo_servicio   = 2;
                    $modelR->save();

                    // El costo del servicio nocturno ya está contemplado en
                    // el monto total, se evita sumarlo nuevamente.
                }
            }

            // Metadatos de la reserva
            $model->created_at    = $ahora;
            $model->updated_at    = $ahora;
            $model->medio_reserva = 5;       // Mantengo tu canal para orgánico
            $model->actualizada   = 0;       // Igual que en actionCreate

            $model->save();

            if ($model->save()) {
                // Usuario/cliente
                if ($u === null && $busca_user === null) {
                    $modelU = new User();
                    $user_name       = $modelC->correo;
                    $modelU->username = $user_name;
                    $modelU->email    = $modelC->correo;
                    $modelU->setPassword($modelC->nro_documento);
                    $modelU->generateAuthKey();
                    $modelU->generateEmailVerificationToken();
                    $modelU->status = 10;
                    $modelU->save();

                    $modelUC = new UserCliente();
                    $modelUC->id_usuario = $modelU->id;
                    $modelUC->id_cliente = $modelV->id_cliente;
                    $modelUC->save();

                    $model->created_by = $modelU->id;
                } else {
                    $modelUC = new UserCliente();
                    $modelUC->id_usuario = $busca_user->id;
                    $modelUC->id_cliente = $modelV->id_cliente;
                    $modelUC->save();
                    $model->created_by = $modelC->id;
                }

                $model->created_at    = $ahora;
                $model->updated_at    = $ahora;
                $model->medio_reserva = 5;
                $model->actualizada   = 0;
                $model->save();

                // === Pago TPV o PDF + Emails ===
                $isBizum = $this->isBizumPayment($model);
                if ((int)$model->id_tipo_pago === 5 || $isBizum) {
                    $this->layout = 'secondary';

                    \Yii::$app->session->open();
                    \Yii::$app->session['reserva'] = $model;
                    \Yii::$app->session->close();

                    $miObj = new RedsysAPI();

                    $version = "HMAC_SHA256_V1";

                    $redsysConfig = $this->getRedsysConfig();
                    $url_tpv = (string)$redsysConfig['paymentUrl'];
                    $merchantKey = (string)$redsysConfig['merchantKey'];

                    $name     = 'PARKING PLUS';
                    $code     = (string)$redsysConfig['fuc'];
                    $terminal = (string)$redsysConfig['terminal'];
                    $order    = $model->nro_reserva;
                    $amount   = $model->monto_total * 100;

                    $currency      = (string)$redsysConfig['currency'];
                    $consumerlng   = '001';
                    $transactionType = '0';
                    $urlMerchant   = 'https://www.parkingplus.es/';
                    $frontendBaseUrl = $this->normalizeFrontendBaseUrl(Yii::$app->params['frontendBaseUrl']);
                    $urlweb_ok     = $frontendBaseUrl . '/site/tpvok';
                    $urlweb_ko     = $frontendBaseUrl . '/site/tpvko';

                    $miObj->setParameter("DS_MERCHANT_AMOUNT", (string)$amount);
                    $miObj->setParameter("DS_MERCHANT_CURRENCY", $currency);
                    $miObj->setParameter("DS_MERCHANT_MERCHANTCODE", $code);
                    $miObj->setParameter("DS_MERCHANT_MERCHANTURL", $urlMerchant);
                    $miObj->setParameter("DS_MERCHANT_ORDER", $order);
                    $miObj->setParameter("DS_MERCHANT_TERMINAL", $terminal);
                    $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $transactionType);
                    $miObj->setParameter("DS_MERCHANT_URLKO", $urlweb_ko);
                    $miObj->setParameter("DS_MERCHANT_URLOK", $urlweb_ok);
                    $miObj->setParameter("DS_MERCHANT_MERCHANTNAME", $name);
                    $miObj->setParameter("DS_MERCHANT_CONSUMERLANGUAGE", $consumerlng);

                    if ($isBizum) {
                        $miObj->setParameter("DS_MERCHANT_PAYMETHODS", "z");
                    }

                    $params    = $miObj->createMerchantParameters();
                    $signature = $miObj->createMerchantSignature($merchantKey);
                    return $this->render('procesar-pago', [
                        'url_tpv'   => $url_tpv,
                        'version'   => $version,
                        'params'    => $params,
                        'signature' => $signature,
                    ]);
                } else {
                    $content = $this->renderPartial('_reportView', ['model' => $this->findModel($model->id)]);

                    $pdf = new Pdf([
                        'mode'        => Pdf::MODE_UTF8,
                        'format'      => Pdf::FORMAT_A4,
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'destination' => Pdf::DEST_FILE,
                        'filename'    => '../web/pdf/comprobante_' . $model->nro_reserva . '.pdf',
                        'content'     => $content,
                        'cssFile'     => '../web/css/reportes.css',
                        'options'     => ['title' => 'Comprobante de Reserva'],
                        'methods'     => [
                            'SetFooter' => ['{PAGENO}'],
                        ]
                    ]);
                    $pdf->render();

                    if ($modelC->correo !== null) {
                        try {
                            $correo = Yii::$app->mailer->compose(
                                ['html' => 'emailReserva2-html', 'text' => 'emailReserva-text'],
                                [
                                    'nro_reserva'     => $model->nro_reserva,
                                    'coche_matricula' => $modelV->matricula,
                                    'fecha_entrada'   => $fecha1,
                                    'hora_entrada'    => $model->hora_entrada,
                                    'fecha_salida'    => $fecha2,
                                    'hora_salida'     => $model->hora_salida,
                                    'token'           => $model->cod_valid
                                ]
                            );
                            $correo->setTo($modelC->correo)
                                ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                                ->setSubject('Reservación Parking Plus')
                                ->attach('../web/pdf/comprobante_' . $model->nro_reserva . '.pdf')
                                ->send();

                            $correo2 = Yii::$app->mailer->compose(
                                ['html' => 'emailReserva2-html', 'text' => 'emailReserva-text'],
                                [
                                    'nro_reserva'   => $model->nro_reserva,
                                    'fecha_entrada' => $fecha1,
                                    'hora_entrada'  => $model->hora_entrada,
                                    'fecha_salida'  => $fecha2,
                                    'hora_salida'   => $model->hora_salida,
                                ]
                            );
                            $correo2->setTo('asistenciaplus00@gmail.com')
                                ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                                ->setSubject('Reservación Parking Plus')
                                ->attach('../web/pdf/comprobante_' . $model->nro_reserva . '.pdf')
                                ->send();
                        } catch (\Exception $e) {
                            // Mantén mismo flujo que actionCreate
                            return $this->redirect(['finalizada', 'reserva' => $model->nro_reserva]);
                        }
                    }

                    // IMPORTANTE: unificar con actionCreate
                    return $this->redirect([
                        'finalizada',
                        'reserva' => $model->nro_reserva,
                    ]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Su reserva no pudo ser procesada. Disculpe las molestias ocasionadas.');
                return $this->redirect(['site/index']);
            }
        }

        // === Render inicial ===
        return $this->render('createN', [
            'model'          => $model,
            'modelC'         => $modelC,
            'modelV'         => $modelV,
            'tipo_documento' => $tipo_documento,
            'terminales'     => $terminales,
            'servicios'      => $servicios,
            'seguro'         => $seguroServices,
            'precio_diario'  => $precio_diario,
            'tipos_pago'     => $tipos_pago,
            'iva'            => $iva,
            'entrada'        => $entrada,
            'salida'         => $salida,
            'hora_e'         => $hora_e,
            'hora_s'         => $hora_s,
            'cant_dias'      => $cant_dias,
            'nocturno'       => $extraNocturno,
            'type_reserva'   => $type_reserva,
            'precio_dia'     => $precio_dia_cfg ? $precio_dia_cfg->valor_numerico : 0,
            'plan'           => $plan
        ]);
    }

    // end ER

    public function Obtener_token($cantidadCaracteres)
    {

        $Caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $ca = strlen($Caracteres);
        $ca--;
        $Hash = '';
        for ($x = 1; $x <= $cantidadCaracteres; $x++) {
            $Posicao = rand(0, $ca);
            $Hash .= substr($Caracteres, $Posicao, 1);
        }
        return $Hash;
    }
    public function actionUpdate()
    {

        $model = Reservas::find()->where(['nro_reserva' => $_GET['codId']])->andWhere(['cod_valid' => $_GET['codValid']])->one();

        if ($model === null) {
            throw new NotFoundHttpException('La reserva solicitada no existe.');
        }

        $modelC = Clientes::find()->where(['id' => $model->id_cliente])->one();
        if ($modelC === null) {
            throw new NotFoundHttpException('El cliente asociado a la reserva no existe.');
        }
        $modelV = Coches::find()->where(['id' => $model->id_coche])->one();

        $modelOld = clone $model;
        $modelCOld = clone $modelC;
        $modelVOld = clone $modelV;

        $model->factura = isset($_GET["invoice"]) && !empty($_GET["invoice"]) ? $_GET["invoice"] : 0;

        $entrada = $model->fecha_entrada;
        $salida = $model->fecha_salida;

        $hora_e = $model->hora_entrada;
        $hora_s = $model->hora_salida;

        $cant_dias = $_GET['cdias'];
        $type_reserva = $_GET['type'];

        $fecha_entrada = strtotime($entrada . ' ' . $hora_e);
        $fecha_salida = strtotime($salida . ' ' . $hora_s);

        $fhne = strtotime($entrada . ' 00:30:00');
        $fhnes = strtotime($entrada . ' 03:45:00');

        $fhns = strtotime($salida . ' 00:30:00');
        $fhnss = strtotime($salida . ' 03:45:00');

        $type_reserva = 0;


        $extraNocturno = Servicios::find()->where(['id' => '11'])->all();

        $extraNocturno[0]['id'] .= (($fecha_entrada > $fhne && $fecha_entrada < $fhnes) || ($fecha_salida > $fhns && $fecha_salida < $fhnss)) ? '-1' : '-0';


        $modelC = Clientes::find()->where(['id' => $model->id_cliente])->one();
        $modelV = Coches::find()->where(['id' => $model->id_coche])->one();

        $oldReserva = clone $model;
        $oldCliente = clone $modelC;

        $tipo_documento = [
            'NIF' => 'NIF',
            'NIE' => 'NIE',
            'Pasaporte' => 'Pasaporte'
        ];

        $terminales = [
            'TERMINAL 1' => 'TERMINAL 1',
            'TERMINAL 2' => 'TERMINAL 2',
            'TERMINAL 3' => 'TERMINAL 3',
            'TERMINAL 4' => 'TERMINAL 4',
            'AUN NO CONOZCO LA TERMINAL' => 'AUN NO CONOZCO LA TERMINAL'
        ];

        $servicios = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '2'])->all();
        $serviceNames = [];
        $oldExtras = [];
        foreach ($servicios as $serAll) {
            $ids_all[] = $serAll['id'];
            $serviceNames[$serAll['id']] = $serAll['nombre_servicio'];
        }

        $servicios_sel = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])->all();
        foreach ($servicios_sel as $serSel) {
            $fijo = $serSel->servicios['fijo'];
            if ($fijo == 2) {
                $ids_sel[] = $serSel['id_servicio'];
                $oldExtras[$serSel['id_servicio']] = $serSel['cantidad'];
            }
            if ($serSel->id_servicio == 9) {
                $type_reserva = 9;
            } else if ($serSel->id_servicio == 12) {
                $type_reserva = 12;
            }
        }

        if (isset($ids_sel)) {
            if (count($ids_sel) > 0) {
                foreach ($ids_all as $idall) {
                    foreach ($ids_sel as $idsel) {
                        if ($idall == $idsel) {
                            $seleccionados[] = $idall;
                        }
                    }
                }
            }
        } else {
            $seleccionados = null;
        }

        $seguro = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '1'])->all();

        $query = new Query();
        $query->select(
            [
                'registro_precios2.id_lista',
                'registro_precios2.cantidad',
                'registro_precios2.costo AS precio',
                'servicios.*'
            ]
        )
            ->from('registro_precios2')
            ->join(
                'LEFT JOIN',
                'servicios',
                'registro_precios2.id_lista = servicios.id_listas_precios'
            );

        $command = $query->createCommand();
        $precio_diario = $command->queryAll();

        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->all();

        $day1 = $entrada . ' ' . $hora_e;
        $day1 = strtotime($day1);
        $day2 = $salida . ' ' . $hora_s;
        $day2 = strtotime($day2);

        $diffHours = round(($day2 - $day1) / 3600);

        $dias = $diffHours / 24;

        $partes = explode('.', $dias);


        if (count($partes) == 1) {
            $cant_dias = $dias;
        } else {
            $cant_dias = intval($dias) + 1;
        }


        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->one();

        if (!is_null($precioTemporada)) {
            foreach ($precio_diario as $key => $diario) {
                $precio_diario[$key]['precio'] = $precio_diario[$key]['costo'] + ($precio_diario[$key]['cantidad'] * $precioTemporada->precio);
            }
        }


        $pagos = TipoPago::find()->where(['estatus' => '1'])->all();
        $tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion');

        $impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
        foreach ($impuestos as $imp) {
            $tipo_imp = $imp->tipo_impuesto;
            if ($tipo_imp == 1) {
                $iva = $imp->valor_numerico;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $modelC->load(Yii::$app->request->post()) && $modelV->load(Yii::$app->request->post())) {

            if ($_POST['solicitud_factura']) {
                $this->actionGenerarf($model->id);

                $correo = Yii::$app->mailer->compose(
                    [
                        'html' => 'emailFactura-html',
                    ],
                    [
                        'nro_reserva' => $model->nro_reserva,
                    ]
                );

                $correo->setTo("parkingplus01@gmail.com")
                    ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                    ->setSubject('Reservación ParkingPlus con Factura')
                    ->send();

                Yii::$app->session->setFlash('success', 'La solicitud de la factura ha sido realizada correctamente.');

                return $this->redirect(['site/index']);
            }


            //Eliminando Lavado cortesia si existe lavado completo

            if (($_POST['cantidad2'] > 0 && $_POST['cantidad7'] > 0) || ($_POST['cantidad1'] > 0 && $_POST['cantidad7'] > 0)) {
                unset($servicios[3]);
            }

            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));

            $montonoiva = ($model->monto_total / $iva);
            $montoimp = $model->monto_total - $montonoiva;

            $model->monto_factura = round($montonoiva, 2);
            $model->monto_impuestos = round($montoimp, 2);

            $idmax = UserCliente::find()->max('id');
            $iduc = $idmax + 1;
            $fecha_creacion = date('Y-m-d H:i:s');


            $client = Clientes::find()->where(['movil' => $modelC->movil])->one();


            if ($client == null) {
                $modelC->estatus = 1;
                $modelC->created_at = $fecha_creacion;
                $modelC->updated_at = $fecha_creacion;
                $modelC->created_by = $iduc;
                $modelC->save();

                $errorC = count($modelC->getErrors());

                if ($errorC > 0) {
                    Yii::$app->session->setFlash('error', 'Error en Carga de Datos');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                $model->id_cliente = $modelC->id;
                $modelV->id_cliente = $modelC->id;
            } else {
                $model->id_cliente = $client->id;
                $modelV->id_cliente = $client->id;
            }

            $modelV->estatus_coche = 1;

            $v = Coches::find()->where(['matricula' => $modelV->matricula])->one();

            $u = UserCliente::find()->where(['id_cliente' => $modelV->id_cliente])->one();

            $busca_user = User::find()->where(['email' => $modelC->correo])->one();


            $fecha_creacion = date('Y-m-d H:i:s');
            $modelV->created_at = $fecha_creacion;
            $modelV->updated_at = $fecha_creacion;

            if ($u == null) {
                $idmax = UserCliente::find()->max('id');
                $iduc = $idmax + 1;
                $modelV->created_by = $iduc;
            } else {
                $modelV->created_by = $u->id;
            }

            $model->id_coche = $this->resolveCarId($modelV);

            $newExtras = [];
            foreach ($servicios as $ser) {
                if ($ser->fijo == 2) {
                    $newExtras[$ser->id] = (int)$_POST['cantidad' . $ser->id];
                }
            }

            $extraChanges = [];
            $allExtraIds = array_unique(array_merge(array_keys($oldExtras), array_keys($newExtras)));
            foreach ($allExtraIds as $sid) {
                $oldQty = $oldExtras[$sid] ?? 0;
                $newQty = $newExtras[$sid] ?? 0;
                if ($oldQty != $newQty) {
                    $campo = 'servicio_' . ($serviceNames[$sid] ?? $sid);
                    $extraChanges[] = ['campo' => $campo, 'old' => $oldQty, 'new' => $newQty];
                }
            }

            $buscaServicios = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])->all();

            foreach ($buscaServicios as $ser) {
                $ser->delete();
            }

            foreach ($servicios as $ser) {

                $modelR = new ReservasServicios();
                $precio_unitario = $_POST['precio_unitario' . $ser->id];
                $cantidad = $_POST['cantidad' . $ser->id];
                $precio_total = $_POST['precio_unitario' . $ser->id];
                $tipo_servicio = $_POST['tipo_servicio' . $ser->id];

                if ($cantidad != 0) {
                    $modelR->id_reserva = $model->nro_reserva;
                    $modelR->id_servicio = $ser->id;
                    $modelR->cantidad = $cantidad;
                    $modelR->precio_unitario = $precio_unitario;
                    $modelR->precio_total = $precio_total;
                    $modelR->tipo_servicio = $tipo_servicio;
                    $modelR->save();
                }
            }

            $precio_dia = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '0'])->all();

            if ($precio_dia[0]->fijo == 0) {
                $modelR = new ReservasServicios();
                $modelR->id_reserva = $model->nro_reserva;
                $modelR->id_servicio = $precio_dia[0]->id;
                $modelR->cantidad = $_POST['cant_basico'];
                $modelR->precio_unitario = $precio_dia[0]->costo;
                $modelR->precio_total = $model->costo_servicios;
                $modelR->tipo_servicio = 0;
                $modelR->save();
            }

            if ($seguro[0]->fijo == 1) {
                $modelR = new ReservasServicios();
                $modelR->id_reserva = $model->nro_reserva;
                $modelR->id_servicio = $seguro[0]->id;
                $modelR->cantidad = $_POST['cant_seguro'];
                $modelR->precio_unitario = $seguro[0]->costo;
                $modelR->precio_total = $seguro[0]->costo;
                $modelR->tipo_servicio = 1;
                $modelR->save();
            }


            if ($_POST['is_noc'] == '11-1') {
                $modelR = new ReservasServicios();
                $modelR->id_reserva = $model->nro_reserva;
                $modelR->id_servicio = $_POST['servicio_noc_id'];
                $modelR->cantidad = 1;
                $modelR->precio_unitario = $_POST['servicio_noc_costo'];
                $modelR->precio_total = $_POST['servicio_noc_costo'];
                $modelR->tipo_servicio = 2;
                $modelR->save();

                // El costo del servicio nocturno ya está considerado en el
                // monto total, evitando una doble suma.
            }

            // Normalizar fechas y horas para evitar diferencias de formato
            $modelOld->fecha_entrada = date('Y-m-d', strtotime($modelOld->fecha_entrada));
            $modelOld->fecha_salida = date('Y-m-d', strtotime($modelOld->fecha_salida));

            $modelOld->hora_entrada = date('H:i', strtotime($modelOld->hora_entrada));
            $modelOld->hora_salida = date('H:i', strtotime($modelOld->hora_salida));

            $model->hora_entrada = date('H:i', strtotime($model->hora_entrada));
            $model->hora_salida = date('H:i', strtotime($model->hora_salida));

            $changes = $extraChanges;

            $reservaAttrs = [
                'fecha_entrada',
                'fecha_salida',
                'hora_entrada',
                'hora_salida',
                'terminal_entrada',
                'terminal_salida',
                'nro_vuelo_regreso',
                'ciudad_procedencia',
                'observaciones',
                'factura',
                'nif',
                'razon_social',
                'direccion',
                'cod_postal',
                'ciudad',
                'provincia',
                'pais',
                'id_tipo_pago'
            ];
            foreach ($reservaAttrs as $attr) {
                if ($modelOld->$attr != $model->$attr) {
                    $changes[] = ['campo' => $attr, 'old' => $modelOld->$attr, 'new' => $model->$attr];
                }
            }

            $clienteAttrs = ['nombre_completo', 'correo', 'nro_documento', 'movil'];
            foreach ($clienteAttrs as $attr) {
                if ($modelCOld->$attr != $modelC->$attr) {
                    $campo = $attr === 'movil' ? 'telefono' : $attr;
                    $changes[] = ['campo' => $campo, 'old' => $modelCOld->$attr, 'new' => $modelC->$attr];
                }
            }

            $cocheAttrs = ['marca', 'modelo', 'matricula'];
            foreach ($cocheAttrs as $attr) {
                if ($modelVOld->$attr != $modelV->$attr) {
                    $changes[] = ['campo' => $attr, 'old' => $modelVOld->$attr, 'new' => $modelV->$attr];
                }
            }

            $model->actualizada = 1;

            if ($model->save()) {
                if ($model->estatus == 3) {
                    foreach ($changes as $change) {
                        $log = new ReservasLogCambios();
                        $log->reserva_id = $model->id;
                        $log->campo = $change['campo'];
                        $log->valor_anterior = $change['old'];
                        $log->valor_nuevo = $change['new'];
                        $log->save(false);
                    }
                }
                if (($u == null) && ($busca_user == null)) {
                    $modelU = new User();
                    $user_name = $modelC->correo;
                    $modelU->username = $user_name;
                    $modelU->email = $modelC->correo;
                    $modelU->setPassword($modelC->nro_documento);
                    $modelU->generateAuthKey();
                    $modelU->generateEmailVerificationToken();
                    $modelU->status = 10;
                    $modelU->save();

                    $modelUC = new UserCliente();
                    $modelUC->id_usuario = $modelU->id;
                    $modelUC->id_cliente = $modelV->id_cliente;
                    $modelUC->save();

                    $model->created_by = $modelU->id;
                } else {

                    $modelUC = new UserCliente();
                    $modelUC->id_usuario = $busca_user->id;
                    $modelUC->id_cliente = $modelV->id_cliente;
                    $modelUC->save();
                    $model->created_by = $modelC->id;
                }

                $model->save();

                $isBizum = $this->isBizumPayment($model);
                if ((int)$model->id_tipo_pago === 5 || $isBizum) {
                    $this->layout = 'secondary';

                    /*
                    $res = Reservas:: find()->where(['nro_reserva' => $reserva])->one();
                    $mtotal = $res->monto_total;
                    return $this->render('pagos', [
                        'reserva' => $reserva,
                        'monto' => $mtotal,
                    ]);
                    */

                    \Yii::$app->session->open();
                    \Yii::$app->session['reserva'] = $model;
                    \Yii::$app->session->close();


                    $miObj = new RedsysAPI();

                    $version = "HMAC_SHA256_V1";

                    $redsysConfig = $this->getRedsysConfig();
                    $url_tpv = (string)$redsysConfig['paymentUrl'];
                    $merchantKey = (string)$redsysConfig['merchantKey'];

                    $name = 'PARKING PLUS';
                    $code = (string)$redsysConfig['fuc'];
                    $terminal = (string)$redsysConfig['terminal'];
                    $order = $model->nro_reserva;
                    $amount = $model->monto_total * 100;

                    $currency = (string)$redsysConfig['currency'];
                    $consumerlng = '001';
                    $transactionType = '0';
                    $urlMerchant = 'https://www.parkingplus.es/';
                    $frontendBaseUrl = $this->normalizeFrontendBaseUrl(Yii::$app->params['frontendBaseUrl']);
                    $urlweb_ok = $frontendBaseUrl . '/site/tpvok';
                    $urlweb_ko = $frontendBaseUrl . '/site/tpvko';

                    $miObj->setParameter("DS_MERCHANT_AMOUNT", (string) $amount);
                    $miObj->setParameter("DS_MERCHANT_CURRENCY", $currency);
                    $miObj->setParameter("DS_MERCHANT_MERCHANTCODE", $code);
                    $miObj->setParameter("DS_MERCHANT_MERCHANTURL", $urlMerchant);
                    $miObj->setParameter("DS_MERCHANT_ORDER", $order);
                    $miObj->setParameter("DS_MERCHANT_TERMINAL", $terminal);
                    $miObj->setParameter("DS_MERCHANT_TRANSACTIONTYPE", $transactionType);
                    $miObj->setParameter("DS_MERCHANT_URLKO", $urlweb_ko);
                    $miObj->setParameter("DS_MERCHANT_URLOK", $urlweb_ok);

                    $miObj->setParameter("DS_MERCHANT_MERCHANTNAME", $name);
                    $miObj->setParameter("DS_MERCHANT_CONSUMERLANGUAGE", $consumerlng);

                    if ($isBizum) {
                        $miObj->setParameter("DS_MERCHANT_PAYMETHODS", "z");
                    }

                    $params = $miObj->createMerchantParameters();
                    $signature = $miObj->createMerchantSignature($merchantKey);
                    return $this->render('procesar-pago', [
                        'url_tpv' => $url_tpv,
                        'version' => $version,
                        'params' => $params,
                        'signature' => $signature,
                    ]);
                } else {

                    $content = $this->renderPartial('_reportView', ['model' => $this->findModel($model->id)]);

                    $pdf = new Pdf([
                        'mode' => Pdf::MODE_UTF8,
                        'format' => Pdf::FORMAT_A4,
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'destination' => Pdf::DEST_FILE,
                        'filename' => '../web/pdf/comprobante_' . $model->nro_reserva . '.pdf',
                        'content' => $content,
                        'cssFile' => '../web/css/reportes.css',
                        'options' => ['title' => 'Comprobante de Reserva'],
                        'methods' => [
                            'SetFooter' => ['{PAGENO}'],
                        ]
                    ]);

                    $pdf->render();

                    //$reserva = $model->nro_reserva;

                    //$mensaje = '';

                    if ($modelC->correo != null) {
                        try {
                            $correo = Yii::$app->mailer->compose(
                                [
                                    'html' => 'emailReserva2-html',
                                    'text' => 'emailReserva-text'
                                ],
                                [
                                    'nro_reserva' => $model->nro_reserva,
                                    'coche_matricula' => $modelV->matricula,
                                    'fecha_entrada' => $fecha1,
                                    'hora_entrada' => $model->hora_entrada,
                                    'fecha_salida' => $fecha2,
                                    'hora_salida' => $model->hora_salida,
                                ]
                            );

                            $correo->setTo($modelC->correo)
                                ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                                ->setSubject('Reservación Parking Plus')
                                ->attach('../web/pdf/comprobante_' . $model->nro_reserva . '.pdf')
                                ->send();


                            $correo2 = Yii::$app->mailer->compose(
                                [
                                    'html' => 'emailReserva2-html',
                                    'text' => 'emailReserva-text'
                                ],
                                [
                                    'nro_reserva' => $model->nro_reserva,
                                    'fecha_entrada' => $fecha1,
                                    'hora_entrada' => $model->hora_entrada,
                                    'fecha_salida' => $fecha2,
                                    'hora_salida' => $model->hora_salida,
                                ]
                            );
                            $correo2->setTo('asistenciaplus00@gmail.com')
                                ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                                ->setSubject('Reservación Parking Plus')
                                ->attach('../web/pdf/comprobante_' . $model->nro_reserva . '.pdf')
                                ->send();
                        } catch (\Exception $e) {
                            return $this->redirect(['finalizada', 'reserva' => $model->nro_reserva]);
                        }
                    }

                    return $this->redirect([
                        'finalizada',
                        'reserva' => $model->nro_reserva,
                    ]);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Su reserva no pudo ser procesada. Disculpe las molestias ocasionadas.');
            }

            return $this->redirect(['site/index']);
        }


        return $this->render('createN', [
            'model' => $model,
            'modelC' => $modelC,
            'modelV' => $modelV,
            'tipo_documento' => $tipo_documento,
            'terminales' => $terminales,
            'servicios' => $servicios,
            'seguro' => $seguro,
            'precio_diario' => $precio_diario,
            'tipos_pago' => $tipos_pago,
            'iva' => $iva,
            'entrada' => $entrada,
            'salida' => $salida,
            'hora_e' => $hora_e,
            'hora_s' => $hora_s,
            'cant_dias' => $cant_dias,
            'nocturno' => $extraNocturno,
            'type_reserva' => $type_reserva,
            'seleccionados' => $seleccionados,
            'token' => $_GET['token'],
            'solicitud_factura' => isset($_GET["invoice"]) && !empty($_GET["invoice"]) ? true : false
        ]);
    }

    public function actionGenerarf($reservaId)
    {
        $model = new FacturasReserva();

        $model->id_reserva = $reservaId;

        $datos_reserva = Reservas::find()->where(['id' => $reservaId])->one();

        if ($datos_reserva != null) {

            $modelFactura = new Facturas();

            $facturas = Facturas::find()->max('id');
            if ($facturas == 0) {
                $facturas = Factureros::find()->where(['estatus' => '1'])->one();
                $idfactura = 1;
            } else {
                $idfactura = $facturas + 1;
            }

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

            $modelFactura->id = $idfactura;
            $modelFactura->serie = $serie;
            $modelFactura->nro_factura = $proxima_factura;
            $modelFactura->nif = $datos_reserva->nif;
            $modelFactura->razon_social = $datos_reserva->razon_social;
            $modelFactura->direccion = $datos_reserva->direccion;
            $modelFactura->cod_postal = $datos_reserva->cod_postal;
            $modelFactura->ciudad = $datos_reserva->ciudad;
            $modelFactura->provincia = $datos_reserva->provincia;
            $modelFactura->pais = $datos_reserva->pais;

            $buscaiva = Configuracion::find()->where(['tipo_campo' => 1])->one();
            $iva = $buscaiva->valor_numerico;

            $modelFactura->monto_factura = round(($datos_reserva->monto_total / $iva), 2);
            $modelFactura->monto_impuestos = round(($datos_reserva->monto_total - ($datos_reserva->monto_total / $iva)), 2);
            $modelFactura->monto_total = $datos_reserva->monto_total;

            $modelFactura->id_tipo_pago = $datos_reserva->id_tipo_pago;
            $modelFactura->estatus = 1;

            $modelFactura->save();

            $facturas = Facturas::find()->max('id');
            if ($facturas == 0) {
                $facturas = Factureros::find()->where(['estatus' => '1'])->one();
                $idfactura = 1;
            } else {
                $idfactura = $facturas;
            }

            $model->id_factura = $idfactura;
            $model->save();

            $servicios = ReservasServicios::find()->where(['id_reserva' => $datos_reserva->nro_reserva])->all();

            foreach ($servicios as $service) {
                $modelFS = new FacturasServicios();
                $modelFS->id_factura = $idfactura;
                $modelFS->id_servicio = $service->id_servicio;
                $modelFS->cantidad = $service->cantidad;
                $modelFS->precio_unitario = $service->precio_unitario;
                $modelFS->precio_total = $service->precio_total;
                $modelFS->tipo_servicio = $service->tipo_servicio;
                $modelFS->save();
            }
        }

        Yii::$app->session->setFlash('success', 'La Factura se ha solicitado de manera exitosa.');
        return $this->redirect(['site/index']);
    }

    public function actionAnulacion()
    {
        if (Yii::$app->request->post()) {

            $reserva = Reservas::find()->where(['nro_reserva' => Yii::$app->request->post('reserva')])->one();

            $buscaServicios = ReservasServicios::find()->where(['id_reserva' => $reserva->nro_reserva])->all();
            foreach ($buscaServicios as $servicio) {
                $rs = ReservasServicios::findOne($servicio->id);
                $rs->delete();
            }

            $reserva->delete();

            Yii::$app->session->setFlash('success', 'Se ha realizado la anulación de la reserva de manera correcta.');
            return $this->redirect(['site/index']);
        }

        return $this->renderAjax('anulacion', [
            'reserva' => $_GET['reserva'],
        ]);
    }

    public function actionModifica()
    {

        if (Yii::$app->request->post()) {
            $fecha_entrada = date('Y-m-d', strtotime($_POST['fecha_entrada']));
            $hora_entrada = $_POST['hora_entrada'];
            $fecha_salida = date('Y-m-d', strtotime($_POST['fecha_salida']));
            $hora_salida = $_POST['hora_salida'];

            $dias = (strtotime($fecha_entrada) - strtotime($fecha_salida)) / 86400;
            $dias = abs($dias);
            $dias = floor($dias);

            if ($dias == 0) {
                $horas = (strtotime($hora_entrada) - strtotime($hora_salida)) / 3600;
                $horas = abs($horas);
                $horas = floor($horas);
                if ($horas == 0) {
                    $minutos = (strtotime($hora_entrada) - strtotime($hora_salida)) / 60;
                    $minutos = abs($minutos);
                    $minutos = floor($minutos);
                    if ($minutos > 0) {
                        $dias = $dias + 1;
                    }
                } else {
                    $dias = $dias + 1;
                }
            } else {
                $minutos = (strtotime($hora_entrada) - strtotime($hora_salida)) / 60;
                //$minutos = abs($minutos); $minutos = floor($minutos);
                $aux_dias = $dias;
                if ($minutos < 0) {
                    $dias = $dias + 1;
                }
                if ($minutos > 0) {
                    $dias = $aux_dias;
                }
            }
            return ($dias);
        }
    }

    public function actionTpvok()
    {

        $miObj = new RedsysAPI();

        $version = $_GET["Ds_SignatureVersion"];
        $params = $_GET["Ds_MerchantParameters"];
        $signatureRecibida = $_GET["Ds_Signature"];

        $decodec = $miObj->decodeMerchantParameters($params);

        $codigoRespuesta = $miObj->getParameter("Ds_Response");

        $redsysConfig = $this->getRedsysConfig();
        $claveModuloAdmin = (string)$redsysConfig['merchantKey'];

        $signatureCalculada = $miObj->createMerchantSignatureNotif($claveModuloAdmin, $params);

        //var_dump($signatureRecibida.' -- '.$signatureCalculada.' -- '.$codigoRespuesta); die();

        $model = Yii::$app->session->get('reserva');
        if ($model === null) {
            Yii::$app->session->setFlash('error', 'No se encontró la reserva de la sesión.');
            return $this->redirect(['site/index']);
        }

        $isApproved = is_numeric($codigoRespuesta) && (int)$codigoRespuesta < 100;
        $reservaPersistida = Reservas::findOne($model->id);
        if ($reservaPersistida !== null) {
            $reservaPersistida->pago_confirmado = $isApproved ? 1 : 0;
            $reservaPersistida->save(false);
            $model = $reservaPersistida;
        }

        if ($signatureCalculada === $signatureRecibida && $isApproved) {

            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));

            $this->sendReservaEmail($model, $fecha1, $fecha2);

            //unlink('../web/pdf/comprobante_'.$reserva.'.pdf');

            \Yii::$app->session->destroy();


            return $this->redirect(['finalizada', 'reserva' => $model->nro_reserva]);
        } elseif ($signatureCalculada === $signatureRecibida) {
            $paymentNotice = '¡Reserva confirmada! <strong>NO hemos podido procesar el pago online</strong>, pero no te preocupes: tu plaza está garantizada. Podrás realizar el pago en efectivo o con tarjeta al momento de entregar tu vehículo.';
            $paymentNoticePdf = 'No hemos podido procesar el pago online, Podrás realizar el pago en efectivo o con tarjeta al momento de entregar tu vehículo.';
            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));
            $this->sendReservaEmail($model, $fecha1, $fecha2, $paymentNoticePdf);
            Yii::$app->session->setFlash('payment_notice', $paymentNotice);
            Yii::$app->session->remove('reserva');
            return $this->redirect(['finalizada', 'reserva' => $model->nro_reserva]);
        } else {
            Yii::$app->session->setFlash('error', 'Firma inválida en la respuesta del TPV.');
            return $this->redirect(['site/index']);
        }
    }

    public function actionTpvko()
    {
        $miObj = new RedsysAPI();

        $version = $_GET["Ds_SignatureVersion"];
        $params = $_GET["Ds_MerchantParameters"];
        $signatureRecibida = $_GET["Ds_Signature"];

        $decodec = $miObj->decodeMerchantParameters($params);

        $codigoRespuesta = $miObj->getParameter("Ds_Response");

        $redsysConfig = $this->getRedsysConfig();
        $claveModuloAdmin = (string)$redsysConfig['merchantKey'];

        $signatureCalculada = $miObj->createMerchantSignatureNotif($claveModuloAdmin, $params);

        //var_dump($signatureRecibida.' -- '.$signatureCalculada.' -- '.$codigoRespuesta); die();

        if ($signatureCalculada === $signatureRecibida) {
            $model = Yii::$app->session->get('reserva');
            if ($model === null) {
                Yii::$app->session->setFlash('error', 'No se encontró la reserva de la sesión.');
                return $this->redirect(['site/index']);
            }
            $reservaPersistida = Reservas::findOne($model->id);
            if ($reservaPersistida !== null) {
                $reservaPersistida->pago_confirmado = 0;
                $reservaPersistida->save(false);
                $model = $reservaPersistida;
            }

            $paymentNotice = '¡Reserva confirmada! <strong>NO hemos podido procesar el pago online</strong>, pero no te preocupes: tu plaza está garantizada. Podrás realizar el pago en efectivo o con tarjeta al momento de entregar tu vehículo.';
            $paymentNoticePdf = 'No hemos podido procesar el pago online, Podrás realizar el pago en efectivo o con tarjeta al momento de entregar tu vehículo.';
            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));
            $this->sendReservaEmail($model, $fecha1, $fecha2, $paymentNoticePdf);
            Yii::$app->session->setFlash('payment_notice', $paymentNotice);
            Yii::$app->session->remove('reserva');
            return $this->redirect(['finalizada', 'reserva' => $model->nro_reserva]);
        }
    }

    private function sendReservaEmail(
        Reservas $model,
        string $fechaEntrada,
        string $fechaSalida,
        ?string $paymentNotice = null
    ): void
    {
        $content = $this->renderPartial('_reportView', [
            'model' => $this->findModel($model->id),
            'paymentNotice' => $paymentNotice,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_FILE,
            'filename' => '../web/pdf/comprobante_' . $model->nro_reserva . '.pdf',
            'content' => $content,
            'cssFile' => '../web/css/reportes.css',
            'options' => ['title' => 'Comprobante de Reserva'],
            'methods' => [
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        $pdf->render();

        $reserva = $model->nro_reserva;
        $matricula = $model->coche ? $model->coche->matricula : null;

        if ($model->cliente->correo != null) {
            try {
                $correo = Yii::$app->mailer->compose(
                    [
                        'html' => 'emailReserva2-html',
                        'text' => 'emailReserva-text'
                    ],
                    [
                        'nro_reserva'     => $reserva,
                        'coche_matricula' => $matricula,
                        'fecha_entrada'   => $fechaEntrada,
                        'hora_entrada'    => $model->hora_entrada,
                        'fecha_salida'    => $fechaSalida,
                        'hora_salida'     => $model->hora_salida,
                        'token'           => $model->cod_valid,
                    ]
                );

                $correo->setTo($model->cliente->correo)
                    ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                    ->setSubject('Reservación Parking Plus')
                    ->attach('../web/pdf/comprobante_' . $reserva . '.pdf')
                    ->send();

                $correo2 = Yii::$app->mailer->compose(
                    [
                        'html' => 'emailReserva2-html',
                        'text' => 'emailReserva-text'
                    ],
                    [
                        'nro_reserva'   => $reserva,
                        'fecha_entrada' => $fechaEntrada,
                        'hora_entrada'  => $model->hora_entrada,
                        'fecha_salida'  => $fechaSalida,
                        'hora_salida'   => $model->hora_salida,
                    ]
                );
                $correo2->setTo('asistenciaplus00@gmail.com')
                    ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                    ->setSubject('Reservación Parking Plus')
                    ->attach('../web/pdf/comprobante_' . $reserva . '.pdf')
                    ->send();
            } catch (\Exception $e) {
                Yii::error('Error enviando correo TPV: ' . $e->getMessage(), __METHOD__);
            }
        }
    }

    public function actionProcesada($id, $paymentId, $token, $PayerID, $signatureRecibida, $signatureCalculada)
    {
        $res = Reservas::findOne($id);
        $servicios = ReservasServicios::find()->where(['id_reserva' => $id])->all();
        $cant_servicios = count($servicios);

        /*
        return $this->render('procesada', [
            'model' => $model,
            'servicios' => $servicios,
            'cant_servicios' => $cant_servicios,
            'paymentId' => $paymentId,
            'token' => $token,
            'PayerID' => $PayerID,
            'signatureRecibida' => $signatureRecibida,
            'signatureCalculada' => $signatureCalculada,
        ]);
        */

        return $this->render('reserva-procesada', [
            'reserva' => $res,
        ]);
    }

    public function actionFinalizada($reserva)
    {
        $res = Reservas::find()->where(['nro_reserva' => $reserva])->one();

        return $this->render('reserva-procesada', [
            'reserva' => $res,
        ]);
    }

    public function actionSuccess($reserva)
    {
        $res = Reservas::find()->where(['nro_reserva' => $reserva])->one();

        return $this->render('reserva-procesada', [
            'reserva' => $res,
        ]);
    }

    private function normalizeFrontendBaseUrl($frontendBaseUrl)
    {
        $frontendBaseUrl = trim((string)$frontendBaseUrl);
        if ($frontendBaseUrl === '') {
            return 'https://parkingplus.es/aparcamiento';
        }

        $frontendBaseUrl = rtrim($frontendBaseUrl, '/');
        if (parse_url($frontendBaseUrl, PHP_URL_SCHEME) === null) {
            $frontendBaseUrl = 'https://' . ltrim($frontendBaseUrl, '/');
        }

        return $frontendBaseUrl;
    }

    public function actionSolicitarf()
    {

        if (Yii::$app->request->post()) {

            $num_reserva = $_POST['num_reserva'];
            $nif = $_POST['nif'];
            $razon_social = $_POST['razon_social'];
            $direccion = $_POST['direccion'];
            $cod_postal = $_POST['cod_postal'];
            $ciudad = $_POST['ciudad'];
            $provincia = $_POST['provincia'];
            $pais = $_POST['pais'];

            $modelReserva = Reservas::find()->where(['nro_reserva' => $num_reserva])->one();

            if ($modelReserva == null) {
                Yii::$app->session->setFlash('error', '<b>El N° de Reserva</b> ingresado en su solicitud no corresponde con ninguna reserva en nuestra base de datos. Verifique el N° de Reserva e intente nuevamente.');
                return $this->redirect(['site/index']);
            } else {
                if ($modelReserva->factura == 0) {
                    $modelReserva->factura = 1;
                    $modelReserva->nif = $nif;
                    $modelReserva->razon_social = $razon_social;
                    $modelReserva->direccion = $direccion;
                    $modelReserva->cod_postal = $cod_postal;
                    $modelReserva->ciudad = $ciudad;
                    $modelReserva->provincia = $provincia;
                    $modelReserva->pais = $pais;
                    $modelReserva->updated_at = date('Y-m-d H:i:s');
                    $modelReserva->save();

                    $confirmacion_correo = Yii::$app->mailer->compose(
                        [
                            'html' => 'emailSolicitud-html',
                        ],
                        [
                            'num_reserva' => $num_reserva,
                            'nif' => $nif,
                            'razon_social' => $razon_social,
                            'direccion' => $direccion,
                            'cod_postal' => $cod_postal,
                            'ciudad' => $ciudad,
                            'provincia' => $provincia,
                            'pais' => $pais,
                        ]
                    );

                    $confirmacion_correo->setTo('facturas@parkingplus.es')
                        ->setFrom([Yii::$app->params['reservasEmail'] => 'Facturación - ' . Yii::$app->name])
                        ->setSubject('Solicitud de Factura - Parking Plus')
                        ->send();

                    Yii::$app->session->setFlash('success', 'Su Solicitud de Factura fué realizada de manera exitosa. Nos pondremos en contacto con usted a la brevedad posible.');
                    return $this->redirect(['site/index']);
                } else {
                    Yii::$app->session->setFlash('error', 'Le Informamos que al momento de reservar usted realizó la solicitud de factura, de igual forma nos pondremos en contacto con usted para cualquier duda con respecto a su factura.');
                    return $this->redirect(['site/index']);

                    $confirmacion_correo = Yii::$app->mailer->compose(
                        [
                            'html' => 'emailSolicitud-html',
                        ],
                        [
                            'num_reserva' => $num_reserva,
                            'nif' => $nif,
                            'razon_social' => $razon_social,
                            'direccion' => $direccion,
                            'cod_postal' => $cod_postal,
                            'ciudad' => $ciudad,
                            'provincia' => $provincia,
                            'pais' => $pais,
                        ]
                    );

                    $confirmacion_correo->setTo('facturas@parkingplus.es')
                        ->setFrom([Yii::$app->params['reservasEmail'] => 'Facturación - ' . Yii::$app->name])
                        ->setSubject('Solicitud de Factura - Parking Plus')
                        ->send();
                }
            }
        }
        return $this->renderAjax('solicitud_factura');
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerificacion-html', 'text' => 'emailVerificacion-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Registro de Cuenta')
            ->send();
    }

    /**
     * Resolves the car id associated with the given Coches model.
     * If the license plate is empty, assigns 'N/D' and saves the model.
     * If a car with the same plate exists, its id is returned; otherwise the
     * provided model is saved and its id returned.
     *
     * @param Coches $modelV
     * @return int|null The resolved car id or null on failure
     */
    private function resolveCarId($modelV)
    {
        if (empty($modelV->matricula)) {
            $modelV->matricula = 'N/D';
            if ($modelV->save()) {
                return $modelV->id;
            }
            return null;
        }

        $v = Coches::find()->where(['matricula' => $modelV->matricula])->one();
        if ($v === null) {
            if ($modelV->save()) {
                return $modelV->id;
            }
            return null;
        }

        return $v->id;
    }

    public function actionReserva()
    {

        if (Yii::$app->request->post()) {
            $model = new Reservas();
            $model->fecha_entrada = empty($_POST['Reservas']['fechae']) ? date("d-m-Y", strtotime(date("d-m-Y") . "+ 1 days")) : $_POST['Reservas']['fechae'];
            $model->hora_entrada = empty($_POST['Reservas']['horae']) ? date('H:i') : $_POST['Reservas']['horae'];
            $model->fecha_salida = empty($_POST['Reservas']['fechas']) ? date("d-m-Y", strtotime(date("d-m-Y") . "+ 1 days")) : $_POST['Reservas']['fechas'];
            $model->hora_salida = empty($_POST['Reservas']['horas']) ? date('H:i') : $_POST['Reservas']['horas'];


            $fecha_entrada = strtotime($model->fecha_entrada . ' ' . $model->hora_entrada);
            $fecha_salida = strtotime($model->fecha_salida . ' ' . $model->hora_salida);

            $diffHours = round(($fecha_salida - $fecha_entrada) / 3600);

            $dias = $diffHours / 24;

            $partes = explode('.', $dias);

            if (count($partes) == 1) {
                $cant_dias = $dias;
            } else {
                $cant_dias = intval($dias) + 1;
            }

            $fhne = strtotime($model->fecha_entrada . ' 00:30:00');
            $fhnes = strtotime($model->fecha_entrada . ' 03:45:00');

            $fhns = strtotime($model->fecha_salida . ' 00:30:00');
            $fhnss = strtotime($model->fecha_salida . ' 03:45:00');

            $queryS = new Query();
            $queryS->select('id, costo')->from('servicios')->where(['id' => [2, 6, 9, 12]]);

            $commandS = $queryS->createCommand();

            $query = new Query();
            $query->select(
                [
                    'registro_precios2.id_lista',
                    'registro_precios2.cantidad',
                    'registro_precios2.costo AS precio',
                    'servicios.*'
                ]
            )
                ->from('registro_precios2')
                ->join(
                    'LEFT JOIN',
                    'servicios',
                    'registro_precios2.id_lista = servicios.id_listas_precios'
                );

            $command = $query->createCommand();
            $precios_diarios = $command->queryAll();


            $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->one();


            if (!is_null($precioTemporada)) {
                foreach ($precios_diarios as $key => $diario) {
                    $precios_diarios[$key]['precio'] = $precios_diarios[$key]['costo'] + ($precios_diarios[$key]['cantidad'] * $precioTemporada->precio);
                }
            }


            $precio_dia = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '0'])->one();


            return $this->render('reservaN', [
                'cant_dias' => $cant_dias == 0 ? 1 : $cant_dias,
                'model' => $model,
                'precio_diario' => $precios_diarios,
                'nocturno' => Servicios::find()->where(['nombre_servicio' => 'Costo Nocturnidad'])->one(),
                'servicios' => $commandS->queryAll(),
                'temporada' => $precioTemporada,
                'precio_dia' => $precio_dia->valor_numerico
            ]);
        }
    }

    /** ER 09-07
     * Muestra el formulario de encuesta inicial.
     * @param string|null $reserva Numero de reserva para mostrar en la vista
     * @return string
     */
    public function actionEncuesta1()
    {
        $model = new EncuestaInicial();

        // Solo asigna reserva_id si es GET
        if (Yii::$app->request->isGet) {
            $reservaId = Yii::$app->request->get('reserva');
            $model->reserva_id = $reservaId;

            // Verifica si ya existe una valoración para la reserva
            if ($reservaId) {
                $valoracion = EncuestaInicial::findOne(['reserva_id' => $reservaId]);
                if ($valoracion !== null) {
                    return $this->render('encuesta1_completada', [
                        'valoracion' => $valoracion,
                    ]);
                }
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $max = max([
                $model->pregunta1,
                $model->pregunta2,
                $model->pregunta3,
            ]);

            if ($max >= 4 && empty($model->sugerencias)) {
                return $this->render('encuesta1_sugerencias', ['model' => $model]);
            }

            if ($model->save()) {
                if ($max < 4) {
                    return $this->redirect('https://g.page/r/CSa4fL5NJ---EBM/review');
                }

                return $this->render('encuesta1_confirm', ['model' => $model]);
            }

            Yii::error('Error al guardar la encuesta: ' . \yii\helpers\VarDumper::dumpAsString($model->errors), __METHOD__);
            Yii::$app->session->setFlash(
                'error',
                'No se pudo guardar la encuesta: ' . implode(', ', $model->getFirstErrors())
            );
        }

        return $this->render('encuesta1', ['model' => $model]);
    }



    // agg ER

    public function actionReservaorganic()
    {

        if (Yii::$app->request->post()) {
            $model = new Reservas();
            $model->fecha_entrada = empty($_POST['Reservas']['fechae']) ? date("d-m-Y", strtotime(date("d-m-Y") . "+ 1 days")) : $_POST['Reservas']['fechae'];
            $model->hora_entrada = empty($_POST['Reservas']['horae']) ? date('H:i') : $_POST['Reservas']['horae'];
            $model->fecha_salida = empty($_POST['Reservas']['fechas']) ? date("d-m-Y", strtotime(date("d-m-Y") . "+ 1 days")) : $_POST['Reservas']['fechas'];
            $model->hora_salida = empty($_POST['Reservas']['horas']) ? date('H:i') : $_POST['Reservas']['horas'];


            $paradaActiva = $this->tieneParadaActiva($model->fecha_entrada, $model->hora_entrada, $model->fecha_salida, $model->hora_salida);


            $fecha_entrada = strtotime($model->fecha_entrada . ' ' . $model->hora_entrada);
            $fecha_salida = strtotime($model->fecha_salida . ' ' . $model->hora_salida);

            $diffHours = round(($fecha_salida - $fecha_entrada) / 3600);

            $dias = $diffHours / 24;

            $partes = explode('.', $dias);

            if (count($partes) == 1) {
                $cant_dias = $dias;
            } else {
                $cant_dias = intval($dias) + 1;
            }

            $fhne = strtotime($model->fecha_entrada . ' 00:30:00');
            $fhnes = strtotime($model->fecha_entrada . ' 03:45:00');

            $fhns = strtotime($model->fecha_salida . ' 00:30:00');
            $fhnss = strtotime($model->fecha_salida . ' 03:45:00');

            $queryS = new Query();
            $queryS->select('id, costo')->from('servicios')->where(['id' => [2, 6, 9, 12]]);

            $commandS = $queryS->createCommand();

            $query = new Query();
            $query->select(
                [
                    'registro_precios2.id_lista',
                    'registro_precios2.cantidad',
                    'registro_precios2.costo AS precio',
                    'servicios.*'
                ]
            )
                ->from('registro_precios2')
                ->join(
                    'LEFT JOIN',
                    'servicios',
                    'registro_precios2.id_lista = servicios.id_listas_precios'
                );

            $command = $query->createCommand();
            $precios_diarios = $command->queryAll();


            $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->one();


            if (!is_null($precioTemporada)) {
                foreach ($precios_diarios as $key => $diario) {
                    $precios_diarios[$key]['precio'] = $precios_diarios[$key]['costo'] + ($precios_diarios[$key]['cantidad'] * $precioTemporada->precio);
                }
            }


            $precio_dia = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '0'])->one();


            return $this->render('reservaorganic', [
                'cant_dias' => $cant_dias == 0 ? 1 : $cant_dias,
                'model' => $model,
                'precio_diario' => $precios_diarios,
                'nocturno' => Servicios::find()->where(['nombre_servicio' => 'Costo Nocturnidad'])->one(),
                'servicios' => $commandS->queryAll(),
                'temporada' => $precioTemporada,
                'precio_dia' => $precio_dia->valor_numerico,
                'paradaActiva' => $paradaActiva
            ]);
        }

        return $this->redirect(['site/organic']);
    }

    // end ER

    /**
     * Metodo que elimina pdf del server mayores a 60 dias
     */
    public function actionDelete()
    {
        $files = glob('../web/pdf/*'); //obtenemos el nombre de todos los ficheros


        foreach ($files as $file) {
            $lastModifiedTime = filemtime($file);
            $currentTime = time();
            $timeDiff = abs($currentTime - $lastModifiedTime) / (24 * 60 * 60); //en horas
            if (is_file($file) && $timeDiff > 60)
                unlink($file); //elimino el fichero
        }

        echo 'finalizo';
    }
}
