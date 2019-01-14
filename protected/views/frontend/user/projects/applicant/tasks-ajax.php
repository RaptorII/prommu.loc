<div class="task__header">
    <div class="task__title task__item">
        Название
    </div>
    <div class="task__date task__item">
        Дата
    </div>
    <div class="task__start task__item">
        Начало
    </div>
    <div class="task__end task__item">
        Конец
    </div>
    <div class="task__post task__item">
        Должность
    </div>
    <? /*<div class="task__status task__item">
                                                    <?= $itemTask['status'] ?>
                                                </div>*/ ?>
    <div class="task__control start task__item">
        Статус
    </div>
</div>
<? foreach ($viData['items'] as $keyDate => $itemDate): ?>
    <div class="cabinet__menu">
        <span  data-map-project="<?= $project ?>"
               data-map-user="<?= $userId ?>"
               data-map-point=""
               data-map-date="<?= $keyDate ?>"
               class="cabinet__rout-view js-get-target">Смотреть маршрут</span>

        <?/*<span class="cabinet__menu-con">
            <span class="cabinet__rout-view">Описания задач</span>
            <span class="cabinet__rout-view">Описания ТТ</span>
        </span>*/?>
    </div>
    <? foreach ($itemDate as $keyCity => $itemCity): ?>
        <? if ($itemCity['is_cur_date'] == 1): ?>
            <div id="target"></div>
        <? endif; ?>

        <pre><?print_r($itemCity);?></pre>

        <? foreach ($itemCity['points'] as $keyTT => $itemTT): ?>


            <div class="cabinet__point">
                <div class="point">
                    <div class="point__header">


                        <? if ($viData['points'][$keyTT]['comment']): ?>
                            <div title="Нажмите для прочтения комментария к ТТ" class="warning js-g-hashint"></div>
                        <? endif; ?>

                        <?= $viData['points'][$keyTT]['city'] ?>,
                        <?= $viData['points'][$keyTT]['index_full'] ?>

                        <? if ($viData['points'][$keyTT]['metro']): ?>
                            (Станция метро: <?= $viData['points'][$keyTT]['metro'] ?>)
                        <? endif; ?>

                        <b class="js-g-hashint js-get-target tooltipstered"
                           data-map-project="<?= $project ?>"
                           data-map-user="<?= $userId ?>"
                           data-map-point="<?= $keyTT ?>"
                           data-map-date="<?= $keyDate ?>"
                        ></b>
                    </div>

                    <? if ($itemCity['is_cur_date'] == 1): ?>
                        <? if (!in_array($keyTT, $viData['start'])): ?>
                            <div class="timer__control point__timer start"
                                 data-point="<?= $keyTT ?>"
                                 data-date="<?= $keyDate ?>"
                            >
                                <i class="timer__play"></i>
                                <span class="timer__control-st">начать</span>
                            </div>
                        <? else: ?>
                            <div class="timer__control point__timer stop"
                                 data-point="<?= $keyTT ?>"
                                 data-date="<?= $keyDate ?>"
                            >
                                <i class="timer__stop"></i>
                                <span class="timer__control-st">завершить</span>
                            </div>
                        <? endif; ?>
                    <? endif; ?>
                </div>

                <? if ($viData['points'][$keyTT]['comment']): ?>
                    <div class="point__descr">
                        <?= $viData['points'][$keyTT]['comment'] ?>
                    </div>
                <? endif; ?>


                <? foreach ($itemTT as $keyUser => $itemUser): ?>
                    <div class="tasks">
                        <? foreach ($itemUser as $keyTask => $itemTask): ?>


                            <div class="task" data-id="<?= $keyTask; ?>">
                                <div class="task__title task__item">
                                    <div title="Нажмите для прочтения описания задачи" class="task__descr-ico js-g-hashint">
                                        <?= $itemTask['name'] ?>
                                    </div>
                                </div>
                                <div class="task__date task__item">
                                    <? if ($itemCity['is_cur_date'] == 1): ?>
                                        <b><?= date('d.m.Y', $keyDate) ?></b>
                                    <?else:?>
                                        <?= date('d.m.Y', $keyDate) ?>
                                    <?endif;?>
                                </div>
                                <div class="task__start task__item">
                                    <?= $viData['points'][$keyTT]['btime'] ?>
                                </div>
                                <div class="task__end task__item">
                                    <?= $viData['points'][$keyTT]['etime'] ?>
                                </div>
                                <div class="task__post task__item">
                                    <?= $viData['points'][$keyTT]['post_name'] ?>
                                </div>

                                <div class="task__status task__item">
                                    <? if ($itemCity['is_cur_date'] == 1
                                        &&
                                        in_array($keyTT, $viData['start'])
                                    ): ?>

                                        <div class="task__item-data">
                                            <span class="task__select"><?= $arStatus[$itemTask['status']]; ?></span>
                                            <ul class="task__ul-hidden">
                                                <? foreach ($arStatus as $key => $value): ?>
                                                    <li class="task__li-hidden" data-id="<?= $key ?>"
                                                        data-task-id="<?= $itemTask['id']; ?>"><?= $value ?></li>
                                                <? endforeach; ?>
                                            </ul>
                                            <input type="hidden" class="task__li-visible"
                                                   value="<?= $itemTask['status']; ?>"
                                                   data-map-point="<?= $keyTT ?>"
                                                   data-map-date="<?= $keyDate ?>"
                                            />
                                        </div>
                                    <? else: ?>
                                        <?= $arStatus[$itemTask['status']] ?>
                                    <? endif; ?>


                                </div>


                            </div>

                            <? if ($itemTask['text']): ?>
                                <div class="task_descr">
                                    Описание задачи: <?= $itemTask['text'] ?>
                                </div>
                            <? endif; ?>
                            <? /*<div class="task__descr task__item">
                                                <?= $itemTask['text'] ?>
                                            </div>*/ ?>


                        <? endforeach; ?>
                    </div>
                <? endforeach; ?>

            </div>
        <? endforeach; ?>
    <? endforeach; ?>

    <div class="cabinet__date-end">

    </div>
<? endforeach; ?>