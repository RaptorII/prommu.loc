<?php


<style type="text/css">

    .label-important {
        background: #dd4b39;
    }
    input {
    border: #ecf0f5;
    width: 94px;
}
</style>


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

function ShowServ($type,$consult, $control, $rezident, $nrezident, $advertising){
  if($type == "outstaffing"){
    return "$consult, $rezident, $nrezident";
  }
  else return "$control, $consult, $advertising";
}

function ShowType($type)
{ 
    switch ($type) {
        case 'vacancy':
            $name = 'Премиум-заявка';
            break;
        case 'sms':
            $name = 'СМС-приглашение';
            break;
        case 'push':
            $name = 'PUSH-приглашение';
            break;
        case 'email':
            $name = 'EMAIL-приглашение';
            break;
        case 'repost':
            $name = 'Публикация в соцсети';
            break;
        case 'api':
            $name = 'Заявка на API';
            break;
    }

  return $name;
}


function ShowStatus($vac)
{ 
  if($vac == 0){
    return '<span class="label label-important">ожидает оплаты</span>';
  }
  else return '<span class="label label-success">оплачено</span>';

}

function ShowEmpl($empl)
{
   return "<a href='/admin/site/EmplEdit/$empl'> $empl</a>";

}

function ShowVac($vac)
{
   return "<a href='/admin/site/VacancyEdit/$vac'>$vac</a>";

}

function ShowName($id)
{
   
            $id_user = $id;
            $user = Yii::app()->db->createCommand()
                ->select("e.name, e.firstname, e.lastname, e.id")
                ->from('employer e')
                ->join('user usr', 'usr.id_user=e.id_user')
                ->where('e.id_user=:id_user', array(':id_user' => $id_user))
                ->queryAll();
            $fio = $user[0]['name']." ".$user[0]['firstname']." ".$user[0]['lastname'];
            $ids = $user[0]['id'];
            
            return "<a href='/admin/site/EmplEdit/$id'> $fio</a>";
}

function ShowEmail($id)
{
     $user = Yii::app()->db->createCommand()
            ->select("usr.email")
            ->from('user usr')
            ->where('usr.id_user=:id_user', array(':id_user' => $id))
            ->queryAll();
    return $email = $user[0]['email'];
}

function ShowMess($chat, $type){
    if($type == 3){
        $sql = "SELECT ca.id_use iduse
                FROM chat ca 
                WHERE ca.id_theme = {$chat}";
            /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql)->queryScalar();

        $empl = $res;

        $sql = "SELECT COUNT(*) cou
                FROM chat ca
                WHERE id_theme = {$chat} AND ca.id_use = {$empl} AND ca.is_resp = 1 AND ca.is_read = 0";
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryScalar();
        return $res;

    } elseif ($type == 2) {
        $sql = "SELECT ca.id_use iduse
                FROM chat ca 
                WHERE ca.id_theme = {$chat}";
            /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql)->queryScalar();
           
        $promo = $res;
   
        $sql = "SELECT COUNT(*) cou
                FROM chat ca
                WHERE id_theme = {$chat} AND ca.id_usp = {$promo} AND ca.is_resp = 0 AND ca.is_read = 0";
        $res = Yii::app()->db->createCommand($sql);
        $res = $res->queryScalar();
        return $res;
  }
  else return 0;

}

function ShowEdit($id,$number) {
    if(empty($id)){
        return '<a style ="background: #00c0ef;" href="/admin/site/mail/' . $number . '" rel="tooltip" data-placement="top" title="Ответить"><span class="label label-inverse"><i class="icon-edit icon-white"></i></span></a>&nbsp;';
    }
    else 
        return  '<a style ="background: #00c0ef;" href="/admin/site/update/' . $id . '" rel="tooltip" data-placement="top" title="Ответить"><span class="label label-inverse"><i class="icon-edit icon-white"></i></span></a>&nbsp;';
}

function ShowCondition($cnd, $service_id)
{

    $conditions = ["просмотрен","новый"];
    $icon = ["label-warning", "label-success"];
    $html = '<div class="dropdown">
  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $conditions[$cnd] . '">
    <span class="label ' . $icon[$cnd] . '">' . $conditions[$cnd] . '</span>
    <span class="caret"></span>
  </button>';

    $html .= '<ul class="dropdown-menu" style="position: absolute;top: 100%;left: -73px;" aria-labelledby="dropdownMenu1">';
    for ($i = 0; $i < count($conditions); $i++) {
        $html .= '<li><a href = "void(0);" onclick = "setViewed(' . $service_id . ', ' . $i . ');return false;" ><span class="label ' . $icon[$i] . '"><i class="icon-off icon-white"></i></span> ' . $conditions[$i] . '</a></li >';
    }
    $html .= '</ul></div>';
    return $html;
}
echo CHtml::submitButton('Обновить',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));
echo CHtml::endForm();
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



