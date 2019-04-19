<?php 
  Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'private/vacansies-list.css');
  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'private/page-vac-list.js', CClientScript::POS_END);

	$arUser = $viData['user']['userInfo'];
	$cntComments = $viData['user']['lastComments']['count'][0] + $viData['user']['lastComments']['count'][1];
?>
<div class='row employer-vacansies-list'>
  <div class="col-xs-12">
    <div class="evl__header">
      <h1 class="evl__header-name"><?=$arUser['name']?></h1>
      <a class='evl__header-btn prmu-btn' href='<?= MainConfig::$PAGE_VACPUB ?>'><span>ДОБАВИТЬ ВАКАНСИЮ</span></a>  
    </div>
  </div>     
  <div class='col-xs-12 col-sm-4 col-lg-3'>
    <div class="evl__logo">
      <img src="<?=Share::getPhoto(3,$arUser['logo']);?>" class="evl-logo__img js-g-hashint" title="<?=$arUser['name']?>">
      <ul class="evl-logo__stars">
        <li class="full"></li>
      </ul>
      <span class="evl-logo__subtitle"><?=Share::getRating($arUser['rate'], $arUser['rate_neg'])?></span>
      <?php if($cntComments): ?>
        <div class="evl-logo__subtitle">
          <span>Отзывы:</span> 
          <span class="evl-logo__review evl-logo__review-red js-g-hashint" title="Отрицательные отзывы">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$arUser['id_user']?>"><?=$viData['user']['lastComments']['count'][1]?></a>
          </span>
          <span class="evl-logo__review evl-logo__review-green js-g-hashint" title="Положительные отзывы">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$arUser['id_user']?>"><?=$viData['user']['lastComments']['count'][0]?></a>
          </span> 
          <span class="ppp__logo-allrev">Всего:</span>
          <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$arUser['id_user']?>"><?=$cntComments?></a>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class='col-xs-12 col-sm-8 col-lg-9'>
    <div class="evl__vacancies">
      <div class="evl__tabs">
        <?php
          $need = DS . MainConfig::$PAGE_VACANCIES; 
          $url = Yii::app()->request->requestUri;
        ?>
        <? if(strpos($url, $need)!==false): ?>
          <div class='evl__tabs-link active'>Мои вакансии : <span><?=$viData['cnt']['vac']?></span></div>
          <a class='evl__tabs-link' href='<?= DS . MainConfig::$PAGE_VACARHIVE?>'>Архив : <span><?=$viData['cnt']['arc']?></span></a>
        <? else: ?>
          <a class='evl__tabs-link' href='<?=$need?>'>Мои вакансии : <span><?=$viData['cnt']['vac']?></span></a>
          <div class='evl__tabs-link active'>Архив : <span><?=$viData['cnt']['arc']?></span></div>
        <? endif; ?>
        <div class="clearfix"></div>
      </div>
      <hr class="evl-vacancies__line">
      <div class="evl-vacancies__list">
        <?php if( $arVacs['vacs'] ): ?>
          <?php foreach ($arVacs['vacs'] as $key => $val): ?>
            <div class='evl-vacancies__item'>
              <a class='evl-vacancies__item-name' href='<?= MainConfig::$PAGE_VACANCY . DS . $val['id'] ?>'><?= $val['title'] ?></a>
              <div class="evl-vacancies__item-info">
                <span class="evl-vacancies__item-resp">Отклики: <a href="#" class="js-g-hashint" title="Отклики детально"><?=$val['isresp'][1]?></a></span>
                <span class="evl-vacancies__item-view">Просмотры: <a href="#" class="js-g-hashint" title="Просмотры детально"><?=$val['isresp'][0]?></a></span>
              </div>
              <div class="clearfix"></div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="evl-vacancies__empty">Пока нет  вакансий в архиве</span>
        <?php endif; ?>  
      </div>
      <? $this->widget('CLinkPager', array(
          'pages' => $viData['pages'],
          'htmlOptions' => ['class' => 'paging-wrapp'],
          'firstPageLabel' => '1',
          'prevPageLabel' => 'Назад',
          'nextPageLabel' => 'Вперед',
          'header' => '',
      )) ?>
    </div>
  </div>
</div>