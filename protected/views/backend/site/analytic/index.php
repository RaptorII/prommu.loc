<!-- <div class="container">
    <div class="row">
        <div class="span11">
            <img style="padding-top: : -24px; padding-left: 44%;" src="/admin/logo-sm.png"><h3>PrommuLeads 0.5 Beta Аналитика</h3>
            <p><<<<<Проводятся технические работы, экспорт временно отключен >>>>>></p>
        </div>
    </div>
    <div class="row">
        <div class="span11"> -->
<style type="text/css">

    .label-important {
        background: #dd4b39;
    }
    input {
    border: #ecf0f5;
    width: 94px;
}
</style>
<? $title = $_SERVER['REDIRECT_URL'];
if($title == '/admin/site/analyticspb') $domen = 1;
?>


<form action="/api.export_auto" method="GET">
Выгрузка от
<input type="date" name="date">
по
<input type="date" name="bdate">
<input type="hidden" name="domen" value="<?=$_GET['subdomen']; ?>">
<button style='padding: 10px;background: #00c0ef;color: #f4f4f4'; type="submit">Автоматическая выгрузка (ну почти...)</button>
</form>
<? 

 $data = Yii::app()->db->createCommand()
            ->select("*")
            ->from('analytic')
            ->where('active=:active AND date=:date AND type = 2', array(':active' => 1, ':date'=> date("Y-m-d")))
            ->order("id desc")
            ->queryAll();
$countPromo = count($data);

 $datas = Yii::app()->db->createCommand()
            ->select("*")
            ->from('analytic')
            ->where('active=:active AND date=:date AND type = 3', array(':active' => 1, ':date'=> date("Y-m-d")))
            ->order("id desc")
            ->queryAll();
$countEmpl = count($datas);


?>
<a style="padding: 10px;background: #00c0ef;color: #f4f4f4;" href="#" target="" onclick="export_delete()">Покончить с ними</a>
<div style="display: -webkit-inline-box;"><p>Новых соискателей за сегодня: <?=$countPromo?> </p> <p style="padding-left: 10px">Новых работодателей за сегодня: <?=$countEmpl?></p> </div>
<?php 
echo CHtml::form('/admin/site/UserUpdate?id=0', 'POST', array("id" => "form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
$this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'dvgrid',
                'dataProvider'=>$model->search(),
                 'itemsCssClass' => 'table table-bordered table-hover dataTable',
                  'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style'=>    'padding: 10px;  overflow: scroll;'),
                'filter' => $model,
                'columns'=>array(
                  array(
            'class'=>'CCheckBoxColumn',
            'selectableRows' => 2,
            'checkBoxHtmlOptions' => array('class' => 'checkclass'),
            'value' => '$data->id',
        ),    
                  array(
                        'header' => 'Пользователь',
                        'name' => 'id_us',
                        'value' => '$data->id_us',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Имя/Фамилия',
                        'name' => 'name',
                        'value' => 'ShowName($data->id_us, $data->type)',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Тип',
                        'name' => 'type',
                        'value' => 'ShowType($data->type)',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Источник',
                        'name' => 'transition',
                        'value' => '$data->transition',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Канал',
                        'name' => 'canal',
                        'value' => '$data->canal',
                        'type' => 'raw',
                       
                    ),
                  array(
                        'header' => 'Кампания',
                        'name' => 'campaign',
                        'value' => 'ShowKey($data->campaign)',
                        'type' => 'raw',
                        
                    ),
                   array(
                        'header' => 'Контент',
                        'name' => 'content',
                        'value' => 'ShowKey($data->content)',
                        'type' => 'raw',
                        
                    ), 
                   array(
                        'header' => 'Ключевое слово',
                        'name' => 'keywords',
                        'value' => '$data->keywords',
                        'type' => 'raw',
                        
                    ),
                   array(
                        'header' => 'Email',
                        'name' => 'id',
                        'value' => 'ShowEmail($data->id_us)',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Дата',
                        'name' => 'id',
                        'value' => '$data->date',
                        'type' => 'raw',
                        
                    ),   
                ),
            ));

function ShowKey($mess){

  $mess = mb_substr($mess, 0, 40, 'UTF-8') . '...';
  return $mess;

}

function ShowType($type)
{
    if($type == 0){
        return 'Гость';
    }
    elseif($type == 2){
        return 'Cоискатель';
    }
    else return 'Работодатель';

}

function ShowName($id, $type)
{
    if($type == 2){
                $types = "Соискатель";
                $id_user = $id;
              $user = Yii::app()->db->createCommand()
            ->select("e.firstname, e.lastname")
            ->from('resume e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id_user))
            ->queryAll();
            $firstname = $user[0]['firstname'];
            $lastname = $user[0]['lastname'];
            if(empty($user)){
              return "Удален";
            }
            else {
               return $fio = "$firstname ".$lastname;
            }

            
            }
            elseif($type == 3){
            
            $id_user = $id;
                $user = Yii::app()->db->createCommand()
            ->select("e.name, e.firstname, e.lastname")
            ->from('employer e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('e.id_user=:id_user', array(':id_user' => $id_user))
            ->queryAll();
            $fio = $user[0]['name']." ".$user[0]['firstname']." ".$user[0]['lastname'];
            if(empty($user)){
              return "Удален";
            }
            else {
               return $fio;
            }
         
            }

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

function ShowStatus($id, $ismoder)
{
$status = ['не решена','решена'];
    $st_ico = ["label-warning", "label-success"];
    $html = 
    '<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"  title="статус: ' . $status[$ismoder] . '">
    <span class="label ' . $st_ico[$ismoder] . '"><i class="icon-star icon-white"></i></span>
    <span class="caret"></span>
    </button>';
    $html .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenu2">';
    for ($i = 0; $i < 2; $i++) {
        $html .= '<li ><a href = "#" onclick = "doStatusModer(' . $id . ', ' . $i . ')" ><span class="label ' . $st_ico[$i] . '"><i class="icon-star icon-white"></i></span> ' . $status[$i] . '</a></li >';
    }
    $html .= '</ul></div>';
    return $html;
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
  }
  elseif($type == 2){
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
echo CHtml::endForm();
?>
        </div>
        <div style="display: -webkit-inline-box;"><p>* Добавлено отображение аналитики ВК и FB</p></div>

    </div>

</div>

<script type="text/javascript">
  function export_send() {
      document.forms['form'].method = 'POST';
      document.forms['form'].action = "/admin/site/ExportAnalytic";
      document.forms['form'].submit();
    }

  function export_delete() {
        document.forms['form'].method = 'POST';
        document.forms['form'].action = "/admin/site/DeleteAnalytic";
        document.forms['form'].submit();
    }

</script>
