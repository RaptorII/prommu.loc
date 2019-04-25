<img style="padding-top: : -24px; padding-left: 44%;" src="/admin/logo-sm.png">
<h3 class="box-title">Отзывы</h3>
<style type="text/css">
    .label-important {
        background: #dd4b39;
    }
    input {
    border: #ecf0f5;
    width: 94px;
}
</style>

<?php
echo CHtml::form('/admin/site/UserUpdate?id=0', 'POST', array("id" => "form", "name"=> "form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
echo '<input type="hidden" id="curr_id" name="curr_id">';
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'dvgrid',
    'dataProvider' => $model->search(),
     'itemsCssClass' => 'table table-bordered table-hover dataTable',
    'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style'=>    'padding: 10px; overflow: scroll;'),
    'filter' => $model,
    'enablePagination' => true,
    'columns' => array(
     
           array(
            'header'=>'id',
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'html',
        ),
        array(
            'header'=>'Дата',
            'name' => 'crdate',
            'value' => '$data->crdate',
            'type' => 'html',
            'filter' => '',
            
        ),
        array(
            'header'=>'Работодатель',
            'name' => 'id_empl',
            'value' => 'ShowName($data->id_empl, 3)',
            'type' => 'html',
            'filter' => '',
        ),
        array(
            'header'=>'Соискатель',
            'name' => 'id_promo',
            'value' => 'ShowName($data->id_promo, 2)',
            'type' => 'html',
            'filter' => '',
        ),
         array(
            'header'=>'Отзыв',
            'name' => 'message',
            'value' => '$data->message',
            'type' => 'html',
            'filter' => '',
        ),
         array(
            'header'=>'Выставлен',
            'name' => 'iseorp',
            'value' => 'ShowIseorp($data->iseorp)',
            'type' => 'html',
            'filter' => '',
        ),
        array(
            'header'=>'Позитивный',
            'name' => 'isneg',
            'value' => 'ShowStatus($data->id, $data->isneg, 0)',
            'type' => 'html',
            'filter' => '',
        ),
        array(
            'header'=>'Отображается',
            'name' => 'isactive',
            'value' => 'ShowStatus($data->id, $data->isactive,1)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
        ),
    )));

echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));

function ShowStatus($id, $ismoder, $type)
{
    if($type == 1){
        $status = ['не отображается','отображается'];
        $st_ico = ["label-warning", "label-success"];
    } else {
        $status = ['позитивный','негативный'];
        $st_ico = [ "label-success", "label-warning"];

    }
   
    $html = 
    '<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $status[$ismoder] . '">
    <span class="label ' . $st_ico[$ismoder] . '">'.$status[$ismoder].'</span>
    <span class="caret"></span>
    </button>';
    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
    for ($i = 0; $i < 2; $i++) {
        $html .= '<li ><a href = "#" onclick = "doStatusModer(' . $id . ', ' . $i . ')" ><span class="label ' . $st_ico[$i] . '"><i class="icon-star icon-white"></i></span> ' . $status[$i] . '</a></li >';
    }
    $html .= '</ul></div>';
    return $html;
}



function ShowName($id,$type)
{
        if($type == 2){
            $sql = "SELECT r.firstname, r.lastname, r.id_user FROM resume r WHERE r.id = {$id}";
            $res =  Yii::app()->db->createCommand($sql)->queryRow();
            $id_user = $res['id_user'];
            return "<a href='https://prommu.com/admin/site/PromoEdit/$id_user'>".$res['firstname'].$res['lastname']."</a>";
        }
        else {
             $sql = "SELECT e.name, e.id_user FROM employer e WHERE e.id = {$id}";
             $res =  Yii::app()->db->createCommand($sql)->queryRow();
             $id_user = $res['id_user'];
             return "<a href='https://prommu.com/admin/site/EmplEdit/$id_user'>".$res['name']."</a>";
        }
    
}

function ShowIseorp($id) {
    if($id == 0) {
        return "Работодателем";
    } else return "Соискателем";
   
}


function ShowEdit($id) {
    return  '<button type="button" class="btn btn-default"><a href="/admin/site/PromoEdit/' . $id . '">Редактировать</a></button> ';
}
echo CHtml::endForm();
?>

<script type="text/javascript">


    function doStatusModer(id, st) {
        $("#curr_status").val(st);
        $("#form").attr("action", "/admin/site/CommentModer/" + id);
        $("#btn_submit").click();
    }

  
</script>