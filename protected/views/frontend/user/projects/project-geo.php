<?php
$bUrl = Yii::app()->baseUrl;
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-geo.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-geo.js', CClientScript::POS_END);

/***********FANCYBOX************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.css');
/***********FANCYBOX************/
/***********MAP************/
Yii::app()->getClientScript()->registerScriptFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyC9M8BgorAu7Sn226LNP2rteTF5gO7KjLc');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/route-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-map.css');
/***********MAP************/
?>
<div class="filter__veil"></div>
<div class="row project">
  <div class="col-xs-12">
    <? require 'project-nav.php'; ?>
  </div>
</div>
<div class="project__module">
  <?php if (!empty($viData['id_user'])): ?>
<?
echo "<pre>";
print_r($viData);
echo "</pre>";
?>
    
    <div class="geo__item-cart">
      <div class="geo-item__cart-data">
        <img src="<?= $viData['user']['src'] ?>">
        <div class="geo-item__cart-info">
          <div class="geo-item__cart-bl1">
            <div class="geo-item__cart-name"><?= $viData['user']['name'] ?></div>
            <div class="geo-item__cart-border">
              <div>
                <? if (isset($viData['item']['bfact']) && !$viData['item']['is-missed']): ?>
                  <span class="geo__green">&#9679 активен <?
                    echo ($viData['item']['time-isactive']?'(всего '.$viData['item']['time-isactive'].')':'')
                  ?></span>
                <? else: ?>
                  <span class="geo__red">&#9679 неактивен</span>
                <? endif; ?>
              </div>
              <div>Дата: <?=$viData['date']?></div>
            </div>
          </div>
          <div class="geo-item__cart-bl2">
            <div>
              <div>
                <? if(!empty($viData['item']['bfact']) || !empty($viData['item']['time-lateness'])): ?>
                  <span>Старт работ: </span>
                  <? if(!$viData['item']['is-lateness']): ?>
                    <span class="geo__green">начал в <?=$viData['item']['bfact']?></span>
                  <? else: ?>
                    <span class="geo__red">опоздание на <?=$viData['item']['time-lateness']?> мин.</span>
                  <? endif; ?>
                <? endif; ?>
              </div>
            </div>
            <div class="geo-item__cart-cur">
              <?/*<div>
                <span>Сейчас в: <?=$viData['point']['name']?></span>
                <b
                  data-map-project="<?=$project?>"
                  data-map-user="<?=$viData['id_user']?>"
                  data-map-point="<?=$viData['id_point']?>"
                  data-map-date="<?=$viData['unix']?>"
                  class="js-g-hashint js-get-map" title="Посмотреть на карте"></b>
              </div>
              <?/*<a href="#" class="geo-item__route">Показать маршрут передвижения</a>*/?>
            </div>
          </div>
        </div>
      </div>
      <div id="user-data">
        <h2 class="geo__item-title"><?= $viData['point']['city'] ?></h2>
        <table class="geo__item-table geo-item__table-single">
          <thead>
            <tr>
              <th>Название</th>
              <th>Адрес</th>
              <th>План работ</th>
              <th>Факт работ</th>
              <th>Задачи по ТТ</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="geo__table-cell"><?=$viData['point']['name'];?></div>
              </td>
              <td>
                <div class="geo__table-cell geo__table-loc">
                  <span><?=$viData['point']['adres'];?></span>
                  <b
                  data-map-project="<?=$project?>"
                  data-map-user="<?=$viData['id_user']?>"
                  data-map-point="<?=$viData['id_point']?>"
                  data-map-date="<?=$viData['unix']?>"
                  class="js-g-hashint js-get-map" title="Посмотреть на карте"></b>
                </div>
              </td>
              <td>
                <div class="geo__table-cell"><?=$viData['item']['bplan']?></div>
              </td>
              <td>
                <div class="geo__table-cell"><?=$viData['item']['bfact']?></div>
              </td>
              <td>
                <div class="geo__table-cell"><?=count($viData['tasks'][$viData['unix']][$viData['id_point']][$viData['id_user']])?></div>
              </td>
            </tr>
          </tbody>
        </table>

          <div class="geo__route-block">
              <h2 class="geo__item-title">Маршрут передвижения</h2>

              <div class="geo__map-container">
                  <h2 class="geo__item-error">Маршрут передвижения не найден!</h2>
                  <div class="geo__route-map" id="geo__route-<?=$viData['id_user'];?>-<?=$viData['unix']?>"                                         data-map-project="<?=$project?>"
                       data-map-user="<?=$viData['id_user']?>"
                       data-map-point="<?=$viData['id_point']?>"
                       data-map-date="<?=$viData['unix']?>">
                  </div>
              </div>

          </div>

      </div>

    </div>
  <?
  //
  ?>
  <?php elseif(sizeof($viData['items'])>0): ?>
    <?
    /***********UNIVERSAL FILTER************/
    Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
    Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
    /***********UNIVERSAL FILTER************/
    $arFilterData = [
        'STYLES' => 'project__geo-filter',
        'HIDE' => false,
        'ID' => $project, //Обязательное свойство!
        'FILTER_ADDITIONAL_VALUE' => ['filter' => 1],
        'FILTER_SETTINGS' => [
          0 => [
            'NAME' => 'Фамилия',
            'TYPE' => 'text',
            'INPUT_NAME' => 'lname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
          ],
          1 => [
            'NAME' => 'Имя',
            'TYPE' => 'text',
            'INPUT_NAME' => 'fname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
          ],
          2 => [
            'NAME' => 'Статус',
            'TYPE' => 'select',
            'INPUT_NAME' => 'user_status',
            'DATA' => [
              0 => [
                'title' => 'Все',
                'id' => '0'
              ],
              1 => [
                'title' => 'Активен',
                'id' => '1'
              ],
              2 => [
                'title' => 'Неактивен',
                'id' => '2'
              ]
            ],
            'DATA_DEFAULT' => '0'
          ],
          3 => [
            'NAME' => 'Город',
            'TYPE' => 'select',
            'INPUT_NAME' => 'city',
            'DATA' => [
              0 => [
                'title' => 'Все',
                'id' => '0'
              ],
            ],
            'DATA_DEFAULT' => '0',
          ]
        ]
      ];
      foreach ($viData['filter']['cities'] as $id => $c)
        $arFilterData['FILTER_SETTINGS'][3]['DATA'][$id] = ['title' => $c['city'], 'id' => $id];
    ?>
    <div class="project__route"><? require 'filter.php'; // ФИЛЬТР ?></div>
    <div class="project__geo-list" id="ajax-content">
      <? require 'project-geo-ajax.php'; // СПИСОК ?>
    </div>
  <?php else: ?>
    <br><br><h2 class="center">На сегодня события с персоналом не установлены</h2>
  <?php endif; ?>
</div>
