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
        <?/*
        echo "<pre>";
        print_r($viData);
        echo "</pre>";
        */?>

        <div class="geo__item-cart">
            <div class="geo-item__cart-data">
                <img src="<?= $viData['user']['src'] ?>">
                <div class="geo-item__cart-info">
                    <div class="geo-item__cart-bl1">
                        <div class="geo-item__cart-border geo-item__cart-name">
                            <?= $viData['user']['name'] ?>
                            <div>Дата: <?= $viData['date'] ?></div>
                        </div>
                    </div>
                    <div class="geo-item__cart-bl2">

                        <table class="geo__table-route">
                            <thead>
                            <tr>
                                <th>Торговая точка</th>
                                <th>Активность по ТТ</th>
                                <th>Старт работ</th>
                                <th>Маршрут</th>
                            </tr>
                            </thead>
                            <tbody>
                            <? foreach ($viData['points'] as $key => $value): ?>
                                <tr>
                                    <td>
                                        <div class="geo__table-cell table-cell-first">
                                            <?= $value['city']; ?>,
                                            <?= $value['index_full']; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="geo__table-cell">
                                            <? if (isset($viData['items'][$value['point']]['bfact']) && !$viData['items'][$value['point']]['is-missed']): ?>
                                                <span class="geo__green">&#9679 активен <?
                                                    echo($viData['items'][$value['point']]['time-isactive'] ? '(всего ' . $viData['items'][$value['point']]['time-isactive'] . ')' : '')
                                                    ?></span>
                                            <? else: ?>
                                                <span class="geo__red">&#9679 неактивен</span>
                                            <? endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="geo__table-cell">
                                            <? if (!empty($viData['items'][$value['point']]['bfact']) || !empty($viData['items'][$value['point']]['time-lateness'])): ?>
                                                <? if (!$viData['items'][$value['point']]['is-lateness']): ?>
                                                    <span class="geo__green">начал в <?= $viData['items'][$value['point']]['bfact'] ?></span>
                                                <? else: ?>
                                                    <span class="geo__red">опоздание на <?= $viData['items'][$value['point']]['time-lateness'] ?>
                                                        мин.</span>
                                                <? endif; ?>

                                            <?else:?>
                                                -
                                            <? endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="geo__table-cell geo__table-loc">

                                            <? if (isset($viData['items'][$value['point']]['bfact']) && !$viData['items'][$value['point']]['is-missed']): ?>
                                                <b
                                                        data-map-project="<?= $project ?>"
                                                        data-map-user="<?= $viData['id_user'] ?>"
                                                        data-map-point="<?= $value['point'] ?>"
                                                        data-map-date="<?= $viData['unix'] ?>"
                                                        class="js-g-hashint js-get-map" title="Посмотреть маршрут в рамках текущей ТТ"></b>
                                            <? else: ?>
                                                -
                                            <? endif; ?>


                                        </div>
                                    </td>
                                </tr>
                            <? endforeach; ?>
                            </tbody>
                        </table>
                        <div class="geo-item__cart-cur">
                            <? /*<div>
                <span>Сейчас в: <?=$viData['point']['name']?></span>
                <b
                  data-map-project="<?=$project?>"
                  data-map-user="<?=$viData['id_user']?>"
                  data-map-point="<?=$viData['id_point']?>"
                  data-map-date="<?=$viData['unix']?>"
                  class="js-g-hashint js-get-map" title="Посмотреть на карте"></b>
              </div>
              <?/*<a href="#" class="geo-item__route">Показать маршрут передвижения</a>*/ ?>
                        </div>
                    </div>
                </div>
            </div>
            <div id="user-data">

                <div class="geo__route-block">
                    <h2 class="geo__item-title">Маршрут передвижения</h2>

                    <div class="geo__map-container">
                        <h2 class="geo__item-error">Маршрут передвижения не найден!</h2>
                        <div class="geo__route-map" id="geo__route-<?= $viData['id_user']; ?>-<?= $viData['unix'] ?>"
                             data-map-project="<?= $project ?>"
                             data-map-user="<?= $viData['id_user'] ?>"
                             data-map-point="<?= $viData['id_point'] ?>"
                             data-map-date="<?= $viData['unix'] ?>">
                        </div>
                    </div>

                </div>


                <h2 class="geo__item-title">Локации</h2>
                <? if (count($viData['points']) > 0): ?>
                    <table class="geo__table-route">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Город</th>
                            <th>Адрес</th>
                            <th>Маршрут</th>
                            <th>План работ</th>
                            <th>Факт работ</th>
                            <th>Задачи по ТТ</th>
                        </tr>
                        </thead>
                        <tbody>

                        <? foreach ($viData['points'] as $key => $value): ?>
                            <tr>
                                <td>
                                    <div class="geo__table-cell table-cell-first"><?= $value['name']; ?></div>
                                </td>
                                <td>
                                    <div class="geo__table-cell"><?= $value['city']; ?></div>
                                </td>
                                <td>
                                    <div class="geo__table-cell geo__table-loc">
                                        <span><?= $value['index_full']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="geo__table-cell geo__table-loc">
                                        <? if (isset($viData['items'][$value['point']]['bfact']) && !$viData['items'][$value['point']]['is-missed']): ?>
                                            <b
                                                    data-map-project="<?= $project ?>"
                                                    data-map-user="<?= $viData['id_user'] ?>"
                                                    data-map-point="<?= $value['point'] ?>"
                                                    data-map-date="<?= $viData['unix'] ?>"
                                                    class="js-g-hashint js-get-map" title="Посмотреть маршрут в рамках текущей ТТ"></b>
                                        <? else: ?>
                                            -
                                        <? endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="geo__table-cell"><?= $viData['items'][$value['point']]['bplan'] ?></div>
                                </td>
                                <td>
                                    <div class="geo__table-cell">
                                        <? if ($viData['items'][$value['point']]['bfact']): ?>
                                            <?= $viData['items'][$value['point']]['bfact'] ?>
                                        <? else: ?>
                                            -
                                        <? endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="geo__table-cell"><?= count($viData['tasks'][$viData['unix']][$value['point']][$viData['id_user']]) ?></div>
                                </td>
                            </tr>
                        <? endforeach; ?>
                        </tbody>
                    </table>
                <? else: ?>
                    <h2 class="geo__item-error">Локаций не найдено!</h2>
                <? endif; ?>

            </div>

        </div>
        <?
        //
        ?>
    <?php elseif (sizeof($viData['items']) > 0): ?>
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
