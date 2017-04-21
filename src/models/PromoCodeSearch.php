<?php
namespace dvizh\promocode\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dvizh\promocode\models\PromoCode;

class PromoCodeSearch extends PromoCode
{
    public function rules()
    {
        return [
            [['id', 'discount', 'status'], 'integer'],
            [['title', 'description', 'code'], 'safe'],
        ];
    }
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PromoCode::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'discount' => $this->discount,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
