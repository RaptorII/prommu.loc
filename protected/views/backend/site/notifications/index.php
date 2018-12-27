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
							$model = new Mailing;
							$this->widget(
								'zii.widgets.grid.CGridView', 
								array(
									'id' => 'mailing-event',
									'dataProvider' => $model->search(),
									'itemsCssClass' => 'table table-bordered table-hover custom-table',
									//'htmlOptions'=>array('class'=>'table'),
									//'filter' => $model,
									'enablePagination' => true,
									'columns' => array(
											array(
												'header'=>'ID',
												'name' => 'id',
												'value' => '$data->id',
												'type' => 'raw'
											),
											array(
												'header'=>'Тип',
												'name' => 'type',
												'value' => 'Mailing::$TYPES[$data->type]',
												'type' => 'raw'
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
												'type' => 'raw'
											)
										)
									)
							);
						?>
					</div>
					<div role="tabpanel" class="tab-pane fade" id="tab_letter">
						<h4>Рассылки</h4>
						<div class="pull-right">
							<a href="?letter_id=0" class="btn btn-success">Создать рассылку</a>
						</div>
						<div class="clearfix"></div>
						<?
							$model = new MailingLetter;
							$this->widget(
								'zii.widgets.grid.CGridView', 
								array(
									'id' => 'mailing-letter',
									'dataProvider' => $model->search(),
									'itemsCssClass' => 'table table-bordered table-hover custom-table',
									//'htmlOptions'=>array('class'=>'table'),
									//'filter' => $model,
									'enablePagination' => true,
									'columns' => array(
											array(
												'header'=>'ID',
												'name' => 'id',
												'value' => '$data->id',
												'type' => 'raw'
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
												'value' => 'MailingLetter::getDate($data->mdate)',
												'type' => 'raw',
												'htmlOptions'=>array('style'=>'width:20%'),
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
			'#mailing-event .custom-table tbody td',
			function(){
				var id = $(this).siblings('td').eq(0).text();
				$(location).attr('href','?event_id='+id);
		});
	$(document).on(
			'click',
			'#mailing-letter .custom-table tbody td',
			function(){
				var id = $(this).siblings('td').eq(0).text();
				$(location).attr('href','?letter_id='+id);
		});
	});
</script>