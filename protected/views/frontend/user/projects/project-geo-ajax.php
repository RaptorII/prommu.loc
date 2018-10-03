<? if (sizeof($viData['items']) > 0): ?>
    <? foreach ($viData['items'] as $keyTimeStamp => $valueTimeStamp): ?>
        <? foreach ($valueTimeStamp as $keyCity => $valueCity): ?>
            <div class="project__geo-item">
                <h2 class="geo__item-title"><?= $valueCity['city'] ?> <span><?= $valueCity['date'] ?></span>
                </h2>
                <table class="geo__item-table">
                    <thead>
                    <tr>
                        <th>Сотрудник</th>
                        <th>Статус</th>
                        <th>Кол-во ТТ</th>
                        <th>Старт работы</th>
                        <th>Последнее место</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <? foreach ($valueCity['users'] as $keyUser => $valueUser): ?>
                        <tr>
                            <td>
                                <div class="geo__table-cell geo__table-user">

                                    <img src="<?= $viData['users'][$keyUser]['src'] ?>">
                                    <span><?= $viData['users'][$keyUser]['name'] ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="geo__table-cell">
                                    <? if ($viData['users'][$keyUser]['is_online'] != 0): ?>
                                        <span class="geo__green">&#9679 активен</span>
                                    <? else: ?>
                                        <span class="geo__red">&#9679 неактивен</span>
                                    <? endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="geo__table-cell">
                                    <?= count($valueUser['points']); ?>
                                </div>
                            </td>

                            <td>
                                <div class="geo__table-cell">

                                    <?if($valueUser['fact']):?>
                                        <span class="geo__green">начал</span>
                                    <?else:?>
                                        <span class="geo__grey">отсутствует</span>
                                    <?endif;?>
                                </div>
                            </td>
                            <td>
                                <div class="geo__table-cell">
                                    <div class="geo__table-loc">
                                        <?if($valueUser['last-point']):?>
                                        <span><?=$viData['points'][$valueUser['last-point']]['name']?></span>
                                        <b
                                            data-map-project="<?=$project?>"
                                            data-map-user="<?=$keyUser?>"
                                            data-map-point="<?=$valueUser['last-point']?>"
                                            data-map-date="<?=$keyTimeStamp?>"
                                            class="js-g-hashint js-get-map" title="Посмотреть на карте"></b>
                                        <?else:?>
                                        <span>-</span>
                                        <?endif;?>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="geo__table-cell">
                                    <a href="<? echo MainConfig::$PAGE_PROJECT_LIST . '/' . $project . '/geo/' . $keyUser .'/'.$keyTimeStamp.'/' ?>">подробнее</a>
                                </div>
                            </td>
                        </tr>
                    <? endforeach; ?>

                    </tbody>
                </table>
            </div>
        <? endforeach; ?>
    <? endforeach; ?>
<? else: ?>
    <br><p class="center">Не найдено локаций с выбранным персоналом</p>
<? endif; ?>
