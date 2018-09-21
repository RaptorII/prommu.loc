<?php
$bUrl = Yii::app()->baseUrl;
$idus = Yii::app()->getRequest()->getParam('user_id');
$unixTime = Yii::app()->getRequest()->getParam('unix');
$project = Yii::app()->getRequest()->getParam('id');
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-geo.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-geo.js', CClientScript::POS_END);

/*$viData['dates'] = array(
    'bdate' => '07.02.18',
    'bdate-full' => '07.02.2018',
    'edate' => '01.10.18',
    'edate-full' => '01.10.2018'
);
$viData['index'] = array(
1307 => array(
  'name' => 'Москва',
  'id' => 1307,
  'metro' => 1,
  'locations' => array(
    1 => array(
      'id' => 1,
      'name' => 'АТБ1',
      'index' => 'ул. Исполкомовская 123',
      'metro' => array(
        1 => 'Авиамоторная',
        2 => 'Автозаводская (Замоскворецкая линия)',
        4 => 'Алексеевская'
      ),
      'periods' => array(
        1 => array(
          'id' => 1,
          'bdate' => '07.02.18',
          'edate' => '08.02.18',
          'btime' => '14:00',
          'etime' => '16:00'
        ),
        2 => array(
          'id' => 2,
          'bdate' => '20.02.18',
          'edate' => '22.02.18',
          'btime' => '09:00',
          'etime' => '18:00'
        )
      )
    ),
    2 => array(
      'id' => 2,
      'name' => 'АТБ2',
      'index' => 'ул. Исполкомовская 777',
      'metro' => array(
        4 => 'Алексеевская'
      ),
      'periods' => array(
        3 => array(
          'id' => 3,
          'bdate' => '01.08.18',
          'edate' => '01.08.18',
          'btime' => '12:00',
          'etime' => '13:00'
        )
      )
    )
  ),
  'users' => array(1,15)
),
2582 => array(
  'name' => 'Донецк',
  'id' => 2582,
  'metro' => 0,
  'locations' => array(
    3 => array(
      'id' => 3,
      'name' => 'АТБ3',
      'index' => 'ул. Исполкомовская 999',
      'metro' => array(),
      'periods' => array(
        4 => array(
          'id' => 4,
          'bdate' => '07.02.18',
          'edate' => '08.02.18',
          'btime' => '14:00',
          'etime' => '16:00'
        ),
        5 => array(
          'id' => 5,
          'bdate' => '20.02.18',
          'edate' => '22.02.18',
          'btime' => '09:00',
          'etime' => '18:00'
        )
      )
    )
  ),
  'users' => array(22)
)
);
$viData['users'] = array(
    1 => array(
        'id' => 1,
        'name' => 'Ибадулаев Павел',
        'logo' => '/images/applic/20180503073112204100.jpg',
        'isonline' => 1,
        'loc_cnt' => 5,
        'work_state' => 1,
        'loc_last' => 'АТБ1',
        'date' => '06.02.2018'
    ),
    15 => array(
        'id' => 15,
        'name' => 'Бондаренко Наталья',
        'logo' => '/images/applic/20180428142455264100.jpg',
        'isonline' => 0,
        'loc_cnt' => 5,
        'work_state' => 2,
        'loc_last' => 'АТБ1',
        'date' => '06.02.2018'
    ),
    22 => array(
        'id' => 22,
        'name' => 'Бондаренченко Раиса',
        'logo' => '/images/applic/20180428142455264100.jpg',
        'isonline' => 0,
        'loc_cnt' => 3,
        'work_state' => 2,
        'loc_last' => 'АТБ1',
        'date' => '06.02.2018'
    ),
);
$viData['states'] = array(
  1 => 'начал',
  2 => 'просрочил'
);
*/ ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"
      integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
      crossorigin=""/>

<!-- Make sure you put this AFTER Leaflet's CSS -->
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"
        integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
        crossorigin=""></script>

<style>
    #map { height: 400px; }
</style>

<div id="map"></div>
<div id='actions'><a href='#'>Find me!</a></div>



<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>


<div class="filter__veil"></div>
<div class="row project">
    <div class="col-xs-12">
        <? require __DIR__ . '/project-nav.php'; ?>
    </div>
