<?
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCssFile($bUrl . '/css/template.css');
  $gcs->registerCssFile($bUrl . '/css/vacancy/list.css');
  $gcs->registerScriptFile($bUrl . '/js/vacancy/list.js', CClientScript::POS_HEAD);
  Yii::app()->clientScript->registerScript(
    're-install-date-picker',
    "function reinstallDatePicker(id, data){
      $('.grid_date').datepicker(jQuery.extend(jQuery.datepicker.regional['ru'],{changeMonth:true}));
    }");
?>

<meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=1.0, user-scalable=no">
<img style="padding-top: : -24px; padding-left: 44%;" src="/admin/logo-sm.png"><h3>Администрирование работодателей</h3>
<br>
        <div class="pull-right">
            <a href="javascript:void(0)" class="btn btn-success export_btn">Экспорт в Excell</a>
        </div>
        <div class="clearfix"></div>
<br>

<style type="text/css">
    .label-important {
        background: #dd4b39;
    }
    input {
        border: #ecf0f5;
        width: 60px;
    }
    .manager.glyphicon-envelope{ cursor:pointer }
    .user_logo img{
        width: 100%;
        height: auto;
        border-radius: 50%;
    }
    .custom-table .dropdown-menu.ismoder li{
      width: 130px;
    }
</style>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="" onclick="export_delete()">Покончить с ними</a><br><br>
<?php

echo CHtml::form('/admin/site/UserUpdate?id=0','POST',array("id"=>"form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
echo '<input type="hidden" id="curr_id" name="curr_id">';
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'dvgrid',
  'dataProvider'=>$model->searchempl(),
  'itemsCssClass' => 'table table-bordered table-hover custom-table',
  'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid'),
  'filter'=>$model,  
  'enablePagination' => true,
  'rowCssClassExpression' => function($row, $data)
  {
    return ($data->is_new ? 'new-row' : '');
  },
  'columns'=>array(
    array(
            'class'=>'CCheckBoxColumn',
            'selectableRows' => 2,
            'checkBoxHtmlOptions' => array('class' => 'checkclass'),
            'value' => '$data->id_user',
        ),
        array(
            'header' => 'id',
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'raw',
            'htmlOptions'=>array('style'=>'width: 50px; text-align: center;'),
        ),
        array(
            'header' => 'Название',
            'name' => 'name',
            'value' => '$data->name',
            'type' => 'raw',
        ),
        array(
            'header' => 'Имя',
            'name' => 'firstname',
            'value' => '$data->firstname',
            'type' => 'raw',
        ),
        array(
            'header' => 'Фамилия',
            'name' => 'lastname',
            'value' => '$data->lastname',
            'type' => 'raw',
        ),
        array(
            'header'=> 'Тип',
            'name' => 'type',
            'value' => 'ShowType($data->type, $data->id_user)',
            'type' => 'raw',
        ),
        array(
            'header'=> 'Фото',
            'name' => 'logo',
            'value' => 'ShowLogo($data->id_user,$data->logo)',
            'type' => 'raw',
        ),
        array(
          'header'=>'Город',
          'name' => 'city',
          'value' => 'AdminView::getUserCities($data->id_user)',
          'filter' => false,
          'type' => 'raw',
        ),
        array(
            'header'=> 'Регистрация',
            'name' => 'crdate',
            'value' => '$data->crdate',
            'type' => 'raw',
        ),
        array(
            'header'=> 'Дата изменения',
            'name' => 'mdate',
            'value' => '$data->mdate',
            'type' => 'raw',
        ),
      array(
        'header'=>'Модерация',
        'name' => 'status',
        'type' => 'raw',
        'filter' => CHtml::dropDownList(
          'Employer[ismoder]',
          isset($_GET['Employer']['ismoder']) ? $_GET['Employer']['ismoder'] : '',
          User::getAdminArrIsmoder(true)
        ),
        'value' => 'AdminView::getUserStatus($data->ismoder, $data->id_user, "ismoder")',
        'htmlOptions' => ['style'=>'width:5%;padding:0']
      ),
      array(
        'header'=>'Статус',
        'name' => 'isblocked',
        'type' => 'raw',
        'filter' => CHtml::dropDownList(
          'Employer[isblocked]',
          isset($_GET['Employer']['isblocked']) ? $_GET['Employer']['isblocked'] : '',
          User::getAdminArrIsblocked(true)
        ),
        'value' => 'AdminView::getUserStatus($data->isblocked, $data->id_user, "isblocked")',
        'htmlOptions' => ['style'=>'width:5%;padding:0']
      ),
      array(
            'name' => 'Редактор',
            'value' => 'ShowEdit($data->id_user)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width:5%;padding:0', 'class' => 'sorting', 'background-color'=> 'antiquewhite')
        ),
      array(
            'name' => 'Вакансии',
            'value' => 'ShowVaccount($data->id, $data->id_user)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => ['style'=>'width:3%']
        ),
      array(
            'name' => 'Письмо менеджера',
            'value' => 'getManagerMail($data->accountmail, $data->id_user)',
            'type' => 'html',
            'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle'),
            'filter' => '',
            'htmlOptions' => ['style'=>'width:3%']
        ),
      [
        'header'=>'Просмотрено',
        'filter' => CHtml::dropDownList(
          'Employer[is_new]',
          isset($_GET['Employer']['is_new']) ? $_GET['Employer']['is_new'] : '',
          [''=>'Все', '1'=>'Новые', '0'=>'Просмотреные']
        ),
        'name' => 'is_new',
        'value' => 'getLabel($data->is_new)',
        'type' => 'raw',
        'htmlOptions' => ['style'=>'width:3%']
      ]
)));

echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));
echo CHtml::endForm();
//
function getLabel($s)
{
  return '<span class="glyphicon ' . ($s ? 'glyphicon-flash' : '') . '"></span>';
}
//
function ShowLogo($id_user,$photo)
{   
    $src = Share::getPhoto($id_user,2,$photo,'small');
    $srcBig = Share::getPhoto($id_user,2,$photo,'big');
    return (!$srcBig ? '-' 
        : CHtml::link(CHtml::image($src),$srcBig,['class'=>'user_logo']));
}

function ShowVaccount($id,$id_user)
{
        // читаем вакансии
        $sql = "SELECT COUNT(*) cou FROM empl_vacations v WHERE v.id_user = {$id_user}";
        $res =  Yii::app()->db->createCommand($sql)->queryScalar();
    if($res == 0){
        return '<p>нет</p>';
    }
$link = '/admin/site/vacancy?Vacanc/?id=Vacancy&Vacancy[id]=&Vacancy[id_empl]='.$id.'&Vacancy[title]=&Vacancy[city]=&Vacancy[crdate]=&Vacancy[remdate]=&Vacancy[status]=&Vacancy_page=1&ajax=dvgrid&id_empl='.$id;
$result = '<a href='. $link.'>'.$res.'</a>';
return $result;
}


function ShowType($type, $id_user)
{

    $types = ["Прямой работодатель", "Ивент агенство", "Кадровое агенство", "Рекламное агенство", "Рекрутинговое агенство", "Модельное агенство"];

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

    return $types[0];
}

function ShowEdit($id) {
    return  '<a href="/admin/site/EmplEdit/' . $id . '" type="button" class="btn btn-default">Редактировать</a> ';
}
function getManagerMail($e, $id){
    $icon = !$e ? 'envelope text-info' : 'ok text-success';
    $title = !$e ? 'Отправить' : 'Отправлено';
    return '<span class="glyphicon glyphicon-' . $icon 
        . ' manager" title="' . $title . '"><i class="hide">' . $id . '</i></span>';
}

?>

<script type="text/javascript">
    function onchangeLang(sel) {
        var value = sel.options[sel.selectedIndex].value;
        $("#field_lang").val(value);
        //alert(value);
        $("#form").attr("action", "/admin/site/pages");
        $("#btn_submit").click();
    }

    function doStatus(id, st) {
        $("#curr_status").val(st);
        $("#form").attr("action", "/admin/site/EmplBlocked/" + id);
        $("#btn_submit").click();
    }

    function doStatusModer(id, st) {
        $("#curr_status").val(st);
        $("#form").attr("action", "/admin/site/EmplChangeModer/" + id);
        $("#btn_submit").click();
    }

    function export_send() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/ExportEmpl";
        document.forms['form'].submit();
    }

    function export_delete() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/DeleteEmpl";
        document.forms['form'].submit();
    }
    /*
    *
    */
    // отправить письмо менеджера
    $('#form').on('click','.manager', function(){
        var self = this,
            id = $(self).find('i').text(),
            arParams = [];

        arParams.push({name:'param',value:'accountmail'});
        arParams.push({name:'id',value:id});

        if($(self).hasClass('glyphicon-ok')){
            alert('Письмо уже отправлялось');
            return false;
        }

        $.ajax({
            type: 'GET',
            url: '/admin/ajax/sendPrivateManagerMail',
            data: arParams,
            dataType: 'json',
            success: function(r){
                console.log(r);
                if(r.error==0 && r.sendmail==1){
                    alert('Письмо отправлено работодателю');
                    $(self).removeClass('glyphicon-envelope text-info')
                        .addClass('glyphicon-ok text-success')
                        .attr('title','Отправлено');
                }
                else
                    alert('При отправке произошла ошибка попробуйте еще раз');
            }
        })
    });
</script>

<form method="POST" action="/admin/empl?export_xls=Y" id="export_form">
    <label class="d-label">
        <span>Даты регистрации</span>
        <input type="radio" name="export_date" value="create" checked>
    </label>
    <div class="row">
        <div class="col-xs-6">
            <label class="d-label">
                <span>Период с</span>
                <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'name'=>'export_beg_date',
                        'options'=>['changeMonth'=>true],
                        'htmlOptions'=>[
                            'id'=>'export_beg_date',
                            'class'=>'form-control',
                            'autocomplete'=>'off'
                        ]
                    ));
                ?>
            </label>  
        </div>
        <div class="col-xs-6">
            <label class="d-label">
                <span>по</span>
                <?php
                    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                        'name'=>'export_end_date',
                        'options'=>['changeMonth'=>true],
                        'htmlOptions'=>[
                            'id'=>'export_end_date',
                            'class'=>'form-control',
                            'autocomplete'=>'off'
                        ]
                    ));
                ?>
            </label>  
        </div>
        <div class="hidden-xs col-sm-6"></div>
    </div>
    <br>
    <div class="export_form-radio">
        <label class="d-label">
            <span>все</span>
            <input type="radio" name="export_status" value="all" checked>
        </label>
        <label class="d-label">
            <span>активные</span>
            <input type="radio" name="export_status" value="active">
        </label>                
        <label class="d-label">
            <span>не активные</span>
            <input type="radio" name="export_status" value="no_active">
        </label>
    </div>
    <br>
    <div class="text-center">
        <button type="submit" class="btn btn-success export_start_btn">Выгрузить</button>
    </div>
    <div class="export_form-close">&#10006</div>
</form>
<div class="bg_veil"></div>
