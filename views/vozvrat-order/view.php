<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\VozvratOrder */
?>
<div class="vozvrat-order-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'client_id',
            'date',
            [
                'attribute' => 'exchange_rate',
                'value' => $model->exchange_rate ?? 0,
            ],
            [
                'attribute' => 'discount_amount',
                'value' => $model->getCalculatedDiscountAmount(),
            ],
            [
                'attribute' => 'summ_dollar',
                'value' => $model->sum_dollar ?? 0,
            ],
            [
                'attribute' => 'all_summ_dollar',
                'value' => $model->all_summ_dollar ?? 0,
            ],
            [
                'attribute' => 'total_debt',
                'value' => $model->total_debt ?? 0,
            ],
            'confirmation',
            'cr_date_time',
            'comment:ntext',
            'created_by',
        ],
    ]) ?>

</div>
