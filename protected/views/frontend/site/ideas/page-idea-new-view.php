<?php
	Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/ideas/page-idea.css');
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/ideas/page-idea.js', CClientScript::POS_END);

	$title = 'Идеи и предложения';
	$this->setBreadcrumbsEx(array($title, MainConfig::$PAGE_IDEAS_LIST));
	// устанавливаем title
	$title = 'Добавить идею/предложение';
	$this->pageTitle = $title;
	// устанавливаем h1
	$this->ViewModel->setViewData('pageTitle', '<h1>' . $title . '</h1>');
	// breadcrumbs
	$this->setBreadcrumbsEx(array($title, MainConfig::$PAGE_IDEA_NEW));
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
						<?php foreach ($viData['types'] as $key => $item): ?>
							<li data-id="<?=$key?>"><?=$item['idea']?></li>
						<?php endforeach; ?>
					</ul>
					<input type="hidden" name="type" id="new-idea-type">
					<b class="glyphicon glyphicon-triangle-bottom"></b>
				</div>
				<button type="submit" class="new-idea__btn" id="new-idea-btn">Отправить</button>
			</div>
			<input type="hidden" name="new-idea" value="1">
		</form>
	</div>
</div>