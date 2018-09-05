<?php 
	$s = Yii::app()->getRequest()->getParam('section');
	if(!isset($s) || empty($s))
		$s = 'main';
	$link = MainConfig::$PAGE_PROJECT_LIST . '/' .  $project;
	$arTabs = [
		'main' => [ 'name' => 'ОСНОВНОЕ', 'link' => $link ],
		'staff' => [ 'name' => 'ПЕРСОНАЛ', 'link' => $link . '/staff' ],
		'index' => [ 'name' => 'АДРЕСНАЯ ПРОГРАММА', 'link' => $link . '/index' ],
		'geo' => [ 'name' => 'ГЕОЛОКАЦИЯ', 'link' => $link . '/geo' ],
		'route' => [ 'name' => 'МАРШРУТ ГЕО', 'link' => $link . '/route' ],
		'tasks' => [ 'name' => 'ЗАДАНИЯ', 'link' => $link . '/tasks' ],
		'report' => [ 'name' => 'ОТЧЕТЫ', 'link' => $link . '/report' ],
	];

  $this->setBreadcrumbsEx(
    array('Мои проекты', MainConfig::$PAGE_PROJECT_LIST),
    array($viData['project']['name'], MainConfig::$PAGE_PROJECT_NEW),
    array($arTabs[$s]['name'], $arTabs[$s]['link'])
  );
  $this->setPageTitle($viData['project']['name']);
?>
<div class="project__tabs">
	<?php foreach ($arTabs as $k => $item): ?>
		<a href="<?=$item['link']?>" class="<?=($s==$k)?'active':''?>">
			<b><?=$item['name']?></b>
		</a>
	<?php endforeach; ?>
</div>