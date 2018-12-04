<?php
/**
 * Created by PhpStorm.
 * User: Stanislav
 * Date: 19.09.2018
 * Time: 14:40
 */
if (sizeof($viData['items']) > 0): ?>
    <?php
    foreach ($viData['items'] as $d => $date):
        ?>

        <?/* <h2 class="task__item-title"><?=$city['city']?> <span><?=$city['date']?></span></h2>*/
        ?>
        <h2 class="task__item-title"><?= date('d.m.Y', $d); ?></span></h2>
        <div class="task__item">
        <table class="task__table">
            <thead>
            <tr>
                <th class="name">Город</th>
                <th class="name">Название ТТ</th>
                <th class="index">Адрес ТТ</th>
                <th class="metro">Метро</th>
                <th class="time">Время</th>
                <th class="time"></th>
            </tr>
            </thead>
            <tbody>
            <?
            foreach ($date as $city):
                ?>
                <?php
                foreach ($city['users'] as $idus => $arPoints):
                    foreach ($arPoints as $p):
                        $point = $viData['points'][$p];
                        ?>
                        <tr>
                            <td class="name">
                                <div class="task__table-cell border"><?= $city['city'] ?></div>
                            </td>
                            <td class="name">
                                <div class="task__table-cell border"><?= $point['name'] ?></div>
                            </td>
                            <td class="index">
                                <div class="task__table-cell border task__table-index">
                                    <span><?= $point['index_full'] ?></span>
                                </div>
                            </td>
                            <td class="metro">
                                <div class="task__table-cell border task__table-index">
                    <span>
                        <? if (!empty($point['metro'])): ?>
                            <?= $point['metro'] ?>
                        <?
                        else:?>
                            -
                        <?endif; ?>
                    </span>
                                </div>
                            </td>
                            <td class="time">
                                <div class="task__table-cell border text-center"><?= $point['btime'] . '-' . $point['etime'] ?></div>
                            </td>
                            <td class="time">
                                <div class="task__table-cell border task__table-index">
                                    <b
                                            data-map-project="<?= $project ?>"
                                            data-map-user="<?= $idus ?>"
                                            data-map-point="<?= $p; ?>"
                                            data-map-date="<?= $d ?>"
                                            class="js-g-hashint js-get-target all__geo-data"
                                            title="Посмотреть на карте">
                                    </b>
                                </div>

                            </td>
                        </tr>
                        <?php
                    endforeach;
                endforeach;
                ?>
                <?php
            endforeach;
            ?>                    </tbody>
        </table>


        <div class="geo__map-container">

            <div class="geo__route-map map__get-point"
                 id="geo__route-<?= $idus; ?>-<?= $d ?>"
                 data-map-project="<?= $project ?>"
                 data-map-user="<?= $idus ?>"
                 data-map-point=""
                 data-map-date="<?= $d ?>">
            </div>
        </div>


        </div><?
    endforeach;
    ?>
<?php else: ?>
    <br><p class="center">Не найдено локаций с выбранным персоналом</p>
<?php endif; ?>