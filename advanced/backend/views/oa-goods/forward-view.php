<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\OaGoods */

$this->title = $model->devNum;
$this->params['breadcrumbs'][] = ['label' => '产品推荐', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="oa-goods-view">
    <div>
        <?= Html::img($model->img,['width'=>100,'height'=>100])?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            [
                'attribute' => 'img',
                'format' => 'raw',
                'value' => Html::a("<a target='_blank' href=$model->img>$model->img</a>",$model->img),
            ],
            'cate',
            'subCate',
            [
                'attribute' => 'vendor1',
                'format' => 'raw',
                'value' => Html::a("<a target='_blank' href=$model->vendor1>$model->vendor1</a>",$model->vendor1),

            ],
            [
                'attribute' => 'vendor2',
                'format' => 'raw',
                'value' => Html::a("<a target='_blank' href=$model->vendor2>$model->vendor2</a>",$model->vendor2),

            ],
            [
                'attribute' => 'vendor3',
                'format' => 'raw',
                'value' => Html::a("<a target='_blank' href=$model->vendor3>$model->vendor3</a>",$model->vendor3),

            ],
            [
                'attribute' => 'origin1',
                'format' => 'raw',
                'value' => Html::a("<a target='_blank' href=$model->origin1>$model->origin1</a>",$model->origin1),

            ],
            [
                'attribute' => 'origin2',
                'format' => 'raw',
                'value' => Html::a("<a target='_blank' href=$model->origin2>$model->origin2</a>",$model->origin2),

            ],
            [
                'attribute' => 'origin3',
                'format' => 'raw',
                'value' => Html::a("<a target='_blank' href=$model->origin3>$model->origin3</a>",$model->origin3),

            ],



            'devNum',
            'developer',
            'introducer',
            'devStatus',
            'checkStatus',
            'createDate',
            'updateDate',
            'salePrice',
            'hopeWeight',
            'hopeRate',
            'hopeSale',
            'hopeMonthProfit',
        ],
    ]) ?>

</div>
