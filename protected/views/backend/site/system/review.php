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
        Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/clike/clike.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/php/php.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile($codeMirror . 'mode/htmlmixed/htmlmixed.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/nicEdit.js', CClientScript::POS_HEAD);
		Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/system/item.js', CClientScript::POS_END);
		Yii::app()->getClientScript()->registerCssFile(Yii::app()->request->baseUrl . '/css/system/item.css');
		Yii::app()->getClientScript()->registerCssFile($codeMirror . 'lib/codemirror.css');
		$item = $viData['item'];
		!is_object($item) && $item = (object) ['chat_id'=>time().rand(1000,9999)];
		$bAccess = Yii::app()->user->id==$item->author_id || !intval($viData['id']);
	?>
	<? if($viData['error'] && isset($viData['messages'])): ?>
		<div class="alert danger">- <?=implode('<br>- ', $viData['messages']) ?></div>
	<? endif; ?>
	<div class="row">
		<div class="col-xs-12">
			<? if($bAccess): ?>
				<form action="" method="POST" id="form">
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
					<?
					//
					?>
					<div class="d-label">
						<span>Теги</span>
						<div class="tags_block">
							<div class="btn btn-success">+</div>
							<? if(!empty($item->tags)): ?>
								<? $arTags = explode(' ',$item->tags); ?>
								<? foreach ($arTags as $v): ?>
									<input type="text" name="tags[]" class="form-control" value="<?=$v?>">
								<? endforeach; ?>
							<? else: ?>
								<input type="text" name="tags[]" class="form-control">
							<? endif; ?>
						</div>
					</div>
					<div class="pull-right">
						<a href="<?=$this->createUrl('')?>" class="btn btn-success d-indent">Назад</a>
						<button type="submit" class="btn btn-success d-indent" id="btn_submit">Сохранить</button>
					</div>
					<input type="hidden" value="<?=$item->chat_id?>" name="chat_id">
				</form>
			<?
			// not author
			?>
			<? else: ?>
				<div class="row">
					<div class="hidden-xs col-sm-1 col-md-3"></div>
					<div class="col-xs-12 col-sm-10 col-md-6">
						<table class="table table-bordered template-table">
							<tbody>
								<tr><td><b>Название</b></td><td><?=$item->name?></td></tr>
								<tr><td colspan="2"><b>Описание</b><br><div><?echo $item->description?></div></td></tr>
								<tr><td><b>Теги</b></td><td><?=$item->tags?></td></tr>		
						</table>
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
				</div>
			<? endif; ?>
		</div>
	</div>
	<?
	//
	?>
	<?
		$this->widget(
			'YiiChatWidget',
			array(
				'chat_id' => $item->chat_id,
				'identity' => Yii::app()->user->id,
				'selector' => '#admin_chat',
				'minPostLen' => 2,
				'maxPostLen' => 512,
				'sendButtonText' => 'Отправить',
				'model' => new ChatHandler(),
				'data'=>'',
				'onSuccess' => new CJavaScriptExpression("function(code, text, post_id){}"),
				'onError' => new CJavaScriptExpression("function(errorcode, info){}")
			));
	?>
	<div id="admin_chat"></div>
	<?
	//
	?>
	<? if(!$bAccess): ?>
		<style type="text/css">
			.CodeMirror-cursors{ visibility: hidden !important; }
			.CodeMirror-code{ cursor: default !important; }
		</style>
	<? endif; ?>
<? endif; ?>