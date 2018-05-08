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
<h3>Редактирование работодателя  '. $data['id_user'].'</h3>';

echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
//echo CHtml::hiddenField('field_lang', $lang, array('type'=>"hidden"));
//echo CHtml::hiddenField('pagetype', $pagetype, array('type'=>"hidden"));

echo '<h4 style="font-size: 40px;font-weight: 100;">ОСНОВНАЯ ИНФОРМАЦИЯ</h4>';
echo '<div class="control-group">
      <label class="control-label">Название компании</label>
        <div class="controls input-append">';
echo CHtml::textField('User[name]', $data['name'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Фамилия</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[lastname]', $data['lastname'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Имя</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[firstname]', $data['firstname'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Тип компании</label>
        <div class="controls input-append">';

echo CHtml::textField('User[type]', ShowType($data['type'], $data['id_user']), array('class'=>'form-control'));
echo '  <span class="add-on">Прямой работодатель - 102, Рекламное агенство - 103, Кадровое агенство - 104, Рекрутинговое агенство - 105, Модельное агенство - 106, Не выбран - 135</span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Город</label>
        <div class="controls input-append">';
echo CHtml::textField('User[city]', $data['city'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Лого</label>
        <div class="controls input-append">';
echo CHtml::image('https://prommu.com/images/company/tmp/'.$data['logo'].'400.jpg');
echo '</div></div></div>';

echo '<div class="col-md-6">';
echo '<h3>Работодатель #'.$data['firstname']. $data['lastname'].'</h3>';

echo '<h4 style="font-size: 40px;font-weight: 100;">МОДЕРАЦИЯ</h4>';

echo '<div class="control-group">
      <label class="control-label">Прошел модерацию (просмотрен) </label>
        <div class="controls input-append">';
echo CHtml::CheckBox('User[ismoder]', $data['ismoder'], array('value'=>'1' 
));
echo '</div></div>';
echo '<div class="control-group">
      <label class="control-label">Видимость</label>
        <div class="controls input-append">';
echo CHtml::textField('User[isblocked]', $data['isblocked'], array('class'=>'form-control'));
echo '  <span class="add-on">0 - полностью активен, 1 - заблокирован, 2 - ожидает активации, 3 - активирован, но еще не заполнил все необходимые поля, 4 - приостановка показа</span>';
echo '</div></div>';

echo '<div class="span11">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo '<a href="/admin/site/Users" class="btn btn-warning" id="btn_cancel">Отмена</a>';
//echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div></div></div>';




//$this->endWidget();
echo CHtml::endForm();

function ShowType($type, $id_user)
{

    $types = ["Прямой работодатель", "Ивент агенство", "Кадровое агенство", "Рекламное агенство", "Рекрутинговое агенство", "Модельное агенство", "Не выбран"];

    if($type == 102) {
        return $types[0];
    }
    if($type == 107) {
        return $types[1];
    }
    if($type == 104) {
        return $types[2];
    }
     if($type == 105) {
        return $types[4];
    }
     if($type == 106) {
        return $types[5];
    }
     if($type == 103) {
        return $types[3];
    }
     if($type == 135) {
        return $types[6];
    }

    return $types[0];
}
?>
</div>

<script>
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
</script>