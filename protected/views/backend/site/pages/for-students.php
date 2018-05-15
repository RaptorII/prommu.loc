<h3>Редактирование страницы 'Работа для студентов'</h3>
<?php
	if(Yii::app()->user->hasFlash('success') ){
		Yii::app()->user->getFlash('success');
		echo '<span class="text-success bg-success">Данные сохранены</span>';
	}

	echo '<div class="content">';
	Yii::app()->getClientScript()->registerCoreScript('jquery');

	echo CHtml::form('','post',array('class'=>'form-horizontal'));
		echo '<div class="control-group">
			<label class="control-label">Title</label>
				<div class="controls input-append">';
					echo CHtml::textField(
						'meta_title', 
						$data['meta_title'], 
						array('class'=>'form-control', 'style'=>'max-width:700px;')
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Description</label>
				<div class="controls input-append">';
					echo CHtml::textArea(
						'meta_description', 
						$data['meta_description'],
						array('class'=>'form-control', 'style'=>'max-width:700px;')
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo CHtml::hiddenField('id', $data['id'], array('type'=>"hidden"));
		echo CHtml::hiddenField('url', $data['url'], array('type'=>"hidden"));
		echo CHtml::hiddenField('meta_keywords', $data['meta_keywords'], array('type'=>"hidden"));
		echo CHtml::hiddenField('seo_h1', $data['seo_h1'], array('type'=>"hidden"));
		//
		echo '<div class="control-group">';
		echo '<div style="float:right;  display:inline;">';
		echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
		echo '&nbsp;&nbsp;';
		echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
		echo '</div></div>';

	echo CHtml::endForm();
?>
<style type="text/css">
	.text-success{ font-size: 18px }
</style>
<script type="text/javascript">
	'use strict'
	jQuery(function($){
		$('#btn_cancel').click(function(){
			$(location).attr('href','/admin/site');
		});
	});
</script>
<?php echo "</div>"; ?>