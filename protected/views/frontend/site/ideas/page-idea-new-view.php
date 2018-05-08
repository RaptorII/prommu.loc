<?php
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/ideas/page-idea.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/ideas/page-idea.js', CClientScript::POS_END);
?>
<div class="row">
	<div class="col-xs-12">
		<form action="<?=MainConfig::$PAGE_IDEAS_LIST?>" method="POST" id="new-idea">
			<h1 class="new-idea__title">Создать новую тему</h1>
			<label for="new-idea-name">*Заголовок темы</label>
			<input type="text" name="name" class="new-idea__input" id="new-idea-name" placeholder="Короткое описание вашей идеи">
			<label for="new-idea-text">*Описание идеи</label>
			<textarea name="text" class="new-idea__text" id="new-idea-text" placeholder="Опишите идею"></textarea>
			<div class="new-idea__bottom">
				<div class="ideas__select">
					<span>Выбрать тип</span>
					<ul>
						<li data-id="1">Идея</li>
						<li data-id="2">Ошибка</li>
						<li data-id="3">Вопрос</li>
					</ul>
					<input type="hidden" name="type" id="new-idea-type">
					<b class="glyphicon glyphicon-triangle-bottom"></b>
				</div>
				<button type="submit" class="new-idea__btn" id="new-idea-btn">Отправить</button>
			</div>
		</form>
	</div>
</div>