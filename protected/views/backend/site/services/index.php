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



<?php 
echo CHtml::form('/admin/site/UserUpdate?id=0', 'POST', array("id" => "form"));
echo '<input type="hidden" id="curr_status" name="curr_status">';
$this->widget('zii.widgets.grid.CGridView', array(
                'id'=>'dvgrid',
                'dataProvider'=>$model->search(),
                 'itemsCssClass' => 'table table-bordered table-hover dataTable',
                  'htmlOptions'=>array('class'=>'table table-hover', 'name'=>'my-grid', 'style'=>    'padding: 10px;'),
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
                        'name' => 'id_user',
                        'value' => 'ShowName($data->id_user)',
                        'type' => 'raw',
                        
                    ),
                  // array(
                  //       'header' => 'Вакансия',
                  //       'name' => 'vacancy',
                  //       'value' => 'ShowVac($data->vacancy)',
                  //       'type' => 'raw',
                        
                  //   ),
                  array(
                        'header' => 'Тип',
                        'name' => 'type',
                        'value' => 'ShowType($data->type)',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Вакансия',
                        'name' => 'name',
                        'value' => 'ShowVac($data->name)',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Cумма',
                        'name' => 'sum',
                        'value' => '$data->sum',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Дата начала:',
                        'name' => 'bdate',
                        'value' => '$data->bdate',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Дата окончания:',
                        'name' => 'edate',
                        'value' => '$data->edate',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Номер транзакции',
                        'name' => 'key',
                        'value' => '$data->key',
                        'type' => 'raw',
                        
                    ),
                  array(
                        'header' => 'Статус',
                        'name' => 'status',
                        'value' => 'ShowStatus($data->status)',
                        'type' => 'raw',
                        
                    ),
                  // array(
                  //       'header' => 'Тип',
                  //       'name' => 'type',
                  //       'value' => 'ShowType($data->type)',
                  //       'type' => 'raw',
                        
                  //   ),
                  // array(
                  //       'header' => 'Источник',
                  //       'name' => 'transition',
                  //       'value' => '$data->transition',
                  //       'type' => 'raw',
                        
                  //   ),
                  // array(
                  //       'header' => 'Канал',
                  //       'name' => 'canal',
                  //       'value' => '$data->canal',
                  //       'type' => 'raw',
                       
                  //   ),
                  // array(
                  //       'header' => 'Кампания',
                  //       'name' => 'campaign',
                  //       'value' => 'ShowKey($data->campaign)',
                  //       'type' => 'raw',
                        
                  //   ),
                  //  array(
                  //       'header' => 'Контент',
                  //       'name' => 'content',
                  //       'value' => 'ShowKey($data->content)',
                  //       'type' => 'raw',
                        
                  //   ), 
                  //  array(
                  //       'header' => 'Ключевое слово',
                  //       'name' => 'keywords',
                  //       'value' => '$data->keywords',
                  //       'type' => 'raw',
                        
                  //   ),
                  //  array(
                  //       'header' => 'Email',
                  //       'name' => 'id',
                  //       'value' => 'ShowEmail($data->id_us)',
                  //       'type' => 'raw',
                        
                  //   ),
                  // array(
                  //       'header' => 'Дата',
                  //       'name' => 'id',
                  //       'value' => '$data->date',
                  //       'type' => 'raw',
                        
                  //   ),   
                ),
            ));

function ShowServ($type,$consult, $control, $rezident, $nrezident, $advertising){
  if($type == "outstaffing"){
    return "$consult, $rezident, $nrezident";
  }
  else return "$control, $consult, $advertising";
 

}

function ShowType($vac)
{ 
  if($vac == 'vacancy'){
    return 'Премиум вакансия';
  }
  else return 'Смс оповещение';

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
   return "<a href='https://prommu.com/admin/site/EmplEdit/$empl'> $empl</a>";

}

function ShowVac($vac)
{
   return "<a href='https://prommu.com/admin/site/VacancyEdit/$vac'>$vac</a>";

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
            
         
             return "<a href='https://prommu.com/admin/site/EmplEdit/$id'> $fio</a>";

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


