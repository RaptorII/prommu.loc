<?php
$bUrl = Yii::app()->baseUrl;
$idus = Yii::app()->getRequest()->getParam('user_id');
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
        </form>
        <?
        //
        ?>

        <div class="project__geo-list" id="geo-list">
            <? foreach ($viData['items'] as $keyTimeStamp => $valueTimeStamp): ?>
                <? foreach ($valueTimeStamp as $keyCity => $valueCity): ?>
                    <div class="project__geo-item">
                        <h2 class="geo__item-title"><?= $valueCity['city'] ?> <span><?= $valueCity['date'] ?></span>
                        </h2>
                        <table class="geo__item-table">
                            <thead>
                            <tr>
                                <th>Сотрудник</th>
                                <th>Статус</th>
                                <th>Кол-во ТТ</th>
                                <th>Старт работы</th>
                                <th>Последнее место</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <? foreach ($valueCity['users'] as $keyUser => $valueUser): ?>
                                <tr>
                                    <td>
                                        <div class="geo__table-cell geo__table-user">

                                            <img src="<?= $viData['users'][$keyUser]['src'] ?>">
                                            <span><?= $viData['users'][$keyUser]['name'] ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="geo__table-cell">
                                            <? if ($viData['users'][$keyUser]['status'] != 0): ?>
                                                <span class="geo__green">&#9679 активен</span>
                                            <? else: ?>
                                                <span class="geo__red">&#9679 неактивен</span>
                                            <? endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="geo__table-cell">
                                            <?= count($valueUser) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="geo__table-cell">
                                            <span class="geo__green">начал</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="geo__table-cell">
                                            <div class="geo__table-loc">
                                                <span>АТБ1</span>
                                                <b class="js-g-hashint" title="Посмотреть на карте"></b>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="geo__table-cell">
                                            <a href="<? echo MainConfig::$PAGE_PROJECT_LIST . '/' . $project . '/geo/' . $keyUser ?>">подробнее</a>
                                        </div>
                                    </td>
                                </tr>
                            <? endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                <? endforeach; ?>
            <? endforeach; ?>
        </div>
    <?php else: ?>
        <div class="geo__item-cart">
            <div class="geo-item__cart-data">
                <img src="/images/applic/20180503073112204100.jpg">
                <div class="geo-item__cart-info">
                    <form action="" class="geo-item__cart-filter" id="filter-form">
                        <div class="geo__header-date user__header-date">
                            <div class="calendar-filter">
                                <label>Дата с</label>
                                <span><?= $viData['dates']['bdate'] ?></span>
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
                                <input type="hidden" name="bdate" value="<?= $viData['dates']['bdate-full'] ?>">
                            </div>
                            <div class="calendar-filter">
                                <label>По</label>
                                <span><?= $viData['dates']['edate'] ?></span>
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
                                <input type="hidden" name="edate" value="<?= $viData['dates']['edate-full'] ?>">
                            </div>
                        </div>
                        <input type="hidden" name="project" value="<?= $project ?>" class="project-inp">
                        <input type="hidden" name="project" value="<?= $project ?>" class="project-inp">
                    </form>


                    <div class="geo-item__cart-bl1">
                        <div class="geo-item__cart-name">Ибадулаев Павел</div>
                        <div class="geo-item__cart-border">
                            <div>
                                <span class="geo__green">&#9679 активен</span> / <span
                                        class="geo__red">&#9679 неактивен</span>
                            </div>
                            <div>Дата: 06.02.2018</div>
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
                            <a href="#" class="geo-item__route">Показаь маршрут передвижения</a>
                        </div>
                    </div>
                </div>
            </div>
            <div id="user-data">
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
                            <div class="geo__table-cell">АТБ1</div>
                        </td>
                        <td>
                            <div class="geo__table-cell geo__table-loc">
                                <span>ул. Пирогова 23</span>
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
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>