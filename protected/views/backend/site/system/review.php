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
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/php/php.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/htmlmixed/htmlmixed.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/nicEdit.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerCssFile($codeMirror . 'lib/codemirror.css');

		$item = $viData['item'];
		!is_object($item) && $item = (object) [];
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
							<div class="col-xs-12">
								<label class="d-label">
									<span>Название</span>
									<input type="text" name="name" class="form-control" autocomplete="off" value="<?=$item->name?>">
								</label>
								<label class="d-label">
									<span>Описание</span>
									<div id="description-edit-panel"></div>
									<textarea name="description" class="d-textarea form-control" id="description-edit"><?=$item->description?></textarea>
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
					<span>Код</span>
					<textarea name="code" class="d-textarea" id="transform-code"><?=$item->code?></textarea>
				</label>
				<div class="pull-right">
					<a href="<?=$this->createUrl('')?>" class="btn btn-success d-indent">Назад</a>
					<button type="submit" class="btn btn-success d-indent" id="btn_submit">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
	<?
	//
	?>
	<style type="text/css">
		.nicEdit-main {
			margin: 0 !important;
			padding: 4px;
			width: 100% !important;
			border-top: 1px solid #e3e3e3 !important;
			background: #fff;
		}
		#transform-code>div:nth-child(2),
		#description-edit>div:nth-child(2),
		.controls.input-append>div{ border: 0 !important; }
		.nicEdit-main:focus{ outline: none; }
		#description-edit-panel .nicEdit-button{ background-image: url("/jslib/nicedit/nicEditorIcons.gif") !important; }
		.CodeMirror{ min-height: 425px; }
	</style>
	<?
	//
	?>
	<script type="text/javascript">
		jQuery(function($){
			var myCodeMirror = initMirror();
			var myNicEditor = new nicEditor(
						{
							maxHeight: 600, 
							buttonList: ['bold','italic','underline','left','center','right','justify','ol','ul'] 
						}
					);

			myNicEditor.addInstance('description-edit');
			myNicEditor.setPanel('description-edit-panel');
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
		});
	</script>
<? endif; ?>