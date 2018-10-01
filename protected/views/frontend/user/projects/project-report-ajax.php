<? if (count($viData['items']) > 0): ?>
    <? foreach ($viData['items'] as $key => $value): ?>
        <? foreach ($value as $keyUser => $valueUser): ?>
            <? foreach ($valueUser as $keyPlace => $valuePlace): ?>

                <div class="report__infoblock">
                    <div class="report__person">
                        <div class="report__person-main">
                            <div class="report__person-image">
                                <img src="<?= $viData['users'][$keyUser]['src'] ?>">
                            </div>
                            <div class="report__person-name">
                                <?= $viData['users'][$keyUser]['name'] ?>
                            </div>
                        </div>

                        <div class="report__person-please">

                            <div class="report__person-city"><?= $valuePlace['date'] ?></div>
                            <div class="report__person-date"><?= $valuePlace['city'] ?></div>
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
                        <? foreach ($valuePlace['points'] as $keyPoint => $valuePoint): ?>
                            <? $jpsInfo = $viData['jps-info'][$key][$keyUser][$valuePoint];?>
                            <tr>
                                <td>
                                    <div class="route__table-cell border">
                                        <span><?= $viData['points'][$valuePoint]['adres'] ?></span>
                                        <span class="report__info-main"><?= $viData['points'][$valuePoint]['name'] ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= $valuePlace['date'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= $viData['points'][$valuePoint]['btime'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                            <span class="report__info-main">
                                                 <?= ($jpsInfo['btime-fact']) ? $jpsInfo['btime-fact'] : "-"; ?>
                                            </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= $viData['points'][$valuePoint]['etime']; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                            <span class="report__info-main">
                                                 <?= ($jpsInfo['etime-fact']) ? $jpsInfo['etime-fact'] : "-"; ?>
                                            </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= ($jpsInfo['time-total']) ? $jpsInfo['time-total'] : "-"; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= ($jpsInfo['moving']) ? $jpsInfo['moving'] : "-"; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= ($jpsInfo['tasks-total']) ? $jpsInfo['tasks-total'] : "-"; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                            <span class="report__info-main">
                                                <?= ($jpsInfo['tasks-fact']) ? $jpsInfo['tasks-fact'] : "-"; ?>
                                            </span>
                                    </div>
                                </td>
                            </tr>
                        <?endforeach; ?>

                        </tbody>
                    </table>



                    <div
                        data-map-project="<?=$project?>"
                        data-map-user="<?=$keyUser?>"
                        data-map-point=""
                        data-map-date="<?=$key?>"
                        class="report__road-container js-get-map">
                        <div class="report__road-see">Посмотреть маршрут на карте <b
                                class="js-g-hashint tooltipstered"></b>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        <? endforeach; ?>
    <? endforeach; ?>
<? else: ?>
    <br><p class="center">Не найдено локаций с выбранным персоналом</p>
<? endif; ?>
