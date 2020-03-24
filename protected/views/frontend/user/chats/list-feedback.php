<?
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/chats/list-section.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl.'js/chats/list.js', CClientScript::POS_END);

  $status = Feedback::getAdminStatus();
?>
<div class="chat-list feedback">
	<a href="<?=MainConfig::$PAGE_CHATS_LIST?>" class="chat-list__btn-link mobile-none"><span><</span> Назад</a>
	<? if(sizeof($viData['items'])): ?>
		<h2>Сообщения Prommu 
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
        <div class="chat-list__wrap">
            <div class="chat-list__wrap-scroll-ico">
            <? foreach ($viData['items'] as $id => $item): ?>
                <a href="<?=MainConfig::$PAGE_CHATS_LIST_FEEDBACK . DS . $id?>" class="chat__item">
                    <span class="chat__item-user">
                            <span class="chat__item-logo">
                                <img src="<?=$viData['users'][$item['user']]['src']?>" alt="<?=$viData['users'][$item['user']]['name']?>">
                            </span>
                            <span class="chat__item-data">
                                <span class="chat__title"><?=$item['title']?></span>
                                <span class="chat__user"><?=$viData['users'][$item['user']]['name']?></span>
                            </span>
                    </span>
                    <span class="chat__item-info">
                        <span class="chat__info-item"><span>Всего сообщений:</span><span><?=$item['cnt-mess']?></span></span>
                        <span class="chat__info-item"><span>Не прочитано:</span><span><?=$item['cnt-noread']?></span></span>
                        <span class="chat__info-item"><span>Направление:</span><span><?=$viData['directs'][$item['direct']]['name']?></span></span>
                        <span class="chat__info-item">
                            <span>Статус:</span>
                            <span>
                                <?=$status[$viData['statuses'][$item['id']]['status']]?>
                            </span>
                        </span>
                    </span>
                </a>
            <? endforeach ?>
            </div>
        </div>
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
    <div class="pull-right">
      <a href="<?=MainConfig::$PAGE_FEEDBACK ?>" class="prmu-btn prmu-btn_normal">
        <span>Задать вопрос Prommu</span>
      </a>
      <div class="clearfix"></div>
    </div>
	<? else: ?>
		<div class="chat-list__empty">
			<h2>У Вас нет обращений к администрации портала Prommu</h2>
			<a href="<?=MainConfig::$PAGE_FEEDBACK?>" class="chat-list__empty-btn prmu-btn"><span>Задать вопрос</span></a>
		</div>
	<? endif; ?>
</div>
