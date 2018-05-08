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
                        'value' => 'ShowName($data->email, $data->type, $data->name)',
                        'type' => 'raw',
                        
                    ),
					array(
						'header' => 'Тема письма',
						'name' => 'theme',
						'value' => '$data->theme',
						'type' => 'raw',
						'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
//			'filter' => CHtml::activeTextField($model, 'theme',array('style' => 'width:50px')),
					),

					array(
						'header' => 'Дата создания',
						'name' => 'crdate',
						'value' => '$data->crdate',
						'type' => 'raw',
						'htmlOptions' => array('style' => 'width: 120px; text-align: center;'),
						'filter' => '',
					),
					array(
						'header' => 'Проблема',
						'name' => 'name',
						'value' => 'ShowStatus($data->id,$data->is_smotr)',
						'type' => 'raw',
						'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
						'filter' => '',
//			'filter' => CHtml::activeTextField($model, 'name',array('style' => 'width:50px')),
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
	if($type == 0){
		return 'Гость';
	}
	elseif($type == 2){
		return 'Cоискатель';
	}
	else return 'Работодатель';

}
function ShowName($email, $type, $names)
{
    if($type == 2){
              $user = Yii::app()->db->createCommand()
            ->select("e.firstname, e.lastname")
            ->from('resume e')
            ->join('user usr', 'usr.id_user=e.id_user')
            ->where('usr.email=:email', array(':email' => $email))
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
            ->where('usr.email=:email', array(':email' => $email))
            ->queryAll();
            $fio = $user[0]['name']." ".$user[0]['firstname']." ".$user[0]['lastname'];
            if(empty($user)){
              return "Удален";
            }
            else {
               return $fio;
            }
         
            }
              elseif($type == 0){
            	return $names;
            }

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

function ShowDelete($id) {
    return  '<button type="button" onclick = "doDelete(' . $id . ')" class="btn btn-default">Удалить</button> ';

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


function ShowEdit($id,$number, $type) {
	if($type == 0){
		return '
			<button type="button" class="btn btn-default"><a href="/admin/site/mail/' . $number . '" rel="tooltip" data-placement="top" title="Ответить">Ответить</a></button> ';
	}
	else
   		return  '	<button type="button" class="btn btn-default"><a href="/admin/site/update/' . $id . '" rel="tooltip" data-placement="top" title="Ответить">Ответить</a></button> ';
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