</div>
<?
//
?>
<div class="project__module">
    <?php if (!isset($idus)): ?>
        <form action="" class="project__geo-filter" id="filter-form">
            <div class="geo__header-city">
                <label>Город</label>
                <div class="city-filter">
                    <span class="city-filter__select">Все</span>
                    <ul class="city-list">
                        <li data-id="0">Все</li>
                        <? foreach ($viData['filter']['cities'] as $id => $arCity)
                            echo '<li data-id="' . $id . '">' . $arCity . '</li>';
                        ?>
                    </ul>
                    <input type="hidden" name="city" class="city-input" value="0">
                </div>
            </div>

            <div class="geo__header-date">
                <div class="calendar-filter">
                    <label>Дата с</label>
                    <span><?= $viData['filter']['bdate-short'] ?></span>
                    <div class="calendar" data-type="bdate">
                        <table>
                            <thead>
                            <tr>
                                <td class="mleft">‹
                                <td colspan="5" class="mname">
                                <td class="mright">›
                            </tr>
                            <tr>
                                <td>Пн
                                <td>Вт
                                <td>Ср
                                <td>Чт
                                <td>Пт
                                <td>Сб
                                <td>Вс
                            </tr>
                            <tbody></tbody>
                        </table>
                    </div>
                    <input type="hidden" name="bdate" value="<?= $viData['filter']['bdate'] ?>">
                </div>
                <div class="calendar-filter">
                    <label>По</label>
                    <span><?= $viData['filter']['edate-short'] ?></span>
                    <div class="calendar" data-type="edate">
                        <table>
                            <thead>
                            <tr>
                                <td class="mleft">‹
                                <td colspan="5" class="mname">
                                <td class="mright">›
                            </tr>
                            <tr>
                                <td>Пн
                                <td>Вт
                                <td>Ср
                                <td>Чт
                                <td>Пт
                                <td>Сб
                                <td>Вс
                            </tr>
                            <tbody></tbody>
                        </table>
                    </div>
                    <input type="hidden" name="edate" value="<?= $viData['filter']['edate'] ?>">
                </div>
            </div>
            <input type="hidden" name="project" value="<?= $project ?>" class="project-inp">
            <input type="hidden" name="filter" value="1">
        </form>
        <?
        //
        ?>

        <div class="project__geo-list" id="geo-list">
            <? require __DIR__ . '/project-geo-ajax.php'; // СПИСОК ?>
        </div>
    <?php else: ?>
        <?
            $arUser = $viData['users'][$idus];
        ?>
        <div class="geo__item-cart">
            <div class="geo-item__cart-data">
                <img src="<?= $arUser['src'] ?>">
                <div class="geo-item__cart-info">
                   <?/* <form action="" class="geo-item__cart-filter" id="filter-form">
                        <div class="geo__header-date user__header-date">
                            <div class="calendar-filter">
                                <label>Дата с</label>
                                <span><?= $viData['filter']['bdate-short'] ?></span>
                                <div class="calendar" data-type="bdate">
                                    <table>
                                        <thead>
                                        <tr>
                                            <td class="mleft">‹
                                            <td colspan="5" class="mname">
                                            <td class="mright">›
                                        </tr>
                                        <tr>
                                            <td>Пн
                                            <td>Вт
                                            <td>Ср
                                            <td>Чт
                                            <td>Пт
                                            <td>Сб
                                            <td>Вс
                                        </tr>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="bdate" value="<?= $viData['filter']['bdate'] ?>">
                            </div>
                            <div class="calendar-filter">
                                <label>По</label>
                                <span><?= $viData['filter']['edate-short'] ?></span>
                                <div class="calendar" data-type="edate">
                                    <table>
                                        <thead>
                                        <tr>
                                            <td class="mleft">‹
                                            <td colspan="5" class="mname">
                                            <td class="mright">›
                                        </tr>
                                        <tr>
                                            <td>Пн
                                            <td>Вт
                                            <td>Ср
                                            <td>Чт
                                            <td>Пт
                                            <td>Сб
                                            <td>Вс
                                        </tr>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="edate" value="<?= $viData['filter']['edate'] ?>">
                            </div>
                        </div>
                        <input type="hidden" name="project" value="<?= $project ?>" class="project-inp">
                    </form>*/?>


                    <div class="geo-item__cart-bl1">
                        <div class="geo-item__cart-name"><?= $arUser['name'] ?></div>
                        <div class="geo-item__cart-border">
                            <div>

                                <? if ($arUser['is_online'] != 0): ?>
                                    <span class="geo__green">&#9679 активен</span>
                                <? else: ?>
                                    <span class="geo__red">&#9679 неактивен</span>
                                <? endif; ?>
                            </div>
                            <div>Дата: <?=date('d.m.Y',$unixTime)?></div>
                        </div>
                    </div>

                    <div class="geo-item__cart-bl2">
                        <div>
                            <div>
                                <span>Старт работ: </span>
                                <span class="geo__green">начал в 9:30</span>
                                <span> / </span>
                                <span class="geo__red">опоздание на 20 мин.</span>
                            </div>
                            <div>
                                <span>Последнее место: АТБ1 </span>
                                <b></b>
                            </div>
                        </div>
                        <div class="geo-item__cart-cur">
                            <div><span>Сейчас в: АТБ1 </span><b></b></div>
                            <a href="#" class="geo-item__route">Показать маршрут передвижения</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="user-data">

                <? foreach ($viData['items'][$unixTime] as $keyUnix => $valueUnix): ?>
                    <? if (isset($valueUnix['users'][$idus]) && !empty($valueUnix['users'][$idus])): ?>
                        <h2 class="geo__item-title"><?= $valueUnix['city'] ?> <span><?= $valueUnix['date'] ?></span></h2>
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

                            <? foreach ($valueUnix['users'][$idus] as $keyItem => $valueItem): ?>
                            <tr>
                                <td>
                                    <div class="geo__table-cell"><?=$viData['points'][$valueItem]['name'];?></div>
                                </td>
                                <td>
                                    <div class="geo__table-cell geo__table-loc">
                                        <span><?=$viData['points'][$valueItem]['adres'];?></span>
                                        <b class="js-g-hashint" title="Посмотреть на карте"></b>
                                    </div>
                                </td>
                                <td>
                                    <div class="geo__table-cell">07.02.2018 14:00</div>
                                </td>
                                <td>
                                    <div class="geo__table-cell">07.02.2018 с 9:00 до 18:00</div>
                                </td>
                                <td>
                                    <div class="geo__table-cell">12</div>
                                </td>
                            </tr>
                            <? endforeach; ?>

                            </tbody>
                        </table>
                    <? endif; ?>
                <? endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>


<script>
    var map = L.map('map').setView([51.505, -0.09], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: ''
    }).addTo(map);

    function locate() {
        map.locate({setView: true, maxZoom: 16});
    }

    function onLocationFound(e) {
        var current_position;
        map.removeLayer(current_position);
        console.log(current_position);
    }

    $('#actions a').click(function(){
        locate();
        map.on('locationfound', onLocationFound);
    });


</script>