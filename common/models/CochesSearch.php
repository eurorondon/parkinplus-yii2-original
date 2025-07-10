<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Coches;
use frontend\models\UserCliente;

/**
 * CochesSearch represents the model behind the search form of `common\models\Coches`.
 */
class CochesSearch extends Coches
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_cliente', 'estatus_coche', 'created_by', 'updated_by'], 'integer'],
            [['matricula', 'marca', 'modelo', 'color', 'created_at', 'updated_at'], 'safe'],
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
        $query = Coches::find();

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
            'estatus_coche' => $this->estatus_coche, 
            'created_at' => $this->created_at, 
            'created_by' => $this->created_by, 
            'updated_at' => $this->updated_at, 
            'updated_by' => $this->updated_by,             
        ]);

        $query->andFilterWhere(['like', 'matricula', $this->matricula])
            ->andFilterWhere(['like', 'marca', $this->marca])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'id_cliente', $this->id_cliente]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchCoche($params)
    {

        $id = Yii::$app->user->id;

        $user_cliente = UserCliente::find()->where(['id_usuario' => $id])->one();
        $idcliente = $user_cliente->id_cliente;

        $query = Coches::find();

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
            'id_cliente' => $this->id_cliente,
            'estatus_coche' => $this->estatus_coche, 
            'created_at' => $this->created_at, 
            'created_by' => $this->created_by, 
            'updated_at' => $this->updated_at, 
            'updated_by' => $this->updated_by,             
        ]);

        $query->andFilterWhere(['like', 'matricula', $this->matricula])
            ->andFilterWhere(['like', 'marca', $this->marca])
            ->andFilterWhere(['like', 'modelo', $this->modelo])
            ->andFilterWhere(['like', 'color', $this->color]);

        return $dataProvider;
    }

}
