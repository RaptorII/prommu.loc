<img style="padding-top:-24px; padding-left: 44%;" src="/admin/logo-sm.png">
<h3 class="box-title">Администрирование модераторов</h3>
<style type="text/css">
    .label-important {
        background: #dd4b39;
    }
    input {
    border: #ecf0f5;
    width: 94px;
}
</style>
<!-- <a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="_blank" onclick="export_send()">Экспорт в Excell</a>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="" onclick="export_delete()">Покончить с ними</a> -->
<?php
echo CHtml::form('/admin/site/UserUpdate?id=0', 'POST', array("id" => "form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'dvgrid',
    'dataProvider' => $model->search(),
     'itemsCssClass' => 'table table-bordered table-hover dataTable',
    'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style'=>    'padding: 10px; overflow: scroll;'),
    'filter' => $model,
    'enablePagination' => true,
    'columns' => array(
         array(
            'class'=>'CCheckBoxColumn',
            'selectableRows' => 2,
            'checkBoxHtmlOptions' => array('class' => 'checkclass'),
            'value' => '$data->id',
        ),
        //  array(
        //     'header'=>'Добавлен',
        //     'name' => 'admin',
        //     'value' => '$data->admin',
        //     'type' => 'raw',
        // ),
        array(
            'header'=>'ID',
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'raw',
        ),
        array(
            'header'=>'Логин',
            'name' => 'login',
            'value' => '$data->login',
            'type' => 'raw',
        ),
       array(
            'header'=>'Email',
            'name' => 'email',
            'value' => '$data->email',
            'type' => 'raw',
        ),
       array(
            'header'=>'Доступы',
            'name' => 'access',
            'value' => '$data->access',
            'type' => 'raw',
        ),

        array(
            'name' => 'Редактор',
            'value' => 'ShowEdit($data->id)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
        ),
    
    )));



function ShowBlocked($blocked, $id_user, $ismoder)
{

    $block_status = ["полностью активен", "заблокирован", "ожидает активации", "активирован", "остановлен к показу"];
    $icon = ["label-success", "label-important", "label-warning", "label-info", "label-primary"];
    $html = '<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $block_status[$blocked] . '">
    <span class="label ' . $icon[$blocked] . '">' . $block_status[$blocked] . '</span>
    <span class="caret"></span>
  </button>';
   
    $html .= '<ul class="dropdown-menu" style="position: absolute;top: 100%;left: -73px;" aria-labelledby="dropdownMenu1">';

    for ($i = 0; $i < 5; $i++) {
        if ($i != 2 && $i != 3) {
            $html .= '<li ><a href = "#" onclick = "doStatus(' . $id_user . ', ' . $i . ')" ><span class="label ' . $icon[$i] . '"><i class="icon-off icon-white"></i></span> ' . $block_status[$i] . '</a></li >';
        }
    }
    $html .= '</ul></div>';
    return $html;
}

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

function ShowPhoto($photo)
{   
    if($photo !=''){
       return  '<a target="_black" href="/images/applic/' . $photo . '400.jpg" type="button" class="btn btn-default">Фото</a> ';
    }
    else  return  'нету ';
}

function ShowVaccount($id_user)
{
        // читаем вакансии
        $sql = "SELECT COUNT(*) cou FROM empl_vacations v WHERE v.id_user = {$id_user} AND v.status = 1 AND v.ismoder = 100 AND v.in_archive=0";
        return Yii::app()->db->createCommand($sql)->queryScalar();
}



function ShowStatus($id_user, $ismoder)
{
$status = ['не обработан','обработан'];
    $st_ico = ["label-warning", "label-success"];
    $html = 
    '<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $status[$ismoder] . '">
    <span class="label ' . $st_ico[$ismoder] . '">' . $status[$ismoder] . '</span>
    <span class="caret"></span>
    </button>';
    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
    for ($i = 0; $i < 2; $i++) {
        $html .= '<li ><a href = "#" onclick = "doStatusModer(' . $id_user . ', ' . $i . ')" ><span class="label ' . $st_ico[$i] . '"><i class="icon-star icon-white"></i></span> ' . $status[$i] . '</a></li >';
    }
    $html .= '</ul></div>';
    return $html;
}

function ShowEdit($id) {
    return  '<a href="/admin/site/AdminEdit/' . $id . '" type="button" class="btn btn-default">Редактировать</a> ';
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