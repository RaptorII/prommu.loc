<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);

Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-report.css');

/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/

$arFilterData = [
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => [
        'SECTION_ID' => Yii::app()->request->getParam('section')
    ],
    'FILTER_SETTINGS' => [
        0 => [
            'NAME' => 'Статус',
            'TYPE' => 'select',
            'INPUT_NAME' => 'status',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Подтверждено',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Не подтверждено',
                    'id' => '2'
                ]

            ],
            'DATA_DEFAULT' => '0'
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

    <div class="filter__veil"></div>

    <div class="project__header">
        <? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>
    </div>

    <div class="report__content">

        <div class="report__person">
            <div class="report__person-main">
                <div class="report__person-image">
                    <img src="http://n1s1.starhit.ru/98/88/17/988817db6d167526849b082151b49a00/480x497_0_41ed55af84b672870b6db9d9401c9604@480x497_0xc0a8399a_17479801141492689970.jpeg">
                </div>
                <div class="report__person-name">
                    Джеки Чан
                </div>
            </div>

            <div class="report__person-please">
                <div class="report__person-city">Гонконг</div>
                <div class="report__person-date">05.09.2018</div>
            </div>
        </div>

        <table class="route__table report__table">
            <thead>
            <tr>
                <th>Название и адрес ТТ</th>
                <th>Дата</th>
                <th>План прибытия</th>
                <th>факт прибытия</th>
                <th>План убыл</th>
                <th>Факт убыл</th>
                <th>Пробыл</th>
                <th>Перемещение</th>
                <th>Задачи план</th>
                <th>Задачи факт</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div class="route__table-cell border">
                        <span>Ул. Чаджуня 23</span>
                        <span class="report__info-main">АТБ1</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        15.02.2018
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 10:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 10:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 12:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 12:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        55 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        30 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        3
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">2</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="route__table-cell border">
                        <span>Ул. Фуньсуня 23</span>
                        <span class="report__info-main">АТБ1</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        15.02.2018
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 10:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 10:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 12:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 12:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        55 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        30 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        3
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">2</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="route__table-cell border">
                        <span>Ул. Зеросуня 23</span>
                        <span class="report__info-main">АТБ1</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        15.02.2018
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 10:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 10:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 12:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 12:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        55 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        30 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        3
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">2</span>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>

        <div class="report__road-container">
            <div class="report__road-see">Посмотреть маршрут на карте <b class="js-g-hashint tooltipstered"></b></div>
        </div>
    </div>
</div>
