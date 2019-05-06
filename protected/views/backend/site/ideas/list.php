<h3>Управление идеями и предложениями</h3>
<style type="text/css">
	input {
		border: #ecf0f5;
		width: 94px;
	}
	.dataTable{ width: 100% }
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
				$(location).attr('href','ideaedit/'+id);
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
			'style'=>'padding: 10px; overflow: scroll;'
		),
		'filter' => $model,
		'enablePagination' => true,
		'columns'=>array(
			array(
				'header' => 'ID',
				'name' => 'id',
				'value' => '$data->id',
				'type' => 'html',
			),
			array(
				'header' => 'Название',
				'name' => 'name',
				'value' => '$data->name',
				'type' => 'raw',
			),
			array(
				'header' => 'Тип',
				'name' => 'type',
				'value' => 'getIdeaType($data->type)',
                'type' => 'raw',
			),
			array(
				'header' => 'Статус',
				'name' => 'status',
				'value' => 'getIdeaStatus($data->status)',
				'type' => 'raw',
			),
			array(
				'header' => 'Проверено',
				'name' => 'ismoder',
				'value' => 'getIdeaIsModer($data->ismoder)',
				'type' => 'html',
				'htmlOptions' => array('style' => 'width: 70px; text-align: center;'),
			),
			array(
				'header' => 'Дата модерации',
				'name' => 'mdate',
				'value' => 'getIdeaDate($data->mdate)',
				'type' => 'raw',
			),
			array(
				'header' => 'Дата создания',
				'name' => 'crdate',
				'value' => 'getIdeaDate($data->crdate)',
				'type' => 'raw',
			),
			array(
				'name' => 'Скрытые комментарии',
				'value' => 'getIdeaCmntsCnt($data->id)',
				'type' => 'raw',
				'filter' => ''
			),
            array(
				'name' => 'Непросмотренные комментарии',
				'value' => 'getIdeaIsReadCnt($data->id)',
				'type' => 'raw',
				'filter' => ''
			),
			array(
				'class'=>'CButtonColumn',
				'deleteConfirmation'=>"js:'Запись ID = '+$(this).parent().parent().children(':first-child').text()+' будет удалена! Продолжить?'",
				'template' => '{delete}',
				'buttons'=>array(
					'delete' => array(
						'url'=>'Yii::app()->createUrl("site/IdeaDelete",  array("id"=>$data->id))',
						'options'=>array('title'=>'Удалить'),
					),
				),
			),
		)
	));

	function getIdeaType($t){ 
		$model = new Ideas;
		$arr = $model->getParams();
		return $arr['types'][$t]['idea'];
	};

	function getIdeaStatus($t){ 
		$model = new Ideas;
		$arr = $model->getParams();
		return $arr['statuses'][$t]['idea'];
	};

	function getIdeaDate($d){ 
		return DateTime::createFromFormat('Y-m-d H:i:s', $d)->format('d.m.y H:i');
	};

	function getIdeaCmntsCnt($id){ 
		$sql = "SELECT COUNT(DISTINCT id)
				FROM ideas_attrib 
				WHERE comment IS NOT NULL AND hidden=1 AND id_idea={$id}";
        return Yii::app()->db->createCommand($sql)->queryScalar();
	};

	function getIdeaIsReadCnt($id){
		$sql = "SELECT COUNT(DISTINCT id)
				FROM ideas_attrib 
				WHERE comment IS NOT NULL AND isread=0 AND id_idea={$id}";
        return Yii::app()->db->createCommand($sql)->queryScalar();
	};

	function getIdeaIsModer($t){
		$icon = $t ? 'ok text-success' : 'remove text-danger';
		$title = $t ? 'Проверено' : 'Не проверено';
		return '<span class="glyphicon glyphicon-' . $icon . '" title="' . $title . '"></span>';
	}
?>