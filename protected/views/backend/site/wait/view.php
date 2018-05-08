<img style="padding-top: : -24px; padding-left: 44%;" src="/admin/logo-sm.png">
<h3 class="box-title">Брошенные регистрации</h3>
<style type="text/css">
    .label-important {
        background: #dd4b39;
    }
    input {
    border: #ecf0f5;
    width: 94px;
}
</style>

<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#"  onclick="mail_send()">Отправить напоминание</a>

<!-- <a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#"  onclick="mail_send_all()">Отправить напоминание всем</a> -->
<?php
echo CHtml::form('/admin/site/UserUpdate?id=0', 'POST', array("id" => "form", "name"=> "form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
echo '<input type="hidden" id="curr_id" name="curr_id">';
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'dvgrid',
    'dataProvider' => $model->search(),
     'itemsCssClass' => 'table table-bordered table-hover dataTable',
     'htmlOptions' => array('class' => 'table table-bordered table-hover dataTable', 'name' => 'my-grid'),
    // 'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style'=>    'padding: 10px;'),
    'filter' => $model,
    'enablePagination' => true,
    'columns' => array(
         array(
            'class'=>'CCheckBoxColumn',
            'selectableRows' => 2,
            'checkBoxHtmlOptions' => array('class' => 'checkclass'),
            'value' => '$data->id_user',
        ),
           array(
            'header'=>'Регистрация',
            'name' => 'access_time',
            'value' => '$data->access_time',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
        ),
        array(
            'header'=>'Пользователь',
            'name' => 'id_user',
            'value' => '$data->id_user',
            'type' => 'raw',
            // 'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting'),
        ),
         array(
            'header'=>'Последняя рассылка',
            'name' => 'mdate',
            'value' => '$data->mdate',
            'type' => 'raw',
            // 'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting'),
        ),
        array(
            'header'=>'Ел. почта',
            'name' => 'email',
            'value' => '$data->email',
            'type' => 'raw',
            // 'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
        ),
        array(
            'header'=>'Тип',
            'name' => 'status',
            'value' => 'ShowType($data->status)',
            'type' => 'raw',
            // 'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
        ),
       
        // array(
        //     'header'=>'Статус',
        //     'name' => 'status',
        //     'value' => 'ShowStatus($data->id_user, $data->ismoder)',
        //     'type' => 'raw'
        //     // 'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
        // ),
        array(
            'header'=>'Модерация',
            'name' => 'isblocked',
            'type' => 'raw',
            'value' => 'ShowBlocked($data->isblocked, $data->id_user)',
            'filter'=> '',               // 'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting'),

        ),
       //  array(
       //      'name' => 'Редактор',
       //      'value' => 'ShowEdit($data->id_user)',
       //      'type' => 'raw',
       //      'filter' => '',
       //      'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
       //  ),

    )));
echo '<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#"  onclick="mail_send()">Отправить напоминание</a>';
echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));



function ShowBlocked($blocked, $id_user)
{

    $block_status = ["полностью активен", "заблокирован", "ожидает активации", "активирован", "остановлен к показу"];
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

function ShowType($type)
{
        if($type == 2){
          return 'Соискатель';
        }
        else return 'Работодатель';

}


function ShowVaccount($id_user)
{
        // читаем вакансии
        $sql = "SELECT COUNT(*) cou FROM empl_vacations v WHERE v.id_user = {$id_user} AND v.status = 1 AND v.ismoder = 100";
        return Yii::app()->db->createCommand($sql)->queryScalar();
}

function ShowStatus($id_user, $ismoder)
{
$status = ['не обработан','просмотрен'];
    $st_ico = ["label-warning", "label-success"];
    $html = 
    '<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $status[$ismoder] . '">
    <span class="label ' . $st_ico[$ismoder] . '">'.$status[$ismoder].'</span>
    <span class="caret"></span>
    </button>';
    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
    for ($i = 0; $i < 2; $i++) {
        $html .= '<li ><a href = "#" onclick = "doStatusModer(' . $id_user . ', ' . $i . ')" ><span class="label ' . $st_ico[$i] . '"><i class="icon-star icon-white"></i></span> ' . $status[$i] . '</a></li >';
    }
    $html .= '</ul></div>';
    return $html;
}

function ShowName($id,$type)
{
        if($type == 2){
            $sql = "SELECT r.firstname, r.lastname FROM resume r WHERE r.id_user = {$id}";
            $res =  Yii::app()->db->createCommand($sql)->queryAll();
            return $res['firstname'].$res['lastname'];
        }
        else {
             $sql = "SELECT e.name FROM employer e WHERE e.id_user = {$id}";
             $res =  Yii::app()->db->createCommand($sql)->queryAll();
             return $res['name'];
        }
    

}


function ShowEdit($id) {
    return  '<button type="button" class="btn btn-default"><a href="/admin/site/PromoEdit/' . $id . '">Редактировать</a></button> ';
}
echo CHtml::endForm();
?>
<!-- <a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="_blank" onclick="export_send()">Экспорт в Excell</a> -->
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
        document.forms['form'].action = "/admin/site/MailCloud";
        document.forms['form'].submit();
    }

    function mail_send_all() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/MailCloudAll";
        document.forms['form'].submit();
    }
</script>