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
    
        $model = new Reservas;

        $query = new Query;
        $query  ->select([
            'registro_precios.id_lista', 
            'registro_precios.cantidad',
            'registro_precios.costo AS precio',
            'servicios.*']
            )  
            ->from('registro_precios')
            ->join('LEFT JOIN', 'servicios',
                'registro_precios.id_lista = servicios.id_listas_precios'); 
          
        $command = $query->createCommand();
        $precio_diario = $command->queryAll(); 

        $milista = $precio_diario[0]['id_lista'];

        $buscaAgregado = ListasPrecios::find()->where(['id' => $milista])->all();

        $agregado = $buscaAgregado[0]->agregado;                

        return $this->render('index', [
            'model' => $model,
            'precio_diario' => $precio_diario,
            'agregado' => $agregado,
        ]);
    }

    public function actionFechas()
    {
        $model = new Reservas;

        if ($model->load(Yii::$app->request->post())) {
            $entrada = $model->fecha_entrada;
            $salida = $model->fecha_salida;
            $hora_e = $model->hora_entrada;
            $hora_s = $model->hora_salida; 
     
            return Yii::$app->response->redirect(['site/create', 'entrada' => $entrada, 'salida' => $salida, 'hora_e' => $hora_e, 'hora_s' => $hora_s])->send();
        }           
        return $this->renderAjax('fechas', [
            'model' => $model,
        ]);
    }


    /**
     * Displays homepage Loged.
     *
     * @return mixed
     */
    public function actionPanel()
    {
        $model = new Reservas;

        $id = Yii::$app->user->id;
        $user_cliente = UserCliente::find()->where(['id_usuario' => $id])->one();
        if ($user_cliente == NULL) {
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

        $facturas = NULL;
        
        for ($i=0; $i < $cantR ; $i++) { 
            $buscaFactura[$i] = FacturasReserva::find()->where(['id_reserva' => $reservas[$i]->id])->one();
            
            if ($buscaFactura[$i] === NULL) {
                $facturas = NULL;    
            } else {
                $facturas[$i] = Facturas::find()->where(['id' => $buscaFactura[$i]->id_factura])->one();
                
            }
            
        }

        $coches = Coches::find()->where(['id_cliente' => $datos->id])->limit(5)->all();

        if ($model->load(Yii::$app->request->post())) {
            if (($model->fecha_entrada == NULL) || ($model->fecha_salida == NULL)) {
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

        }else{
            $datos = "Error en Carga de Datos";
        }
   
        return Json::encode($datos);
    } 

    public function encrypt_3DES($message, $key){
        $bytes = array(0,0,0,0,0,0,0,0);
        $iv = implode(array_map("chr", $bytes)); 
        $ciphertext = mcrypt_encrypt(MCRYPT_3DES, $key, $message, MCRYPT_MODE_CBC, $iv);
        return $ciphertext;
    }

    public function mac256($ent,$key){
        $res = hash_hmac('sha256', $ent, $key, true);
        return $res;
    } 


    public function actionCadena()
    {

        if(Yii::$app->request->post()) {

            $MONTO = $_POST['monto'];
            $AMOUNT = str_replace('.', '', $MONTO);
            $ORDER  = $_POST['pedido'];
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
            //$COMERCIO = 'sq7HjrUOBfKmC576ILgskD5srU870gJ7';
            //CLAVE DEL COMERCIO PRODUCCION
            $COMERCIO = 'X/5rYzzA5kbrxgt7Afx74djVpM3+LARq';
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

            $crea_signature = $this->mac256($datos,$key);

            $signature = base64_encode($crea_signature);

        } else{
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

        $dataProvider->pagination->pageSize=10;

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
            'NIF'=>'NIF', 'NIE'=>'NIE','Pasaporte'=>'Pasaporte'
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

        $dataProvider->pagination->pageSize=10;

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

    public function actionProcesada()
    {
        $mens = 'Su reserva ha sido procesada de manera exitosa. Revise su correo electrónico para mayor información';
        return $this->render('reserva-procesada', [
            'mens' => $mens,
        ]);
    }    

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

    public function actionPdf($id) {

        $content = $this->renderPartial('_reportView',['model' => $this->findModel($id)]);   
        
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, 
            'format' => Pdf::FORMAT_A4, 
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            'destination' => Pdf::DEST_BROWSER, 
            'content' => $content,  
            'cssFile' => '../web/css/reportes.css',
            'methods' => [ 
                'SetFooter'=>['{PAGENO}'],
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
        
        $model = new Reservas();
        $modelC = new Clientes();
        $modelV = new Coches(); 

        $tipo_documento = [
            'NIF'=>'NIF', 'NIE'=>'NIE','Pasaporte'=>'Pasaporte'
        ];               

        $terminales = [
            'TERMINAL 1'=>'TERMINAL 1', 'TERMINAL 2'=>'TERMINAL 2',
            'TERMINAL 3'=>'TERMINAL 3','TERMINAL 4'=>'TERMINAL 4','AUN NO CONOZCO LA TERMINAL'=>'AUN NO CONOZCO LA TERMINAL'
        ]; 

        $servicios = Servicios::find()->where(['estatus'=>'1'])->andWhere(['fijo'=>'2'])->all();

        $seguro = Servicios::find()->where(['estatus'=>'1'])->andWhere(['fijo'=>'1'])->all();

        $query = new Query;
        $query  ->select([
            'registro_precios.id_lista', 
            'registro_precios.cantidad',
            'registro_precios.costo AS precio',
            'servicios.*']
        )  
        ->from('registro_precios')
        ->join('LEFT JOIN', 'servicios',
            'registro_precios.id_lista = servicios.id_listas_precios'); 

        $command = $query->createCommand();
        $precio_diario = $command->queryAll();

        $pagos = TipoPago::find()->where(['estatus'=>'1'])->all();
        $tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion');

        $impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
        foreach ($impuestos as $imp) {
            $tipo_imp = $imp->tipo_impuesto;
            if ($tipo_imp == 1) {
                $iva = $imp->valor_numerico;
            }
        }                          

        if ($model->load(Yii::$app->request->post()) && $modelC->load(Yii::$app->request->post()) && $modelV->load(Yii::$app->request->post())) {

            $nro_reserva = Reservas::find()->max('nro_reserva');
            if ($nro_reserva == 0) {
                $correlativo = Configuracion::find()->where(['estatus' => '1', 'tipo_campo' => '2'])->one();
                $nro_reserva = $correlativo->valor_numerico;
                $num_reserva = intval($nro_reserva); 
            } else {
                $num_reserva = intval($nro_reserva) + 1;    
            }  

            $model->nro_reserva = $num_reserva;

            $precio_dia = Servicios::find()->where(['estatus'=>'1'])->andWhere(['fijo'=>'0'])->all(); 

            $seguro = Servicios::find()->where(['estatus'=>'1'])->andWhere(['fijo'=>'1'])->all();

            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));

            $model->estatus = 1;
            
            $modelC->estatus = 1;

            $c = Clientes::find()->where(['nro_documento' => $modelC->nro_documento])->one();

            if ($c === NULL) { 
                $idmax = UserCliente::find()->max('id');
                $iduc = $idmax + 1;
                $fecha_creacion = date('Y-m-d H:i:s');
                $modelC->created_at = $fecha_creacion;
                $modelC->created_by = $iduc;

                $modelC->save();
                $model->id_cliente = $modelC->id;
                $modelV->id_cliente = $modelC->id;
            } else {
                $modelV->id_cliente = $c->id;
                $model->id_cliente = $c->id;
            }
            
            $modelV->estatus = 1;

            $v = Coches::find()->where(['matricula' => $modelV->matricula])->one();

            $u = UserCliente::find()->where(['id_cliente' => $modelV->id_cliente])->one();

            $fecha_creacion = date('Y-m-d H:i:s');
            $modelV->created_at = $fecha_creacion;

            if ($u == NULL) {
                $idmax = UserCliente::find()->max('id');
                $iduc = $idmax + 1;
                $modelV->created_by = $iduc;    
            } else {
                $modelV->created_by = $u->id;
            }            

            if ($v == NULL) {
                $modelV->save();
                $model->id_coche = $modelV->id;
            } else {
                $model->id_coche = $v->id; 
            }     
     
            foreach ($servicios as $ser) {

                $modelR = new ReservasServicios;
                $precio_unitario = $_POST['precio_unitario'.$ser->id];
                $cantidad = $_POST['cantidad'.$ser->id];
                $precio_total = $_POST['precio_total'.$ser->id];
                $tipo_servicio = $_POST['tipo_servicio'.$ser->id];

                if ($cantidad != 0) {
                    $modelR->id_reserva = $num_reserva;
                    $modelR->id_servicio = $ser->id;
                    $modelR->cantidad = $cantidad;
                    $modelR->precio_unitario = $precio_unitario;
                    $modelR->precio_total = $precio_total;
                    $modelR->tipo_servicio = $tipo_servicio;
                    $modelR->save();
                }
            }  

            if ($precio_dia[0]->fijo == 0) {
                $modelR = new ReservasServicios;
                $modelR->id_reserva = $num_reserva;
                $modelR->id_servicio = $precio_dia[0]->id;
                $modelR->cantidad = $_POST['cant_basico'];
                $modelR->precio_unitario = $precio_dia[0]->costo;
                $modelR->precio_total = $model->costo_servicios;
                $modelR->tipo_servicio = 0;
                $modelR->save();              
            }

            if ($seguro[0]->fijo == 1) {
                $modelR = new ReservasServicios;
                $modelR->id_reserva = $num_reserva;
                $modelR->id_servicio = $seguro[0]->id;
                $modelR->cantidad = $_POST['cant_seguro'];
                $modelR->precio_unitario = $seguro[0]->costo;
                $modelR->precio_total = $seguro[0]->costo;
                $modelR->tipo_servicio = 1;
                $modelR->save();               
            }           
                
            //$u = UserCliente::find()->where(['id_cliente' => $modelV->id_cliente])->one();
                
            $model->save();

            if ($model->save()) {
                if ($u == NULL) {
                    $modelU = new User;
                    /*
                    $length = 10;
                    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $user_name = '';
                    for ($i = 0; $i < $length; $i++) {
                        $user_name .= $characters[rand(0, $charactersLength - 1)];
                    }
                    */

                    $user_name = $modelC->correo;

                    $modelU->username = $user_name;
                    $modelU->email = $modelC->correo;
                    $modelU->setPassword($modelC->nro_documento);
                    $modelU->generateAuthKey();
                    $modelU->generateEmailVerificationToken();
                    $modelU->status = 10;
                    $modelU->save();

                    $modelUC = new UserCliente;
                    $modelUC->id_usuario = $modelU->id;
                    $modelUC->id_cliente = $modelV->id_cliente;
                    $modelUC->save();

                    $model->created_by = $modelU->id; 
                } else {

                    $model->created_by = $u->id;     
                }

                $fecha_creacion = date('Y-m-d H:i:s');
                $model->created_at = $fecha_creacion;
                $model->medio_reserva = 3;

                $model->save();           
                        
                $content = $this->renderPartial('_reportView',['model' => $this->findModel($model->id)]);   

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
                        'SetFooter'=>['{PAGENO}'],
                    ]
                ]);

                $pdf->render();

                $reserva = $model->nro_reserva;
                
                //$mensaje = '';

                if ($modelC->correo != NULL) {

                $correo = Yii::$app->mailer->compose(['html' => 'emailReserva-html', 'text' => 'emailReserva-text']);
                $correo->setTo($modelC->correo)
                        ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - '.Yii::$app->name])
                        ->setSubject('Reservación Parking Plus')
                        ->attach('../web/pdf/comprobante_'.$reserva.'.pdf')
                        ->send(); 

                }

                if ($model->id_tipo_pago == 5) {
                    $this->layout = 'secondary';
                    $res = Reservas:: find()->where(['nro_reserva' => $reserva])->one();
                    $mtotal = $res->monto_total;
                    return $this->render('pagos', [
                        'reserva' => $reserva, 
                        'monto' => $mtotal,
                    ]);
                } else {
                    return $this->redirect(['site/procesada']);    
                }

                //Yii::$app->session->setFlash('success', 'Su reserva ha sido procesada de manera exitosa. Revise su correo electrónico para mayor información');
    
   
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
        ]);
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
