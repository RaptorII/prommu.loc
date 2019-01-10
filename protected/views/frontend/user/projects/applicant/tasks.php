<?

$userId = Share::$UserProfile->id;

Yii::app()->getClientScript()->registerCssFile('/theme/css/projects/app-tasks.css');
Yii::app()->getClientScript()->registerScriptFile('/theme/js/projects/app-tasks.js', CClientScript::POS_END);


/***********FANCYBOX************/
Yii::app()->getClientScript()->registerScriptFile('/theme/js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('/theme/js/dist/fancybox/jquery.fancybox.css');
/***********FANCYBOX************/
/***********MAP************/
Yii::app()->getClientScript()->registerScriptFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyC9M8BgorAu7Sn226LNP2rteTF5gO7KjLc');
Yii::app()->getClientScript()->registerScriptFile('/theme/js/projects/route-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('/theme/css/projects/universal-map.css');
/***********MAP************/
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile('/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/

$arFilterData = [
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => ['filter' => 1],
    'FILTER_SETTINGS' => [
        0 => [
            'NAME' => 'Название',
            'TYPE' => 'text',
            'INPUT_NAME' => 'title',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        /*1 => [
            'NAME' => 'Фамилия',
            'TYPE' => 'text',
            'INPUT_NAME' => 'lname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],*/
        1 => [
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
        2 => [
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
        6 => [
            'NAME' => 'Должность',
            'TYPE' => 'select',
            'INPUT_NAME' => 'tt_position',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ]
            ],
            'DATA_DEFAULT' => '0'
        ],
        7 => [
            'NAME' => 'Статус задачи',
            'TYPE' => 'select-multi',
            'INPUT_NAME' => 'type',
            'DATA' => [
                1 =>    'В работе',
                2 =>    'Отменена',
                3 =>    'Доработка',
                4 =>    'Готова',
                5 =>    'Ожидание',
            ],
        ],
    ]
];

$arStatus = [
    '0' => 'Ожидание',
    '1' => 'В работе',
    '3' => 'Готова',
    '4' => 'Отменена'
];

?>


<div class="cabinet">
    <div class="cabinet__header">
        <div class="project__info">
            <span><?= $viData['project']['name'] ?></span>
        </div>

        <a class="cabinet__link-target" href="#target">
        <div class="cabinet__timer">
            <div class="timer__box">
                <div class="timer__time">
                    <span id="t_hours" class="timer__hours">&nbsp;&nbsp;</span>
                    <span class="timer__sw">:</span>
                    <span id="t_minutes" class="timer__minutes">&nbsp;&nbsp;</span>
                </div>
                <div class="timer__control-top">
                    <div id="t_week" class="timer__control-text"></div>
                    <div class="timer__control-tasks">
                        Задач на сегодня:<span class="control__tasks"><?=$viData['tasks_cnt']?></span>
                    </div>
                </div>
            </div>
        </div>
        </a>

        <a class="cabinet__user" href="/user/profile">
            <img class="cabinet__user-logo" src="<?= $viData['users'][$userId]['src'] ?>"
                 alt="<?= $viData['users'][$userId]['name'] ?>"/>
            <div class="cabinet__user-name"><?= $viData['users'][$userId]['name'] ?></div>
        </a>
    </div>


    <div class="project__module" data-id="<?=$project?>">
        <div class="project__header">
            <? require '/../filter.php'; // ФИЛЬТР?>
        </div>
    </div>

    <div class="cabinet__body">

        <div class="cabinet__tasks">

            <? if (count($viData['items']) > 0): ?>

                <div class="tasks__box" id="ajax-content">
                    <? require __DIR__ . '/tasks-ajax.php';?>
                </div>
            <?php else: ?>
                <br><br><h2 class="center">Задания не найдены</h2>
            <?php endif; ?>


        </div>
    </div>

</div>

<input type="hidden" id="user_id" value="<?= $userId ?>"/>
<input type="hidden" id="project_id" value="<?= $project ?>"/>
