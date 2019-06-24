<?php



echo CHtml::form('/admin/site/UserUpdate?id=0', 'POST', array("id" => "form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
echo '<input type="hidden" id="curr_cnd" name="curr_cnd">';
echo '<input type="hidden" id="type" name="type" value="'.$_GET['type'].'">';
$this->widget('zii.widgets.grid.CGridView',
                array(
                    'id'=>'dvgrid',
                    'dataProvider'=>$model->search(),
                    'itemsCssClass' => 'table table-bordered table-hover dataTable',
                    'htmlOptions'=>array('class' => 'table table-hover',
                                         'name'  => 'my-grid',
                                         'style' => 'padding: 10px; overflow: scroll;'
                ),
                'filter' => $model,
                'columns'=>array(
                    array(
                        'class'=>'CCheckBoxColumn',
                        'selectableRows' => 2,
                        'checkBoxHtmlOptions' => array('class' => 'checkclass'),
                        'value' => '$data->id',
                    ),
                    array(
                        'header' => 'ID',
                        'name' => 'id',
                        'value' => '$data->id',
                        'type' => 'raw',

                    ),
                    array(
                        'header' => 'Цена',
                        'name' => 'price',
                        'value' => '$data->price',
                        'type' => 'raw',
                    ),
                    array(
                        'header' => 'Комментарий',
                        'name' => 'comment',
                        'value' => '$data->comment',
                        'type' => 'raw',

                    ),
                    array(
                        'header' => 'Услуга',
                        'name' => 'service',
                        'value' => '$data->service',
                        'type' => 'raw',
                    ),
                    array(
                        'header' => 'Регион',
                        'name' => 'region',
                        'value' => '$data->region',
                        'type' => 'raw',
                    ),
                   
                    /*array(
                        'header' => 'Text',
                        'name' => 'text',
                        'value' => '$data->text',
                        'type' => 'raw',
                    ),*/
                ),
        ));





function ShowStatus($vac)
{ 
  if($vac == 0){
    return '<span class="label label-important">ожидает оплаты</span>';
  }
  else return '<span class="label label-success">оплачено</span>';

}



?>
<script type="text/javascript">
    function setViewed(id, cnd) {
        $("#curr_cnd").val(cnd);
        $("#form").attr("action", "/admin/site/ServicesSetViewed/" + id);
        $("#btn_submit").click();
    }


</script>
        </div>
        <div style="display: inline-block;"><p>* Добавлено отображение аналитики ВК и FB</p></div>

    </div>

</div>



