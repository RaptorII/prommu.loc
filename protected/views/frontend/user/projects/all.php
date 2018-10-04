<?php 
  $this->setBreadcrumbsEx(
    array('Мои проекты', MainConfig::$PAGE_PROJECT_LIST),
    array('Все проекты', MainConfig::$PAGE_PROJECT_ALL)
  );
  $this->setPageTitle('Все проекты');
	$bUrl = Yii::app()->baseUrl . '/theme/';
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item-base.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/all.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-filter.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/universal-filter.js', CClientScript::POS_END);

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
		]
	];
	foreach ($viData['filter']['cities'] as $key => $value)
		$arFilterData['FILTER_SETTINGS'][2]['DATA'][$key] = ['title' => $value['city'], 'id' => $key];
	foreach ($viData['filter']['tt_name'] as $n)
		$arFilterData['FILTER_SETTINGS'][5]['DATA'][] = ['title' => $n, 'id' => $n];
	foreach ($viData['filter']['tt_index'] as $i)
		$arFilterData['FILTER_SETTINGS'][6]['DATA'][] = ['title' => $i, 'id' => $i];

/*
echo "<pre>";
print_r($viData); 
echo "</pre>";
*/
?>
<div class="row project">
	<div class="col-xs-12">
		<div class="project__module">
			<div class="filter__veil"></div>
			<div class="project__header">
				<? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>
			</div>
			<div id="ajax-content">
				<? require __DIR__ . '/all-ajax.php'; // СПИСОК ?>
			</div>
		</div>
	</div>
</div>