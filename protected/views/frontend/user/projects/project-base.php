<?php
$bUrl = Yii::app()->baseUrl . '/theme/';
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item-base.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/additional.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/item-base.js', CClientScript::POS_END);

Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-filter.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($bUrl.'js/projects/project-convert-vacancy.js', CClientScript::POS_END);


/***********FANCYBOX************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'js/dist/fancybox/jquery.fancybox.css');
/***********FANCYBOX************/
/***********MAP************/
Yii::app()->getClientScript()->registerScriptFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyC9M8BgorAu7Sn226LNP2rteTF5gO7KjLc');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/route-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-map.css');
/***********MAP************/

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
            'INPUT_NAME' => 'post',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
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
            'NAME' => 'Статус персонала',
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
            'NAME' => 'Статус ТТ',
            'TYPE' => 'select',
            'INPUT_NAME' => 'haspoint',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Выбран персонал',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Без персонала',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '0'
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
foreach ($viData['filter']['cities'] as $k => $c)
    $arFilterData['FILTER_SETTINGS'][0]['DATA'][$k] = ['title' => $c, 'id' => $k];
foreach ($viData['filter']['tt_name'] as $n)
    $arFilterData['FILTER_SETTINGS'][1]['DATA'][] = ['title' => $n, 'id' => $n];
foreach ($viData['filter']['tt_index'] as $i)
    $arFilterData['FILTER_SETTINGS'][2]['DATA'][] = ['title' => $i, 'id' => $i];
foreach ($viData['filter']['metros'] as $id => $metro)
    $arFilterData['FILTER_SETTINGS'][6]['DATA'][$id] = ['title' => $metro['metro'], 'id' => $metro['id'], 'DATA_VALUE_PARENT_ID' => $metro['id_city']];
foreach ($viData['filter']['posts'] as $k => $p)
    $arFilterData['FILTER_SETTINGS'][5]['DATA'][$k] = ['title' => $p, 'id' => $k];


/*echo "<pre>";
print_r($viData); 
echo "</pre>";
*/

?>

<div class="row project">
    <div class="col-xs-12">
        <? require 'project-nav.php'; // Меню вкладок ?>
        <div id="content">
            <div class="project__module">
                <div class="filter__veil"></div>
                <div class="project__header">
                    <? require 'filter.php'; // ФИЛЬТР ?>
                </div>

                <div class="notification">
                    * При поиске персонала, пустые ТТ отображаться не будут!
                </div>
                <h1 class="project__title">ПРОЕКТ: <span><?= $viData['project']['name'] ?></span></h1>
                <? if(empty($viData['project']['vacancy'])): ?>
                    <div class="projects__to-vac-btn prmu-btn" data-id="<?=$project?>"><span>Перевести в вакансию</span></div> 
                <? endif; ?>

                <div id="ajax-content">
                    <? require 'project-base-ajax.php'; // СПИСОК ?>
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

<div class="tasks__popup">
    <div class="tasks__popup-close">
        <span>X</span>
    </div>
    <div class="tasks__popup-content">
        <div class="popup__content-user">
            <img class="popup__content-logo" src="/images/applic/20180819180030833100.jpg">

            <div class="popup__user">
                <div class="popup__user-name">
                    ...
                </div>
                <div class="popup__user-secondname">
                    ...
                </div>

                <div class="popup__user-status">
                    <span class="geo__red">● неактивен</span> /
                    <span class="geo__green">● активен</span>
                </div>
            </div>
        </div>
        <div class="popup__tasks">
            <table class="popup__table">
                <caption>Задания</caption>
                <thead>
                <tr>
                    <td>Дата</td>
                    <td>Название</td>
                    <td>Описание</td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        12.01.2018
                    </td>
                    <td>
                        Покупка курицы
                    </td>
                    <td>
                        Необходимо купить курицу в АТБ
                    </td>
                </tr>
                <tr>
                    <td>
                        13.01.2018
                    </td>
                    <td>
                        Готовка курицы
                    </td>
                    <td>
                        Необходимо приготовить курицу, купленную в АТБ
                    </td>
                </tr>
                <tr>
                    <td>
                        14.01.2018
                    </td>
                    <td>
                        Оформление стола
                    </td>
                    <td>
                        Необходимо подать гостям приготовленную курицу
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
    <div class="popup__control">
        <a href="/user/projects/<?=$project?>/tasks">Редактировать задания</a>
    </div>
</div>