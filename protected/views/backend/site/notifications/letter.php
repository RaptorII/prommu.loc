<h3><?=$this->pageTitle?></h3>
<? if(!$viData['item'] && intval($viData['id'])): ?>
	<div class="alert danger">Данные отсутствуют</div>
<? else: ?>
	<?
		$codeMirror = Yii::app()->request->baseUrl . '/plugins/codemirror/';
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'lib/codemirror.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/xml/xml.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/javascript/javascript.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/css/css.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/vbscript/vbscript.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/htmlmixed/htmlmixed.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerCssFile($codeMirror . 'lib/codemirror.css');
		
		$item = $viData['item'];
		!is_object($item) && $item = (object) ['params'=>'','text'=>''];
		$params = unserialize($item->params);

		if(empty($item->text))
			$item->text = $viData['template']->body;
	?>
	<? if($viData['error'] && isset($viData['messages'])): ?>
		<div class="alert danger">- <?=implode('<br>- ', $viData['messages']) ?></div>
	<? endif; ?>
	<div class="row">
		<div class="col-xs-12">
			<form action="" method="POST" id="notification-form">
				<div class="row">
					<div class="hidden-xs col-sm-1 col-md-3"></div>
					<div class="col-xs-12 col-sm-10 col-md-6">
						<div class="row">
							<?
							//
							?>
							<div class="col-xs-12 col-md-6">
								<label class="d-label">
									<input type="checkbox" name="user_status[]" value="0"
										<?=((count($params['status']) && in_array(0, $params['status']))?'checked="checked"':'')?>>
									<span>Не активированым</span>
								</label>
								<label class="d-label">
									<input type="checkbox" name="user_status[]" value="2"
									<?=((count($params['status']) && in_array(2,$params['status']))?'checked="checked"':'')?>>
									<span>Соискателям</span>
								</label>
								<label class="d-label">
									<input type="checkbox" name="user_status[]" value="3"
									<?=((count($params['status']) && in_array(3,$params['status']))?'checked="checked"':'')?>>
									<span>Работодателям</span>
								</label>
							</div>
							<?
							//
							?>
							<div class="col-xs-12 col-md-6">
								<label class="d-label">
									<input type="checkbox" name="user_moder[]" value="0" 
										<?=((count($params['moder']) && in_array(0, $params['moder']))?'checked="checked"':'')?>>
									<span>Промодерированым</span>
								</label>
								<label class="d-label">
									<input type="checkbox" name="user_moder[]" value="1"
										<?=((count($params['moder']) && in_array(1,$params['moder']))?'checked="checked"':'')?>>
									<span>Не промодерированым</span>
								</label>						
							</div>
							<?
							//
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
						</div>
					</div>
					<div class="hidden-xs col-sm-1 col-md-3"></div>
				</div>
				<?
				//
				?>
				<label class="d-label">
					<span>Текст письма</span>
					<textarea name="text" class="d-textarea" id="transform-code"><?=$item->text?></textarea>
				</label>
				<div class="pull-right">
					<span class="btn btn-success d-indent" id="check-html">Проверить</span>
				</div>
				<iframe id="iframe-html"></iframe>
				<div class="pull-right">
					<a href="<?=$this->createUrl('')?>" class="btn btn-success d-indent">Назад</a>
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
	</style>
	<?
	//
	?>
	<script type="text/javascript">
		jQuery(function($){
			var iframe = document.getElementById('iframe-html');
			var mixedMode = {
						name: "htmlmixed",
						scriptTypes: [
							{matches: /\/x-handlebars-template|\/x-mustache/i, mode: null},
							{matches: /(text|application)\/(x-)?vb(a|script)/i,mode: "vbscript"}
						]
					};

			var myCodeMirror = CodeMirror.fromTextArea(
							document.getElementById('transform-code'),
							{
								lineNumbers: true,
								matchBrackets: true,
								mode: mixedMode,
								indentUnit: 2
							}
						);

			iframe = iframe.contentWindow || ( iframe.contentDocument.document || iframe.contentDocument);
			iframe.document.open();
			iframe.document.write(myCodeMirror.getValue());
			iframe.document.close();

			$('#check-html').click(function()
			{
				iframe.document.open();
				iframe.document.write(myCodeMirror.getValue());
				iframe.document.close();
			});
			$('.submit-btn').click(function(){ $('#notification-form').submit() });
		});
	</script>
<? endif; ?>