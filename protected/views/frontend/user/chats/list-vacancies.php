<?
	$type = Share::$UserProfile->type;
	Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'chats/list-section.css');
	Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'chats/list.js', CClientScript::POS_END);
	$s = Yii::app()->getRequest()->getParam('s');
?>
<div class="chat-list vacancies">
	<a href="<?=MainConfig::$PAGE_CHATS_LIST?>" class="chat-list__btn-link"><span><</span> Назад</a>
	<? if(count($viData['items'])): ?>
		<h2>Сообщения по вакансиям 
			<? if($viData['cnt-chat']>0): ?>
				<span class="js-g-hashint chat" title="Открытых чатов"><?=$viData['cnt-chat']?></span>
			<? endif; ?>
			<? if($viData['cnt-mess']>0): ?>
				<span class="js-g-hashint messages" title="Сообщений"><?=$viData['cnt-mess']?></span>
			<? endif; ?>
			<? if($viData['cnt-mess-noread']>0): ?>
				<span class="js-g-hashint noread" title="Не прочитано"><?=$viData['cnt-mess-noread']?></span>
			<? endif; ?>
		</h2>
		<div class="two__tabs">
			<? if($s=='archive'): ?>
				<a class='two__tabs-link' href='<?=$this->createUrl('',['section'=>'vacancies'])?>'>Активные</a>
				<div class='two__tabs-link active'>Архив</div>
			<? else: ?>
				<div class='two__tabs-link active'>Активные</div>
				<a class='two__tabs-link' href='<?=$this->createUrl('',['section'=>'vacancies','s'=>'archive'])?>'>Архив</a>
			<? endif; ?>
			<div class="clearfix"></div>
		</div>
		<? foreach ($viData['items'] as $idvac => $item): ?>
			<? $personal_link = MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $idvac; ?>
			<div class="vacancy__item<?=(count($item['users'])?'':' disable')?>">
				<div class="vacancy__item-tab">
					<a href="<?=MainConfig::$PAGE_VACANCY . DS . $item['id']?>" title="Перейти на вакансию" target="_blank" class="js-g-hashint"><?=$item['title']?></a>
					<? if($item['cnt-mess']>0): ?>
						<span class="js-g-hashint vac-item__content-chat-mess" title="Сообщений"><?=$item['cnt-mess']?></span>
					<? endif; ?>
					<? if($item['cnt-mess-noread']>0): ?>
						<span class="js-g-hashint vac-item__content-chat-mess noread" title="Не прочитано"><?=$item['cnt-mess-noread']?></span>
					<? endif; ?>
					<? if(!count($item['users'])): ?>
						<span class="vacancy__item-tab-small">(нет утвержденного персонала)</span>
					<? endif; ?>	
				</div>
				<? if(count($item['users'])): ?>
					<div class="vacancy__item-content">
						<div class="vac-item__content-chat">
							<div class="vac-item__chat-users">
								<? foreach ($item['users'] as $idus): ?>
									<? if($idus != Share::$UserProfile->id): ?>
										<? $user = $viData['users'][$idus]; ?>
										<a href="<?=$user['profile']?>" title="<?=$user['name']?>" target="_blank" class="js-g-hashint">
											<img src="<?=$user['src']?>" alt="<?=$user['name']?>">
										</a>
									<? endif; ?>
								<? endforeach ?>
							</div>
							<a href="<?=$personal_link?>" class="vac-item__chat-users-name"><b>Общий чат</b>
								<? if($cntMess = count($item['public-mess'])): ?>
									<span class="js-g-hashint vac-item__content-chat-mess" title="Сообщений"><?=$cntMess?></span>
								<? endif; ?>
								<? if($item['cnt-public-noread']>0): ?>
									<span class="js-g-hashint vac-item__content-chat-mess noread" title="Не прочитано"><?=$item['cnt-public-noread']?></span>
								<? endif; ?>
							</a>
						</div>
						<? foreach ($item['users'] as $idus): ?>
							<? $user = $viData['users'][$idus]; ?>
							<? if($type!=$user['status']): // только противоположности ?>
								<div class="vac-item__content-chat personal">
									
									<a href="<?=$user['profile']?>" title="Просмотреть анкету" target="_blank" class="js-g-hashint">
										<img src="<?=$user['src']?>" alt="<?=$user['name']?>">
									</a>
									<a href="<?=$personal_link . DS . $user['id']?>" class="vac-item__chat-users-name"><b><?=$user['name']?></b>
										<? if($cntMess = count($item['personal-chat'][$user['id']]['id'])): ?>
											<span class="js-g-hashint vac-item__content-chat-mess" title="Сообщений"><?=$cntMess?></span>
										<? endif; ?>
										<? if($item['personal-chat'][$user['id']]['noread']>0): ?>
											<span class="js-g-hashint vac-item__content-chat-mess noread" title="Не прочитано"><?=$item['personal-chat'][$user['id']]['noread']?></span>
										<? endif; ?>
									</a>
								</div>
							<? endif; ?>
						<? endforeach ?>		
					</div>
				<? endif; ?>
			</div>
		<? endforeach ?>
		<? // display pagination
			$this->widget('CLinkPager', array(
				'pages' => $viData['pages'],
				'htmlOptions' => array('class' => 'paging-wrapp'),
				'firstPageLabel' => '1',
				'prevPageLabel' => 'Назад',
				'nextPageLabel' => 'Вперед',
				'header' => '',
				'cssFile' => false
			));
		?>
	<? else: ?>
		<div class="chat-list__empty">
			<h2><?=($s=='archive'?'Неактивных':'Активных')?> вакансий не найдено</h2><br>
			<div class="two__tabs">
				<? if($s=='archive'): ?>
					<a class='two__tabs-link' href='<?=$this->createUrl('',['section'=>'vacancies'])?>'>Активные</a>
					<div class='two__tabs-link active'>Архив</div>
				<? else: ?>
					<div class='two__tabs-link active'>Активные</div>
					<a class='two__tabs-link' href='<?=$this->createUrl('',['section'=>'vacancies','s'=>'archive'])?>'>Архив</a>
				<? endif; ?>
				<div class="clearfix"></div>
			</div>
			<br><br>
			<? if(Share::$UserProfile->type==2): ?>
				<a href="<?=MainConfig::$PAGE_SEARCH_VAC?>" class="chat-list__empty-btn prmu-btn">
					<span>Найти вакансию</span>
				</a>
			<? else: ?>
				<a href="<?=MainConfig::$PAGE_SEARCH_PROMO?>" class="chat-list__empty-btn prmu-btn">
					<span>Найти персонал</span>
				</a>
			<? endif; ?>
		</div>
	<? endif; ?>
</div>