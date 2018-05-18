<h3>Редактирование элемента '<?=$id?>'</h3>
<?php
	echo '<div class="content">';
	Yii::app()->getClientScript()->registerCoreScript('jquery');

	echo CHtml::form('','post',array('class'=>'form-horizontal'));
		echo '<div class="control-group">Дата создания: ' . $data['crdate'] . '</div>';
		echo '<div class="control-group">Дата модерации: ' . $data['mdate'] . '</div>';
		$link = getProfile($data['author']['type'], $data['author']['id']);
		echo '<div class="control-group">Автор: <a href="' . $link . '" target="_blank">' . $data['author']['name'] . '</a></div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Проверено</label>
				<div class="controls input-append">';
					echo CHtml::CheckBox('ismoder', $data['ismoder'], array('value'=>'1'));
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Название</label>
				<div class="controls input-append">';
					echo CHtml::textField(
						'name', 
						$data['name'], 
						array('class'=>'form-control', 'style'=>'max-width:700px;')
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Описание</label>
				<div class="controls input-append">';
					echo CHtml::textArea(
						'text', 
						$data['text'],
						array('class'=>'form-control', 'style'=>'max-width:700px;')
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Тип</label>
				<div class="controls input-append">';
					$arTypes = array();
					foreach ($data['types'] as $val => $item) {
						$arTypes[$val] = $item['idea'];
					}
					echo CHtml::radioButtonList(
						'type',
						$data['type'],
						$arTypes,
						array()
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		//
		echo '<div class="control-group">
			<label class="control-label">Статус</label>
				<div class="controls input-append">';
					$arStatuses = array();
					foreach ($data['statuses'] as $val => $item) {
						$arStatuses[$val] = $item['idea'];
					}
					echo CHtml::radioButtonList(
						'status',
						$data['status'],
						$arStatuses,
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
		echo CHtml::hiddenField('event' , 'idea', array());
	echo CHtml::endForm();
	echo '<br/><br/><br/><h4>Комментарии</h4>';
	if(sizeof($data['arr_comments'])){
		echo '<table class="comment-table"><thead><th>Автор<th>Комментарий<th>Дата комментария<tbody>';
		foreach ($data['arr_comments'] as $item){
			$author = $data['users'][$item['id_user']];
			echo '<tr><td><a href="' 
				. getProfile($author['type'],$author['id']) 
				. '" target="_blank">' . $author['name'] . '</a>' 
				. '<td>' . $item['comment']
				. '<td>' . $item['date_comment'];
		}
		echo "</table>";
	}
	//
	//
	echo CHtml::form('','post',array('class'=>'form-horizontal'));
		//
		echo '<div class="control-group">
			<label class="control-label">Оставить комментарий</label>
				<div class="controls input-append">';
					echo CHtml::textArea(
						'comment', 
						'',
						array('class'=>'form-control', 'style'=>'max-width:700px;')
					);
					echo '  <span class="add-on"><i class="icon-tag"></i></span>';
				echo '</div>
		</div>';
		echo '<br/><div class="control-group">';
		echo '<div style="float:right;  display:inline;">';
		echo CHtml::submitButton('Отправить',array("class"=>"btn btn-success", "id"=>"btn_comment"));
		echo '</div></div>';
		echo CHtml::hiddenField('event' , 'comment', array());
		echo CHtml::hiddenField('id' , $data['id'], array());
	echo CHtml::endForm();
echo "</div><br/><br/>"; ?>
<?php
	function getProfile($type, $id){
		if($type==2) $link = '/admin/site/PromoEdit/' . $id;
		elseif($type==3) $link = '/admin/site/EmplEdit/' . $id;
		else $link = 'javascript:void(0)';

		return $link;
	}

?>
<script type="text/javascript">
	'use strict'
	jQuery(function($){
		$('#btn_cancel').click(function(){
			$(location).attr('href','/admin/site/ideas');
		});
	});
</script>
<style type="text/css">
	.comment-table{
		width: 100%;
		background: #ffffff;
	}
	.comment-table th,
	.comment-table td{
		padding: 5px;
		border: 1px solid #f4f4f4;
	}
	.comment-table th:nth-child(1),
	.comment-table td:nth-child(1){ width: 20% }
	.comment-table th:nth-child(2),
	.comment-table td:nth-child(2){ width: 80% }
	.comment-table th:nth-child(3),
	.comment-table td:nth-child(4){ width: 10% }
</style>