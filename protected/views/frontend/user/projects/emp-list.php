<?php
    $title = 'Мои проекты';
    $this->setBreadcrumbs($title, MainConfig::$PAGE_PROJECT_LIST);
    $this->setPageTitle($title);
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/emp-list.css');
?>
<div class="row projects">
	<div class="col-xs-12">
		<?php if(count($viData)): ?>
			<h1 class="projects__title">ВЫБЕРИТЕ ПРОЕКТ</h1>
			<div class="projects__list">
				<?php for($i = 0; $i < count($viData); $i ++):?>
					<a href="<? echo MainConfig::$PAGE_PROJECT_LIST . '/' . $viData[$i]['project'] ?>" class="projects__item"><?=$viData[$i]['name']?></a>
				<?php endfor; ?>
			</div>
			<div class="projects__btn">
				<a href="<?=MainConfig::$PAGE_PROJECT_NEW?>">+ Добавить проект</a>
			</div>
		<?php else: ?>
			<h1 class="projects__title">У ВАС ПОКА НЕТ ПРОЕКТОВ</h1>
			<div class="projects__btn empty">
				<a href="<?=MainConfig::$PAGE_PROJECT_NEW?>">+ Добавить проект</a>
			</div>
		<?php endif; ?>
	</div>
</div>