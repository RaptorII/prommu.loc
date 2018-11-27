<?php
/**
 * Created by PhpStorm.
 * User: Stanislav
 * Date: 09.10.2018
 * Time: 13:04
 */

?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <?php if (count($viData['location']) > 0): ?>
            <table class="project__program">
                <tbody>
                <? foreach ($viData['location'] as $id => $arCity): ?>
                    <tr class="program__item" data-city="<?= $id ?>">
                        <td colspan="5">
                            <div class="program__city border">
                                <b><?= $arCity['name'] ?></b>
                                <? /*<span class="address__item-change">
											<span>изменить</span>
											<ul>
												<li><a href="<? echo $project . '/address-edit?city=' . $id ?>">изменить</a></li>
												<li data-id="<?= $id ?>" class="delcity">удалить</li>
											</ul>
										</span>*/ ?>
                            </div>
                        </td>
                    </tr>
                    <? foreach ($arCity['locations'] as $idloc => $arLoc): ?>
                        <tr class="program__cell-head">
                            <td>
                                <div class="program__cell border program__cell-fix">Название ТТ</div>
                            </td>
                            <td>
                                <div class="program__cell border program__cell-fix">Адрес ТТ</div>
                            </td>


                            <?/*<td>
                                <div class="program__cell border program__cell-fix">Метро</div>
                            </td>*/?>


                            <td>
                                <div class="program__cell border program__cell-fix">Персонал</div>
                            </td>
                            <td>
                                <div class="program__cell border program__cell-fix">Период</div>
                            </td>
                        </tr>
                        <tr class="loc-item" data-city="<?= $id ?>">
                            <td>
                                <div class="program__cell green-name">
                                    <?= $arLoc['name'] ?>
                                </div>
                            </td>
                            <td <? /*= (empty($arCity['metro']) ? 'colspan="2"' : '') */ ?>>
                                <div class="program__cell border">
                                    <?= $arLoc['index'] ?>


                                    <?$metro = join(',</br>', $arLoc['metro'])?>
                                    <?if(!empty($metro)):?>
                                        <span>
                                            <img title="<?=$metro?>"
                                                 class="point__metro js-g-hashint"
                                                 src="/theme/pic/projects/metro.png"/>
                                        </span>
                                    <?endif;?>

                                    <?/*
                                        echo $project;
                                        echo $idus;
                                        echo $viData['points'][$valueItem]['point'];
                                        echo $keyUnix;
                                    */?>

                                    <b
                                            data-map-project="<?= $project ?>"
                                            data-map-user="<?= $idus ?>"
                                            data-map-point="<?= $viData['points'][$valueItem]['point'] ?>"
                                            data-map-date="<?= $keyUnix ?>"
                                            class="js-g-hashint js-get-target all__geo-data" title="Посмотреть на карте">
                                    </b>
                                </div>
                            </td>

                            <?/*<td>
                                <div class="program__cell border">
                                    <? if (!empty($arCity['metro'])): ?>
                                        <? echo join(',</br>', $arLoc['metro']) ?>
                                    <? else: ?>
                                        <div class="program__cell-null">-</div>
                                    <? endif; ?>
                                </div>
                            </td>*/?>

                            <td>
                                <div class="program__cell border user">
                                    <?php
                                    $arUsers = array();
                                    foreach ($arLoc['periods'] as $idper => $arPer):
                                        foreach ($viData['users'] as $id_user => $user):
                                            if (in_array($idper, $user['points'])):
                                                $arUsers[$idper][] = $id_user;
                                                ?>
                                                <div class="program__cell-users">
                                                    <div class="program__cell-user">
                                                        <img src="<?= $user['src'] ?>">

                                                            <a href="/user/projects/user-card/<?=$user['id_user']?>" target="_blank">
                                                                <span class="program__user-card"><?= $user['name'] ?> </span>
                                                            </a>



                                                        <span title="Кол-во задач" class="program__tasks js-g-hashint tasks__count"
                                                              data-popup-project="<?=$project?>"
                                                              data-popup-user="<?=$user['id_user']?>"
                                                              data-popup-point="<?=$idper?>"
                                                              data-popup-date=""
                                                        ><?
                                                        echo (isset($viData['task-counters'][$idper][$id_user]) 
                                                                ? $viData['task-counters'][$idper][$id_user] 
                                                                : 0);
                                                        ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php if (!sizeof($arUsers[$idper])): ?>
                                        <div class="program__select-user" data-period="<?= $idper ?>">
                                            <a href="<? echo $project . '/users-select/' . $idper ?>"
                                               class="program-select-user__title">
                                                <span>Выбрать персонал </span>
                                                <b>&#9660</b>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <a href="<? echo $project . '/users-select/' . $idper ?>"><span>Изменить</span></a>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="period-data">
                                <div class="program__cell border">
                                    <? foreach ($arLoc['periods'] as $idper => $arPer): ?>
                                        <div class="program__cell-period" data-period="<?= $idper ?>">
                                            <span><? echo $arPer['bdate'] . ' до ' . $arPer['edate'] ?></span>
                                            <span class="program__cell-tiem"><? echo $arPer['btime'] . ' - ' . $arPer['etime'] ?></span>
                                            <span class="address__item-change period">
														<span>изменить</span>
														<ul>
															<li><a href="<? echo $project . '/address-edit?city=' . $id . '&per=' . $idper ?>">изменить</a></li>
															<li data-id="<?= $idper ?>" class="delperiod">удалить</li>
														</ul>
													</span>
                                        </div>
                                        <?php foreach ($arUsers[$idper] as $u) {
                                            echo "<br>";
                                        } ?>
                                    <? endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    <?
                    /*
                    ?>
                    <tr data-city="<?=$id?>">
                        <td colspan="5">
                            <div class="program__btns">
                                <a href="#" class="program__add-btn">+ ДОБАВИТЬ ПЕРИОД</a>
                                <a href="#" class="program__save-btn">СОХРАНИТЬ</a>
                            </div>
                        </td>
                    </tr>
                    <?
                    */
                    ?>
                <? endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <br><p class="center">По заданым параметрам данных не найдено</p>
        <?php endif; ?>
    </div>
</div>