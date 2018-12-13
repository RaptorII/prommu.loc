<? /*
echo "<pre>";
print_r($viData);
echo "</pre>";
*/
?>

<?if(count($viData['items'])>0):?>
<? foreach ($viData['items'] as $keyDate => $itemDate): ?>
    <div class="task__item-date"><?= date('d.m.Y', $keyDate) ?></div>
    <div class="tasks__one-date">

        <? foreach ($itemDate as $keyCity => $itemCity): ?>
            <div class="task__item-city"><?= $itemCity['city'] ?></div>
            <div class="tasks__one-city">

                <? foreach ($itemCity['points'] as $keyTT => $itemTT): ?>
                    <div class="task__item-tt">
                        <?= $viData['points'][$keyTT]['name'] ?>,
                        <?= $viData['points'][$keyTT]['index_full'] ?>
                        <b class="js-g-hashint js-get-target tooltipstered"
                           data-map-project="<?=$project?>"
                           data-map-user=""
                           data-map-point="<?=$keyTT?>"
                           data-map-date="<?=$keyDate?>"
                        ></b>
                    </div>
                    <div class="tasks__one-tt">

                        <div class="task__item-container">

                            <? foreach ($itemTT as $keyUser => $itemUser): ?>
                                <div class="task__item-user">
                                    <a href="/user/projects/user-card/<?=$keyUser?>" class="task__table-cell task__table-user task__table-user-1">
                                        <img src="<?= $viData['users'][$keyUser]['src'] ?>">
                                        <span><?= $viData['users'][$keyUser]['name'] ?></span>
                                    </a>


                                    <div class="task__table-cell task__table-user border task__table-user-2">
                                        Кол-во задач:
                                        <? $tasksCount = count($viData['tasks'][$keyDate][$keyTT][$keyUser]) ?>
                                        <span class="task__table-user-task-ok">
                                            <? if ($tasksCount > 0): ?>
                                                <?= $tasksCount ?>
                                            <? else: ?>
                                                <span class="task__table-user-task-none">0</span>
                                            <? endif; ?>
                                        </span>

                                    </div>


                                    <? if ($tasksCount > 0): ?>
                                        <div data-type='change'
                                             class="task__table-cell task__table-user border task__table-user-3 button__task-control"
                                        >
                                            Изменить
                                        </div>
                                    <? else: ?>
                                        <div data-type='add'
                                             class="task__table-cell task__table-user border task__table-user-3 button__task-control"
                                        >
                                            Добавить
                                        </div>
                                    <? endif; ?>


                                </div>

                                <div class="tasks__one-user" data-user-id='<?= $keyUser ?>'
                                     data-tt-id='<?= $keyTT ?>' data-date-unix='<?= $keyDate ?>'
                                     data-project='<?= $project ?>'>
                                    <? $taskcount = 0; ?>
                                    <? foreach ($itemUser as $keyTask => $itemTask): ?>

                                        <div data-task-id='<?= $keyTask ?>' data-type="old" class="task__item">
                                            <table class="task__table">
                                                <? if ($taskcount == 0): ?>
                                                    <?$taskcount++;?>
                                                    <thead>
                                                    <tr>
                                                        <th class="name">Название</th>
                                                        <th class="descr">Описание</th>
                                                        <th class="status">Статус</th>
                                                    </tr>
                                                    </thead>
                                                <? endif; ?>
                                                <tbody>
                                                <tr>
                                                    <td class="name">
                                                        <div class="task__table-cell border">
                                                            <?= $itemTask['name'] ?>
                                                        </div>
                                                    </td>
                                                    <td class="descr">
                                                        <div class="task__table-cell border task__table-index">
                                                            <?= $itemTask['text'] ?>
                                                        </div>
                                                    </td>
                                                    <td class="status">

                                                        <div class="task__table-cell border">

                                                            <span class="tasks__status-<?= $itemTask['status'] ?> tasks__status">
                                                                <span class="tasks__status-circle">&#8226;</span>
                                                                <span class="status__active">
                                                                    <?= $arStatus[$itemTask['status']]; ?>
                                                                </span>
                                                            </span>
                                                        </div>

                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>


                                        <? /*<div class="task__item">

                                            <table class="task__table">
                                                <thead>
                                                <? /*<tr>
                        <th class="user">ФИО</th>
                        <th class="name">Название</th>
                        <th class="descr">Описание</th>
                        <th class="status">Статус</th>
                    </tr>*/ /*?>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td class="name">
                                                        <div class="task__table-cell border">
                                                            Название задания
                                                        </div>
                                                    </td>
                                                    <td class="descr">
                                                        <div class="task__table-cell border task__table-index">
                                                            Описание задания Описание задания Описание задания Описание
                                                            задания Описание
                                                            задания
                                                            мОписание
                                                            задания Описание задания Описание задания Описание задания
                                                            Описание
                                                            заданияОписание
                                                            задания
                                                            Описание задания Описание задания Описание задания Описание
                                                            задания Описание
                                                            заданияОписание
                                                            задания Описание задания Описание задания
                                                        </div>
                                                    </td>
                                                    <td class="status">
                                                        <div class="task__table-cell border">
                                <span class="tasks__status-canceled tasks__status">
                                    <span class="tasks__status-circle">&#8226;</span>
                                    <span class="status__active">Отменена</span>
                                </span>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>*/ ?>
                                    <? endforeach; ?>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
        <? endforeach; ?>
    </div>
