<?php

namespace backend\controllers;

use Yii;
use common\models\RegistroPrecios;
use common\models\FacturasReserva;
use common\models\FacturasServicios;
use common\models\Facturas;
use common\models\User;
use common\models\Factureros;
use common\models\Reservas;
use common\models\Clientes;
use common\models\Coches;
use common\models\Servicios;
use common\models\TipoPago;
use common\models\Agencias;
use common\models\Configuracion;
use common\models\ReservasServicios;
use common\models\ReservasSearch;
use common\models\UserAfiliados;
use common\models\PrecioTemporada;
use common\models\EncuestaInicialSearch;
use yii\helpers\BaseArrayHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\db\Query;
use yii\db\Expression;

/**
 * ReservasController implements the CRUD actions for Reservas model.
 */
class ReservasController extends Controller
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

    public function actionChart()
    {
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $mes_actual = $data['mes'];

            $age = Agencias::find()->all();
            foreach ($age as $a) {
                $nom = $a->nombre;
                $cant_ag = 0;
                $reservas = Reservas::find()->where(['agencia' => $nom])->all();
                foreach ($reservas as $res) {
                    $mes_creacion = date('m', strtotime($res->created_at));
                    if ($mes_actual == $mes_creacion) {
                        $cant_ag = $cant_ag + 1;
                    }
                }
                $agencias[] = array('name' => $nom, 'y' => $cant_ag);
            }

            $totales = count(Reservas::find()->all());
            $medio1 = Reservas::find()->where(['medio_reserva' => 1])->all();
            $secretaria = count($medio1);
            $medio2 = Reservas::find()->where(['medio_reserva' => 2])->all();
            $agencia = count($medio2);
            $medio3 = Reservas::find()->where(['medio_reserva' => 3])->all();
            $online = count($medio3);

            $ayo_actual = date('Y');
            $sec_mes = 0;
            $ag_mes = 0;
            $web_mes = 0;
            foreach ($medio1 as $sec) {
                $fecha_reserva = $sec->created_at;
                $fecha = strtotime($fecha_reserva);
                $mes_reserva = date("m", $fecha);
                $ayo_reserva = date("Y", $fecha);
                if (($mes_reserva == $mes_actual) and ($ayo_reserva == $ayo_actual)) {
                    $sec_mes = $sec_mes + 1;
                }
            }
            foreach ($medio2 as $ag) {
                $fecha_reserva = $ag->created_at;
                $fecha = strtotime($fecha_reserva);
                $mes_reserva = date("m", $fecha);
                $ayo_reserva = date("Y", $fecha);
                if (($mes_reserva == $mes_actual) and ($ayo_reserva == $ayo_actual)) {
                    $ag_mes = $ag_mes + 1;
                }
            }
            foreach ($medio3 as $web) {
                $fecha_reserva = $web->created_at;
                $fecha = strtotime($fecha_reserva);
                $mes_reserva = date("m", $fecha);
                $ayo_reserva = date("Y", $fecha);
                if (($mes_reserva == $mes_actual) and ($ayo_reserva == $ayo_actual)) {
                    $web_mes = $web_mes + 1;
                }
            }

            $m_sec = array('name' => 'Secretaria', 'y' => $sec_mes);
            $m_age = array('name' => 'Agencias', 'y' => $ag_mes);
            $m_web = array('name' => 'Página Web', 'y' => $web_mes);

            $datos = array('m_sec' => $m_sec, 'm_age' => $m_age, 'm_web' => $m_web, 'agencias' => $agencias);

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return ['datos' => $datos];
        }
    }

    /**
     * Lists all Reservas models.
     * @return mixed
     */

    // ER TRABAJANDO EN CAMBIAR ESTATUS A  AQUELLAS RESERVAS QUE NO CAMBIAN DE ESTATUS

    public function actionIndex()
    {
        $id_usuario = Yii::$app->user->id;

        // Verificar si el usuario es afiliado
        $buscarAfiliado = UserAfiliados::find()->where(['user_id' => $id_usuario])->one();
        $tipo_afiliado = !empty($buscarAfiliado) ? $buscarAfiliado['tipo_afiliado'] : 0;

        // Configurar zona horaria
        date_default_timezone_set('Europe/Madrid');
        $fechaActual = new \DateTime();
        $ayo = date('Y');
        $noActualizadas = [];

        // Reservas actualizadas desde la web que requieren confirmación
        $actualizadasFront = Reservas::find()
            ->alias('r')
            ->joinWith('cambios')
            ->where(['r.actualizada' => 1, 'r.estatus' => 3])
            ->andWhere(new Expression('reservas_log_cambios.fecha > r.fecha_entrada'))
            ->distinct()
            ->orderBy(['reservas_log_cambios.fecha' => SORT_DESC])
            ->all();

        // 1. ACTUALIZACIÓN DE ESTATUS AUTOMÁTICA (FINALIZADAS)
        $reservasVencidas = Reservas::find()
            ->where(new \yii\db\Expression("TIMESTAMP(fecha_salida, hora_salida) <= NOW()"))
            ->andWhere(['NOT IN', 'estatus', ['0', '2', '4']])
            ->all();

        foreach ($reservasVencidas as $reserva) {
            try {
                $reserva->estatus = '2'; // Finalizada
                if (!$reserva->save(false)) {
                    throw new \Exception('Error al guardar: ' . json_encode($reserva->errors));
                }
            } catch (\Exception $e) {
                $noActualizadas[] = [
                    'id' => $reserva->id,
                    'nro_reserva' => $reserva->nro_reserva,
                    'error' => $e->getMessage(),
                    'fecha_salida' => $reserva->fecha_salida,
                    'estatus_actual' => $reserva->estatus
                ];
                Yii::error("Error actualizando reserva ID {$reserva->id}: " . $e->getMessage());
            }
        }

        // 1.1 ACTUALIZACIÓN DE ESTATUS AUTOMÁTICA (EN CURSO / ACTIVAS)
        $reservasEnCurso = Reservas::find()
            ->where(new \yii\db\Expression("TIMESTAMP(fecha_entrada, hora_entrada) <= NOW()"))
            ->andWhere(new \yii\db\Expression("TIMESTAMP(fecha_salida, hora_salida) > NOW()"))
            ->andWhere(['NOT IN', 'estatus', ['0', '2', '3', '4']]) // Excluye canceladas, finalizadas, ya activas y especiales
            ->all();


        foreach ($reservasEnCurso as $reserva) {
            try {
                $reserva->estatus = '3'; // En curso
                if (!$reserva->save(false)) {
                    throw new \Exception('Error al guardar (estatus 3): ' . json_encode($reserva->errors));
                }
            } catch (\Exception $e) {
                $noActualizadas[] = [
                    'id' => $reserva->id,
                    'nro_reserva' => $reserva->nro_reserva,
                    'error' => $e->getMessage(),
                    'fecha_entrada' => $reserva->fecha_entrada,
                    'estatus_actual' => $reserva->estatus
                ];
                Yii::error("Error actualizando a activa reserva ID {$reserva->id}: " . $e->getMessage());
            }
        }

        // 1.2 ENVÍO AUTOMÁTICO DE ENCUESTAS DE VALORACIÓN
        // $reservasParaEvaluar = Reservas::find()
        //     ->where(['estatus' => 2, 'evaluacion_enviada' => 0])
        //     ->andWhere(new Expression("TIMESTAMP(fecha_salida, hora_salida) <= NOW() - INTERVAL 2 DAY"))
        //     ->all();

        // foreach ($reservasParaEvaluar as $reserva) {
        //     try {
        //         $cliente = $reserva->cliente->nombre_completo;
        //         $email_cliente = trim($reserva->cliente->correo);
        //         $urlEncuesta = Yii::$app->urlManagerFrontend->createAbsoluteUrl([
        //             'site/encuesta1',
        //             'reserva' => $reserva->nro_reserva,
        //         ]);

        //         $correo = Yii::$app->mailer->compose(
        //             [
        //                 'html' => 'evaluacionServicio-html',
        //                 'text' => 'evaluacionServicio-text',
        //             ],
        //             [
        //                 'cliente' => $cliente,
        //                 'nro_reserva' => $reserva->nro_reserva,
        //                 'correo' => $email_cliente,
        //                 'urlEncuesta' => $urlEncuesta,
        //             ]
        //         );
        //         $correo->setTo($email_cliente)
        //             ->setFrom([Yii::$app->params['contactEmail'] => Yii::$app->name])
        //             ->setSubject('Evalúe su reserva de aparcamiento')
        //             ->send();

        //         $reserva->evaluacion_enviada = 1;
        //         $reserva->save(false);
        //     } catch (\Exception $e) {
        //         Yii::error("Error enviando evaluación a reserva {$reserva->id}: " . $e->getMessage());
        //     }
        // }


        // 2. CONSULTA DE AÑOS DISPONIBLES PARA FILTRO
        $query = new \yii\db\Query();
        $query->select(['YEAR(fecha_entrada) as year'])->distinct()->from('reservas');
        $command = $query->createCommand();
        $anios = $command->queryAll();

        $anos = [];
        foreach ($anios as $anio) {
            $anos[$anio['year']] = $anio['year'];
        }

        // 3. DATA PROVIDER Y SEARCH
        $searchModel = new ReservasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 15;

        // 4. DETECCIÓN DE ERRORES EN SERVICIOS EXTRA
        $connection = Yii::$app->getDb();
        $sqlErrores = "
        SELECT 
            r.id, 
            r.nro_reserva, 
            r.costo_servicios_extra, 
            r.created_at,
            r.fecha_entrada,
            r.fecha_salida,
            COUNT(rs.id) AS total_servicios
        FROM reservas r
        LEFT JOIN reservas_servicios rs ON r.nro_reserva = rs.id_reserva
        WHERE r.costo_servicios_extra > 0
        AND r.created_at >= NOW() - INTERVAL 30 DAY
        AND r.fecha_salida >= CURDATE()
        GROUP BY 
            r.id, 
            r.nro_reserva, 
            r.costo_servicios_extra, 
            r.created_at,
            r.fecha_entrada,
            r.fecha_salida
        HAVING COUNT(rs.id) < 3
        ORDER BY r.fecha_salida DESC
    ";
        $reservasConErrores = $connection->createCommand($sqlErrores)->queryAll();

        // 5. CONSULTA DE RESERVAS QUE NO SE ACTUALIZARON (por si alguna falló)
        $pendientesSinActualizar = Reservas::find()
            ->select(['id', 'nro_reserva', 'fecha_salida', 'estatus'])
            ->where(['<', 'fecha_salida', date('Y-m-d')])
            ->andWhere(['NOT IN', 'estatus', ['0', '2', '4']])
            ->orderBy(['fecha_salida' => SORT_DESC])
            ->asArray()
            ->all();

        // 6. LOG DE DEPURACIÓN
        Yii::info("Actualización automática ejecutada. Fecha: " . $fechaActual->format('Y-m-d H:i:s'));
        if (!empty($noActualizadas)) {
            Yii::warning(count($noActualizadas) . " reservas no se actualizaron correctamente.");
        }

        // 7. RENDER DE LA VISTA
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tipo_afiliado' => $tipo_afiliado,
            'anios' => $anos,
            'reservasConErrores' => $reservasConErrores,
            'noActualizadas' => $noActualizadas,
            'fechaActual' => $fechaActual->format('Y-m-d H:i:s'),
            'pendientesSinActualizar' => $pendientesSinActualizar,
            'actualizadasFront' => $actualizadasFront
        ]);
    }


    // public function actionIndex()
    // {

    //     $id_usuario = Yii::$app->user->id;

    //     if (is_null($id_usuario) || empty($id_usuario)) {
    //         $this->redirect(array('site/login'));
    //     }
    //     /* $periodo = 'created_at';
    //      $desde = date('Y-m-d');
    //      $hasta = date('Y-m-d');

    //      // Datos para pruebas
    //      //$desde = date('2022-03-14');
    //      //$hasta = date('2022-03-14');

    //      $params = 'period_type=' . $periodo . '&from=' . $desde . '&till=' . $hasta;

    //      $url = 'https://api.parkos.com/v1/reservations?' . $params;

    //      $ch = curl_init($url);

    //      $token = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxNDA0IiwianRpIjoiYzZhOTZkZjRjMmNmZTY0NmI1NTRkZTUzM2QzMTE4ODZjOGNlMDJhMjJhMWZiYjM4ZGFlZTNjOTFiMWJhOGIxNWY5OGRkNWI1MDNlMGU1MmQiLCJpYXQiOjE2NDU4MjQzNzAsIm5iZiI6MTY0NTgyNDM3MCwiZXhwIjoxNjc3MzYwMzcwLCJzdWIiOiIxMjA4NzI4Iiwic2NvcGVzIjpbXX0.jk4WIsvu17qVaMMA8yV3bWLx9UMEBMQjwDTEUdz7IIxxY0Pcewu9RdDIYE5b6FNAuaY8dNIDUgoldYSZ_2E9TyGnw7EvoKjkiYkBwya75dR-6jh9NDjrmujIJFYNCAGugl9fe3swb33IC99xBh2gUmxRxDmoW7MHebyqflY7X_O7PK8O2HaTC0MItjLGl0FPRlfrJNyFo3z6IDlbcKqzvrs-k31XZugmjfovfoWYXZvfuLx6naeGM3jOc_RjhhzTco5Y9IDhAp9bQolxpMeJuXbpohyyOHrHjTGS3sbE_I5aPlBiQtsSIXJZT9v_ihMqwGjmcfWoVOiyndXt9ezOMfUnxujPJOu0AthkWSk3acyiwC9UhHnwEpmRGD7mMIkuFyYQ9s1Fp_QIsD0l1qffYcQqueTyHhGur-SMyKZZyZHrDQR-BvaBRhdhjQU57r_1sJIlywDEK56OkhroP5mmRYICiUAKdMzRz-ZlkAB-5hyle64cXgQJClZx5NV-0PDhPbWl4GAItnmGnjdfGsqW3gAE3i4tKypImFvYx9Q4XLSBTPoivR6zKJpxbjwbxTT4T2c7qZWw55WOT63-mXYNp45WG9INzIAyI7f8K2edfDXl6UL_HAuTaR39t232D0ifHaN8X85YEzrVgZqEBNeeOySrQQD4qlxTMvCiCKI1T-A";

    //      $headers = array();
    //      $headers[] = 'Cache-Control: no-cache';
    //      $headers[] = 'Authorization: ' . $token;
    //      $headers[] = 'Content-Type: application/json; charset= utf-8';

    //      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    //      $result = curl_exec($ch);
    //      $datos = json_decode($result, true);
    //      if (isset($datos['data'])) {
    //          $pagination = $datos['paginator'];
    //          $datos = $datos['data'];

    //      } else {
    //          $datos = null;
    //      }

    //      if ($datos != null) {
    //          $cant_datos = count($datos);
    //          for ($i = 0; $i < $cant_datos; $i++) {
    //              $buscoConfig = Configuracion::find()->where(['tipo_campo' => 3])->one();
    //              $procesadas = $buscoConfig['valor_texto'];

    //              if ($procesadas != null) {
    //                  $parsea_pro = explode("|", $procesadas);

    //                  $clave = array_search($datos[$i]['code'], $parsea_pro);

    //                  if ($clave === false) {
    //                      $model = new Reservas();
    //                      $clientes = new Clientes();
    //                      $coches = new Coches();

    //                      $length = 6;
    //                      $characters = '0123456789';
    //                      $charactersLength = strlen($characters);
    //                      $proxima_reserva = '';
    //                      for ($k = 0; $k < $length; $k++) {
    //                          $proxima_reserva .= $characters[rand(0, $charactersLength - 1)];
    //                      }

    //                      $buscarReserva = Reservas::find()->where(['nro_reserva' => $proxima_reserva])->one();

    //                      if ($buscarReserva != null) {
    //                          $length = 6;
    //                          $characters = '0123456789';
    //                          $charactersLength = strlen($characters);
    //                          $proxima_reserva = '';
    //                          for ($k = 0; $k < $length; $k++) {
    //                              $proxima_reserva .= $characters[rand(0, $charactersLength - 1)];
    //                          }
    //                      }

    //                      $medio = 2;
    //                      $agencia = 'AG 01';

    //                      $fecha_creacion = date('Y-m-d H:i:s');

    //                      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //                      $charactersLength = strlen($characters);
    //                      $randomString = '';
    //                      for ($j = 0; $j < 10; $j++) {
    //                          $randomString .= $characters[rand(0, $charactersLength - 1)];
    //                      }

    //                      $clientes->nombre_completo = $datos[$i]['name'];
    //                      $clientes->movil = $datos[$i]['phone'];
    //                      $clientes->tipo_documento = 'S/N';
    //                      $clientes->nro_documento = 'S/N';
    //                      $clientes->correo = $randomString . '@parkingplus.es';
    //                      $clientes->estatus = 1;
    //                      $clientes->created_at = $fecha_creacion;
    //                      $clientes->created_by = $id_usuario;
    //                      $clientes->save();

    //                      $id_cliente = Clientes::find()->max('id');

    //                      $coches->id_cliente = $id_cliente;
    //                      $coches->marca = $datos[$i]['car_brand_model'];
    //                      $coches->matricula = $datos[$i]['car_license_plate'];
    //                      $coches->estatus_coche = 1;
    //                      $coches->created_at = $fecha_creacion;
    //                      $coches->created_by = $id_usuario;
    //                      $coches->save();

    //                      $id_coche = Coches::find()->max('id');

    //                      $reserva_servicio = new ReservasServicios();
    //                      $reserva_servicio->id_reserva = $proxima_reserva;
    //                      $reserva_servicio->id_servicio = 6;
    //                      $reserva_servicio->cantidad = 1;
    //                      $reserva_servicio->precio_unitario = 0;
    //                      $reserva_servicio->precio_total = 0;
    //                      $reserva_servicio->tipo_servicio = 0;
    //                      $reserva_servicio->save();

    //                      $servicios = $datos[$i]['products'];

    //                      if ($servicios) {
    //                          foreach ($servicios as $ser) {
    //                              if ($ser['id'] == 11662) {
    //                                  $modelR = new ReservasServicios();
    //                                  $modelR->id_reserva = $proxima_reserva;
    //                                  $modelR->id_servicio = 8;
    //                                  $modelR->cantidad = 1;
    //                                  $modelR->precio_unitario = 0;
    //                                  $modelR->precio_total = 0;
    //                                  $modelR->tipo_servicio = 0;
    //                                  $modelR->save();
    //                              }

    //                              if ($ser['id'] == 11663) {
    //                                  $modelR = new ReservasServicios();
    //                                  $modelR->id_reserva = $proxima_reserva;
    //                                  $modelR->id_servicio = 1;
    //                                  $modelR->cantidad = 1;
    //                                  $modelR->precio_unitario = 0;
    //                                  $modelR->precio_total = 0;
    //                                  $modelR->tipo_servicio = 0;
    //                                  $modelR->save();
    //                              }

    //                              if ($ser['id'] == 11664) {
    //                                  $modelR = new ReservasServicios();
    //                                  $modelR->id_reserva = $proxima_reserva;
    //                                  $modelR->id_servicio = 2;
    //                                  $modelR->cantidad = 1;
    //                                  $modelR->precio_unitario = 0;
    //                                  $modelR->precio_total = 0;
    //                                  $modelR->tipo_servicio = 0;
    //                                  $modelR->save();
    //                              }
    //                          }
    //                      }

    //                      $model->nro_reserva = $proxima_reserva;
    //                      $model->fecha_entrada = $datos[$i]['arrival_date'];
    //                      $model->fecha_salida = $datos[$i]['departure_date'];
    //                      $model->hora_entrada = $datos[$i]['arrival_time'];
    //                      $model->hora_salida = $datos[$i]['departure_time'];
    //                      $model->id_cliente = $id_cliente;
    //                      $model->id_coche = $id_coche;
    //                      $model->monto_factura = 0;
    //                      $model->costo_servicios = 0;
    //                      $model->monto_total = 0;
    //                      $model->id_tipo_pago = 6;
    //                      $model->costo_servicios_extra = 0;
    //                      $model->monto_impuestos = 0;
    //                      $model->condiciones = 1;
    //                      $model->medio_reserva = $medio;
    //                      $model->agencia = $agencia;
    //                      $model->estatus = 1;
    //                      $model->created_at = $fecha_creacion;
    //                      $model->created_by = $id_usuario;
    //                      $model->save();

    //                      $buscoConfig['valor_texto'] = $procesadas . "|" . $datos[$i]['code'];
    //                      $buscoConfig->save();
    //                  }
    //              } else {

    //                  $model = new Reservas();
    //                  $clientes = new Clientes();
    //                  $coches = new Coches();

    //                  $length = 6;
    //                  $characters = '0123456789';
    //                  $charactersLength = strlen($characters);
    //                  $proxima_reserva = '';
    //                  for ($k = 0; $k < $length; $k++) {
    //                      $proxima_reserva .= $characters[rand(0, $charactersLength - 1)];
    //                  }

    //                  $buscarReserva = Reservas::find()->where(['nro_reserva' => $proxima_reserva])->one();

    //                  if ($buscarReserva != null) {
    //                      $length = 6;
    //                      $characters = '0123456789';
    //                      $charactersLength = strlen($characters);
    //                      $proxima_reserva = '';
    //                      for ($k = 0; $k < $length; $k++) {
    //                          $proxima_reserva .= $characters[rand(0, $charactersLength - 1)];
    //                      }
    //                  }

    //                  $medio = 2;
    //                  $agencia = 'AG 01';

    //                  $fecha_creacion = date('Y-m-d H:i:s');

    //                  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //                  $charactersLength = strlen($characters);
    //                  $randomString = '';
    //                  for ($j = 0; $j < 10; $j++) {
    //                      $randomString .= $characters[rand(0, $charactersLength - 1)];
    //                  }

    //                  $clientes->nombre_completo = $datos[$i]['name'];
    //                  $clientes->movil = $datos[$i]['phone'];
    //                  $clientes->tipo_documento = 'S/N';
    //                  $clientes->nro_documento = 'S/N';
    //                  $clientes->correo = $randomString . '@parkingplus.es';
    //                  $clientes->estatus = 1;
    //                  $clientes->created_at = $fecha_creacion;
    //                  $clientes->created_by = $id_usuario;
    //                  $clientes->save();

    //                  $id_cliente = Clientes::find()->max('id');

    //                  $coches->marca = $datos[$i]['car_brand_model'];
    //                  $coches->matricula = $datos[$i]['car_license_plate'];
    //                  $coches->estatus_coche = 1;
    //                  $coches->created_at = $fecha_creacion;
    //                  $coches->created_by = $id_usuario;
    //                  $coches->save();

    //                  $id_coche = Coches::find()->max('id');

    //                  $reserva_servicio = new ReservasServicios();
    //                  $reserva_servicio->id_reserva = $proxima_reserva;
    //                  $reserva_servicio->id_servicio = 6;
    //                  $reserva_servicio->cantidad = 1;
    //                  $reserva_servicio->precio_unitario = 0;
    //                  $reserva_servicio->precio_total = 0;
    //                  $reserva_servicio->tipo_servicio = 0;
    //                  $reserva_servicio->save();

    //                  $servicios = $datos[$i]['products'];

    //                  if ($servicios) {
    //                      foreach ($servicios as $ser) {
    //                          if ($ser['id'] == 11662) {
    //                              $modelR = new ReservasServicios();
    //                              $modelR->id_reserva = $proxima_reserva;
    //                              $modelR->id_servicio = 8;
    //                              $modelR->cantidad = 1;
    //                              $modelR->precio_unitario = 0;
    //                              $modelR->precio_total = 0;
    //                              $modelR->tipo_servicio = 0;
    //                              $modelR->save();
    //                          }

    //                          if ($ser['id'] == 11663) {
    //                              $modelR = new ReservasServicios();
    //                              $modelR->id_reserva = $proxima_reserva;
    //                              $modelR->id_servicio = 1;
    //                              $modelR->cantidad = 1;
    //                              $modelR->precio_unitario = 0;
    //                              $modelR->precio_total = 0;
    //                              $modelR->tipo_servicio = 0;
    //                              $modelR->save();
    //                          }

    //                          if ($ser['id'] == 11664) {
    //                              $modelR = new ReservasServicios();
    //                              $modelR->id_reserva = $proxima_reserva;
    //                              $modelR->id_servicio = 2;
    //                              $modelR->cantidad = 1;
    //                              $modelR->precio_unitario = 0;
    //                              $modelR->precio_total = 0;
    //                              $modelR->tipo_servicio = 0;
    //                              $modelR->save();
    //                          }
    //                      }
    //                  }
    //                  $model->nro_reserva = $proxima_reserva;
    //                  $model->fecha_entrada = $datos[$i]['arrival_date'];
    //                  $model->fecha_salida = $datos[$i]['departure_date'];
    //                  $model->hora_entrada = $datos[$i]['arrival_time'];
    //                  $model->hora_salida = $datos[$i]['departure_time'];
    //                  $model->id_cliente = $id_cliente;
    //                  $model->id_coche = $id_coche;
    //                  $model->monto_factura = 0;
    //                  $model->costo_servicios = 0;
    //                  $model->monto_total = 0;
    //                  $model->id_tipo_pago = 6;
    //                  $model->costo_servicios_extra = 0;
    //                  $model->monto_impuestos = 0;
    //                  $model->condiciones = 1;
    //                  $model->medio_reserva = $medio;
    //                  $model->agencia = $agencia;
    //                  $model->estatus = 1;
    //                  $model->created_at = $fecha_creacion;
    //                  $model->created_by = $id_usuario;
    //                  $model->save();

    //                  $buscoConfig['valor_texto'] = $datos[$i]['code'];
    //                  $buscoConfig->save();
    //              }
    //          }
    //      }*/

    //     $buscarAfiliado = UserAfiliados::find()->where(['user_id' => $id_usuario])->one();
    //     if (!empty($buscarAfiliado)) {
    //         $tipo_afiliado = $buscarAfiliado['tipo_afiliado'];
    //     } else {
    //         $tipo_afiliado = 0;
    //     }
    //     $ayo = date('Y');

    //     //->where(['between', 'created_at', date("d-m-Y", strtotime(date('Y-m-d') . "- 3 month")), date('Y-m-d')])->all();
    //     $reservas = Reservas::find()->where(['between', 'created_at', date("Y-m-d", strtotime(date('Y-m-d') . "- 3 month")), date('Y-m-d')])->orderBy(['created_at' => SORT_DESC])->all();

    //     foreach ($reservas as $res) {
    //         $fecha_creacion = $res->created_at;
    //         $ayoReserva = date('Y', strtotime($fecha_creacion));
    //         if ($ayoReserva == $ayo) {
    //             $fecha = date('Y-m-d');
    //             $fecha_in = $res->fecha_entrada;
    //             $fecha_out = $res->fecha_salida;

    //             if ($res->estatus != '0') {
    //                 if (($fecha > $fecha_out) && ($res->estatus != 4)) {
    //                     $res->estatus = '2';
    //                     $res->save();
    //                 }
    //                 if (($fecha > $fecha_in) && ($fecha < $fecha_out) && ($res->estatus != 4)) {
    //                     $res->estatus = '3';
    //                     $res->save();
    //                 }
    //             }
    //         }
    //     } // FIN FOREACH


    //     $query = new Query();
    //     $query->select(['DISTINCT(year(fecha_entrada)) as year'])->from('reservas');

    //     $command = $query->createCommand();
    //     $anios = $command->queryAll();

    //     $anos = [];
    //     foreach ($anios as $anio) {
    //         $anos[$anio['year']] = $anio['year'];
    //     }

    //     $searchModel = new ReservasSearch();
    //     $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    //     $dataProvider->pagination->pageSize = 15;


    //     // ER 26-06-25 ENCONTRAR ERRORES EN SERVICIO EXTRAS CONTRATADOS QUE NO SE MUESTRAN EN LAS RESERVAS
    //     $connection = Yii::$app->getDb();

    //     $sqlErrores = "
    //         SELECT 
    //           r.id, 
    //           r.nro_reserva, 
    //           r.costo_servicios_extra, 
    //           r.created_at,
    //           r.fecha_entrada,
    //           r.fecha_salida,
    //           COUNT(rs.id) AS total_servicios
    //         FROM reservas r
    //         LEFT JOIN reservas_servicios rs ON r.nro_reserva = rs.id_reserva
    //         WHERE r.costo_servicios_extra > 0
    //           AND r.created_at >= NOW() - INTERVAL 30 DAY
    //         GROUP BY 
    //           r.id, 
    //           r.nro_reserva, 
    //           r.costo_servicios_extra, 
    //           r.created_at,
    //           r.fecha_entrada,
    //           r.fecha_salida
    //         HAVING COUNT(rs.id) < 3
    //         ORDER BY r.fecha_salida DESC
    //     ";

    //     $reservasConErrores = $connection->createCommand($sqlErrores)->queryAll();
    //     // END



    //     return $this->render('index', [
    //         'searchModel' => $searchModel,
    //         'dataProvider' => $dataProvider,
    //         'tipo_afiliado' => $tipo_afiliado,
    //         'anios' => $anos,
    //         'reservasConErrores' => $reservasConErrores,
    //     ]);
    // }

    //     public function actionDebugUpdateStatus()
    // {
    //     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    //     // Obtener reservas problemáticas
    //     $reservas = Reservas::find()
    //         ->where(['<', 'fecha_salida', date('Y-m-d')])
    //         ->andWhere(['NOT IN', 'estatus', ['0', '2', '4']])
    //         ->all();

    //     $result = [
    //         'total' => count($reservas),
    //         'updated' => 0,
    //         'notUpdated' => 0,
    //         'problematicReservations' => []
    //     ];

    //     foreach ($reservas as $reserva) {
    //         $shouldBeStatus = '2'; // Por defecto debería ser finalizada (2)

    //         if ($reserva->estatus != $shouldBeStatus) {
    //             $result['notUpdated']++;
    //             $result['problematicReservations'][] = [
    //                 'id' => $reserva->id,
    //                 'nro_reserva' => $reserva->nro_reserva,
    //                 'estatus' => $reserva->estatus,
    //                 'fecha_salida' => $reserva->fecha_salida,
    //                 'error' => $reserva->getErrors() ? json_encode($reserva->getErrors()) : 'Sin error registrado'
    //             ];
    //         } else {
    //             $result['updated']++;
    //         }
    //     }

    //     return $result;
    // }

    /**
     * Lists all Planning Reservas models.
     * @return mixed
     */
    public function actionPlanning()
    {

        $session = Yii::$app->session;
        $session->open();
        $session['query_params'] = json_encode(Yii::$app->request->queryParams);
        $session->close();

        $searchModel = new ReservasSearch();
        $searchModel->fecha_busca = date('d-m-Y');

        if ($searchModel->load(Yii::$app->request->get())) {
            //echo "<pre>"; var_dump($searchModel); echo "</pre>"; die();

            $fecha = $searchModel->fecha_busca;
            $fecha = date('Y-m-d', strtotime($fecha));

            $dataProvider = $searchModel->searchPlanning(Yii::$app->request->queryParams, $fecha);
            $dataProvider1 = $searchModel->searchP(Yii::$app->request->queryParams, $fecha);
            $dataProvider2 = $searchModel->searchP(Yii::$app->request->queryParams, $fecha);
            $dataProvider3 = $searchModel->searchPlanning(Yii::$app->request->queryParams, $fecha);

            $dataProvider->pagination->pageSize = 10;
            $dataProvider1->pagination->pageSize = 10;
            $dataProvider2->pagination->pageSize = 100;
            $dataProvider3->pagination->pageSize = 100;
        } else {

            $fecha = '';
            $dataProvider = $searchModel->searchPlanning(Yii::$app->request->queryParams, $fecha);
            $dataProvider1 = $searchModel->searchP(Yii::$app->request->queryParams, $fecha);
            $dataProvider2 = $searchModel->searchP(Yii::$app->request->queryParams, $fecha);
            $dataProvider3 = $searchModel->searchPlanning(Yii::$app->request->queryParams, $fecha);

            $dataProvider->pagination->pageSize = 10;
            $dataProvider1->pagination->pageSize = 10;
            $dataProvider2->pagination->pageSize = 100;
            $dataProvider3->pagination->pageSize = 100;
        }

        return $this->render('planning', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProvider1' => $dataProvider1,
            'dataProvider2' => $dataProvider2,
            'dataProvider3' => $dataProvider3,
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
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
            'id' => $id,
        ]);
    }



    public function actionTotal()
    {

        if (Yii::$app->request->post()) {

            $id_usuario = Yii::$app->user->id;

            $buscarAfiliado = UserAfiliados::find()->where(['user_id' => $id_usuario])->one();
            if (!empty($buscarAfiliado)) {
                $tipo_afiliado = $buscarAfiliado['tipo_afiliado'];
            } else {
                $tipo_afiliado = 0;
            }

            $mes = $_POST['mes'];
            $ayo = $_POST['ayo'];

            if ($tipo_afiliado == 0) {
                $reservas = Reservas::find()->all();
            } else {
                $reservas = Reservas::find()->where(['medio_reserva' => 4])->all();
            }

            $cuenta = 0;
            $pendientes = 0;
            $activas = 0;
            $rezagadas = 0;
            $finalizadas = 0;
            $canceladas = 0;
            foreach ($reservas as $res) {
                $fecha = $res->fecha_entrada;
                $mesfecha = date('m', strtotime($fecha));
                $ayofecha = date('Y', strtotime($fecha));

                if (($mesfecha == $mes) && ($ayofecha == $ayo)) {
                    $cuenta = $cuenta + 1;
                    if ($res->estatus == 0) {
                        $canceladas = $canceladas + 1;
                    }
                    if ($res->estatus == 1) {
                        $pendientes = $pendientes + 1;
                    }
                    if ($res->estatus == 2) {
                        $finalizadas = $finalizadas + 1;
                    }
                    if ($res->estatus == 3) {
                        $activas = $activas + 1;
                    }
                    if ($res->estatus == 4) {
                        $rezagadas = $rezagadas + 1;
                    }
                }
            }
            return ($cuenta . '/' . $pendientes . '/' . $activas . '/' . $rezagadas . '/' . $finalizadas . '/' . $canceladas);
        }
    }

    public function actionOpinion()
    {

        if (Yii::$app->request->post()) {
            $idC = $_POST['id_cliente'];

            $reservas = Reservas::find()->where(['id_cliente' => $idC])->all();
            for ($i = 0; $i < count($reservas); $i++) {
                $datos_reserva[$i] = array('id' => $reservas[$i]['nro_reserva'], 'reserva' => $reservas[$i]['nro_reserva']);
            }

            $listaR = json_encode($datos_reserva);

            $datos = Clientes::find()->where(['id' => $idC])->one();
            $correo = $datos->correo;

            return ($correo . '/' . $listaR);
        }
    }

    public function actionCheck()
    {

        if (Yii::$app->request->post()) {
            $idReserva = $_POST['id_reserva'];
            $datosReserva = Reservas::findOne($idReserva);
            $id_cliente = $datosReserva->id_cliente;
            $datos = Clientes::findOne($id_cliente);
            $nombre = $datos->nombre_completo;
            $correo = $datos->correo;
            return ($nombre . '/' . $correo . '/' . $id_cliente);
        }
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

    public function actionAgencia()
    {

        $entrada = $_GET['entrada'];
        $salida = $_GET['salida'];

        $hora_e = $_GET['hora_e'];
        $hora_s = $_GET['hora_s'];

        $medio = $_GET['medio'];
        $agencia = $_GET['agencia'];

        $model = new Reservas();

        // $length = 8;
        // $characters = '0123456789';
        // $charactersLength = strlen($characters);
        // $proxima_reserva = '';
        // for ($i = 0; $i < $length; $i++) {
        //     $proxima_reserva .= $characters[rand(0, $charactersLength - 1)];
        // }

        // $buscarReserva = Reservas::find()->where(['nro_reserva' => $proxima_reserva])->one();

        // if ($buscarReserva != null) {
        //     $length = 6;
        //     $characters = '0123456789';
        //     $charactersLength = strlen($characters);
        //     $proxima_reserva = '';
        //     for ($i = 0; $i < $length; $i++) {
        //         $proxima_reserva .= $characters[rand(0, $charactersLength - 1)];
        //     }
        // }

        $proxima_reserva = substr(strtotime(date('Y-m-d H:i:s')), 2, 10);

        $buscarReserva = Reservas::find()->where(['nro_reserva' => $proxima_reserva])->one();

        if ($buscarReserva != null) {
            $proxima_reserva = substr(strtotime(date('Y-m-d H:i:s')), 2, 10);
        }

        $clientes = new Clientes();

        $coches = new Coches();

        $terminales = [
            'TERMINAL 1' => 'TERMINAL 1',
            'TERMINAL 2' => 'TERMINAL 2',
            'TERMINAL 3' => 'TERMINAL 3',
            'TERMINAL 4' => 'TERMINAL 4',
            'AUN NO CONOZCO LA TERMINAL' => 'AUN NO CONOZCO LA TERMINAL'
        ];

        $servicios = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '2'])->all();

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

        $pagos = TipoPago::find()->where(['estatus' => '1'])->all();
        $tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion');

        $impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
        foreach ($impuestos as $imp) {
            $tipo_imp = $imp->tipo_impuesto;
            if ($tipo_imp == 1) {
                $iva = $imp->valor_numerico;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $clientes->load(Yii::$app->request->post()) && $coches->load(Yii::$app->request->post())) {


            $medio = $_POST['medio_reserva'];
            $agencia = $_POST['agencia'];

            $id_usuario = Yii::$app->user->id;
            $fecha_creacion = date('Y-m-d H:i:s');

            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 10; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $clientes->tipo_documento = 'S/N';
            $clientes->nro_documento = 'S/N';
            $clientes->correo = $randomString . '@parkingplus.es';
            $clientes->estatus = 1;
            $clientes->created_at = $fecha_creacion;
            $clientes->created_by = $id_usuario;
            $clientes->save();

            $id_cliente = Clientes::find()->max('id');
            $coches->id_cliente = $id_cliente;
            $coches->estatus_coche = 1;
            $coches->created_at = $fecha_creacion;
            $coches->created_by = $id_usuario;
            $coches->save();

            $reserva_servicio = new ReservasServicios();
            $reserva_servicio->id_reserva = $model->nro_reserva;
            $reserva_servicio->id_servicio = 6;
            $reserva_servicio->cantidad = 1;
            $reserva_servicio->precio_unitario = $model->costo_servicios;
            $reserva_servicio->precio_total = $model->costo_servicios;
            $reserva_servicio->tipo_servicio = 0;
            $reserva_servicio->save();

            foreach ($servicios as $ser) {
                $modelR = new ReservasServicios();
                $precio_unitario = $_POST['precio_unitario' . $ser->id];
                $cantidad = $_POST['cantidad' . $ser->id];
                $precio_total = $_POST['precio_total' . $ser->id];
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

            $id_coche = Coches::find()->max('id');
            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));
            $model->id_cliente = $id_cliente;
            $model->id_coche = $id_coche;
            $monto_factura = $model->costo_servicios / $iva;
            $model->monto_factura = round($monto_factura, 2);
            //$model->costo_servicios_extra = 0;
            $model->monto_impuestos = round(($model->costo_servicios - $monto_factura), 2);
            $model->condiciones = 1;
            $model->medio_reserva = $medio;
            $model->agencia = $agencia;
            $model->estatus = 1;
            $model->created_at = $fecha_creacion;
            $model->created_by = $id_usuario;
            $model->save();

            Yii::$app->session->setFlash('success', 'La Reserva se ha generado de manera exitosa.');
            return $this->redirect(['reservas/index']);
        }

        return $this->render('agencia', [
            'model' => $model,
            'proxima_reserva' => $proxima_reserva,
            'clientes' => $clientes,
            'coches' => $coches,
            'terminales' => $terminales,
            'servicios' => $servicios,
            'precio_diario' => $precio_diario,
            'tipos_pago' => $tipos_pago,
            'iva' => $iva,
            'entrada' => $entrada,
            'salida' => $salida,
            'hora_e' => $hora_e,
            'hora_s' => $hora_s,
            'medio' => $medio,
            'agencia' => $agencia,
        ]);
    }

    public function actionActualizar($id)
    {
        $model = $this->findModel($id);
        $entrada = date('d-m-Y', strtotime($model->fecha_entrada));
        $salida = date('d-m-Y', strtotime($model->fecha_salida));
        $hora_e = $model->hora_entrada;
        $hora_s = $model->hora_salida;
        $medio = $model->medio_reserva;
        $agencia = $model->agencia;
        $proxima_reserva = $model->nro_reserva;
        $clientes = Clientes::find()->where(['id' => $model->id_cliente])->one();
        $coches = Coches::find()->where(['id' => $model->id_coche])->one();

        $terminales = [
            'TERMINAL 1' => 'TERMINAL 1',
            'TERMINAL 2' => 'TERMINAL 2',
            'TERMINAL 3' => 'TERMINAL 3',
            'TERMINAL 4' => 'TERMINAL 4'
        ];

        $servicios = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '2'])->all();

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

        $pagos = TipoPago::find()->where(['estatus' => '1'])->all();
        $tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion');

        $impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
        foreach ($impuestos as $imp) {
            $tipo_imp = $imp->tipo_impuesto;
            if ($tipo_imp == 1) {
                $iva = $imp->valor_numerico;
            }
        }

        if ($model->load(Yii::$app->request->post()) && $clientes->load(Yii::$app->request->post()) && $coches->load(Yii::$app->request->post())) {

            $medio = $_POST['medio_reserva'];
            $agencia = $_POST['agencia'];

            $id_usuario = Yii::$app->user->id;
            $fecha_update = date('Y-m-d H:i:s');

            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 10; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $clientes->tipo_documento = 'S/N';
            $clientes->nro_documento = 'S/N';
            //$clientes->correo = $randomString.'@parkingplus.es';
            $clientes->estatus = 1;
            $clientes->updated_at = $fecha_update;
            $clientes->updated_by = $id_usuario;
            $clientes->save();

            //$id_cliente = Clientes::find()->max('id');
            $coches->id_cliente = $clientes->id;
            $coches->estatus_coche = 1;
            $coches->updated_at = $fecha_update;
            $coches->updated_by = $id_usuario;
            $coches->save();
            //echo "<pre>"; var_dump($servicios); echo "</pre>"; die();

            foreach ($servicios as $ser) {
                $id_servicio = $ser->id;
                $precio_unitario = $_POST['precio_unitario' . $ser->id];
                $cantidad = $_POST['cantidad' . $ser->id];
                $precio_total = $_POST['precio_total' . $ser->id];
                $tipo_servicio = $_POST['tipo_servicio' . $ser->id];

                $reserva_servicio = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])->andWhere(['id_servicio' => $id_servicio])->one();

                if ($reserva_servicio == null) {
                    if ($cantidad != 0) {
                        $modelR = new ReservasServicios();
                        $modelR->id_reserva = $model->nro_reserva;
                        $modelR->id_servicio = $ser->id;
                        $modelR->cantidad = $cantidad;
                        $modelR->precio_unitario = $precio_total;
                        $modelR->precio_total = $precio_total;
                        $modelR->tipo_servicio = $tipo_servicio;
                        $modelR->save();
                    }
                } else {

                    if ($cantidad != 0) {
                        $reserva_servicio->precio_unitario = $precio_total;
                        $reserva_servicio->precio_total = $precio_total;
                        $reserva_servicio->tipo_servicio = $tipo_servicio;
                        $reserva_servicio->save();
                    } else {
                        $reserva_servicio->delete();
                    }
                }
            }

            //$id_coche = Coches::find()->max('id');
            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));
            //$model->id_cliente = $clientes->id;
            //$model->id_coche = $coches->id;
            $monto_factura = $model->costo_servicios / $iva;
            $model->monto_factura = round($monto_factura, 2);
            $model->costo_servicios_extra = 0;
            $model->monto_impuestos = round(($model->costo_servicios - $monto_factura), 2);
            $model->condiciones = 1;
            $model->medio_reserva = $medio;
            $model->agencia = $agencia;
            $model->estatus = 1;
            $model->updated_at = $fecha_update;
            $model->updated_by = $id_usuario;

            $model->save();

            Yii::$app->session->setFlash('success', 'La Reserva ha sido modificada de manera exitosa.');
            return $this->redirect(['reservas/index']);

            //echo "<pre>"; var_dump($model); echo "</pre>"; die();
        }

        return $this->render('agencia', [
            'model' => $model,
            'proxima_reserva' => $proxima_reserva,
            'clientes' => $clientes,
            'coches' => $coches,
            'terminales' => $terminales,
            'servicios' => $servicios,
            'precio_diario' => $precio_diario,
            'tipos_pago' => $tipos_pago,
            'iva' => $iva,
            'entrada' => $entrada,
            'salida' => $salida,
            'hora_e' => $hora_e,
            'hora_s' => $hora_s,
            'medio' => $medio,
            'agencia' => $agencia,
        ]);
    }


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

    /**
     * Creates a new Reservas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        $entrada = $_GET['entrada'];
        $salida = $_GET['salida'];

        $hora_e = $_GET['hora_e'];
        $hora_s = $_GET['hora_s'];


        /*$extraNocturno = Servicios::find()->where(['id' => '11'])->all();
         $extraNocturno[0]['id'] = $extraNocturno[0]['id'] .'-'.$this->serviceNocturno($hora_e, $hora_s);*/

        $fecha_entrada = strtotime($entrada . ' ' . $hora_e);
        $fecha_salida = strtotime($salida . ' ' . $hora_s);

        $fhne = strtotime($entrada . ' 00:30');
        $fhnes = strtotime($entrada . ' 03:45');

        $fhns = strtotime($salida . ' 00:30');
        $fhnss = strtotime($salida . ' 03:45');

        $extraNocturno = Servicios::find()->where(['id' => '11'])->all();

        $extraNocturno[0]['id'] .= (($fecha_entrada >= $fhne && $fecha_entrada <= $fhnes) || ($fecha_salida >= $fhns && $fecha_salida <= $fhnss)) ? '-1' : '-0';


        $medio = $_GET['medio'];
        $agencia = $_GET['agencia'];

        $model = new Reservas();

        $model->cod_valid = $this->Obtener_token(48);

        // $length = 8;
        // $characters = '0123456789';
        // $charactersLength = strlen($characters);
        // $proxima_reserva = '';
        // for ($i = 0; $i < $length; $i++) {
        //     $proxima_reserva .= $characters[rand(0, $charactersLength - 1)];
        // }

        // $buscarReserva = Reservas::find()->where(['nro_reserva' => $proxima_reserva])->one();

        // if ($buscarReserva != null) {
        //     $length = 8;
        //     $characters = '0123456789';
        //     $charactersLength = strlen($characters);
        //     $proxima_reserva = '';
        //     for ($i = 0; $i < $length; $i++) {
        //         $proxima_reserva .= $characters[rand(0, $charactersLength - 1)];
        //     }
        // }
        $proxima_reserva = substr(strtotime(date('Y-m-d H:i:s')), 2, 10);

        $buscarReserva = Reservas::find()->where(['nro_reserva' => $proxima_reserva])->one();

        if ($buscarReserva != null) {
            $proxima_reserva = substr(strtotime(date('Y-m-d H:i:s')), 2, 10);
        }

        $clientes = new Clientes();

        $coches = new Coches();

        $terminales = [
            'TERMINAL 1' => 'TERMINAL 1',
            'TERMINAL 2' => 'TERMINAL 2',
            'TERMINAL 3' => 'TERMINAL 3',
            'TERMINAL 4' => 'TERMINAL 4'
        ];


        $servicios = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '2'])->all();

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

        /*$horaInicio = new DateTime($entrada.' '.$hora_e);
        $horaTermino = new DateTime( $salida.' '.$hora_s);

        $interval = $horaInicio->diff($horaTermino);

       if($interval->d > 0 && ($interval->h > 0 || $interval->i > 0)){
           $cant_dias = $interval->d + 1;
       }else{
           $cant_dias = $interval->d == 0 ? 1 : $interval->d;
       }*/

        /*$position = null;
        foreach ($precio_diario as $key => $data) {
            if ($data['cantidad'] == $cant_dias) {
                $position = $key;
            }
        }

        foreach ($precioTemporada as $temporada) {
            if (strtotime($entrada . ' ' . $hora_e) >= strtotime($temporada->fecha_inicio . ' ' . $temporada->hora_inicio) && strtotime($entrada . ' ' . $hora_e) <= strtotime($temporada->fecha_fin . ' ' . $temporada->hora_fin)) {
                $precio_diario[$position]['precio'] = $precio_diario[$position]['costo'] + ($cant_dias * $temporada->precio);
            }
        }*/

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

        $precio_dia = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '0'])->one();


        if ($model->load(Yii::$app->request->post()) && $clientes->load(Yii::$app->request->post()) && $coches->load(Yii::$app->request->post())) {


            $medio = $_POST['medio_reserva'];
            $agencia = $_POST['agencia'];


            $id_usuario = Yii::$app->user->id;
            $fecha_creacion = date('Y-m-d H:i:s');

            $num_reserva = $model->nro_reserva;

            $client = Clientes::find()->where(['movil' => $clientes->movil])->one();

            if ($client == null) {
                $clientes->estatus = 1;
                $clientes->created_at = $fecha_creacion;
                $clientes->created_by = $id_usuario;
                $clientes->updated_at = $fecha_creacion;
                $clientes->updated_by = $id_usuario;
                $clientes->save();
                $id_cliente = Clientes::find()->max('id');
            } else {

                if ($client->correo != $clientes->correo) {
                }
                $id_cliente = $client->id;
            }
            /*$clientes->estatus = 1;
            $clientes->created_at = $fecha_creacion;
            $clientes->created_by = $id_usuario;
            $clientes->updated_at = $fecha_creacion;
            $clientes->updated_by = $id_usuario;
            $clientes->save();*/


            $coche = Coches::find()->where(['matricula' => $coches->matricula])->one();

            if ($coche == null) {
                $coches->id_cliente = $id_cliente;
                $coches->estatus_coche = 1;
                $coches->created_at = $fecha_creacion;
                $coches->created_by = $id_usuario;
                $coches->updated_at = $fecha_creacion;
                $coches->updated_by = $id_usuario;
                $coches->save();
                $id_coche = Coches::find()->max('id');
            } else {
                $id_coche = $coche->id;
            }

            $precio_diario = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '0'])->all();

            $seguro = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '1'])->all();

            if ($medio != 2) {
                foreach ($servicios as $ser) {
                    $modelR = new ReservasServicios();
                    $precio_unitario = $_POST['precio_unitario' . $ser->id];
                    $cantidad = $_POST['cantidad' . $ser->id];
                    $precio_total = $_POST['precio_total' . $ser->id];
                    $tipo_servicio = $_POST['tipo_servicio' . $ser->id];

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

                if ($precio_diario[0]->fijo == 0) {
                    $modelR = new ReservasServicios();
                    $modelR->id_reserva = $num_reserva;
                    $modelR->id_servicio = $precio_diario[0]->id;
                    $modelR->cantidad = $_POST['cant_basico'];
                    $modelR->precio_unitario = $precio_diario[0]->costo;
                    $modelR->precio_total = $model->costo_servicios;
                    $modelR->tipo_servicio = 0;
                    $modelR->save();
                }

                if ($seguro[0]->fijo == 1) {
                    $modelR = new ReservasServicios();
                    $modelR->id_reserva = $num_reserva;
                    $modelR->id_servicio = $seguro[0]->id;
                    $modelR->cantidad = $_POST['cant_seguro'];
                    $modelR->precio_unitario = $seguro[0]->costo;
                    $modelR->precio_total = $seguro[0]->costo;
                    $modelR->tipo_servicio = 1;
                    $modelR->save();
                }

                if ($model->cortesia == '1') {

                    if ($seguro[1]->fijo == 1) {
                        $modelR = new ReservasServicios();
                        $modelR->id_reserva = $num_reserva;
                        $modelR->id_servicio = $seguro[1]->id;
                        $modelR->cantidad = $_POST['cant_seguro'];
                        $modelR->precio_unitario = $seguro[1]->costo;
                        $modelR->precio_total = $seguro[1]->costo;
                        $modelR->tipo_servicio = 1;
                        $modelR->save();
                    }
                }

                if ($_POST['is_noc'] == '11-1') {
                    $modelR = new ReservasServicios();
                    $modelR->id_reserva = $num_reserva;
                    $modelR->id_servicio = $_POST['servicio_noc_id'];
                    $modelR->cantidad = 1;
                    $modelR->precio_unitario = $_POST['servicio_noc_costo'];
                    $modelR->precio_total = $_POST['servicio_noc_costo'];
                    $modelR->tipo_servicio = 2;
                    $modelR->save();
                }
            }
            $impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
            foreach ($impuestos as $imp) {
                $tipo_imp = $imp->tipo_impuesto;
                if ($tipo_imp == 1) {
                    $iva = $imp->valor_numerico;
                }
            }

            $fecha1 = $model->fecha_entrada;
            $model->fecha_entrada = date("Y-m-d", strtotime($fecha1));
            $fecha2 = $model->fecha_salida;
            $model->fecha_salida = date("Y-m-d", strtotime($fecha2));

            $model->estatus = 1;
            $model->medio_reserva = $medio;
            $model->condiciones = 1;

            $model->created_at = $fecha_creacion;
            $model->created_by = $id_usuario ? $id_usuario : 0;

            $model->updated_at = $fecha_creacion;
            $model->updated_by = $id_usuario ? $id_usuario : 0;


            $montonoiva = ($model->monto_total / $iva);
            $montoimp = $model->monto_total - $montonoiva;

            $model->monto_factura = round($montonoiva, 2);
            $model->monto_impuestos = round($montoimp, 2);

            $model->id_cliente = $id_cliente;
            $model->id_coche = $id_coche;

            $model->save();

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
                    'SetTitle' => Yii::$app->name . ' | Comprobante de Reserva',
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]);

            $pdf->render();

            $reserva = $model->nro_reserva;

            $buscarCorreo = Clientes::find()->where(['id' => $id_cliente])->one();
            $mail = $buscarCorreo->correo;

            if ($mail != null) {
                try {
                    $correo = Yii::$app->mailer->compose(
                        [
                            'html' => 'emailReserva2-html',
                            'text' => 'emailReserva-text'
                        ],
                        [
                            'nro_reserva' => $reserva,
                            'coche_matricula' => $coches->matricula,
                            'fecha_entrada' => $fecha1,
                            'hora_entrada' => $model->hora_entrada,
                            'fecha_salida' => $fecha2,
                            'hora_salida' => $model->hora_salida,
                            'token' => $model->cod_valid
                        ]
                    );
                    $correo->setTo($mail)
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
                            'nro_reserva' => $reserva,
                            'coche_matricula' => $coches->matricula,
                            'fecha_entrada' => $fecha1,
                            'hora_entrada' => $model->hora_entrada,
                            'fecha_salida' => $fecha2,
                            'hora_salida' => $model->hora_salida,
                            'token' => $model->cod_valid
                        ]
                    );

                    $correo2->setTo('asistenciaplus00@gmail.com')
                        ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                        ->setSubject('Reservación Parking Plus')
                        ->attach('../web/pdf/comprobante_' . $reserva . '.pdf')
                        ->send();
                } catch (\Exception $e) {
                    Yii::$app->session->setFlash('success', 'La Reserva se ha generado de manera exitosa. Por favor verifique que el correo del cliente exista.');
                    return $this->redirect(['reservas/index']);
                }
            }

            Yii::$app->session->setFlash('success', 'La Reserva se ha generado de manera exitosa.');
            return $this->redirect(['reservas/index']);
        }

        return $this->render('create', [
            'model' => $model,
            'proxima_reserva' => $proxima_reserva,
            'clientes' => $clientes,
            'coches' => $coches,
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
            'medio' => $medio,
            'agencia' => $agencia,
            'nocturno' => $extraNocturno,
            'precio_dia' => $precio_dia->valor_numerico
        ]);
    }


    /**
     * Updates an existing Reservas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);
        $medio = $model->medio_reserva;
        $agencia = $model->agencia;
        $proxima_reserva = $model->nro_reserva;
        $fecha_e = $model->fecha_entrada;
        $fecha_e = date("d-m-Y", strtotime($fecha_e));
        $hora_e = $model->hora_entrada;
        $hora_e = date("H:i", strtotime($hora_e));
        $fecha_s = $model->fecha_salida;
        $fecha_s = date("d-m-Y", strtotime($fecha_s));
        $hora_s = $model->hora_salida;
        $hora_s = date("H:i", strtotime($hora_s));
        $entrada = $fecha_e;
        $salida = $fecha_s;
        $descuento = 0;
        $porcentaje = $model->porcentaje_cupo;
        if ($porcentaje) {
            $porc = ($porcentaje / 100);
            $descuento = $model->costo_servicios * $porc;
            $costo_servicios = $model->costo_servicios - $descuento;
            $model->costo_servicios = round($costo_servicios, 2);
        }


        $fecha_entrada = strtotime($entrada . ' ' . $hora_e);
        $fecha_salida = strtotime($salida . ' ' . $hora_s);

        $fhne = strtotime($entrada . ' 00:30');
        $fhnes = strtotime($entrada . ' 03:45');

        $fhns = strtotime($salida . ' 00:30');
        $fhnss = strtotime($salida . ' 03:45');

        $extraNocturno = Servicios::find()->where(['id' => '11'])->all();

        $extraNocturno[0]['id'] .= (($fecha_entrada > $fhne && $fecha_entrada < $fhnes) || ($fecha_salida > $fhns && $fecha_salida < $fhnss)) ? '-1' : '-0';


        $clientes = Clientes::find()->where(['id' => $model->id_cliente])->one();

        $coches = Coches::find()->where(['id' => $model->id_coche])->one();

        $terminales = [
            'TERMINAL 1' => 'TERMINAL 1',
            'TERMINAL 2' => 'TERMINAL 2',
            'TERMINAL 3' => 'TERMINAL 3',
            'TERMINAL 4' => 'TERMINAL 4'
        ];

        $servicios = Servicios::find()->where(['estatus' => '1'])->andWhere(['fijo' => '2'])->all();
        foreach ($servicios as $serAll) {
            $ids_all[] = $serAll['id'];
        }

        $servicios_sel = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])->all();
        foreach ($servicios_sel as $serSel) {
            $fijo = $serSel->servicios['fijo'];
            if ($fijo == 2) {
                $ids_sel[] = $serSel['id_servicio'];
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
        $servicioTechado = Servicios::find()
            ->where(['estatus' => '1', 'fijo' => '1'])
            ->andWhere(['like', 'nombre_servicio', 'techado'])
            ->one();
        $idtechado = $servicioTechado ? $servicioTechado->id : null;
        if ($idtechado === null) {
            Yii::warning('Servicio "techado" no encontrado.', __METHOD__);
        }



        $seguro_sel = null;
        if ($idtechado !== null) {
            $seguro_sel = ReservasServicios::find()
                ->where(['id_reserva' => $model->nro_reserva])
                ->andWhere(['id_servicio' => $idtechado])
                ->one();
        }

        $sel_techado = 0;
        if ($seguro_sel != null) {
            $idtechado_sel = $seguro_sel->id_servicio;

            if ($idtechado == $idtechado_sel) {
                $sel_techado = 1;
            }
        }

        $precioTechado = $seguro_sel ? $seguro_sel->precio_total : 0;

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

        $pagos = TipoPago::find()->where(['estatus' => '1'])->all();
        $tipos_pago = ArrayHelper::map($pagos, 'id', 'descripcion');

        $impuestos = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '1'])->all();
        foreach ($impuestos as $imp) {
            $tipo_imp = $imp->tipo_impuesto;
            if ($tipo_imp == 1) {
                $iva = $imp->valor_numerico;
            }
        }

        $precio_dia = Configuracion::find()->where(['estatus' => '1'])->andWhere(['tipo_campo' => '0'])->one();


        $servicioP = ReservasServicios::find()
            ->where(['id_reserva' => $model->nro_reserva])
            ->andWhere(['tipo_servicio' => '0'])
            ->one();

        $precioTemporada = PrecioTemporada::find()->where(['status' => 'activo'])->one();


        if (!is_null($precioTemporada)) {
            foreach ($precio_diario as $key => $diario) {
                $precio_diario[$key]['precio'] = $precio_diario[$key]['costo'] + ($precio_diario[$key]['cantidad'] * $precioTemporada->precio);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $clientes->load(Yii::$app->request->post()) && $coches->load(Yii::$app->request->post())) {

            $fecha_e = $model->fecha_entrada;
            $fecha_e = date("Y-m-d", strtotime($fecha_e));
            $model->fecha_entrada = $fecha_e;

            $fecha_s = $model->fecha_salida;
            $fecha_s = date("Y-m-d", strtotime($fecha_s));
            $model->fecha_salida = $fecha_s;


            $montonoiva = ($model->monto_total / $iva);
            $montoimp = $model->monto_total - $montonoiva;

            $model->monto_factura = round($montonoiva, 2);
            $model->monto_impuestos = round($montoimp, 2);

            $clientes->save();

            $coche = Coches::find()->where(['matricula' => $coches->matricula])->one();

            if (is_null($coche)) {
                $cochesN = new Coches();
                $cochesN->id_cliente = $clientes->id;
                $cochesN->matricula = $coches->matricula;
                $cochesN->marca = $coches->marca;
                $cochesN->modelo = $coches->modelo;
                $cochesN->color = $coches->color;
                $cochesN->estatus_coche = 1;
                $cochesN->created_at = date('Y-m-d');
                $cochesN->created_by = Yii::$app->user->id;
                $cochesN->updated_at = date('Y-m-d');
                $cochesN->updated_by = Yii::$app->user->id;
                $cochesN->save();
                $model->id_coche = Coches::find()->max('id');
            } else {
                $model->id_coche = $coche->id;
            }


            $model->save();

            $sel_techado = $model->techado;

            if ($_POST["cambiar_costo_servicio"] == 1) {
                $servicioP = ReservasServicios::find()
                    ->where(['id_reserva' => $model->nro_reserva])
                    ->andWhere(['tipo_servicio' => '0'])
                    ->one();


                $day1 = $model->fecha_entrada . ' ' . $model->hora_entrada;
                $day1 = strtotime($day1);
                $day2 = $model->fecha_salida . ' ' . $model->hora_salida;
                $day2 = strtotime($day2);

                $diffHours = round(($day2 - $day1) / 3600);

                $dias = $diffHours / 24;

                $partes = explode('.', $dias);

                if (count($partes) == 1) {
                    $cant_dias = $dias;
                } else {
                    $cant_dias = intval($dias) + 1;
                }

                $servicioP->precio_total = $model->costo_servicios;
                $servicioP->cantidad = $cant_dias;

                $servicioP->save();
            }

            $buscaServicios = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])->all();

            foreach ($buscaServicios as $ser) {
                if ($ser->servicios->fijo == 2) {
                    $ser->delete();
                }
            }

            if (isset($_POST['total_techado']) && $idtechado !== null) {
                $total_techado = $_POST['total_techado'];
                $modelR = new ReservasServicios();
                $modelR->id_reserva = $model->nro_reserva;
                $modelR->id_servicio = $idtechado;
                $modelR->cantidad = 1;
                $modelR->precio_unitario = $total_techado;
                $modelR->precio_total = $total_techado;
                $modelR->tipo_servicio = 1;
                $modelR->save();
            } elseif (isset($_POST['total_techado']) && $idtechado === null) {
                Yii::error('No se pudo guardar ReservasServicios: servicio "techado" no encontrado.', __METHOD__);
            } elseif ($idtechado !== null) {
                $buscaRS = ReservasServicios::find()
                    ->where(['id_reserva' => $model->nro_reserva])
                    ->andWhere(['id_servicio' => $idtechado])
                    ->one();
                if (!$sel_techado && $buscaRS) {
                    $buscaRS->delete();
                }
            }

            foreach ($servicios as $ser) {
                $modelR = new ReservasServicios();
                $precio_unitario = $_POST['precio_unitario' . $ser->id];
                $cantidad = $_POST['cantidad' . $ser->id];
                $precio_total = $_POST['precio_total' . $ser->id];
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

            if ($_POST['is_noc'] == '11-1') {
                $modelR = new ReservasServicios();
                $modelR->id_reserva = $model->nro_reserva;
                $modelR->id_servicio = $_POST['servicio_noc_id'];
                $modelR->cantidad = 1;
                $modelR->precio_unitario = $_POST['servicio_noc_costo'];
                $modelR->precio_total = $_POST['servicio_noc_costo'];
                $modelR->tipo_servicio = 2;
                $modelR->save();
            }

            $reserva = $model->nro_reserva;

            if ($_POST['envio_email'] == 1) {
                $buscarCorreo = Clientes::find()->where(['id' => $model->cliente->id])->one();
                $mail = $buscarCorreo->correo;
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
                        'SetTitle' => Yii::$app->name . ' | Comprobante de Reserva',
                        'SetFooter' => ['{PAGENO}'],
                    ]
                ]);

                $pdf->render();

                if ($mail != null) {
                    $correo = Yii::$app->mailer->compose(
                        [
                            'html' => 'emailReserva2-html',
                            'text' => 'emailReserva-text'
                        ],
                        [
                            'nro_reserva' => $reserva,
                            'coche_matricula' => $coches->matricula,
                            'fecha_entrada' => $model->fecha_entrada,
                            'hora_entrada' => $model->hora_entrada,
                            'fecha_salida' => $model->fecha_salida,
                            'hora_salida' => $model->hora_salida,
                            'token' => $model->cod_valid
                        ]
                    );
                    $correo->setTo($mail)
                        ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                        ->setSubject('Reservación Parking Plus - Modificada')
                        ->attach('../web/pdf/comprobante_' . $reserva . '.pdf')
                        ->send();

                    $correo2 = Yii::$app->mailer->compose(
                        [
                            'html' => 'emailReserva2-html',
                            'text' => 'emailReserva-text'
                        ],
                        [
                            'nro_reserva' => $reserva,
                            'coche_matricula' => $coches->matricula,
                            'fecha_entrada' => $model->fecha_entrada,
                            'hora_entrada' => $model->hora_entrada,
                            'fecha_salida' => $model->fecha_salida,
                            'hora_salida' => $model->hora_salida,
                            'token' => $model->cod_valid
                        ]
                    );

                    $correo2->setTo('asistenciaplus00@gmail.com')
                        ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                        ->setSubject('Reservación Parking Plus - Modificada')
                        ->attach('../web/pdf/comprobante_' . $reserva . '.pdf')
                        ->send();
                }
            }

            Yii::$app->session->setFlash('success', 'La Reserva ha sido modificada de manera exitosa.');
            return $this->redirect(['reservas/index']);
        }

        return $this->render('update', [
            'model' => $model,
            'proxima_reserva' => $proxima_reserva . '-' . $servicioP->precio_total,
            'clientes' => $clientes,
            'coches' => $coches,
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
            'medio' => $medio,
            'agencia' => $agencia,
            'seleccionados' => $seleccionados,
            'descuento' => $descuento,
            'sel_techado' => $sel_techado,
            'precioTechado' => $precioTechado,
            'nocturno' => $extraNocturno,
            'precio_dia' => $precio_dia->valor_numerico
        ]);
    }

    /**
     * Deletes an existing Reservas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $reserva = Reservas::find()->where(['id' => $id])->one();
        $nro_reserva = $reserva->nro_reserva;

        $buscaServicios = ReservasServicios::find()->where(['id_reserva' => $nro_reserva])->all();
        foreach ($buscaServicios as $servicio) {
            $rs = ReservasServicios::findOne($servicio->id);
            $rs->delete();
        }

        $this->findModel($id)->delete();

        Yii::$app->session->setFlash('success', 'La Reserva ha sido Eliminada de manera exitosa.');
        return $this->redirect(['reservas/index']);
    }

    public function actionEstatus()
    {

        $model = new Reservas();
        //$reservas = Reservas::find()->orderBy(['nro_reserva' => SORT_DESC])->all();
        $reservas = Reservas::find()
            ->where(['between', 'created_at', date('Y-m-d', strtotime(date('Y-m-d') . "- 5 month")), date('Y-m-d', strtotime(date('Y-m-d') . "+ 1 days"))])
            ->orderBy(['nro_reserva' => SORT_DESC])->all();

        $listaR = ArrayHelper::map($reservas, 'id', 'nro_reserva');

        $listaE = [
            '0' => 'CANCELAR RESERVA',
            '2' => 'FINALIZAR RESERVA',
            '4' => 'RESERVA REZAGADA'
        ];

        if (Yii::$app->request->post()) {
            $id_reserva = $_POST['id_reserva'];
            $estatus = $_POST['estatus'];

            $reserva = Reservas::find()->where(['id' => $id_reserva])->one();
            $reserva->estatus = $estatus;
            $reserva->canceled_by = $estatus == 0 ? Yii::$app->user->id : 0;

            $reserva->save();

            if ($reserva->save()) {
                Yii::$app->session->setFlash('success', 'El estado de la reserva ha sido modificado de manera exitosa.');
                return $this->redirect(['reservas/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Error modificando el estado de la reserva.');
                return $this->redirect(['reservas/index']);
            }
        }

        return $this->renderAjax('estatus', [
            'model' => $model,
            'listaR' => $listaR,
            'listaE' => $listaE,
        ]);
    }

    // VALORACION ER

    // public function actionValoracion()
    // {
    //     $clientes = Clientes::find()->where(['estatus' => '1'])->orderBy(['nombre_completo' => SORT_DESC])->all();
    //     $listaClientes = ArrayHelper::map($clientes, 'id', 'nombre_completo');


    //     if (Yii::$app->request->post()) {
    //         $id_cliente = $_POST['id_cliente'];
    //         $buscarCliente = Clientes::findOne($id_cliente);
    //         $cliente = $buscarCliente->nombre_completo;
    //         $nro_reserva = $_POST['reserva'];
    //         $email_cliente = trim($_POST['correo']);

    //         $correo = Yii::$app->mailer->compose(
    //             [
    //                 'html' => 'evaluacionServicio-html',
    //                 'text' => 'evaluacionServicio-text',
    //             ],
    //             [
    //                 'cliente' => $cliente,
    //                 'nro_reserva' => $nro_reserva,
    //                 'correo' => $email_cliente,
    //             ]
    //         );
    //         $correo->setTo($email_cliente)
    //             ->setFrom([Yii::$app->params['contactEmail'] => Yii::$app->name])
    //             ->setSubject('Evalúe su reserva de aparcamiento')
    //             ->send();

    //         Yii::$app->session->setFlash('success', 'La Notificación de valoración de nuestro servicio ha sido enviada de manera exitosa.');
    //         return $this->redirect(['reservas/index']);
    //     }

    //     return $this->renderAjax('valoracion', [
    //         'listaClientes' => $listaClientes,
    //     ]);
    // }

    public function actionValoracion()
    {
        $clientes = Clientes::find()->where(['estatus' => '1'])->orderBy(['nombre_completo' => SORT_DESC])->all();
        $listaClientes = ArrayHelper::map($clientes, 'id', 'nombre_completo');


        if (Yii::$app->request->post()) {
            $id_cliente = $_POST['id_cliente'];
            $buscarCliente = Clientes::findOne($id_cliente);
            $cliente = $buscarCliente->nombre_completo;
            $nro_reserva = $_POST['reserva'];
            $email_cliente = trim($_POST['correo']);
            $urlEncuesta = Yii::$app->urlManagerFrontend->createAbsoluteUrl([
                'site/encuesta1',
                'reserva' => $nro_reserva,
            ]);

            $correo = Yii::$app->mailer->compose(
                [
                    'html' => 'evaluacionServicio-html',
                    'text' => 'evaluacionServicio-text',
                ],
                [
                    'cliente' => $cliente,
                    'nro_reserva' => $nro_reserva,
                    'correo' => $email_cliente,
                    'urlEncuesta' => $urlEncuesta,
                ]
            );
            $correo->setTo($email_cliente)
                ->setFrom([Yii::$app->params['contactEmail'] => Yii::$app->name])
                ->setSubject('Evalúe su reserva de aparcamiento')
                ->send();

            Yii::$app->session->setFlash('success', 'La Notificación de valoración de nuestro servicio ha sido enviada de manera exitosa.');
            return $this->redirect(['reservas/index']);
        }

        return $this->renderAjax('valoracion', [
            'listaClientes' => $listaClientes,
        ]);
    }

    public function actionCheckin()
    {
        $reservas = Reservas::find()->where(['between', 'created_at', date('Y-m-d', strtotime(date('Y-m-d') . "- 3 month")), date('Y-m-d', strtotime(date('Y-m-d') . "+ 1 days"))])->orderBy(['nro_reserva' => SORT_DESC])->all();
        $listaReservas = ArrayHelper::map($reservas, 'id', 'nro_reserva');

        if (Yii::$app->request->post()) {
            $id_reserva = $_POST['id_reserva'];
            $buscaReserva = Reservas::findOne($id_reserva);
            $nro_reserva = $buscaReserva->nro_reserva;
            $id_cliente = $_POST['id_cliente'];
            $buscarCliente = Clientes::findOne($id_cliente);
            $cliente = $buscarCliente->nombre_completo;
            $email_cliente = trim($_POST['correo']);

            $correo = Yii::$app->mailer->compose(
                [
                    'html' => 'confirmacionRecepcion-html',
                ],
                [
                    'cliente' => $cliente,
                    'nro_reserva' => $nro_reserva,
                    'correo' => $email_cliente,
                ]
            );
            $correo->setTo($email_cliente)
                ->setFrom([Yii::$app->params['contactEmail'] => Yii::$app->name])
                ->setSubject('Confirmación de Recepción de Coche')
                ->send();

            Yii::$app->session->setFlash('success', 'La Confirmación de Recepción ha sido enviada de manera exitosa.');
            return $this->redirect(['reservas/index']);
        }

        return $this->renderAjax('checkin', [
            'listaReservas' => $listaReservas,
        ]);
    }

    public function actionSugerencias()
    {
        $searchModel = new EncuestaInicialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderAjax('sugerencias', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionViewPdf($id)
    {

        $reserva = Reservas::find()->where(['id' => $id])->one();
        $reserva->impreso = 'SI';
        $reserva->save();

        $content = $this->renderPartial('_reportView', ['model' => $this->findModel($id)]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'cssFile' => '../web/css/reportes.css',
            'methods' => [
                'SetTitle' => Yii::$app->name . ' | Comprobante de Reserva',
                'SetFooter' => ['{PAGENO}'],
            ]
        ]);

        return $pdf->render();
    }

    public function actionGenerarPdf($fecha, $tipo)
    {

        $manana = strtotime($fecha . "+ 1 days");
        $manana = date("Y-m-d", $manana);

        // Entradas a las 00:00:00
        $buscaE0 = Reservas::find()->where(['fecha_entrada' => $manana])->andWhere(['between', 'hora_entrada', "00:00:00", "01:00:00"])->all();
        $e0 = count($buscaE0);

        // Salidas a las 00:00:00
        $buscaS0 = Reservas::find()->where(['fecha_salida' => $manana])->andWhere(['between', 'hora_salida', "00:00:00", "01:00:00"])->all();
        $s0 = count($buscaS0);

        $fechabusca = $fecha;
        $fecha = date('Y-m-d', strtotime($fecha));

        $session = Yii::$app->session;
        $session->open();
        $queryParams = isset($session['query_params']) ? json_decode($session['query_params'], true) : [];
        $session->close();

        $searchModel = new ReservasSearch();
        $dataProvider = $searchModel->searchPlanning($queryParams, $fecha);
        $dataProvider1 = $searchModel->searchP($queryParams, $fecha);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $formatter = \Yii::$app->formatter;

        // creando nuevo boton reporte2 ER 30-06

        if ($tipo == 1) {
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'filename' => 'Planning de Reservas-' . $fechabusca . '.pdf',
                'content' => $this->renderPartial('_reportPlanning', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProvider1' => $dataProvider1,
                    'fecha' => $fecha,
                    'e0' => $e0,
                    's0' => $s0
                ]),
                'cssInline' => 'body {
            font-size: 12px;
            text-transform: uppercase;
        }
        @page {
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 1cm;
            margin-right: 1cm;
        }
        a {
            color: #333;
        }
        #content {
            padding: 0px;
        }',
                'methods' => [
                    'SetTitle' => Yii::$app->name . ' | Planning de Reservas',
                    'SetFooter' => ['|Página {PAGENO}|'],
                ]
            ]);
        } elseif ($tipo == 2) {
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_BROWSER,
                'filename' => 'Planning de Rutas-' . $fechabusca . '.pdf',
                'content' => $this->renderPartial('_reportRuta', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProvider1' => $dataProvider1,
                    'fecha' => $fecha,
                    'e0' => $e0,
                    's0' => $s0
                ]),
                'cssInline' => 'body {
            font-size: 12px;
            text-transform: uppercase;
        }
        @page {
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 1cm;
            margin-right: 1cm;
        }
        a {
            color: #333;
        }
        #content {
            padding: 0px;
        }',
                'methods' => [
                    'SetTitle' => Yii::$app->name . ' | Planning Simplificado - Rutas',
                    'SetFooter' => ['|Página {PAGENO}|'],
                ]
            ]);
        } elseif ($tipo == 3) {
            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                // 'format' => [140, 216], // Media carta horizontal
                // 'format' => [216, 140], // Media carta vertical
                'format' => [216, 279], // Formato carta vertical
                'orientation' => Pdf::ORIENT_LANDSCAPE,
                'destination' => Pdf::DEST_BROWSER,
                'filename' => 'Resumen Diario - ' . $fechabusca . '.pdf',
                'content' => $this->renderPartial('_reportResumenDiario', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'dataProvider1' => $dataProvider1,
                    'fecha' => $fecha,
                    'e0' => $e0,
                    's0' => $s0
                ]),
                'cssInline' => 'body {
            font-size: 10px;
            text-transform: uppercase;
        }
        @page {
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 1cm;
            margin-right: 1cm;
        }
        a {
            color: #333;
        }
        #content {
            padding: 0px;
        }',
                'methods' => [
                    'SetTitle' => Yii::$app->name . ' | Resumen Diario',
                    'SetFooter' => ['|Página {PAGENO}|'],
                ]
            ]);
        } else {
            throw new \yii\web\BadRequestHttpException("Tipo de reporte no válido.");
        }

        return $pdf->render();



        // ASI ESTABA ANTERIORMENTE  ANTES DE CAMBIAR LAS LINEAS DE ARRIBA
        // if ($tipo == 1) {
        //     $pdf = new Pdf([
        //         'mode' => Pdf::MODE_UTF8,
        //         'format' => Pdf::FORMAT_A4,
        //         'orientation' => Pdf::ORIENT_PORTRAIT,
        //         'destination' => Pdf::DEST_BROWSER,
        //         'filename' => 'Planning de Reservas-' . $fechabusca . '.pdf',
        //         'content' => $this->renderPartial('_reportPlanning', [
        //             'searchModel' => $searchModel,
        //             'dataProvider' => $dataProvider,
        //             'dataProvider1' => $dataProvider1,
        //             'fecha' => $fecha,
        //             'e0' => $e0,
        //             's0' => $s0
        //         ]),
        //         'cssInline' => 'body {
        //     font-size: 12px;
        //     text-transform: uppercase;
        //   }
        //   @page {
        //       margin-top: 0.5cm;
        //       margin-bottom: 0.5cm;
        //       margin-left: 1cm;
        //       margin-right: 1cm;
        //   }
        //   a {
        //     color: #333;
        //   }
        //   #content {
        //     padding: 0px;
        //   }',
        //         'methods' => [
        //             'SetTitle' => Yii::$app->name . ' | Planning de Reservas',
        //             'SetFooter' => ['|Página {PAGENO}|'],
        //         ]
        //     ]);
        // } else {
        //     if ($tipo == 2) {
        //         $pdf = new Pdf([
        //             'mode' => Pdf::MODE_UTF8,
        //             'format' => Pdf::FORMAT_A4,
        //             'orientation' => Pdf::ORIENT_PORTRAIT,
        //             'destination' => Pdf::DEST_BROWSER,
        //             'filename' => 'Planning de Reservas-' . $fechabusca . '.pdf',
        //             'content' => $this->renderPartial('_reportRuta', [
        //                 'searchModel' => $searchModel,
        //                 'dataProvider' => $dataProvider,
        //                 'dataProvider1' => $dataProvider1,
        //                 'fecha' => $fecha,
        //                 'e0' => $e0,
        //                 's0' => $s0
        //             ]),
        //             'cssInline' => 'body {
        //       font-size: 12px;
        //       text-transform: uppercase;
        //     }
        //     @page {
        //         margin-top: 0.5cm;
        //         margin-bottom: 0.5cm;
        //         margin-left: 1cm;
        //         margin-right: 1cm;
        //     }
        //     a {
        //       color: #333;
        //     }
        //     #content {
        //       padding: 0px;
        //     }',
        //             'methods' => [
        //                 'SetTitle' => Yii::$app->name . ' | Planning Simplificado - Rutas',
        //                 'SetFooter' => ['|Página {PAGENO}|'],
        //             ]
        //         ]);
        //     }
        // }
        // return $pdf->render();

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

    public function actionCoches()
    {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $id_cliente = $parents[0];
                $lista = Reservas::getCochesList($id_cliente);

                foreach ($lista as $c => $coches) {
                    $out[] = ['id' => $coches['id'], 'name' => $coches['marca'] . ' - ' . $coches['modelo']];
                }
                return Json::encode(['output' => $out, 'selected' => '']);
            }
        }
        echo Json::encode(['output' => '', 'selected' => '']);
    }

    public function actionClientes()
    {
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $id = $data['id'];
            $cliente = Clientes::find()->where(['id' => $id])->one();

            $correo = $cliente['correo'];
            $tipo_documento = $cliente['tipo_documento'];
            $nro_documento = $cliente['nro_documento'];
            $movil = $cliente['movil'];

            $datos = array('correo' => $correo, 'tipo_documento' => $tipo_documento, 'nro_documento' => $nro_documento, 'movil' => $movil);

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return ['datos' => $datos];
        }
    }

    public function actionVehiculos()
    {
        if (Yii::$app->request->isAjax) {

            $data = Yii::$app->request->post();
            $id = $data['id'];
            $coche = Coches::find()->where(['id' => $id])->one();

            $matricula = $coche['matricula'];
            $color = $coche['color'];

            $datos = array('matricula' => $matricula, 'color' => $color);

            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            return ['datos' => $datos];
        }
    }

    public function actionFechas()
    {
        $model = new Reservas();
        $agencias = Agencias::find()->all();
        $listaAgencias = ArrayHelper::map($agencias, 'nombre', 'nombre');


        if ($model->load(Yii::$app->request->post())) {
            $entrada = $model->fecha_entrada;
            $salida = $model->fecha_salida;
            $hora_e = $model->hora_entrada;
            $hora_s = $model->hora_salida;
            $medio = $model->medio_reserva;
            $agencia = $model->agencia;

            if ($medio == 2) {
                return Yii::$app->response->redirect(['reservas/agencia', 'entrada' => $entrada, 'salida' => $salida, 'hora_e' => $hora_e, 'hora_s' => $hora_s, 'medio' => $medio, 'agencia' => $agencia])->send();
            } else {
                return Yii::$app->response->redirect(['reservas/create', 'entrada' => $entrada, 'salida' => $salida, 'hora_e' => $hora_e, 'hora_s' => $hora_s, 'medio' => $medio, 'agencia' => $agencia])->send();
            }
        }
        return $this->renderAjax('fechas', [
            'model' => $model,
            'listaAgencias' => $listaAgencias,
        ]);
    }

    public function actionDescuento()
    {
        $model = new Reservas();
        $reservas = Reservas::find()->where(['cupon' => null])->orderBy(['id' => SORT_DESC])->all();
        $listaR = ArrayHelper::map($reservas, 'nro_reserva', 'nro_reserva');

        if ($model->load(Yii::$app->request->post())) {
            $nro_reserva = $model->nro_reserva;
            $cupon = $model->cupon;
            $porcentaje = $model->porcentaje_cupo;
            $porc = ($porcentaje / 100);

            $buscareserva = Reservas::find()->where(['nro_reserva' => $nro_reserva])->one();

            $model = $buscareserva;
            $model->cupon = $cupon;
            $model->porcentaje_cupo = $porcentaje;

            $buscaiva = Configuracion::find()->where(['tipo_campo' => 1])->one();
            $iva = $buscaiva->valor_numerico;

            $descuento = $buscareserva->costo_servicios * $porc;
            $costo_servicios = $buscareserva->costo_servicios - $descuento;
            $model->costo_servicios = round($costo_servicios, 2);

            $reservice = ReservasServicios::find()->where(['id_reserva' => $nro_reserva])->andWhere(['tipo_servicio' => '1'])->one();
            $rcv = $reservice->precio_unitario;

            $modelRS = ReservasServicios::find()->where(['id_reserva' => $nro_reserva])->andWhere(['tipo_servicio' => '0'])->one();

            $punitario = $model->costo_servicios / $modelRS->cantidad;
            $modelRS->precio_unitario = round($punitario, 2);
            $precio_total = $model->costo_servicios;
            $modelRS->precio_total = round($precio_total, 2);

            $global = $model->costo_servicios + $buscareserva->costo_servicios_extra + $rcv;

            $montosiniva = ($global / (1 + $iva));
            $montoiva = $global - $montosiniva;

            $model->monto_factura = round($montosiniva, 2);
            $model->monto_impuestos = round($montoiva, 2);
            $mtotal = $model->monto_factura + $model->monto_impuestos;
            $model->monto_total = $mtotal;
            $model->save();
            $modelRS->save();

            Yii::$app->session->setFlash('success', 'El Cupón de Decuento fue registrado de manera exitosa.');
            return $this->redirect(['reservas/index']);
        }
        return $this->renderAjax('descuento', [
            'model' => $model,
            'listaR' => $listaR,
        ]);
    }

    public function actionGenerarf()
    {
        $model = new FacturasReserva();

        if ($model->load(Yii::$app->request->post())) {

            $id_usuario = Yii::$app->user->id;

            $id = $model->id_reserva;

            $datos_reserva = Reservas::find()->where(['id' => $id])->one();

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

                /*$montosiniva =  round(($datos_reserva->monto_total / $iva), 2);
                $montoiva = round(($datos_reserva->monto_factura - $montosiniva),2); */

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

                //var_dump($servicios); die();

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

            //var_dump($model); var_dump($modelFS); var_dump($modelFS); die();

            Yii::$app->session->setFlash('success', 'La Factura se ha generado de manera exitosa.');
            return $this->redirect(['facturas/index']);
        }
        return $this->renderAjax('generarf', [
            'model' => $model,
        ]);
    }

    public function actionTicket($id)
    {
        $model = Reservas::findOne($id);
        $model->impreso = 'SI';
        $model->save();

        return $this->renderAjax('ticket', [
            'model' => $model,
        ]);
    }

    public function actionTicketPlanning($ids)
    {

        if (Yii::$app->request->post()) {
            $lista = json_decode($ids);
            $tipo_ticket = $_POST['tipo_ticket'];

            for ($i = 0; $i < count($lista); $i++) {
                $model[$i] = Reservas::findOne($lista[$i]);
                $model[$i]->impreso = 'SI';
                $model[$i]->save();
                $servicios[$i] = ReservasServicios::find()->where(['id_reserva' => $model[$i]->nro_reserva])->all();

                $contS[$i] = 0;
                for ($j = 0; $j < count($servicios[$i]); $j++) {

                    if ($servicios[$i][$j]->servicios->fijo == 2) {
                        $contS[$i]++;
                    }
                }
            }

            if ($tipo_ticket == 1) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $formatter = \Yii::$app->formatter;
                $pdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8,
                    'format' => [80, 225],
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'destination' => Pdf::DEST_BROWSER,
                    'cssFile' => '../web/css/reportes.css',
                    //'cssFile' => '../admin/css/reportes.css',
                    'content' => $this->renderPartial('_reportTicketM', [
                        'contS' => $contS,
                        'model' => $model,
                        'servicios' => $servicios,
                    ]),
                    'cssInline' => 'body {
              text-transform: uppercase;
              font-weight: bold;
            }       
            @page {
                margin-top: 1cm;
                margin-bottom: 1cm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
            }
            a {
              color: #333;
            }
            #content {
              padding: 0px;
            }',
                    'methods' => [
                        'SetTitle' => Yii::$app->name . ' | Ticket de Reserva',
                        'SetFooter' => ['|Página {PAGENO}|'],
                    ]
                ]);
                return $pdf->render();
            }

            if ($tipo_ticket == 2) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $formatter = \Yii::$app->formatter;
                $pdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8,
                    'format' => [80, 138],
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'destination' => Pdf::DEST_BROWSER,
                    'cssFile' => '../web/css/reportes.css',
                    //'cssFile' => '../admin/css/reportes.css',
                    'content' => $this->renderPartial('_reportSobreM', [
                        'contS' => $contS,
                        'model' => $model,
                        'servicios' => $servicios,
                    ]),
                    'cssInline' => 'body {
              text-transform: uppercase;
              font-weight: bold;
            }       
            @page {
                margin-top: 0.5cm;
                margin-bottom: 0.5cm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
            }
            a {
              color: #333;
            }
            #content {
              padding: 0px;
            }',
                    'methods' => [
                        'SetTitle' => Yii::$app->name . ' | Sobre',
                        'SetFooter' => ['|Página {PAGENO}|'],
                    ]
                ]);
                return $pdf->render();
            }

            if ($tipo_ticket == 3) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
                $formatter = \Yii::$app->formatter;
                $pdf = new Pdf([
                    'mode' => Pdf::MODE_UTF8,
                    'format' => [80, 70],
                    'orientation' => Pdf::ORIENT_PORTRAIT,
                    'destination' => Pdf::DEST_BROWSER,
                    'cssFile' => '../web/css/reportes.css',
                    //'cssFile' => '../admin/css/reportes.css',
                    'content' => $this->renderPartial('_reportTicketParkM', [
                        'contS' => $contS,
                        'model' => $model,
                        'servicios' => $servicios,
                    ]),
                    'cssInline' => 'body {
              text-transform: uppercase;
              font-weight: bold;
            }       
            @page {
                margin-top: 0.5cm;
                margin-bottom: 0.5cm;
                margin-left: 0.5cm;
                margin-right: 0.5cm;
            }
            a {
              color: #333;
            }
            #content {
              padding: 0px;
            }',
                    'methods' => [
                        'SetTitle' => Yii::$app->name . ' | Ticket - Parking',
                        'SetFooter' => ['|Página {PAGENO}|'],
                    ]
                ]);
                return $pdf->render();
            }
        }

        return $this->renderAjax('ticketPlanning', [
            'lista_ids' => $ids,
        ]);
    }

    public function actionPrintTicket($id)
    {

        $model = Reservas::findOne($id);

        $servicios = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])->all();

        $contS = 0;
        for ($i = 0; $i < count($servicios); $i++) {

            if ($servicios[$i]->servicios->fijo == 2) {
                $contS++;
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $formatter = \Yii::$app->formatter;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => [80, 210],
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'cssFile' => '../web/css/reportes.css',
            'content' => $this->renderPartial('_reportTicket', [
                'contS' => $contS,
                'model' => $model,
                'servicios' => $servicios,
            ]),
            'cssInline' => 'body {
          text-transform: uppercase;
          font-weight: bold;
        }       
        @page {
          margin-top: 0.5cm;
          margin-bottom: 0.5cm;
          margin-left: 0.5cm;
          margin-right: 0.5cm;
        }
        a {
          color: #333;
        }
        #content {
          padding: 0px;
        }',
            'methods' => [
                'SetTitle' => Yii::$app->name . ' | Ticket de Reserva',
                'SetFooter' => ['|Página {PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }

    public function actionPrintSobre($id)
    {

        $model = Reservas::findOne($id);

        $servicios = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])->all();

        $contS = 0;
        for ($i = 0; $i < count($servicios); $i++) {

            if ($servicios[$i]->servicios->fijo == 2) {
                $contS++;
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $formatter = \Yii::$app->formatter;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => [80, 138],
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'cssFile' => '../web/css/reportes.css',
            'content' => $this->renderPartial('_reportSobre', [
                'contS' => $contS,
                'model' => $model,
                'servicios' => $servicios,
            ]),
            'cssInline' => 'body {
          text-transform: uppercase;
          font-weight: bold;
        }       
        @page {
          margin-top: 0.5cm;
          margin-bottom: 0.5cm;
          margin-left: 0.5cm;
          margin-right: 0.5cm;
        }
        a {
          color: #333;
        }
        #content {
          padding: 0px;
        }',
            'methods' => [
                'SetTitle' => Yii::$app->name . ' | Sobre',
                'SetFooter' => ['|Página {PAGENO}|'],
            ]
        ]);

        return $pdf->render();
    }

    public function actionPrintTicketPark($id)
    {

        $model = Reservas::findOne($id);

        $servicios = ReservasServicios::find()->where(['id_reserva' => $model->nro_reserva])->all();

        $contS = 0;
        for ($i = 0; $i < count($servicios); $i++) {

            if ($servicios[$i]->servicios->fijo == 2) {
                $contS++;
            }
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        $formatter = \Yii::$app->formatter;
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => [80, 70],
            'orientation' => Pdf::ORIENT_LANDSCAPE,
            'destination' => Pdf::DEST_BROWSER,
            'cssFile' => '../web/css/reportes.css',
            //'cssFile' => '../admin/css/reportes.css',
            'content' => $this->renderPartial('_reportTicketPark', [
                'contS' => $contS,
                'model' => $model,
                'servicios' => $servicios,
            ]),
            'cssInline' => 'body {
          text-transform: uppercase;
          font-weight: bold;
        }       
        @page {
            margin-top: 0.5cm;
            margin-bottom: 0.5cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
        }
        a {
          color: #333;
        }
        #content {
          padding: 0px;
        }',
            'methods' => [
                'SetTitle' => Yii::$app->name . ' | Ticket - Parking',
                'SetFooter' => ['|Página {PAGENO}|'],
            ]
        ]);
        return $pdf->render();
    }

    public function actionEnviaReserva($id)
    {
        $reserva = Reservas::find()->where(['id' => $id])->one();

        if (Yii::$app->request->post()) {

            $buscaReserva = Reservas::findOne($id);

            $nro_reserva = $buscaReserva->nro_reserva;
            $fecha_entrada = $buscaReserva->fecha_entrada;
            $fecha_salida = $buscaReserva->fecha_salida;
            $hora_entrada = $buscaReserva->hora_entrada;
            $hora_salida = $buscaReserva->hora_salida;

            $email_cliente = trim($_POST['correo']);

            $content = $this->renderPartial('_reportView', ['model' => $this->findModel($id)]);

            $pdf = new Pdf([
                'mode' => Pdf::MODE_UTF8,
                'format' => Pdf::FORMAT_A4,
                'orientation' => Pdf::ORIENT_PORTRAIT,
                'destination' => Pdf::DEST_FILE,
                'filename' => '../web/pdf/comprobante_' . $nro_reserva . '.pdf',
                'content' => $content,
                'cssFile' => '../web/css/reportes.css',
                'options' => ['title' => 'Comprobante de Reserva'],
                'methods' => [
                    'SetTitle' => Yii::$app->name . ' | Comprobante de Reserva',
                    'SetFooter' => ['{PAGENO}'],
                ]
            ]);

            $pdf->render();


            $correo = Yii::$app->mailer->compose(
                [
                    'html' => 'emailReserva2-html',
                    'text' => 'emailReserva-text'
                ],
                [
                    'nro_reserva' => $nro_reserva,
                    'fecha_entrada' => $fecha_entrada,
                    'hora_entrada' => $hora_entrada,
                    'fecha_salida' => $fecha_salida,
                    'hora_salida' => $hora_salida,
                ]
            );

            $correo->setTo($email_cliente)
                ->setFrom([Yii::$app->params['reservasEmail'] => 'Reservas - ' . Yii::$app->name])
                ->setSubject('Reservación Parking Plus')
                ->attach('../web/pdf/comprobante_' . $nro_reserva . '.pdf')
                ->send();

            Yii::$app->session->setFlash('success', 'El envio del Comprobante ha sido enviada de manera exitosa.');
            return $this->redirect(['reservas/index']);
        }

        return $this->renderAjax('reenvio_comprobante', [
            'reserva' => $reserva,
        ]);
    }

    public function actionApis()
    {

        $url = 'https://api.parkos.com/v1/reservations';

        $ch = curl_init($url);

        $token = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxNDA0IiwianRpIjoiYzZhOTZkZjRjMmNmZTY0NmI1NTRkZTUzM2QzMTE4ODZjOGNlMDJhMjJhMWZiYjM4ZGFlZTNjOTFiMWJhOGIxNWY5OGRkNWI1MDNlMGU1MmQiLCJpYXQiOjE2NDU4MjQzNzAsIm5iZiI6MTY0NTgyNDM3MCwiZXhwIjoxNjc3MzYwMzcwLCJzdWIiOiIxMjA4NzI4Iiwic2NvcGVzIjpbXX0.jk4WIsvu17qVaMMA8yV3bWLx9UMEBMQjwDTEUdz7IIxxY0Pcewu9RdDIYE5b6FNAuaY8dNIDUgoldYSZ_2E9TyGnw7EvoKjkiYkBwya75dR-6jh9NDjrmujIJFYNCAGugl9fe3swb33IC99xBh2gUmxRxDmoW7MHebyqflY7X_O7PK8O2HaTC0MItjLGl0FPRlfrJNyFo3z6IDlbcKqzvrs-k31XZugmjfovfoWYXZvfuLx6naeGM3jOc_RjhhzTco5Y9IDhAp9bQolxpMeJuXbpohyyOHrHjTGS3sbE_I5aPlBiQtsSIXJZT9v_ihMqwGjmcfWoVOiyndXt9ezOMfUnxujPJOu0AthkWSk3acyiwC9UhHnwEpmRGD7mMIkuFyYQ9s1Fp_QIsD0l1qffYcQqueTyHhGur-SMyKZZyZHrDQR-BvaBRhdhjQU57r_1sJIlywDEK56OkhroP5mmRYICiUAKdMzRz-ZlkAB-5hyle64cXgQJClZx5NV-0PDhPbWl4GAItnmGnjdfGsqW3gAE3i4tKypImFvYx9Q4XLSBTPoivR6zKJpxbjwbxTT4T2c7qZWw55WOT63-mXYNp45WG9INzIAyI7f8K2edfDXl6UL_HAuTaR39t232D0ifHaN8X85YEzrVgZqEBNeeOySrQQD4qlxTMvCiCKI1T-A";

        $headers = array();
        $headers[] = 'Cache-Control: no-cache';
        $headers[] = 'Authorization: ' . $token;
        $headers[] = 'Content-Type: application/json; charset= utf-8';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $result = curl_exec($ch);
        $datos = json_decode($result, true);
        $datos = $datos['data'];

        if (Yii::$app->request->post()) {
            $periodo = $_POST['periodo'];
            $desde = date('Y-m-d', strtotime($_POST['desde']));
            $hasta = date('Y-m-d', strtotime($_POST['hasta']));

            $params = 'period_type=' . $periodo . '&from=' . $desde . '&till=' . $hasta;

            $url = 'https://api.parkos.com/v1/reservations?' . $params;

            $ch = curl_init($url);

            $token = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxNDA0IiwianRpIjoiYzZhOTZkZjRjMmNmZTY0NmI1NTRkZTUzM2QzMTE4ODZjOGNlMDJhMjJhMWZiYjM4ZGFlZTNjOTFiMWJhOGIxNWY5OGRkNWI1MDNlMGU1MmQiLCJpYXQiOjE2NDU4MjQzNzAsIm5iZiI6MTY0NTgyNDM3MCwiZXhwIjoxNjc3MzYwMzcwLCJzdWIiOiIxMjA4NzI4Iiwic2NvcGVzIjpbXX0.jk4WIsvu17qVaMMA8yV3bWLx9UMEBMQjwDTEUdz7IIxxY0Pcewu9RdDIYE5b6FNAuaY8dNIDUgoldYSZ_2E9TyGnw7EvoKjkiYkBwya75dR-6jh9NDjrmujIJFYNCAGugl9fe3swb33IC99xBh2gUmxRxDmoW7MHebyqflY7X_O7PK8O2HaTC0MItjLGl0FPRlfrJNyFo3z6IDlbcKqzvrs-k31XZugmjfovfoWYXZvfuLx6naeGM3jOc_RjhhzTco5Y9IDhAp9bQolxpMeJuXbpohyyOHrHjTGS3sbE_I5aPlBiQtsSIXJZT9v_ihMqwGjmcfWoVOiyndXt9ezOMfUnxujPJOu0AthkWSk3acyiwC9UhHnwEpmRGD7mMIkuFyYQ9s1Fp_QIsD0l1qffYcQqueTyHhGur-SMyKZZyZHrDQR-BvaBRhdhjQU57r_1sJIlywDEK56OkhroP5mmRYICiUAKdMzRz-ZlkAB-5hyle64cXgQJClZx5NV-0PDhPbWl4GAItnmGnjdfGsqW3gAE3i4tKypImFvYx9Q4XLSBTPoivR6zKJpxbjwbxTT4T2c7qZWw55WOT63-mXYNp45WG9INzIAyI7f8K2edfDXl6UL_HAuTaR39t232D0ifHaN8X85YEzrVgZqEBNeeOySrQQD4qlxTMvCiCKI1T-A";

            $headers = array();
            $headers[] = 'Cache-Control: no-cache';
            $headers[] = 'Authorization: ' . $token;
            $headers[] = 'Content-Type: application/json; charset= utf-8';

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

            $result = curl_exec($ch);
            $datos = json_decode($result, true);
            if (isset($datos['data'])) {
                $datos = $datos['data'];
            } else {
                $datos = null;
            }
        }
        return $this->render('configura_apis', [
            'datos' => $datos
        ]);
    }

    /**
     * Mark a reservation as reviewed from the admin panel.
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionMarcarActualizada($id)
    {
        if (($model = Reservas::findOne($id)) !== null) {
            $model->actualizada = 0;
            $model->save(false, ['actualizada']);
        }
        return $this->redirect(['index']);
    }

    public function actionGenerarclave()
    {
        $modelU = new User();
        $modelU->username = '';
        $modelU->email = '';
        $modelU->setPassword('');
        $modelU->generateAuthKey();
        $modelU->generateEmailVerificationToken();
        $modelU->status = 10;

        print_r($modelU->password_hash);
    }

    /**
     * Metodo que elimina pdf del server mayores a 60 dias
     */
    public function actionDeletefiles()
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
