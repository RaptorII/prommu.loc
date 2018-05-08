<?php
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);

/*
echo '<pre>';
print_r($data);
echo '</pre>';
die;
*/
echo '<div class="col-md-12">';
echo '<div class="col-md-6">
<h3>Редактирование вакансии '.$data['vac']['id'].'</h3><br/>';

echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
echo '<h4 style="font-size: 40px;font-weight: 100;">ОСНОВНАЯ ИНФОРМАЦИЯ</h4>';

echo '<div class="control-group">
      <label class="control-label">Название</label>
        <div class="controls input-append">';
echo CHtml::textField('Vacancy[title]', $data['vac']['title'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

// echo '<div class="control-group">
//       <label class="control-label">Премиум</label>
// 	    <div class="controls input-append">';
// echo CHtml::textField('Vacancy[ispremium]', $data['vac']['ispremium'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Премиум</label>
        <div class="controls input-append">';
echo CHtml::CheckBox('Vacancy[ispremium]', $data['vac']['ispremium'], array('value'=>'1' 
));
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Требования</label>
	    <div class="controls input-append">';
$text = html_entity_decode($data['vac']['requirements']);
$text = strip_tags($text);
echo CHtml::textArea('Vacancy[requirements]', $text, array('rows' => 6, 'cols' => 50,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Обязанности</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['vac']['duties']);
$text = strip_tags($text);     
echo CHtml::textArea('Vacancy[duties]', $text, array('rows' => 6, 'cols' => 50,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Условия</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['vac']['conditions']);
$text = strip_tags($text);   
echo CHtml::textArea('Vacancy[conditions]', $text, array('rows' => 6, 'cols' => 50,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

// echo '<h4 style="font-size: 40px;font-weight: 100;">#Город</h4>';
// echo '<div class="control-group">
//       <label class="control-label">Город</label>
//         <div class="controls input-append">';
        
// echo CHtml::textField('Vacancy[city]', $data['vac']['city'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';

// echo '<div class="control-group">
//       <label class="control-label">Дата снятия с публикации</label>
//         <div class="controls input-append">';
// echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('name'=>'Vacancy[remdate]', 'value'=>$data['vac']['remdate'], 'language'=>'ru', 'options'=>array('dateFormat'=>'dd.mm.yy', 'minDate'=>'Today'),), true);
// echo '  <span class="add-on"></span>';
// echo '</div></div>';

// echo '<div class="control-group">
//       <label class="control-label">Дата снятия с публикации</label>
//         <div class="controls input-append">';
// echo CHtml::textArea('Vacancy[remdate]', $data['vac']['remdate'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';



echo '<div class="control-group">
      <label class="control-label">Постоянная</label>
        <div class="controls input-append">';
echo CHtml::textField('Vacancy[istemp]', $data['vac']['istemp'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Оплата в час</label>
        <div class="controls input-append">';
echo CHtml::textField('Vacancy[shour]', $data['vac']['shour'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Наличие опыта</label>
        <div class="controls input-append">';
echo CHtml::textField('Vacancy[exp]', $data['vac']['exp'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Комментария администратора</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['vac']['comment']);
// $text = strip_tags($text);   
echo CHtml::textArea('Vacancy[comment]', $text, array('rows' => 3, 'cols' => 50,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div></div>';

echo '<div class="col-md-6">';
echo '<h3>'.$data['vac']['title'].'</h3><br/>';
echo '<h4 style="font-size: 40px;font-weight: 100;">ПОЛ</h4>';
// echo '<div class="control-group">
//       <label class="control-label">Нужны девушки</label>
//         <div class="controls input-append">';
// echo CHtml::textField('Vacancy[iswoman]', $data['vac']['iswoman'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Нужны девушки</label>
        <div class="controls input-append">';
echo CHtml::CheckBox('Vacancy[iswoman]', $data['vac']['iswoman'], array('value'=>'1' 
));
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Нужны юноши</label>
        <div class="controls input-append">';
echo CHtml::CheckBox('Vacancy[isman]', $data['vac']['isman'], array('value'=>'1' 
));
echo '</div></div>';

echo '<h4 style="font-size: 40px;font-weight: 100;">ДОПОЛНИТЕЛЬНО</h4>';
// echo '<div class="control-group">
//       <label class="control-label">Нужна медкнижка</label>
//         <div class="controls input-append">';
// echo CHtml::textField('Vacancy[ismed]', $data['vac']['ismed'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Нужна медкнижка</label>
        <div class="controls input-append">';
echo CHtml::CheckBox('Vacancy[ismed]', $data['vac']['ismed'], array('value'=>'1' 
));
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Нужен автомобиль</label>
        <div class="controls input-append">';
echo CHtml::CheckBox('Vacancy[isavto]', $data['vac']['isavto'], array('value'=>'1' 
));
echo '</div></div>';

// echo '<div class="control-group">
//       <label class="control-label">Нужен автомобиль</label>
//         <div class="controls input-append">';
// echo CHtml::textField('Vacancy[isavto]', $data['vac']['isavto'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';

echo '<h4 style="font-size: 40px;font-weight: 100;">ВОЗРАСТ</h4>';
echo '<div class="control-group">
      <label class="control-label">Возраст соискателя от</label>
        <div class="controls input-append">';
echo CHtml::textField('Vacancy[agefrom]', $data['vac']['agefrom'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Возраст соискателя до</label>
        <div class="controls input-append">';
echo CHtml::textField('Vacancy[ageto]', $data['vac']['ageto'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<h4 style="font-size: 40px;font-weight: 100;">МОДЕРАЦИЯ</h4>';
// echo '<div class="control-group">
//       <label class="control-label">Активна</label>
//         <div class="controls input-append">';
        
// echo CHtml::textField('Vacancy[status]', $data['vac']['status'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Активна</label>
        <div class="controls input-append">';
echo CHtml::CheckBox('Vacancy[status]', $data['vac']['status'], array('value'=>'1' 
));
echo '</div></div>';

// echo '<div class="control-group">
//       <label class="control-label">Проверено администратором</label>
//         <div class="controls input-append">';
        
// echo CHtml::textField('Vacancy[ismoder]', $data['vac']['ismoder'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Проверено администратором</label>
        <div class="controls input-append">';
echo CHtml::CheckBox('Vacancy[ismoder]', $data['vac']['ismoder'], array('value'=>'100' 
));
echo '</div></div>';

echo '<h4 style="font-size: 40px;font-weight: 100;">META</h4>';

echo '<div class="control-group">
      <label class="control-label">meta_title</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['vac']['meta_title']);
$text = strip_tags($text);     
echo CHtml::textArea('Vacancy[meta_title]', $text, array('rows' => 2, 'cols' => 40,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">meta_h1</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['vac']['meta_h1']);
$text = strip_tags($text);     
echo CHtml::textArea('Vacancy[meta_h1]', $text, array('rows' => 2, 'cols' => 40,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">meta_description</label>
        <div class="controls input-append">';
$text = html_entity_decode($data['vac']['meta_description']);
$text = strip_tags($text);     
echo CHtml::textArea('Vacancy[meta_description]', $text, array('rows' => 3, 'cols' => 40,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Запрет индексации</label>
        <div class="controls input-append">';
echo CHtml::CheckBox('Vacancy[index]', $data['vac']['index'], array('value'=>'1' 
));
echo '</div></div>';

echo '<div class="span11">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Сохранить',array( "class"=>"btn btn-success", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo '<a href="/admin/site/vacancy" class="btn btn-warning" id="btn_cancel">Отмена</a>';
//echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div></div></div>';




//$this->endWidget();
echo CHtml::endForm();
?>
</div>

<!-- <script>
    function Del(key)
    {
        if(confirm('Вы действительно хотите удалить документ '+key))
        {
            $.ajax({
                type:'GET',
                url:'/admin/ajax/DeleteScan?key='+key+'&id=<?php echo $data['id']?>',
                cache: false,
                dataType: 'text',
                success:function (data) {
                    $("#lst_scan").html(data);
                },
                error: function(data){
                    alert("Download error!");
                }
            });
        }
    }


    function Add(fname)
    {
            $.ajax({
                type:'GET',
                url:'/admin/ajax/AddScan?id=<?php echo $data['id']?>&fname='+fname,
                cache: false,
                dataType: 'text',
                success:function (data) {
                    $("#lst_scan").html(data);
                },
                error: function(data){
                    alert("Download error!");
                }
            });

    }



    function ajaxFileUpload()
    {

        $.ajaxFileUpload
        (
            {
                url:'/uploads/doajaxdocupload.php',
                secureuri:false,
                fileElementId:'fileToUpload',
                dataType: 'json',
                data:{name:'logan', id:'id'},
                success: function (data, status)
                {
                    if(typeof(data.error) != 'undefined')
                    {
                        if(data.error != '')
                        {
                            alert(data.error);
                        }else
                        {
                            //alert(data.name);
                            Add(data.name);

                        }
                    }
                },
                error: function (data, status, e)
                {
                    alert(e);
                }
            }
        )

        return false;

    }
</script> -->
<style>
@import url(https://fonts.googleapis.com/css?family=Lato:300,400,700);
.checkboxFive {
    width: 25px;
    margin: 20px 100px;
    position: relative;
}
.checkboxFive label {
    cursor: pointer;
    position: absolute;
    width: 25px;
    height: 25px;
    top: 0;
    left: 0;
    background: #eee;
    border:1px solid #ddd;
}
.checkboxFive label:after {
    opacity: 0.2;
    content: '';
    position: absolute;
    width: 9px;
    height: 5px;
    background: transparent;
    top: 6px;
    left: 7px;
    border: 3px solid #333;
    border-top: none;
    border-right: none;

    transform: rotate(-45deg);
}
.checkboxFive label:hover::after {
    opacity: 0.5;
}

/**
 * Create the checkbox state for the tick
 */
.checkboxFive input[type=checkbox]:checked + label:after {
    opacity: 1;
}
</style>