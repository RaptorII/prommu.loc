<div class="row">
	<div class="col-xs-12 notifications">
		<h3><?=$this->pageTitle?></h3>
		<div class="row">
			<div class="col-xs-12 col-sm-3 col-md-2">
				<ul class="nav user__menu" role="tablist" id="tablist">
					<li class="active">
						<a href="#tab_event" aria-controls="tab_event" role="tab" data-toggle="tab">События</a>
					</li>
					<li>
						<a href="#tab_letter" aria-controls="tab_letter" role="tab" data-toggle="tab">Рассылки</a>
					</li>
					<li>
						<a href="#tab_template" aria-controls="tab_template" role="tab" data-toggle="tab">Шаблоны</a>
					</li>
					<li>
						<a href="#tab_system" aria-controls="tab_system" role="tab" data-toggle="tab">Отправка</a>
					</li>
			  </ul>
			</div>
			<?
			// content
			?>
			<div class="col-xs-12 col-sm-9 col-md-10">
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade active in" id="tab_event">
						<h4>События</h4>
						<?
							$model = new MailingEvent;
							$this->widget(
								'zii.widgets.grid.CGridView', 
								array(
									'dataProvider' => $model->search(),
									'itemsCssClass' => 'table table-bordered table-hover custom-table',
									'htmlOptions'=>array('class'=>'notif-module','data-type'=>'event'),
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
												'header'=>'Тип',
												'name' => 'type',
												'value' => 'MailingEvent::$TYPES[$data->type]',
												'type' => 'raw',
												'htmlOptions'=>['style'=>'width:10%']
											),
											array(
												'header'=>'Заголовок',
												'name' => 'title',
												'value' => '$data->title',
												'type' => 'raw'
											),
											array(
												'header'=>'Дата изменения',
												'name' => 'mdate',
												'value' => 'Mailing::getDate($data->mdate)',
												'type' => 'raw',
												'htmlOptions'=>['style'=>'width:20%']
											)
										)
									)
							);
						?>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tab_letter">
						<h4>Рассылки</h4>
						<div class="pull-right">
							<a href="<?=$this->createUrl('',['type'=>'letter','id'=>0])?>" class="btn btn-success">Создать рассылку</a>
						</div>
						<div class="clearfix"></div>
						<?
							$model = new MailingLetter;
							$this->widget(
								'zii.widgets.grid.CGridView', 
								array(
									'dataProvider' => $model->search(),
									'itemsCssClass' => 'table table-bordered table-hover custom-table',
									'htmlOptions'=>array('class'=>'notif-module','data-type'=>'letter'),
									//'filter' => $model,
									'enablePagination' => true,
									'columns' => array(
											array(
												'header'=>'ID',
												'name' => 'id',
												'value' => '$data->id',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:5%')
											),
											array(
												'header'=>'Заголовок',
												'name' => 'title',
												'value' => '$data->title',
												'type' => 'raw',
											),
											array(
												'header'=>'Дата изменения',
												'name' => 'mdate',
												'value' => 'Mailing::getDate($data->mdate)',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:20%'),
											)
										)
									)
							);
						?>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tab_template">
						<h4>Шаблоны</h4>
						<div class="pull-right">
							<a href="<?=$this->createUrl('',['type'=>'template','id'=>0])?>" class="btn btn-success">Создать шаблон</a>
						</div>
						<div class="clearfix"></div>
						<?
							$model = new MailingTemplate;
							$this->widget(
								'zii.widgets.grid.CGridView', 
								array(
									'dataProvider' => $model->search(),
									'itemsCssClass' => 'table table-bordered table-hover custom-table',
									'htmlOptions'=>array('class'=>'notif-module','data-type'=>'template'),
									//'filter' => $model,
									'enablePagination' => true,
									'columns' => array(
											array(
												'header'=>'ID',
												'name' => 'id',
												'value' => '$data->id',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:5%')
											),
											array(
												'header'=>'Название',
												'name' => 'name',
												'value' => '$data->name',
												'type' => 'raw'
											),
											array(
												'header'=>'Активность',
												'name' => 'isactive',
												'value' => '$data->isactive ? "Да" : "Нет"',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:10%')
											),
											array(
												'header'=>'Дата изменения',
												'name' => 'mdate',
												'value' => 'Mailing::getDate($data->mdate)',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:20%')
											)
										)
									)
							);
						?>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tab_system">
						<h4>Отправка</h4>
						<?
							$model = new Mailing;
							$this->widget(
								'zii.widgets.grid.CGridView', 
								array(
									'dataProvider' => $model->search(),
									'itemsCssClass' => 'table table-bordered table-hover custom-table',
									'htmlOptions'=>array('class'=>'notif-module','data-type'=>'system'),
									//'filter' => $model,
									'enablePagination' => true,
									'columns' => array(
											array(
												'header'=>'ID',
												'name' => 'id',
												'value' => '$data->id',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:3%')
											),
											array(
												'header'=>'Получатель',
												'name' => 'receiver',
												'value' => '$data->receiver',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:20%')
											),
											array(
												'header'=>'Заголовок',
												'name' => 'title',
												'value' => '$data->title',
												'type' => 'raw'
											),
											array(
												'header'=>'Срочное',
												'name' => 'is_urgent',
												'value' => 'Mailing::getBool($data->is_urgent)',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:4%')
											),
											array(
												'header'=>'Дата создания',
												'name' => 'cdate',
												'value' => 'Mailing::getDate($data->cdate)',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:11%')
											),
											array(
												'header'=>'Статус',
												'name' => 'status',
												'value' => 'Mailing::getStatus($data->status)',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:10%')
											),
											array(
												'header'=>'Дата отправки',
												'name' => 'rdate',
												'value' => '$data->rdate ? Mailing::getDate($data->rdate) : "-"',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:11%')
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
			'.notif-module tbody td',
			function(e){ 
				var parent = $(this).closest('.notif-module')[0],
						type = parent.dataset.type,
						id = $(this).siblings('td').eq(0).text(),
						url = '/admin/notifications/' + id + '?type=' + type;

				if(!$(this).hasClass('empty'))
					$(location).attr('href',url);
			});
	});
</script>