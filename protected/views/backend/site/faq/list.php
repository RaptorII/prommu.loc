<h3>Управление списком FAQ</h3>
<style type="text/css">
	input {
		border: #ecf0f5;
		width: 94px;
	}
	.dataTable tr{
		cursor: pointer;
	}
	.dataTable tr:hover *{
		color: #3c8dbc;	
	}
</style>
<script type="text/javascript">
	'use strict'
	jQuery(function($){
		$(document).on('click','.dataTable tbody td',function(){
			if(!$(this).hasClass('button-column')){
				var id = $(this).siblings('td').eq(0).text();
				$(location).attr('href','faqedit/'+id);
			}
		});
	});
</script>
<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'dvgrid',
		'dataProvider'=>$model->search(),
		'itemsCssClass' => 'table table-bordered table-hover dataTable',
		'htmlOptions'=>array(
			'class'=>'table table-hover', 
			'name'=>'my-grid', 
			'style'=>'padding: 10px;'
		),
		'filter' => $model,
		'enablePagination' => true,
		'columns'=>array(
			array(
				'header' => 'ID',
				'name' => 'id',
				'value' => '$data->id',
				'type' => 'raw',
			),
			array(
				'header' => 'Вопрос',
				'name' => 'question',
				'value' => '$data->question',
				'type' => 'raw',
			),
			array(
				'header' => 'Тип',
				'name' => 'type',
				'value' => '$data->type==2 ? "Работодатель" : "Соискатель"',
				'type' => 'raw',
			),
			array(
				'header' => 'Тема',
				'name' => 'theme',
				'value' => '$data->theme',
				'type' => 'raw',
			),
			array(
				'class'=>'CButtonColumn',
				'deleteConfirmation'=>"js:'Запись ID = '+$(this).parent().parent().children(':first-child').text()+' будет удалена! Продолжить?'",
				'template' => '{delete}',
				'buttons'=>array(
					'delete' => array(
						'url'=>'Yii::app()->createUrl("site/FaqDelete",  array("id"=>$data->id))',
						'options'=>array('title'=>'Удалить'),
					),
				),
			),
		)
	));
?>