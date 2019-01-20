<div class="row">
	<div class="col-xs-12 notifications">
		<h3><?=$this->pageTitle?></h3>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-2">
				<ul class="nav user__menu" role="tablist" id="tablist">
					<li class="active">
						<a href="#tab_review" aria-controls="tab_review" role="tab" data-toggle="tab">Ревью</a>
					</li>
			  </ul>
			</div>
			<?
			// content
			?>
			<div class="col-xs-12 col-sm-9 col-md-10">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="tab_review">
						<h4>Ревью</h4>
						<div class="bs-callout bs-callout-info">&laquo;Рецензия кода — это возможность обмениваться знаниями и принимать осмысленные инженерные решения. Но это невозможно, если автор воспринимает обсуждение как персональную атаку на него самого&raquo;</div>
						<div class="pull-right">
							<a href="<?=$this->createUrl('',['type'=>'review','id'=>0])?>" class="btn btn-success">Создать</a>
						</div>
						<div class="clearfix"></div>
						<?
							$model = new CodeReview;
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
												'header'=>'Название',
												'name' => 'name',
												'value' => '$data->name',
												'type' => 'raw'
											),
											array(
												'header'=>'Дата изменения',
												'name' => 'mdate',
												'value' => 'CodeReview::getDate($data->mdate)',
												'type' => 'raw',
												'htmlOptions'=>['style'=>'width:10%']
											),
											array(
												'header'=>'Дата создания',
												'name' => 'cdate',
												'value' => 'CodeReview::getDate($data->cdate)',
												'type' => 'raw',
												'htmlOptions'=>['style'=>'width:10%']
											)
										)
									)
							);
						?>
					</div>
				</div>
			</div>
		</div>
  </div>
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
						url = '/admin/system/' + id + '?type=' + type;

				if(!$(this).hasClass('empty'))
					$(location).attr('href',url);
			});
	});
</script>