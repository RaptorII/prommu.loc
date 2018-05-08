<?php

Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);

echo '<div class="content">
<h3><i>Редактирование карточки пользователя</i> ID='.$data['id'].'</h3>';

echo CHtml::form('','post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
//echo CHtml::hiddenField('field_lang', $lang, array('type'=>"hidden"));
//echo CHtml::hiddenField('pagetype', $pagetype, array('type'=>"hidden"));

echo '<div class="col-md-12">';
echo '<div class="col-md-6">';
echo '<h4 style="font-size: 40px;font-weight: 100;">Личные данные</h4>';


echo '<div class="control-group">
      <label class="control-label">Фамилия</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[fff]', $data['fff'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Имя</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[iii]', $data['iii'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Отчество</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[ooo]', $data['ooo'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Дата рождения</label>
      <div class="controls input-append">';
echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('name'=>'Card[borndate]', 'value'=>$data['borndate'], 'language'=>'ru', 'id'=>'borndate', 'options'=>array('dateFormat'=>'dd.mm.yy', 'minDate'=>'12.12.43'),), true);
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Место рождения</label>
      <div class="controls input-append">';
echo CHtml::textField('Card[bornplace]', $data['bornplace'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Телефон</label>
      <div class="controls input-append">';
echo CHtml::textField('Card[tel]', $data['tel'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<h4 style="font-size: 40px;font-weight: 100;">Паспортные данные</h4>';

echo '<div class="control-group">
      <label class="control-label">Тип удостоверения личности (21-паспорт)</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[doctype]', $data['doctype'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Серия паспорта</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[docser]', $data['docser'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Номер паспорта</label>
      <div class="controls input-append">';
echo CHtml::textField('Card[docnum]', $data['docnum'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Дата выдачи (дд.мм.гггг)</label>
	    <div class="controls input-append">';
echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('name'=>'Card[docdate]', 'value'=>$data['docdate'], 'language'=>'ru', 'id'=>'docdate', 'options'=>array('dateFormat'=>'dd.mm.yy', 'minDate'=>'Today'),), true);
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Код подразделения выдавший документ (паспорт)</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[docorgcode]', $data['docorgcode'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Адрес прописки</label>
      <div class="controls input-append">';
echo CHtml::textField('Card[regaddr]', $data['regaddr'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Адрес фактического проживания</label>
      <div class="controls input-append">';
echo CHtml::textField('Card[liveaddr]', $data['liveaddr'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Кем выдан документ (паспорт)</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[docorgname]', $data['docorgname'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div></div>';

echo '<div class="col-md-6">';

echo '<h4 style="font-size: 40px;font-weight: 100;">Общая информация</h4>';

echo '<div class="control-group">
      <label class="control-label">Фирма</label>
      <div class="controls input-append">';
echo CHtml::textField('Card[name]', $data['name'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Должность</label>
      <div class="controls input-append">';
echo CHtml::textField('Card[post]', $data['post'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Код страны</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[regcountry]', $data['regcountry'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Дата создания</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[crdate]', $data['crdate'], array('class'=>'form-control', 'readonly'=>true));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Комментарий</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[comment]', $data['comment'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Статус</label>
        <div class="controls input-append">';
echo CHtml::textField('Card[status]', $data['status'],array('class'=>'form-control'));
echo '  <span class="add-on">0 - новый, 1 - просмотрен, 2 - отменен, 3 - обработка, 4 - не хватает данных, 5 - выполнен. Если ни один из статусов проставлен не будет (по умолчанию 0 при сохранении сменится на 3 - обработка)</span>';

echo '</div></div>';




echo '<div class="control-group">
      <label class="control-label">Комментарий админа</label>
        <div class="controls input-append">';
        
echo CHtml::textArea('Card[comad]', $data['coma'], array('rows' => 6, 'cols' => 50,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

$comad = explode("<br/>", $data['comad']);
$count = count($comad);
for ($i=0; $i < $count; $i++) { 
echo '<div class="control-group">
      
      <div class="controls input-append">';
echo CHtml::textField('Card[ddaaad]', $comad[$i], array('class'=>'form-control','rows' => 8, 'cols' => 80, 'readonly'=>true));
echo '  <span class="add-on"></span>';
echo '</div></div>';
}



echo '<div class="control-group">
      <label class="control-label">Сканы</label>
	    <div class="controls">';

$lst = json_decode($data['files']);
    echo '<ul class="controls" id="lst_scan">';
if(!empty($lst)) {
    $i = 1;
    foreach ($lst as $r) {
        echo '<li><a class="btn btn-success" href="' . $r->orig . '" target="_blank">Документ ' . $i . ' просмотр</a>';
        echo '<a href="javascript:Del(' . $i . ')" class="btn btn-warning">Удалить</a>';
        echo '</li>';
        $i++;
    }
}
    echo '</ul>';


echo '<div class="controls input-append">';
echo '<input id="fileToUpload" type="file" name="fileToUpload" class="form-control"/>';
echo '</div>';
echo '<button class="btn btn-success" id="buttonUpload" onclick="return ajaxFileUpload();">Загрузить</button>';
//echo '<a class="controls btn btn-success" href="javascript:add_doc()" >Добавить скан документа</a>';
echo '</div></div></div></div>';


echo '<div class="span11">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo '<a href="/admin/site/cards" class="btn btn-warning" id="btn_cancel">Отмена</a>';
//echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div></div>';

function ShowStatusCard($blocked, $id_user)
{

    $block_status = ["новый","просмотрен", "отменен", "обработка", "не хватает данных", "выполнен"];
    $icon = ["label-success", "label-warning", "label-important", "label-info", "label-primary","label-info","label-success"];
    $html = '<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $block_status[$blocked] . '">
    <span class="label ' . $icon[$blocked] . '">' . $block_status[$blocked] . '</span>
    <span class="caret"></span>
  </button>';

    $html .= '<ul class="dropdown-menu" style="position: absolute;top: 100%;left: -73px;" aria-labelledby="dropdownMenu1">';
    for ($i = 0; $i < 6; $i++) {
       
            $html .= '<li ><a href = "#" onclick = "doStatusCard(' . $id_user . ', ' . $i . ')" ><span class="label ' . $icon[$i] . '"><i class="icon-off icon-white"></i></span> ' . $block_status[$i] . '</a></li >';
        
    }
    $html .= '</ul></div>';
    return $html;
}


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