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
<h3>Редактирование администратора#'.$data['id'].'</h3>';

echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
//echo CHtml::hiddenField('field_lang', $lang, array('type'=>"hidden"));
//echo CHtml::hiddenField('pagetype', $pagetype, array('type'=>"hidden"));

echo '<h4 style="font-size: 40px;font-weight: 100;">Настройки</h4>';

echo '<div class="control-group">
      <label class="control-label">Логин</label>
        <div class="controls input-append">';
echo CHtml::textField('UserAdm[login]', $data['login'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Email</label>
        <div class="controls input-append">';
echo CHtml::textField('UserAdm[email]', $data['email'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Имя</label>
        <div class="controls input-append">';
echo CHtml::textField('UserAdm[name]', $data['name'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Фамилия</label>
        <div class="controls input-append">';
echo CHtml::textField('UserAdm[surname]', $data['surname'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Доступы</label>
        <div class="controls input-append">';
echo CHtml::textField('UserAdm[access]', $data['access'], array('class'=>'form-control'));
echo '  <span class="add-on">Существуют доступы: Соискатели, Вакансии, SEO, Карта, Работодатели, Аналитика, Статьи, Новости, Обратная связь, Услуги, Брошенные регистрации</span>';
echo '</div></div>';


// echo '<div class="control-group">
//       <label class="control-label">Пароль</label>
//         <div class="controls input-append">';
// echo CHtml::textField('UserAdm[passw]', $data['passw'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';


// echo '<h4 style="font-size: 40px;font-weight: 100;">ОСНОВНАЯ ИНФОРМАЦИЯ</h4>';

// echo '<div class="control-group">
//       <label class="control-label">Фамилия</label>
// 	    <div class="controls input-append">';
// echo CHtml::textField('User[lastname]', $data['lastname'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';


// echo '<div class="control-group">
//       <label class="control-label">Имя</label>
// 	    <div class="controls input-append">';
// echo CHtml::textField('User[firstname]', $data['firstname'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';

// echo '<h4 style="font-size: 40px;font-weight: 100;">ПОЛ</h4>';

// echo '<div class="control-group">
//       <label class="control-label">Дата рождения (дд.мм.гггг)</label>
//         <div class="controls input-append">';
// //echo CHtml::textField('User[birthday]', $data['birthday'], array('class'=>'admform span2'));
// echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('name'=>'User[birthday]', 'value'=>$data['birthday'], 'language'=>'ru', 'id'=>'birthday', 'options'=>array('dateFormat'=>'dd.mm.yy', 'minDate'=>'12.12.1942'),), true);
// echo '  <span class="add-on"></span>';
// echo '</div></div>';


// echo '<div class="control-group">
//       <label class="control-label">Парень</label>
//         <div class="controls input-append">';
// echo CHtml::CheckBox('User[isman]', $data['isman'], array('value'=>'1' 
// ));
// echo '</div></div>';

// echo '<div class="control-group">
//       <label class="control-label">Фото</label>
//         <div class="controls input-append">';
// echo CHtml::image('https://prommu.com/images/applic/'.$data['photo'].'400.jpg');
// echo '</div></div></div>';



// echo '<div class="col-md-6">
// <h3>Соискатель #'.$data['firstname']. $data['lastname'].'</h3>';
// echo '<h4 style="font-size: 40px;font-weight: 100;">МОДЕРАЦИЯ</h4>';

// // echo '<div class="control-group">
// //       <label class="control-label">Статус</label>
// //         <div class="controls input-append">';
// // echo CHtml::textField('User[ismoder]', $data['ismoder'], array('class'=>'form-control'));
// // echo '  <span class="add-on"></span>';
// // echo '</div></div>';

// echo '<div class="control-group">
//       <label class="control-label">Модерация</label>
//         <div class="controls input-append">';
// echo CHtml::CheckBox('User[ismoder]', $data['ismoder'], array('value'=>'1' 
// ));
// echo '</div></div>';



// echo '<div class="control-group">
//       <label class="control-label">Видимость</label>
//         <div class="controls input-append">';
// echo CHtml::textField('User[isblocked]', $data['isblocked'], array('class'=>'form-control'));
// echo '  <span class="add-on">0 - полностью активен, 1 - заблокирован, 2 - ожидает активации, 3 - активирован, но еще не заполнил все необходимые поля, 4 - приостановка показа</span>';
// echo '</div></div>';
// echo '<h4 style="font-size: 40px;font-weight: 100;">ДОПОЛНИТЕЛЬНО</h4>';

// echo '<div class="control-group">
//       <label class="control-label">О себе</label>
//         <div class="controls input-append">';
// echo CHtml::textField('User[aboutme]', $data['aboutme'], array('class'=>'form-control'));
// echo '  <span class="add-on"></span>';
// echo '</div></div>';
// echo '<div class="control-group">
//       <label class="control-label">Мед. книга</label>
//         <div class="controls input-append">';
// echo CHtml::CheckBox('User[ismed]', $data['ismed'], array('value'=>'1' 
// ));
// echo '</div></div>';

// echo '<div class="control-group">
//       <label class="control-label">Автомобиль</label>
//         <div class="controls input-append">';
// echo CHtml::CheckBox('User[ishasavto]', $data['ishasavto'], array('value'=>'1' 
// ));
// echo '</div></div>';



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