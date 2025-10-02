<?php
namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\EncuestaInicial;

class EncuestaInicialSearch extends EncuestaInicial
{
    /**
     * Permite filtrar opcionalmente por encuestas con o sin comentario.
     * Valores admitidos: "con" | "sin" | null.
     *
     * @var string|null
     */
    public $tiene_sugerencias;

    public function rules()
    {
        return [
            [['id', 'reserva_id'], 'integer'],
            [['sugerencias', 'created_at'], 'safe'],
            [
                ['tiene_sugerencias'],
                'in',
                'range' => ['con', 'sin'],
                'strict' => false,
                'skipOnEmpty' => true,
            ],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = EncuestaInicial::find();

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

        if ($this->tiene_sugerencias === 'con') {
            $query->andWhere(['not', ['sugerencias' => null]])
                ->andWhere(['<>', 'sugerencias', '']);
        } elseif ($this->tiene_sugerencias === 'sin') {
            $query->andWhere([
                'or',
                ['sugerencias' => null],
                ['=', 'sugerencias', ''],
            ]);
        }

        return $dataProvider;
    }
}
