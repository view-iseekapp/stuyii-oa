<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017-11-16
 * Time: 11:32
 */
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;
use yii\helpers\Html;

//动态的去判断属性的名称和值
//var_dump($propertyVar);die
$property1Len = 0;
$property2Len = 0;
$property3Len = 0;
$property1Vis = true;
$property2Vis = true;
$property3Vis = true;
$property1Lab = '款式-1';
$property2Lab = '款式-2';
$property3Lab = '款式-3';
$metaProperty = [
    'property1'
];
foreach ($propertyVar as $row){
    $property1Len += strlen($row->property1);
    $property2Len += strlen($row->property2);
    $property3Len += strlen($row->property3);
}
if($property1Len==0){
    $property1Vis = false;
}
else{
    $dump = $propertyVar[0]->property1;
    $property1Lab = explode(':',$dump)[0];
}

if($property2Len==0){
    $property2Vis = false;
}
else{
    $dump = $propertyVar[0]->property2;
    $property2Lab = explode(':',$dump)[0];
}
if($property3Len==0){
    $property3Vis = false;
}
else{
    $dump = $propertyVar[0]->property2;
    $property2Lab = explode(':',$dump)[0];
}
?>

<div class="">
        <?php
        $varForm = ActiveForm::begin(['id'=>'var-form','method'=>'post',]);
        echo TabularForm::widget([
            'dataProvider' => $templatesVar,
            'id' => 'var-table',
            'form' => $varForm,
            'actionColumn' =>
                [
                    'class' => '\kartik\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' =>
                        [
                            'delete' => function($url,$model,$key) {
                                $options = [
                                    'title' => '删除',
                                    'data-id' => $key,
                                ];
                                return Html::a('<span class="delete-icon glyphicon glyphicon-trash"</span>','#',$options);
                            }
                        ]
                ],
            'attributes' =>
                [
                    'sku'=>
                        [
                            'label'=>'SKU', 'type'=>TabularForm::INPUT_TEXT,
                            'options'=>['class'=>'sku'],
                        ],
                    'quantity'=>
                        [
                            'type'=>TabularForm::INPUT_TEXT,
                            'options'=>['class'=>'quantity','value' => 5],

                        ],
                    'retailPrice'=>
                        [
                            'type'=>TabularForm::INPUT_TEXT,
                            'options'=>['class'=>'retailPrice'],
                        ],
                    'imageUrl' =>
                        [
                            'type' => TabularForm::INPUT_TEXT,
                            'options' => ['class' =>'imageUrl']
                        ],
                    'image'=>
                        [
                            'type'=>TabularForm::INPUT_RAW,
                            'options' => ['class' => 'image'],
                            'value'=>function($data){return "<img weight='50' height='50' src='".$data->imageUrl."'>";}
                        ],

                    'property1'=>
                        [
                            'type'=>TabularForm::INPUT_RAW,
                            'options'=>[
                                'class'=>'property1',
                            ],

                            'value'=>function($data,$key)
                            {
                                $property = $data->property1;
                                $ret = explode(':',$property)[1];
                                return '<input type="text"  class="property1 form-control kv-align-top" name="OaTemplatesVar['.$key.'][property1]" value="'.$ret.'">';
                            },
                            'label' => $property1Lab,
                            'visible' =>$property1Vis,
                        ],
                    'property2'=>
                        [
                            'type'=>TabularForm::INPUT_TEXT,
                            'options'=>['class'=>'property2'],
                            'value'=>function(){},
                            'label' => '',
                            'visible' =>$property2Vis,
                        ],
                    'property3'=>
                        [
                            'type'=>TabularForm::INPUT_TEXT,
                            'options'=>['class'=>'property3'],
                            'value'=>function(){},
                            'label' => '',
                            'visible' =>$property3Vis,
                        ],
                    'UPC'=>
                        [
                            'type'=>TabularForm::INPUT_TEXT,
                            'options'=>['class'=>'UPC','value' => 'Does not apply'],

                        ],
                    'EAN'=>
                        [
                            'type'=>TabularForm::INPUT_TEXT,
                            'options'=>['class'=>'EAN','value' => 'Does not apply'],

                        ],
                ],
            'gridSettings'=>[
            'panel'=>[
                'heading'=>'<h3 class="panel-title">多属性设置</h3>',
                'type'=>GridView::TYPE_PRIMARY,
                'after'=>
                    Html::input('text','rowNum','',['class' => 'x-row','placeholder'=>'行数']).' '.
                    Html::button('新增行', ['id'=>'add-row','type'=>'button', 'class'=>'btn kv-batch-create']) . ' ' .
                    Html::input('text','number','',['class' => 'number-replace','placeholder'=>'数量']).' '.
                    Html::button('数量确定', ['id'=>'number','type'=>'button','class'=>'btn']).' '.
                    Html::input('text','RetailPrice','',['class' => 'RetailPrice-replace','placeholder'=>'零售价$']).' '.
                    Html::button('价格确定', ['id'=>'RetailPrice-set','type'=>'button','class'=>'btn']).''.
                    Html::input('text','EAN','',['class' => 'ean-replace','placeholder'=>'Does not apply']).' '.
                    Html::button('EAN确定', ['id'=>'ean-set','type'=>'button','class'=>'btn']).' '.
                    Html::input('text','label','',['class' => 'label-input','hidden'=>true,'placeholder'=>'Does not apply']).' '.
                    Html::button('保存', ['id'=>'save-only','type'=>'button','class'=>'btn btn-info']).' '.
                    Html::button('删除行', ['id'=>'delete-row','type'=>'button', 'class'=>'btn btn-danger kv-batch-delete'])
            ]
        ]

        ]);

        ActiveForm::end();
        ?>
