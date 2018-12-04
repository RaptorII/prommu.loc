<?
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/chats/list-section.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl.'js/chats/list.js', CClientScript::POS_END);
?>
<div class="chat-list vacancies">
	<a href="<?=MainConfig::$PAGE_CHATS_LIST?>" class="chat-list__btn-link"><span><</span> Назад</a>
	<? if(count($viData['items'])): ?>
		<h2>Сообщения по вакансиям <span><?=$viData['cnt']?></span></h2>
		<? foreach ($viData['items'] as $idvac => $item): ?>
			<? $personal_link = MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $idvac; ?>
			<div class="vacancy__item<?=(count($item['users'])?'':' disable')?>">
				<div class="vacancy__item-tab">
					<a href="<?=MainConfig::$PAGE_VACANCY . DS . $item['id']?>" title="Перейти на вакансию" target="_blank" class="js-g-hashint"><?=$item['title']?></a>
					<? if(!count($item['users'])): ?>
						<span class="vacancy__item-tab-small">(нет утвержденного персонала)</span>
					<? endif; ?>
				</div>
				<? if(count($item['users'])): ?>
					<div class="vacancy__item-content">
						<div class="vac-item__content-chat">
							<div class="vac-item__chat-users">
								<? foreach ($item['users'] as $id_promo): ?>
									<? $user = $viData['promos'][$id_promo]; ?>
									<a href="<?=$user['profile']?>" title="<?=$user['name']?>" target="_blank" class="js-g-hashint">
										<img src="<?=$user['src']?>" alt="<?=$user['name']?>">
									</a>
								<? endforeach ?>
							</div>
							<a href="<?=$personal_link?>">Общий чат</a>
						</div>
						<? foreach ($item['users'] as $id_promo): ?>
							<div class="vac-item__content-chat personal">
								<? $user = $viData['promos'][$id_promo]; ?>
								<a href="<?=$user['profile']?>" title="<?=$user['name']?>" target="_blank" class="js-g-hashint">
									<img src="<?=$user['src']?>" alt="<?=$user['name']?>">
								</a>
								<a href="<?=$personal_link . DS . $user['id']?>">Личный чат</a>
							</div>
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
			<? if(Share::$UserProfile->type==2): ?>
				<h2>Для начала необходимо отозаваться на вакансию</h2>
				<a href="<?=MainConfig::$PAGE_SEARCH_VAC?>" class="chat-list__empty-btn prmu-btn">
					<span>Найти вакансию</span>
				</a>
			<? else: ?>
				<h2>Для начала необходимо создать вакансию и пригласить на нее персонал</h2>
				<a href="<?=MainConfig::$PAGE_SEARCH_PROMO?>" class="chat-list__empty-btn prmu-btn">
					<span>Найти персонал</span>
				</a>
			<? endif; ?>
		</div>
	<? endif; ?>
</div>