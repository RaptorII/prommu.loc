<?php
$pLink = MainConfig::$PAGE_PROJECT_LIST . '/' . $project;
$bUrl = Yii::app()->baseUrl;

Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/phone-codes/projects.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/get-personal.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/phone-codes/style.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-staff.css');

/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/

$arFilterData = [
    'STYLES' => 'project__header-filter',
    'HIDE' => true,
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => ['filter' => 1],
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
            'DATA_DEFAULT' => '0',
            'CONDITION' => [
                'BLOCKED' => 'true',
                'PARENT_ID' => '4',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '0',
                    1 => '1'
                ]
            ]
        ],
        3 => [
            'NAME' => 'Фамилия',
            'TYPE' => 'text',
            'INPUT_NAME' => 'lname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        4 => [
            'NAME' => 'Привязка к адресу',
            'TYPE' => 'select',
            'INPUT_NAME' => 'haspoint',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Привязан',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Не привязан',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '0'
        ],
        5 => [
            'NAME' => 'Название ТТ',
            'TYPE' => 'select',
            'INPUT_NAME' => 'tt_name',
            'DATA' => [
                0 => [
                    'title' => 'Название 1',
                    'id' => '1'
                ],
                1 => [
                    'title' => 'Название 2',
                    'id' => '0'
                ],
                2 => [
                    'title' => 'Все',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '2',
            'CONDITION' => [
                'BLOCKED' => 'true',
                'PARENT_ID' => '2',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '0',
                    1 => '1'
                ]
            ]
        ],
        6 => [
            'TYPE' => 'block',
        ],
        7 => [
            'TYPE' => 'block',
        ],
        8 => [
            'NAME' => 'Адрес ТТ',
            'TYPE' => 'select',
            'INPUT_NAME' => 'tt_location',
            'DATA' => [
                0 => [
                    'title' => 'Адрес ТТ 1',
                    'id' => '1'
                ],
                1 => [
                    'title' => 'Адрес ТТ 2',
                    'id' => '0'
                ],
                2 => [
                    'title' => 'Адрес ТТ 3',
                    'id' => '2'
                ],
                3 => [
                    'title' => 'Все',
                    'id' => '3'
                ]
            ],
            'DATA_DEFAULT' => '2',
            'CONDITION' => [
                'BLOCKED' => 'true',
                'PARENT_ID' => '4',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '0',
                    1 => '1'
                ]
            ]
        ],
        9 => [
            'TYPE' => 'block',
        ],
        10 => [
            'TYPE' => 'block',
        ],
        11 => [
            'NAME' => 'Метро',
            'TYPE' => 'select',
            'INPUT_NAME' => 'metro',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ]
            ]
        ]
    ],
];
foreach ($viData['filter']['cities'] as $id => $v)
    $arFilterData['FILTER_SETTINGS'][2]['DATA'][$id] = ['title' => $v['city'], 'id' => $id];
foreach ($viData['filter']['metros'] as $id => $v)
    $arFilterData['FILTER_SETTINGS'][11]['DATA'][$id] = ['title' => $v['metro'], 'id' => $id];
?>
<div class="row project">
    <div class="col-xs-12">
        <? require __DIR__ . '/project-nav.php'; // Меню вкладок ?>
    </div>
</div>

<div class="filter__veil"></div>

