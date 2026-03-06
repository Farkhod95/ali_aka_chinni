<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\TypeExpense;

/* @var $this yii\web\View */
/* @var $model app\models\ExpensesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expenses-search" style="margin-bottom: 20px;">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'start_date')->input('date')->label('Boshlanish sana') ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'end_date')->input('date')->label('Tugash sana') ?>
        </div>

        <div class="col-md-3">
            <?= $form->field($model, 'type_id')->dropDownList(
                ArrayHelper::map(TypeExpense::find()->orderBy('name')->all(), 'id', 'name'),
                ['prompt' => 'Barchasi']
            )->label('Turi') ?>
        </div>

        <div class="col-md-3" style="margin-top: 25px;">
            <?= Html::submitButton('Filter', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Tozalash', ['index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>