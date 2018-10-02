<?php
    $pLink = MainConfig::$PAGE_PROJECT_LIST . '/' . $project;
    $point = Yii::app()->getRequest()->getParam('point');
    $this->setBreadcrumbsEx(
        array('Мои проекты', MainConfig::$PAGE_PROJECT_LIST),
        array($viData['project']['name'], $pLink),
        array(
            'ВЫБОР ПОЛЬЗОВАТЕЛЕЙ', 
            $pLink . '/users-select' . '/' . $point
        )
    );
    $this->setPageTitle($viData['project']['name']);
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/users-select.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/new.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/additional.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/users-select.js', CClientScript::POS_END);
	/***********UNIVERSAL FILTER************/
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/universal-filter.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-filter.css');

	$arFilterData = [
        'STYLES' => 'project__header-filter',
        'HIDE' => true,
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
                ],
                3 => [
                    'title' => 'Отказавшие',
                    'id' => '3'
                ]
            ],
            'DATA_DEFAULT' => '0'
        ],
        2 => [
            'NAME' => 'Фамилия',
            'TYPE' => 'text',
            'INPUT_NAME' => 'lname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        3 => [
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
        ]
    ],
	];
?>
<div class="row project">
    <div class="filter__veil"></div>
	<div class="col-xs-12">
		<h2 class="project__title">ВЫБОР ПОЛЬЗОВАТЕЛЕЙ<span></span></h2>
    <div class="prommu__universal-filter__buttoncontainer">
        <span class="prommu__universal-filter__button">ФИЛЬТР ДЛЯ ПЕРСОНАЛА</span>
    </div>
    <div class="project__header">
        <? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>
    </div>
		<table class="index-table">
			<tr>
				<td><b><?=$viData['point']['city'] ?></b></td>
			</tr>
			<? if(isset($viData['point']['ismetro'])): ?>
				<tr>
					<td><?=$viData['point']['metro'] ?></td>
				</tr>
			<? endif; ?>
			<tr>
				<td><?=$viData['point']['locname'] ?></td>
			</tr>
			<tr>
				<td><?=$viData['point']['locindex'] ?></td>
			</tr>
			<tr>
				<td><?=$viData['point']['date'] . ' ' . $viData['point']['time']?></td>
			</tr>
		</table>
		<br>
		<br>
		<form action="" method="POST" id="select-form">
			<div id="ajax-content">
				<?php require __DIR__ . '/project-users-select-ajax.php'; ?>
			</div>
			<div class="project__all-btns">
				<span class="save-btn" id="save-btn">СОХРАНИТЬ</span>
				<a class="save-btn" href="<?=$pLink?>">НАЗАД</a>
			</div>
		</form>
	</div>
</div>