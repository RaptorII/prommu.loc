<?php
    $title = 'Мои проекты';
    $this->setBreadcrumbs($title, MainConfig::$PAGE_PROJECT_LIST);
    $this->setPageTitle($title);
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/emp-list.css');
	$section = Yii::app()->getRequest()->getParam('id');
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
		<div class="projects__tabs">
			<? if($section!='archive'): ?>
				<div class="projects__tabs-item active">Проекты : <?=count($viData['items'])?></div>
				<a href="<?=MainConfig::$PAGE_PROJECT_ARCHIVE?>" class="projects__tabs-item">Архив : <span><?=count($viData['archive'])?></span></a>	
			<? else: ?>
				<a href="<?=MainConfig::$PAGE_PROJECT_LIST?>" class="projects__tabs-item">Проекты : <span><?=count($viData['items'])?></span></a>
				<div class="projects__tabs-item active">Архив : <?=count($viData['archive'])?></div>	
			<? endif; ?>
			<div class="clearfix"></div>
		</div>
		<? if($section!='archive'): ?>
			<?php if(count($viData['items'])): ?>
				<a class='projects__btn all-projects prmu-btn' href='<?=MainConfig::$PAGE_PROJECT_ALL?>'>
					<span>Все проекты</span>
				</a>
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
		<?php else: ?>
			<?php if(count($viData['archive'])): ?>
				<div class="projects__list">
					<?php foreach ($viData['archive'] as $id => $p): ?>
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
				<h1 class="projects__title">У ВАС ПОКА НЕТ ПРОЕКТОВ В АРХИВЕ</h1>
			<?php endif; ?>	
		<?php endif; ?>
	</div>
</div>