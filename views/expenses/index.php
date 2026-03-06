<?php
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExpensesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $totalSum float */

$this->title = 'Xarajatlar';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>

<div class="panel panel-inverse user-index">
    <div class="panel-heading">
        <div class="panel-heading-btn">
            <a href="/expenses/create" role="modal-remote" class="btn btn-xs btn-success">
                Qo'shish <i class="fa fa-plus"></i>
            </a>
            <a href="javascript:;" title="Во весь экран" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand">
                <i class="fa fa-expand"></i>
            </a>
            <a href="javascript:;" title="Обновить" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload">
                <i class="fa fa-repeat"></i>
            </a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse">
                <i class="fa fa-minus"></i>
            </a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove">
                <i class="fa fa-times"></i>
            </a>
        </div>
        <h4 class="panel-title">Roʻyxat</h4>
    </div>

    <div class="panel-body">

        <?php Pjax::begin([
            'id' => 'crud-datatable-pjax',
            'timeout' => 5000,
            'enablePushState' => false,
        ]); ?>

            <?= $this->render('_search', ['model' => $searchModel]) ?>

            <div class="row" style="margin-bottom: 15px;">
                <div class="col-md-12">
                    <div class="alert alert-warning" style="margin-bottom: 0;">
                        <strong><b style=" font-size: 16px;">Umumiy xarajat summasi:</b></strong>
                        <b style="color:red; font-size: 16px;"><?= Yii::$app->formatter->asDecimal($totalSum, 0) ?> $</b>
                    </div>
                </div>
            </div>

            <div id="ajaxCrudDatatable">
                <?= GridView::widget([
                    'id' => 'crud-datatable',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'pjax' => false, // MUHIM: tashqarida Pjax bor, ichida false bo'lsin
                    'columns' => require(__DIR__ . '/_columns.php'),
                    'striped' => true,
                    'condensed' => true,
                    'responsive' => true,
                    'pager' => [
                        'firstPageLabel' => 'Birinchi',
                        'lastPageLabel'  => 'Oxirgi'
                    ],
                    'responsiveWrap' => false,
                    'panelBeforeTemplate' => false,
                    'panel' => [
                        'headingOptions' => ['style' => 'display: none;'],
                        'after' => '<div class="clearfix"></div>',
                    ],
                ]) ?>
            </div>

        <?php Pjax::end(); ?>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",
]) ?>
<?php Modal::end(); ?>