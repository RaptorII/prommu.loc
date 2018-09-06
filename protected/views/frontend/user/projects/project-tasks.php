<?php
$bUrl = Yii::app()->baseUrl;
$pLink = MainConfig::$PAGE_PROJECT_LIST . '/' . $project;
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-tasks.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-tasks.js', CClientScript::POS_END);

$arFilterData = [
    'STYLES' => 'project__tasks-filter',
    'HIDE' => false,
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => ['filter'=>1],
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
            'DATA_DEFAULT' => '0',
        ],
        1 => [
            'NAME' => 'Дата с',
            'TYPE' => 'calendar',
            'INPUT_NAME' => 'bdate',
            'DATA' => [],
            'DATA_DEFAULT' => $viData['filter']['bdate'],
            'DATA_SHORT' => $viData['filter']['bdate-short']
        ],
        2 => [
            'NAME' => 'По',
            'TYPE' => 'calendar',
            'INPUT_NAME' => 'edate',
            'DATA' => [],
            'DATA_DEFAULT' => $viData['filter']['edate'],
            'DATA_SHORT' => $viData['filter']['edate-short']
        ]
    ]
];
foreach ($viData['filter']['cities'] as $id => $city)
  $arFilterData['FILTER_SETTINGS'][0]['DATA'][$id] = ['title'=>$city, 'id'=>$id];
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
<div class="project__module">
	<div class="tasks__list">
		<? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>
		<div class="tasks" id="ajax-content">
			<? require __DIR__ . '/project-tasks-ajax.php'; // СПИСОК ?>
		</div>
  </div>
  <div class="users__list">
	<?php
		foreach ($viData['items'] as $arDate):
			foreach ($arDate as $id_city => $arCity):
				foreach ($arCity['points'] as $point => $arUsers): 
					foreach ($arUsers as $id_user => $user): 
	?>
	  <div 
			class="task__single"
			data-user="<?=$id_user?>"
			data-date="<?=$date?>"
			data-point="<?=$point?>"
		>
	    <div class="task__single-logo">
	      <img src="<?=$user['src']?>">
	    </div>
	    <div class="task__single-info">
	      <div class="task__block">
	        <h2 class="task__single-title"><?=$user['name']?></h2>
	        <div class="task__single-table">

	          <div class="task__single-user task__user-info">
	            <div class="task__user-name"><?=$user['user']?></div>
	            <div class="task__user-index"><b><?=$user['index']?></b></div>
	            <div class="task__user-date"><?=$arCity['date']?></div>
	          </div>
	          <div class="task__tasks-info">
	            <div class="task__tasks-title">
	              <span class="task__name">Не выбрано</span>
	              <ul class="task__hidden-ul">
	                <li data-task-id="1">Название задания 1</li>
	                <li data-task-id="2">Название задания 2</li>
	                <li data-task-id="3">Название задания 3</li>
	                <li data-task-id="4">Название задания 4</li>
	                <li data-task-id="5">Название задания 5</li>
	                <li data-task-id="6">Название задания 6</li>
	              </ul>
	            </div>

	            <div class="task__tasks-buttons">
	              <span class="task__tasks-button task__button-green task__button-upload">Изменить</span>
	              <span class="task__tasks-button task__button-grey task__button-alldate">Дублироать на все даты</span>
	              <span class="task__tasks-button task__button-green task__button-allpersons">Дублироать задачу всем</span>
	            </div>
	          </div>
	        </div>

	        <input name="task_title" class="task__info-name" type="text" placeholder="Название задания..."/>
	        <textarea name="task_descr" class="task__info-descr" placeholder="Опишите задание..."></textarea>

	        <? /**********hiddens*************/ ?>
	        <input class="task_id-hidden" type="hidden" name="task_id" value="0">
	        <input type="hidden" name="person_id" value="0">
	        <input type="hidden" name="project_id" value="0">
	        <input type="hidden" name="task_date" value="0">
	        <input type="hidden" name="task_location_id" value="0">
	        <? /**********hiddens*************/ ?>

	        <div class="task__single-info-btn">
	          <a href="#" class="task__add-task">ДОБАВИТЬ ЗАДАЧУ</a>
	          <a href="#" class="task__add-update">ИЗМЕНИТЬ</a>
	          <a href="#" class="task__add-cancel">ОТМЕНИТЬ</a>
	        </div>
	      </div>
	    </div>
	  </div>
	<?php
					endforeach;
				endforeach;
			endforeach;
		endforeach;
	?>
  </div>
</div>
