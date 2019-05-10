<?
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCssFile($bUrl . '/css/system/list.css');
  $gcs->registerScriptFile($bUrl . '/js/system/list.js',CClientScript::POS_HEAD);
?>
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
							<div class="d-indent"></div>
						</div>
						<div class="clearfix"></div>
						<div id="search_form">
							<input type="text" name="q" class="form-control">
							<div class="btn btn-success glyphicon glyphicon-search"></div>
							<div class="clear glyphicon glyphicon-remove"></div>
							<div class="d-indent"></div>
						</div>
						<?
							$model = new CodeReview;
							$this->widget(
								'zii.widgets.grid.CGridView', 
								array(
									'id'=>'review_table',
									'dataProvider' => $model->search(),
									'itemsCssClass' => 'table table-bordered table-hover custom-table',
									'htmlOptions'=>array('class'=>'system-module','data-type'=>'review'),
									'filter' => $model,
									'enablePagination' => true,
									'columns' => array(
											array(
												'header'=>'ID',
												'filter'=>'',
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
												'header'=>'Автор',
												'name' => 'author',
												'value' => '$data->user ? $data->user->surname : " - "',
												'type' => 'raw',
												'htmlOptions'=>['style'=>'width:15%']
											),
											array(
												'header'=>'Дата изменения',
												'filter'=>'',
												'name' => 'mdate',
												'value' => 'CodeReview::getDate($data->mdate)',
												'type' => 'raw',
												'htmlOptions'=>['style'=>'width:10%']
											),
											array(
												'header'=>'Дата создания',
												'filter'=>'',
												'name' => 'cdate',
												'value' => 'CodeReview::getDate($data->cdate)',
												'type' => 'raw',
												'htmlOptions'=>['style'=>'width:10%']
											),
											array(
												'header'=>'Архив',
												'filter'=>CHtml::dropDownList(
                                            'CodeReview[in_archive]', 
                                            isset($_GET['CodeReview']['in_archive']) 
                                            	? $_GET['CodeReview']['in_archive'] : 0, 
                                            ['0'=>'Активные', '1'=>'Архив']
                                        ),
												'name' => 'in_archive',
												'value' => '$data->in_archive ? "Архив" : "Активная"',
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