<?php
    $title = 'Мои проекты';
    $this->setBreadcrumbs($title, MainConfig::$PAGE_PROJECT_LIST);
    $this->setPageTitle($title);
	$bUrl = Yii::app()->baseUrl;
	$data['projects'] = array(
    0 => array(
      'id' => 2323));

	$projects = $data['projects'];
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/emp-list.css');
?>
<div class="row projects">
	<div class="col-xs-12">
		<h1 class="projects__title">ВЫБЕРИТЕ ПРОЕКТ</h1>
		<div class="projects__list">
		    <? for($i = 0; $i < count($projects); $i ++):?>
			<a href="/user/projects/<?=$projects['id']?>" class="projects__item">ПРОЕКТ 1</a>
			<? endfor;?>
		</div>
		<div class="projects__btn">
			<a href="<?=MainConfig::$PAGE_PROJECT_NEW?>">+ Добавить проект</a>
		</div>
	</div>
</div>