</div>

<?php
$js = <<< JS

//删除选中行

$('#delete-row').click(function() {
    var ids = [];
    $("[name='selection[]']:checkbox:checked").each(function() {
        id = $(this).closest('tr').attr('data-key');
        $(this).closest('tr').remove();  
        if(id){
            ids.push(id);
        }
    });
    // ajax方式提交数据到后台删除
    if(ids){
        $.ajax({
            url:'/channel/delete-var',
            type:'post',
            data:{id:ids},
            success: function (ret) {
            }
        })
    }
});

//新增行
var row_count = 0; //全局变量,记录当前新增行数

$('#add-row').click(function() {
   //增加一行
    function addOneRow() {
        var skuTable = $('#var-table').find('table');
        var row =$('<tr class="kv-tabform-row" ></tr>');
        
        var seriralTd = $('<td class="kv-align-center kv-align-middle" style="width:50px;" data-col-seq="0">New-'+ row_count+'</td>'); 
        row.append(seriralTd);
        
        var checkBoxTd =$(
            '<td class="skip-export kv-align-center kv-align-middle kv-row-select" style="width:50px;" data-col-seq="1">' +
            '<input type="checkbox" class="kv-row-checkbox" name="selection[]" >' +
             '</td>');
        row.append(checkBoxTd);
        
        var actionTd = $(
             '<td class="skip-export kv-align-center kv-align-middle" style="width:80px;" data-col-seq="2">' +
              '<a href="javascript:void(0)" ' +
               'class="new-delete" title="删除" aria-label="删除" >' +
               '<span class=" delete-icon glyphicon glyphicon-trash"></span></a></td>');
        row.append(actionTd);
        
        
        //动态地添加主字段
        var fieldsMap = {
            'SKU':'sku',
            '数量':'quantity',
            '价格':'reailPrice',
            '图片地址':'imageUrl',
            '图片':'image',
            '{$property1Lab}':'property1',
            '{$property2Lab}':'property2',
            '{$property3Lab}':'property3',
            'UPC':'UPC',
            'EAN':'EAN'
        };
        var inputFields = [];
        $('thead th').each(function(index,element) {
           var cnName = $.trim($(this).text());
           var enName = fieldsMap[cnName];
           if((typeof(enName) != "undefined")){
                inputFields.push(enName); 
           }
        });
        // var inputFields = ['sku','quantity','reailPrice','imageUrl','image','property1','property2','property3','UPC','EAN'];
        for(var i=3; i< inputFields.length + 3; i++){
            if(inputFields[i-3] == 'imageUrl'){
                var fun = " var new_image = $(this).val();;$(this).parents('tr').find('img').attr('src',new_image); ";
                var td = $('<td class="kv-align-top" data-col-seq="'+ i +'" >' +
                             '<div class="form-group">' +
                                '<input type="text"  name="OaTemplatesVar[New-'+ row_count +']['+ inputFields[i-3] +']" class="form-control  '+ inputFields[i-3] +
                                // '">' +
                                '" onchange=' + '"'+ fun+'">' +
                             '</div>' +
                           '</td>');
            }
            else if(inputFields[i-3] == 'image'){
                var td = $('<td class="kv-align-top" data-col-seq="'+ i +'" >' +
                             '<div class="form-group">' +
                                "<img weight='50' height='50' src=''>" +
                                 
                             '</div>' +
                           '</td>');
            }
            else {
                var td = $('<td class="kv-align-top" data-col-seq="'+ i +'" >' +
                             '<div class="form-group">' +
                                '<input type="text"  name="OaTemplatesVar[New-'+ row_count +']['+ inputFields[i-3] +']" class="form-control  '+ inputFields[i-3] +'">' +
                                 
                             '</div>' +
                           '</td>');
            }
                row.append(td);
        }
        
        skuTable.append(row);
        row_count++;
    }
    
    var rowNum = $('.x-row').val();        
        if (rowNum !== null || rowNum !== undefined ) { 
            if( rowNum == ''){
                  addOneRow();   
            }           
           for(var r=0;r<rowNum;r++){               
              addOneRow();               
           }
        }
});


