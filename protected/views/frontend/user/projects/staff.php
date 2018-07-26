<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);
?>

<div class="row project">
	<div class="col-xs-12">
		<div class="project__tabs">
      <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/nav.php'; ?>
    </div>
  </div>
</div>

<div class="project__module">
  <h1 class="project__title personal__title">ПЕРСОНАЛ</h1>
  <div class="row">
    <div class="col-xs-12 col-sm-4 col-md-3">
      <div class="personal__item">
        <img src="/images/applic/20180503160015590100.jpg">
        <div class="personal__item-name">Шишкина Виктория</div>
        <div class="personal__item-add">
          <a href="#">Закрепленные адреса</a>
        </div>
        <div class="personal__item-city">Ростов</div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-3">
      <div class="personal__item">
        <img src="/images/applic/20180503073112204100.jpg">
        <div class="personal__item-name">Ибадулаев Павел</div>
        <div class="personal__item-add">
          <a href="#">Закрепленные адреса</a>
        </div>
        <div class="personal__item-city">Москва</div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-3">
      <div class="personal__item">
        <img src="/images/applic/20180430140946442100.jpg">
        <div class="personal__item-name">Немич Константин</div>
        <div class="personal__item-add">
          <a href="#">Закрепленные адреса</a>
        </div>
        <div class="personal__item-city">Новгород</div>
      </div>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-3">
      <div class="personal__item">
        <img src="/images/applic/20180428142455264100.jpg">
        <div class="personal__item-name">Простова Ольга</div>
        <div class="personal__item-add">
          <a href="#">Закрепленные адреса</a>
        </div>
        <div class="personal__item-city">Рыбинск</div>
      </div>
    </div>
  </div>
</div>
