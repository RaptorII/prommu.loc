<h3><?=$this->pageTitle?></h3>
<? if(!$viData['item'] && intval($viData['id'])): ?>
	<div class="alert danger">Данные отсутствуют</div>
<? else: ?>
	<? Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/ckeditor/ckeditor.js', CClientScript::POS_HEAD); ?>
	<? if($viData['error'] && isset($viData['messages'])): ?>
		<div class="alert danger">- <?=implode('<br>- ', $viData['messages']) ?></div>
	<? endif; ?>
	<div class="row">
		<div class="hidden-xs hidden-sm col-md-2"></div>
		<div class="col-xs-12 col-md-8">
			<form method="POST">
				<label class="d-label">
					<span>ID: </span>
					<span><?=$viData['item']['id'] ?: 'Новая'?></span>
				</label>
				<? if(intval($viData['id'])): ?>
					<br><br>
					<a href="<?=$viData['example']?>" target="_blank"><b>Проверить страницу</b></a>
				<? endif; ?>
				<div class="bs-callout bs-callout-warning">На страницу могут распространятся другие правила, которые перекроют действие параметра "Индексировать страницу"</div>
				<label class="d-label">
					<span>Индексировать страницу: </span>
					<input type="checkbox" name="index" value="1" <?=(!isset($viData['item']['index']) || $viData['item']['index']==1) ? 'checked' : ''?>>
				</label>
				<br><br>
				<label class="d-label">
					<span>Заголовок H1: </span>
					<input type="text" name="seo_h1" value="<?=$viData['item']['seo_h1']?>" class="form-control">
				</label>
				<br><br>
				<div class="bs-callout bs-callout-warning">Требуется четкое соответствие реальному урлу. Например в конце урла не должно быть символа "/", но в начале этот символ обязателен (главная страница указывается именно в виде "/")</div>
				<label class="d-label">
					<span>URL: </span>
					<input type="text" name="url" value="<?=$viData['item']['url']?>" class="form-control">
				</label>
				<br><br>
				<label class="d-label">
					<span>Титл: </span>
					<input type="text" name="meta_title" value="<?=$viData['item']['meta_title']?>" class="form-control">
				</label>
				<br><br>
				<label class="d-label">
					<span>Дескришен: </span>
					<textarea name="meta_description" class="form-control"><?=$viData['item']['meta_description']?></textarea>
				</label>
				<br>
				<div class="bs-callout bs-callout-warning">Вывод описания предусмотрен не на всех страницах. Конерктный случай необходимо обсудить с разработчиком</div>
				<label class="d-label">
					<span>Описание: </span>
					<textarea name="meta_keywords" class="form-control" id="text"><?=$viData['item']['meta_keywords']?></textarea>
				</label>
				<div class="pull-right">
					<button type="submit" class="btn btn-success d-indent" id="btn_submit">Сохранить</button>
					<a href="<?=$this->createUrl('')?>" class="btn btn-success d-indent">Назад</a>
				</div>
			</form>
		</div>
		<div class="hidden-xs col-sm-1 col-md-3"></div>
	</div>
<? endif; ?>
<style type="text/css">
	textarea{ resize: none; }
	.nicEdit-main {
		margin: 0 !important;
		padding: 4px;
		width: 100% !important;
		border-top: 1px solid #e3e3e3 !important;
		background: #fff;
	}
	#text>div:nth-child(2),
	.nicEdit-main:focus{ outline: none; }
	#text-panel .nicEdit-button{ background-image: url("/jslib/nicedit/nicEditorIcons.gif") !important; }
</style>
<script type="text/javascript">
	'use strict'
	jQuery(function($){
		CKEDITOR.replace('text');
		CKEDITOR.config.height = '750px';
	});
</script>