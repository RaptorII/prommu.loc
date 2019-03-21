<?
	$url = Yii::app()->request->requestUri;
	$archive = MainConfig::$PAGE_APPLICANT_VACS_LIST_ARCHIVE;
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'vacancies/app-list.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'vacancies/app-list.js', CClientScript::POS_END);
?>
<div class="row applicant_projects">
	<div class="col-xs-12">
		<div class="center">
			<h1><?=$this->pageTitle?></h1>
			<div class="app-projects__tabs">
				<? if(strpos($url, $archive)===false): ?>
					<span class="app-projects__tabs-item actual enable">
						<span>Действующие</span>
					</span>
					<a href="<?=MainConfig::$PAGE_APPLICANT_VACS_LIST_ARCHIVE?>" class="app-projects__tabs-item archive">
						<span>Архив</span>
					</a>
				<? else: ?>
					<a href="<?=MainConfig::$PAGE_APPLICANT_VACS_LIST?>" class="app-projects__tabs-item actual">
						<span>Действующие</span>
					</a>
					<span class="app-projects__tabs-item archive enable">
						<span>Архив</span>
					</span>
				<? endif; ?>
			</div>
		</div>
		<? if(count($viData['items'])): ?>
			<div class="app-projects__list">
				<? foreach ($viData['items'] as $k => $v): ?>
					<? $employer = $viData['users'][$v['employer']]; ?>
					<div class="app-projects__list-item">
						<div class="app-projects__item-logo">
							<img src="<?=$employer['src']?>" alt="<?=$employer['name']?>">
						</div>
						<div class="app-projects__item-info">
							<div class="app-projects__item-company"><?=$employer['name']?></div>
							<div class="app-projects__item-title"><?=$v['title']?></div>
							<div class="app-projects__item-link">
								<a href="<?=MainConfig::$PAGE_APPLICANT_VACS_LIST . DS . $v['id']?>" class="prmu-btn prmu-btn_small">
									<span>Просмотр</span>
								</a>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="app-projects__item-right">
							<div class="app-projects__right-bl center">Дата публикации : <b><?=$v['pubdate']?></b></div>
							<div class="app-projects__right-bl center">Статус : <b><?=$v['condition']?></b></div>
							<? if($v['access_to_chat']): ?>
								<div class="app-projects__right-bl center">
									<a 
										href="<?=MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $v['id']?>" 
										class="app-projects__item-btn">Общий чат</a>
								</div>
								<div class="app-projects__right-bl center">
									<a 
										href="<?=MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $v['id'] . DS . $v['employer']?>" 
										class="app-projects__item-btn">Личный чат</a>
								</div>
							<? endif; ?>
							<? if($v['second_response']): ?>
								<div class="app-projects__right-bl center">
									<span 
										class="app-projects__item-btn second_response" 
										data-id="<?=$v['id']?>"
										data-sresponse="<?=Share::$UserProfile->exInfo->id_resume?>"
										>Отозваться повторно</span>
								</div>
							<? endif; ?>
						</div>
					</div>
				<? endforeach; ?>
			</div>
			<div class="app-projects__pages">
				<? $this->widget('CLinkPager', array(
						'pages' => $viData['pages'],
						'htmlOptions' => ['class'=>'paging-wrapp'],
						'firstPageLabel' => '1',
						'prevPageLabel' => 'Назад',
						'nextPageLabel' => 'Вперед',
						'header' => ''
				)); ?>		
			</div>
		<? else: ?>
			<div class="center">
				<? if(strpos($url, $archive)===false): ?>
					<h2>В данный момент Вы не участвуете в проектах</h2>
				<? else: ?>
					<h2>В данный момент завершенные проекты отсутствуют</h2>
				<? endif; ?>
				<a href="<?=MainConfig::$PAGE_SEARCH_VAC?>" class="prmu-btn prmu-btn_normal">
					<span>НАЙТИ РАБОТУ</span>
				</a>
			</div>
		<? endif; ?>
	</div>
</div>