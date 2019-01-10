<?php
$s = Yii::app()->getRequest()->getParam('section');
if(!isset($s) || empty($s))
    $s = 'main';
$link = MainConfig::$PAGE_PROJECT_LIST . '/' .  $project;
$arTabs = [
    'main' => [ 'name' => 'ГЛАВНАЯ', 'link' => $link ],
    'route' => [ 'name' => 'МАРШРУТ', 'link' => $link . '/route' ],
    'cabinet' => [ 'name' => 'КАБИНЕТ', 'link' => $link . '/tasks' ],
];

$this->setBreadcrumbsEx(
    array('Мои проекты', MainConfig::$PAGE_PROJECT_LIST),
    array($viData['project']['name'], $link)
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