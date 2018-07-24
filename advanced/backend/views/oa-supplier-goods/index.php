<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaSupplierGoodsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Oa Supplier Goods';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-supplier-goods-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Oa Supplier Goods', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'supplier',
            'purchaser',
            'goodsCode',
            'goodsName',
            //'supplierGoodsCode',
            //'createdTime',
            //'updatedTime',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