//批量设置零售价
    $('#RetailPrice-set').on('click',function(){
       var newRetailprice = $('.RetailPrice-replace').val(); 
       $('.retailPrice').each(function(){
           $(this).val(newRetailprice);
           });
       });

// 批量设置数量
    $("#number").on('click',function() {
        var newQuantity = $('.number-replace').val();
        $('.quantity').each(function() {
            $(this).val(newQuantity);
        })
    });


//批量设置EAN
    $('#ean-set').on('click',function() {
        var newEAN = $('.ean-replace').val();
        $('.EAN').each(function() {
            $(this).val(newEAN);
        });
    });

    //监听图片变化事件
    
    $('.imageUrl').change(function() {
        var new_image = $(this).val();
        $(this).parents('tr').find('img').attr('src',new_image); 
    });

//每行的删除操作改为ajax方式 新增行也能删除
    $('table').on('click','.delete-icon',function() {
        id = $(this).parents('tr').attr('data-key');
        $(this).parents('tr').remove();//前端删除
        if(id){
            $.ajax({
            url:'/channel/delete-var',
            type:'post',
            data:{id:id},
            success: function(res) {
            
            }
        });
        }
    });
    
    
// 点击触发编辑事件
$(".table").find("th ").bind("dblclick", function () {
        // alert("Hi!");
        var input = "<input type='text' id='temp' style='width:130px;' value=" + $(this).text() + " >";
        $(this).text("");
        $(this).append(input);
        $("input#temp").focus();
        $("input").blur(function () {
            if ($(this).val() == "") {
                $(this).remove();
            } else {
                $(this).closest("th").text($(this).val());
            }
        });
    });

// 可修改列加按键
$('thead').find("th").each(function() {
    var newHeader =  ' <span class="remove-col glyphicon glyphicon-remove"></span>';
    if($(this).text().indexOf('款式')>-1){
        $(this).append(newHeader);
    }   
});

//设置所有输入框的样式为 kv-align-top
$('td').attr('class',"kv-align-top");

// 删除列事件
$('.remove-col').click(function() {
    var index = $(this).closest('th').attr('data-col-seq'); //找到列数
    var selector = "[data-col-seq='" + index + "']";
    $(selector).remove();
});


//保存按钮事件
$('#save-only').click(function() {
    //计算label值;动态的计算label值
    
    var sequences = {"property1":'.property1',"property2":'.property2',"property3":'.property3'};
    for (var key in sequences)
    {   
        var seq = $(sequences[key]).closest('td').attr('data-col-seq');
        if(seq){
             var condation = 'th[data-col-seq="'+ seq+'"]';
           
            sequences[key] = $(condation).text(); 
        }
        else{
            sequences[key] = '';
        }
       
    }
    //ajax 提交方式
   $('.label-input').val(JSON.stringify(sequences)); 
   var varForm = $('#var-form').serialize();
   $.ajax({
       type: "POST",
       url: '/channel/var-save?id={$tid}',
        // dataType:'json',
       data:varForm,
       success: function(data) {
           alert(data);
       }
   });
});
JS;




$this->registerJs($js);
?>
<style>
    .panel-primary > .panel-heading {
        color: black;
        background-color: whitesmoke;
        border-color: transparent;
    }
    .panel-primary {
        border-color: transparent;
    }

    .panel-footer {
        padding: 10px 15px;
        background-color: white;
        border-top: 1px solid #ddd;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
    }
</style>