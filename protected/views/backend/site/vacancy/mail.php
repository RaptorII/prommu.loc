<img style="padding-top: : -24px; padding-left: 44%;" src="/admin/logo-sm.png"><h3 class="box-title">Администрирование вакансий</h3>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#"  onclick="mail_send()">Отправить напоминание</a>

<style type="text/css">
    .label-important {
        background: #dd4b39;
    }
    input {
        border: #ecf0f5;
        width:80px;
    }
</style>
<?php
echo CHtml::form('/admin/site/UserUpdate?id=0','POST',array("id"=>"form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'dvgrid',
  'dataProvider'=>$model->searchvac(),
  'itemsCssClass' => 'table table-bordered table-hover dataTable',
  'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style' =>    'padding: 10px;'),
  'filter'=>$model,  
  'enablePagination' => true,
  'columns'=>array(
            array(
                'class'=>'CCheckBoxColumn',
                'selectableRows' => 2,
                'checkBoxHtmlOptions' => array('class' => 'checkclass'),
                'value' => '$data->id',
            ),
            array(
                'header'=>'Вакансия',
                'name' => 'id',
                'value' => '$data->id',
                'type' => 'html',
                'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
            ),
            array(
                'header'=>'Работодатель',
                'name' => 'id_empl',
                'type'=>'raw',
                'value'=>'CHtml::tag("span",array(),GetEmployerName($data->id_empl) . " (" . $data->id_empl . ")")',
                'htmlOptions' => array('style' => 'width: 50px; text-align: center;')
            ),
            array(
                'header'=>'Название',
                'name' => 'title',
                'value' => 'ShowVacancy($data->id, $data->title)',
                'type' => 'html',
                'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),

            ),
            array(
                'header'=>'Город',
                'name' => 'city',
                'type'=>'raw',
                'value'=>'CHtml::tag("span",array(),implode(", ", GetVacCities($data->id)))',
                'htmlOptions' => array('style' => 'width: 50px; text-align: center;')
            ),
            array(
                'header'=>'Создана',
                'name' => 'crdate',
                'value' => '$data->crdate',
                'type' => 'html',
                'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
            ),
            array(
                'header'=>'Дата рассылки',
                'name' => 'remdate',
                'value' => '$data->mdate',
                'type' => 'html',
                'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
            ),
            array(
                'header'=>'Статус',
                'name' => 'status',
                'value' => 'ShowStatus($data->id,$data->status)',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),

            ),
            array(
                'header'=>'Модерация',
                'name' => 'ismoder',
                'value' => 'ShowStatusModer($data->id,$data->ismoder)',
                'type' => 'raw',
                'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),

            ),
            array(
            'name' => 'Редактор',
            'value' => 'ShowEdit($data->id)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting', 'background-color'=> 'antiquewhite')
          ),
             array(
            'name' => 'Удалить',
            'value' => 'ShowDelete($data->id)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting', 'background'=> '#ef0018')
        ),

)));
echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));

function ShowStatus($id, $stat)
{
$status = ['не активна','активна'];
    $st_ico = ["label-warning", "label-success"];
    $html = 
    '<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $status[$stat] . '">
    <span class="label ' . $st_ico[$stat] . '">'.$status[$stat] .'</span>
    <span class="caret"></span>
    </button>';
    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
    for ($i = 0; $i < 2; $i++) {
        $html .= '<li ><a href = "#" onclick = "doStatusModer(' . $id . ', ' . $i . ')" ><span class="label ' . $st_ico[$i] . '"><i class="icon-star icon-white"></i></span> ' . $status[$i] . '</a></li >';
    }
    $html .= '</ul></div>';
    return $html;
}

function ShowVacancy($id, $title){
   return  "<a href='/admin/site/VacancyEdit/$id'>$title</a> ";
}
function ShowStatusModer($id, $stat)
{
    if($stat == 100){
        $stat = 1;
    }
    elseif($stat == 200){
        $stat = 0;
    }
    else $stat = 0;
    $status = ['не просмотрена','просмотрена'];
    $st_ico = ["label-warning", "label-success"];
    $html = 
    '<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $status[$stat] . '">
    <span class="label ' . $st_ico[$stat] . '">'.$status[$stat].'</span>
    <span class="caret"></span>
    </button>';
    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
    for ($i = 0; $i < 2; $i++) {
        if($i == 0){
        $stat = 200;
    }
    elseif($i == 1){
        $stat = 100;
    }
        $html .= '<li ><a href = "#" onclick = "doStatus(' . $id . ', ' . $stat . ')" ><span class="label ' . $st_ico[$i] . '"><i class="icon-star icon-white"></i></span> ' . $status[$i] . '</a></li >';
    }
    $html .= '</ul></div>';
    return $html;
}

function ShowEdit($id) {
    return  '<button type="button" class="btn btn-default"><a href="/admin/site/VacancyEdit/' . $id . '">Редактировать</a></button> ';
}

function ShowDelete($id) {
    return  '<button type="button" onclick = "doDelete(' . $id . ')" class="btn btn-default">Удалить</button> ';

}
function GetEmployerName($id){
    $emp = Yii::app()->db->createCommand()
        ->select('name')
        ->from('employer')
        ->where('id=:id', array(':id'=>$id))
        ->queryRow();
    return  html_entity_decode($emp['name']);
}
function GetVacCities($id){
    $arCities = Yii::app()->db->createCommand()
        ->select('c.name')
        ->from('empl_city ec')
        ->where('id_vac=:id', array(':id'=>$id))
        ->join('city c','ec.id_city=c.id_city')
        ->limit(1000)
        ->queryAll();

    $arRes = array();
    if(sizeof($arCities)>0)
        foreach($arCities as $k => $val)
            array_push($arRes, $val['name']);

    return $arRes;
}
echo CHtml::endForm();
?>
<!-- <a href="#" onclick="export_send()">Экспорт в Excel</a> -->
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

    function mail_send() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/VacCloud";
        document.forms['form'].submit();
    }

    function doDelete(id) {
        $("#form").attr("action", "/admin/site/VacancyDelete/" + id);
        $("#btn_submit").click();
    }
</script>

