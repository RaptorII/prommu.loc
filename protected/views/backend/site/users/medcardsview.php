<img style="padding-top: : -24px; padding-left: 44%;" src="/admin/logo-sm.png"><h3>Администрирование заявок получения мед. книги</h3>
<style type="text/css">
    .label-important {
        background: #dd4b39;
    }
    input {
    border: #ecf0f5;
    width: 60px;
}
</style>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="_blank" onclick="export_send()">Экспорт в Excell</a>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="" onclick="export_delete()">Покончить с ними</a>
<?php
echo CHtml::form('/admin/site/UserUpdate?id=0', 'POST', array("id" => "form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
$this->widget('zii.widgets.grid.CGridView', array(
   'id' => 'dvgrid',
    'dataProvider'=>$model->search(),
      'itemsCssClass' => 'table table-bordered table-hover dataTable',
    'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style'=>    'padding: 10px;  overflow: scroll;'),
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
            'name' => 'ID',
            'value' => '$data->id',
            'type' => 'html',
            'htmlOptions'=>array('style'=>'width: 50px; text-align: center;'),
            'filter'=>'',
        ),
        array(
            'name' => 'Фамилия',
            'value' => 'ShowName($data->id,$data->fff)',
            'type' => 'html',
            'filter'=>'',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        array(
            'name' => 'Телефон',
            'value' => '$data->tel',
            'type' => 'html',
            'filter'=>'',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        array(
            'name' => 'Эл. почта',
            'value' => '$data->email',
            'type' => 'html',
            'filter'=>'',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        array(
            'name' => 'Выбранный адрес',
            'value' => '$data->regaddr',
            'type' => 'html',
            'filter'=>'',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        array(
            'name' => 'Способ оплаты',
            'value' => '$data->pay',
            'type' => 'html',
            'filter'=>'',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        array(
            'name' => 'Дата создания',
            'value' => '$data->crdate',
            'type' => 'html',
            'filter'=>'',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        
         array(
            'name' => 'Статус',
            'value' => 'ShowStatusCard($data->status,$data->id)',
            'type' => 'raw',
            'filter'=>'',
        ),
        array(
            'name' => 'Редактор',
            'value' => 'ShowEdit($data->id)',
            'type' => 'raw',
            'filter'=>'',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        )

    )));

echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));


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


function ShowEdit($id) {
    return  '<button type="button" class="btn btn-default"><a href="/admin/site/MedCardEdit/' . $id . '">Редактировать</a></button> ';
}
function ShowName($id, $name){
    if($name == "")
        return  '<a href="/admin/site/MedCardEdit/' . $id . '">Редактировать</a> ';
    else
        return  '<a href="/admin/site/MedCardEdit/' . $id . '">'.$name.'</a> ';
}
echo CHtml::endForm();
?>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="_blank" onclick="export_send()">Экспорт в Excell</a>

<script type="text/javascript">
    function onchangeLang(sel)
    {
        var value = sel.options[sel.selectedIndex].value;
        $("#field_lang").val(value);
        //alert(value);
        $("#form").attr("action","/admin/site/pages");
        $("#btn_submit").click();
    }

    function doStatusCard(id, st) {
        $("#curr_status").val(st);
        $("#form").attr("action", "/admin/site/MedCardStatus/" + id);
        $("#btn_submit").click();
    }

    function export_send() {
      document.forms['form'].method = 'POST';
      document.forms['form'].action = "/admin/site/ExportMedCards";
      document.forms['form'].submit();
    }

     function export_delete() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/DeleteMedCard";
        document.forms['form'].submit();
    }


</script>