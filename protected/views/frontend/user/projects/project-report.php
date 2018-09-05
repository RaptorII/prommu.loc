<?php
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/item.js', CClientScript::POS_END);

Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-report.css');

/***********UNIVERSAL FILTER************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
/***********UNIVERSAL FILTER************/

$arFilterData = [
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => [
        'SECTION_ID' => Yii::app()->request->getParam('section')
    ],
    'FILTER_SETTINGS' => [
        0 => [
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
        ]
    ]
];
?>

<div class="row project">
    <div class="col-xs-12">
        <? require __DIR__ . '/project-nav.php'; ?>
    </div>
</div>

<div class="project__module">

    <div class="filter__veil"></div>
    <div class="prommu__universal-filter">

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

    <div class="report__content">

        <div class="report__person">
            <div class="report__person-main">
                <div class="report__person-image">
                    <img src="http://n1s1.starhit.ru/98/88/17/988817db6d167526849b082151b49a00/480x497_0_41ed55af84b672870b6db9d9401c9604@480x497_0xc0a8399a_17479801141492689970.jpeg">
                </div>
                <div class="report__person-name">
                    Джеки Чан
                </div>
            </div>

            <div class="report__person-please">
                <div class="report__person-city">Гонконг</div>
                <div class="report__person-date">05.09.2018</div>
            </div>
        </div>

        <table class="route__table report__table">
            <thead>
            <tr>
                <th>Название и адрес ТТ</th>
                <th>Дата</th>
                <th>План прибытия</th>
                <th>факт прибытия</th>
                <th>План убыл</th>
                <th>Факт убыл</th>
                <th>Пробыл</th>
                <th>Перемещение</th>
                <th>Задачи план</th>
                <th>Задачи факт</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div class="route__table-cell border">
                        <span>Ул. Чаджуня 23</span>
                        <span class="report__info-main">АТБ1</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        15.02.2018
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 10:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 10:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 12:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 12:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        55 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        30 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        3
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">2</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="route__table-cell border">
                        <span>Ул. Фуньсуня 23</span>
                        <span class="report__info-main">АТБ1</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        15.02.2018
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 10:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 10:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 12:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 12:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        55 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        30 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        3
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">2</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="route__table-cell border">
                        <span>Ул. Зеросуня 23</span>
                        <span class="report__info-main">АТБ1</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        15.02.2018
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 10:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 10:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        в 12:00
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">в 12:15</span>
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        55 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        30 мин.
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        3
                    </div>
                </td>
                <td>
                    <div class="route__table-cell border">
                        <span class="report__info-main">2</span>
                    </div>
                </td>
            </tr>

            </tbody>
        </table>

        <div class="report__road-container">
            <div class="report__road-see">Посмотреть маршрут на карте <b class="js-g-hashint tooltipstered"></b></div>
        </div>
    </div>
</div>
