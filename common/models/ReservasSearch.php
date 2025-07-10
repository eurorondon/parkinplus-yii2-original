<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Reservas;
use frontend\models\UserCliente;
use common\models\UserAfiliados;

/**
 * ReservasSearch represents the model behind the search form of `common\models\Reservas`.
 */
class ReservasSearch extends Reservas
{

    public $matricula;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_cliente', 'id_coche', 'factura_equipaje', 'factura', 'id_tipo_pago', 'condiciones', 'estatus', 'medio_reserva', 'created_by', 'updated_by'], 'integer'],
            [['agencia'], 'string', 'max' => 255],                      
            [['fecha_entrada', 'hora_entrada', 'fecha_busca', 'fecha_salida', 'hora_salida', 'terminal_entrada', 'terminal_salida', 'nro_vuelo_regreso', 'ciudad_procedencia', 'observaciones', 'nif', 'razon_social', 'direccion', 'cod_postal', 'ciudad', 'provincia', 'pais', 'created_at', 'updated_at', 'matricula'], 'safe'],
            [['nro_reserva','costo_servicios', 'costo_servicios_extra', 'monto_factura', 'monto_impuestos', 'monto_total'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {

        $flagParams = 0;
        if(isset($params['ReservasSearch'])){
            foreach($params['ReservasSearch'] as $key => $value){
                if(!empty($value)){
                    $flagParams = 1;
                }
            }
        } 

        $id_usuario = Yii::$app->user->id;

        $buscarAfiliado = UserAfiliados::find()->where(['user_id' => $id_usuario])->one();
        if (!empty($buscarAfiliado)) {
            $tipo_afiliado = $buscarAfiliado['tipo_afiliado'];
        } else {
            $tipo_afiliado = 0;     
        }

        if ($tipo_afiliado == 0) {        
            $query = $flagParams == 0 ? 
                            Reservas::find()
                                ->where(['!=','reservas.estatus','10'])
                                ->andWhere(['between', 'reservas.created_at', date("Y-m-d", strtotime(date('Y-m-d 00:00:00') . "- 15 days")), date('Y-m-d 23:59:59')])
                                ->orderBy(['reservas.id' => SORT_DESC]) : 
                            Reservas::find()
                                ->where(['!=','reservas.estatus','10'])
                                ->andWhere(['between', 'reservas.created_at', date("Y-m-d", strtotime(date('Y-m-d 00:00:00') . "- 1 year")), date('Y-m-d 23:59:59')])
                                ->orderBy(['reservas.id' => SORT_DESC]);

            $query->joinWith(['coche']);

        } else {
            $query = $flagParams == 0 ? 
                        Reservas::find()
                            ->where(['!=','reservas.estatus','10'])
                            ->andWhere(['reservas.medio_reserva' => 4])
                            ->andWhere(['between', 'reservas.created_at', date("Y-m-d", strtotime(date('Y-m-d 00:00:00') . "- 15 days")), date('Y-m-d 23:59:59')])
                            ->orderBy(['reservas.id' => SORT_DESC]) : 
                        Reservas::find()
                            ->where(['!=','reservas.estatus','10'])
                            ->andWhere(['reservas.medio_reserva' => 4])
                            ->andWhere(['between', 'reservas.created_at', date("Y-m-d", strtotime(date('Y-m-d 00:00:00') . "- 1 years")), date('Y-m-d 23:59:59')])
                            ->orderBy(['reservas.id' => SORT_DESC]);

            $query->joinWith(['coche']);            
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        //echo "<pre>"; var_dump($dataProvider->getModels()); echo "</pre>"; die();


        

        $this->load($params);

        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'nro_reserva' => $this->nro_reserva,
            'fecha_entrada' => $this->fecha_entrada,
            'hora_entrada' => $this->hora_entrada,
            'fecha_salida' => $this->fecha_salida,
            'hora_salida' => $this->hora_salida,
            'coches.id_cliente' => $this->id_cliente,
            'id_coche' => $this->id_coche,
            'factura_equipaje' => $this->factura_equipaje,
            'factura' => $this->factura,
            'costo_servicios' => $this->costo_servicios,
            'costo_servicios_extra' => $this->costo_servicios_extra,
            'monto_factura' => $this->monto_factura,
            'monto_impuestos' => $this->monto_impuestos,
            'monto_total' => $this->monto_total,
            'id_tipo_pago' => $this->id_tipo_pago,
            'condiciones' => $this->condiciones,
            'estatus' => $this->estatus,
            'medio_reserva' => $this->medio_reserva,
            'agencia' => $this->agencia,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'terminal_entrada', $this->terminal_entrada])
            ->andFilterWhere(['like', 'terminal_salida', $this->terminal_salida])
            ->andFilterWhere(['like', 'nro_vuelo_regreso', $this->nro_vuelo_regreso])
            ->andFilterWhere(['like', 'ciudad_procedencia', $this->ciudad_procedencia])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'cod_postal', $this->cod_postal])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'provincia', $this->provincia])
            //->andFilterWhere(['like', 'estatus', $this->estatus])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'coches.matricula', $this->matricula]);
            

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchRes($params)
    {

        $id = Yii::$app->user->id;

        $user_cliente = UserCliente::find()->where(['id_usuario' => $id])->one();
        $idcliente = $user_cliente->id_cliente;

        $query = Reservas::find();        

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        $query->andFilterWhere(['id_cliente' => $idcliente]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'nro_reserva' => $this->nro_reserva,
            'fecha_entrada' => $this->fecha_entrada,
            'hora_entrada' => $this->hora_entrada,
            'fecha_salida' => $this->fecha_salida,
            'hora_salida' => $this->hora_salida,
            'id_cliente' => $this->id_cliente,
            'id_coche' => $this->id_coche,
            'factura_equipaje' => $this->factura_equipaje,
            'factura' => $this->factura,
            'costo_servicios' => $this->costo_servicios,
            'costo_servicios_extra' => $this->costo_servicios_extra,
            'monto_factura' => $this->monto_factura,
            'monto_impuestos' => $this->monto_impuestos,
            'monto_total' => $this->monto_total,
            'id_tipo_pago' => $this->id_tipo_pago,
            'condiciones' => $this->condiciones,
            'estatus' => $this->estatus,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'terminal_entrada', $this->terminal_entrada])
            ->andFilterWhere(['like', 'terminal_salida', $this->terminal_salida])
            ->andFilterWhere(['like', 'nro_vuelo_regreso', $this->nro_vuelo_regreso])
            ->andFilterWhere(['like', 'ciudad_procedencia', $this->ciudad_procedencia])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'cod_postal', $this->cod_postal])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'provincia', $this->provincia])
            ->andFilterWhere(['like', 'pais', $this->pais]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPlanning($params, $fecha)
    {    

        $id_usuario = Yii::$app->user->id;

        $buscarAfiliado = UserAfiliados::find()->where(['user_id' => $id_usuario])->one();
        if (!empty($buscarAfiliado)) {
            $tipo_afiliado = $buscarAfiliado['tipo_afiliado'];
        } else {
            $tipo_afiliado = 0;     
        }

        if ($tipo_afiliado == 0) {        
             $query = Reservas::find()->where(['<>', 'estatus', 0])->orderBy(['hora_entrada' => SORT_ASC]); 

        } else {
            $query = Reservas::find()->where(['<>', 'estatus', 0])->andWhere(['medio_reserva' => 4])->orderBy(['hora_entrada' => SORT_ASC]);             
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => false,
            ],            
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'nro_reserva' => $this->nro_reserva,
            'id_cliente' => $this->id_cliente,

        ]);

        $query->andFilterWhere(['like', 'terminal_entrada', $this->terminal_entrada])
            ->andFilterWhere(['like', 'terminal_salida', $this->terminal_salida])
            ->andFilterWhere(['like', 'nro_vuelo_regreso', $this->nro_vuelo_regreso])
            ->andFilterWhere(['like', 'ciudad_procedencia', $this->ciudad_procedencia])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'cod_postal', $this->cod_postal])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'provincia', $this->provincia])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'coche.matricula', $this->matricula])
            ->andFilterWhere(['like', 'coche.marca', $this->marca])
            ->andFilterWhere(['like', 'coche.modelo', $this->modelo])
            ->andFilterWhere(['like', 'fecha_entrada', $fecha]);        

        return $dataProvider;
    } 

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchP($params, $fecha)
    {    

        $id_usuario = Yii::$app->user->id;

        $buscarAfiliado = UserAfiliados::find()->where(['user_id' => $id_usuario])->one();
        if (!empty($buscarAfiliado)) {
            $tipo_afiliado = $buscarAfiliado['tipo_afiliado'];
        } else {
            $tipo_afiliado = 0;     
        }

        if ($tipo_afiliado == 0) {        
            $query = Reservas::find()->where(['<>', 'estatus', 0])->orderBy(['hora_salida' => SORT_ASC]); 

        } else {
            $query = Reservas::find()->where(['<>', 'estatus', 0])->andWhere(['medio_reserva' => 4])->orderBy(['hora_salida' => SORT_ASC]);             
        }

        // add conditions that should always apply here

        $dataProvider1 = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => false,
            ],             
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider1;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'nro_reserva' => $this->nro_reserva,
            'id_cliente' => $this->id_cliente,

        ]);

        $query->andFilterWhere(['like', 'terminal_entrada', $this->terminal_entrada])
            ->andFilterWhere(['like', 'terminal_salida', $this->terminal_salida])
            ->andFilterWhere(['like', 'nro_vuelo_regreso', $this->nro_vuelo_regreso])
            ->andFilterWhere(['like', 'ciudad_procedencia', $this->ciudad_procedencia])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'cod_postal', $this->cod_postal])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'provincia', $this->provincia])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'coche.matricula', $this->matricula])
            ->andFilterWhere(['like', 'fecha_salida', $fecha]);        

        return $dataProvider1;
    }       

}
