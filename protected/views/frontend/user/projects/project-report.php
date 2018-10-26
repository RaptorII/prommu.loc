<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-report.css');
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/

Yii::app()->getClientScript()->registerScriptFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-map.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/js/dist/fancybox/jquery.fancybox.css');

$arFilterData = [
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => ['filter'=>1],
    'FILTER_SETTINGS' => [
        0 => [
            'NAME' => 'Имя',
            'TYPE' => 'text',
            'INPUT_NAME' => 'fname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        1 => [
            'NAME' => 'Фамилия',
            'TYPE' => 'text',
            'INPUT_NAME' => 'lname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        2 => [
            'NAME' => 'Город',
            'TYPE' => 'select',
            'INPUT_NAME' => 'city',
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
        6 => [
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
        7 => [
            'TYPE' => 'block',
        ],
        8 => [
            'TYPE' => 'block',
        ],
        9 => [
            'NAME' => 'Тип события',
            'TYPE' => 'select',
            'INPUT_NAME' => 'type',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Отмечен на точке',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Не отмечен на точке',
                    'id' => '2'
                ],
                3 => [
                    'title' => 'Опоздал',
                    'id' => '3'
                ],
                4 => [
                    'title' => 'Не опоздал',
                    'id' => '4'
                ]

            ],
            'DATA_DEFAULT' => '0'
        ]
    ]
];
foreach ($viData['filter']['cities'] as $key => $value)
    $arFilterData['FILTER_SETTINGS'][2]['DATA'][$key] = ['title' => $value['city'], 'id' => $key];
foreach ($viData['filter']['tt_name'] as $n)
    $arFilterData['FILTER_SETTINGS'][5]['DATA'][] = ['title' => $n, 'id' => $n];
foreach ($viData['filter']['tt_index'] as $i)
    $arFilterData['FILTER_SETTINGS'][6]['DATA'][] = ['title' => $i, 'id' => $i];
?>
<div class="row project">
    <div class="col-xs-12">
        <? require __DIR__ . '/project-nav.php'; ?>
    </div>
</div>


<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData['result']); ?>
</pre>

<div class="project__module">

    <div class="filter__veil"></div>

    <div class="project__header">
        <? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>
    </div>

    <div class="project__header-xls project__xls">
        <? /*<a href="/uploads/promo_import.xls" download>Скачать текущий персонал</a>*/ ?>
        <a href="/uploads/promo_import.xls" download>
            Выгрузить отчет по выбранным данным в xls
        </a>
    </div>


    <div class="report__content" id="ajax-content">
        <? require __DIR__ . '/project-report-ajax.php'; // СПИСОК ?>
    </div>
</div>
