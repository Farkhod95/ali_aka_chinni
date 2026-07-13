<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use unclead\multipleinput\MultipleInput;
/* @var $this yii\web\View */
/* @var $model app\models\ProductCategory */
/* @var $form yii\widgets\ActiveForm */
$data = is_array($model->allValue) && !empty($model->allValue) ? $model->allValue : [];
?>

<div class="product-category-form">

    <?php $form = ActiveForm::begin(['id' => 'product-category-form']); ?>
		<div class="row">
			<div class="col-md-12">
                    <?= $form->field($model, 'brand_id')->label()->widget(\kartik\select2\Select2::classname(), [
                        'data' => $model->getBrands(),
                        'options' => [
                            'placeholder' => Yii::t('app','Tanlang...'),
                            'required' => true,
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                            'allowClear' => true,],
                    ]); ?> 
            </div>
			<div class="col-md-2">
				<?= $form->field($model, 'sorting')->textInput(['maxlength' => true]) ?>
			</div>	
			<div class="col-md-10">
				<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
			</div>
            	
		</div>
        <div class="row">
             <div class="col-md-12">
                    <?php echo $form->field($model, 'allValue')->widget(MultipleInput::className(), [
                        'id' => 'my_id',
                        'data' => $data,
                        'allowEmptyList' => false,
                        'min' => 1,
                        'enableGuessTitle' => true,
                        'columns' => [
                            [
                                'name'  => 'size',
                                'title' => 'O\'lchami <b style="color:red">(Misol: 9.99 )</b>',
                                'enableError' => true,
                                'options' => [
                                    'class' => 'input-priority js-size',
                                    // 'type' =>'number',
                                    'required' => true,
                                ],
                                'headerOptions' => [
                                    'style' => 'width: 180px;',
                                    
                                ] 
                            ],
                            [
                                'name' => 'type',
                                'title' => 'Tip',
                                'type' => \kartik\select2\Select2::className(),
                                'options' => [
                                    'data'  => $model->getType(),
                                    'options' => [
                                        'placeholder' => 'Tanlang...',
                                        'class' => 'input-priority js-type',
                                        'required' => true
                                    ],   
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                    ],
                                    'headerOptions' => [
                                        'style' => 'width: 180px;',
                                        
                                    ] 
                            ],      
                        ]
                    ])->label('');?>
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
// ... sizning form kodingiz o‘zgarmaydi ...

$this->registerCss("
.pc-invalid { border-color:#dc3545 !important; }
.select2-selection.pc-invalid { border-color:#dc3545 !important; }
.pc-row-error { margin-top:4px; }
");

$adviceUrl = \yii\helpers\Url::to(['product-category/sorting-advice']);
$js = <<<JS
(function(){
  var \$brand = $('#productcategory-brand_id');
  var \$sorting = $('#productcategory-sorting');
  var \$field = $('.field-productcategory-sorting');
  var \$hint = $('<div class="help-block text-info small" id="sorting-hint"></div>');
  var lastSuggested = null;
  if (\$field.find('#sorting-hint').length === 0) {
      \$field.append(\$hint);
  }

  function fillSortingFromAdvice(data) {
      if (!data || !data.next_free) {
          return;
      }
      var next = String(data.next_free);
      var current = $.trim(\$sorting.val() || '');
      if (current === '' || current === String(lastSuggested)) {
          \$sorting.val(next);
          lastSuggested = next;
      }
  }

  function renderHint(data, currentVal) {
      if (!data) { \$hint.text(''); return; }
      var next = data.next_free || 1;
      var msg = 'Tavsiya etilgan keyingi raqam: ' + next;
      var v = parseInt(currentVal || 0, 10);
      if (v && v > next) {
          // kiritilgan qiymatgacha bo'shlar (oxirgi 5 tasini ko'rsatamiz)
          var miss = (data.missing || []).filter(function(n){ return n < v; });
          if (miss.length) {
              var tail = miss.slice(-5).join(', ');
              msg += ' — Diqqat: quyi bo‘sh raqam(lar): ' + tail;
          }
      }
      \$hint.text(msg);
  }

  var tId;
  function refreshHint() {
      var b = \$brand.val();
      if (!b) { \$hint.text('Avval brandni tanlang'); return; }
      $.getJSON('$adviceUrl', { brand_id: b, value: \$sorting.val() }, function(resp){
          fillSortingFromAdvice(resp);
          renderHint(resp, \$sorting.val());
      });
  }

  // Brand Select2 bo'lsa ham ishlaydi
  \$brand.on('change', refreshHint);
  \$sorting.on('keyup change', function(){
      clearTimeout(tId);
      tId = setTimeout(refreshHint, 200);
  });

  // Form ochilganda bir marta
  refreshHint();
})();

(function(){
  function applyRequiredAttributes() {
    $('#my_id').find('.js-size, .js-type').attr({
      required: true,
      'aria-required': 'true'
    });
  }

  function clearErrors() {
    $('.pc-row-error').remove();
    $('.pc-invalid').removeClass('pc-invalid');
    $('.select2-selection').removeClass('pc-invalid');
  }

  function markInput(\$input, message) {
    \$input.addClass('pc-invalid');
    if (\$input.next('.pc-row-error').length === 0) {
      \$input.after('<div class="pc-row-error text-danger small">'+message+'</div>');
    }
  }

  function markSelect(\$select, message) {
    var \$selection = \$select.next('.select2').find('.select2-selection');
    \$selection.addClass('pc-invalid');
    if (\$selection.parent().next('.pc-row-error').length === 0) {
      \$selection.parent().after('<div class="pc-row-error text-danger small">'+message+'</div>');
    }
  }

  $(document).on('input change', '#my_id .js-size, #my_id .js-type', function(){
    clearErrors();
  });

  $('#my_id').on('afterAddRow', function(){
    applyRequiredAttributes();
  });

  applyRequiredAttributes();

  $('#product-category-form').on('submit', function(e){
    applyRequiredAttributes();
    clearErrors();
    var hasError = false;

    $('#my_id').find('.multiple-input-list__item').each(function(){
      var \$row = $(this);
      var \$size = \$row.find('.js-size');
      var \$type = \$row.find('.js-type');
      var sizeVal = $.trim(\$size.val() || '').replace(',', '.');

      if (!sizeVal || isNaN(parseFloat(sizeVal))) {
        hasError = true;
        markInput(\$size, "O'lcham kiritilishi shart");
      }

      if (!\$type.val()) {
        hasError = true;
        markSelect(\$type, 'Tip tanlanishi shart');
      }
    });

    if (hasError) {
      e.preventDefault();
      var \$first = $('.pc-row-error').first();
      if (\$first.length) {
        $('html,body').animate({scrollTop: \$first.offset().top - 150}, 250);
      }
    }
  });
})();
JS;

$this->registerJs($js);
?>
