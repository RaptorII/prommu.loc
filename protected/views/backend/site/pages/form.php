<?php
if($pagetype=='news') {
	$this->setPageTitle('Редактирование новости');
    echo '<h3><i>Редактирование новости</i></h3>';
} elseif($pagetype=='articles'){
	$this->setPageTitle('Редактирование статьи');
	echo '<h3><i>Редактирование статьи</i></h3>';
} elseif($pagetype=='about'){
	$this->setPageTitle('Редактирование страницы "О нас"');
	echo '<h3><i>Редактирование страницы "О нас"</i></h3>';
}else {
    echo '<h3><i>Настройка страницы сайта</i></h3>';
}
echo '<div class="content">';

  Yii::app()->getClientScript()->registerCoreScript('jquery');
  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);

  $share = new Share;
  $lang = $share->getLang();

  $pg = new PagesContent;
  $result = $pg->getContent($lang, $id);

  $result2 = new Pages;
  if($id>0){
  	$result2 = Pages::model()->findByPk($id);
  	$model = $result;
  }
echo CHtml::label('Язык: '.$lang, 'lang');

echo CHtml::form('','post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
echo CHtml::hiddenField('field_lang', $lang, array('type'=>"hidden"));
echo CHtml::hiddenField('pagetype', $pagetype, array('type'=>"hidden"));

echo '<div class="control-group">
      <label class="control-label">Скрыть</label>
	    <div class="controls input-append">';
echo CHtml::CheckBox('PagesContent[hidden]',$result->hidden, array (
                                        'value'=>'1', 'style'=>'width:700px;'
                                        ));
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Ссылка</label>
	    <div class="controls input-append">';
echo CHtml::textField('PagesContent[link]', $result2->link, array('class'=>'form-control', 'style'=>'width:700px;'));
echo '  <span class="add-on"><i class="icon-tag"></i></span>';
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Название</label>
	    <div class="controls input-append">';
echo CHtml::textField('PagesContent[name]', $result->name, array('class'=>'form-control', 'style'=>'width:700px;'));
echo '  <span class="add-on"><i class="icon-tag"></i></span>';
echo '</div>
	    </div>';


echo '<div class="control-group">
      <label class="control-label">Анонс</label>
	    <div class="controls input-append">';
echo CHtml::textArea('PagesContent[anons]', $result->anons, array('class'=>'form-control', 'style'=>'width:700px;'));
echo '  <span class="add-on"><i class="icon-tag"></i></span>';
echo '</div>
	    </div>';


echo '<div class="control-group">
      <label class="control-label">Html</label>
	    <div class="controls" style="width:700px">';
$this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$model,
'attribute'=>'html',
'language'=>'ru',
'editorTemplate'=>'full',
'width'=>'50%',
));

echo '</div></div>';

echo CHtml::hiddenField('PagesContent[img]', $result->img, array('class'=>'form-control', 'id'=>'fimg', 'style'=>'width:700px;'));
?>
<!-- Uploader -->
  <div class="control-group">
      <label class="control-label">Изображение</label>
	    <div class="controls input-append">
<img id="loading" src="/images/loading.gif" style="display:none;">

		<table cellpadding="0" cellspacing="0" class="tableForm">

		<thead>
			<tr>
				<th>Выберите изображение</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
				<input id="fileToUpload" type="file" name="fileToUpload" />
			</td></tr>

		</tbody>
			<tfoot>
				<tr>
					<td><button class="btn" id="buttonUpload" onclick="return ajaxFileUpload();">Загрузить</button></td>
				</tr>				
			</tfoot>

	</table>  
	</div>
	<div id="msg" class="alert alert-message span6 offset4"></div>
      <img id="img_page" src="/images/<?php echo $pagetype.'/'.$result->img; ?>" class="span6 offset4"/>
	</div>
<?php
echo '<div class="control-group">
      <label class="control-label">Дата публикации</label>
	    <div class="controls input-append">';
//echo CHtml::date_time_set('PagesContent[name]', $result->name, array('class'=>'admform span5'));
echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('model'=>$result, 'attribute'=>'pubdate','language'=>'ru', 'id'=>'pubdate', 'options'=>array('dateFormat'=>'yy-mm-dd 00:00:00', 'timeFormat'=>'hh:mm:ss', 'minDate'=>'Today'),), true);

echo '  <span class="add-on"><i class="icon-tag"></i></span>';
echo '</div>
	    </div>';


echo '<div class="control-group">
      <label class="control-label">Мета: Название страницы</label>
	    <div class="controls input-append">';
echo CHtml::textField('PagesContent[meta_title]', $result->meta_title, array('class'=>'form-control', 'style'=>'width:700px;'));
echo '  <span class="add-on"><i class="icon-hand-right"></i></span>';
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Мета: Описание</label>
	    <div class="controls input-append">';
echo CHtml::textArea('PagesContent[meta_description]', $result->meta_description, array('rows'=>4, 'style'=>'width:700px;','id'=>'meta_description', 'class'=>'form-control'));
echo '</div>
	    </div>';

echo '<div class="control-group">
      <label class="control-label">Мета: ключевые слова</label>
	    <div class="controls input-append">';
echo CHtml::textField('PagesContent[meta_keywords]', $result->meta_keywords, array('class'=>'form-control', 'style'=>'width:700px;'));
echo '  <span class="add-on"><i class="icon-tasks"></i></span>';
echo '</div>
	    </div>';





echo '<div class="control-group">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div></div>';

//$this->endWidget();
echo CHtml::endForm();
?>

<script type="text/javascript">

$(document).ready(function(){
	$('#btn_cancel').click(function(){
    $("#form").attr("action","/admin/site/pages");
    $("#btn_submit").click();
    });
});


$("#93_textInput").val("text");
	function ajaxFileUpload()
	{
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});

		$.ajaxFileUpload
		(
			{
				url:'/uploads/doajaxpageupload.php?pagetype=<?php echo $pagetype;?>',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							$('#msg').html($('#msg').html()+data.msg);
                            $("#img_page").attr("src", "/images/<?php echo $pagetype;?>/"+data.name);
                            $('#fimg').val(data.name);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)

		return false;

	}

</script>
</div>
</div>