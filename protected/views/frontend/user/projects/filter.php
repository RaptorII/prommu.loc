<div 
    class="<?=$arFilterData['STYLES']?> prommu__universal-filter"
    <?=$arFilterData['HIDE']?' style="display:none"':''?>>
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
            $parentValueId = $value['CONDITION']['PARENT_VALUE_ID'][0];
        endif; ?>

        <? switch ($value['TYPE']):
            case 'block':
                ?>
                <div data-type="<?= $value['TYPE'] ?>"
                     class="u-filter__item u-filter__item-<?= $key ?> u-filter__blockitem">
                </div>
                <?
                break;
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
                        <span class="u-filter__calendar"><?= $value['DATA_SHORT'] ?></span>
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


<?/*

//Пример Массива на всякий случай

$arFilterData = [
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => [
        'SECTION_ID' => Yii::app()->request->getParam('section')
    ],
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
                    0 => '1',
                    1 => '2'
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
                'PARENT_ID' => '4',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '1',
                    1 => '2'
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
                    0 => '1',
                    1 => '2'
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
?>