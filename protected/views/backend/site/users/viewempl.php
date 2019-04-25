<meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=1.0, user-scalable=no">
<img style="padding-top: : -24px; padding-left: 44%;" src="/admin/logo-sm.png"><h3>Администрирование работодателей</h3>
<style type="text/css">
    .label-important {
        background: #dd4b39;
    }
    input {
        border: #ecf0f5;
        width: 60px;
    }
    .manager.glyphicon-envelope{ cursor:pointer }
</style>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="_blank" onclick="export_send()">Экспорт в Excell</a>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="" onclick="export_delete()">Покончить с ними</a>
<?php

echo CHtml::form('/admin/site/UserUpdate?id=0','POST',array("id"=>"form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
echo '<input type="hidden" id="curr_id" name="curr_id">';
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'dvgrid',
  'dataProvider'=>$model->searchempl(),
  'itemsCssClass' => 'table table-bordered table-hover dataTable',
  'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style'=>'padding: 10px; overflow: scroll;'),
  'filter'=>$model,  
  'enablePagination' => true,
  'columns'=>array(
    array(
            'class'=>'CCheckBoxColumn',
            'selectableRows' => 2,
            'checkBoxHtmlOptions' => array('class' => 'checkclass'),
            'value' => '$data->id_user',
        ),
        // array(
        //     'header'=>'Добавлен',
        //     'name' => 'admin',
        //     'value' => '$data->admin',
        //     'type' => 'raw',
        // ),
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
            'header'=> 'Лого',
            'name' => 'type',
            'value' => 'ShowLogo($data->logo)',
            'type' => 'raw',
        ),
        array(
            'header' => 'Город',
            'name' => 'city',
            'value' => '$data->city',
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
            'name' => 'ismoder',
            'value' => 'ShowStatus($data->id_user, $data->ismoder)',
            'type' => 'raw',
            // 'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
        ),
     array(
          'header'=>'Статус',
          'name' => 'isblocked',
          'type' => 'raw',
          'value' => 'ShowBlocked($data->isblocked, $data->id_user)',
          'htmlOptions' => array('class' => '\"isblocked_".$data->isblocked'),
      ),
      array(
            'name' => 'Редактор',
            'value' => 'ShowEdit($data->id_user)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting', 'background-color'=> 'antiquewhite')
        ),
      array(
            'name' => 'Вакансии',
            'value' => 'ShowVaccount($data->id, $data->id_user)',
            'type' => 'raw',
            'filter' => '',
        ),
      array(
            'name' => 'Письмо менеджера',
            'value' => 'getManagerMail($data->accountmail, $data->id_user)',
            'type' => 'html',
            'htmlOptions' => array('style' => 'text-align:center;vertical-align:middle'),
            'filter' => '',
        ),      

)));

echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));
echo CHtml::endForm();



function ShowLogo($photo)
{   
    if($photo !=''){
       return  '<a target="_black" href="/images/company/tmp/' . $photo . '400.jpg" type="button" class="btn btn-default">Лого</a> ';
    }
    else  return  'нету ';
}

function ShowBlocked($blocked, $id_user)
{

    $block_status = ["активен", "заблокирован", "ожидает активации", "активирован", "остановлен к показу"];
    $icon = ["label-success", "label-important", "label-warning", "label-info", "label-primary"];
    $html = '<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $block_status[$blocked] . '">
    <span class="label ' . $icon[$blocked] . '">' . $block_status[$blocked] . '</span>
    <span class="caret"></span>
  </button>';
    if($blocked == 2) {
        return  $html.'</div>';
    }
    $html .= '<ul class="dropdown-menu" style="position: absolute;top: 100%;left: -73px;" aria-labelledby="dropdownMenu1">';
    if($blocked == 3) {
        return  $html .= '</ul></div>';
    }
    for ($i = 0; $i < 5; $i++) {
        if ($i != 2 && $i != 3) {
            $html .= '<li ><a href = "#" onclick = "doStatus(' . $id_user . ', ' . $i . ')" ><span class="label ' . $icon[$i] . '"><i class="icon-off icon-white"></i></span> ' . $block_status[$i] . '</a></li >';
        }
    }
    $html .= '</ul></div>';
    return $html;
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