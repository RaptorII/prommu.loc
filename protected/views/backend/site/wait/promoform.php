<?php
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
/*
echo '<pre>';
print_r($data);
echo '</pre>';
die;
*/
echo '<div class="span11">
<h3><i>Редактирование соискателя</i> #'.$data['id_user'].'</h3>';

echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
//echo CHtml::hiddenField('field_lang', $lang, array('type'=>"hidden"));
//echo CHtml::hiddenField('pagetype', $pagetype, array('type'=>"hidden"));

echo '<h4>ОСНОВНАЯ ИНФОРМАЦИЯ</h4>';


echo '<div class="control-group">
      <label class="control-label">Фамилия</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[lastname]', $data['lastname'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Имя</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[firstname]', $data['firstname'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';



echo '<div class="control-group">
      <label class="control-label">Дата рождения (дд.мм.гггг)</label>
	    <div class="controls input-append">';
//echo CHtml::textField('User[birthday]', $data['birthday'], array('class'=>'admform span2'));
echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('name'=>'User[birthday]', 'value'=>$data['birthday'], 'language'=>'ru', 'id'=>'birthday', 'options'=>array('dateFormat'=>'dd.mm.yy', 'minDate'=>'Today'),), true);
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Наличие медкнижки</label>
	    <div class="controls input-append">';

echo CHtml::CheckBox('User[ismed]', $data['ismed'], array (
                                        'value'=>'1',
                                        ));
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Наличие автомобиля</label>
	    <div class="controls input-append">';
echo CHtml::CheckBox('User[ishasavto]', $data['ishasavto'], array (
    'value'=>'1', 
));
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Пол</label>
	    <div class="controls input-append">';
echo CHtml::radioButtonList('User[isman]', $data['isman'], array (
    0=>'Жен.', 1=>'Муж'), array( 'labelOptions'=>array('style'=>'display:inline'), 'separator' => " | ", 'class'=>'admform span1' )
);
echo '</div></div>';

echo '<h4>КОНТАКТНАЯ ИНФОРМАЦИЯ</h4>';

echo '<div class="control-group">
      <label class="control-label">Телефон</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[mob]', $data['attr']['mob'], array('class'=>'admform span3'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Доп. Телефон</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[addmob]', $data['attr']['addmob'], array('class'=>'admform span3'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Электронная почта</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[email]', $data['attr']['email'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Skype</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[skype]', $data['attr']['skype'], array('class'=>'admform span3'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Место рождения</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[bornplace]', $data['bornplace'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Страница ВКОНТАКТЕ (сылка)</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[vk]', $data['attr']['vk'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Страница Facebook (ссылка)</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[fb]', $data['attr']['fb'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Viber</label>
	    <div class="controls input-append">';
echo CHtml::CheckBox('User[viber]', $data['attr']['viber'], array (
    'value'=>'1',
));
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">ICQ</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[icq]', $data['attr']['icq'], array('class'=>'admform span3'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Другое</label>
	    <div class="controls input-append">';
echo CHtml::textField('User[custcont]', $data['attr']['custcont'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '</div></div>';


echo '<div class="span11">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo '<a href="/admin/site/Users" class="btn btn-warning" id="btn_cancel">Отмена</a>';
//echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div></div>';




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