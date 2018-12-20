<?
	$sectionList = MainConfig::$PAGE_CHATS_LIST_VACANCIES;
	$this->setBreadcrumbsEx(array($viData['vacancy']['title'], $sectionList . DS . $vacancy));
	$this->setPageTitle($viData['vacancy']['title']);
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/chats/item.css');
    Yii::app()->getClientScript()->registerCssFile('/jslib/magnific-popup/magnific-popup-min.css');
	Yii::app()->getClientScript()->registerScriptFile('/jslib/nicedit/nicEdit.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/chats/item-public.js', CClientScript::POS_END);

    Yii::app()->getClientScript()->registerScriptFile('/jslib/magnific-popup/jquery.magnific-popup.min.js', CClientScript::POS_END);
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
                            <a href="#" class="js-attach-file attach-file black-green -icon-before-16"><span>прикрепить файл</span></a>
                        </div>
                    </div>
				</div>
			</div>
			<input type="hidden" name="vacancy" value="<?=$vacancy?>" id="chat-vacancy">
		</form>
        <form method="post" enctype="multipart/form-data" id="F3uploaded" <?= !count($viData['files']) ?: 'style="display: block"' ?>>
            <div class="message -red"></div>
            <h3>Прикрепленные файлы</h3>
            <div id="DiImgs">
                <?php foreach ($viData['files'] ?: array() as $key => $val): ?>
                    <?php if( $val['meta']['type'] == 'images' ): ?>
                        <div class="attached-image uni-img-block">
                            <span class="uni-delete js-g-hashint" data-id="<?= $key ?>" title="удалить изображение"></span>
                            <a href="<?= $val['files']['orig'].",{$val['extmeta']->idTheme}" ?>" class="uni-img-link" target="_blank">
                                <img src="<?= $val['files']['tb'].",{$val['extmeta']->idTheme}" ?>" alt="" class="uni-img">
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div id="DiFiles">
                <?php foreach ($viData['files'] ?: array() as $key => $val): ?>
                    <?php if( $val['meta']['type'] == 'files' ): ?>
                        <div class="attached-file <?= $val['meta']['ext'] ?> uni-img-block">
                            <span class="uni-delete js-g-hashint" data-id="<?= $key ?>" title="удалить файл"></span>
                            <a href="<?= $val['files']['orig'].",{$val['extmeta']->idTheme}" ?>" class="uni-link" target="_blank">
                                <?= $val['meta']['name'] ?>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="clear"></div>
        </form>
	</div>
    <div class="mess-box-end"></div>

    <script type="text/javascript">
        <!--
        G_VARS.uniFiles = <?= json_encode($viData['files']) ?>;
        //-->
    </script>

    <div id="TmplF2upload">
        <form method="post" enctype="multipart/form-data" id="F2upload">
            <input type="hidden" name="MAX_FILE_SIZE" value="5242880">
            <h2>Добавить файл к сообщению</h2>
            <input type="file" name="img" id="UplImg">
            <div class="message -red"></div>
            <div class="btn-white-green-wr btn-upload">
                <button type="button">Выбрать и загрузить</button>
                <div class="loading-block"><span class="loading-ico"><img src="/theme/pic/loading2.gif" alt=""></span></div>
            </div>
            <p>Файл загружаемый на сайт не должен превышать размер 5 Мб, максимальный размер изображения 2500х2500 пикселей.<br />Типы файла для загрузки: JPG, PNG, DOC, XLS</p>
        </form>
    </div>

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

</div>