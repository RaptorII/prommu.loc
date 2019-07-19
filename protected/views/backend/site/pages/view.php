<?
Yii::app()->getClientScript()
  ->registerCssFile(Yii::app()->request->baseUrl . '/css/template.css');
?>
<h3>Управление страницами сайта</h3>
<?php
echo CHtml::form('/admin/site/pagesform','POST',array("id"=>"form"));

$model = new Pages;


$criteria = new CDbCriteria();
                $criteria->with=array('pages_contents');
                $criteria->addCondition("`t`.`group_id` = 1");
                $pagination = array('pageSize'=>20,);

$dataProvider = new CActiveDataProvider('Pages', array('criteria' => $criteria, 'pagination' => $pagination, ));
echo '<input type="hidden" name="pagetype" value="pages">';
echo '<div class="span12">';
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'my-grid',
  'dataProvider'=>$dataProvider,
  'itemsCssClass' => 'table table-bordered table-hover dataTable',
   'enablePagination' => true,
  'htmlOptions'=>array('class'=>'table'),
  'columns'=>array(
        array(
            'name' => '#',
            'value' => '$data->id',
            'type' => 'html',
            'htmlOptions'=>array('style'=>'width: 50px; text-align: center;'),
        ),
        array(
            'name' => 'Ссылка (url)',
            'value' => '$data->link',
            'type' => 'html',
            'htmlOptions'=>array('style'=>'width: 150px;'),
        ),
        array(
            'name' => 'Название 1',
            'value' => '" [".$data->pages_contents[0]->lang."] ".CHtml::link(Share::CheckName($data->pages_contents[0]->name), Yii::app()->createUrl("site/PageUpdate",array("id"=>$data->id, "lang"=>$data->pages_contents[0]->lang, "pagetype"=>"pages")))',
            'type' => 'html',
            'htmlOptions' => array('class'=>'m_element'),
        ),
        array(
            'name' => 'Название 2',
            'value' => '" [".$data->pages_contents[1]->lang."] ".CHtml::link(Share::CheckName($data->pages_contents[1]->name), Yii::app()->createUrl("site/PageUpdate",array("id"=>$data->id, "lang"=>$data->pages_contents[1]->lang, "pagetype"=>"pages")))',
            'type' => 'html',
        ),
	    array(
	      'class'=>'CButtonColumn',
	      'deleteConfirmation'=>"js:'Страница ID = '+$(this).parent().parent().children(':first-child').text()+' будет удалена! Продолжить?'",
		  'template' => '{delete}',
	  'buttons'=>array
      (
        'delete' => array
        (
            'url'=>'Yii::app()->createUrl("site/PageDelete",  array("id"=>$data->id))',
            'options'=>array('title'=>'Удалить'),
        ),
      ),
  ),
)));
echo '</div>';
echo '<p style="float:right;margin-right:20px">';
echo CHtml::submitButton('Создать',array("class"=>"btn btn-success","id"=>"btn_submit"));
echo '</p>';
echo CHtml::endForm();

//            'updateButtonUrl' => 'Yii::app()->createUrl("site/PageUpdate",  array("id"=>$data->id))',
//            'deleteButtonUrl' => 'Yii::app()->createUrl("site/PageDelete",  array("id"=>$data->id))',

?>
<script type="text/javascript">
function onchangeLang(sel)
{
	var value = sel.options[sel.selectedIndex].value;
	$("#field_lang").val(value);
	//alert(value);
 	$("#form").attr("action","/admin/site/pages");
  	$("#btn_submit").click();
}
</script>