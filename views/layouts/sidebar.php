<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\models\Users;

$model = Users::findOne(Yii::$app->user->identity->id);

NavBar::begin([
    'brandLabel' => '<i class="fa fa-bar-chart"></i> <strong>Optom Chinni</strong>',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
        'style' => 'border-radius:0;',
    ],
    'brandOptions' => ['style' => 'color:white; font-size:18px'],
    'renderInnerContainer' => true,
    'innerContainerOptions' => ['class' => 'container-fluid'],
]);

$menuItems = [];

// ====== ASOSIY MENYU (sidebar dagi itemlar) ======
$menuItems[] = [
    'label' => 'Ombor mahsulotlari',
    'url' => ['/warehouse/all-list'],
    'visible' => $model && in_array($model->permission, [1,2,3,4,5,6]),
];

$menuItems[] = [
    'label' => 'Buyurtma qilish',
    'url' => ['/order-account/orders'],
    'visible' => $model && in_array($model->permission, [1,2,5,6]),
];

$menuItems[] = [
    'label' => 'Mijoz buyurtmalar tarixi',
    'url' => ['/order-account-history/index'],
    'visible' => $model && in_array($model->permission, [1,2,5,6]),
];

// $menuItems[] = [
//     'label' => 'Mijozdan qarzdorlik',
//     'url' => ['/order-account-history/index-deptor'],
//     'visible' => $model && in_array($model->permission, [1,2,5,6]),
// ];

// $menuItems[] = [
//     'label' => 'Buyurtmalar va qarzlar',
//     'url' => ['/order-account-history/order-and-debt'],
//     'visible' => $model && ($model->permission == 1),
// ];

// $menuItems[] = [
//     'label' => 'Vozvrat',
//     'url' => ['/vozvrat-order/vozvrat'],
//     'visible' => $model && in_array($model->permission, [1,2,5,6]),
// ];

// $menuItems[] = [
//     'label' => 'Vozvrat buyurmalar tarixi',
//     'url' => ['/vozvrat-order/index'],
//     'visible' => $model && in_array($model->permission, [1,2,5,6]),
// ];

$menuItems[] = [
    'label' => 'Mijoz umumiy buyurtmasi',
    'url' => ['/order-account/index'],
    'visible' => $model && in_array($model->permission, [1,2,5,6]),
];

$menuItems[] = [
    'label' => 'Mahsulotlar',
    'url' => ['/warehouse/index'],
    'visible' => $model && in_array($model->permission, [1,2,5]),
];

$menuItems[] = [
    'label' => 'Sklad hisobi',
    'url' => ['/sklad/index'],
    'visible' => $model && in_array($model->permission, [1,2,5,6]),
];

$menuItems[] = [
    'label' => 'Mening qarzlarim',
    'url' => ['/my-total-debt/index'],
    'visible' => $model && ($model->permission == 1),
];

// ====== TIZIM BOSHQARUVI (dropdown) ======
$menuItems[] = [
    'label' => 'Tizim boshqaruvi',
    'url' => '#',
    'items' => [
         [
            'label' => 'Modellar',
            'url' => ['/brands/index'],
            'visible' => $model && in_array($model->permission, [1,6]),
        ],
        [
            'label' => 'Mahsulot toifalari',
            'url' => ['/product-category/index'],
            'visible' => $model && in_array($model->permission, [1,6]),
        ],
        [
            'label' => 'Mahsulot o\'lchami',
            'url' => ['/brands-size/index'],
            'visible' => $model && in_array($model->permission, [1,6]),
        ],
        [
            'label' => 'O\'zgarishlar hisobi',
            'url' => ['/elegant-history-update/index'],
            'visible' => $model && ($model->permission == 1),
        ],
        [
            'label' => 'Foydalanuvchilar',
            'url' => ['/users/index'],
            'visible' => $model && ($model->permission == 1),
        ],
        // [
        //     'label' => 'Foyda va zarar',
        //     'url' => ['/loss-of-profit/index'],
        //     'visible' => $model && ($model->permission == 1),
        // ],
        [
            'label' => 'Xarajatlar',
            'url' => ['/expenses/index'],
            'visible' => $model && ($model->permission == 1),
        ],
        [
            'label' => 'Yuk chiquvchi joy',
            'url' => ['/type-sklad/index'],
            'visible' => $model && ($model->permission == 1),
        ],
        [
            'label' => 'Xarajat turi',
            'url' => ['/type-expense/index'],
            'visible' => $model && ($model->permission == 1),
        ],
        // [
        //     'label' => 'Ombor', 
        //     'url' => ['/warehouse/index'],
        //     'visible' => $model->permission == 1 || $model->permission == 5|| $model->permission == 2 ? true : false,
        // ],
        // [
        //     'label' => 'Sklad tarixi', 
        //     'url' => ['/sklad/index'],
        //     'visible' => $model->permission == 1 || $model->permission == 2 || $model->permission == 5 ? true : false,
        // ],
        [
            'label' => 'Biz haqimizda',
            'url' => ['/about/index'],
            'visible' => $model && ($model->permission == 1),
        ],
    ],
    'visible' => $model && in_array($model->permission, [1,6]),
];

