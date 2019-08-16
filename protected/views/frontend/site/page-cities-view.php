<?php
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'othercities.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'othercities.js', CClientScript::POS_END);
  $SubdomainCache = Subdomain::getCacheData();
  $arCities = [];
  foreach ($SubdomainCache->data as $id => $v)
  {
    if($id!=$SubdomainCache->id)
    {
      $arCities[$id] = $v;
    }
  }
  $cnt = count($arCities);
?>
<div class="row">
  <div class="col-xs-12 othercities">
    <? if($cnt): ?>
      <form id="cities_form">
        <label for="search_field">Укажите город, который тербуется найти</label>
        <input type="text" id="search_field">
      </form>
      <div id="cities_block">
        <? $col = 1;//($cnt<=10 ? 1 : 2); ?>
        <ul class="othercities_list col-<?=$col?>">
          <? foreach ($arCities as $id => $site): ?>
            <li class="othercities_item">
              <a href="<?=$site['url']?>"><?=$site['city']?></a>
            </li>
          <? endforeach; ?>
          <li class="othercities_item-empty">К сожалению такой город не имеет поддомена</li>
        </ul>
      </div>
    <? else: ?>
      Доступных городов нет
    <? endif; ?>
  </div>
</div>