<?php
/**
 * Created by PhpStorm.
 * User: Stanislav
 * Date: 19.09.2018
 * Time: 14:40
 */

if(sizeof($viData['items'])>0): ?>
    <?php
    foreach ($viData['items'] as $d => $date):
        foreach ($date as $city):
            ?>
            <div class="task__item">
                <h2 class="task__item-title"><?=$city['city']?> <span><?=$city['date']?></span></h2>
                <table class="task__table">
                    <thead>
                    <tr>
                        <th class="name">Название ТТ</th>
                        <th class="index">Адрес ТТ</th>
                        <? if(!empty($city['ismetro'])): ?><th class="metro">Метро</th><? endif; ?>
                        <?//<th>Дата</th>?>
                        <th class="task">Кол-во заданий</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($city['users'] as $idus => $arPoints): ?>
                        <?php $user = $viData['users'][$idus]; ?>
                        <tr>
                            <?php $cnt = 0; ?>
                            <?php foreach ($arPoints as $p): ?>
                                <?php $point = $viData['points'][$p]; ?>
                                <td class="name">
                                    <div class="task__table-cell border"><?=$point['name']?></div>
                                </td>
                                <td class="index">
                                    <div class="task__table-cell border task__table-index">
                                        <span><?=$point['adres']?></span>
                                        <b class="js-g-hashint" title="Посмотреть на карте"></b>
                                    </div>
                                </td>
                                <?php if(!empty($city['ismetro'])): ?>
                                    <td class="metro">
                                        <div class="task__table-cell border task__table-index">
                                            <span><?=$point['metro']?></span>
                                        </div>
                                    </td>
                                <?php endif; ?>
                                <?/*<td>
									<div class="task__table-cell border text-center"><?=$date?></div>
								</td>*/?>
                                <td class="task">
                                    <div class="task__table-cell border task__table-cnt">
                                        <? $tasks = sizeof($viData['tasks'][$d][$p][$idus]); ?>
                                        <span><?=$tasks?></span>
                                        <?if($tasks):?>
                                        <span
                                            class="task__table-watch"
                                            data-user="<?=$idus?>"
                                            data-date="<?=$city['date']?>"
                                            data-point="<?=$p?>"
                                        >
                                            Смотреть
                                        </span>
                                        <?endif;?>
                                    </div>
                                </td>
                                <?php $cnt++; ?>
                                <? if($cnt<sizeof($viData['points'])) echo '</tr><tr>'; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
        endforeach;
    endforeach;
    ?>
<?php else: ?>
    <br><p class="center">Не найдено локаций с выбранным персоналом</p>
<?php endif; ?>