// ====== SOZLAMALAR (dropdown) ======
$menuItems[] = [
    'label' => 'Sozlamalar',
    'url' => '#',
    'items' => [
        [
            'label' => 'Mijozlar',
            'url' => ['/client/index'],
            'visible' => $model && in_array($model->permission, [1,2,6]),
        ],
           [
            'label' => 'Yuk jo\'natuvchilar', 
            'url' => ['/consignor/index'],
            'visible' => $model->permission == 1 || $model->permission == 2  ? true : false,
        ],
        // [
        //     'label' => 'Sotilgan tovarlar tarixi',
        //     'url' => ['/order-account-history/export'],
        //     'visible' => $model && in_array($model->permission, [1,2,6]),
        // ],
        // [
        //     'label' => 'To\'langan qarzlar',
        //     'url' => ['/debt-repayment/index'],
        //     'visible' => $model && in_array($model->permission, [1,2,6]),
        // ],
        // [
        //     'label' => 'Klientlar tarixi',
        //     'url' => ['/order-account-history/client-history'],
        //     'visible' => $model && in_array($model->permission, [1,2,6]),
        // ],
        // [
        //     'label' => 'Kunlik sotilgan tovarlar',
        //     'url' => ['/order-account-history/day-orders'],
        //     'visible' => $model && in_array($model->permission, [1,2]),
        // ],
        [
            'label' => 'Top mijozlar',
            'url' => ['/order-account/top-client'],
            'visible' => $model && ($model->permission == 1),
        ],
        [
            'label' => 'Qarzdorlar ro\'yxati',
            'url' => ['/order-account/debtors'],
            'visible' => $model && in_array($model->permission, [1,2,6]),
        ],
        [
            'label' => 'Mijoz Buyurtma Karzinka',
            'url' => ['/order-account-history/trash-o'],
            'visible' => $model && in_array($model->permission, [1,2,6]),
        ],
        [
            'label' => 'To\'langan Qarz Karzinka',
            'url' => ['/debt-repayment/trash-o'],
            'visible' => $model && in_array($model->permission, [1,2,6]),
        ],
    ],
    'visible' => $model && in_array($model->permission, [1,2,6]),
];

// ====== PROFIL (o'ng tomonda dropdown) ======
$menuItems[] = [
    'label' => '<i class="fa fa-user"></i> ' . Html::encode($model ? $model->getFio() : 'Profil'),
    'items' => [
        // [
        //     'label' => '<i class="fa fa-user"></i> Profil',
        //     'url' => ['/users/view', 'id' => $model ? $model->id : null],
        //     'visible' => (bool)$model,
        // ],
        // '<li class="divider"></li>',
        [
            'label' => '<i class="fa fa-sign-out"></i> Chiqish',
            'url' => ['/site/logout'],
            'linkOptions' => ['data-method' => 'post'],
        ],
    ],
    'encode' => false,
];

echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => array_filter($menuItems, function($item){
        // visible false bo'lsa chiqarib tashlaymiz
        return !isset($item['visible']) || $item['visible'];
    }),
    'encodeLabels' => false,
]);

NavBar::end();
?>

<style>
/* Header menu hover/active style */
.navbar-inverse .navbar-nav > li > a:hover,
.navbar-inverse .navbar-nav > .active > a,
.navbar-inverse .navbar-nav > .open > a,
.navbar-inverse .navbar-nav > .open > a:hover,
.navbar-inverse .navbar-nav > .open > a:focus{
    background-color: #235c80 !important;
    color: #ffffff !important;
    border-radius: 4px;
}

/* Dropdown ichidagi linklar */
.navbar-inverse .dropdown-menu > li > a:hover{
    background-color: #235c80 !important;
    color: #fff !important;
}
</style>
