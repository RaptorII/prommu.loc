<?
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/chats/list-section.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl.'js/chats/list.js', CClientScript::POS_END);
?>
<? if($mess = Yii::app()->user->getFlash('Message')):?>
    <form class="complete-popup tmpl"><?=$mess['message']?></form>
<? endif; ?>
<div class="chat-list feedback">
	<a href="<?=MainConfig::$PAGE_CHATS_LIST?>" class="chat-list__btn-link"><span><</span> Назад</a>
	<? if(sizeof($viData['items'])): ?>
		<h2>Сообщения Prommu <span><?=$viData['cnt']?></span></h2>
		<? foreach ($viData['items'] as $id => $item): ?>
			<a href="<?=MainConfig::$PAGE_CHATS_LIST_FEEDBACK . DS . $id?>" class="chat__item">
				<div class="chat__item-user">
						<div class="chat__item-logo">
							<img src="<?=$viData['users'][$item['user']]['src']?>" alt="<?=$viData['users'][$item['user']]['name']?>">
						</div>
                        <div class="chat__item-data">
                            <div class="chat__title"><?=$item['title']?></div>
                            <div class="chat__user"><?=$viData['users'][$item['user']]['name']?></div>
                        </div>
				</div>
                <div class="chat__item-info">
                    <div class="chat__info-item"><div>Всего сообщений:</div><div><?=$item['cnt-mess']?></div></div>
                    <div class="chat__info-item"><div>Не прочитано:</div><div><?=$item['cnt-noread']?></div></div>
                    <div class="chat__info-item"><div>Направление:</div><div><?=$viData['directs'][$item['direct']]['name']?></div></div>
                </div>
			</a>
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
			<h2>У Вас нет обращений к администрации портала Prommu</h2>
			<a href="<?=MainConfig::$PAGE_FEEDBACK?>" class="chat-list__empty-btn prmu-btn"><span>Задать вопрос</span></a>
		</div>
	<? endif; ?>
</div>