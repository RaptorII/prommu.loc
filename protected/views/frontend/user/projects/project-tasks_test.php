<?php
$bUrl = Yii::app()->baseUrl . '/theme/';
$pLink = MainConfig::$PAGE_PROJECT_LIST . '/' . $project . '/tasks';
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/additional.js', CClientScript::POS_END);

/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item-tasks_test.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/item-tasks_test.js', CClientScript::POS_END);

Yii::app()->getClientScript()->registerScriptFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('//unpkg.com/leaflet@1.3.4/dist/leaflet.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/js/projects/universal-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/css/projects/universal-map.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/js/dist/fancybox/jquery.fancybox.css');

$arFilterData = [
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => ['filter' => 1],
    'FILTER_SETTINGS' => [
        0 => [
            'NAME' => 'ФИО',
            'TYPE' => 'text',
            'INPUT_NAME' => 'fname',
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
            'TYPE' => 'block',
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
?>

<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>

<div class="filter__veil"></div>
<div class="row project">
	<div class="col-xs-12">
		<? require __DIR__ . '/project-nav.php'; // Меню вкладок ?>
	</div>
</div>


<div class="project__module" data-id="<?=$project?>">
    <div class="project__header">
        <? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>
    </div>

	<?php if(sizeof($viData['items'])>0): ?>
		<div class="tasks__list">
			<div class="tasks" id="ajax-content">
				<? require __DIR__ . '/project-tasks_test-ajax.php'; // СПИСОК ?>
			</div>
		</div>
	<?php else: ?>
		<br><br><h2 class="center">Не найдено локаций с выбранным персоналом</h2>
	<?php endif; ?>
</div>

<div class="go-up" title="Вверх" id='ToTop'>⇧</div>
<div class="go-down" title="Вниз" id='OnBottom'>⇩</div>