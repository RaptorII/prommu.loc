<?php
$_POST['FeedbackTreatment'] ='';
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/form-checker.js', CClientScript::POS_HEAD);

echo '<div class="col-md-12">';
echo '<div class="col-md-6"><h3>Чат обратной связи #' . $data['id'] . '</h3>';

echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));


echo '<label>ID запроса:</label> ' .
      $data['id'] . '<input type="hidden" name="Feedback[id]" value="' . $data['id'] . '"><br>';
echo '<label>Дата и время создания обращения:</label> ' . Share::getPrettyDate($data['crdate']) . '<br>';
echo '<label>Кто обращается:</label> Гость ' . AdminView::getUserType(0) . '<br>';
echo '<label>ФИО:</label> ' . $data['name'] . '<br>';
echo '<label>Направление запроса:</label> ' . Feedback::getAdminDirects($data['direct']) . '<br>';
echo '<label>Тема:</label> ' . $data['theme'] . '<br>';
echo '<label>Email:</label> ' . $data['email'] . '<br>';
echo '<label>Текст:</label><div style="border:1px solid #e3e3e3;background-color:#fff;border-radius:3px;margin-bottom:15px;padding:10px">' . $data['text'] . '</div>';

echo '<div class="control-group">
      <label class="control-label">История решения вопроса</label>
      <div class="controls input-append" style="word-break:break-word">';
echo "Вопрос обратной связи: ".$data['text']."<br>";
echo "Ответ админа: ".$data['chat'];
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Ответ</label>
	    <div class="controls input-append">';
echo CHtml::textArea(
        'Feedback[chat]',
        $data['chat'],
        array('rows'=>6,'cols'=>50,'class'=>'form-control','id'=>'admin-answer')
      );
echo '<div id="admin-answer-panel"></div>';
echo '  <span class="add-on"></span><p></p>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Статус: </label>' .
  CHtml::dropDownList('Feedback[status]', $data['status'], Feedback::getAdminStatus(), ['class'=>'form-control','style'=>'max-width:300px']) .
  '</div><br><br>';
?>
<div class="pull-right">
  <?=CHtml::submitButton('Отправить', ["class" => "btn btn-success", "id" => "btn_submit"])?>
  <a href="/admin/feedback" class="btn btn-warning" id="btn_cancel">Отмена</a>
</div>
<div class="clearfix"></div>
<?
echo '</div>';
echo CHtml::endForm();
require 'mail-templates.php'; // подключение шаблонов
?>
</div>
