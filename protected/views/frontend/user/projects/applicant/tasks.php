<?

$userId = Share::$UserProfile->id;

Yii::app()->getClientScript()->registerCssFile('/theme/css/projects/app-tasks.css');
Yii::app()->getClientScript()->registerScriptFile('/theme/js/projects/app-tasks.js', CClientScript::POS_END);


/***********FANCYBOX************/
Yii::app()->getClientScript()->registerScriptFile('/theme/js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('/theme/js/dist/fancybox/jquery.fancybox.css');
/***********FANCYBOX************/
/***********MAP************/
Yii::app()->getClientScript()->registerScriptFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyC9M8BgorAu7Sn226LNP2rteTF5gO7KjLc');
Yii::app()->getClientScript()->registerScriptFile('/theme/js/projects/route-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile('/theme/css/projects/universal-map.css');
/***********MAP************/

$arStatus = [
    '0'=>'Ожидание',
    '1'=>'В работе',
    '3'=>'Готова',
    '4'=>'Отменена'
];

?>


<div class="cabinet">
    <div class="cabinet__header">
        <div class="project__info">
            <span><?= $viData['project']['name'] ?></span>
        </div>
        <div class="cabinet__timer">
            <div class="timer__box">
                <div class="timer__time">
                    <span id="t_hours" class="timer__hours">&nbsp;&nbsp;</span>
                    <span class="timer__sw">:</span>
                    <span id="t_minutes" class="timer__minutes">&nbsp;&nbsp;</span>
                </div>
                <div class="timer__control-top">
                    <div id="t_week" class="timer__control-text"></div>
                    <div class="timer__control-tasks">
                        Задач на сегодня:<span class="control__tasks">7</span>
                    </div>
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
                                    <div class="point__header">

                                        <? if ($viData['points'][$keyTT]['comment']): ?>
                                            <div class="warning"></div>
                                        <? endif; ?>

                                        <?= $viData['points'][$keyTT]['city'] ?>,
                                        <?= $viData['points'][$keyTT]['index_full'] ?>

                                        (<?= $viData['points'][$keyTT]['name'] ?>)
                                        <b class="js-g-hashint js-get-target tooltipstered"
                                           data-map-project="<?= $project ?>"
                                           data-map-user="<?=$userId?>"
                                           data-map-point="<?= $keyTT ?>"
                                           data-map-date="<?= $keyDate ?>"
                                        ></b>
                                    </div>

                                    <? if ($keyTT == 5728): ?>
                                        <div class="timer__control point__timer stop"
                                             data-point="<?= $keyTT ?>"
                                             data-date="<?= $keyDate ?>"
                                        >
                                            <i class="timer__stop"></i>
                                            <span class="timer__control-stop">завершить</span>
                                        </div>
                                    <? else: ?>
                                        <div class="timer__control point__timer start"
                                             data-point="<?= $keyTT ?>"
                                             data-date="<?= $keyDate ?>"
                                        >
                                            <i class="timer__play"></i>
                                            <span class="timer__control-start">начать</span>
                                        </div>
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


                                            <div class="task">
                                                <div class="task__title task__item">
                                                    <div class="task__descr-ico">
                                                        <?= $itemTask['name'] ?>
                                                    </div>
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

                                                <div class="task__status task__item">


                                                    <?/*=$arStatus[$itemTask['status']] */?>

                                                    <div class="task__item-data">
                                                        <span class="task__select"><?=$arStatus[$itemTask['status']];?></span>
                                                        <ul class="task__ul-hidden">
                                                            <?foreach ($arStatus as $key => $value):?>
                                                                <li class="task__li-hidden" data-id="<?=$key?>" data-task-id="<?=$itemTask['id'];?>"><?=$value?></li>
                                                            <?endforeach;?>
                                                        </ul>
                                                        <input type="hidden" class="task__li-visible"
                                                               value="<?=$itemTask['status'];?>"
                                                                data-map-point="<?= $keyTT ?>"
                                                                data-map-date="<?= $keyDate ?>"
                                                        />
                                                    </div>
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
                <? endforeach; ?>
            <? endif; ?>
        </div>
    </div>

</div>

<input type="hidden" id="user_id" value="<?=$userId?>"/>
<input type="hidden" id="project_id" value="<?=$project?>"/>

<?
echo "<pre>";
print_r($viData);
echo "</pre>";
?>
