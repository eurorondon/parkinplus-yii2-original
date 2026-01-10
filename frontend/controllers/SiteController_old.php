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
use common\models\ReservasSearch;
use common\models\CochesSearch;
use common\models\Paradas;
use common\models\PrecioTemporada;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseArrayHelper;
use yii\base\InvalidArgumentException;
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
        $query  ->select(
            [
            'registro_precios.id_lista',
            'registro_precios.cantidad',
            'registro_precios.costo AS precio',
            'servicios.*']
        )
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

        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->all();

        if(count($precioTemporada) > 0) {
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

    public function actionFechas()
    {
        $model = new Reservas();
        if ($model->load(Yii::$app->request->post())) {
            $cdias = $_POST['cantdias'];
            $entrada = $model->fecha_entrada;
            $salida = $model->fecha_salida;
            $hora_e = $model->hora_entrada;
            $hora_s = $model->hora_salida;

            return Yii::$app->response->redirect(['site/create', 'cdias' => $cdias,'entrada' => $entrada, 'salida' => $salida, 'hora_e' => $hora_e, 'hora_s' => $hora_s])->send();
        }
        return $this->renderAjax('fechas', [
            'model' => $model,
        ]);
    }

    public function actionParada()
    {

        if(Yii::$app->request->post()) {

            $fecha_entrada = date('Y-m-d', strtotime($_POST['fecha_e']));
            $hora_entrada = $_POST['hora_e'];
            $fecha_salida = date('Y-m-d', strtotime($_POST['fecha_s']));
            $hora_salida = $_POST['hora_s'];

            $in = $fecha_entrada.' '.$hora_entrada;
            $fecha_in = new DateTime($in);
            $fecha_in = $fecha_in->format('Y-m-d H:i:s');

            $fin = $fecha_salida.' '.$hora_salida;
            $fecha_finc = new DateTime($fin);
            $fecha_finc = $fecha_finc->format('Y-m-d H:i:s');

            $buscaParadas = Paradas::find()->all();

            $cantParadas = count($buscaParadas);

            if ($cantParadas > 0) {
                foreach ($buscaParadas as $parada) {
                    $fecha_inicio = $parada['fecha_inicio'];
                    $hora_inicio = $parada['hora_inicio'];
                    $fecha_fin = $parada['fecha_fin'];
                    $hora_fin = $parada['hora_fin'];

                    $date_ini = $fecha_inicio.' '.$hora_inicio;
                    $fecha_i = new DateTime($date_ini);
                    $fecha_i = $fecha_i->format('Y-m-d H:i:s');

                    $date_fin = $fecha_fin.' '.$hora_fin;
                    $fecha_f = new DateTime($date_fin);
                    $fecha_f = $fecha_f->format('Y-m-d H:i:s');

                    if (($fecha_in >= $fecha_i) and ($fecha_in <= $fecha_f)) {
                        $result = $parada['descripcion'];
                        break;
                    } elseif(($fecha_finc >= $fecha_i) and ($fecha_finc <= $fecha_f)) {
                        $result = $parada['descripcion'];
                        break;
                    } else {
                        $result = 1;
                    }
                }
            } else {
                $result = 1;
            }
            return ($result);
        }
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

        for ($i = 0; $i < $cantR ; $i++) {
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
        if(Yii::$app->request->post()) {
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
            $datos = $numero_reserva.'/'.$fecha_e.'/'.$fecha_s.'/'.$monto.'/'.$nombre;

        } else {
            $datos = "Error en Carga de Datos";
        }

        return Json::encode($datos);
    }

    public function encrypt_3DES($message, $key)
    {
        $bytes = array(0,0,0,0,0,0,0,0);
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

        if(Yii::$app->request->post()) {

            $MONTO = $_POST['monto'];
            $AMOUNT = str_replace('.', '', $MONTO);
            $ORDER  = $_POST['pedido'];
            $redsysConfig = Yii::$app->params['redsys'] ?? [];
            $MERCHANTCODE = (string)($redsysConfig['fuc'] ?? '');
            $CURRENCY = (string)($redsysConfig['currency'] ?? '');
            $TRANSACTIONTYPE = '0';
            $TERMINAL = (string)($redsysConfig['terminal'] ?? '');
            $URL = 'http://parkingplus.es/aparcamiento/site/notificacion';
            //$PAN = $_POST['nro_tarjeta'];
            //$PAN = str_replace('-', '', $PAN);
            //$EXPIRYDATE = $_POST['fecha_expira'];
            //$CVV2 = $_POST['cvv'];
            //CLAVE DEL COMERCIO TEST
            //$COMERCIO = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
            //CLAVE DEL COMERCIO PRODUCCION
            $COMERCIO = (string)($redsysConfig['merchantKey'] ?? '');
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
        return ($datos.'/'.$signature);
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
            'NIF' => 'NIF', 'NIE' => 'NIE','Pasaporte' => 'Pasaporte'
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

        $fecha_entrada = strtotime($entrada . ' ' . $hora_e);
        $fecha_salida = strtotime($salida . ' ' . $hora_s);

        $fhne = strtotime($entrada . ' 00:30:00');
        $fhnes = strtotime($entrada . ' 03:45:00');

        $fhns = strtotime($salida . ' 00:30:00');
        $fhnss = strtotime($salida . ' 03:45:00');

        $extraNocturno = null;

        if(($fecha_entrada >= $fhne && $fecha_entrada <= $fhnes) || ($fecha_salida >= $fhns && $fecha_salida <= $fhnss)) {
            $extraNocturno = Servicios::find()->where(['nombre_servicio' => 'Costo Nocturnidad'])->all();
        }

        $model = new Reservas();
        $modelC = new Clientes();
        $modelV = new Coches();

        $tipo_documento = [
            'NIF' => 'NIF', 'NIE' => 'NIE','Pasaporte' => 'Pasaporte'
        ];

        $terminales = [
            'TERMINAL 1' => 'TERMINAL 1', 'TERMINAL 2' => 'TERMINAL 2',
            'TERMINAL 3' => 'TERMINAL 3','TERMINAL 4' => 'TERMINAL 4','AUN NO CONOZCO LA TERMINAL' => 'AUN NO CONOZCO LA TERMINAL'
        ];

        $servicios = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '2'])->all();

        $seguro = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '1'])->all();

        $query = new Query();
        $query  ->select(
            [
            'registro_precios.id_lista',
            'registro_precios.cantidad',
            'registro_precios.costo AS precio',
            'servicios.*']
        )
        ->from('registro_precios')
        ->join(
            'LEFT JOIN',
            'servicios',
            'registro_precios.id_lista = servicios.id_listas_precios'
        );

        $command = $query->createCommand();
        $precio_diario = $command->queryAll();

        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->all();

        /*$day1 = $entrada.' '.$hora_e;
        $day1 = strtotime($day1);
        $day2 = $salida.' '.$hora_s;
        $day2 = strtotime($day2);

        $diffHours = round(($day2 - $day1) / 3600);

        $dias = $diffHours / 24;

        $partes = explode('.', $dias);*/


        $horaInicio = new DateTime($entrada.' '.$hora_e);
        $horaTermino = new DateTime($salida.' '.$hora_s);

        $interval = $horaInicio->diff($horaTermino);

        if($interval->d > 0 && ($interval->h > 0 || $interval->i > 0)) {
            $cant_dias = $interval->d + 1;
        } else {
            $cant_dias = $interval->d == 0 ? 1 : $interval->d;
        }

        /*if (count($partes) == 1) {
            $cant_dias = $dias;
        } else {
            $cant_dias = intval($dias) + 1;
        }*/


        $position = null;
        foreach ($precio_diario as $key => $data) {
            if($data['cantidad'] == $cant_dias) {
                $position = $key;
            }
        }

        foreach ($precioTemporada as $temporada) {
            if(strtotime($entrada . ' ' . $hora_e) >= strtotime($temporada->fecha_inicio . ' ' . $temporada->hora_inicio) && strtotime($entrada . ' ' . $hora_e) <= strtotime($temporada->fecha_fin . ' ' . $temporada->hora_fin)) {
                $precio_diario[$position]['precio'] = $precio_diario[$position]['costo'] + ($cant_dias * $temporada->precio);
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

            $length = 8;
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $num_reserva = '';
            for ($i = 0; $i < $length; $i++) {
                $num_reserva .= $characters[rand(0, $charactersLength - 1)];
            }

            $buscarReserva = Reservas::find()->where(['nro_reserva' => $num_reserva])->one();

            if ($buscarReserva != null) {
                $length = 8;
                $characters = '0123456789';
                $charactersLength = strlen($characters);
                $num_reserva = '';
                for ($i = 0; $i < $length; $i++) {
                    $num_reserva .= $characters[rand(0, $charactersLength - 1)];
                }
            }

            $model->nro_reserva = $num_reserva;

            //$num_reserva = ;
            //$model->nro_reserva = substr(strtotime(date('Y-m-d h:i:s')), 6, 7).substr(strtotime(date('Y-m-d h:i:s')), 0, 3);

            $precio_dia = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '0'])->all();

            $seguro = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '1'])->all();

            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));

            $model->estatus = 1;
            $modelC->estatus = 1;

            $idmax = UserCliente::find()->max('id');
            $iduc = $idmax + 1;
            $fecha_creacion = date('Y-m-d H:i:s');

            /*$modelC->created_at = $fecha_creacion;
            $modelC->updated_at = $fecha_creacion;
            $modelC->created_by = $iduc;
            $modelC->save();

            $errorC = count($modelC->getErrors());

            if ($errorC > 0) {
                Yii::$app->session->setFlash('error', 'Error en Carga de Datos');
                return $this->redirect(Yii::$app->request->referrer);
            }
            $model->id_cliente = $modelC->id;
            $modelV->id_cliente = $modelC->id;*/


            $client = Clientes::find()->where(['movil' => $modelC->movil])->one();


            if($client == null) {
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

            if ($v == null) {
                $modelV->save();
                $model->id_coche = $modelV->id;
            } else {
                $model->id_coche = $v->id;
            }

            foreach ($servicios as $ser) {

                $modelR = new ReservasServicios();
                $precio_unitario = $_POST['precio_unitario'.$ser->id];
                $cantidad = $_POST['cantidad'.$ser->id];
                $precio_total = $_POST['precio_total'.$ser->id];
                $tipo_servicio = $_POST['tipo_servicio'.$ser->id];

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


            if($_POST['servicio_noc_id'] != 0) {
                $modelR = new ReservasServicios();
                $modelR->id_reserva = $model->nro_reserva;
                $modelR->id_servicio = $_POST['servicio_noc_id'];
                $modelR->cantidad = 1;
                $modelR->precio_unitario = $_POST['servicio_noc_costo'];
                $modelR->precio_total = $_POST['servicio_noc_costo'];
                $modelR->tipo_servicio = 2;
                $modelR->save();

                $model->monto_factura += $_POST['servicio_noc_costo'];
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

                $model->save();

                if ($model->id_tipo_pago == 5) {
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
                    $redsysConfig = Yii::$app->params['redsys'] ?? [];

                    // URL PARA PRUEBAS TPV
                    //$url_tpv = 'https://sis-t.redsys.es:25443/sis/realizarPago';

                    // URL REAL
                    $url_tpv = (string)($redsysConfig['paymentUrl'] ?? 'https://sis.redsys.es/sis/realizarPago');

                    $version = "HMAC_SHA256_V1";

                    // Clave Real
                    $clave = (string)($redsysConfig['merchantKey'] ?? '');
                    // Clave Pruebas
                    //$clave = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';

                    $name = 'PARKING PLUS';
                    $code = (string)($redsysConfig['fuc'] ?? '');
                    $terminal = (string)($redsysConfig['terminal'] ?? '');
                    $order = $model->nro_reserva;
                    $amount = $model->monto_total * 100;

                    $currency = (string)($redsysConfig['currency'] ?? '');
                    $consumerlng = '001';
                    $transactionType = '0';
                    $urlMerchant = 'https://www.parkingplus.es/';
                    $urlweb_ok = 'https://parkingplus.es/aparcamiento/site/tpvok';
                    $urlweb_ko = 'https://parkingplus.es/aparcamiento/site/tpvko';

                    // URLS PARA PRUEBAS EN LOCALHOST

                    //$urlweb_ok = 'https://localhost/aparcamiento/site/tpvok';
                    //$urlweb_ko = 'https://localhost/aparcamiento/site/tpvko';


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
                    $params = $miObj->createMerchantParameters();
                    $signature = $miObj->createMerchantSignature($clave);
                    return $this->render('procesar-pago', [
                        'url_tpv' => $url_tpv,
                        'version' => $version,
                        'params' => $params,
                        'signature' => $signature
                    ]);
                } else {

                    $content = $this->renderPartial('_reportView', ['model' => $this->findModel($model->id)]);

                    $pdf = new Pdf([
                        'mode' => Pdf::MODE_UTF8,
                        'format' => Pdf::FORMAT_A4,
                        'orientation' => Pdf::ORIENT_PORTRAIT,
                        'destination' => Pdf::DEST_FILE,
                        'filename' => '../web/pdf/comprobante_'.$model->nro_reserva.'.pdf',
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
                                    'fecha_entrada' => $fecha1,
                                    'hora_entrada' => $model->hora_entrada,
                                    'fecha_salida' => $fecha2,
                                    'hora_salida' => $model->hora_salida,
                                ]
                            );

                            $correo->setTo($modelC->correo)
                            ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - '.Yii::$app->name])
                            ->setSubject('Reservación Parking Plus')
                            ->attach('../web/pdf/comprobante_'.$model->nro_reserva.'.pdf')
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
                            ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - '.Yii::$app->name])
                            ->setSubject('Reservación Parking Plus')
                            ->attach('../web/pdf/comprobante_'.$model->nro_reserva.'.pdf')
                            ->send();

                        } catch (\Exception $e) {
                            return $this->redirect(['finalizada', 'reserva' => $model->nro_reserva]);
                        }
                    }

                    return $this->redirect(['finalizada',
                        'reserva' => $model->nro_reserva,
                    ]);

                }
            } else {
                Yii::$app->session->setFlash('error', 'Su reserva no pudo ser procesada. Disculpe las molestias ocasionadas.');
            }

            return $this->redirect(['site/index']);
        }


        return $this->render('create', [
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
            'nocturno' => $extraNocturno
        ]);
    }

    public function actionTpvok()
    {

        $miObj = new RedsysAPI();

        $version = $_GET["Ds_SignatureVersion"];
        $params = $_GET["Ds_MerchantParameters"];
        $signatureRecibida = $_GET["Ds_Signature"];

        $decodec = $miObj->decodeMerchantParameters($params);

        $codigoRespuesta = $miObj->getParameter("Ds_Response");

        // PRUEBAS
        //$claveModuloAdmin = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';

        // REAL
        $redsysConfig = Yii::$app->params['redsys'] ?? [];
        $claveModuloAdmin = (string)($redsysConfig['merchantKey'] ?? '');

        $signatureCalculada = $miObj->createMerchantSignatureNotif($claveModuloAdmin, $params);

        //var_dump($signatureRecibida.' -- '.$signatureCalculada.' -- '.$codigoRespuesta); die();

        if ($signatureCalculada === $signatureRecibida) {

            $model = Yii::$app->session['reserva'];

            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));


            $content = $this->renderPartial('_reportView', ['model' => $this->findModel($model->id)]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_FILE,
                'filename' => '../web/pdf/comprobante_'.$model->nro_reserva.'.pdf',
                'content' => $content,
                'cssFile' => '../web/css/reportes.css',
                'options' => ['title' => 'Comprobante de Reserva'],
                'methods' => [
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]);

            $pdf->render();

            $reserva = $model->nro_reserva;

            //$mensaje = '';

            if ($model->cliente->correo != null) {
                $correo = Yii::$app->mailer->compose(
                    [
                        'html' => 'emailReserva2-html',
                        'text' => 'emailReserva-text'
                    ],
                    [
                        'nro_reserva' => $reserva,
                        'fecha_entrada' => $fecha1,
                        'hora_entrada' => $model->hora_entrada,
                        'fecha_salida' => $fecha2,
                        'hora_salida' => $model->hora_salida,
                    ]
                );

                $correo->setTo($model->cliente->correo)
                ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - '.Yii::$app->name])
                ->setSubject('Reservación Parking Plus')
                ->attach('../web/pdf/comprobante_'.$reserva.'.pdf')
                ->send();

                $correo2 = Yii::$app->mailer->compose(
                    [
                        'html' => 'emailReserva2-html',
                        'text' => 'emailReserva-text'
                    ],
                    [
                        'nro_reserva' => $reserva,
                        'fecha_entrada' => $fecha1,
                        'hora_entrada' => $model->hora_entrada,
                        'fecha_salida' => $fecha2,
                        'hora_salida' => $model->hora_salida,
                    ]
                );
                $correo2->setTo('asistenciaplus01@gmail.com')
                ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - '.Yii::$app->name])
                ->setSubject('Reservación Parking Plus')
                ->attach('../web/pdf/comprobante_'.$reserva.'.pdf')
                ->send();
            }

            //unlink('../web/pdf/comprobante_'.$reserva.'.pdf');

            \Yii::$app->session->destroy();


            $paymentId = 'NULL';
            $token = 'NULL';
            $PayerID = 'NULL';

            return $this->redirect(['procesada', 'id' => $model->id, 'paymentId' => $paymentId, 'token' => $token, 'PayerID' => $PayerID, 'signatureCalculada' => $signatureCalculada, 'signatureRecibida' => $signatureRecibida]);
        } else {

            $reserva = $model->nro_reserva;
            $idC = $model->id_cliente;
            $idV = $model->id_coche;

            $model->delete();

            $buscaServicios = ReservasServicios::find()->where(['id_reserva' => $reserva])->all();

            if (count($buscaServicios) > 0) {
                foreach ($buscaServicios as $servicio) {
                    $servicio->delete();
                }
            }

            $userC = UserCliente::find()->where(['id_cliente' => $idC])->one();
            if (!is_null($userC)) {
                $userC->delete();
            }

            $coche = Coches::find()->where(['id' => $idV])->one();
            if (!is_null($coche) > 0) {
                $coche->delete();
            }

            $cliente = Clientes::find()->where(['id' => $idC])->one();
            if (!is_null($cliente)) {
                $cliente->delete();
            }

            $msje = 'SU PAGO NO PUDO SER PROCESADO - <b>TRANSACCIÓN DENEGADA : '.$codigoRespuesta.'</b>';
            Yii::$app->session->setFlash('error', $msje);
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

        // PRUEBAS
        //$claveModuloAdmin = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';

        // REAL
        $redsysConfig = Yii::$app->params['redsys'] ?? [];
        $claveModuloAdmin = (string)($redsysConfig['merchantKey'] ?? '');

        $signatureCalculada = $miObj->createMerchantSignatureNotif($claveModuloAdmin, $params);

        //var_dump($signatureRecibida.' -- '.$signatureCalculada.' -- '.$codigoRespuesta); die();

        if ($signatureCalculada === $signatureRecibida) {

            $model = Yii::$app->session['reserva'];

            $reserva = $model->nro_reserva;
            $idC = $model->id_cliente;
            $idV = $model->id_coche;

            $model->delete();

            $buscaServicios = ReservasServicios::find()->where(['id_reserva' => $reserva])->all();

            if (count($buscaServicios) > 0) {
                foreach ($buscaServicios as $servicio) {
                    $servicio->delete();
                }
            }

            $userC = UserCliente::find()->where(['id_cliente' => $idC])->one();
            if (!is_null($userC)) {
                $userC->delete();
            }

            $coche = Coches::find()->where(['id' => $idV])->one();
            if (!is_null($coche) > 0) {
                $coche->delete();
            }

            $cliente = Clientes::find()->where(['id' => $idC])->one();
            if (!is_null($cliente)) {
                $cliente->delete();
            }

            $msje = 'SU PAGO NO PUDO SER PROCESADO - <b>CÓDIGO DE ERROR : '.$codigoRespuesta.'</b>';

            Yii::$app->session->setFlash('error', $msje);
            return $this->redirect(['site/index']);
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
                    ->setFrom([Yii::$app->params['reservasEmail'] => 'Facturación - '.Yii::$app->name])
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
                    ->setFrom([Yii::$app->params['reservasEmail'] => 'Facturación - '.Yii::$app->name])
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
            ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - '.Yii::$app->name])
            ->setTo($user->email)
            ->setSubject('Registro de Cuenta')
            ->send();
    }

}
