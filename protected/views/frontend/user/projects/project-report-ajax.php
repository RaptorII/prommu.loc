<?

if (count($viData['items']) > 0):
    foreach ($viData['items'] as $id_user => $value): ?>

        <div class="report__infoblock">
            <div class="report__person">
                <div class="report__person-main">
                    <div class="report__person-image">
                        <img src="<?= $viData['users'][$id_user]['src'] ?>">
                    </div>
                    <div class="report__person-name">
                        <?= $viData['users'][$id_user]['name'] ?>
                    </div>
                </div>
            </div>


            <table class="route__table report__table">
                <thead>
                <tr>
                    <th>Город</th>
                    <th>Название и адрес ТТ</th>
                    <th>Дата</th>
                    <th>План прибытия</th>
                    <th>Факт прибытия</th>
                    <th>План убыл</th>
                    <th>Факт убыл</th>
                    <th>Пробыл</th>
                    <th>Перемещение</th>
                    <th>Задачи план</th>
                    <th>Задачи факт</th>
                </tr>
                </thead>
                <tbody>
                <? foreach ($value as $unix => $valueDate):
                    foreach ($valueDate as $keyPlace => $valuePlace):
                        foreach ($valuePlace['points'] as $id_point):
                            $arGPS = $viData['gps-info'][$id_user][$unix][$id_point]; ?>
                            <tr>
                                <td>
                                    <div class="route__table-cell border">
                                        <?=$valuePlace['city']?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <span><?= $viData['points'][$id_point]['adres'] ?></span>
                                        <span class="report__info-main"><?= $viData['points'][$id_point]['name'] ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= $valuePlace['date'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= $viData['points'][$id_point]['btime'] ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                            <span class="report__info-main">
                                                 <?= ($arGPS['btime-fact']) ? $arGPS['btime-fact'] : "-"; ?>
                                            </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= $viData['points'][$id_point]['etime']; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                            <span class="report__info-main">
                                                 <?= ($arGPS['etime-fact']) ? $arGPS['etime-fact'] : "-"; ?>
                                            </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= ($arGPS['time-total']) ? $arGPS['time-total'] : "-"; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= ($arGPS['moving']) ? $arGPS['moving'] : "-"; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                        <?= ($arGPS['tasks-total']) ? $arGPS['tasks-total'] : "-"; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="route__table-cell border">
                                            <span class="report__info-main">
                                                <?= ($arGPS['tasks-fact']) ? $arGPS['tasks-fact'] : "-"; ?>
                                            </span>
                                    </div>
                                </td>
                            </tr>
                        <? endforeach; ?>
                        <?
                    endforeach;
                endforeach; ?>
                </tbody>
            </table>
        </div>
        <?
    endforeach;
endif;
?>