<? endforeach; ?>

    <input type="hidden" id="project_main" value="<?= $project ?>">


    <?else:?>
    <br><p class="center">Задачи отсутствуют</p>
    <?endif;?>
<? /*



            <div class="task__item-container">
                <div class="task__item-user task__item-false">
                    <div class="task__table-cell task__table-user task__table-user-1">
                        <img src="/images/applic/20180819180030833100.jpg">
                        <span>Бахвалова Маргарита</span>
                    </div>
                    <div class="task__table-cell task__table-user border task__table-user-2">
                        Кол-во задач: <span class="task__table-user-task-none">0</span>
                    </div>
                    <div data-type='add'
                         class="task__table-cell task__table-user border task__table-user-3 button__task-control"
                    >
                        Добавить
                    </div>
                </div>

                <div class="tasks__one-user">

                </div>

            </div>


            <div class="task__item-container">
                <div class="task__item-user">
                    <div class="task__table-cell task__table-user task__table-user-1">
                        <img src="/images/applic/20180819180030833100.jpg">
                        <span>Бахвалова Маргарита</span>
                    </div>
                    <div class="task__table-cell task__table-user border task__table-user-2">
                        Кол-во задач: <span class="task__table-user-task-ok">2</span>
                    </div>
                    <div data-type='change'
                         class="task__table-cell task__table-user border task__table-user-3 button__task-control"
                    >
                        Изменить
                    </div>
                </div>
                <div class="tasks__one-user">
                    <div class="task__item">
                        <table class="task__table">
                            <thead>
                            <tr>
                                <th class="name">Название</th>
                                <th class="descr">Описание</th>
                                <th class="status">Статус</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="name">
                                    <div class="task__table-cell border">
                                        Название задания
                                    </div>
                                </td>
                                <td class="descr">
                                    <div class="task__table-cell border task__table-index">
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        задания
                                        мОписание
                                        задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания Описание задания Описание задания
                                    </div>
                                </td>
                                <td class="status">
                                    <div class="task__table-cell border">
                                <span class="tasks__status-work tasks__status">
                                    <span class="tasks__status-circle">&#8226;</span>
                                    <span class="status__active">В работе</span>
                                </span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="task__item">

                        <table class="task__table">
                            <thead>
                            <? /*<tr>
                        <th class="user">ФИО</th>
                        <th class="name">Название</th>
                        <th class="descr">Описание</th>
                        <th class="status">Статус</th>
                    </tr>*/ /*?>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="name">
                                    <div class="task__table-cell border">
                                        Название задания
                                    </div>
                                </td>
                                <td class="descr">
                                    <div class="task__table-cell border task__table-index">
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        задания
                                        мОписание
                                        задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания Описание задания Описание задания
                                    </div>
                                </td>
                                <td class="status">
                                    <div class="task__table-cell border">
                                <span class="tasks__status-canceled tasks__status">
                                    <span class="tasks__status-circle">&#8226;</span>
                                    <span class="status__active">Отменена</span>
                                </span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?endforeach;?>
    </div>
    <?endforeach;?>
</div>
<?endforeach;?>

    <?/*<div class="task__item-city">Донецк</div>
    <div class="tasks__one-city">
        <div class="task__item-tt">
            Название ТТ 2, Адрес ТТ 2
            <b class="js-g-hashint js-get-map tooltipstered"></b>
        </div>

        <div class="tasks__one-tt">

            <div class="task__item-container">
                <div class="task__item-user">
                    <div class="task__table-cell task__table-user task__table-user-1">
                        <img src="/images/applic/20180819180030833100.jpg">
                        <span>Бахвалова Маргарита</span>
                    </div>
                    <div class="task__table-cell task__table-user border task__table-user-2">
                        Кол-во задач: <span class="task__table-user-task-ok">3</span>
                    </div>
                    <div data-type='change'
                         class="task__table-cell task__table-user border task__table-user-3 button__task-control"
                    >
                        Изменить
                    </div>
                </div>

                <div class="tasks__one-user">
                    <div class="task__item">
                        <table class="task__table">
                            <thead>
                            <tr>
                                <th class="name">Название</th>
                                <th class="descr">Описание</th>
                                <th class="status">Статус</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="name">
                                    <div class="task__table-cell border">
                                        Название задания
                                    </div>
                                </td>
                                <td class="descr">
                                    <div class="task__table-cell border task__table-index">
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        задания
                                        мОписание
                                        задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания Описание задания Описание задания
                                    </div>
                                </td>
                                <td class="status">
                                    <div class="task__table-cell border">
                                <span class="tasks__status-work tasks__status">
                                    <span class="tasks__status-circle">&#8226;</span>
                                    <span class="status__active">В работе</span>
                                </span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="task__item">

                        <table class="task__table">
                            <thead>
                            <? /*<tr>
                        <th class="user">ФИО</th>
                        <th class="name">Название</th>
                        <th class="descr">Описание</th>
                        <th class="status">Статус</th>
                    </tr>*/ /*?>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="name">
                                    <div class="task__table-cell border">
                                        Название задания
                                    </div>
                                </td>
                                <td class="descr">
                                    <div class="task__table-cell border task__table-index">
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        задания
                                        мОписание
                                        задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания Описание задания Описание задания
                                    </div>
                                </td>
                                <td class="status">
                                    <div class="task__table-cell border">
                                <span class="tasks__status-canceled tasks__status">
                                    <span class="tasks__status-circle">&#8226;</span>
                                    <span class="status__active">Отменена</span>
                                </span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>?>
</div>


<div class="task__item-date">12.09.2018</div>
<div class="tasks__one-date">
    <div class="task__item-city">Алабино</div>
    <div class="tasks__one-city">
        <div class="task__item-tt">Название ТТ 1, Адрес ТТ 1</div>
        <div class="tasks__one-tt">
            <div class="task__item-container">
                <div class="task__item-user">
                    <div class="task__table-cell task__table-user task__table-user-1">
                        <img src="/images/applic/20180819180030833100.jpg">
                        <span>Бахвалова Маргарита</span>
                    </div>
                    <div class="task__table-cell task__table-user border task__table-user-2">
                        Кол-во задач: <span class="task__table-user-task-ok">3</span>
                    </div>
                    <div data-type='change'
                         class="task__table-cell task__table-user border task__table-user-3 button__task-control"
                    >
                        Изменить
                    </div>
                </div>

                <div class="tasks__one-user">
                    <div class="task__item">
                        <table class="task__table">
                            <thead>
                            <tr>
                                <th class="name">Название</th>
                                <th class="descr">Описание</th>
                                <th class="status">Статус</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="name">
                                    <div class="task__table-cell border">
                                        Название задания
                                    </div>
                                </td>
                                <td class="descr">
                                    <div class="task__table-cell border task__table-index">
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        задания
                                        мОписание
                                        задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания Описание задания Описание задания
                                    </div>
                                </td>
                                <td class="status">
                                    <div class="task__table-cell border">
                                <span class="tasks__status-work tasks__status">
                                    <span class="tasks__status-circle">&#8226;</span>
                                    <span class="status__active">В работе</span>
                                </span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="task__item-container">
                <div class="task__item-user">
                    <div class="task__table-cell task__table-user task__table-user-1">
                        <img src="/images/applic/20180819180030833100.jpg">
                        <span>Бахвалова Маргарита</span>
                    </div>
                    <div class="task__table-cell task__table-user border task__table-user-2">
                        Кол-во задач: <span class="task__table-user-task-ok">3</span>
                    </div>
                    <div data-type='change'
                         class="task__table-cell task__table-user border task__table-user-3 button__task-control"
                    >
                        Изменить
                    </div>
                </div>

                <div class="tasks__one-user">
                    <div class="task__item">
                        <table class="task__table">
                            <thead>
                            <tr>
                                <th class="name">Название</th>
                                <th class="descr">Описание</th>
                                <th class="status">Статус</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="name">
                                    <div class="task__table-cell border">
                                        Название задания
                                    </div>
                                </td>
                                <td class="descr">
                                    <div class="task__table-cell border task__table-index">
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        задания
                                        мОписание
                                        задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания Описание задания Описание задания
                                    </div>
                                </td>
                                <td class="status">
                                    <div class="task__table-cell border">
                                <span class="tasks__status-work tasks__status">
                                    <span class="tasks__status-circle">&#8226;</span>
                                    <span class="status__active">В работе</span>
                                </span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <div class="task__item">

                        <table class="task__table">
                            <thead>
                            <? /*<tr>
                        <th class="user">ФИО</th>
                        <th class="name">Название</th>
                        <th class="descr">Описание</th>
                        <th class="status">Статус</th>
                    </tr>*/ /*?>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="name">
                                    <div class="task__table-cell border">
                                        Название задания
                                    </div>
                                </td>
                                <td class="descr">
                                    <div class="task__table-cell border task__table-index">
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        задания
                                        мОписание
                                        задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания
                                        Описание задания Описание задания Описание задания Описание задания Описание
                                        заданияОписание
                                        задания Описание задания Описание задания
                                    </div>
                                </td>
                                <td class="status">
                                    <div class="task__table-cell border">
                                <span class="tasks__status-canceled tasks__status">
                                    <span class="tasks__status-circle">&#8226;</span>
                                    <span class="status__active">Отменена</span>
                                </span>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>

 */ ?>