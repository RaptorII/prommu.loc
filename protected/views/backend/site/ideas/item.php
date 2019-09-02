<h3>Редактирование элемента '<?=$id?>'</h3>
<?php
	echo '<div class="content">';
	Yii::app()->getClientScript()->registerCoreScript('jquery');

	echo CHtml::form('','post',array('class'=>'form-horizontal'));
		echo '<div class="control-group">Дата создания: ' . $data['crdate'] . '</div>';
		echo '<div class="control-group">Дата модерации: ' . $data['mdate'] . '</div>';
		$author = $data['users'][$data['id_user']];
		$link = getProfile($author['status'], $author['id']);
		echo '<div class="control-group">Автор: <a href="' . $link . '" target="_blank">' . $author['name'] . '</a></div>';
		echo '<div><span class="glyphicon glyphicon-thumbs-up"></span> ' . $data['posrating'];
		echo '<div><span class="glyphicon glyphicon-thumbs-down"></span> ' . $data['negrating'];
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
		echo '<div class="row"><div class="col-xs-12 col-md-3"><div class="control-group">
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
		</div></div>';
		//
		echo '<div class="col-xs-12 col-md-3"><div class="control-group">
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
		</div></div><div class="hidden-xs hidden-sm col-md-6"></div></div>';
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
	if(sizeof($data['comments'])){
		echo '<table class="comment-table"><thead><th>ID<th>Автор<th>Комментарий'
			. '<th>Дата комментария<th>Видимость<th>Удалить<tbody>';
		foreach ($data['comments'] as $item){
			$author = $data['users'][$item['id_user']];
			echo '<tr data-id="' . $item['id'] . '"><td>' . $item['id'] . '<td><a href="' 
				. getProfile($author['type'],$author['id']) 
				. '" target="_blank">' . $author['name'] . '</a>' 
				. '<td>' . $item['comment']
				. '<td>' . $item['date_comment']
				. '<td class="text-center">' . getIdeaIsModer($item['hidden'],$item['id'])
				. '<td class="text-center"><span class="glyphicon glyphicon-trash remove-comment" data-id="' 
				. $item['id'] . '" title="Удалить"></span>';
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
	function getIdeaIsModer($h, $id){
		$icon = $h ? 'remove text-danger' : 'ok text-success';
		$title = $h ? 'Не отображается' : 'Отображается';
		return '<span class="glyphicon glyphicon-' . $icon . ' hide-comment" title="' . $title . '" data-id="' . $id . '"></span>';
	}
?>
<script type="text/javascript">
	'use strict'
	jQuery(function($){
		$('#btn_cancel').click(function(){
			$(location).attr('href','/admin/site/ideas');
		});
		// удаление коммента
		$('.remove-comment').click(function(){
			var id = this.dataset.id,
				arComments = $('.comment-table tbody tr'),
				arParams = [];

			arParams.push({name:'event',value:'del'});
			arParams.push({name:'id',value:id});

			var query = confirm("Вы действительно хотите удалить комментарий ID = '"+id+"' ?");
			if(query) {
				$.ajax({
					type: 'GET',
					url: '/admin/ajax/changeIdeaComment',
					data: arParams,
					success: function(res){
						if(res=='1'){
							alert('Удалено');
							for (var i=0; i<arComments.length; i++) {
								if(arComments[i].dataset.id===id) {
									$(arComments[i]).remove();
									break;
								}
							}
						}
						else
							alert('Произошла ошибка');
					}
				});
			}
		});
		// изменение коммента
		$('.hide-comment').click(function(){
			var id = this.dataset.id,
				$it = $(this),
				arParams = [];

			arParams.push({name:'event',value:'hidden'});
			arParams.push({name:'id',value:id});

			$.ajax({
				type: 'GET',
				url: '/admin/ajax/changeIdeaComment',
				data: arParams,
				success: function(res){
					if(res=='1'){
						alert('Изменено');
						if($it.hasClass('glyphicon-ok')) {
							$it.removeClass('glyphicon-ok text-success')
								.addClass('glyphicon-remove text-danger')
								.attr('title','Не отображается');
						}
						else{
							$it.removeClass('glyphicon-remove text-danger')
								.addClass('glyphicon-ok text-success')
								.attr('title','Отображается');
						}
					}
					else
						alert('Произошла ошибка');
				}
			});
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
	.comment-table td:nth-child(1){ width: 4% }
	.comment-table th:nth-child(2),
	.comment-table td:nth-child(2){ width: 15% }
	.comment-table th:nth-child(3),
	.comment-table td:nth-child(3){ width: 65% }
	.comment-table th:nth-child(4),
	.comment-table td:nth-child(4){ width: 10% }
	.comment-table th:nth-child(5),
	.comment-table td:nth-child(5){ width: 3% }
	.comment-table th:nth-child(6),
	.comment-table td:nth-child(6){ width: 3% }
	.remove-comment,
	.hide-comment{ cursor:pointer; }
</style>