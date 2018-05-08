<?php
  Yii::app()->getClientScript()->registerCoreScript('jquery');
echo '<div class="span11">
<h3><i>Редактирование учетных данных пользователя</i></h3>';

  $form=$this->beginWidget('CActiveForm', array(
	'id'=>'form',
	'htmlOptions'=>array("class"=>"form-horizontal"),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,		
  ),
 ));
 
echo '<div class="control-group">
      <label class="control-label">Блокировать</label>
	    <div class="controls input-append">';
echo $form->CheckBox($model,'isblocked');
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Логин</label>
	    <div class="controls input-append">';
echo $form->textField($model,'login');
echo '  <span class="add-on"><i class="icon-user"></i></span>';
echo $form->error($model,'login');
echo '</div>
	    </div>';
 
echo '<div class="control-group">
      <label class="control-label">Е-майл</label>
	    <div class="controls input-append">';
echo $form->textField($model,'email', array('class'=>'admform'));
echo '  <span class="add-on"><i class="icon-envelope"></i></span>';
echo $form->error($model,'email');
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Статус</label>
	    <div class="controls input-append">';
echo CHtml::dropDownList('User[status]', $model->status,
             array('0' => 'Отключен', '1' => 'Промоутер', '2' => 'Работодатель', '3' => 'Рекламное агенство'));
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Индекс</label>
	    <div class="controls input-append">';
echo $form->textField($model,'booble_index');
echo '  <span class="add-on"><i class="icon-signal"></i></span>';
echo $form->error($model,'booble_index');
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Последний вход</label>
	    <div class="controls input-append">
	    <span class="input-xlarge uneditable-input span3">';
echo  $model->access_time;
echo '</span><span class="add-on"><i class="icon-calendar"></i></span>
      </div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">IP адрес</label>
	    <div class="controls input-append">
	    <span class="input-xlarge uneditable-input span3">';
echo  $model->ip;
echo '</span></span><span class="add-on"><i class="icon-globe"></i></span>
      </div>
	    </div>';

echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Сохранить',array("class"=>"btn", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn"));
echo '</div';
//echo CHtml::endForm();
$this->endWidget();
?>
<script type="text/javascript">
$(document).ready(function(){
	$('#btn_cancel').click(function(){
        document.location = '/admin/site/users/view';
    });
});
</script>
</div>
</div>