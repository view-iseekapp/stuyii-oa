<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel backend\models\OaGoodsinfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '图片信息';
$this->params['breadcrumbs'][] = $this->title;


?>
<?php echo '<br>'?>
<div class="oa-goodsinfo-index">

    <p>

        <?= Html::button('标记已完善', ['id'=>'complete-lots','class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([

        'dataProvider'=> $dataProvider,
        'filterModel' => $searchModel,
        'showPageSummary'=>true,
        'id' => 'picinfo',
        'pjax'=>true,
        'striped'=>true,
        'responsive'=>true,
        'hover'=>true,
//        'panel'=>['type'=>'primary', 'heading'=>'基本信息'],

        'columns' => [
            ['class'=>'kartik\grid\SerialColumn'],
            ['class' => 'kartik\grid\CheckboxColumn'],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' =>'{view} {update} {complete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => '查看',
                            'aria-label' => '查看',
                            'data-toggle' => 'modal',
                            'data-target' => '#index-modal',
                            'data-id' => $key,
                            'class' => 'index-view',

                        ];
                        return Html::a('<span  class="glyphicon glyphicon-eye-open"></span>', '#', $options);
                    },
                    'complete' => function ($url, $model, $key) {
                        $options = [
                            'title' => '标记图片已完善',
                            'aria-label' => '标记图片已完善',
                            'data-id' => $key,
                            'class' => 'index-complete',

                        ];
                        return Html::a('<span  class="glyphicon glyphicon-check"></span>', '#', $options);
                    },

                ],

            ],
            [
                'attribute' => 'picUrl',
                'value' =>function($model,$key, $index, $widget) {
                    return "<img src='$model->picUrl' width='100' height='100'/>";
                },
                'format' => 'raw',
                'width' => '100px',
            ],

            'GoodsCode',
            'GoodsName',
            'picStatus',
            'developer',
            [
                'attribute' => 'devDatetime',
                'label'=>'开发时间',
                'value'=>
                    function($model){
                        return  substr($model->devDatetime,0,19);   //主要通过此种方式实现
                    },
            ],
            'possessMan2',
        ],




    ]); ?>

    <?php
    //创建模态框
    use yii\bootstrap\Modal;
    Modal::begin([
        'id' => 'index-modal',
        'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
        'size' => "modal-lg"
    ]);
    //echo
    Modal::end();
    $viewUrl = Url::toRoute('view');

    $completeUrl = Url::toRoute('complete');           //标记图片单个已完善
    $completeLotsUrl = Url::toRoute('complete-lots'); //标记图片批量已完善
    $js = <<<JS
// 查看框
$('.index-view').on('click',  function () {
    $('.modal-body').children('div').remove();
    $.get('{$viewUrl}',{ id: $(this).closest('tr').data('key') },
            function (data) {
                $('.modal-body').html(data);
            }
        );
    });

    //单个标记已完善
    $(".index-complete").on('click',function() {
        id = $(this).closest('tr').data('key');
        
        $.ajax({
        url:'{$completeUrl}',
        type:'get',
        data:{id:id},
        success:function(res) {
        alert(res);//传回结果信息
        }
        });
    });
    
    //批量标记完善
    $("#complete-lots").on('click',function() {
        ids = $("#picinfo").yiiGridView('getSelectedRows');
        
        $.ajax({
        url:'{$completeLotsUrl}',
        type:'get',
        data:{ids:ids},
        success:function(res) {
        alert(res);
        location.reload();
        }
        });
    });
JS;
    $this->registerJs($js);

    ?>
</div>







