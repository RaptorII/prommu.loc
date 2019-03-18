<?php 
  $bUrl = Yii::app()->baseUrl;
  Yii::app()->getClientScript()->registerCssFile($bUrl . MainConfig::$CSS . 'services/detail.css');
  Yii::app()->getClientScript()->registerScriptFile($bUrl . MainConfig::$JS . 'dist/jquery.maskedinput.min.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile($bUrl . MainConfig::$JS . 'services/list.js', CClientScript::POS_END);
  $type = Share::$UserProfile->type;
  $cnt = iconv_strlen($viData['service']['name'],'UTF-8');
  $arCustom = ['outstaffing','personal-manager-outsourcing','medical-record']; // По запросу
  $arGuest = ['prommu_card','medical-record']; // для гостя
?>
<div class="row">
  <div class="col-xs-12 service">
    <div class="service__img <?=$viData['service']['link']?>">
      <div class="service__title<?=($cnt>30?' small':'')?>"><?=$viData['service']['name']?></div>
    </div>
    <div class="service__price">
      <div class="service__price-list">
        <? $arRes = $prices['prices'][$viData['service']['link']]; ?>
        <? foreach ($arRes as $price): ?>
        <div class="service__price-item<?=(sizeof($arRes)>3?' many':'')?>">
          <? if(in_array($viData['service']['link'], $arCustom)): ?>
            <span class="free">По запросу</span>
          <? elseif($price['price']): ?>
            <b><?= $price['price']?> &#8381</b><br>
            <span><?= $price['comment']?></span>
          <? else: ?>
            <span class="free">Бесплатно</span>
          <? endif; ?>
        </div>
        <? endforeach;?>
      </div>
      <div 
        class="order-service"
        data-id="<?=$viData['service']['id']?>" 
        data-type="<?=$viData['service']['link']?>"
      >
        <? if(((Share::isApplicant() || Share::isEmployer()) && $viData['service']['link']!='geolocation-staff') || in_array($viData['service']['link'], $arGuest)): ?>
          <? if(Share::isEmployer() && $viData['service']['link']==='creation-vacancy'): ?>
            <a href="<?=MainConfig::$PAGE_VACPUB?>" class="user">Разместить</a>
          <? else: ?>
            <a href="<?='/user/services/' . $viData['service']['link']?>" class="user">Заказать</a>
          <? endif; ?>
        <? else: ?>
          <? if($viData['service']['link']==='creation-vacancy'): ?>
            <a href="javascript:void(0)" class="user creation-vacancy">Разместить</a>
          <? else: ?>
            <a href="javascript:void(0)">Заказать</a>
          <? endif; ?>
        <? endif; ?>
      </div>
    </div>
    <div class="service__text"><? echo $viData['service']['html']; ?></div>
  </div>
</div>
<div class="hidden">
  <div class="creation-vacancy_mess prmu__popup">Нам очень жаль, но размещать вакансии могут только зарегистрированные работодатели<br><a href="<?=MainConfig::$PAGE_REGISTER . '?type=3'?>">Зарегистрироваться</a>
  </div>
</div>
<? require __DIR__ . '/popups.php'; ?>
