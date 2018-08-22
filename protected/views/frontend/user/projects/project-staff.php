<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);
$arPromo = array(
    0 =>array(
        'image'=>'/images/applic/20180503073112204100.jpg',
        'ttlink'=>'',
        'name'=>'Джон Смит',
        'city'=>'Moskow',
        'status' => 1
    ),
    1 =>array(
        'image'=>'/images/applic/20180503073112204100.jpg',
        'ttlink'=>'',
        'name'=>'Джон Смит',
        'city'=>'Moskow',
        'status' => 0
    ),
    2 =>array(
        'image'=>'/images/applic/20180428142455264100.jpg',
        'ttlink'=>'',
        'name'=>'Sasha Meet',
        'city'=>'Moskow',
        'status' => 1
    ),
    3 =>array(
        'image'=>'/images/applic/20180428142455264100.jpg',
        'ttlink'=>'',
        'name'=>'Sasha Meet',
        'city'=>'Moskow',
        'status' => 1
    )
);


?>

<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($arPromo); ?>
</pre>

<div class="row project">
	<div class="col-xs-12">
		<div class="project__tabs">
      <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/project-nav.php'; ?>
    </div>
  </div>
</div>

<div class="project__module">
    <div class="project__xls">
        <!--<label for="person_xls_add">-->
            <a href="#" id="add-program">Добавить персонал на проект</a>
        <!--</label>-->
        <input id="person_xls_add" type="file" name="person_xls" class="hide" accept="xls">
        <a href="/uploads/example.xls" download>Скачать пример для добавления</a>
    </div>

    <div class="project__control-panel">
        <div class="program__btns control__buttons">
            <a href="#" id="control__add-btn" class="control__add-btn">+ ДОБАВИТЬ ПЕРСОНАЛ</a>
            <a href="#" id="control__save-btn" class="program__save-btn">СОХРАНИТЬ</a>
        </div>
    </div>

    <h1 class="project__title personal__title">ПЕРСОНАЛ</h1>
    <div class="row">
    <? for($i = 0; $i < count($arPromo); $i ++): ?>
        <div class="col-xs-12 col-sm-4 col-md-3">
              <div class="personal__item">

                    <img class="<?=($arPromo[$i]['status'] == 0) ? 'personal__deact' : '';?>" src="<?=$arPromo[$i]['image']; ?>">

                    <div class="personal__item-name"><?=$arPromo[$i]['name']; ?></div>
                    <div class="personal__item-add">
                        <a href="<?=$arPromo[$i]['ttlink']; ?>">Закрепленные адреса</a>
                    </div>
                    <div class="personal__item-city"><?=$arPromo[$i]['city']; ?></div>
              </div>
        </div>
    <? endfor;?>
    <!--<div class="col-xs-12 col-sm-4 col-md-3">-->
    <!--  <div class="personal__item">-->
    <!--    <img src="/images/applic/20180503073112204100.jpg">-->
    <!--    <div class="personal__item-name">Ибадулаев Павел</div>-->
    <!--    <div class="personal__item-add">-->
    <!--      <a href="#">Закрепленные адреса</a>-->
    <!--    </div>-->
    <!--    <div class="personal__item-city">Москва</div>-->
    <!--  </div>-->
    <!--</div>-->
    <!--<div class="col-xs-12 col-sm-4 col-md-3">-->
    <!--  <div class="personal__item">-->
    <!--    <img src="/images/applic/20180430140946442100.jpg">-->
    <!--    <div class="personal__item-name">Немич Константин</div>-->
    <!--    <div class="personal__item-add">-->
    <!--      <a href="#">Закрепленные адреса</a>-->
    <!--    </div>-->
    <!--    <div class="personal__item-city">Новгород</div>-->
    <!--  </div>-->
    <!--</div>-->
    <!--<div class="col-xs-12 col-sm-4 col-md-3">-->
    <!--  <div class="personal__item">-->
    <!--    <img src="/images/applic/20180428142455264100.jpg">-->
    <!--    <div class="personal__item-name">Простова Ольга</div>-->
    <!--    <div class="personal__item-add">-->
    <!--      <a href="#">Закрепленные адреса</a>-->
    <!--    </div>-->
    <!--    <div class="personal__item-city">Рыбинск</div>-->
    <!--  </div>-->
    <!--</div>-->
    </div>
</div>
