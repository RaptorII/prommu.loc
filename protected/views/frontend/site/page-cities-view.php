<?php
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'othercities.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'othercities.js', CClientScript::POS_END);
  $SubdomainCache = Subdomain::getCacheData();
  //
  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! только индексируемые города
  //
  $arIndexCities = [1838,1307,1449,571,1973,149,437,684,2260,1879,2243,2300,2339,2467,2127,1072];
  $arCities = [];
  foreach ($SubdomainCache->data as $id => $v)
  {
    if(in_array($id, $arIndexCities) && $id!=$SubdomainCache->id)
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
        <? $col = ($cnt<=10 ? 1 : 2); ?>
        <ul class="othercities_list col-<?=$col?>">
          <? foreach ($arCities as $id => $site): ?>
            <li class="othercities_item">
              <a href="<?=$site['url']?>"><?=$site['city']?></a>
            </li>
          <? endforeach; ?>
          <li class="othercities_item-empty">Город не найден</li>
        </ul>
      </div>
    <? else: ?>
      Доступных городов нет
    <? endif; ?>
  </div>
</div>