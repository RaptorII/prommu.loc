<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-route.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-route.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile('https://code.jquery.com/ui/1.12.1/jquery-ui.js', CClientScript::POS_END);
?>


<?
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
                    'id' => '0'
                ],
                1 => [
                    'title' => 'метро 1',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'метро 2',
                    'id' => '2'
                ]

            ],
            'DATA_DEFAULT' => '0'
        ],
    ]
];
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



    <div class="routes">
        <div class="route__item">
            <h2 class="route__item-title">Харьков</h2>
            <table class="route__table">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Название ТТ</th>
                    <th>Адрес ТТ</th>
                    <th>Статус посещения</th>
                    <th>Дата</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td rowspan="3">
                        <div class="route__table-cell route__table-user">
                            <img src="/images/applic/20180503073112204100.jpg">
                            <span>Дмитриев<br/>Николай</span>
                        </div>
                    </td>
                    <td>
                        <div class="route__table-cell border">АТБ1</div>
                    </td>
                    <td>
                        <div class="route__table-cell border route__table-index">
                            <span>ул. Пирогова 23</span>
                            <b class="js-g-hashint" title="Посмотреть на карте"></b>
                        </div>
                    </td>
                    <td>
                        <div class="route__table-cell border route__table-status">
                            <span>2</span>
                            <a href="#">изменить</a>
                        </div>
                    </td>
                    <td>
                        <div class="route__table-cell border text-center">14.02.2018</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="route__table-cell border">ВАРУС</div>
                    </td>
                    <td>
                        <div class="route__table-cell border route__table-index">
                            <span>пр. Кирова 18</span>
                            <b class="js-g-hashint" title="Посмотреть на карте"></b>
                        </div>
                    </td>
                    <td>
                        <div class="route__table-cell border route__table-status">
                            <span>1</span>
                            <a href="#">изменить</a>
                        </div>
                    </td>
                    <td>
                        <div class="route__table-cell border text-center">14.02.2018</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="route__table-cell border">СЕЛЬПО</div>
                    </td>
                    <td>
                        <div class="route__table-cell border route__table-index">
                            <span>ул. Строителей 4</span>
                            <b class="js-g-hashint" title="Посмотреть на карте"></b>
                        </div>
                    </td>
                    <td>
                        <div class="route__table-cell border route__table-status">
                            <span>3</span>
                            <a href="#">изменить</a>
                        </div>
                    </td>
                    <td>
                        <div class="route__table-cell border text-center">14.02.2018</div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="routes__map">
        <div class="routes__map-city">Харьков</div>
        <div class="routes__map-map">
            <img src="/theme/pic/projects/temp-map-2.jpg">
        </div>
    </div>
    <div class="routes__btns">
        <a href="#" class="route__watch-btn">ИЗМЕНИТЬ</a>
        <a href="#" class="route__watch-btn">СМОТРЕТЬ МАРШРУТ</a>
    </div>
</div>




<div class="project__route-changer">
    <div class="project__changer-content">
        <table class="route__table route__table-sort">
            <thead>
            <tr>
                <th>№</th>
                <th>Название ТТ</th>
                <th>Адрес ТТ</th>
                <th>Статус посещения</th>
                <th>Дата</th>
            </tr>
            </thead>
            <tbody id="sortable">
            <tr class="ui-state-default">
                <td>
                    1
                </td>
                <td>
                    <div class="route__table-cell border">АТБ1</div>
                </td>
                <td>
                    <div class="route__table-cell border route__table-index">
                        <span>ул. Пирогова 23</span>
                        <b class="js-g-hashint" title="Посмотреть на карте"></b>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border route__table-status">
                        <span>2</span>
                        <a href="#">изменить</a>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border text-center">14.02.2018</div>
                </td>
            </tr>
            <tr class="ui-state-default">
                <td>
                    2
                </td>
                <td>
                    <div class="route__table-cell border">ВАРУС</div>
                </td>
                <td>
                    <div class="route__table-cell border route__table-index">
                        <span>пр. Кирова 18</span>
                        <b class="js-g-hashint" title="Посмотреть на карте"></b>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border route__table-status">
                        <span>1</span>
                        <a href="#">изменить</a>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border text-center">14.02.2018</div>
                </td>
            </tr>
            <tr class="ui-state-default">
                <td>
                    3
                </td>
                <td>
                    <div class="route__table-cell border">СЕЛЬПО</div>
                </td>
                <td>
                    <div class="route__table-cell border route__table-index">
                        <span>ул. Строителей 4</span>
                        <b class="js-g-hashint" title="Посмотреть на карте"></b>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border route__table-status">
                        <span>3</span>
                        <a href="#">изменить</a>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border text-center">14.02.2018</div>
                </td>
            </tr>
            </tbody>
        </table>
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
</div>
