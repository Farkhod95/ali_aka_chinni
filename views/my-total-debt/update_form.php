<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model app\models\MyTotalDebt */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="my-total-debt-form">

    <?php $form = ActiveForm::begin(['id' => 'my-total-debt-pay-form']); ?>
    <?= Html::hiddenInput('debt_request_id', Yii::$app->security->generateRandomString(32), ['id' => 'my-total-debt-request-id']) ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'consignor_id')->label()->widget(\kartik\select2\Select2::classname(), [
                'data' => $model->getConsignor1(),
                'options' => [
                    'placeholder' => Yii::t('app','Tanlang...'),
                    'disabled' => true,
                ],
                'pluginOptions' => [
                    'tags' => true,
                    'allowClear' => true,
                ],
            ])->label('Yuk jo\'natuvchi'); ?> 
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'total_debts')->textInput([
                'type' => 'number',
                'min' => 0,
                'step' => '0.01',
                'value' => $model->total_debts !== null ? $model->total_debts : 0,
            ])->label("<b style='color:red'> To'lanadigan qarz miqdori ($)</b>") ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'chegirma')->textInput([
                'type' => 'number',
                'min' => 0,
                'step' => '0.01',
                'value' => $model->chegirma !== null ? $model->chegirma : 0,
            ]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'cr_date')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => Yii::t('app','Sanani tanlang...'), 'required'=>True, 'value' => date('Y-m-d')],
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose'=>true, 
                    'format' => 'yyyy-mm-dd',
                ]
            ]);
            ?>
        </div>
    </div>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<?php
$js = <<<JS
(function(){
    var form = document.getElementById('my-total-debt-pay-form');
    if (!form) {
        return;
    }

    function makeRequestId() {
        if (window.crypto && window.crypto.getRandomValues) {
            var bytes = new Uint32Array(4);
            window.crypto.getRandomValues(bytes);
            return Array.prototype.map.call(bytes, function(n){ return n.toString(16); }).join('-');
        }
        return String(Date.now()) + '-' + String(Math.random()).slice(2);
    }

    form.addEventListener('submit', function(e) {
        var requestInput = document.getElementById('my-total-debt-request-id');
        if (requestInput && !requestInput.value) {
            requestInput.value = makeRequestId();
        }

        var button = form.querySelector('[type="submit"]') || document.querySelector('.modal-footer [type="submit"]');
        if (button) {
            button.disabled = true;
        }
    });
})();
JS;
$this->registerJs($js);
?>
