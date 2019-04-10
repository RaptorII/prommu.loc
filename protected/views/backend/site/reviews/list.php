<div class="row">
	<div class="hidden-xs col-sm-1 col-md-2"></div>
	<div class="col-xs-12 col-sm-10 col-md-8">
		<h3><?=$this->pageTitle?></h3>
		<?
			$model = new CommentsAboutUs;
			$this->widget(
				'zii.widgets.grid.CGridView', 
				array(
					'dataProvider' => $model->search(),
					'itemsCssClass' => 'table table-bordered table-hover custom-table',
					'htmlOptions'=>array('class'=>'system-module','data-type'=>'review'),
					//'filter' => $model,
					'enablePagination' => true,
					'columns' => array(
							array(
								'header'=>'ID',
								'name' => 'id',
								'value' => '$data->id',
								'type' => 'raw',
								'htmlOptions'=>['style'=>'width:5%']
							),
							array(
								'header'=>'Пользователь',
								'name' => 'id_user',
								'value' => 'CommentsAboutUs::getUser($data->id_user)',
								'type' => 'raw'
							),
							array(
								'header'=>'Статус',
								'name' => 'is_negative',
								'value' => 'CommentsAboutUs::getStatus($data->is_negative)',
								'type' => 'raw',
								'htmlOptions'=>['style'=>'width:20%']
							),
							array(
								'header'=>'Дата создания',
								'name' => 'cdate',
								'value' => 'CommentsAboutUs::getDate($data->cdate)',
								'type' => 'raw',
								'htmlOptions'=>['style'=>'width:20%']
							),
							array(
								'header'=>'Новый',
								'name' => 'is_viewed',
								'value' => '(!$data->is_viewed ? "Да" : "")',
								'type' => 'raw',
								'htmlOptions'=>['style'=>'width:10%']
							)
						)
					)
			);
		?>
  </div>
  <div class="hidden-xs col-sm-1 col-md-2"></div>
</div>
<script type="text/javascript">
	'use strict'
	jQuery(function($){
		$(document).on(
			'click',
			'.system-module tbody td',
			function(e){ 
				var parent = $(this).closest('.system-module')[0],
						type = parent.dataset.type,
						id = $(this).siblings('td').eq(0).text(),
						url = '/admin/reviews/' + id;

				if(!$(this).hasClass('empty'))
					$(location).attr('href',url);
			});
	});
</script>