<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
/***********FANCYBOX************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.css');
/***********FANCYBOX************/
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-route.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-route.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js', CClientScript::POS_END);

Yii::app()->getClientScript()->registerScriptFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-map.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.css');
?>
<div class="row project">
  <div class="col-xs-12">
    <? require __DIR__ . '/project-nav.php'; ?>
  </div>
</div>
<div class="filter__veil"></div>
<div class="project__module">
  <?php if(sizeof($viData['items'])>0): ?>
    <?
      /***********UNIVERSAL FILTER************/
      Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
      Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
      /***********UNIVERSAL FILTER************/
      $arFilterData = [
        'STYLES' => 'project__tasks-filter',
        'HIDE' => false,
        'ID' => $project, //Обязательное свойство!
        'FILTER_ADDITIONAL_VALUE' => ['filter' => 1],
        'FILTER_SETTINGS' => [
          0 => [
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
          ],
          1 => [
            'NAME' => 'Название ТТ',
            'TYPE' => 'select',
            'INPUT_NAME' => 'tt_name',
            'DATA' => [
              0 => [
                'title' => 'Все',
                'id' => '0'
              ]
            ],
            'DATA_DEFAULT' => '0'
          ],
          2 => [
            'NAME' => 'Адрес ТТ',
            'TYPE' => 'select',
            'INPUT_NAME' => 'tt_index',
            'DATA' => [
              0 => [
                'title' => 'Все',
                'id' => '0'
              ]
            ],
            'DATA_DEFAULT' => '0'
          ],
          3 => [
            'NAME' => 'Дата с',
            'TYPE' => 'calendar',
            'INPUT_NAME' => 'bdate',
            'DATA' => [],
            'DATA_DEFAULT' => $viData['filter']['bdate'],
            'DATA_SHORT' => $viData['filter']['bdate-short']
          ],
          4 => [
            'NAME' => 'По',
            'TYPE' => 'calendar',
            'INPUT_NAME' => 'edate',
            'DATA' => [],
            'DATA_DEFAULT' => $viData['filter']['edate'],
            'DATA_SHORT' => $viData['filter']['edate-short']
          ],
          5 => [
            'NAME' => 'Метро',
            'TYPE' => 'select',
            'INPUT_NAME' => 'metro',
            'DATA' => [
              0 => [
                'title' => 'Все',
                'id' => '0',
                'DATA_VALUE_PARENT_ID' => 'ALL'
              ],
            ],
            'DATA_LI_VISIBLE' => '0',
            'DATA_DEFAULT' => '0',
            'CONDITION' => [
              'BLOCKED' => 'false',
              'PARENT_ID' => '0',
              'PARENT_VALUE' => '',
              'PARENT_VALUE_ID' => []
            ]
          ]
        ]
      ];
      foreach ($viData['filter']['cities'] as $id => $c)
          $arFilterData['FILTER_SETTINGS'][0]['DATA'][$id] = ['title' => $c['city'], 'id' => $id];
      foreach ($viData['filter']['tt_name'] as $n)
          $arFilterData['FILTER_SETTINGS'][1]['DATA'][] = ['title' => $n, 'id' => $n];
      foreach ($viData['filter']['tt_index'] as $i)
          $arFilterData['FILTER_SETTINGS'][2]['DATA'][] = ['title' => $i, 'id' => $i];
      foreach ($viData['filter']['metros'] as $id => $metro) {
          $arFilterData['FILTER_SETTINGS'][5]['DATA'][$id] = [
              'title' => $metro['metro'],
              'id' => $metro['id'], 
              'DATA_VALUE_PARENT_ID' => $metro['id_city']
            ];
      }
    ?>
    <div class="project__route"><? require __DIR__ . '/filter.php'; // ФИЛЬТР ?></div>
    <div class="project__route-header">
      <div class="project__addr-xls">
        <a href="#">Изменить адресную программу</a>
        <a href="#">Скачать существующую</a>
        <a href="#">Добавить адресную программу</a>
        <input type="file" name="xls" class="hide" accept="xls">
      </div>
    </div>
    <div id="content_top"></div>
    <div class="rout__main" id="ajax-content">
      <? require __DIR__ . '/project-route-ajax.php'; // СПИСОК ?>
    </div>
    <div class="project__route-changer">
      <div class="project__changer-content">
        <div class="route__table-sort">
          <div class="route__table-header">
            <div class="route__table-item__number route__table-head">№</div>
            <div class="route__table-item__name route__table-head">Название ТТ</div>
            <div class="route__table-item__address route__table-head">Адрес ТТ</div>
          </div>
          <div id="sortable">

            <div class="route__table-item" data-location="1">
              <div class="route__table-item__number">
                <div class="route__table-cell route__table-number">1</div>
              </div>
              <div class="route__table-item__name">
                <div class="route__table-cell border">АТБ1</div>
              </div>
              <div class="route__table-item__address">
                <div class="route__table-cell border route__table-index">
                  <span>ул. Пирогова 23</span>
                </div>
              </div>
            </div>

            <div class="route__table-item" data-location="2">
              <div class="route__table-item__number">
                <div class="route__table-cell route__table-number">2</div>
              </div>
              <div class="route__table-item__name">
                <div class="route__table-cell border">АТБ1</div>
              </div>
              <div class="route__table-item__address">
                <div class="route__table-cell border route__table-index">
                  <span>ул. Пирогова 23</span>
                </div>
              </div>
            </div>

            <div class="route__table-item" data-location="3">
              <div class="route__table-item__number">
                <div class="route__table-cell route__table-number">3</div>
              </div>
              <div class="route__table-item__name">
                <div class="route__table-cell border">АТБ1</div>
              </div>
              <div class="route__table-item__address">
                <div class="route__table-cell border route__table-index">
                  <span>ул. Пирогова 23</span>
                </div>
              </div>
            </div>

            <div class="route__table-item" data-location="4">
              <div class="route__table-item__number">
                <div class="route__table-cell route__table-number">4</div>
              </div>
              <div class="route__table-item__name">
                <div class="route__table-cell border">АТБ1</div>
              </div>
              <div class="route__table-item__address">
                <div class="route__table-cell border route__table-index">
                  <span>ул. Пирогова 23</span>
                </div>
              </div>
            </div>

            <div class="route__table-item" data-location="5">
              <div class="route__table-item__number">
                <div class="route__table-cell route__table-number">5</div>
              </div>
              <div class="route__table-item__name">
                <div class="route__table-cell border">АТБ1</div>
              </div>
              <div class="route__table-item__address">
                <div class="route__table-cell border route__table-index">
                  <span>ул. Пирогова 23</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <div class="project__changer-buttons">

        <div class="project__changer-button">
          <img class="project__route-touch"
          src="http://pluspng.com/img-png/png-touch-feature-ultra-soft-texture-180.png"/>
        </div>

        <div class="project__changer-button">
          <img class="project__route-touch touch__arrow-top"
          src="https://jobcart.ru/wp-content/uploads/2018/07/%D1%81%D1%82%D1%80%D0%B5%D0%BB%D0%BA%D0%B0-%D0%B2%D0%B2%D0%B5%D1%80%D1%85.png"/>
        </div>
        <div class="project__changer-button">
          <img class="project__route-touch touch__arrow-bottom"
          src="https://jobcart.ru/wp-content/uploads/2018/07/%D1%81%D1%82%D1%80%D0%B5%D0%BB%D0%BA%D0%B0-%D0%B2%D0%B2%D0%B5%D1%80%D1%85.png"/>
        </div>
      </div>

      <div class="routes__btns route__table-buttons">
        <span class="route__watch-btn route__button-save">СОХРАНИТЬ</span>
        <span class="route__watch-btn route__button-cancel">ОТМЕНИТЬ</span>
      </div>

    </div>
  <?php else: ?>
    <br><br><h2 class="center">Не найдено локаций с выбранным персоналом</h2>
  <?php endif; ?>
</div>
