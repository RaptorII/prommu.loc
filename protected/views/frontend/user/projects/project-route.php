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
            <div class="route__item">
                <h2 class="route__item-title">Харьков</h2>

                <div class="route__item-box">
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
                                    <a class="route__change-id" href="#">изменить</a>
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
                                    <a class="route__change-id" href="#">изменить</a>
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
                                    <a class="route__change-id" href="#">изменить</a>
                                </div>
                            </td>
                            <td>
                                <div class="route__table-cell border text-center">14.02.2018</div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="routes__map">
                        <div class="routes__map-city">Харьков</div>
                        <div class="routes__map-map">

                            <script>
                                window.scribblemaps = {
                                    settings: {baseAPI: "google", key: 'AIzaSyAOevBkK_oALP0mD9aG3g4RyhUePQHl6SU'}
                                };
                                var loadmap = function () {
                                    var sm = new scribblemaps.ScribbleMap(
                                        document.getElementById('ScribbleMap'),
                                        { controlMode: { 'mapType': scribblemaps.ControlModes.SMALL } }
                                    );
                                    console.log(sm);
                                    sm.ui.setAvailableTools([]);
                                    sm.ui.setMapInfoIcons([]);
                                    sm.ui.styleControl(scribblemaps.ControlType.SEARCH,{display:"none"});
                                    sm.ui.styleControl(scribblemaps.ControlType.UNDO,{display:"none"});
                                    sm.ui.styleControl(scribblemaps.ControlType.LINE_COLOR,{display:"none"});
                                    sm.ui.styleControl(scribblemaps.ControlType.FILL_COLOR,{display:"none"});
                                    sm.ui.styleControl(scribblemaps.ControlType.LINE_SETTINGS,{display:"none"});
                                    sm.ui.styleControl(scribblemaps.ControlType.UNDO_CONTROL,{display:"none"});
                                   
                                    setMapTypeControlMode
                                    /*sm.ui.setMapTypes([
                                        {
                                            label: "Hybrid",
                                            id: "Hybrid"
                                        },
                                        {
                                            label: "Road",
                                            id: "Road"
                                        }
                                    ]);*/
                                    sm.ui.setTheme("dark");
                                }
/*

sm.ui.setTheme("light");

sm.ui.styleControl(scribblemaps.ControlType.TOOLBOX, { width: "65px" });
sm.ui.styleControl(scribblemaps.ControlType.SEARCH, { width: "120px", left: "75px", top: "10px" });
sm.ui.styleControl(scribblemaps.ControlType.LINE_COLOR, { left: "10px", top: "167px" });
sm.ui.styleControl(scribblemaps.ControlType.FILL_COLOR, { left: "10px", top: "199px" });
sm.ui.styleControl(scribblemaps.ControlType.LINE_SETTINGS, { left: "75px", top: "42px" });

sm.ui.setAvailableTools(["edit", "drag", "eraser",
                         "fill", "scribble", "line",
                         "polygon", "circle",
                         "flightLine", "rectangle"]);

sm.ui.setTool("drag");



/*
setMapInfoIcons
:
function (iconArray)
setMapTypeControlMode
:
function (modeType)
setMapTypes
:
function (mapTypeArray)
setMarkerLibrary
:
function (markerLibrary, keepDefaults)
setMenuIcons
:
function (iconArray)
setModalElement
:
function (domElement)
setSearchAppendString
:
function (appendQueryString)
setSearchBounds
:
function (SMBounds)
setSearchControlMode
:
function (modeType)
setSearchRegion
:
function (regionTLD)
setStyle
:
function (styleOject)
setTheme
:
function (themeType, transition)
setTool
:
function (toolId)

                                }*/
                            </script>
                            <script src="//scribblemaps.com/api/js/?callback=loadmap" async></script>  
                            <div id="ScribbleMap" style="width: 1140px; height: 474px"></div>
                            <?//<img src="/theme/pic/projects/temp-map-2.jpg"> ?>
                        </div>
                    </div>
                    <div class="routes__btns">
                        <a href="#content_top" class="route__watch-btn route__button-change">ИЗМЕНИТЬ</a>
                        <span class="route__watch-btn route__button-map">СМОТРЕТЬ МАРШРУТ</span>
                    </div>
                </div>

            </div>

            <div class="route__item">
                <h2 class="route__item-title">Москва</h2>

                <div class="route__item-box">
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
                                    <a class="route__change-id" href="#">изменить</a>
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
                                    <a class="route__change-id" href="#">изменить</a>
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
                                    <a class="route__change-id" href="#">изменить</a>
                                </div>
                            </td>
                            <td>
                                <div class="route__table-cell border text-center">14.02.2018</div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="routes__map">
                        <div class="routes__map-city">Москва</div>
                        <div class="routes__map-map">
                            <img src="/theme/pic/projects/temp-map-2.jpg">
                        </div>
                    </div>
                    <div class="routes__btns">
                        <a href="#content_top" class="route__watch-btn route__button-change">ИЗМЕНИТЬ</a>
                        <span class="route__watch-btn route__button-map">СМОТРЕТЬ МАРШРУТ</span>
                    </div>
                </div>


                <div class="route__item-box">
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
                                    <a class="route__change-id" href="#">изменить</a>
                                </div>
                            </td>
                            <td>
                                <div class="route__table-cell border text-center">15.02.2018</div>
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
                                    <a class="route__change-id" href="#">изменить</a>
                                </div>
                            </td>
                            <td>
                                <div class="route__table-cell border text-center">15.02.2018</div>
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
                                    <a class="route__change-id" href="#">изменить</a>
                                </div>
                            </td>
                            <td>
                                <div class="route__table-cell border text-center">15.02.2018</div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <div class="routes__map">
                        <div class="routes__map-city">Москва</div>
                        <div class="routes__map-map">
                            <img src="/theme/pic/projects/temp-map-2.jpg">
                        </div>
                    </div>
                    <div class="routes__btns">
                        <a href="#content_top" class="route__watch-btn route__button-change">ИЗМЕНИТЬ</a>
                        <span class="route__watch-btn route__button-map">СМОТРЕТЬ МАРШРУТ</span>
                    </div>
                </div>

            </div>
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
