<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\models\Expenses;

class ExpensesSearch extends Expenses
{
    public $start_date;
    public $end_date;

    public function rules()
    {
        return [
            [['id', 'type_id', 'loss_of_profit_id'], 'integer'],
            [['nomi', 'date_cr', 'start_date', 'end_date'], 'safe'],
            [['summa'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Expenses::find()->alias('e');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'e.id' => $this->id,
            'e.type_id' => $this->type_id,
            'e.loss_of_profit_id' => $this->loss_of_profit_id,
        ]);

        $query->andFilterWhere(['like', 'e.nomi', $this->nomi]);

        if ($this->summa !== null && $this->summa !== '') {
            $query->andWhere(['e.summa' => $this->summa]);
        }

        // Sana oralig'i filteri
        if (!empty($this->start_date)) {
            $start = date('Y-m-d', strtotime($this->start_date));
            $query->andWhere(['>=', 'e.date_cr', $start]);
        }

        if (!empty($this->end_date)) {
            $end = date('Y-m-d', strtotime($this->end_date));
            $query->andWhere(['<=', 'e.date_cr', $end]);
        }

        // Grid filterdagi date_cr ishlashi uchun
        if (!empty($this->date_cr)) {
            $date = date('Y-m-d', strtotime($this->date_cr));
            $query->andWhere(['e.date_cr' => $date]);
        }

        return $dataProvider;
    }

    public function getTotalSum($params)
    {
        $query = Expenses::find()->alias('e');

        $this->load($params);

        if (!$this->validate()) {
            return 0;
        }

        $query->andFilterWhere([
            'e.id' => $this->id,
            'e.type_id' => $this->type_id,
            'e.loss_of_profit_id' => $this->loss_of_profit_id,
        ]);

        $query->andFilterWhere(['like', 'e.nomi', $this->nomi]);

        if ($this->summa !== null && $this->summa !== '') {
            $query->andWhere(['e.summa' => $this->summa]);
        }

        if (!empty($this->start_date)) {
            $start = date('Y-m-d', strtotime($this->start_date));
            $query->andWhere(['>=', 'e.date_cr', $start]);
        }

        if (!empty($this->end_date)) {
            $end = date('Y-m-d', strtotime($this->end_date));
            $query->andWhere(['<=', 'e.date_cr', $end]);
        }

        if (!empty($this->date_cr)) {
            $date = date('Y-m-d', strtotime($this->date_cr));
            $query->andWhere(['e.date_cr' => $date]);
        }

        return (float)$query->sum('e.summa');
    }
}