<?php

$sql = "SELECT COUNT(*) cou
                FROM chat ca
                LEFT JOIN employer e ON e.id_user = ca.id_use
                LEFT JOIN resume r ON r.id_user = ca.id_usp
                WHERE ca.id_theme = {$id}";
        /** @var $res CDbCommand */
        $res = Yii::app()->db->createCommand($sql);

$sql = "SELECT e.name, e.email, e.text, e.id, e.theme
                FROM feedback e
                WHERE e.chat = {$id}";
        /** @var $res CDbCommand */
        $result = Yii::app()->db->createCommand($sql);
        $result = $result->queryRow();

        $count=  array_reverse($res->queryRow());

Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
// print_r($data);
echo '<div class="col-md-12">';
echo '<div class="col-md-6">
<h3>Чат обратной связи #'.$result['id'] .'</h3>';

echo '<div class="control-group">
      <label class="control-label">Номер тикета</label>
        <div class="controls input-append">';
echo CHtml::textField('Update[id]', $result['id'], array('class'=>'form-control'));
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
echo CHtml::textField('Update[theme]', $result['theme'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Email</label>
        <div class="controls input-append">';
echo CHtml::textField('Update[email]', $result['email'], array('class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Текст</label>
        <div class="controls input-append">';
echo CHtml::textArea('Update[text]', $result['text'], array('rows' => 6, 'cols' => 50,'class'=>'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';



echo CHtml::form($id,'post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
echo '<div class="box box-primary direct-chat direct-chat-primary">';
echo '<div class="direct-chat-messages">';
for($i = 0; $i<$count['cou']; $i++){
    if($data[$i]['isresp'] == "0") {
        $class = "direct-chat-msg";
        $author = "namefrom";
    }
    else {
        $class = "direct-chat-msg right";
        $author = "nameto";
    }
echo  '<span class="direct-chat-timestamp pull-right">';
echo $data[$i]['crdate'] . "  " . $data[$i]['crtime'];
echo '</span>';
echo '<div class='.$class.'>
      <label class="control-label">';
echo $data[$i][$author];
echo ' </label>';
echo     '<div class="direct-chat-text">';
echo  $data[$i]['message'];
echo '  <span class="add-on"></span>';
echo '</div></div>';
if($data[$i]['message'] == "Проблема решена") {
 $res = Yii::app()->db->createCommand()
            ->update('feedback', array(
                'is_smotr' => 1,
            ), 'chat = :chat', array(':chat' => $id));    
}
}
echo '</div>';

echo '<div class="control-group">
      <label class="control-label">Ответ</label>
        <div class="controls input-append">';
echo CHtml::textArea('Update[message]', $data['message'], array('class'=>'form-control'));
echo '  <span class="add-on"><i class="icon-tag"></i></span>';
echo '</div>
        </div></div>';
echo CHtml::textArea('Update[idusp]', $data[0]['idusp'], array('class'=>'hidd'));
echo '<div class="span11">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Отправить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo '<a href="/admin/site/feedback/index" class="btn btn-warning" id="btn_cancel">Отмена</a>';
//echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div></div></div>';


echo CHtml::endForm();
?>
<style type="text/css">
    .hidd{
        visibility:hidden;
    }
</style>
</div>

