<?php
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/form-checker.js', CClientScript::POS_HEAD);

echo '<div class="col-md-12">';
echo '<div class="col-md-6 col-xs-12">
<h3>Чат обратной связи #' . $data['chat']['id'] . '</h3>';

echo '<b>Пользователь: '
    . CHtml::link(!empty($data['user']['name']) ? $data['user']['name'] : $data['chat']['name'] , $data['user']['link'], array('target'=>'_blank'))
    . '</b>';

echo '<div class="control-group">
      <label class="control-label">Номер тикета</label>
        <div class="controls input-append">';
echo CHtml::textField('Update[id]', $data['chat']['id'], array('class' => 'form-control'));
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
echo CHtml::textField('Update[theme]', $data['chat']['theme'], array('class' => 'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Email</label>
        <div class="controls input-append">';
echo CHtml::textField('Update[email]', $data['chat']['email'], array('class' => 'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Текст</label>
        <div class="controls input-append">';
echo CHtml::textArea('Update[text]', $data['chat']['text'], array('rows' => 6, 'cols' => 50, 'class' => 'form-control'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo CHtml::form($id, 'post', array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal'));
echo '<div class="box box-primary direct-chat direct-chat-primary">';
echo '<div class="direct-chat-messages">';
foreach ($data['items'] as $item) {
    if($data['user']['type']==2) {
        if(!($item['isresp']==1 && $item['iduse']==1766)) {
            $class = "direct-chat-msg";
            $author = "namefrom";
        }
        else {
            $class = "direct-chat-msg right";
            $author = "nameto";
        }
    }
    if($data['user']['type']==3) {
        if($item['isresp']==0 && $item['idusp']==2054) {
            $class = "direct-chat-msg";
            $author = "namefrom";
        }
        else {
            $class = "direct-chat-msg right";
            $author = "nameto";
        }
    }

    echo '<span class="direct-chat-timestamp pull-right">';
    echo $item['crdate'] . "  " . $item['crtime'];
    echo '</span>';
    echo '<div class=' . $class . '>
      <label class="control-label">';

    echo $item[$author];
    echo ' </label>';

    if(($item['isresp']==0 && $item['idusp']==2054)
            ||
        ($item['isresp']==1 && $item['iduse']==1766)) 
    {
        echo '<div class="direct-chat-text">';
    }else{
        echo '<div class="direct-chat-text chat-admin">';
    }

    echo $item['message'];

    if(!empty($item['files'])) {
        $arFiles = json_decode($item['files'], true);
        $content = '<b>Прикрепленные файлы</b><br>';
        foreach ($arFiles as $key => $f) {
            $content .= CHtml::link(
                            $f['meta']['name'], 
                            $f['files']['orig'], 
                            array('target'=>'_blank')
                        );
        }
        echo CHtml::tag('div', array(), $content);
    }

    echo '  <span class="add-on"></span>';
    echo '</div></div>';
}
echo '</div>';

echo '<div class="control-group">
      <label class="control-label">Ответ</label>
        <div class="controls input-append">';
echo CHtml::textArea(
    'Update[message]', 
    ($data['message'] ? $data['message'] : '<br>'), 
    array('class' => 'form-control','id'=>'admin-answer')
);
echo '<div id="admin-answer-panel"></div>';
echo '  <span class="add-on"><i class="icon-tag"></i></span>';
echo '</div>
        </div></div>';
echo '<div class="span11">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Отправить', array("class" => "btn btn-success", "id" => "btn_submit"));
echo '&nbsp;&nbsp;';
echo '<a href="/admin/site/feedback/index" class="btn btn-warning" id="btn_cancel">Отмена</a>';
//echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div>';
echo '</div>';
echo '</div>';
echo '<input type="hidden" name="usertype" value="'.$data['user']['type'].'">';
echo '<input type="hidden" name="Update[idusp]" value="'.$data['user']['id'].'">';
echo '<input type="hidden" name="Update[email]" value="'.$data['chat']['email'].'">';
echo CHtml::endForm();
require 'mail-templates.php'; // подключение шаблонов
?>
</div>

