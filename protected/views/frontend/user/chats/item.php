<? 
	!empty($viData['title']['title']) && $title = $viData['title']['title'];
	!empty($viData['title']['vactitle']) && $title = $viData['title']['vactitle'];
	empty($title) && $title = 'Без названия';
	$sectionList = MainConfig::$PAGE_CHATS_LIST . DS . $section;

	if(!empty($title))
	{
		$this->setBreadcrumbsEx(array($title, $sectionList . DS . $id));
		$this->setPageTitle($title);		
	}
	$type = Share::$UserProfile->type;
	Yii::app()->getClientScript()->registerCssFile('/theme/css/chats/item.css');
	Yii::app()->getClientScript()->registerCssFile('/jslib/magnific-popup/magnific-popup-min.css');
	Yii::app()->getClientScript()->registerScriptFile('/theme/js/chats/item-' . ($type==3?'emp.js':'app.js'), CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile('/jslib/nicedit/nicEdit.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile('/jslib/magnific-popup/jquery.magnific-popup.min.js', CClientScript::POS_END);
?>
<div class="message"><? echo ($viData['error'] ? $viData['message'] : '')?></div>
<div class="chat-item">
	<? if(!$viData['error']): ?>
		<a href="<?=$sectionList?>" class="chat-item__btn-link"><span><</span> Назад</a>
		<? if( $isNew ): ?>
			<div class="theme-input">
				<? if( $viData['themes'] ): ?>
					<label id="LCBtheme">
						<b>Выберите тему диалога из заявок этого пользователя на ваши вакансии</b>
						<select name="theme" id="CBTheme">
							<option value="aa">- Выберите тему -</option>
							<? foreach ($viData['themes'] as $key => $val): ?>
								<option value="<?= $val['id'] ?>"><?= $val['title'] ?></option>
							<? endforeach; ?>
						</select>
					</label>
					<br /><br />или<br /><br />
				<? endif; ?>
				<label id="LEdTheme">
					<b>Введите новую тему</b>
					<input type="text" name="theme-cust" id="EdTheme"/>
				</label><br /><br />
			</div>
		<? endif; ?>
		<div class="chat-item__title">
			<h2>
				<? 
					if( !$isNew ) 
						echo $title;
				?>
			</h2>
		</div>



		<div id="DiChatWrapp">
			<div class="mess-block">
				<div class="mess-block-inner">
					<div id="DiMessagesWrapp">
						<? if( $isNew ): ?>
							<div class="nomess">Нет сообщений в этой теме. Выберите тему и введите сообщение...</div>
						<? endif; ?>
						<div id="DiMessagesInner">
							<div id="DiMessages"></div>
						</div>
					</div>
				</div>
			</div>



			<div class='message-box'>
				<form enctype='multipart/form-data'>
					<div class='message'>
					<textarea name='sendmess' id="Mmessage"></textarea>
						<div class='go clearfix'>
						<div class="btn-white-green-wr">
							<button type='button'>Отправить</button>
						</div>
						<div id="DiButtonPanel">
							<div class="add-panel">
								<div class="divider"></div>
								<a href="#" class="js-attach-file attach-file black-green -icon-before-16">
									<span>прикрепить файл</span>
								</a>
							</div>
						</div>
						</div>
					</div>
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
        </div>










		<div class="prev-mess-tpl tmpl">
			<a href='#prev-mess' class="green-orange">показать предыдущие сообщения</a>
		</div>

		<div class="new-mess-tpl tmpl"><div><b>Новые сообщения</b></div></div>

		<div class='mess-box mess-from tmpl'>
			<div class='author'>
				<img src="" alt="">
				<b class='fio'><!-- fio --></b>
				<span class='date'><!-- date --></span>
			</div>
			<div class='mess'><!-- mess --></div>
			<div class='files'>
				<div class="js-container"></div>
				<a href="" class="black-orange"><img src="" alt=""></a>
			</div>
		</div>

		<div class='mess-box mess-to tmpl'>
			<div class='author'>
				<img src="" alt="">
				<b class='fio'><!-- fio --></b>
				<span class='date'><!-- date --></span>
			</div>
			<div class='mess'><!-- mess --></div>
			<div class='files'>
				<div class="js-container"></div>
				<a href="" class="black-orange"><img src="" alt=""></a>
			</div>
		</div>
		<script type="text/javascript">
			<!--
			G_VARS.idTm = '<?=$id?>';
			<? if( $isNew ): ?>
				G_VARS.isNew = '<?=$isNew?>';
			<? endif; ?>
			G_VARS.uniFiles = <?=json_encode($viData['files'])?>;
			//-->
		</script>
		<script type="text/template" id="TmplF2upload">
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
		</script>
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
	<?php endif; ?>
</div>