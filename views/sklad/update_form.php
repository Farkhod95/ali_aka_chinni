<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use unclead\multipleinput\MultipleInput;
use app\models\ExchangeRate;
use app\models\Brands;
use app\models\BrandsSize;
use app\models\WarehouseHistory;
use app\models\MyTotalDebt;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\Warehouse */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Tovar qo\'shish';
$this->params['breadcrumbs'][] = $this->title;
$exchangeRate = ExchangeRate::findOne(1);
$brandList = Brands::listActive();
$sizeList = [];
foreach (BrandsSize::find()->select('size')->distinct()->orderBy(['size' => SORT_ASC])->column() as $size) {
    $sizeList[(string)$size] = (string)$size;
}

$urlCats = Url::to(['order-account-history/categories-by-brand']);
$urlSizesOnly = Url::to(['order-account-history/sizes-types-by-category']);
$urlTypesBySz = Url::to(['order-account-history/types-by-size']);

$warehouseHistory = WarehouseHistory::find()->where(['sklad_id' => $model->id])->all();
$data = [];
foreach ($warehouseHistory as $warehouse_history) {
    if (!isset($brandList[$warehouse_history->brand_id]) && $warehouse_history->brand) {
        $brandList[$warehouse_history->brand_id] = $warehouse_history->brand->name;
    }
    $sizeList[(string)$warehouse_history->size] = (string)$warehouse_history->size;
    $data[] = [
        'brand_id' => $warehouse_history->brand_id,
        'product_category_id' => $warehouse_history->product_category_id,
        'size' => $warehouse_history->size,
        'type' => $warehouse_history->type,
        'count' => $warehouse_history->count,
        'price' => $warehouse_history->price,
    ];
}


?>

