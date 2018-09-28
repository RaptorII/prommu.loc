<?php
    $title = 'Мои проекты';
    $this->setBreadcrumbs($title, MainConfig::$PAGE_PROJECT_LIST);
    $this->setPageTitle($title);
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/emp-list.css');
?>
<div class="row projects">
  <div class="col-xs-12">
    <div class="projects__header">
      <span class="projects__header-name"><?=$viData['employer']['name']?></span>
      <a class='projects__btn prmu-btn' href='<?=MainConfig::$PAGE_PROJECT_NEW?>'>
      	<span>Добавить проект</span>
      </a>  
    </div>
  </div>
	<div class="col-xs-12 col-sm-4 col-lg-3">
		<img src="<?=$viData['employer']['src']?>" alt="<?=$viData['employer']['name']?>" class="projects__user">
	</div>
	<div class="col-xs-12 col-sm-8 col-lg-9">
		<?php if(count($viData['items'])): ?>
			<div class="projects__list">
				<?php foreach ($viData['items'] as $id => $p): ?>
					<div class="projects__item">
						<a 
							href="<? echo MainConfig::$PAGE_PROJECT_LIST . '/' . $id ?>" 
							class="projects__item-name"><?=$p['name']?></a>
						<div class="projects__item-data">
							<div class="projects__item-date">Дата: <?=$p['date']?></div>
							<div class="projects__item-staff">Персонал: 
								<span class="js-g-hashint green" title="Приняли предложение"><?=$p['agreed']?></span> /
								<span class="js-g-hashint grey" title="Пока без реакции"><?=$p['ignored']?></span> /
								<span class="js-g-hashint red" title="Отказались"><?=$p['refused']?></span>
							</div>
						</div>		
					</div>
				<?php endforeach; ?>
			</div>
		<?php else: ?>
			<h1 class="projects__title">У ВАС ПОКА НЕТ ПРОЕКТОВ</h1>
		<?php endif; ?>
	</div>
</div>