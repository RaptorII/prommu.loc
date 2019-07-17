<?
Yii::app()->getClientScript()
  ->registerCssFile(Yii::app()->request->baseUrl . '/css/template.css');
?>
<h3>Управление страницами статей</h3>
<?php
echo CHtml::form('/admin/pagesform', 'POST', ["id" => "form"]);

$model = new Pages;
$criteria = new CDbCriteria();
$criteria->with = ['pages_contents'];
$criteria->join = 'right join pages_content pc ON t.id = pc.page_id';
$criteria->addCondition("`t`.`group_id` = 99");
$criteria->params = [':lang' => 'ru'];
$dataProvider = new CActiveDataProvider(
  'Pages',
  ['criteria' => $criteria, 'pagination' => ['pageSize' => 100]]
);

echo '<input type="hidden" name="pagetype" value="articles">';
echo '<p class="pull-right">';
  echo CHtml::submitButton('Создать',["class"=>"btn btn-success","id"=>"btn_submit"]);
echo '</p>';
echo '<div class="clearfix"></div>';
echo '<div class="span12">';

$this->widget('zii.widgets.grid.CGridView', array(
  'id' => 'my-grid',
  'dataProvider' => $dataProvider,
  'itemsCssClass' => 'table table-bordered table-hover dataTable',
  'enablePagination' => true,
  'htmlOptions' => array('class' => 'table'),
  'columns' => array(
    array(
      'name' => '#',
      'value' => '$data->id',
      'type' => 'html',
      'htmlOptions' => array('style' => 'width: 50px; text-align: center;'),
    ),
    array(
      'name' => 'Ссылка (url)',
      'value' => '$data->link',
      'type' => 'html',
      'htmlOptions' => array('style' => 'width: 400px;'),
    ),
    array(
      'name' => 'Заголовок',
      'value' => '" [".$data->pages_contents[0]->lang."] ".CHtml::link(Share::CheckName($data->pages_contents[0]->name), Yii::app()->createUrl("site/PageUpdate",array("id"=>$data->id, "lang"=>$data->pages_contents[0]->lang, "pagetype"=>"articles")))',
      'type' => 'html',
      'htmlOptions' => array('class' => 'm_element'),
    ),
    array(
      'class' => 'CButtonColumn',
      'deleteConfirmation' => "js:'Страница ID = '+$(this).parent().parent().children(':first-child').text()+' будет удалена! Продолжить?'",
      'template' => '{delete}',
      'buttons' => array
      (
        'delete' => array
        (
          'url' => 'Yii::app()->createUrl("site/PageDelete",  array("id"=>$data->id))',
          'options' => array('title' => 'Удалить'),
        ),
      ),
    ),
  )));
echo '</div>';
echo CHtml::endForm();
?>
<script type="text/javascript">
function onchangeLang(sel)
{
	var value = sel.options[sel.selectedIndex].value;
	$("#field_lang").val(value);
 	$("#form").attr("action","/admin/pages");
  $("#btn_submit").click();
}
</script>