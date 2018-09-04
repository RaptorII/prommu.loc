<?php
	$pLink = MainConfig::$PAGE_PROJECT_LIST . '/' . $project;
	$point = Yii::app()->getRequest()->getParam('point');
  $this->setBreadcrumbsEx(
    array('Мои проекты', MainConfig::$PAGE_PROJECT_LIST),
    array($viData['title'], $pLink),
    array(
    	'ВЫБОР ПОЛЬЗОВАТЕЛЕЙ', 
    	$pLink . '/users-select' . '/' . $point
    )
  );
  $this->setPageTitle($viData['title']);
	$bUrl = Yii::app()->baseUrl;
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/users-select.css');
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/users-select.js', CClientScript::POS_END);
	/***********UNIVERSAL FILTER************/
	Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
	Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');

	$arFilterData = [
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
        <div class="project__header-filter prommu__universal-filter" style="display:none">
            <? foreach ($arFilterData['FILTER_SETTINGS'] as $key => $value): ?>

                <?
                if (count($value['CONDITION']['PARENT_VALUE_ID']) > 1):
                    for ($i = 0; $i < count($value['CONDITION']['PARENT_VALUE_ID']); $i++):
                        if ($i == 0) {
                            $parentValueId = $value['CONDITION']['PARENT_VALUE_ID'][$i];
                        } else {
                            $parentValueId .= "," . $value['CONDITION']['PARENT_VALUE_ID'][$i];
                        }
                    endfor;
                else:
                    $parentValueId = $value['CONDITION']['PARENT_VALUE_ID'];
                endif; ?>

                <? switch ($value['TYPE']):
                    case 'text':
                        ?>
                        <div data-type="<?= $value['TYPE'] ?>"
                             data-id="<?= $key ?>"
                             data-parent-id="<?= $value['CONDITION']['PARENT_ID'] ?>"
                             data-parent-value="<?= $value['CONDITION']['PARENT_VALUE'] ?>"
                             data-parent-value-id="<?= $parentValueId ?>"
                             class="u-filter__item u-filter__item-<?= $key ?>  <?= ($value['CONDITION']['BLOCKED']) ? 'blocked' : '' ?>">
                            <div class="u-filter__item-title">
                                <?= $value['NAME']; ?>
                            </div>
                            <div class="u-filter__item-data">
                                <input
                                        placeholder="<?= $value['PLACEHOLDER'] ?>"
                                        class="u-filter__text"
                                        type="text"
                                        name="<?= $value['INPUT_NAME']; ?>"
                                />
                                <input
                                        type="hidden"
                                        class="u-filter__hidden-default"
                                        value="<?= $value['DATA_DEFAULT'] ?>"
                                />
                            </div>
                        </div>
                        <?
                        break;
                    case 'select':
                        ?>
                        <div data-type="<?= $value['TYPE'] ?>"
                             data-id="<?= $key ?>"
                             data-parent-id="<?= $value['CONDITION']['PARENT_ID'] ?>"
                             data-parent-value="<?= $value['CONDITION']['PARENT_VALUE'] ?>"
                             data-parent-value-id="<?= $parentValueId ?>"
                             class="u-filter__item u-filter__item-<?= $key ?> <?= ($value['CONDITION']['BLOCKED']) ? 'blocked' : '' ?>">
                            <div class="u-filter__item-title">
                                <?= $value['NAME']; ?>
                            </div>
                            <div class="u-filter__item-data">
                                <span class="u-filter__select"></span>
                                <ul class="u-filter__ul-hidden">
                                    <? foreach ($value['DATA'] as $d_key => $d_value): ?>
                                        <li class="u-filter__li-hidden"
                                            data-id="<?= $d_value['id']; ?>"><?= $d_value['title']; ?></li>
                                    <? endforeach; ?>
                                </ul>
                                <input
                                        type="hidden"
                                        name="<?= $value['INPUT_NAME'] ?>"
                                        class="u-filter__hidden-data"
                                        value="<?= $value['DATA_DEFAULT'] ?>"
                                />
                                <input
                                        type="hidden"
                                        class="u-filter__hidden-default"
                                        value="<?= $value['DATA_DEFAULT'] ?>"
                                />
                            </div>
                        </div>
                        <?
                        break;
                    case 'block':
                        ?>
                        <div data-type="<?= $value['TYPE'] ?>"
                             class="u-filter__item u-filter__item-<?= $key ?> u-filter__blockitem"></div>
                        <?
                        break;
                endswitch; ?>
            <? endforeach; ?>
        </div>
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
			<div id="staff-content">
				<?php require __DIR__ . '/project-users-select-ajax.php'; ?>
			</div>
			<div class="project__all-btns">
				<span class="save-btn" id="save-btn">СОХРАНИТЬ</span>
				<a class="save-btn" href="<?=$pLink?>">НАЗАД</a>
			</div>
		</form>
	</div>
</div>