<form action="" method="POST" id="update-person">
    <div id="main" class="project__module">


        <div class="prommu__universal-filter__buttoncontainer">
            <span class="prommu__universal-filter__button">ФИЛЬТР ДЛЯ ПЕРСОНАЛА</span>
        </div>
        <div class="project__header">
            <? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>
        </div>


        <div class="project__control-panel">

            <div class="project__header-xlscontainer">
                <div class="project__header-xls project__xls">

                    <? /*<a href="/uploads/promo_import.xls" download>Скачать текущий персонал</a>*/ ?>
                    <a href="/uploads/promo_import.xls" download>
                        Выгрузить добавленный персонал
                    </a>

                    <? /*<a href="/user/uploadprojectxls?id=<?= $project ?>&type=users" class="project__header-addxls"
                       id="add-program">
                            Изменить текущий персонал
                    </a>*/ ?>
                    <a href="/user/uploadprojectxls?id=<?= $project ?>&type=users" class="project__header-addxls"
                        id="add-program">Загрузить изменения по добавленному персоналу</a>

                    <? /*<a class="xlscontainer-child" href="/uploads/promo_import.xls" download>
                        Скачать пример для добавления
                     </a>*/ ?>
                    <a class="xlscontainer-child" href="/uploads/promo_import.xls" download>
                        Выгрузить пример для добавления нового персонала
                    </a>

                    <? /*<a class="xlscontainer-child" href="/user/uploadprojectxls?id=<?= $project ?>&type=users"
                       class="project__header-addxls"
                       id="add-program">
                        Добавить новый персонал
                    </a>*/ ?>
                    <a class="xlscontainer-child" href="/user/uploadprojectxls?id=<?= $project ?>&type=users"
                       class="project__header-addxls"
                       id="add-program">
                        Загрузить новый персонал
                    </a>


                </div>
            </div>

            <div class="program__btns control__buttons">

                <span class="control__add-container">
                    <span id="control__add-all" class="control__add-btn">+ ДОБАВИТЬ ПЕРСОНАЛ</span>
                    <ul class="control__add-ul">
                        <li id="control__add-personal">добавить из базы</li>
                        <li id="control__new-personal">пригласить</li>
                    </ul>
                </span>

                <? /*<button type="submit" id="control__save-btn" class="program__save-btn">ПРИМЕНИТЬ</button>*/ ?>
            </div>
        </div>

        <h1 class="project__title personal__title">ПЕРСОНАЛ</h1>
        <div id="ajax-content">
            <?php require __DIR__ . '/project-staff-ajax.php'; ?>
        </div>
    </div>


    <? /****************************Скрытые блоки приглашения персонала****************************/ ?>
    <div id="addition" class="project__module"></div>

    <div id="invitation" class="project__module">
        <h2 class="project__title personal__title">ПРИГЛАСИТЬ В ПРОЕКТ </h2>
        <div class="project__body project__body-invite invitation-item" data-id="0">
            <span class="invitation-del">&#10006</span>
            <div>
                <input type="text" name="inv-name[0]" placeholder="Имя" class="invite-inp name">
            </div>
            <div>
                <input type="text" name="inv-sname[0]" placeholder="Фамилия" class="invite-inp sname">
            </div>
            <div>
                <input type="text" name="inv-phone[0]" placeholder="Телефон" class="invite-inp phone">
            </div>
            <div>
                <input type="text" name="inv-email[0]" placeholder="E-mail" class="invite-inp email">
            </div>
        </div>
        <div class="project__all-btns">
            <div class="project__invite-btns">
                <span class="project__btn-white" id="add-prsnl-btn" data-event="main">+ДОБАВИТЬ ЕЩЕ ПЕРСОНАЛ</span>
                <span class="save-btn" id="save-prsnl-btn" data-event="main">СОХРАНИТЬ</span>
                <span class="save-btn" id="save-prsnl-cancel">ОТМЕНИТЬ</span>
            </div>
        </div>
    </div>
    <input type="hidden" name="project" value="<?=$project?>">
    <input type="hidden" name="save-users" value="1">
</form>

<form enctype="multipart/form-data" action="" method="POST" id="base-form">
    <input type="hidden" name="project" class="project-inp" value="<?= $project ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="5242880"/>
    <input type="file" name="xls" id="add-xls-inp" class="hide">
    <input type="hidden" name="xls-users" value="1">
</form>
<div class="hidden" id="invitation-content">
    <div class="project__body project__body-invite invitation-item" data-id="">
        <span class="invitation-del">&#10006</span>
        <div>
            <input type="text" name="" placeholder="Имя" class="invite-inp name">
        </div>
        <div>
            <input type="text" name="" placeholder="Фамилия" class="invite-inp sname">
        </div>
        <div>
            <input type="text" name="" placeholder="Телефон" class="invite-inp phone">
        </div>
        <div>
            <input type="text" name="" placeholder="E-mail" class="invite-inp email">
        </div>
    </div>
</div>
