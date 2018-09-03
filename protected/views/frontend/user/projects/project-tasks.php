<?php
$request = Yii::app()->request;
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);
/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/

Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-geo.css');
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-tasks.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item-tasks.js', CClientScript::POS_END);
?>

<?
$projectId = $request->getParam('id');
$sectionId = $request->getParam('section');

$arFilterData = [
    'ID' => $projectId, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => [
        'SECTION_ID' => $sectionId
    ],
    'FILTER_SETTINGS' => [
        0 => [
            'NAME' => 'Город',
            'TYPE' => 'select',
            'INPUT_NAME' => 'city',
            'DATA' => [
                0 => [
                    'title' => 'Москва',
                    'id' => '1'
                ],
                1 => [
                    'title' => 'Санкт-Петербург',
                    'id' => '0'
                ],
                2 => [
                    'title' => 'Все',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '2',
        ],
        1 => [
            'NAME' => 'Дата с',
            'TYPE' => 'calendar',
            'INPUT_NAME' => 'bdate',
            'DATA' => [],
            'DATA_DEFAULT' => '25.08.2018',
            'DATA_SHORT' => '25.08.18'
        ],
        2 => [
            'NAME' => 'По',
            'TYPE' => 'calendar',
            'INPUT_NAME' => 'edate',
            'DATA' => [],
            'DATA_DEFAULT' => '30.08.2018',
            'DATA_SHORT' => '30.08.18'
        ]
    ]
];


$arPromo = [
        0 =>[

        ],
        1 =>[

        ]
];

$testArray = [
        0 =>[
            'city' => 'Москва',
            'firstname' => 'Виталий',
            'lastname' => 'Бутырин',
            'ttadres' => [
                    0=>[
                        'point' => 12323,
                        'name' => 'АТБ1',
                        'adres' => 'Центральная',
                        'etime' => '13:00',
                        'btime' => '14:00',
                        'bdate' => '2018.09.03',
                        'edate' => '2018.09.10',
                        
                    ],
                    
                    1=> [
                        'point' => 15456,
                        'name' => 'АТБ2',
                        'adres' => 'Центральная33',
                        'etime' => '15:30',
                        'btime' => '16:00',
                        'bdate' => '2018.09.13',
                        'edate' => '2018.09.16',
                    ]
                
                ]

        ],
        1 =>[
            'city' => 'Харьков',
            'firstname' => 'Максим',
            'lastname' => 'Максимов',
            'ttadres' => [
                    0=>[
                        'point' => 76876,
                        'name' => 'АТБ1',
                        'adres' => 'Центральная',
                        'etime' => '13:00',
                        'btime' => '14:00',
                        'bdate' => '2018.09.03',
                        'edate' => '2018.09.10',
                        
                    ],
                    
                    1=> [
                        'point' => 435665,
                        'name' => 'АТБ2',
                        'adres' => 'Центральная33',
                        'etime' => '15:30',
                        'btime' => '16:00',
                        'bdate' => '2018.09.13',
                        'edate' => '2018.09.16',
                    ]
                
                ]

        ]
];
?>

<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($testArray); ?>
</pre>

<div class="row project">
    <div class="col-xs-12">
        <div class="project__tabs">
            <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/project-nav.php'; ?>
        </div>
    </div>
</div>

<div class="project__module">
    <div class="project__tasks-filter prommu__universal-filter">
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
                case 'calendar':
                    ?>
                    <div data-type="<?= $value['TYPE'] ?>"
                         data-id="<?= $key ?>"
                         data-parent-id="<?= $value['CONDITION']['PARENT_ID'] ?>"
                         data-parent-value="<?= $value['CONDITION']['PARENT_VALUE'] ?>"
                         data-parent-value-id="<?= $parentValueId ?>"
                         class="geo__header-date u-filter__item u-filter__item-<?= $key ?> <?= ($value['CONDITION']['BLOCKED']) ? 'blocked' : '' ?>">
                        <div class="u-filter__item-title">
                            <?= $value['NAME']; ?>
                        </div>
                        <div class="u-filter__item-data calendar-filter">
                            <span class="u-filter__calendar"></span>
                            <div class="calendar u-filter__calendarbox" data-type="bdate">
                                <table>
                                    <thead>
                                    <tr>
                                        <td class="mleft">‹
                                        <td colspan="5" class="mname">
                                        <td class="mright">›
                                    </tr>
                                    <tr>
                                        <td>Пн
                                        <td>Вт
                                        <td>Ср
                                        <td>Чт
                                        <td>Пт
                                        <td>Сб
                                        <td>Вс
                                    </tr>
                                    <tbody></tbody>
                                </table>
                            </div>

                            <input
                                    type="hidden"
                                    name="<?= $value['INPUT_NAME'] ?>"
                                    class="u-filter__hidden-data"
                                    value="<?= $value['DATA_DEFAULT'] ?>"
                            />
                            <input
                                    type="hidden"
                                    class="u-filter__hidden-default"
                                    value="<?= $value['DATA_SHORT'] ?>"
                            />
                        </div>
                    </div>

                    <?
                    break;
            endswitch; ?>
        <? endforeach; ?>

        <? if (isset($arFilterData['ID']) && !empty($arFilterData['ID'])): ?>
            <input type="hidden" name="id" value="<?= $arFilterData['ID'] ?>"/>
        <? endif; ?>
        <? if (count($arFilterData['FILTER_ADDITIONAL_VALUE']) > 0): ?>
            <? foreach ($arFilterData['FILTER_ADDITIONAL_VALUE'] as $addKey => $addValue): ?>
                <input type="hidden" name="<?= $addKey ?>" value="<?= $addValue ?>"/>
            <? endforeach; ?>
        <? endif; ?>
    </div>


    <div class="tasks">
        <div class="task__item">
            <h2 class="task__item-title">Харьков <span>14.02.2018</span></h2>
            <table class="task__table">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Название ТТ</th>
                    <th>Адрес ТТ</th>
                    <th>Дата</th>
                    <th>Кол-во заданий</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td rowspan="3">
                        <div class="task__table-cell task__table-user">
                            <img src="/images/applic/20180503073112204100.jpg">
                            <span>Дмитриев<br/>Николай</span>
                        </div>
                    </td>
                    <td>
                        <div class="task__table-cell border">АТБ1</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-index">
                            <span>ул. Пирогова 23</span>
                            <b class="js-g-hashint" title="Посмотреть на карте"></b>
                        </div>
                    </td>
                    <td>
                        <div class="task__table-cell border text-center">14.02.2018</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-cnt">
                            <span>3</span>
                            <a href="#" class="task__table-watch">посмотреть</a>
                            <a href="#">добавить</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="task__table-cell border">ВАРУС</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-index">
                            <span>пр. Кирова 18</span>
                            <b class="js-g-hashint" title="Посмотреть на карте"></b>
                        </div>
                    </td>
                    <td>
                        <div class="task__table-cell border text-center">14.02.2018</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-cnt">
                            <span>3</span>
                            <a href="#" class="task__table-watch">посмотреть</a>
                            <a href="#">добавить</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="task__table-cell border">СЕЛЬПО</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-index">
                            <span>ул. Строителей 4</span>
                            <b class="js-g-hashint" title="Посмотреть на карте"></b>
                        </div>
                    </td>
                    <td>
                        <div class="task__table-cell border text-center">14.02.2018</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-cnt">
                            <span>3</span>
                            <a href="#" class="task__table-watch">посмотреть</a>
                            <a href="#">добавить</a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?
        //
        ?>
        <div class="task__item">
            <h2 class="task__item-title">Москва <span>15.02.2018</span></h2>
            <table class="task__table">
                <thead>
                <tr>
                    <th>ФИО</th>
                    <th>Название ТТ</th>
                    <th>Адрес ТТ</th>
                    <th>Дата</th>
                    <th>Кол-во заданий</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td rowspan="3">
                        <div class="task__table-cell task__table-user">
                            <img src="/images/applic/20180428142455264100.jpg">
                            <span>Наталья<br/>Гуторова</span>
                        </div>
                    </td>
                    <td>
                        <div class="task__table-cell border">АТБ1</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-index">
                            <span>ул. Пирогова 23</span>
                            <b class="js-g-hashint" title="Посмотреть на карте"></b>
                        </div>
                    </td>
                    <td>
                        <div class="task__table-cell border text-center">15.02.2018</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-cnt">
                            <span>3</span>
                            <a href="#" class="task__table-watch">посмотреть</a>
                            <a href="#">добавить</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="task__table-cell border">ВАРУС</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-index">
                            <span>пр. Кирова 18</span>
                            <b class="js-g-hashint" title="Посмотреть на карте"></b>
                        </div>
                    </td>
                    <td>
                        <div class="task__table-cell border text-center">15.02.2018</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-cnt">
                            <span>3</span>
                            <a href="#" class="task__table-watch">посмотреть</a>
                            <a href="#">добавить</a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="task__table-cell border">СЕЛЬПО</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-index">
                            <span>ул. Строителей 4</span>
                            <b class="js-g-hashint" title="Посмотреть на карте"></b>
                        </div>
                    </td>
                    <td>
                        <div class="task__table-cell border text-center">15.02.2018</div>
                    </td>
                    <td>
                        <div class="task__table-cell border task__table-cnt">
                            <span>3</span>
                            <a href="#" class="task__table-watch">посмотреть</a>
                            <a href="#">добавить</a>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <?
    //
    ?>
    <div class="task__single">
        <div class="task__single-logo">
            <img src="/images/applic/20180503073112204100.jpg">
        </div>
        <div class="task__single-info">

            <div class="task__block">
                <h2 class="task__single-title">АТБ1</h2>
                <div class="task__single-table">

                    <div class="task__single-user task__user-info">
                        <div class="task__user-name">Дмитриев Николай</div>
                        <div class="task__user-index"><b>ул. Пирогова 147</b></div>
                        <div class="task__user-date">14.08.2018</div>
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
                </div>
            </div>
            <?
            //
            ?>
            <div class="task__block">
                <h2 class="task__single-title">АТБ1</h2>
                <table class="task__single-table">
                    <tr>
                        <td rowspan="3">
                            <div class="task__single-user">
                                <div class="task__user-name">Дмитриев Николай</div>
                                <div class="task__user-index"><b>ул. Пирогова 147</b></div>
                                <div class="task__user-date">14.08.2018</div>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <input type="radio" name="task" id="task_1" checked>
                                <label for="task_1">Видеть заказ</label>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <a href="#">изменить</a>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <a href="#" class="task-single__double">Дублироать на все даты</a>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <a href="#">Дублироать задачу всем</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="task-single__cell">
                                <input type="radio" name="task" id="task_2">
                                <label for="task_2">Проверить ценник</label>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <a href="#">изменить</a>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <a href="#" class="task-single__double">Дублироать на все даты</a>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <a href="#">Дублироать задачу всем</a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="task-single__cell">
                                <input type="radio" name="task" id="task_3">
                                <label for="task_3">Сдать форму</label>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <a href="#">изменить</a>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <a href="#" class="task-single__double">Дублироать на все даты</a>
                            </div>
                        </td>
                        <td>
                            <div class="task-single__cell">
                                <a href="#">Дублироать задачу всем</a>
                            </div>
                        </td>
                    </tr>
                </table>
                <input class="task__info-name" type="text" name="" placeholder="Название задания..."/>
                <textarea class="task__info-descr" placeholder="Опишите задание..."></textarea>
                <div class="task__single-info-btn">
                    <a href="#">ДОБАВИТЬ ЗАДАЧУ</a>
                </div>
            </div>
        </div>
    </div>
</div>
