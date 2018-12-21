<?php
// print_r($data);
$_POST['FeedbackTreatment'] ='';
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/form-checker.js', CClientScript::POS_HEAD);
// print_r($data);
echo '<div class="col-md-12">';
echo '<div class="col-md-6">
<h3>Чат обратной связи</h3>';


echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
//echo CHtml::hiddenField('field_lang', $lang, array('type'=>"hidden"));
//echo CHtml::hiddenField('pagetype', $pagetype, array('type'=>"hidden"));

echo '<h4 style="font-size: 40px;font-weight: 100;">EMAIL</h4>';

echo '<div class="control-group">
      <label class="control-label">Номер тикета</label>
        <div class="controls input-append">';
echo CHtml::textField('Feedback[id]', $data[0]['id'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Дата поступления</label>
        <div class="controls input-append">';
echo $data[0]['crdate'];
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Тема</label>
        <div class="controls input-append">';
echo CHtml::textField('Feedback[theme]', $data[0]['theme'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Email</label>
        <div class="controls input-append">';
echo CHtml::textField('Feedback[email]', $data[0]['email'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Текст</label>
	    <div class="controls input-append">';
echo CHtml::textArea('Feedback[text]', $data[0]['text'], array('rows' => 6, 'cols' => 50,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">История решения вопроса</label>
      <div class="controls input-append" style="word-break:break-word">';
echo "Вопрос обратной связи: ".$data[0]['text']."<br>";
echo "Ответ админа: ".$data[0]['chat'];
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Ответ</label>
	    <div class="controls input-append">';
echo CHtml::textArea(
        'Feedback[chat]',
        $data[0]['chat'], 
        array('rows'=>6,'cols'=>50,'class'=>'form-control','id'=>'admin-answer')
      );
echo '<div id="admin-answer-panel"></div>';
echo '  <span class="add-on"></span><p></p>';
echo '</div></div>';

echo '<div class="span11">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Отправить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo '<a href="/admin/site/mail" class="btn btn-warning" id="btn_cancel">Отмена</a>';
//echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div></div></div>';




//$this->endWidget();
echo CHtml::endForm();
require 'mail-templates.php'; // подключение шаблонов
?>
</div>
