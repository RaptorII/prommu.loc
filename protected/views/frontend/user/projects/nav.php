<?php 
    $title = 'Мои проекты';
    $this->setBreadcrumbs($title, MainConfig::$PAGE_PROJECT_LIST);
    //$this->setPageTitle($title);

	$id = Yii::app()->getRequest()->getParam('id');
	$s = Yii::app()->getRequest()->getParam('section');
	$link = MainConfig::$PAGE_PROJECT_LIST . '/' .  $id;
?>
<a href="<? echo $link?>" class="<?=(!isset($s) || empty($s))?'active':''?>">
	<b>ОСНОВНОЕ</b>
</a>
<a href="<? echo $link . '/staff'?>" class="<?=$s=='staff'?'active':''?>">
	<b>ПЕРСОНАЛ</b>
</a>
<a href="<? echo $link . '/index'?>" class="<?=$s=='index'?'active':''?>">
	<b>АДРЕСНАЯ ПРОГРАММА</b>
</a>
<a href="<? echo $link . '/geo'?>" class="<?=$s=='geo'?'active':''?>">
	<b>ГЕОЛОКАЦИЯ</b>
</a>
<a href="<? echo $link . '/route'?>" class="<?=$s=='route'?'active':''?>">
	<b>МАРШРУТ ГЕО</b>
</a>
<a href="<? echo $link . '/tasks'?>" class="<?=$s=='tasks'?'active':''?>">
	<b>ЗАДАНИЯ</b>
</a>
<a href="<? echo $link . '/report'?>" class="<?=$s=='report'?'active':''?>">
	<b>ОТЧЕТЫ</b>
</a>