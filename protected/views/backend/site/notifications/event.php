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
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/nicEdit.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerCssFile($codeMirror . 'lib/codemirror.css');

		$item = $viData['item'];
		!is_object($item) && $item = (object) [];
		$arParams = (isset($item->params) ? unserialize($item->params) : []);
	?>
	<? if($viData['error'] && isset($viData['messages'])): ?>
		<div class="alert danger">- <?=implode('<br>- ', $viData['messages']) ?></div>
	<? endif; ?>
	<div class="row">
		<div class="col-xs-12">
			<div class="bs-callout bs-callout-info"><?=$item->comment?></div>
			<form action="" method="POST" id="notification-form">
				<div class="row">
					<div class="hidden-xs col-sm-1 col-md-3"></div>
					<div class="col-xs-12 col-sm-10 col-md-6">
						<div class="row">
							<div class="col-xs-12">
								<label class="d-label">Тип: <?=MailingEvent::$TYPES[$item->type]?></label>
								<label class="d-label">
									<span>Email (получатели)</span>
									<input type="text" name="receiver" class="form-control" value="<?=$item->receiver?>">
								</label>
								<div class="bs-callout bs-callout-warning">Возможно добавление почтовых ящиков через запятую<br>Также в этом поле осуществляется замена констант приведеных в таблице(ниже)</div>
								<label class="d-label">
									<span>Заголовок</span>
									<input type="text" name="title" class="form-control" autocomplete="off" value="<?=$item->title?>">
								</label>
								<div class="bs-callout bs-callout-warning">В этом поле осуществляется замена констант приведеных в таблице(ниже)</div>
							</div>
							<?
							//
							?>
							<div class="col-xs-12 col-sm-6">
								<br>
								<label class="d-label">
									<input type="radio" name="body_type" value="text" checked="checked" class="area_type">
									<span>Редактор</span>
								</label>
							</div>
							<div class="col-xs-12 col-sm-6">
								<br>
								<label class="d-label">
									<input type="radio" name="body_type" value="html" class="area_type">
									<span>HTML</span>
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
					<div id="transform-code-panel"></div>
					<textarea name="text" class="d-textarea" id="transform-code"><?=$item->text?></textarea>
				</label>
				<div class="bs-callout bs-callout-warning">Замена констант реальными значениями осуществляется в момент появления события при наличии соответствующей константы в списке констант(ниже)</div>
				<table class="table table-bordered template-table">
					<thead><tr><th>Константа<th>Описание</thead>
					<tbody>
						<? foreach (Mailing::mainParams() as $v): ?>
							<tr><td><?=$v['name']?><td><?=$v['description']?>
						<? endforeach; ?>
						<? foreach ($arParams as $v): ?>
							<tr><td><?=$v['name']?><td><?=$v['description']?>
						<? endforeach; ?>
					</tbody>
				</table>
				<div class="pull-right">
					<span class="btn btn-success d-indent" id="check-html">Проверить</span>
				</div>
				<iframe id="iframe-html"></iframe>
				<div class="pull-right">
					<a href="<?=$this->createUrl('')?>" class="btn btn-success d-indent">Назад</a>
					<button type="submit" class="btn btn-success d-indent" id="btn_submit">Сохранить</button>
				</div>
				<input type="hidden" name="comment" value="<?=$item->comment?>">
				<input type="hidden" name="event_type" value="<?=$item->type?>">
				<input type="hidden" name="params" value="<?=$item->params?>">
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
		.template-table{ 
			background-color: #FFFFFF;
			font-size: 14px;
		}
		.template-table td:first-child{ width: 25% }
		.template-table tbody tr td,.template-table tbody tr th{ padding: 5px; }
		.template-table tbody tr:nth-child(odd){ background-color: #f4f4f4 }
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
	</style>
	<?
	//
	?>
	<script type="text/javascript">
		jQuery(function($){
			var format = 'text',
					content = <?=json_encode($viData['template']->body)?>,
					replace = "<?=MailingTemplate::$CONTENT?>";

			var myNicEditor = new nicEditor(
						{
							maxHeight: 600, 
							buttonList: ['bold','italic','underline','left','center','right','justify','ol','ul'] 
						}
					);

			myNicEditor.addInstance('transform-code');
			myNicEditor.setPanel('transform-code-panel');
			var fullContent = content.replace(replace, myNicEditor.nicInstances[0].getContent().trim());

			setIframe(fullContent);
			var myCodeMirror = initMirror();
			$('.CodeMirror').hide();
			//
			$('#check-html').click(function()
			{
				var newVal;

				if(format==='text')
					newVal = myNicEditor.nicInstances[0].getContent().trim();
				if(format==='html')
					newVal = myCodeMirror.getValue();
				
				fullContent = content.replace(replace, newVal);
				setIframe(fullContent);
			});
			//
			$('.area_type').change(function(){
				format = this.value;
				if(format==='text')
				{
					newVal = myCodeMirror.getValue();
					myNicEditor.nicInstances[0].setContent(newVal)
					$('.CodeMirror').hide();
					$('#transform-code-panel').show();
					$('#transform-code-panel').siblings('div:eq(0)').show();
				}
				if(format==='html')
				{
					newVal = myNicEditor.nicInstances[0].getContent().trim();
					myCodeMirror.setValue(newVal);
					myCodeMirror.toTextArea();
					myCodeMirror = initMirror();
					$('.CodeMirror').show();
					$('#transform-code-panel').hide();
					$('#transform-code-panel').siblings('div:eq(0)').hide();
				}
			});
			//
			$('#btn_submit').click(function(e)
			{
				e.preventDefault();

				if(format==='text')
				{
					var newVal = myNicEditor.nicInstances[0].getContent().trim();
					myCodeMirror.setValue(newVal);	
				}

				$('#notification-form').submit();
			});
			//
			//
			//
    	function initMirror()
    	{
				var mixedMode = {
							name: "htmlmixed",
							scriptTypes: [
								{matches: /\/x-handlebars-template|\/x-mustache/i, mode: null},
								{matches: /(text|application)\/(x-)?vb(a|script)/i,mode: "vbscript"}
							]
						};

				return CodeMirror.fromTextArea(
								document.getElementById('transform-code'),
								{
									lineNumbers: true,
									matchBrackets: true,
									mode: mixedMode,
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