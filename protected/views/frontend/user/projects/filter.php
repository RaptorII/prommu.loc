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