<h3>Редактирование страницы визитки</h3>
<div class="row">
    <div class="span12">
        <div class="form-horizontal">

<?php
  Yii::app()->getClientScript()->registerCoreScript('jquery');
  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);

echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));

$this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$model,
'attribute'=>'content',
'language'=>'ru',
'editorTemplate'=>'full',
'width'=>'200px',
));

?>

<!-- Uploader -->
<img id="loading" src="/images/loading.gif" style="display:none;">

		<table cellpadding="0" cellspacing="0" class="tableForm">

		<thead>
			<tr>
				<th>Please select a file and click Upload button</th>
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
					<td><button class="btn btn-success" id="buttonUpload" onclick="return ajaxFileUpload();">Загрузить</button></td>
				</tr>
			</tfoot>

	</table>

	<div id="msg"></div>

<?php
echo '<input type="hidden" name="id" id="id" value="'.$id.'"/>';
echo '<div class="modal-footer">';
echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-primary", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-inverse"));
echo '</div';

//$this->endWidget();
echo CHtml::endForm();
?>
        </div></div></div>
<script type="text/javascript">

$(document).ready(function(){
	$('#btn_cancel').click(function(){
    $("#form").attr("action","/admin/site/pages");
    $("#btn_submit").click();
    });
});


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
				url:'/uploads/doajaxfileupload.php',
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
							//alert(data.msg);
							$('#msg').html($('#msg').html()+data.msg);
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