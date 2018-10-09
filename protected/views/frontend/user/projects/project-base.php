<?php
$bUrl = Yii::app()->baseUrl . '/theme/';
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item-base.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/additional.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/item-base.js', CClientScript::POS_END);

Yii::app()->getClientScript()->registerScriptFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/universal-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-map.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'js/dist/fancybox/jquery.fancybox.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-filter.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/universal-filter.js', CClientScript::POS_END);

$arFilterData = [
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
                ]

            ],
            'DATA_DEFAULT' => '0'
        ],
        1 => [
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
        2 => [
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
            'NAME' => 'Тип работы',
            'TYPE' => 'select',
            'INPUT_NAME' => 'work_type',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Пример1',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Пример2',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '0'
        ],
        6 => [
            'NAME' => 'Метро',
            'TYPE' => 'select',
            'INPUT_NAME' => 'metro',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0',
                    'DATA_VALUE_PARENT_ID' => 'ALL'
                ]
            ],
            'DATA_LI_VISIBLE' => '0',
            'DATA_DEFAULT' => '0',
            'CONDITION' => [
                'BLOCKED' => 'false',
                'PARENT_ID' => '0',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => []
            ]
        ],
        7 => [
            'NAME' => 'Имя',
            'TYPE' => 'text',
            'INPUT_NAME' => 'fname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        8 => [
            'NAME' => 'Фамилия',
            'TYPE' => 'text',
            'INPUT_NAME' => 'lname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        9 => [
            'NAME' => 'Статус Персонала',
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
                ],
                3 => [
                    'title' => 'Отказано',
                    'id' => '3'
                ]
            ],
            'DATA_DEFAULT' => '0'
        ],
        10 => [
            'TYPE' => 'block',
        ],
        11 => [
            'TYPE' => 'block',
        ],
        12 => [
            'NAME' => 'Задания',
            'TYPE' => 'select',
            'INPUT_NAME' => 'hastask',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Есть',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Нет',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '0'
        ],

    ]
];
/*foreach ($viData['filter']['projects'] as $v)
    $arFilterData['FILTER_SETTINGS'][0]['DATA'][$v['project']] = ['title' => $v['name'], 'id' => $v['project']];*/
foreach ($viData['filter']['cities'] as $k => $c)
    $arFilterData['FILTER_SETTINGS'][0]['DATA'][$k] = ['title' => $c, 'id' => $k];
foreach ($viData['filter']['tt_name'] as $n)
    $arFilterData['FILTER_SETTINGS'][1]['DATA'][] = ['title' => $n, 'id' => $n];
foreach ($viData['filter']['tt_index'] as $i)
    $arFilterData['FILTER_SETTINGS'][2]['DATA'][] = ['title' => $i, 'id' => $i];
foreach ($viData['filter']['metros'] as $id => $metro)
    $arFilterData['FILTER_SETTINGS'][6]['DATA'][$id] = ['title' => $metro['metro'], 'id' => $metro['id'], 'DATA_VALUE_PARENT_ID' => $metro['id_city']];
?>

<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>

<div class="row project">
    <div class="col-xs-12">
        <? require __DIR__ . '/project-nav.php'; // Меню вкладок ?>
        <div id="content">
            <div class="project__module">
                <div class="filter__veil"></div>
                <div class="project__header">
                    <? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>
                </div>
                <? /*<div class="project__xls">
					<a href="/user/uploadprojectxls?id=<?=$project?>&type=index" id="add-xls">Изменить адресную программу</a>
					<a href="/uploads/prommu_example.xls" download>Скачать пример для добавления</a>
				</div>*/ ?>
                <h1 class="project__title">ПРОЕКТ: <span><?= $viData['project']['name'] ?></span></h1>
                <div id="ajax-content">
                    <? require __DIR__ . '/project-base-ajax.php'; // СПИСОК ?>
                </div>
                <form enctype="multipart/form-data" action="" method="POST" id="base-form">
                    <input type="hidden" name="project" class="project-inp" value="<?= $project ?>">
                    <input type="hidden" name="MAX_FILE_SIZE" value="5242880"/>
                    <input type="file" name="xls" id="add-xls-inp" class="hide">
                    <input type="hidden" name="xls-index" value="1">
                </form>
            </div>
        </div>
    </div>
</div>