<div class="panel panel-inverse user-index">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="/sklad/index" class="btn btn-xs  btn-warning"> <i class="fa fa-reply"></i> Orqaga qaytish </a>
            <a href="javascript:;" title="Во весь экран" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
            <a href="javascript:;" title="Обновить" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
        </div>
        <h4 class="panel-title">Tovar qo'shish</h4>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(['id' => 'order-form', 'options' => ['novalidate' => true]]); ?>
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'consignor_id')->label()->widget(\kartik\select2\Select2::classname(), [
                        'data' => $model->getConsignor(),
                        'options' => [
                            'placeholder' => Yii::t('app','Tanlang...'),
                            'disabled' => true,
                            'onchange'=>'
                                $.post( "/sklad/qarzs?id='.'"+$(this).val(), function( data ){
                                    $( "input#total_debts" ).val( data);
                                    alter(data);
                                });' 
                        ],
                        'pluginOptions' => [
                            'tags' => true,
                            'allowClear' => true,
                        ],
                    ])->label('Yuk jo\'natuvchi <b style="color:red">(Kiritilish majburiy)</b>'); ?> 
                </div>
                <div class="col-md-3">
                    <?php if (\Yii::$app->user->identity->permission == 1) {?>
                        <?= $form->field($model, 'my_total_debt')->textInput(['value' => $my_total_debt, 'id' => 'total_debts', 'readonly' => true,])->label("Mening qarzim ($)") ?>
                    <?php }else{?>
                        <?= $form->field($model, 'my_total_debt')->textInput(['value' => $my_total_debt, 'id' => 'total_debts', 'style' => 'display:none;'])->label("") ?>
                    <?php }?>

                </div>
                <div class="col-md-3">
                    <?= $form->field($model, 'cr_date')->widget(DatePicker::classname(), [
                        'options' => [
                            'placeholder' => Yii::t('app','Sanani tanlang...'),
                            'required'=>True,
                            'value' => !empty($model->cr_date) ? date('d.m.Y', strtotime($model->cr_date)) : date('d.m.Y')
                        ],
                        'removeButton' => false,
                        'pluginOptions' => [
                            'autoclose'=>true, 
                            'format' => 'dd.mm.yyyy',
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-1">
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'exchange_rate')->hiddenInput([
                        'value' => $model->exchange_rate ?: $exchangeRate->dollar,
                    ])->label(false) ?>
                </div>
                <div class="col-md-12">
                    <?php echo $form->field($model, 'allValue')->widget(MultipleInput::className(), [
                        'id' => 'my_id',
                        'data' => $data,
                        'allowEmptyList' => false,
                        'enableGuessTitle' => true,
                        'columns' => [
                            [
                                'name' => 'brand_id',
                                'title' => 'Model',
                                'type' => \kartik\select2\Select2::className(),                                
                                'options' => [
                                    'data'  => $brandList,
                                    'options' => [
                                        'placeholder' => 'Tanlang...',
                                        'class' => 'input-priority mi-brand mi-req',
                                        'required' => true
                                    ], 
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                                                     
                                ],
                                'headerOptions' => [
                                    'style' => 'width: 370px;',
                                ] 
                            ],
                            [
                                'name' => 'product_category_id',
                                'title' => 'Nomi',
                                'type' => \kartik\select2\Select2::className(),
                                'options' => [
                                    'data'  => $model->getProductCategories(),
                                    'options' => [
                                        'placeholder' => 'Tanlang...',
                                        'class' => 'input-priority mi-category mi-req',
                                        'required' => true
                                    ],   
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                    ],
                                    'headerOptions' => [
                                        'style' => 'width: 370px;',
                                        
                                    ] 
                            ],
                            [
                                'name'  => 'size',
                                'title' => 'O\'lchami',
                                'type' => \kartik\select2\Select2::className(),
                                'enableError' => true,
                                'defaultValue' => 0,
                                'options' => [
                                    'data' => $sizeList,
                                    'options' => [
                                        'placeholder' => 'Tanlang...',
                                        'class' => 'input-priority mi-size mi-req',
                                        'required' => true,
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                 ]
                            ],
                            [
                                'name' => 'type',
                                'title' => 'Tip',
                                'type' => \kartik\select2\Select2::className(),
                                'options' => [
                                    'data'  => $model->getProductDukonType(),
                                    'options' => [
                                        'placeholder' => 'Tanlang...',
                                        'class' => 'input-priority mi-type mi-req',
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
                            [
                                'name'  => 'count',
                                'title' => 'Soni',
                                'enableError' => true,
                                'options' => [
                                    'type' =>'number',
                                    'class' => 'input-priority target',
                                    'headerOptions' => [
                                        'style' => 'font-size: 40px',
                                    ] ,
                                ]
                            ],   
                            [
                                'name'  => 'price',
                                'title' => \Yii::$app->user->identity->permission == 1 ? 'Narxi ($)' : '',
                                'enableError' => true,
                                'options' => [ 
                                    // 'type' =>'number',
                                    'class' => 'input-priority mi-price',
                                    'style' => \Yii::$app->user->identity->permission == 1 ? '' : 'display: none;',
                                    'options' => [
                                        'id' => 'price',
                                    ], 
                                    'id' => 'price',
                                    'headerOptions' => [
                                        'id' => 'price',
                                    ] ,
                                    'pluginOptions' => [
                                        'id' => 'price',
                                    ],  
                                    // 'onchange'=>'
                                    //     price = $(this).val();
                                    //     // alert(price);
                                    // '
                                ]
                            ],
                        ]
                    ])->label('');?>
                </div>
                <div class="row">
                    
                    <div class="col-md-10">
                    </div>
                    <div class="col-md-2">
                        <?php if (\Yii::$app->user->identity->permission == 1) {?>
                            <?= $form->field($model, 'sum_all_pro')->textInput([
                                'readonly' => true, // readonly qilib qo'yamiz
                                'style' => 'margin-top:-30px; margin-left:0px;width:170px',
                                'id' => 'sum_all_pro', // input elementga ID qo'yamiz
                            ]) ?>
                        <?php }else{?>
                            <?= $form->field($model, 'sum_all_pro')->textInput([
                                'readonly' => true, // readonly qilib qo'yamiz
                                'style' => 'margin-top:-30px; margin-left:0px;width:170px;display:none;',
                                'id' => 'sum_all_pro', // input elementga ID qo'yamiz
                            ])->label("") ?>
                        <?php }?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'comments')->textInput(['required' => true])->label("<b style='color:red;'>O'zgargan mahsulotlar bo'yicha izoh kiriting </b>") ?>
                    </div>
                    <div class="col-md-2">
                        <?= $form->field($model, 'given_sum_dollar')->textInput(['style' => 'display:none;'])->label("<b style='color:#1748d3'></b>") ?> 
                    </div>
                    <div class="col-md-2">
                        <?php if (\Yii::$app->user->identity->permission == 1) {?>
                            <?= $form->field($model, 'sum_dollar')->textInput(['type' => 'number'])->label("<b style='color:#31701b'>Berilgan Summa ($) </b>") ?> 
                        <?php }else{?>
                            <?= $form->field($model, 'sum_dollar')->textInput(['type' => 'number', 'style' => 'display:none;'])->label("<b style='color:#31701b'> </b>") ?> 
                        <?php }?>
                    </div>
                    <div class="col-md-2">
                        <?php if (\Yii::$app->user->identity->permission == 1) {?>
                            <?= $form->field($model, 'discount_amount')->textInput(['type' => 'number'])->label("<b style='color:#f59c1a'>Jami chegirma ($) </b>") ?>
                        <?php }else{?>
                            <?= $form->field($model, 'discount_amount')->textInput(['type' => 'number', 'style' => 'display:none;'])->label("<b style='color:#f59c1a'> </b>") ?>
                        <?php }?>
                    </div>
                </div>
            <!-- <div class="row">
                <div class="col-md-3">
                    < ?= $form->field($model, 'order_account_statuses')->checkbox(['checked' => true])->label("<b style='font-size:16px;color:red'>Tasdiqlash: </b>") ?>
                </div>
            </div> -->
            </div>
            <?php if (!Yii::$app->request->isAjax){ ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Qo\'shish' : 'O\'zgartirish', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary', 'style' => 'width:100%']) ?>
                </div>
            <?php } ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
<?php
$this->registerJsFile('/js/cookie.js');

$this->registerCss("
.select2-selection.is-invalid { border-color:#dc3545 !important; }
.is-invalid { border-color:#dc3545 !important; }
.mi-row-error{ color:#dc3545; font-size:12px; margin-top:4px; }
");

$this->registerJs('window.__SKLAD_URLS__ = '.Json::htmlEncode([
    'cats' => $urlCats,
    'sizes' => $urlSizesOnly,
    'types' => $urlTypesBySz,
]).';', View::POS_END);

$this->registerJs(<<<'JS'

function copyToClipboard(element) {
    element.select(); // Element qiymatini tanlaymiz
    document.execCommand("copy"); // Nusxalaymiz

    // Xabar elementini yaratamiz
    const message = document.createElement("span");
    message.innerText = "Nusxalandi";
    message.style.position = "absolute";
    message.style.bottom = "50%";
    message.style.left = "150px";
    message.style.transform = "translateY(-50%)";
    message.style.backgroundColor = "#83a16ed9";
    message.style.color = "white";
    message.style.padding = "5px 10px";
    message.style.borderRadius = "4px";
    message.style.fontSize = "12px";

    // Xabarni input yoniga qo'shamiz
    element.parentNode.appendChild(message);

    // Xabarni 2 soniyadan keyin o'chiramiz
    setTimeout(() => {
        message.remove();
    }, 2000);
}

document.getElementById("sum_all_pro").onclick = function() {
    copyToClipboard(this); // Input ustiga bosilganda nusxalaymiz
};

function fillSelect($select, data, selectedVal){
    $select.empty();
    $select.append(new Option('Tanlang...', '', false, false));
    let foundSel = false;
    (data || []).forEach(function(item){
        let id = (typeof item === 'object' && item !== null) ? item.id : item;
        let text = (typeof item === 'object' && item !== null) ? item.text : item;
        let selected = selectedVal != null && String(selectedVal) === String(id);
        if (selected) {
            foundSel = true;
        }
        $select.append(new Option(text, id, false, selected));
    });
    if (!foundSel) {
        $select.val('');
    }
    $select.trigger('change.select2');
}

function markSelect2Invalid($select, msg){
    const $container = $select.next('.select2').find('.select2-selection');
    $container.addClass('is-invalid');
    if ($container.parent().next('.mi-row-error').length === 0) {
        $container.parent().after('<div class="mi-row-error">'+msg+'</div>');
    }
}

function clearSelect2Invalid($select){
    const $container = $select.next('.select2').find('.select2-selection');
    $container.removeClass('is-invalid');
    $container.parent().next('.mi-row-error').remove();
}

function markInputInvalid($input, msg){
    $input.addClass('is-invalid');
    if ($input.next('.mi-row-error').length === 0) {
        $input.after('<div class="mi-row-error">'+msg+'</div>');
    }
}

function clearInputInvalid($input){
    $input.removeClass('is-invalid');
    $input.next('.mi-row-error').remove();
}

function fieldByName(name){
    return $('[name="'+name+'"]');
}

var skladUrls = window.__SKLAD_URLS__ || {};

function fetchCategories($row, brandId, selectedCategory){
    if (!brandId) {
        fillSelect($row.find('select.mi-category'), [], null);
        fillSelect($row.find('select.mi-size'), [], null);
        fillSelect($row.find('select.mi-type'), [], null);
        return;
    }
    $.getJSON(skladUrls.cats, {brand_id: brandId}, function(res){
        fillSelect($row.find('select.mi-category'), res.categories || [], selectedCategory);
        fillSelect($row.find('select.mi-size'), [], null);
        fillSelect($row.find('select.mi-type'), [], null);
    });
}

function fetchSizesOnly($row, brandId, categoryId, selectedSize){
    if (!brandId || !categoryId) {
        fillSelect($row.find('select.mi-size'), [], null);
        fillSelect($row.find('select.mi-type'), [], null);
        return;
    }
    $.getJSON(skladUrls.sizes, {brand_id: brandId, category_id: categoryId}, function(res){
        fillSelect($row.find('select.mi-size'), res.sizes || [], selectedSize);
        fillSelect($row.find('select.mi-type'), [], null);
    });
}

function fetchTypesBySize($row, brandId, categoryId, size, selectedType){
    if (!brandId || !categoryId || !size) {
        fillSelect($row.find('select.mi-type'), [], null);
        return;
    }
    $.getJSON(skladUrls.types, {brand_id: brandId, category_id: categoryId, size: size}, function(res){
        fillSelect($row.find('select.mi-type'), res.types || [], selectedType);
        if ((res.types || []).length === 1 && selectedType == null) {
            $row.find('select.mi-type').val(String(res.types[0].id)).trigger('change');
        }
    });
}

$(document).on('change', 'select.mi-brand', function(){
    fetchCategories($(this).closest('tr'), $(this).val(), null);
    clearSelect2Invalid($(this));
});

$(document).on('change', 'select.mi-category', function(){
    const $row = $(this).closest('tr');
    fetchSizesOnly($row, $row.find('select.mi-brand').val(), $(this).val(), null);
    clearSelect2Invalid($(this));
});

$(document).on('change', 'select.mi-size', function(){
    const $row = $(this).closest('tr');
    fetchTypesBySize($row, $row.find('select.mi-brand').val(), $row.find('select.mi-category').val(), $(this).val(), null);
    clearSelect2Invalid($(this));
});

$(document).on('change', 'select.mi-type', function(){
    clearSelect2Invalid($(this));
});

$(document).on('input change', '[name="Sklad[cr_date]"], [name="Sklad[comments]"], [name="Sklad[sum_dollar]"], .target, .mi-price', function(){
    clearInputInvalid($(this));
});

$('#order-form').on('submit', function(e){
    let hasError = false;
    $('.mi-row-error').remove();
    $('.select2-selection').removeClass('is-invalid');
    $('.is-invalid').removeClass('is-invalid');

    ['Sklad[cr_date]', 'Sklad[comments]', 'Sklad[sum_dollar]'].forEach(function(name){
        const $input = fieldByName(name);
        if ($input.length && $.trim($input.val()) === '') {
            hasError = true;
            markInputInvalid($input, 'Bu maydon to\'ldirilishi shart');
        }
    });

    $('#my_id').find('tr.multiple-input-list__item').each(function(){
        const $row = $(this);
        const $brand = $row.find('select.mi-brand');
        const $category = $row.find('select.mi-category');
        const $size = $row.find('select.mi-size');
        const $type = $row.find('select.mi-type');
        const $count = $row.find('input[name*="[count]"]');
        const $price = $row.find('input[name*="[price]"]');

        if ($brand.length && !$brand.val()) { hasError = true; markSelect2Invalid($brand, 'Majburiy maydon'); }
        if ($category.length && !$category.val()) { hasError = true; markSelect2Invalid($category, 'Majburiy maydon'); }
        if ($size.length && !$size.val()) { hasError = true; markSelect2Invalid($size, 'Majburiy maydon'); }
        if ($type.length && !$type.val()) { hasError = true; markSelect2Invalid($type, 'Majburiy maydon'); }

        const countText = ($count.val() || '').trim();
        const count = parseFloat(countText.replace(',', '.'));
        if (countText === '' || isNaN(count) || count <= 0) {
            hasError = true;
            markInputInvalid($count, 'Soni 0 dan katta bo\'lishi kerak');
        }

        const priceText = ($price.val() || '').trim();
        const price = parseFloat(priceText.replace(',', '.'));
        if (priceText === '' || isNaN(price) || price < 0) {
            hasError = true;
            markInputInvalid($price, 'Qiymat kiriting');
        }
    });

    if (hasError) {
        e.preventDefault();
        const $first = $('.mi-row-error').first();
        if ($first.length) {
            $('html,body').animate({scrollTop: $first.offset().top - 150}, 250);
        }
        return;
    }

    $(this).find(':submit').prop('disabled', true);
});

function initDependentSelects(){
    $('#my_id').find('tr').each(function(){
        const $row = $(this);
        const $brand = $row.find('select.mi-brand');
        const $category = $row.find('select.mi-category');
        const $size = $row.find('select.mi-size');
        const $type = $row.find('select.mi-type');
        if (!$brand.length) {
            return;
        }

        const brandId = $brand.val() || null;
        const categoryId = $category.val() || null;
        const size = $size.val() || null;
        const type = $type.val() || null;

        if (brandId) {
            $.getJSON(skladUrls.cats, {brand_id: brandId}, function(res){
                fillSelect($category, res.categories || [], categoryId);
                if (categoryId) {
                    $.getJSON(skladUrls.sizes, {brand_id: brandId, category_id: categoryId}, function(res2){
                        fillSelect($size, res2.sizes || [], size);
                        if (size) {
                            $.getJSON(skladUrls.types, {brand_id: brandId, category_id: categoryId, size: size}, function(res3){
                                fillSelect($type, res3.types || [], type);
                            });
                        } else {
                            fillSelect($type, [], null);
                        }
                    });
                } else {
                    fillSelect($size, [], null);
                    fillSelect($type, [], null);
                }
            });
        } else {
            fillSelect($category, [], null);
            fillSelect($size, [], null);
            fillSelect($type, [], null);
        }
    });
}

$(document).on('afterAddRow', '#my_id', function(e, row){
    const $row = $(row);
    fillSelect($row.find('select.mi-category'), [], null);
    fillSelect($row.find('select.mi-size'), [], null);
    fillSelect($row.find('select.mi-type'), [], null);
});

$(document).ready(initDependentSelects);

var product_details = {};
$(document).on("change", ".input-priority", function() {
    const attr_name = $(this).attr('name');
    let id = attr_name.match(/\d/g).join("");
    
    const price = parseFloat($("input[name='Sklad[allValue][" + id + "][price]']").val());
    const count = parseInt($("input[name='Sklad[allValue][" + id + "][count]']").val());
    
    if (price && count) {
        product_details[id] = price * count; // mavjud bo'lsa yangilanadi, bo'lmasa qo'shiladi
        // console.log('product_details:', product_details);
        
        // Umumiy qiymatni hisoblash
        const total_sum = Object.values(product_details).reduce((a, b) => a + b, 0);
        $("input[name='Sklad[sum_all_pro]']").val(total_sum);
    } else {
        delete product_details[id];
        const total_sum = Object.values(product_details).reduce((a, b) => a + b, 0);
        $("input[name='Sklad[sum_all_pro]']").val(total_sum);
    }
});


$('#cars').on('change', function(e){
    e.preventDefault();
    const value = $(this).val().toUpperCase();
    console.log("value:", value);
    if (value) {
        $("#showRes .handle").filter(function() {
        const td = $(this).children('td').eq(1).text().toUpperCase();
        $(this).toggle(td === value)
        });

        $("#showRes .handle-header").filter(function() {
            const td = $(this).children('td').eq(0).text().toUpperCase();
            $(this).toggle(td === value)
        });

        $("#showRes tr").filter(function() {
            const className = $(this).attr('class').toUpperCase();
            if(className.indexOf('AMOUNT') > -1){
                $(this).toggle(className == value + '-AMOUNT')
            }
        });
    }else{
        $("#showRes tr").filter(function() {
        $(this).show()
        });
    }
});

JS
) ?>


