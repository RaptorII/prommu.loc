<?
	$sectionList = MainConfig::$PAGE_CHATS_LIST_VACANCIES;
	$this->setBreadcrumbsEx(array($viData['vacancy']['title'], $sectionList . DS . $vacancy));
	$this->setPageTitle($viData['vacancy']['title']);
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/chats/item.css');
	Yii::app()->getClientScript()->registerScriptFile('/jslib/nicedit/nicEdit.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/chats/item-public.js', CClientScript::POS_END);
?>
<div class="chat-item" id="DiChatWrapp">
	<a href="<?=$sectionList?>" class="chat-item__btn-link"><span><</span> Назад</a>
	<div class="chat-item__title"><h2><?=$viData['vacancy']['title']?></h2></div>
	<div class="chat-item__messages" id="DiMessagesWrapp">
		<div id="DiMessagesInner">
			<?if(!count($viData['items'])):?>
				<p>Сообщений нет</p>
			<?else:?>
				<?require_once 'item-public-ajax.php'?>
			<?endif;?>
		</div>
	</div>
	<div class='message-box'>
		<form enctype='multipart/form-data' id="form-message">
			<div class='message'>
			<textarea name='message' id="Mmessage"></textarea>
				<div class='go clearfix'>
					<div class="btn-white-green-wr">
						<button type='button'>Отправить</button>
					</div>
					<div id="DiButtonPanel"></div>
				</div>
			</div>
			<input type="hidden" name="vacancy" value="<?=$vacancy?>" id="chat-vacancy">
		</form>
	</div>
</div>