<h3><?=$this->pageTitle?></h3>
<? if(!$viData['item'] && intval($viData['id'])): ?>
	<div class="alert danger">Данные отсутствуют</div>
<? else: ?>
	<?
        $codeMirror = Yii::app()->request->baseUrl . '/plugins/codemirror/';
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'lib/codemirror.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/xml/xml.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'addon/edit/matchbrackets.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/javascript/javascript.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/css/css.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/vbscript/vbscript.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/clike/clike.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/php/php.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/htmlmixed/htmlmixed.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/nicEdit.js', CClientScript::POS_HEAD);
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/filterNotifications.js', CClientScript::POS_HEAD);

        Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl  . '/css/notifications/filterNotifications.css');
        Yii::app()->getClientScript()->registerCssFile($codeMirror . 'lib/codemirror.css');


    $item = $viData['item'];
		!is_object($item) && $item = (object) ['params'=>'','text'=>''];
		$params = unserialize($item->params);

		if(empty($item->text))
			$item->text = 'Содержимое письма';
		// if new letter => in template
		!isset($item->in_template) && $item->in_template = 1;


    /**
     *
     */


		echo "<pre>";
		print_r($viData['cotypes']);
//		print_r($item);
//		print_r();
		echo "</pre>";
	?>
	<? if($viData['error'] && isset($viData['messages'])): ?>
		<div class="alert danger">- <?=implode('<br>- ', $viData['messages']) ?></div>
	<? endif; ?>
	<div class="row">
		<div class="col-xs-12">
			<form action="" method="POST" id="notification-form">
				<div class="row">
					<div class="hidden-xs col-sm-1 col-md-3"></div>
					<div class="col-xs-12 col-sm-10 col-md-6 send_params">
						<div class="row">
							<?
							//
							?>
                            <div class="col-xs-12">
                                <label class="d-label">
                                    <input type="checkbox" name="user_all" value="0" >
                                    <span>Всем</span>
                                </label>
                                <hr>
                            </div>
							<div class="col-xs-12">
								<label class="d-label">
									<input type="checkbox" name="user_status[]" value="0"
										<?=((count($params['status']) && in_array(0, $params['status']))?'checked="checked"':'')?>>
									<span>Не активированым</span>
								</label>
								<hr>
							</div>
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12 col-md-6">
										<label class="d-label">
											<input type="checkbox" name="user_status[]" value="2"
											<?=((count($params['status']) && in_array(2,$params['status']))?'checked="checked"':'')?>>
											<span>Соискателям</span>
										</label>
									</div>
									<div class="col-xs-12 col-md-6">
										<label class="d-label">
											<input type="checkbox" name="user_status[]" value="3"
											<?=((count($params['status']) && in_array(3,$params['status']))?'checked="checked"':'')?>>
											<span>Работодателям</span>
										</label>
									</div>
								</div>
								<hr>
							</div>
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12 col-md-6">
										<label class="d-label">
											<input type="checkbox" name="user_moder[]" value="0" 
												<?=((count($params['moder']) && in_array(0, $params['moder']))?'checked="checked"':'')?>>
											<span>Промодерированым</span>
										</label>
									</div>
									<div class="col-xs-12 col-md-6">
										<label class="d-label">
											<input type="checkbox" name="user_moder[]" value="1"
												<?=((count($params['moder']) && in_array(1,$params['moder']))?'checked="checked"':'')?>>
											<span>Не промодерированым</span>
										</label>	
									</div>
								</div>
								<hr>
							</div>
							<div class="col-xs-12">
								<label class="d-label">
									<input type="checkbox" name="user_subscribe" value="1" 
										<?=$params['subscribe']?'checked="checked"':''?>>
									<span>Подписанным на новости об изменениях и новых возможностях на сайте</span>
								</label>
                                <hr>
							</div>
							<?
							//***
                            // с возможностью выбрать всех в выбранном фильтре и без него
							?>
                            <div class="col-xs-12">
                                <div class="filter filter__cities">
                                    <label class="filter__name ">Город</label>
                                    <div class="filter__content">
                                        <div class="filter__select-cities" id="filter-city" data-city="1">
                                            <ul class="filter__city-select">
                                                <li data-id="0">
                                                    <input type="text" name="fc" class="city-inp" autocomplete="off" >
                                                </li>
                                            </ul>
                                            <ul class="filter__city-list" style="display: none;">
                                                <li data-id="10783" data-metro="0">Великий Новгород</li>
                                                <li data-id="1232" data-metro="0">Белые Столбы</li>
                                                <li data-id="1231" data-metro="0">Белоомут</li>
                                                <li data-id="1230" data-metro="0">Барыбино</li>
                                                <li data-id="1229" data-metro="0">Балашиха</li>
                                                <li data-id="1228" data-metro="0">Бакшеево</li>
                                                <li data-id="1227" data-metro="0">Ашитково</li>
                                                <li data-id="1226" data-metro="0">Архангельское</li>
                                                <li data-id="1225" data-metro="0">Апрелевка</li>
                                                <li data-id="1224" data-metro="0">Алабино</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="filter filter__sex">
                                    <label class="filter__name">
                                        Пол
                                    </label>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <input name="sex[]" value="1" type="checkbox"
                                                   id="pos-1" class="user__sex-m"
                                                <?= ((count($params['sex']) && in_array(1, $params['sex'])) ? 'checked="checked"' : '') ?>>
                                            <label class="filter__content-label"
                                                   for="pos-1">Мужской</label>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <input name="sex[]" value="0" type="checkbox"
                                                   id="pos-0" class="user__sex-w"
                                                <?= ((count($params['sex']) && in_array(0, $params['sex'])) ? 'checked="checked"' : '') ?>>
                                            <label class="filter__content-label"
                                                   for="pos-0">Женский</label>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="filter filter__age">
                                    <label class="filter__name">
                                        Возраст
                                    </label>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <label class="d-label" >
                                                <span>От</span>
                                                <input type="text" name="age-start" value="<?=$params['age-start']?>">
                                            </label>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-4">
                                            <label class="d-label">
                                                <span>До</span>
                                                <input type="text" name="age-stop" value="<?=$params['age-stop']?>">
                                            </label>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="row filter filter__position">
                                    <label class="filter__name">
                                        Должность
                                    </label>
                                    <!--/*-->
                                    <div class="filter__content">
                                        <div class="col-xs-12">
                                            <input id="filter__content-all" name="poall" type="checkbox" class="filter__content-all">
                                            <label class="filter__content-all" for="filter__content-all">Выбрать все / снять все</label>
                                        </div>

                                        <?php
                                        foreach ($viData['positions'] as $position) {
                                            ?>
                                            <div class="col-xs-12 col-sm-6">
                                                <input name="position[]" value="<?= $position['id'] ?>" type="checkbox"
                                                       id="pos-<?= $position['id'] ?>" class="filter__content-input"
                                                    <?= ((count($params['position']) && in_array($position['id'], $params['position'])) ? 'checked="checked"' : '') ?>>
                                                <label class="filter__content-label"
                                                       for="pos-<?= $position['id'] ?>"><?= $position['name'] ?></label>
                                            </div>
                                        <?php } ?>

                                    </div>
                                </div>
                                <!--*/-->
                                <hr>
                            </div>
                            <div class="col-xs-12">
                                <div class="row filter filter__tpempl">
                                    <label class="filter__name">
                                        Тип работодателя
                                    </label>
                                    <div class="tpempl__filter-block">
                                        <div class="tpempl__filter-content">
                                            <div class="col-xs-12">
                                                <input name="cotype-all" type="checkbox" id="cotype-all" class="cotype__filter-input">
                                                <label class="cotype__filter-label cotype__filter-all" for="cotype-all">Выбрать все / снять все</label>
                                            </div>

                                            <?php
                                            foreach ($viData['cotypes'] as $cotype) {
                                                ?>
                                                <div class="col-xs-12 col-sm-6 col-md-4">
                                                    <input name="cotype[]" value="<?= $cotype['id'] ?>" type="checkbox"
                                                           id="cotype-<?= $cotype['id'] ?>" class="cotype__filter-input"
                                                        <?= ((count($params['cotype']) && in_array($cotype['id'], $params['cotype'])) ? 'checked="checked"' : '') ?>>
                                                    <label class="cotype-<?= $cotype['id'] ?>" for="cotype"><?= $cotype['name'] ?></label>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div>
                                </div>
                                <hr>
                            </div>

                            <?
							//***
							?>
							<div class="col-xs-12">
								<div class="bs-callout bs-callout-warning">Если не выбрать тип пользователя - отправка будет выполнятся только по полю Email</div>
								<label class="d-label">
									<span>Email (получатели)</span>
									<input type="text" name="receiver" class="form-control" value="<?=$item->receiver?>">
								</label>
								<div class="bs-callout bs-callout-warning">Возможно добавление почтовых ящиков через запятую</div>
								<label class="d-label">
									<span>Заголовок</span>
									<input type="text" name="title" class="form-control" autocomplete="off" value="<?=$item->title?>">
								</label>
							</div>

							<?
							//
							?>
							<? if(!intval($viData['id'])): ?>
								<div class="col-xs-12 col-sm-6">
									<br>
									<label class="d-label">
										<input type="radio" name="in_template" value="1" class="area_type" <?=($item->in_template ? 'checked="checked"' : '')?>>
										<span>В активном шаблоне</span>
									</label>
								</div>
								<div class="col-xs-12 col-sm-6">
									<br>
									<label class="d-label">
										<input type="radio" name="in_template" value="0" class="area_type" <?=(!$item->in_template ? 'checked="checked"' : '')?>>
										<span>HTML</span>
									</label>
								</div>
								<div class="col-xs-12"><div class="bs-callout bs-callout-warning">Режим устанавливается при создании письма и больше не меняется. Редактирование актуальных данных в двух режимах одновременно невозможно</div></div>
							<? else: ?>
								<input type="hidden" name="in_template" value="<?=$item->in_template?>" class="area_type">
							<? endif; ?>
						</div>				
					</div>
					<div class="hidden-xs col-sm-1 col-md-3"></div>
				</div>
				<?
				//
				?>
				<label class="d-label">
					<span>Текст письма</span>
					<div id="transform-code-panel"></div>
					<textarea name="text" class="d-textarea" id="transform-code"><?=$item->text?></textarea>
				</label>
				<div class="pull-right">
					<span class="btn btn-success d-indent" id="check-html">Проверить</span>
				</div>
				<iframe id="iframe-html"></iframe>
				<div class="pull-right">
					<a href="<?=$this->createUrl('',['anchor'=>'tab_letter'])?>" class="btn btn-success d-indent">Назад</a>
					<label class="btn btn-success d-indent">
						<span>Сохранить</span>
						<input type="radio" name="event_type" value="save" class="hide submit-btn">
					</label>
					<label class="btn btn-success d-indent">
						<span>Отправить</span>
						<input type="radio" name="event_type" value="send" class="hide submit-btn">
					</label>
				</div>
			</form>
		</div>
	</div>
	<?
	//
	?>
	<style type="text/css">
		#iframe-html{
			width: 100%;
			min-height: 600px;
			border: 1px solid #d2d6de;
			border-radius: 3px;
		}
		.nicEdit-main {
			margin: 0 !important;
			padding: 4px;
			width: 100% !important;
			border-top: 1px solid #e3e3e3 !important;
			background: #fff;
		}
		#transform-code>div:nth-child(2),
		.controls.input-append>div{ border: 0 !important; }
		.nicEdit-main:focus{ outline: none; }
		#transform-code-panel .nicEdit-button{ background-image: url("/jslib/nicedit/nicEditorIcons.gif") !important; }
		.CodeMirror{ min-height: 425px; }
		#notification-form hr{
			margin: 5px 0;
			border-color: #d2d6de;
		}
	</style>
	<?
	//
    /**
     * List of Buttons for buttonList option for nicEditor
     *
        'bold'
        'italic'
        'underline'
        'left'
        'center'
        'right'
        'justify'
        'ol'
        'ul'
        'subscript'
        'superscript'
        'strikethrough'
        'removeformat'
        'indent'
        'outdent'
        'hr'
        'image'
        'upload' * requires nicUpload
        'forecolor'
        'bgcolor'
        'link' * requires nicLink
        'unlink' * requires nicLink
        'fontSize' * requires nicSelect
        'fontFamily' * requires nicSelect
        'fontFormat' * requires nicSelect
        'xhtml' * required nicCode
    */
	?>
	<script type="text/javascript">
		jQuery(function($){
			var format, fullContent, myCodeMirror,
					isNew = Number("<?=$viData['id']?>"),
					content = <?=json_encode($viData['template']->body)?>,
					replace = "<?=MailingTemplate::$CONTENT?>",
					myNicEditor = new nicEditor(
						{
							maxHeight: 600,
							buttonList: [
							    'bold',
                                'italic',
                                'underline',
                                'left',
                                'center',
                                'right',
                                'justify',
                                'ol',
                                'ul',
                                'image',
                                'upload',
                            ],
						}
					);
			// get format
			$.each($('.area_type'),function(){
				if($(this).is(':checked'))
					format = this.value==='1' ? 'text' : 'html';
			});
			if(format==undefined)
				format = $('.area_type').val()==='1' ? 'text' : 'html';
			// init
			myNicEditor.addInstance('transform-code');
			myNicEditor.setPanel('transform-code-panel');
			myCodeMirror = initMirror();

			if(format==='text')
			{
				fullContent = content.replace(replace, myNicEditor.nicInstances[0].getContent().trim());
				$('.CodeMirror').hide();
				$('#transform-code-panel').show();
				$('#transform-code-panel').siblings('div:eq(0)').show();
			}
			else
			{
				fullContent = myCodeMirror.getValue();
				$('.CodeMirror').show();
				$('#transform-code-panel').hide();
				$('#transform-code-panel').siblings('div:eq(0)').hide();
			}
			setIframe(fullContent);
			// set data to iframe
			$('#check-html').click(function()
			{
				var newVal;

				if(format==='text')
				{
					newVal = myNicEditor.nicInstances[0].getContent().trim();
					fullContent = content.replace(replace, newVal);
				}
				if(format==='html')
				{
					newVal = myCodeMirror.getValue();
					fullContent = newVal;
				}
				
				setIframe(fullContent);
			});
			// format
			$('.area_type').change(function(){
				if(this.value==='1')
				{
					var textarea = document.getElementById('transform-code');
					newVal = $(textarea).html();
					myNicEditor.nicInstances[0].setContent(newVal)
					$('.CodeMirror').hide();
					$('#transform-code-panel').show();
					$('#transform-code-panel').siblings('div:eq(0)').show();
					format = 'text';
				}
				if(this.value==='0')
				{
					newVal = content.replace(replace, myNicEditor.nicInstances[0].getContent().trim());
					myCodeMirror.setValue(newVal);
					myCodeMirror.toTextArea();
					myCodeMirror = initMirror();
					$('.CodeMirror').show();
					$('#transform-code-panel').hide();
					$('#transform-code-panel').siblings('div:eq(0)').hide();
					format = 'html';
				}
			});
			// send form
			$('.submit-btn').click(function(){ 
				if(format==='text')
				{
					var newVal = myNicEditor.nicInstances[0].getContent().trim();
					myCodeMirror.setValue(newVal);	
				}
				if(this.value==='send')
				{
					var arCheckboxes = $('.send_params [type="checkbox"]'),
							bChecked = false;

					$.each(arCheckboxes,function(){
						if($(this).is(':checked'))
							bChecked = true;
					});

					if(
						bChecked 
						&& 
						!confirm('По выбранным параметрам произойдет рассылка пользователям из базы. Вы уверены?')
					)
						return false;
				}

				$('#notification-form').submit();
			});
			//
			//
			//
            /**
             * php-mode on to CodeMirror
             * 24.04.2019 Karpenko M.
             * TODO: clear coments after tester;
             */
            function initMirror()
            {
                /*var mixedMode = {
                            name: "htmlmixed",
                            scriptTypes: [
                                {matches: /\/x-handlebars-template|\/x-mustache/i, mode: null},
                                {matches: /(text|application)\/(x-)?vb(a|script)/i,mode: "vbscript"},
                                {matches: /(text|application)\/(x-)?vb(a|script)/i,mode: "vbscript"},
                            ]
                        };
                */

                return CodeMirror.fromTextArea(
                    document.getElementById('transform-code'),
                    {
                        lineNumbers: true,
                        matchBrackets: true,
                        autoCloseBrackets: true,
                        //mode: mixedMode,
                        mode: "application/x-httpd-php",
                        indentUnit: 2
                    }
                );

            }
			function setIframe(content)
			{
				var iframe = document.getElementById('iframe-html');

				iframe = iframe.contentWindow || ( iframe.contentDocument.document || iframe.contentDocument);
				iframe.document.open();
				iframe.document.write(content);
				iframe.document.close();
			}
		});
	</script>
<? endif; ?>