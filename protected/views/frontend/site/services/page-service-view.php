<?php 
  $bUrl = Yii::app()->baseUrl;
  Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/services/detail.css');
  Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/dist/jquery.maskedinput.min.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/services/list.js', CClientScript::POS_END);
  $type = Share::$UserProfile->type;
  $cnt = iconv_strlen($viData['service']['name'],'UTF-8');
?>
<div class="row">
  <div class="col-xs-12 service">
    <div class="service__img <?=$viData['service']['link']?>">
      <div class="service__title<?=($cnt>30?' small':'')?>"><?=$viData['service']['name']?></div>
    </div>
    <div class="service__price">
      <div class="service__price-list">
        <div class="service__price-item">
          <b>100 руб./день</b><br>
          <span>МСК и область</span>
        </div>
        <div class="service__price-item">
          <b>100 руб./день</b><br>
          <span>МСК и область</span>
        </div>
        <div class="service__price-item">
          <b>100 руб./день</b><br>
          <span>МСК и область</span>
        </div>
      </div>
      <div 
        class="order-service"
        data-id="<?=$viData['service']['id']?>" 
        data-type="<?=$viData['service']['link']?>"
      >
        <? if(in_array($type,[2,3]) && $viData['service']['link']!='geolocation-staff'): ?>
          <a href="<?='/user/services/' . $viData['service']['link']?>" class="user">Заказать</a>
        <? else: ?>
          <a href="javascript:void(0)">Заказать</a>
        <? endif; ?>
      </div>
    </div>
    <div class="service__text"><? echo $viData['service']['html']; ?></div>
  </div>
</div>
<? require __DIR__ . '/popups.php'; ?>
