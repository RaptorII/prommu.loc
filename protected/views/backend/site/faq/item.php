<?php $isNewFaq = !isset($id);



?>

<h3><?php echo $this->getPageTitle(); ?></h3>
<?php
	echo '<div class="content">';
	Yii::app()->getClientScript()->registerCoreScript('jquery');

	echo CHtml::form('','post',array('class'=>'form-horizontal'));
		echo '<div class="control-group">
			<label class="control-label">Вопрос</label>
				<div class="controls input-append">';
					echo CHtml::textField(
						'question', 
						$data['question'], 
						array('class'=>'form-control', 'style'=>'max-width:700px;')
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Ответ</label>
				<div class="controls input-append">';
					echo CHtml::textArea(
						'answer', 
						$data['answer'],
						array('class'=>'form-control', 'style'=>'max-width:700px;')
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Тема</label>
				<div class="controls input-append">';
					echo CHtml::textField(
						'theme', 
						$data['theme'], 
						array('class'=>'form-control', 'style'=>'max-width:700px;')
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Сортировка</label>
				<div class="controls input-append">';
					echo CHtml::textField(
						'sort', 
						$data['sort'], 
						array('class'=>'form-control', 'style'=>'max-width:100px;')
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Тип</label>
				<div class="controls input-append">';
					echo CHtml::radioButtonList(
						'type',
						($isNewFaq ? '1' : $data['type']),
						array('1'=>'Соискатель', '2'=>'Работодатель'),
						array()
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">';
		echo '<div style="float:right;  display:inline;">';
		echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
		echo '&nbsp;&nbsp;';
		echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
		echo '</div></div>';

	echo CHtml::endForm();
?>
	<script type="text/javascript">
		'use strict'
		jQuery(function($){
			$('#btn_cancel').click(function(){
				$(location).attr('href','/admin/site/faq');
			});
			$('#sort').on('input',function(){
				var val = $(this).val().replace(/\D+/g,'');
				$(this).val(val);
			});
		});
	</script>
<?php echo "</div>"; ?>