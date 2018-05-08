<?php
   Yii::app()->getClientScript()->registerCoreScript('jquery');
   $share = new Share;
   $lang = $share->getLang();

   $menu = new Menu;
   $result = $menu->getMenuForm($lang, $id);
   $list_modules = CHtml::listData($menu->getModelList(),
                'name', 'title');
?>

<h3><i>Настройка элемента меню</i></h3>
<div class="span11">
<?php
echo CHtml::label('Язык: '.$lang, 'lang');
echo CHtml::form('','POST',array("id"=>"form", "class"=>"form-horizontal"));

echo CHtml::hiddenField('field_op', 'EDIT', array('type'=>"hidden"));
echo CHtml::hiddenField('field_lang', $lang, array('type'=>"hidden"));
echo CHtml::hiddenField('field_menu_type', $menu_type, array('type'=>"hidden"));
echo CHtml::hiddenField('field_parent', $menu_parent, array('type'=>"hidden"));

echo '<div class="control-group">
      <label class="control-label">Скрыть</label>
	    <div class="controls input-append">';
echo	CHtml::CheckBox('hidden',$result->hidden, array ('value'=>'1'));
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Название</label>
	    <div class="controls input-append">';
echo	 CHtml::textField('name', $result->MenuTreeNames->name);
echo '  <span class="add-on"><i class="icon-comment"></i></span>
	    </div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Ссылка</label>
	    <div class="controls input-append">';
echo CHtml::textField('link', $result->link);
echo '  <span class="add-on"><i class="icon-tag"></i></span>
	    </div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Модуль сайта</label>
	    <div class="controls input-append">';
echo CHtml::dropDownList('module', $result->module,
              $list_modules,
              array('empty' => '(Выберите модуль)'));
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Показывать только авторизированным</label>
	    <div class="controls input-append">';
echo CHtml::CheckBox('show_auth',$result->show_auth, array ('value'=>'1'));
echo '</div>
	    </div>';

echo CHtml::hiddenField('field_id', $result->id, array('type'=>"hidden"));
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Обработать',array("id"=>"btn_submit","class"=>"btn btn-success"));
echo '&nbsp;&nbsp;';
echo CHtml::tag("input", array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div';

echo CHtml::endForm();
?>

<script type="text/javascript">
$(document).ready(function(){
	$('#btn_cancel').click(function(){
        $("#form").attr("action","/admin/site/menu");
        $("#field_op").val("CANCEL");
        $("#btn_submit").click();
    });
});
</script>
</div>
</div><!-- form -->