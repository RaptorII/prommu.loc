<?

$userId = Share::$UserProfile->id;

Yii::app()->getClientScript()->registerCssFile('/theme/css/projects/app-tasks.css');
Yii::app()->getClientScript()->registerScriptFile('/theme/js/projects/app-tasks.js', CClientScript::POS_END);
?>
<div class="cabinet">
    <div class="cabinet__header">
        <div class="project__info">
            <span><?= $viData['project']['name'] ?></span>
        </div>
        <div class="cabinet__timer">
            <div class="timer__box">
                <div class="timer__time">
                    <span class="timer__hours">15</span>
                    <span class="timer__sw">:</span>
                    <span class="timer__minutes">15</span>
                </div>
                <div class="timer__control">
                    <i class="timer__play"></i>
                    <span class="timer__control-start">начать</span>
                </div>
            </div>
        </div>
        <a class="cabinet__user" href="/user/profile">
            <img class="cabinet__user-logo" src="<?= $viData['users'][$userId]['src'] ?>"
                 alt="<?= $viData['users'][$userId]['name'] ?>"/>
            <div class="cabinet__user-name"><?= $viData['users'][$userId]['name'] ?></div>
        </a>
    </div>

    <div class="cabinet__body">

        <div class="cabinet__tasks">

            <? if (count($viData['items']) > 0): ?>

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

                    </div>
                </div>

                <? foreach ($viData['items'] as $keyDate => $itemDate): ?>
                    <? foreach ($itemDate as $keyCity => $itemCity): ?>
                        <? foreach ($itemCity['points'] as $keyTT => $itemTT): ?>
                            <div class="cabinet__point">
                                <div class="point">
                                    <?= $viData['points'][$keyTT]['city'] ?>,
                                    <?= $viData['points'][$keyTT]['index_full'] ?>

                                    (<?= $viData['points'][$keyTT]['name'] ?>)
                                    <b class="js-g-hashint js-get-target tooltipstered"
                                       data-map-project="<?= $project ?>"
                                       data-map-user=""
                                       data-map-point="<?= $keyTT ?>"
                                       data-map-date="<?= $keyDate ?>"
                                    ></b>
                                </div>


                                <? foreach ($itemTT as $keyUser => $itemUser): ?>
                                    <div class="tasks">
                                        <? foreach ($itemUser as $keyTask => $itemTask): ?>


                                            <div class="task">
                                                <div class="task__title task__item">
                                                    <?= $itemTask['name'] ?>
                                                </div>
                                                <div class="task__date task__item">
                                                    <?= date('d.m.Y', $keyDate) ?>
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
                                                <?/*<div class="task__status task__item">
                                                    <?= $itemTask['status'] ?>
                                                </div>*/?>
                                                <div class="task__control start task__item">
                                                    <div class="timer__control">
                                                        <i class="timer__play"></i>
                                                        <span class="timer__control-start">начать</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <?/*<div class="task__descr task__item">
                                                <?= $itemTask['text'] ?>
                                            </div>*/?>


                                        <? endforeach; ?>
                                    </div>
                                <? endforeach; ?>

                            </div>
                        <? endforeach; ?>
                    <? endforeach; ?>
                <? endforeach; ?>
            <? endif; ?>
        </div>
    </div>

</div>


<?
echo "<pre>";
print_r($viData);
echo "</pre>";
?>
