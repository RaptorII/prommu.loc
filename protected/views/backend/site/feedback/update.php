<?php
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/ajaxfileupload.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/form-checker.js', CClientScript::POS_HEAD);

echo '<div class="col-md-12">';
echo '<div class="col-md-6 col-xs-12"><h3>Чат обратной связи #' . $data['feedback']['id'] . '</h3>';

echo  '<label>ID запроса:</label> ' . $data['feedback']['id'] .
  '<input type="hidden" name="Update[id]" value="' . $data['feedback']['id'] . '"><br>';
echo '<label>Дата и время создания обращения:</label> ' . Share::getPrettyDate($data['feedback']['crdate']) . '<br>';
echo '<label>Кто обращается:</label> ' . (Share::isApplicant($data['feedback']['type']) ? 'Соискатель ' : 'Работодатель ') .
  AdminView::getUserType($data['feedback']['type']) . '<br>';
echo '<label>ID Пользователя:</label> ' . $data['user']['id'] . '<br>';
echo '<label>ФИО:</label> '
  . CHtml::link(!empty($data['user']['fio']) ? $data['user']['fio'] : $data['feedback']['name'] , $data['user']['profile_admin'], ['target'=>'_blank'])
  . '<br>';
echo '<label>Направление запроса:</label> ' . $data['direct'] . '<br>';
echo '<label>Тема:</label> ' . $data['feedback']['theme'] . '<br>';
echo '<label>Email:</label> ' . $data['feedback']['email'] . '<br>';
echo '<label>Текст:</label><div style="border:1px solid #e3e3e3;background-color:#fff;border-radius:3px;margin-bottom:15px;padding:10px">' . $data['feedback']['text'] . '</div>';


echo CHtml::form($id, 'post', array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal'));
echo '<div class="box box-primary direct-chat direct-chat-primary">';
echo '<div class="direct-chat-messages" style="margin-top:0">';
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

echo '<div class="control-group"><label class="control-label">Статус: </label><select id="select_status" class="form-control">';
foreach (Feedback::getAdminStatus() as $key => $v)
{
  echo '<option value="' . $key . '" '
    . ($data['feedback']['status']==$key ? 'selected' : '')
    . (in_array($key,[2,5]) ? ' disabled' : '') . '>' . $v . '</option>';
}
echo '</select></div><br><br>';
?>
<div class="pull-right">
  <?=CHtml::submitButton('Отправить', ["class" => "btn btn-success", "id" => "btn_submit"])?>
  <a href="/admin/feedback" class="btn btn-warning" id="btn_cancel">Отмена</a>
</div>
<div class="clearfix"></div>
<?
echo '</div>';
echo '<input type="hidden" name="usertype" value="'.$data['user']['type'].'">';
echo '<input type="hidden" name="Update[idusp]" value="'.$data['user']['id'].'">';
echo '<input type="hidden" name="Update[email]" value="'.$data['feedback']['email'].'">';




echo CHtml::endForm();
require 'mail-templates.php'; // подключение шаблонов
?>
</div>
<style>
#select_status{ max-width:300px; }
#select_status option:disabled{
  color: #cccccc;
  background-color: #efefef;
}
</style>
