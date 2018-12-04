<?
echo "<pre>";
print_r($viData); 
echo "</pre>";
	$sectionList = MainConfig::$PAGE_CHATS_LIST_VACANCIES;
	$this->setBreadcrumbsEx(array($viData['vacancy']['title'], $sectionList . DS . $vacancy . DS . $id));
	$this->setPageTitle($viData['vacancy']['title']);
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/chats/item.css');
	Yii::app()->getClientScript()->registerScriptFile('/jslib/nicedit/nicEdit.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/chats/item-personal.js', CClientScript::POS_END);
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
					<div id="DiButtonPanel">
						<div class="add-panel">
							<div class="divider"></div>
							<a href="javascript:void(0)" class="js-attach-file attach-file black-green -icon-before-16">прикрепить файл</a>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="vacancy" value="<?=$vacancy?>" id="chat-vacancy">
			<input type="hidden" name="user" value="<?=$id?>" id="chat-user">
			<input type="hidden" name="new" value="<?=(count($viData['items'])?'0':'1')?>" id="chat-new">
		</form>
	</div>
</div>



<form method="post" enctype="multipart/form-data" id="F2upload" class="tmpl">
	<input type="hidden" name="MAX_FILE_SIZE" value="5242880">
	<h2>Добавить файл к сообщению</h2>
	<input type="file" name="img" id="UplImg">
	<div class="message -red"></div>
	<div class="btn-white-green-wr btn-upload">
		<button type="button">Выбрать и загрузить</button>
		<div class="loading-block">
			<span class="loading-ico">
				<img src="/theme/pic/loading2.gif" alt="">
			</span>
		</div>
	</div>
	<p>Файл загружаемый на сайт не должен превышать размер 5 Мб, максимальный размер изображения 2500х2500 пикселей.<br />Типы файла для загрузки: JPG, PNG, DOC, XLS</p>
</form>
<div class="attached-image attached-image-tpl tmpl uni-img-block">
	<span class="uni-delete js-hashint" title="удалить файл"></span>
	<a href="" class="uni-img-link" target="_blank">
		<img src="" alt="" class="uni-img">
	</a>
</div>
<div class="attached-file attached-file-tpl tmpl uni-img-block">
	<span class="uni-delete js-hashint" title="удалить изображение"></span>
	<a href="" class="uni-link" target="_blank"></a>
</div>