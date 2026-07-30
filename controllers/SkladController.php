<?php

namespace app\controllers;

use Yii;
use app\models\Sklad;
use app\models\SkladSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use app\models\Warehouse;
use app\models\WarehouseHistory;
use yii\filters\AccessControl;
use app\models\ElegantHistoryUpdate;
use app\models\Consignor;
use app\models\MyTotalDebt;
use app\models\PriceProduct;
use app\models\MyTotalDebtHistory;
use app\models\Prices;
/**
 * SkladController implements the CRUD actions for Sklad model.
 */
class SkladController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['all-list'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return \app\models\Users::isMenejerRight(Yii::$app->user->identity->id);
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Sklad models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new SkladSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProducts($id)
    {    
        $searchModel2 = new SkladSearch(['consignor_id' => $id]);
        $dataProvider = $searchModel2->search2(Yii::$app->request->queryParams);

        return $this->render('index2', [
            'searchModel2' => $searchModel2,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImport()
    {    
        $orders = Sklad::find()->where(['!=', 'actived', 0])->select(['cr_date'])->groupBy(['cr_date'])->orderBy(['cr_date'=>SORT_DESC])->all();
        return $this->render('import', ['orders' => $orders]);
    }

    public function actionImportOrder($cr_date)
    {    
        $orders = Sklad::find()->andWhere(['!=', 'actived', 0])->andWhere(['cr_date' => $cr_date])->orderBy(['cr_date'=>SORT_DESC])->all();
        $sum_dollars = 0;
        $given_sum_dollars = 0;
        $discount_amounts = 0;
        foreach ($orders as $value) {
            $sum_dollars = $sum_dollars + $value['sum_dollar'];
            $given_sum_dollars = $given_sum_dollars + $value['given_sum_dollar'];
            $discount_amounts = $discount_amounts + $value['discount_amount'];
        }

        $debtRepayments = MyTotalDebtHistory::find()->where(['cr_date' => $cr_date])->orderBy(['cr_date'=>SORT_DESC])->all();
        $deb_all_summ_dollars = 0;
        foreach ($debtRepayments as $value) {
            $deb_all_summ_dollars = $deb_all_summ_dollars + $value['all_summ_dollar'];
        }

        return $this->render('import_order', [
            'orders' => $orders, 
            'cr_date' => $cr_date, 

            'sum_dollars' => $sum_dollars, 
            'given_sum_dollars' => $given_sum_dollars, 
            'discount_amounts' => $discount_amounts, 

            'debtRepayments' => $debtRepayments, 
            'deb_all_summ_dollars' => $deb_all_summ_dollars, 
        ]);
    }
    
    public function actionImportView($id, $cr_date)
    {    
        $model = $this->findModel($id); 
        $warehouse = WarehouseHistory::find()->where(['sklad_id' => $id])->select(['brand_id'])->groupBy(['brand_id'])->all();
        $myTotalDebt = MyTotalDebt::find()->where(['consignor_id' => $model->consignor_id])->one();
        return $this->render('import_view', ['warehouse' => $warehouse, 'cr_date' => $cr_date, 'id' => $id, 'model' => $model, 'myTotalDebt' => $myTotalDebt]);
    }

    public function actionSkladView($id, $cr_date)
    {    
        $model = $this->findModel($id); 
        $warehouse = WarehouseHistory::find()->where(['sklad_id' => $id])->select(['brand_id'])->groupBy(['brand_id'])->all();
        $myTotalDebt = MyTotalDebt::find()->where(['consignor_id' => $model->consignor_id])->one();
        return $this->render('sklad_view', ['warehouse' => $warehouse, 'cr_date' => $cr_date, 'id' => $id, 'model' => $model, 'myTotalDebt' => $myTotalDebt]);
    }   

    public function actionPrint($id)
    {
        $model = $this->findModel($id);
        $mpdf = new \Mpdf\Mpdf(['format' => 'A4',]);
        $mpdf->WriteHTML($model->getInvoiceHtmlText($model, $id));
        
        //call watermark content and image
        $mpdf->SetWatermarkText('');
        $mpdf->showWatermarkText = true;
        $mpdf->watermarkTextAlpha = 0.1;

        //save the file put which location you need folder/filname
        $mpdf->Output("ClientOrderList.pdf", 'F');
        //out put in browser below output function
        $mpdf->Output();
    }
    
    public function actionSkladView2($id, $cr_date)
    {    
        $warehouse = WarehouseHistory::find()->where(['sklad_id' => $id])->select(['brand_id'])->groupBy(['brand_id'])->all();
        return $this->render('sklad_view2', ['warehouse' => $warehouse, 'cr_date' => $cr_date, 'id' => $id]);
    }
    /**
     * Displays a single Sklad model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Sklad #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionQarzs($id){
        $request = Yii::$app->request;
        // $client_id = $request->get('client_id');
        $myTotalDebt = MyTotalDebt::find()->where(['consignor_id' => $id])->one();
        
        $qarz_sum = 0;
        if ($myTotalDebt) {
            $qarz_sum = $myTotalDebt->total_debt;
        }
        return $qarz_sum;
    }

    public function actionQarzss($id=17){
        $request = Yii::$app->request;
        // $client_id = $request->get('client_id');
        $myTotalDebt = MyTotalDebt::find()->where(['consignor_id' => $id])->one();
        
        $qarz_sum = 0;
        if ($myTotalDebt) {
            $qarz_sum = $myTotalDebt->total_debt;
        }
        return $qarz_sum;
    }

    public function actionImportCheck($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id); 
        $model->status = 2;
        $model->save(false);

        // $priceProducts = PriceProduct::find()->all();

        // foreach ($priceProducts as $priceProduct) {
        //     // Warehouse jadvaliga yangi yozuv qo'shish
        //     $warehouse = new Warehouse();
        //     // $warehouse->id = $priceProduct->id;
        //     $warehouse->brand_id = $priceProduct->brand_id;
        //     $warehouse->product_category_id = $priceProduct->product_category_id;
        //     $warehouse->size = $priceProduct->size;
        //     $warehouse->price = $priceProduct->real_price;
        //     $warehouse->count = 1000;
        //     $warehouse->cr_date = $priceProduct->cr_date;
        //     $warehouse->type = $priceProduct->type;
            
        //     // Qo'shimcha ustunlarga qiymat berish
        //     $warehouse->created_by = 'admin'; // Misol uchun, admin deb belgilayapmiz
        //     $warehouse->update_by = 'admin';
        //     $warehouse->consignor_id = 1; // Yuk jo'natuvchi ID sini 1 deb belgilayapmiz
        //     $warehouse->all_sum_dollar = 0; // Boshlang'ich qiymat
        //     $warehouse->all_discount_amount = 0; // Boshlang'ich qiymat
        //     $warehouse->all_my_total_debt = 0; // Boshlang'ich qiymat
        //     $warehouse->comment = 'PriceProduct dan kiritildi';

        //     // Warehouse jadvaliga saqlash
        //     $warehouse->save(false);
        // }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

    /**
     * Creates a new Sklad model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $sklad = new Sklad();
        
        if ($sklad->load(Yii::$app->request->post()) ) {
            $post = Yii::$app->request->post();
            $data = isset($post['Sklad']) ? $post['Sklad'] : [];
            $importProducts = $this->normalizeProductRows(isset($data['allValue']) ? $data['allValue'] : []);
            $consignor_id = isset($data['consignor_id']) ? (int)$data['consignor_id'] : 0;
            $dates = isset($data['dates']) ? $data['dates'] : null;
            $exchange_rates = isset($data['exchange_rates']) ? (int)$data['exchange_rates'] : 0;
            $sum_dollars = isset($data['sum_dollars']) ? (float)$data['sum_dollars'] : 0;
            $discount_amounts = isset($data['discount_amounts']) ? (float)$data['discount_amounts'] : 0;
            $comments = isset($data['comments']) ? $data['comments'] : '';

            $consignor = Consignor::findOne($consignor_id);
            if (!$consignor) {
                throw new \yii\web\BadRequestHttpException('Yuk jo\'natuvchi topilmadi.');
            }
            if (!$dates || strtotime($dates) === false) {
                throw new \yii\web\BadRequestHttpException('Sana noto\'g\'ri kiritilgan.');
            }
            if (trim($comments) === '') {
                throw new \yii\web\BadRequestHttpException('Izoh kiritilishi shart.');
            }
            if ($sum_dollars < 0 || $discount_amounts < 0) {
                throw new \yii\web\BadRequestHttpException('Summa va chegirma manfiy bo\'lmasligi kerak.');
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $myTotalDebt = $this->findOrCreateMyTotalDebt($consignor->id);
                if ($myTotalDebt->isNewRecord) {
                    $this->saveOrFail($myTotalDebt, 'Umumiy qarz yaratilmadi.');
                }
                $this->lockMyTotalDebtForUpdate($myTotalDebt->id);
                $myTotalDebt = MyTotalDebt::findOne($myTotalDebt->id);
                $oldDebt = $this->calculateConsignorTotalDebt($consignor->id, $myTotalDebt->id);
                $given_sum_dollars = $this->calculateProductsTotal($importProducts);
                $debtDelta = $given_sum_dollars - $sum_dollars - $discount_amounts;

                $sklad->created_by = Yii::$app->user->identity->id;
                $sklad->comment = $comments;
                $sklad->given_sum_dollar = $given_sum_dollars;
                $sklad->sum_dollar = $sum_dollars;
                $sklad->exchange_rate = $exchange_rates;
                $sklad->discount_amount = $discount_amounts;
                $sklad->my_total_debt = $debtDelta;
                $sklad->old_my_total_debt = $oldDebt;
                $sklad->cr_date_time = date('Y-m-d H:i:s');
                $sklad->cr_date = date('Y-m-d', strtotime($dates));
                $sklad->consignor_id = $consignor->id;
                $sklad->status = 1;
                $sklad->actived = 0;
                $this->saveOrFail($sklad, 'Kirim hujjati saqlanmadi.');
                $this->refreshConsignorDebtSnapshots($consignor->id, $myTotalDebt->id);

                foreach ($importProducts as $value) {
                    $warehouse = $this->findWarehouseByProductRow($value);
                    if (!$warehouse) {
                        $warehouse = new Warehouse();
                        $warehouse->brand_id = $value['brand_id'];
                        $warehouse->product_category_id = $value['product_category_id'];
                        $warehouse->size = $value['size'];
                        $warehouse->type = $value['type'];
                        $warehouse->count = 0;
                    }

                    $warehouse->price = $value['price'];
                    $warehouse->all_my_total_debt = 0;
                    $warehouse->all_sum_dollar = 0;
                    $warehouse->all_discount_amount = 0;
                    $warehouse->count = (float)$warehouse->count + (float)$value['count'];
                    $warehouse->cr_date = date('Y-m-d', strtotime($dates));
                    $warehouse->save(false);

                    $this->saveWarehouseHistory($sklad->id, $value);
                    $this->savePrice($warehouse->id, $value['price']);
                }

                $myTotalDebt->total_debt = $this->calculateConsignorTotalDebt($consignor->id, $myTotalDebt->id);
                $myTotalDebt->update_by = Yii::$app->user->identity->id;
                $myTotalDebt->cr_date = date('Y-m-d H:i:s');
                $this->saveOrFail($myTotalDebt, 'Umumiy qarz yangilanmadi.');

                $transaction->commit();
                return $this->redirect(['warehouse/index']);
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('create', [
            'model' => $sklad,
        ]);
    }

    public function actionCreateProduct()
    {
        $model = new Sklad();
        
        if ($model->load(Yii::$app->request->post()) ) {
            $post = Yii::$app->request->post();
            
            $importProducts = $post['Sklad']['allValue'];
           
            $dates = $post['Sklad']['dates'];
            $exchange_rates = $post['Sklad']['exchange_rates'];
            $given_sum_dollars = 0;
            $sum_dollars = 0;
            $discount_amounts = 0;
            $comments = $post['Sklad']['comments'];
            
            $sklad = new Sklad();
            $sklad->created_by = Yii::$app->user->identity->id;
            $sklad->comment = $comments;
            $sklad->given_sum_dollar = 0;
            $sklad->sum_dollar = $sum_dollars;
            $sklad->exchange_rate = $exchange_rates;
            $sklad->discount_amount = $discount_amounts;
            $sklad->my_total_debt = 0;
            $sklad->cr_date_time = date('Y-m-d H:i:s');
            $sklad->cr_date = date('Y-m-d',strtotime($dates));
            $sklad->status = 1;
            $sklad->actived = 0;
            $sklad->save(false);


            foreach ($importProducts as $value) {
                

                $warehouse = Warehouse::find()
                                ->andWhere(['brand_id' => (int)$value['brand_id']])
                                ->andWhere(['product_category_id' => (int)$value['product_category_id']])
                                ->andWhere(['type' => $value['type']])
                                ->andWhere(['size' => (float)$value['size']])->one();
                
                if (!$warehouse) {
                    $relative = new Warehouse();
                    $relative->brand_id = $value['brand_id'];
                    $relative->product_category_id = $value['product_category_id'];
                    $relative->size = $value['size'];
                    $relative->count = 0;
                    $relative->price = $value['price'];
                    $relative->type = $value['type'];

                    $relative->all_my_total_debt = 0;
                    $relative->all_sum_dollar = 0;
                    $relative->all_discount_amount = 0;

                    $relative->cr_date = date('Y-m-d',strtotime($dates));
                    $relative->save(false);

                    $relativeHistory = new WarehouseHistory();
                    $relativeHistory->sklad_id = $sklad->id;
                    $relativeHistory->brand_id = $value['brand_id'];
                    $relativeHistory->product_category_id = $value['product_category_id'];
                    $relativeHistory->size = $value['size'];
                    $relativeHistory->type = $value['type'];
                    $relativeHistory->price = $value['price'];
                    $relativeHistory->count = 0;
                    $relativeHistory->cr_date = date('Y-m-d H:i:s');
                    $relativeHistory->cr_date_time = date('Y-m-d H:i:s');
                    $relativeHistory->save(false);
                    $error = $relativeHistory->errors;

                    $modelPrices = new Prices();  
                    $modelPrices->warehouse_id = $relative->id;
                    $modelPrices->price = $value['price'];
                    $modelPrices->save();

                }else {
                    $warehouse->price = $value['price'];
                    $warehouse->all_my_total_debt = 0;
                    $warehouse->all_sum_dollar =0;
                    $warehouse->all_discount_amount = 0;
                    $warehouse->count = $warehouse->count + 0;
                    $warehouse->save(false);
                    // echo '<pre>';
                    // print_r($value['price']);
                    // echo '</pre>';

                    $relativeHistory = new WarehouseHistory();
                    $relativeHistory->sklad_id = $sklad->id;
                    $relativeHistory->brand_id = $value['brand_id'];
                    $relativeHistory->product_category_id = $value['product_category_id'];
                    $relativeHistory->size = $value['size'];
                    $relativeHistory->count = 0;
                    $relativeHistory->type = $value['type'];
                    $relativeHistory->price = $value['price'];
                    $relativeHistory->cr_date = date('Y-m-d H:i:s');
                    $relativeHistory->save(false);
                    $error = $relativeHistory->errors;
                    
                    $price = Prices::find()->where(['warehouse_id' => $warehouse->id])->one();
                    if ($price) {
                        $price->warehouse_id = $warehouse->id;
                        $price->price = $value['price'];
                        $price->save(false);
                    }else {
                        $modelPrices = new Prices();  
                        $modelPrices->warehouse_id = $warehouse->id;
                        $modelPrices->price = $value['price'];
                        $modelPrices->save();
                    }
                    
                }
                    
            }
            return $this->redirect(['warehouse/index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing Sklad model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);  
             

        $consignor_id = $model->consignor_id;
        $my_total_debt_old = $model->my_total_debt;
        $date_old = $model->cr_date;
        $exchange_rate_old = $model->exchange_rate;

        $given_sum_dollar_old = $model->given_sum_dollar;
        $sum_dollar_old = $model->sum_dollar;
        $discount_amount_old = $model->discount_amount;

        $consignor = Consignor::findOne($consignor_id);
        if (!$consignor) {
            throw new NotFoundHttpException('Yuk jo\'natuvchi topilmadi.');
        }
        $myTotalDebt = $this->findOrCreateMyTotalDebt($consignor->id);
        

        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $data = isset($post['Sklad']) ? $post['Sklad'] : [];
            $datees_new = isset($data['cr_date']) ? $data['cr_date'] : null;
            $exchange_rate_new = isset($data['exchange_rate']) ? (int)$data['exchange_rate'] : 0;
            $sum_dollar_new = isset($data['sum_dollar']) ? (float)$data['sum_dollar'] : 0;
            $discount_amount_new = isset($data['discount_amount']) ? (float)$data['discount_amount'] : 0;
            $updateReason = isset($data['comments']) ? $data['comments'] : '';
            $allValues_new = $this->normalizeProductRows(isset($data['allValue']) ? $data['allValue'] : []);
            $given_sum_dollar_new = $this->calculateProductsTotal($allValues_new);
            $newDebtDelta = $given_sum_dollar_new - $sum_dollar_new - $discount_amount_new;

            if (!$datees_new || strtotime($datees_new) === false) {
                throw new \yii\web\BadRequestHttpException('Sana noto\'g\'ri kiritilgan.');
            }
            if (trim($updateReason) === '') {
                throw new \yii\web\BadRequestHttpException('Izoh kiritilishi shart.');
            }
            if ($sum_dollar_new < 0 || $discount_amount_new < 0) {
                throw new \yii\web\BadRequestHttpException('Summa va chegirma manfiy bo\'lmasligi kerak.');
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Lock the edited document and the consignor total before reading old values.
                $this->lockSkladForUpdate($id);
                $lockedSklad = $this->findModel($id);
                $oldDebtDelta = (float)$lockedSklad->my_total_debt;

                $myTotalDebt = $this->findOrCreateMyTotalDebt($consignor->id);
                if ($myTotalDebt->isNewRecord) {
                    $this->saveOrFail($myTotalDebt, 'Umumiy qarz yaratilmadi.');
                }
                $this->lockMyTotalDebtForUpdate($myTotalDebt->id);
                $myTotalDebt = MyTotalDebt::findOne($myTotalDebt->id);
                $oldDebtTotal = $this->calculateConsignorTotalDebt($consignor->id, $myTotalDebt->id);
                $newDebtTotal = $oldDebtTotal - $oldDebtDelta + $newDebtDelta;

                $warehouseHistories = WarehouseHistory::find()->where(['sklad_id' => $id])->all();
                foreach ($warehouseHistories as $value) {
                    $warehouse = Warehouse::find()->andWhere(['brand_id' => $value->brand_id])
                                        ->andWhere(['product_category_id' => $value->product_category_id])
                                        ->andWhere(['type' => $value->type])
                                        ->andWhere(['size' => $value->size])->one();
                    if ($warehouse) {
                        $warehouse->count = (float)$warehouse->count - (float)$value->count;
                        if ($warehouse->count < 0) {
                            throw new \yii\web\BadRequestHttpException('Ombor qoldig\'i manfiy bo\'lib qolishi mumkin emas.');
                        }
                        $this->saveOrFail($warehouse, 'Ombordan eski mahsulot miqdori ayirilmadi.');
                    }
                    if ($value->delete() === false) {
                        throw new \RuntimeException('Mahsulot tarixi o\'chirilmadi.');
                    }
                }

                foreach ($allValues_new as $value) {
                    $warehouse = $this->findWarehouseByProductRow($value);
                    if (!$warehouse) {
                        $warehouse = new Warehouse();
                        $warehouse->brand_id = $value['brand_id'];
                        $warehouse->product_category_id = $value['product_category_id'];
                        $warehouse->size = $value['size'];
                        $warehouse->type = $value['type'];
                        $warehouse->count = 0;
                    }

                    $warehouse->price = $value['price'];
                    $warehouse->all_my_total_debt = $newDebtTotal;
                    $warehouse->all_sum_dollar = $sum_dollar_new;
                    $warehouse->all_discount_amount = $discount_amount_new;
                    $warehouse->count = (float)$warehouse->count + (float)$value['count'];
                    $warehouse->cr_date = date('Y-m-d', strtotime($datees_new));
                    $this->saveOrFail($warehouse, 'Ombor qoldig\'i yangilanmadi.');

                    $this->saveWarehouseHistory($id, $value);
                    $this->savePrice($warehouse->id, $value['price']);
                }

                $elegantHistoryUpdate = new ElegantHistoryUpdate();
                $elegantHistoryUpdate->title = $consignor->name . " dan olingan mahsulotlar ". \Yii::$app->formatter->asDatetime(date('Y-m-d'), 'php:d.m.Y ') ." sanada o'zgartirildi...";
                $elegantHistoryUpdate->comment = $updateReason;
                $elegantHistoryUpdate->status = 1;
                $elegantHistoryUpdate->type = 1;
                $this->saveOrFail($elegantHistoryUpdate, 'O\'zgarish tarixi saqlanmadi.');

                $model->given_sum_dollar = $given_sum_dollar_new;
                $model->sum_dollar = $sum_dollar_new;
                $model->exchange_rate = $exchange_rate_new;
                $model->discount_amount = $discount_amount_new;
                $model->my_total_debt = $newDebtDelta;
                $model->old_my_total_debt = $oldDebtTotal - $oldDebtDelta;
                $model->cr_date_time = date('Y-m-d H:i:s');
                $model->cr_date = date('Y-m-d', strtotime($datees_new));
                $model->consignor_id = $consignor->id;
                $model->status = 1;
                $this->saveOrFail($model, 'Kirim hujjati yangilanmadi.');
                $this->refreshConsignorDebtSnapshots($consignor->id, $myTotalDebt->id);

                $myTotalDebt->total_debt = $this->calculateConsignorTotalDebt($consignor->id, $myTotalDebt->id);
                $myTotalDebt->update_by = Yii::$app->user->identity->id;
                $myTotalDebt->cr_date = date('Y-m-d H:i:s');
                $this->saveOrFail($myTotalDebt, 'Umumiy qarz yangilanmadi.');

                $transaction->commit();
                return $this->redirect(['index']);
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return $this->render('update', ['model' => $model, 'my_total_debt' => $myTotalDebt->total_debt]);
        
    }

    /**
     * Delete an existing Sklad model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $deleteReason = trim((string)$request->post('delete_reason', ''));
        if ($deleteReason === '') {
            $deleteReason = 'Sabab kiritilmagan.';
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->lockSkladForUpdate($id);
            $model = $this->findModel($id);
            $consignor = Consignor::findOne($model->consignor_id);
            if (!$consignor) {
                throw new NotFoundHttpException('Yuk jo\'natuvchi topilmadi.');
            }

            $myTotalDebt = MyTotalDebt::find()->where(['consignor_id' => $model->consignor_id])->one();
            if (!$myTotalDebt) {
                throw new \RuntimeException('Ushbu yuk jo\'natuvchi uchun umumiy qarz topilmadi.');
            }
            $this->lockMyTotalDebtForUpdate($myTotalDebt->id);
            $myTotalDebt = MyTotalDebt::findOne($myTotalDebt->id);

            $warehouseHistories = WarehouseHistory::find()->where(['sklad_id' => $id])->all();
            foreach ($warehouseHistories as $value) {
                $warehouse = Warehouse::find()->andWhere(['brand_id' => $value->brand_id])
                    ->andWhere(['product_category_id' => $value->product_category_id])
                    ->andWhere(['type' => $value->type])
                    ->andWhere(['size' => $value->size])->one();
                if (!$warehouse) {
                    throw new \RuntimeException('Ombordagi mahsulot topilmadi. O\'chirish bekor qilindi.');
                }

                $this->lockWarehouseForUpdate($warehouse->id);
                $warehouse = Warehouse::findOne($warehouse->id);
                $warehouse->count = (float)$warehouse->count - (float)$value->count;
                $this->saveOrFail($warehouse, 'Ombor qoldig\'i yangilanmadi.');

                if ($value->delete() === false) {
                    throw new \RuntimeException('Mahsulot tarixi o\'chirilmadi.');
                }
            }

            $myTotalDebt->total_debt = $this->calculateConsignorTotalDebt($model->consignor_id, $myTotalDebt->id) - (float)$model->my_total_debt;
            $myTotalDebt->update_by = Yii::$app->user->identity->id;
            $myTotalDebt->cr_date = date('Y-m-d H:i:s');
            $this->saveOrFail($myTotalDebt, 'Umumiy qarz yangilanmadi.');

            $elegantHistoryUpdate = new ElegantHistoryUpdate();
            $elegantHistoryUpdate->title = $consignor->name . " ning ". \Yii::$app->formatter->asDatetime(date('Y-m-d'), 'php:d.m.Y ') ." sanadagi import qilingan mahsulotlar o'chirildi...";
            $elegantHistoryUpdate->comment = $deleteReason;
            $elegantHistoryUpdate->status = 2;
            $elegantHistoryUpdate->type = 1;
            $this->saveOrFail($elegantHistoryUpdate, 'O\'chirish tarixi saqlanmadi.');

            if ($model->delete() === false) {
                throw new \RuntimeException('Kirim hujjati o\'chirilmadi.');
            }
            $this->refreshConsignorDebtSnapshots($model->consignor_id, $myTotalDebt->id);

            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Sklad model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    protected function normalizeProductRows($rows)
    {
        if (!is_array($rows) || empty($rows)) {
            throw new \yii\web\BadRequestHttpException('Kamida bitta mahsulot qatori kiritilishi kerak.');
        }

        $normalized = [];
        foreach ($rows as $row) {
            $brandId = isset($row['brand_id']) ? (int)$row['brand_id'] : 0;
            $categoryId = isset($row['product_category_id']) ? (int)$row['product_category_id'] : 0;
            $size = isset($row['size']) ? (float)$row['size'] : null;
            $type = isset($row['type']) ? (int)$row['type'] : 0;
            $count = isset($row['count']) ? (float)$row['count'] : 0;
            $price = isset($row['price']) ? (float)$row['price'] : 0;

            if (!$brandId && !$categoryId && !$type && !$count && !$price) {
                continue;
            }

            if (!$brandId || !$categoryId || $size === null || !$type || $count <= 0 || $price < 0) {
                throw new \yii\web\BadRequestHttpException('Mahsulot qatorlarida model, nomi, o\'lcham, tip, soni va narxi to\'liq kiritilishi kerak.');
            }

            $normalized[] = [
                'brand_id' => $brandId,
                'product_category_id' => $categoryId,
                'size' => $size,
                'type' => $type,
                'count' => $count,
                'price' => $price,
            ];
        }

        if (empty($normalized)) {
            throw new \yii\web\BadRequestHttpException('Kamida bitta mahsulot qatori kiritilishi kerak.');
        }

        return $normalized;
    }

    protected function calculateProductsTotal($rows)
    {
        $total = 0;
        foreach ($rows as $row) {
            $total += (float)$row['price'] * (float)$row['count'];
        }
        return $total;
    }

    protected function findWarehouseByProductRow($row)
    {
        return Warehouse::find()
            ->andWhere(['brand_id' => (int)$row['brand_id']])
            ->andWhere(['product_category_id' => (int)$row['product_category_id']])
            ->andWhere(['type' => (int)$row['type']])
            ->andWhere(['size' => (float)$row['size']])
            ->one();
    }

    protected function saveWarehouseHistory($skladId, $row)
    {
        $relativeHistory = new WarehouseHistory();
        $relativeHistory->sklad_id = $skladId;
        $relativeHistory->brand_id = $row['brand_id'];
        $relativeHistory->product_category_id = $row['product_category_id'];
        $relativeHistory->size = $row['size'];
        $relativeHistory->type = $row['type'];
        $relativeHistory->price = $row['price'];
        $relativeHistory->count = $row['count'];
        $relativeHistory->cr_date = date('Y-m-d H:i:s');
        $relativeHistory->cr_date_time = date('Y-m-d H:i:s');
        $this->saveOrFail($relativeHistory, 'Mahsulot tarixi saqlanmadi.');
    }

    protected function savePrice($warehouseId, $price)
    {
        $modelPrices = Prices::find()->where(['warehouse_id' => $warehouseId])->one();
        if (!$modelPrices) {
            $modelPrices = new Prices();
            $modelPrices->warehouse_id = $warehouseId;
        }
        $modelPrices->price = $price;
        $this->saveOrFail($modelPrices, 'Mahsulot narxi saqlanmadi.');
    }

    protected function findOrCreateMyTotalDebt($consignorId)
    {
        $myTotalDebt = MyTotalDebt::find()->where(['consignor_id' => $consignorId])->one();
        if (!$myTotalDebt) {
            $myTotalDebt = new MyTotalDebt();
            $myTotalDebt->consignor_id = $consignorId;
            $myTotalDebt->total_debt = 0;
        }
        return $myTotalDebt;
    }

    protected function calculateConsignorTotalDebt($consignorId, $myTotalDebtId)
    {
        $documentDebt = Sklad::find()
            ->where(['consignor_id' => $consignorId])
            ->sum('my_total_debt');
        $paidDebt = MyTotalDebtHistory::find()
            ->where(['my_total_debt_id' => $myTotalDebtId])
            ->sum('all_summ_dollar + discount_amount');

        return round((float)$documentDebt - (float)$paidDebt, 2);
    }

    protected function refreshConsignorDebtSnapshots($consignorId, $myTotalDebtId)
    {
        $documents = Sklad::find()
            ->where(['consignor_id' => $consignorId])
            ->orderBy(['cr_date' => SORT_ASC, 'id' => SORT_ASC])
            ->all();
        $payments = MyTotalDebtHistory::find()
            ->where(['my_total_debt_id' => $myTotalDebtId])
            ->orderBy(['cr_date' => SORT_ASC, 'id' => SORT_ASC])
            ->all();

        $paymentIndex = 0;
        $balance = 0;
        foreach ($documents as $document) {
            while (
                isset($payments[$paymentIndex])
                && $payments[$paymentIndex]->cr_date < $document->cr_date
            ) {
                $balance -= (float)$payments[$paymentIndex]->all_summ_dollar
                    + (float)$payments[$paymentIndex]->discount_amount;
                $paymentIndex++;
            }

            $document->old_my_total_debt = round($balance, 2);
            $balance += (float)$document->my_total_debt;
            $this->saveOrFail($document, 'Kirim hujjati qoldig\'i yangilanmadi.');
        }
    }

    protected function lockSkladForUpdate($id)
    {
        Yii::$app->db->createCommand(
            'SELECT `id` FROM `sklad` WHERE `id` = :id FOR UPDATE',
            [':id' => (int)$id]
        )->queryScalar();
    }

    protected function lockMyTotalDebtForUpdate($id)
    {
        Yii::$app->db->createCommand(
            'SELECT `id` FROM `my_total_debt` WHERE `id` = :id FOR UPDATE',
            [':id' => (int)$id]
        )->queryScalar();
    }

    protected function lockWarehouseForUpdate($id)
    {
        Yii::$app->db->createCommand(
            'SELECT `id` FROM `warehouse` WHERE `id` = :id FOR UPDATE',
            [':id' => (int)$id]
        )->queryScalar();
    }

    protected function saveOrFail($model, $message)
    {
        if (!$model->save(false)) {
            throw new \RuntimeException($message);
        }
    }

    /**
     * Finds the Sklad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sklad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sklad::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
