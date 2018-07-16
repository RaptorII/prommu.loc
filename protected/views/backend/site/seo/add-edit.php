<?php if($item): ?>
    <h3><i>Редактирование URL</i></h3>
<?php else: ?>
	<h3><i>Добавление URL</i></h3>
<?php endif; ?>

<?php echo CHtml::form('/admin/site/seoSave','post',array('enctype'=>'multipart/form-data', 'class'=>'form-vertical')); ?>

	<?php if($item): ?>
	    <?php echo CHtml::hiddenField('data[id]', $item['id'], array('class'=>'admform')); ?>
	<?php endif; ?>

	<div class="table table-bordered table-hover dataTable">
      	<label class="control-label">Id</label>
		<?php echo CHtml::textArea('data[id]', $item['id'], array('class'=>'form-control')); ?>
	</div>

	<div class="table table-bordered table-hover dataTable">
      	<label class="control-label">URL</label>
	   	<div class="controls input-prepend">
			<span class="add-on"><?php echo "https://prommu.com" ?></span>
			<?php echo CHtml::textField('data[url]', $item['url'], array('class'=>'admform')); ?>
		</div>
	</div>

	<div class="table table-bordered table-hover dataTable">
      	<label class="control-label">Title</label>
		<?php echo CHtml::textArea('data[meta_title]', $item['meta_title'], array('class'=>'form-control')); ?>
	</div>

	<div class="table table-bordered table-hover dataTable">
      	<label class="control-label">H1</label>
		<?php echo CHtml::textArea('data[seo_h1]', $item['seo_h1'], array('class'=>'form-control')); ?>
	</div>

	<div class="table table-bordered table-hover dataTable">
      	<label class="control-label">Description</label>
		<?php echo CHtml::textArea('data[meta_description]', $item['meta_description'], array('class'=>'form-control')); ?>
	</div>
		
	<div class="table table-bordered table-hover dataTable">
      	<label class="control-label">Отображение текста</label>
		<?php echo CHtml::textArea(
			'data[meta_keywords]', 
			$item['meta_keywords'], 
			array('class'=>'form-control','style'=>'min-height:400px')
		); ?>
	</div>
	
	<?php 
	$model = new Seo; 
	if($model->exist($item['url'])){
	$id= $item['id']; 
	$model = Seo::model()->findByPk($id); 
    }
	echo '<div class="control-group">
      <label class="control-label">Редактирование текста</label>
	    <div class="controls" style="width:100%;margin-bottom:30px">';
$this->widget('application.extensions.ckeditor.CKEditor', array(
'model'=>$model,
'attribute'=>'meta_keywords',
'language'=>'ru',
'editorTemplate'=>'full',
'width'=>'100%',
));

echo '</div></div>';
?>


	<?php echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-success", "id"=>"btn_submit")); ?>
	&nbsp;&nbsp;
	<?php echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning")); ?>

<?php echo CHtml::endForm(); ?>

<script type="text/javascript">
	document.getElementById('btn_cancel').onclick = function(){
		location.href = '/admin/site/seo';
	}
</script>

<style type="text/css">
	.form-vertical .control-group,
	.form-vertical .controls
	{
		display: table;
		width: 100%;
	}

	.form-vertical .control-group input[type=text]
	{
		display: table-cell;
		width: 905px;
	}

	.form-vertical .control-group .input-prepend input[type=text]
	{
		display: table-cell;
		width: 775px;
	}
	#cke_contents_Seo_meta_keywords{
		min-height: 400px
	}
	input-prepend
</style>