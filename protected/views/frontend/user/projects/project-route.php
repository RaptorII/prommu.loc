<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/
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
          1 => [
            'title' => 'Москва',
            'id' => '1',
            'metro' => '1'
          ],
          2 => [
            'title' => 'Гонконг',
            'id' => '2'
          ]
        ],
        'DATA_DEFAULT' => '2',
      ],
      1 => [
        'NAME' => 'Название ТТ',
        'TYPE' => 'select',
        'INPUT_NAME' => 'tt_name',
        'DATA' => [
          0 => [
            'title' => 'Все',
            'id' => '0'
          ],
          1 => [
            'title' => 'Название ТТ 1',
            'id' => '1'
          ],
          2 => [
            'title' => 'Название ТТ 2',
            'id' => '2'
          ]

        ],
        'DATA_DEFAULT' => '0'
      ],
      2 => [
        'NAME' => 'Адрес ТТ',
        'TYPE' => 'select',
        'INPUT_NAME' => 'tt_address',
        'DATA' => [
          0 => [
            'title' => 'Все',
            'id' => '0'
          ],
          1 => [
            'title' => 'Алрес ТТ 1',
            'id' => '1'
          ],
          2 => [
            'title' => 'Алрес ТТ 2',
            'id' => '2'
          ]

        ],
        'DATA_DEFAULT' => '0'
      ],
      3 => [
        'NAME' => 'Дата с',
        'TYPE' => 'calendar',
        'INPUT_NAME' => 'bdate',
        'DATA' => [],
        'DATA_DEFAULT' => "21.11.2018",
        'DATA_SHORT' => "21.11.18"
      ],
      4 => [
        'NAME' => 'По',
        'TYPE' => 'calendar',
        'INPUT_NAME' => 'edate',
        'DATA' => [],
        'DATA_DEFAULT' => "27.11.2018",
        'DATA_SHORT' => "27.11.18"
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
          1 => [
            'title' => 'метро 1',
            'id' => '1',
            'DATA_VALUE_PARENT_ID' => '2'
          ],
          2 => [
            'title' => 'метро 2',
            'id' => '2',
            'DATA_VALUE_PARENT_ID' => '2'
          ],
          3 => [
            'title' => 'метро 3',
            'id' => '3',
            'DATA_VALUE_PARENT_ID' => '2'
          ],
          4 => [
            'title' => 'метро 4',
            'id' => '4',
            'DATA_VALUE_PARENT_ID' => '2'
          ]
        ],
        'DATA_LI_VISIBLE' => '0',

        'DATA_DEFAULT' => '0',
        'CONDITION' => [
          'BLOCKED' => 'false',
          'PARENT_ID' => '2',
          'PARENT_VALUE' => '',
          'PARENT_VALUE_ID' => []
        ]
      ]
    ]
  ];

  echo "<pre>";
  print_r($viData);
  echo "</pre>";
  ?>

<div class="row project">
  <div class="col-xs-12">
    <? require __DIR__ . '/project-nav.php'; ?>
  </div>
</div>


<div class="project__module">

  <div class="project__route">
    <? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>

  </div>

  <div class="project__route-header">
    <div class="project__addr-xls">
      <a href="#">Изменить адресную программу</a>
      <a href="#">Скачать существующую</a>
      <a href="#">Добавить адресную программу</a>
      <input type="file" name="xls" class="hide" accept="xls">
    </div>
  </div>

  <div id="content_top"></div>

  <div class="rout__main">
    <div class="routes">
      <?php
      foreach ($viData['items'] as $d => $date):
        foreach ($date as $city):
          ?>
          <div class="route__item">
            <h2 class="route__item-title"><?=$city['city']?> <span><?=$city['date']?></span></h2>

              <?print_r($city)?>
            <div class="route__item-box">
              <table class="route__table">
                <thead>
                  <tr>
                    <th class="route__table-cell-user">ФИО</th>
                    <th class="route__table-cell-name">Название ТТ</th>
                    <th class="route__table-cell-adres">Адрес ТТ</th>
                    <? if(!empty($city['ismetro'])): ?>
                      <th class="route__table-cell-metro">Метро</th>
                    <? endif; ?>
                    <th class="route__table-cell-status">Статус посещения</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($city['users'] as $idus => $arPoints): ?>
                    <?php $user = $viData['users'][$idus]; ?>
                    <tr>
                      <td rowspan="<?=sizeof($arPoints)?>" class="route__table-cell-user">
                        <div class="route__table-cell route__table-user">
                          <img src="<?=$user['src']?>">
                          <span><?=$user['name']?></span>

                        </div>
                      </td>
                      <?php $cnt = 0; ?>
                      <?php foreach ($arPoints as $p): ?>
                        <?php $point = $viData['points'][$p]; ?>  
                        <td class="route__table-cell-name">
                          <div class="route__table-cell border"><?=$point['name']?></div>
                        </td>
                        <td class="route__table-cell-adres">
                          <div class="route__table-cell border route__table-index">
                            <span><?=$point['adres']?></span>

                            <b data-map-project="<?=$project?>"
                               data-map-user="<?=$user['id_user']?>"
                               data-map-point="<?=$point['point']?>"
                               data-map-date="<?=$d?>"
                               class="js-g-hashint js-get-map" title="Посмотреть на карте">
                            </b>
                          </div>
                        </td>
                        <?php if(!empty($city['ismetro'])): ?>
                          <td class="route__table-cell-metro">
                            <div class="task__table-cell border task__table-index">
                              <span><?=$point['metro']?></span>
                            </div>
                          </td>
                        <?php endif; ?>

                        <td class="route__table-cell-status">
                          <div class="route__table-cell border route__table-status">
                            <span>???</span>
                            <a class="route__change-id" href="#">изменить</a>
                          </div>
                        </td>
                        <?php $cnt++; ?>
                        <? if($cnt<sizeof($viData['points'])) echo '</tr><tr>'; ?>
                      <?php endforeach; ?>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

              <div class="routes__map"></div>
              <div class="routes__btns">
                <a href="#content_top" class="route__watch-btn route__button-change">ИЗМЕНИТЬ</a>
                <span class="route__watch-btn route__button-map">СМОТРЕТЬ МАРШРУТ</span>
              </div>
            </div>                
          </div>
          <?php
        endforeach;
      endforeach;
      ?>
    </div>
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

</div>


<script>
  var mapLocation = <?=$viData['coordinates']['json']?>;
</script>