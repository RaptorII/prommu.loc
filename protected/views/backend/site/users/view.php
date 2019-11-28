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

<img style="padding-top:-24px; padding-left: 44%;" src="/admin/logo-sm.png">
<h3 class="box-title">Администрирование соискателей</h3>
<br>
        <div class="pull-right">
            <?/*<a href="javascript:void(0)" class="btn btn-success" onclick="create()">Создание вакансии</a>*/?>
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
    width: 94px;
}
    .user_logo{
        width: 70px;
        max-width: 70px;
        display: block;
        margin: 0 auto;
    }
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
echo CHtml::form('/admin/site/UserUpdate?id=0', 'POST', array("id" => "form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'dvgrid',
    'dataProvider' => $model->searchpr(),
    'itemsCssClass' => 'table table-bordered table-hover custom-table',
    'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid'),
    'filter' => $model,
    'enablePagination' => true,
    'rowCssClassExpression' => function($row, $data)
    {
      return ($data->is_new ? 'new-row' : '');
    },
    'columns' => array(
         array(
            'class'=>'CCheckBoxColumn',
            'selectableRows' => 2,
            'checkBoxHtmlOptions' => array('class' => 'checkclass'),
            'value' => '$data->id_user',
        ),
        array(
            'header'=>'Соискатель',
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'raw',
        ),
        array(
            'header'=>'Фамилия',
            'name' => 'lastname',
            'value' => '$data->lastname',
            'type' => 'raw',
        ),
       array(
            'header'=>'Имя',
            'name' => 'firstname',
            'value' => '$data->firstname',
            'type' => 'raw',
        ),
       array(
            'header'=>'Дата рождения',
            'name' => 'birthday',
            'value' => 'ShowBirthday($data->id, $data->birthday)',
            'type' => 'raw',
        ),
       array(
            'header'=>'Фото',
            'name' => 'photo',
            'value' => 'ShowPhoto($data->id_user,$data->photo, $data->isman)',
            'type' => 'html',
        ),
       array(
            'header'=>'Город',
            'name' => 'city',
            'value' => 'AdminView::getUserCities($data->id_user)',
            'filter' => false,
            'type' => 'raw',
        ),
       array(
            'header'=>'Регистрация',
            'name' => 'date_public',
            'value' => '$data->date_public',
            'type' => 'raw',
        ),
        array(
            'header'=>'Дата изменения',
            'name' => 'mdate',
            'value' => '$data->mdate',
            'type' => 'raw',
        ),
        array(
          'header'=>'Модерация',
          'name' => 'status',
          'type' => 'raw',
          'filter' => CHtml::dropDownList(
            'Promo[ismoder]',
            isset($_GET['Promo']['ismoder']) ? $_GET['Promo']['ismoder'] : '',
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
            'Promo[isblocked]',
            isset($_GET['Promo']['isblocked']) ? $_GET['Promo']['isblocked'] : '',
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
            'htmlOptions' => array('style' => 'width:5%;padding:0', 'class' => 'sorting')
        ),
        array(
            'name' => 'Проекты',
            'value' => '0',
            'type' => 'raw',
            'filter' => '',
        ),
        [
          'header'=>'Просмотрено',
          'filter' => CHtml::dropDownList(
            'Promo[is_new]',
            isset($_GET['Promo']['is_new']) ? $_GET['Promo']['is_new'] : '',
            [''=>'Все', '1'=>'Новые', '0'=>'Просмотреные']
          ),
          'name' => 'is_new',
          'value' => 'getLabel($data->is_new)',
          'type' => 'raw',
          'htmlOptions' => ['style'=>'width:3%']
        ]
    )));

echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));
//
function getLabel($s)
{
  return '<span class="glyphicon ' . ($s ? 'glyphicon-flash' : '') . '"></span>';
}
//
function ShowBirthday($id, $birthday)
{   
    if($birthday == '1970-01-01'){
        $dateYear = rand(1985, 2001);
        $dateMonth = rand(1, 12);
        $dateDay = rand(1, 31);
        $dateBirth = "{$dateYear}-{$dateMonth}-{$dateDay}";
        $dates = date('Y-m-d', strtotime($dateBirth));
        $res = Yii::app()->db->createCommand()
                    ->update('resume', array( 'birthday' => $dates,
                        
                    ), 'id = :id', array(':id' => $id));
       return $dates;
    }
   else return $birthday;
}

function ShowPhoto($id_user,$photo,$sex)
{
    $src = Share::getPhoto($id_user,2,$photo,'small',$sex);
    $srcBig = Share::getPhoto($id_user,2,$photo,'big',$sex);
    return (!$srcBig ? '-' 
        : CHtml::link(CHtml::image($src),$srcBig,['class'=>'user_logo']));
}

function ShowVaccount($id_user)
{
        // читаем вакансии
        $sql = "SELECT COUNT(*) cou FROM empl_vacations v WHERE v.id_user = {$id_user} AND v.status = 1 AND v.ismoder = 100 AND v.in_archive=0";
        return Yii::app()->db->createCommand($sql)->queryScalar();
}

function ShowEdit($id) {
    return  '<a href="/admin/site/PromoEdit/' . $id . '" type="button" class="btn btn-default">Редактировать</a> ';
}
echo CHtml::endForm();
?>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="_blank" onclick="export_send()">Экспорт в Excell</a>

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
        $("#form").attr("action", "/admin/site/PromoBlocked/" + id);
        $("#btn_submit").click();
    }

    function doStatusModer(id, st) {
        $("#curr_status").val(st);
        $("#form").attr("action", "/admin/site/PromoChangeModer/" + id);
        $("#btn_submit").click();
    }

    function export_send() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/ExportPromo";
        document.forms['form'].submit();
    }
    function export_delete() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/DeletePromo";
        document.forms['form'].submit();
    }
    
</script>

<form method="POST" action="/admin/users?export_xls=Y" id="export_form">
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
    <label class="d-label">
        <span>Даты рождения</span>
        <input type="radio" name="export_date" value="create" checked>
    </label>
    <div class="row">
        <div class="col-xs-6">
            <label class="d-label">
                <span>Период с</span>
                <input type="date" name="birthday_beg_date">
            </label>  
        </div>
        <div class="col-xs-6">
            <label class="d-label">
                <span>по</span>
                <input type="date" name="birthday_end_date">
                
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
