<?php
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'othercities.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'othercities.js', CClientScript::POS_END);
  $SubdomainCache = Subdomain::getCacheData();
  //
  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! только индексируемые города
  //
  $arIndexCities = [149,499,1251,1252,1260,2260,684,1279,10785,1285,1301,1309,1315,9256,1322,1612,1327,1332,1335,1336,1879,1344,1346,2243,2300,2339,1369,2373,1381,1384,2467];
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
        <? $col = 1;//($cnt<=10 ? 1 : 2); ?>
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