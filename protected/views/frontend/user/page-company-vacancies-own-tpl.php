<?php
  $bUrl = Yii::app()->baseUrl;
  Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/private/vacansies-list.css');
  Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/private/page-vac-list.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/projects/project-convert-vacancy.js', CClientScript::POS_END);
?>
<?php if( $mess = Yii::app()->user->getFlash('Message') ): Yii::app()->user->setFlash('Message', null) ?>
    <script type="text/javascript">var flashMes = "<?=$mess['message']?>"</script>
<?php endif; ?>
<?
  $name = Share::$UserProfile->exInfo->name;
  $idus = Share::$UserProfile->id;
  $rt = $viData['rate']['rating']['full'];
  $stars = 0;
  if($rt > 0 && $rt <= 2)
    $stars = 1;
  elseif($rt > 2 && $rt <= 2.5)
    $stars = 2;
  elseif($rt > 2.5 && $rt <= 3.5)
    $stars = 3;
  elseif($rt > 3.5 && $rt <= 4.5)
    $stars = 4;
  elseif($rt > 4.5 && $rt <= 5)
    $stars = 5;

  $cntComments = $viData['rate']['lastComments']['count'][0] + $viData['rate']['lastComments']['count'][1];
?>
<div class='row employer-vacansies-list'>
  <div class="col-xs-12">
    <div class="evl__header">
      <h1 class="evl__header-name"><?=$name?></h1>
      <a class='evl__header-btn prmu-btn' href='<?= MainConfig::$PAGE_VACPUB ?>'><span>ДОБАВИТЬ ВАКАНСИЮ</span></a>  
    </div>
  </div>     
  <div class='col-xs-12 col-sm-4 col-lg-3'>
    <div class="evl__logo">
      <img src="<?=DS . MainConfig::$PATH_EMPL_LOGO . DS . (!Share::$UserProfile->exInfo->logo ?  'logo.png' : (Share::$UserProfile->exInfo->logo) . '400.jpg')?>" class="evl-logo__img js-g-hashint" title="<?=$name?>">
      <ul class="evl-logo__stars">
        <?php
          for($i=1; $i<=5; $i++):
            if($i>$stars):?><li></li><?else:?><li class="full"></li><?endif;?>
        <?php endfor; ?>
      </ul>
      <span class="evl-logo__subtitle"><?=round($viData['rate']['rating']['full'], 2)?> из 5.0</span>
      <?php if($cntComments): ?>
        <div class="evl-logo__subtitle">
          <span>Отзывы:</span> 
          <span class="evl-logo__review evl-logo__review-red js-g-hashint" title="Отрицательные отзывы">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>"><?=$viData['rate']['lastComments']['count'][1]?></a>
          </span>
          <span class="evl-logo__review evl-logo__review-green js-g-hashint" title="Положительные отзывы">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>"><?=$viData['rate']['lastComments']['count'][0]?></a>
          </span> 
          <span class="ppp__logo-allrev">Всего:</span>
          <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>"><?=$cntComments?></a>
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
          <div class='evl__tabs-link active'>Мои вакансии : <span><?=$arCount['vac']?></span></div>
          <a class='evl__tabs-link' href='<?= DS . MainConfig::$PAGE_VACARHIVE?>'>Архив : <span><?=$arCount['arc']?></span></a>
        <? else: ?>
          <a class='evl__tabs-link' href='<?=$need?>'>Мои вакансии : <span><?=$arCount['vac']?></span></a>
          <div class='evl__tabs-link active'>Архив : <span><?=$arCount['arc']?></span></div>
        <? endif; ?>
        <div class="clearfix"></div>
      </div>
      <hr class="evl-vacancies__line">
      <div class="evl-vacancies__list">
        <?php if( $viData['vacs'] ): ?>
          <?php foreach ($viData['vacs'] as $key => $val): ?>
            <div class='evl-vacancies__item'>
              <a class='evl-vacancies__item-name' href='<?= MainConfig::$PAGE_VACANCY . DS . $val['id'] ?>'><?= $val['title'] ?></a>
              <div class="evl-vacancies__item-info">
                <span class="evl-vacancies__item-resp">Отклики: <a href="/vacancy/<?=$val['id']?>?info=resp" class="js-g-hashint" title="Отклики детально"><?=$val['isresp'][1]?></a></span>
                <span class="evl-vacancies__item-view">Просмотры: <a href="/user/analytics" class="js-g-hashint" title="Просмотры детально"><?=$viData['analytic'][$val['id']]?></a></span>
              </div>
              <?php if($val['ismoder']==100): // только для промодерированных ?>
                <div class="evl-vacancies__item-btns">
                  <div class="evl__service-btn">Услуги для вакансии</div>
                  <div class="evl__service-popup tmpl" data-id="<?=$val['id']?>" data-header="Выбор услуги">
                    <?php if(!$val['ispremium']): // если не установлен ?>
                      <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$val['id']}&service=premium"?>" class="evl-vacancies__premium">Установить Премиум статус</a>
                    <?php endif; ?>
                    <?php if(substr($val['repost'], 0,1)=='0'): ?>
                      <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$val['id']}&soc=1&page=0"?>" class="evl-vacancies__vk">Опубликовать в ВК</a>
                    <?php endif; ?>
                    <?php if(substr($val['repost'], 1,1)=='0'): ?>
                      <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$val['id']}&soc=2&page=0"?>" class="evl-vacancies__fb">Опубликовать в Facebook</a>
                    <?php endif; ?>
                    <?php if(substr($val['repost'], 2,1)=='0'): ?>
                      <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$val['id']}&soc=3&page=0"?>" class="evl-vacancies__tl">Опубликовать в Telegram</a>
                    <?php endif; ?>
                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$val['id']}&service=sms"?>" class="evl-vacancies__sms">Произвести СМС рассылку</a>
                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$val['id']}&service=email"?>" class="evl-vacancies__email">Произвести EMAIL рассылку</a>
                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$val['id']}&service=push"?>" class="evl-vacancies__push">PUSH уведомления</a>
                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$val['id']}&service=outsourcing"?>" class="evl-vacancies__atsrc">Аутсорсинг</a>
                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE."?id={$val['id']}&service=outstaffing"?>" class="evl-vacancies__outstf">Аутстаффинг</a>
                  </div> 
                  <? if(!empty($viData['projects'][$val['id']])): // проверяем наличие привязанного проекта ?>
                    <a class="evl__to-project-btn" href="<?=MainConfig::$PAGE_PROJECT_LIST . DS . $viData['projects'][$val['id']] ?>">Привязанный проект</a>
                  <? else: ?>
                    <div class="evl__to-project-btn" data-id="<?=$val['id']?>" id="to-project-btn">Сделать проектом</div>
                  <? endif; ?>
                </div>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <span class="evl-vacancies__empty">Пока нет опубликованных вакансий</span>
        <?php endif; ?>  
      </div>
      <?php // display pagination
        $this->widget('CLinkPager', array(
          'pages' => $pages,
          'htmlOptions' => array('class' => 'paging-wrapp'),
          'firstPageLabel' => '1',
          'prevPageLabel' => 'Назад',
          'nextPageLabel' => 'Вперед',
          'header' => '',
      )) ?>

      <?
      //      COMMENTS
      ?>
      <?php if($cntComments):?>
        <span class="upp__subtitle">Отзывы</span>
        <hr class="upp__line">
        <div class="upp__reviews-cnt">Отрицательных: <span class="upp__review upp__review-red"><a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link"><?=$viData['rate']['lastComments']['count'][1]?></a></span></div>
        <div class="upp__reviews-cnt">Положительных: <span class="upp__review upp__review-green"><a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link"><?=$viData['rate']['lastComments']['count'][0]?></a></span></div>
        
        <?php if($cntComments>3): ?>
          <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__btn-rating-list">все отзывы</a>
        <?php endif; ?>
      <?php else: ?>
        <span class="upp__subtitle">Отзывы отсутствуют</span>
      <? endif;?>
    </div>
  </div>
</div>