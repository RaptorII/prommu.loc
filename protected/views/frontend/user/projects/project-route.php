<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-route.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-route.js', CClientScript::POS_END);
?>

<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width: 450px; }
    #sortable li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 4em; text-align: center; }
</style>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
        $( "#sortable" ).sortable();
        $( "#sortable" ).disableSelection();
    } );
</script>



<ul id="sortable">
    <li class="ui-state-default">1</li>
    <li class="ui-state-default">2</li>
    <li class="ui-state-default">3</li>
    <li class="ui-state-default">4</li>
    <li class="ui-state-default">5</li>
    <li class="ui-state-default">6</li>
    <li class="ui-state-default">7</li>
    <li class="ui-state-default">8</li>
    <li class="ui-state-default">9</li>
    <li class="ui-state-default">10</li>
    <li class="ui-state-default">11</li>
    <li class="ui-state-default">12</li>
</ul>

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
            'NAME' => 'Дата с',
            'TYPE' => 'calendar',
            'INPUT_NAME' => 'bdate',
            'DATA' => [],
            'DATA_DEFAULT' => "21.11.2018",
            'DATA_SHORT' => "21.11.18"
        ],
        2 => [
            'NAME' => 'По',
            'TYPE' => 'calendar',
            'INPUT_NAME' => 'edate',
            'DATA' => [],
            'DATA_DEFAULT' => "27.11.2018",
            'DATA_SHORT' => "27.11.18"
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
