<?php

use app\models\Users;
use app\models\OrderAccount;
use app\models\Brands;
use app\models\Client;
use app\models\DebtRepayment;
use app\models\OrderAccountHistory;

use dosamigos\chartjs\ChartJs;
$this->title = Yii::$app->name;

$user_count =  Users::find()->count();
$productCategory_count = OrderAccount::find()->andWhere(['or',
        ['!=', 'is_worker', 1],
        ['is', 'is_worker', null]
    ])->andWhere(['>', 'total_debt', 0])->orderBy([ 'total_debt' => SORT_DESC,])->count();

$brand_count =  Brands::find()->count();
$order_count =  Client::find()->count();

$results = OrderAccountHistory::find()
    ->where(['or',
        ['!=', 'is_worker', 1],
        ['is', 'is_worker', null]
    ])
    ->all();

$results_jan = 0;
$results_feb = 0;
$results_mar = 0;
$results_apr = 0;
$results_may = 0;
$results_jun = 0;
$results_jul = 0;
$results_aug = 0;
$results_sep = 0;
$results_oct = 0;
$results_nov = 0;
$results_dec = 0;

for($i=0; $i < count($results); $i++){
    if(date("y", strtotime($results[$i]['date'])) == date("y") and date("m", strtotime($results[$i]['date'])) == 1 ){
        $results_jan = round($results_jan + $results[$i]['all_summ_dollar'], 2);     
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 2 ){
        $results_feb = round($results_feb + $results[$i]['all_summ_dollar'], 2);    
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 3 ){
        $results_mar = round($results_mar + $results[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 4 ){
        $results_apr = round($results_apr + $results[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 5 ){
        $results_may = round($results_may + $results[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 6 ){
        $results_jun = round($results_jun + $results[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 7 ){
        $results_jul = round($results_jul + $results[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 8 ){
        $results_aug = round($results_aug + $results[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 9 ){
        $results_sep = round($results_sep + $results[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 10 ){
        $results_oct = round($results_oct + $results[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 11 ){
        $results_nov = round($results_nov + $results[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($results[$i]['cr_date'])) == date("y") and date("m", strtotime($results[$i]['cr_date'])) == 12 ){
        $results_dec = round($results_dec + $results[$i]['all_summ_dollar'], 2);  
    }
}

$orderResults = DebtRepayment::find()
    ->where(['or',
        ['or',
        ['!=', 'is_worker', 1],
        ['is', 'is_worker', null]
    ],
        ['is', 'is_worker', null]
    ])
    ->all();

$orderResults_jan = 0;
$orderResults_feb = 0;
$orderResults_mar = 0;
$orderResults_apr = 0;
$orderResults_may = 0;
$orderResults_jun = 0;
$orderResults_jul = 0;
$orderResults_aug = 0;
$orderResults_sep = 0;
$orderResults_oct = 0;
$orderResults_nov = 0;
$orderResults_dec = 0;
for($i=0; $i < count($orderResults); $i++){
    if(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 1 ){
        $orderResults_jan = round($orderResults_jan + $orderResults[$i]['all_summ_dollar'], 2);      
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 2 ){
        $orderResults_feb = round($orderResults_feb + $orderResults[$i]['all_summ_dollar'], 2);    
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 3 ){
        $orderResults_mar = round($orderResults_mar + $orderResults[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 4 ){
        $orderResults_apr = round($orderResults_apr + $orderResults[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 5 ){
        $orderResults_may = round($orderResults_may + $orderResults[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 6 ){
        $orderResults_jun = round($orderResults_jun + $orderResults[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 7 ){
        $orderResults_jul = round($orderResults_jul + $orderResults[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 8 ){
        $orderResults_aug = round($orderResults_aug + $orderResults[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 9 ){
        $orderResults_sep = round($orderResults_sep + $orderResults[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 10 ){
        $orderResults_oct = round($orderResults_oct + $orderResults[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 11 ){
        $orderResults_nov = round($orderResults_nov + $orderResults[$i]['all_summ_dollar'], 2);  
    }elseif(date("y", strtotime($orderResults[$i]['date'])) == date("y") and date("m", strtotime($orderResults[$i]['date'])) == 12 ){
        $orderResults_dec = round($orderResults_dec + $orderResults[$i]['all_summ_dollar'], 2);  
    }
}

// Register custom CSS
$this->registerCss(<<<CSS
/* Modern Dashboard Styles */
.dashboard-container {
    padding: 25px 15px;
    background: #f4f6f9;
    min-height: 100vh;
}

.dashboard-header {
    margin-bottom: 25px;
}

.dashboard-header h1 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
}

.dashboard-header p {
    font-size: 1rem;
    color: #7f8c8d;
    display: none;
}

/* Modern Stats Cards */
.stats-card {
    background: #fff;
    border-radius: 12px;
    padding: 20px 18px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: none;
    min-height: 145px;
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    opacity: 0.08;
    transition: all 0.3s ease;
}

.stats-card:hover::before {
    transform: scale(1.1);
}

.stats-card.purple::before {
    background: #9b59b6;
}

.stats-card.red::before {
    background: #e74c3c;
}

.stats-card.green::before {
    background: #27ae60;
}

.stats-card.blue::before {
    background: #3498db;
}

.stats-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    color: #fff;
    margin-bottom: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}

.stats-card.purple .stats-icon {
    background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
}

.stats-card.red .stats-icon {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
}

.stats-card.green .stats-icon {
    background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
}

.stats-card.blue .stats-icon {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
}

.stats-info h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #7f8c8d;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stats-info .stats-number {
    font-size: 2.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 8px;
    line-height: 1.1;
}

.stats-link a {
    color: #95a5a6;
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.stats-link a:hover {
    color: #2c3e50;
    gap: 8px;
}

/* Modern Chart Panels */
.chart-panel {
    background: #fff;
    border-radius: 12px;
    padding: 25px 20px;
    margin-bottom: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border: none;
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e8e8e8;
}

.chart-header h4 {
    font-size: 1.15rem;
    font-weight: 600;
    color: #2c3e50;
    margin: 0;
    line-height: 1.4;
}

.chart-controls {
    display: flex;
    gap: 8px;
}

.chart-controls .btn {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    transition: all 0.2s ease;
    font-size: 14px;
}

.chart-controls .btn-expand {
    background: #3498db;
    color: #fff;
}

.chart-controls .btn-collapse {
    background: #f39c12;
    color: #fff;
}

.chart-controls .btn-remove {
    background: #e74c3c;
    color: #fff;
}

.chart-controls .btn:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.chart-body {
    padding: 15px 0;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stats-card {
    animation: fadeInUp 0.4s ease-out;
}

.stats-card:nth-child(1) { animation-delay: 0.05s; }
.stats-card:nth-child(2) { animation-delay: 0.1s; }
.stats-card:nth-child(3) { animation-delay: 0.15s; }
.stats-card:nth-child(4) { animation-delay: 0.2s; }

.chart-panel {
    animation: fadeInUp 0.4s ease-out 0.25s both;
}

/* Responsive Design */
@media (min-width: 1200px) {
    .stats-info h4 {
        font-size: 1.05rem;
    }
    
    .stats-info .stats-number {
        font-size: 3rem;
    }
    
    .chart-header h4 {
        font-size: 1.2rem;
    }
}

@media (max-width: 991px) {
    .stats-card {
        min-height: 135px;
    }
    
    .stats-info .stats-number {
        font-size: 2.5rem;
    }
}

@media (max-width: 768px) {
    .dashboard-header h1 {
        font-size: 1.5rem;
    }
    
    .stats-card {
        padding: 18px;
        min-height: 125px;
    }
    
    .stats-icon {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
    
    .stats-info h4 {
        font-size: 0.9rem;
    }
    
    .stats-info .stats-number {
        font-size: 2.2rem;
    }
    
    .chart-header {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
    
    .chart-header h4 {
        font-size: 1rem;
    }
}
CSS
);
?>

<div class="dashboard-container">
    <!-- Stats Cards Row -->
    <div class="row">
        <!-- Mijozlar Card -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stats-card purple">
                <div class="stats-icon">
                    <i class="fa fa-handshake-o"></i>
                </div>
                <div class="stats-info">
                    <h4>Mijozlar</h4>
                    <div class="stats-number"><?= $order_count ?></div>
                </div>
                <div class="stats-link">
                    <a href="/client/index">
                        Ko'proq <i class="fa fa-arrow-circle-o-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Qarzdorlar Card -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stats-card red">
                <div class="stats-icon">
                    <i class="fa fa-info-circle"></i>
                </div>
                <div class="stats-info">
                    <h4>Qarzdorlar</h4>
                    <div class="stats-number"><?= $productCategory_count ?></div>
                </div>
                <div class="stats-link">
                    <a href="/order-account/debtors">
                        Ko'proq <i class="fa fa-arrow-circle-o-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Modellar Card -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stats-card green">
                <div class="stats-icon">
                    <i class="fa fa-bookmark"></i>
                </div>
                <div class="stats-info">
                    <h4>Modellar</h4>
                    <div class="stats-number"><?= $brand_count ?></div>
                </div>
                <div class="stats-link">
                    <a href="/brands/index">
                        Ko'proq <i class="fa fa-arrow-circle-o-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Foydalanuvchilar Card -->
        <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="stats-card blue">
                <div class="stats-icon">
                    <i class="fa fa-users"></i>
                </div>
                <div class="stats-info">
                    <h4>Foydalanuvchilar</h4>
                    <div class="stats-number"><?= $user_count ?></div>
                </div>
                <div class="stats-link">
                    <a href="/users/index">
                        Ko'proq <i class="fa fa-arrow-circle-o-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- To'langan mahsulotlar Chart -->
        <div class="col-md-6">
            <div class="chart-panel">
                <div class="chart-header">
                    <h4><i class="fa fa-bar-chart"></i> <?= date("Y") ?> - yil uchun To'langan mahsulotlar summasi oylar kesimida</h4>
        
                </div>
                <div class="chart-body">
                    <?= ChartJs::widget([
                        'type' => 'bar',
                        'data' => [ 
                            'labels' => ["Yanvar", "Fevral", "Mart", "Aprel", "May", "Iyun", "Iyul", "Avgust", "Sentyabr", "Oktyabr", "Noyabr", "Dekabr"],
                            'datasets' => [[ 
                                'label' => 'To\'langan mahsulotlar summasi ($)',
                                'backgroundColor' => 'rgba(39, 174, 96, 0.8)',
                                'borderColor' => 'rgba(39, 174, 96, 1)',
                                'borderWidth' => 2,
                                'borderRadius' => 8,
                                'data' => [$results_jan, $results_feb, $results_mar, $results_apr, $results_may, $results_jun, $results_jul, $results_aug, $results_sep, $results_oct, $results_nov, $results_dec]
                            ]]
                        ],
                        'options' => [
                            'responsive' => true,
                            'maintainAspectRatio' => false,
                            'height' => 220,
                            'plugins' => [
                                'legend' => [
                                    'display' => false,
                                ],
                            ],
                            'scales' => [
                                'yAxes' => [[
                                    'display' => true,
                                    'ticks' => [
                                        'suggestedMin' => 1,
                                        'beginAtZero' => true,
                                        'fontSize' => 13,
                                    ],
                                    'gridLines' => [
                                        'color' => 'rgba(0, 0, 0, 0.05)',
                                    ]
                                ]],
                                'xAxes' => [[
                                    'ticks' => [
                                        'fontSize' => 12,
                                    ],
                                    'gridLines' => [
                                        'display' => false,
                                    ]
                                ]]
                            ]
                        ],
                    ]);?>
                </div>
            </div>
        </div>

        <!-- To'langan qarzlar Chart -->
        <div class="col-md-6">
            <div class="chart-panel">
                <div class="chart-header">
                    <h4><i class="fa fa-line-chart"></i> <?= date("Y") ?> - yil uchun To'langan qarzlar summasi oylar kesimida</h4>
     
                </div>
                <div class="chart-body">
                    <?= ChartJs::widget([
                        'type' => 'bar',
                        'data' => [ 
                            'labels' => ["Yanvar", "Fevral", "Mart", "Aprel", "May", "Iyun", "Iyul", "Avgust", "Sentyabr", "Oktyabr", "Noyabr", "Dekabr"],
                            'datasets' => [[
                                'label' => 'To\'langan qarzlar summasi ($)',
                                'backgroundColor' => 'rgba(231, 76, 60, 0.8)',
                                'borderColor' => 'rgba(231, 76, 60, 1)',
                                'borderWidth' => 2,
                                'borderRadius' => 8,
                                'data' => [$orderResults_jan, $orderResults_feb, $orderResults_mar, $orderResults_apr, $orderResults_may, $orderResults_jun, $orderResults_jul, $orderResults_aug, $orderResults_sep, $orderResults_oct, $orderResults_nov, $orderResults_dec]
                            ]]
                        ],
                        'options' => [
                            'responsive' => true,
                            'maintainAspectRatio' => false,
                            'height' => 220,
                            'plugins' => [
                                'legend' => [
                                    'display' => false,
                                ],
                            ],
                            'scales' => [
                                'yAxes' => [[
                                    'display' => true,
                                    'ticks' => [
                                        'suggestedMin' => 1,
                                        'beginAtZero' => true,
                                        'fontSize' => 13,
                                    ],
                                    'gridLines' => [
                                        'color' => 'rgba(0, 0, 0, 0.05)',
                                    ]
                                ]],
                                'xAxes' => [[
                                    'ticks' => [
                                        'fontSize' => 12,
                                    ],
                                    'gridLines' => [
                                        'display' => false,
                                    ]
                                ]]
                            ]
                        ],
                    ]);?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJsFile('/js/cookie.js');

$this->registerJs(<<<JS

$(document).ready(function(){

    active_chart = getCookie('type-chart');
    active_chart_users = getCookie('type-chart_users');
    if(!active_chart || active_chart == 'undefined')
        active_chart = 'line';
    if(!active_chart_users || active_chart_users == 'undefined')
        active_chart_users = 'line';
   
    
    $('#items_'+active_chart).removeClass('hide').addClass('show');
    $('#click_item_'+active_chart).addClass('active');
    $('#users_'+active_chart_users).removeClass('hide').addClass('show');
    $('#click_users_'+active_chart_users).addClass('active');

    $('#click_item_line').click(function(){
        setCookie('type-chart','line');
        $('#items_line').removeClass('hide').addClass('show');
        $('#items_bar').removeClass('show').addClass('hide');
        $(this).addClass('active');
    });
    $('#click_item_bar').click(function(){
        setCookie('type-chart','bar');
        $('#items_bar').removeClass('hide').addClass('show');
        $('#items_line').removeClass('show').addClass('hide');
        $(this).addClass('active');
    });
    
    $('#click_users_line').click(function(){
        setCookie('type-chart_users','line');
        $('#users_line').removeClass('hide').addClass('show');
        $('#users_bar').removeClass('show').addClass('hide');
        $(this).addClass('active');
    });
    $('#click_users_bar').click(function(){
        setCookie('type-chart_users','bar');
        $('#users_bar').removeClass('hide').addClass('show');
        $('#users_line').removeClass('show').addClass('hide');
        $(this).addClass('active');
    });
   
   
    $('.select').click(function(){
        var text = $(this).text() + '<span class="fa fa-caret-down"></span>';
       $('#dropdownMenuLink').html(text);
        active_chart = getCookie('type-chart');
        if(!active_chart || active_chart == 'undefined')
            active_chart = 'line';
        $.post('/statistics/items-chart', {type: $(this).attr("id")}, function(data){ 
            $("#items_chart").html(data);
            $('#items_'+active_chart).removeClass('hide').addClass('show');
        });
    });
    $('.select_user').click(function(){
        var text = $(this).text() + '<span class="fa fa-caret-down"></span>';
       $('#dropdownMenuLinkUsers').html(text);
        active_chart_users = getCookie('type-chart_users');
        if(!active_chart_users || active_chart_users == 'undefined')
            active_chart_users = 'line';
        $.post('/statistics/users-chart', {type: $(this).attr("id")}, function(data){ 
            $("#users_chart").html(data); 
            $('#users_'+active_chart_users).removeClass('hide').addClass('show');
        });
    });
    
});
JS
) ?>
