<?php
$bUrl = Yii::app()->baseUrl . '/theme/';
$pLink = MainConfig::$PAGE_PROJECT_LIST . '/' . $project . '/tasks';
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/additional.js', CClientScript::POS_END);

/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item-tasks.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/item-tasks.js', CClientScript::POS_END);

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
<div class="filter__veil"></div>
<div class="row project">
	<div class="col-xs-12">
		<? require __DIR__ . '/project-nav.php'; // Меню вкладок ?>
	</div>
</div>
<div class="project__module" data-id="<?=$project?>">
	<?php if(sizeof($viData['items'])>0): ?>
		<div class="tasks__list">
			<? require __DIR__ . '/filter.php'; // ФИЛЬТР ?>
			<div class="tasks" id="ajax-content">
				<? require __DIR__ . '/project-tasks-ajax.php'; // СПИСОК ?>
			</div>
		</div>
		<div class="users__list">
		<?php
			foreach ($viData['items'] as $unix => $date):
				foreach ($date as $city):
					foreach ($city['users'] as $idus => $arPoints): 
						foreach ($arPoints as $p): 
							$user = $viData['users'][$idus];
							$point = $viData['points'][$p];
		?>
		  <div 
				class="task__single"
				data-user="<?=$idus?>"
				data-date="<?=$city['date']?>"
				data-point="<?=$p?>"
			>
		    <div class="task__single-logo">
		      <img src="<?=$user['src']?>">
		    </div>
		    <div class="task__single-info">
		      <div class="task__block">
		        <h2 class="task__single-title"><?=$point['name']?></h2>
		        <div class="task__single-table">

		          <div class="task__single-user task__user-info">
		            <div class="task__user-name"><?=$user['name']?></div>
		            <div class="task__user-index"><b><?=$point['adres']?></b></div>
		            <div class="task__user-date"><?=$city['date']?></div>
		          </div>
		          <div class="task__tasks-info">
		          	<?php $tasks = sizeof($viData['tasks'][$unix][$p][$idus]); ?>
								<div class="task__tasks-title"<?=(!$tasks?' style="display:none"':'')?>>
									<span class="task__name">Новое задание</span>
									<ul class="task__hidden-ul">
										<li data-id="new">Новое задание</li>
										<? foreach ($viData['tasks'][$unix][$p][$idus] as $task): ?>
											<li 
												data-id="<?=$task['id']?>" 
												data-text="<?=$task['text']?>"
												><?=$task['name']?></li>
										<? endforeach; ?>
									</ul>
								</div>

								<div class="task__tasks-buttons">
									<span class="task__tasks-button task__button-green task__button-change">Изменить</span>
									<span class="task__tasks-button task__button-grey task__button-alldate">Дублировать на все даты</span>
									<span class="task__tasks-button task__button-green task__button-users">Дублировать всем</span>
									<span class="task__tasks-button task__button-red task__button-del">Удалить</span>
								</div>
		            <p class="task__empty"<?=($tasks?' style="display:none"':'')?>>Заданий нет</p>
		          </div>
		        </div>

		        <input name="title" class="task__info-name" type="text" placeholder="Название задания..."/>
		        <textarea name="text" class="task__info-descr" placeholder="Опишите задание..."></textarea>

		        <? /**********hiddens*************/ ?>
		        <input type="hidden" name="project" value="<?=$project?>">
		        <input class="task_id-hidden" type="hidden" name="task" value="new">
		        <input type="hidden" name="user" value="<?=$idus?>">
		        <input type="hidden" name="date" value="<?=$unix?>">
		        <input type="hidden" name="point" value="<?=$p?>">
		        <? /**********hiddens*************/ ?>

		        <div class="task__single-info-btn">
		        	<a href="<?=$pLink?>" class="task__add-cancel">НАЗАД</a>
		          <a href="javascript:void(0)" class="task__add-task">ДОБАВИТЬ ЗАДАНИЕ</a>
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
	<?php else: ?>
		<br><br><h2 class="center">Не найдено локаций с выбранным персоналом</h2>
	<?php endif; ?>
</div>