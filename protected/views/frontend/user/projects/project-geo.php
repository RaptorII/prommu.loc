<?php

$bUrl = Yii::app()->baseUrl;
$idus = Yii::app()->getRequest()->getParam('user_id');
$unixTime = Yii::app()->getRequest()->getParam('unix');
$project = Yii::app()->getRequest()->getParam('id');
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-geo.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-geo.js', CClientScript::POS_END);

Yii::app()->getClientScript()->registerScriptFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-map.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.css');
/*?>

<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>
<?*/?>
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
    <?php if (isset($idus)): ?>
        <?
            $arUser = $viData['users'][$idus];
        ?>
        <div class="geo__item-cart">
            <div class="geo-item__cart-data">
                <img src="<?= $arUser['src'] ?>">
                <div class="geo-item__cart-info">
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
                            <?/*<div>
                                <span>Последнее место: АТБ1 </span>
                                <b></b>
                            </div>*/?>
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

                            <? foreach ($valueUnix['users'][$idus]['points'] as $keyItem => $valueItem): ?>
                            <tr>
                                <td>
                                    <div class="geo__table-cell"><?=$viData['points'][$valueItem]['name'];?></div>
                                </td>
                                <td>
                                    <div class="geo__table-cell geo__table-loc">
                                        <span><?=$viData['points'][$valueItem]['adres'];?></span>

                                        <b
                                            data-map-project="<?=$project?>"
                                            data-map-user="<?=$idus?>"
                                            data-map-point="<?=$viData['points'][$valueItem]['point']?>"
                                            data-map-date="<?=$keyUnix?>"
                                            class="js-g-hashint js-get-map" title="Посмотреть на карте"></b>
                                    </div>
                                </td>
                                <td>
                                    <div class="geo__table-cell">07.02.2018 <?=$viData['points'][$valueItem]['btime']?> - <?=$viData['points'][$valueItem]['etime']?></div>
                                </td>
                                <td>
                                    <div class="geo__table-cell">07.02.2018 с 9:00 до 18:00</div>
                                </td>
                                <td>
                                    <div class="geo__table-cell"><?=count($viData['tasks'][$unixTime][$valueItem][$idus])?></div>
                                </td>
                            </tr>
                            <? endforeach; ?>

                            </tbody>
                        </table>
                    <? endif; ?>
                <? endforeach; ?>
            </div>
        </div>
    <?php elseif(sizeof($viData['items'])>0): ?>
        <form action="" class="project__geo-filter" id="filter-form">
            <div class="geo__header-city">
                <label>Город</label>
                <div class="city-filter">
                    <span class="city-filter__select">Все</span>
                    <ul class="city-list">
                        <li data-id="0">Все</li>
                        <? foreach ($viData['filter']['cities'] as $id => $city)
                            echo '<li data-id="' . $id . '">' . $city['city'] . '</li>';
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
        <br><br><h2 class="center">Не найдено локаций с выбранным персоналом</h2>
    <?php endif; ?>
</div>