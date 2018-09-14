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
                                    <? if ($viData['users'][$keyUser]['status'] != 0): ?>
                                        <span class="geo__green">&#9679 активен</span>
                                    <? else: ?>
                                        <span class="geo__red">&#9679 неактивен</span>
                                    <? endif; ?>
                                </div>
                            </td>
                            <td>
                                <div class="geo__table-cell">
                                    <?= count($valueUser) ?>
                                </div>
                            </td>
                            <td>
                                <div class="geo__table-cell">
                                    <span class="geo__green">начал</span>
                                </div>
                            </td>
                            <td>
                                <div class="geo__table-cell">
                                    <div class="geo__table-loc">
                                        <span>АТБ1</span>
                                        <b class="js-g-hashint" title="Посмотреть на карте"></b>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="geo__table-cell">
                                    <a href="<? echo MainConfig::$PAGE_PROJECT_LIST . '/' . $project . '/geo/' . $keyUser ?>">подробнее</a>
                                </div>
                            </td>
                        </tr>
                    <? endforeach; ?>

                    </tbody>
                </table>
            </div>
        <? endforeach; ?>
    <? endforeach; ?>
<? endif; ?>
