<?php
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/list.css');
?>
<div class="row projects">
	<div class="col-xs-12">
		<h1 class="projects__title">ВЫБЕРИТЕ ПРОЕКТ</h1>
		<div class="projects__list">
			<a href="/user/projects/1" class="projects__item">ПРОЕКТ 1</a>
			<a href="/user/projects/1" class="projects__item">ПРОЕКТ 2</a>
			<a href="/user/projects/1" class="projects__item">ПРОЕКТ 3</a>
		</div>
		<div class="projects__btn">
			<a href="<?=MainConfig::$PAGE_PROJECT_NEW?>">+ Добавить проект</a>
		</div>
	</div>
</div>