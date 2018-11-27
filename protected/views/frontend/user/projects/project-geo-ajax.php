<? if (sizeof($viData['items']) > 0): ?>
    <? foreach ($viData['items'] as $keyCity => $valueCity): ?>
        <div class="project__geo-item">
            <h2 class="geo__item-title"><?= $valueCity['city'] ?></h2>
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
                                <? if (isset($valueUser['bfact']) && !$valueUser['is-missed']): ?>
                                    <span class="geo__green">&#9679 активен<br><?
                                        echo ($valueUser['time-isactive']?'(всего '.$valueUser['time-isactive'].')':'')
                                    ?></span>
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
                                <?if($valueUser['bfact']):?>
                                    <? if($valueUser['is-lateness']): ?>
                                        <span class="geo__red">опоздал<br>(<?=$valueUser['bfact']?>)</span>
                                    <? else: ?>
                                        <span class="geo__green">начал<br>(<?=$valueUser['bfact']?>)</span>
                                    <? endif; ?>
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
                                        data-map-point="<?=$valueUser['points'][0]?>"
                                        data-map-date="<?=$viData['unix']?>"
                                        class="js-g-hashint js-get-map" title="Посмотреть на карте"></b>
                                    <?else:?>
                                    <span>-</span>
                                    <?endif;?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="geo__table-cell">
                                <a href="<? echo MainConfig::$PAGE_PROJECT_LIST . '/' . $project . '/geo/' . $keyUser . '/'.$valueUser['points'][0]?>">подробнее</a>
                            </div>
                        </td>
                    </tr>
                <? endforeach; ?>

                </tbody>
            </table>
        </div>
    <? endforeach; ?>
<? else: ?>
    <br><br><h2 class="center">Не найдено локаций с выбранным персоналом</h2>
<? endif; ?>
