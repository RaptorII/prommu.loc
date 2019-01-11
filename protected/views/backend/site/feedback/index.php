<div class="container">
  <div class="row">
    <div class="span11">
      <img style="padding-top: : -24px; padding-left: 44%;" src="/admin/logo-sm.png"><h3>Администрирование заявок</h3>
    </div>
  </div>
  <div class="row">
    <div class="span11">
<style type="text/css">
    .label-important {
        background: #dd4b39;
    }
    input {
    border: #ecf0f5;
    width: 94px;
}
.label-double {
    background-color: #3c8dbc;
}
.label-otdel {
    background-color: #9947c6;
}
.label-pending {
    background-color: #999;
}
.label-spam {
    background-color: #ff7373;
}
</style>
<?php 
echo CHtml::form('/admin/site/UserUpdate?id=0','POST',array("id"=>"form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
$this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'dvgrid',
        'dataProvider'=>$model->search(),
         'itemsCssClass' => 'table table-bordered table-hover dataTable',
        'htmlOptions' => array('class' => 'table table-hover', 'name' => 'my-grid'),
        
        'columns'=>array(
          array(
            'header' => 'Номер',
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'raw',
            'filter' => '',
          ),
          array(
            'header' => 'Тип',
            'name' => 'type',
            'value' => 'ShowType($data->type)',
            'type' => 'raw',
            'filter' => '',
          ),
          array(
            'header' => 'Имя/Фамилия',
            'name' => 'email',
            'value' => 'ShowName($data->pid, $data->name)',
            'type' => 'raw',   
          ),
          array(
            'header' => 'Тема письма',
            'name' => 'theme',
            'value' => '$data->theme',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
          ),
          array(
            'header' => 'Дата создания',
            'name' => 'crdate',
            'value' => 'getFormatedDate($data->crdate)',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'width: 120px; text-align: center;'),
            'filter' => '',
          ),
          array(
            'header' => 'Проблема',
            'name' => 'name',
            'value' => 'ShowStatus($data->id,$data->status)',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
            'filter' => '',
          ),
          array(
            'name' => 'Ответ',
            'value' => 'ShowEdit($data->chat, $data->id, $data->type)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting')
          ),
          array(
            'name' => 'Удалить',
            'value' => 'ShowDelete($data->id)',
            'type' => 'raw',
            'filter' => '',
            'htmlOptions' => array('style' => 'width: 50px; text-align: center;', 'class' => 'sorting', 'background'=> '#ef0018')
          ),
        ),
      ));
echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit", "style"=>"visibility:hidden"));
function ShowType($type)
{
  $user;
  switch ($type) {
    case 0: $user='Гость'; break;
    case 2: $user='Cоискатель'; break;
    case 3: $user='Работодатель'; break;
  }
  return $user;
}
function ShowName($id_user, $name)
{
  if(!$id_user)
    return $name;

  $sql = Yii::app()->db->createCommand()
            ->select("u.status, 
            CONCAT(r.firstname,' ',r.lastname) app_name,
            CONCAT(e.firstname,' ',e.lastname) emp_name")
            ->from('user u')
            ->leftjoin('resume r','r.id_user=u.id_user')
            ->leftjoin('employer e','e.id_user=u.id_user')
            ->where('u.id_user=:id',[':id'=>$id_user])
            ->queryRow();

  $result = 'Удален';
  $sql['status']==2 && $result = $sql['app_name'];
  $sql['status']==3 && $result = $sql['emp_name'];

  return trim($result);
}
function ShowStatus($id, $ismoder)
{
$status = ['обработка','дубль','передан в отдел','ожидание ответа','спам','решено'];

    $st_ico = ["label-warning","label-double", 'label-otdel', 'label-pending', 'label-spam', "label-success" ];
    $html = 
    '<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $status[$ismoder] . '">
    <span class="label ' . $st_ico[$ismoder] . '"><i class="icon-star icon-white">'.$status[$ismoder].'</i></span>
    <span class="caret"></span>
    </button>';
    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
    for ($i = 0; $i < 6; $i++) {
        $html .= '<li ><a href = "#" onclick = "doStatusModer(' . $id . ', ' . $i . ')" ><span class="label ' . $st_ico[$i] . '"><i class="icon-star icon-white"></i></span> ' . $status[$i] . '</a></li >';
    }
    $html .= '</ul></div>';
    return $html;
}

function ShowDelete($id) {
    return  '<button type="button" onclick = "doDelete(' . $id . ')" class="btn btn-default">Удалить</button> ';

}

function ShowEdit($id,$number, $type) {
  return '<button type="button" class="btn btn-default"><a href="/admin/site/'
          . (!$type ? 'mail/' . $number : 'update/' . $id)
          . '" rel="tooltip" data-placement="top" title="Ответить">Ответить</a></button> ';
}
function getFormatedDate($date) {
  $curY = date('Y');
  $unix = strtotime($date);
  $dateY = date('Y',$unix);
  $arMonths = array(
                1=>'янв',2=>'фев',3=>'мар',
                4=>'апр',5=>'мая',6=>'июн',
                7=>'июл',8=>'авг',9=>'сен',
                10=>'окт',11=>'ноя',12=>'дек'
              );

  return date('d',$unix) . $arMonths[date('n',$unix)] . ' ' 
          . ($curY!=$dateY ? $dateY : '') . date(' G:i',$unix);
}
?>
    </div>
  </div>
</div>
<script type="text/javascript">

    function doStatusModer(id, st) {
        $("#curr_status").val(st);
        $("#form").attr("action", "/admin/site/FeedbackModer/" + id);
        $("#btn_submit").click();
    }

     function doDelete(id) {
        $("#form").attr("action", "/admin/site/FeedbackDelete/" + id);
        $("#btn_submit").click();
    }


</script>
