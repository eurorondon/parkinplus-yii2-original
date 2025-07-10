<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Facturas;
use frontend\models\UserCliente;

/**
 * FacturasSearch represents the model behind the search form of `common\models\Facturas`.
 */
class FacturasSearch extends Facturas
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'estatus'], 'integer'],
            [['serie', 'nif', 'razon_social', 'direccion', 'cod_postal', 'ciudad', 'provincia', 'pais', 'created_at', 'updated_at'], 'safe'],
            [['nro_factura', 'monto_factura', 'monto_impuestos', 'monto_total'], 'number'],
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
        //->where(['estatus' => 1])
        $query = Facturas::find()->orderBy(['nro_factura' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'estatus' => $this->estatus,
            'nro_factura' => $this->nro_factura,
            'monto_factura' => $this->monto_factura,
            'monto_impuestos' => $this->monto_impuestos,
            'monto_total' => $this->monto_total,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'serie', $this->serie])
            ->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'cod_postal', $this->cod_postal])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'provincia', $this->provincia])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'created_at' , $this->created_at]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchFac($params)
    {

        $id = Yii::$app->user->id;

        $user_cliente = UserCliente::find()->where(['id_usuario' => $id])->one();
        $idcliente = $user_cliente->id_cliente;
        
        $query = Facturas::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'nro_factura' => $this->nro_factura,
            'monto_factura' => $this->monto_factura,
            'monto_impuestos' => $this->monto_impuestos,
            'monto_total' => $this->monto_total,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'serie', $this->serie])
            ->andFilterWhere(['like', 'nif', $this->nif])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'direccion', $this->direccion])
            ->andFilterWhere(['like', 'cod_postal', $this->cod_postal])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'provincia', $this->provincia])
            ->andFilterWhere(['like', 'pais', $this->pais]);

        return $dataProvider;
    }

}
