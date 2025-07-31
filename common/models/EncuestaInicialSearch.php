<?php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EncuestaInicial;

class EncuestaInicialSearch extends EncuestaInicial
{
    public function rules()
    {
        return [
            [['id', 'reserva_id'], 'integer'],
            [['sugerencias', 'created_at'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = EncuestaInicial::find()->where(['not', ['sugerencias' => null]])->andWhere(['<>', 'sugerencias', '']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'reserva_id' => $this->reserva_id,
        ]);

        $query->andFilterWhere(['like', 'sugerencias', $this->sugerencias]);

        if (!empty($this->created_at)) {
            $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        }

        return $dataProvider;
    }
}
