<?
$this->setBreadcrumbsEx(
    array('Мои проекты', MainConfig::$PAGE_PROJECT_LIST),
    array($viData['project']['name'], "/")
);
$this->setPageTitle($viData['project']['name']);

$bUrl = Yii::app()->baseUrl . '/theme/';
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/additional.js', CClientScript::POS_END);


Yii::app()->getClientScript()->registerCssFile($bUrl . '/css/projects/project-app.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/js/projects/project-app.js', CClientScript::POS_END);


/***********FANCYBOX************/
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'js/dist/fancybox/jquery.fancybox.css');
/***********FANCYBOX************/
/***********MAP************/
Yii::app()->getClientScript()->registerScriptFile('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyC9M8BgorAu7Sn226LNP2rteTF5gO7KjLc');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/route-map.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/universal-map.css');
/***********MAP************/
?>


<?/*<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>*/?>

<div class="filter__veil"></div>

<div class="row project">
    <div class="col-xs-12">
        <? require 'nav.php'; ?>
    </div>
</div>


<div class="project__module" data-id="<?= $project ?>">
    <?php if (sizeof($viData['items']) > 0): ?>
        <div class="tasks__list">
            <?/* require __DIR__ . '/filter.php'; // ФИЛЬТР */?>
            <div class="tasks" id="ajax-content">
                <? require __DIR__ . '/base-ajax.php'; // СПИСОК ?>
            </div>
        </div>
        <div class="users__list">
            <?php
            foreach ($viData['items'] as $unix => $date):
                foreach ($date as $city):
                    foreach ($city['users'] as $idus => $arPoints):
                        foreach ($arPoints as $p):
                            $user = $viData['users'][$idus];
                            $point = $viData['points'][$p];
                            ?>
                            <div
                                    class="task__single"
                                    data-user="<?= $idus ?>"
                                    data-date="<?= $city['date'] ?>"
                                    data-point="<?= $p ?>"
                            >
                                <div class="task__single-info">
                                    <div class="task__block">

                                        <h2 class="task__item-title">Информация о ТТ</h2>
                                        <table class="task__table task__table-pointinfo">
                                            <thead>
                                            <tr>
                                                <th>Город</th>
                                                <th>Название</th>
                                                <th>Адрес</th>
                                                <? if ($point['metro']): ?>
                                                    <th>Метро</th>
                                                <? endif; ?>
                                                <th>Дата</th>
                                                <th>Время</th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="name">
                                                    <div class="task__table-cell border">
                                                        <?= $point['city'] ?>
                                                    </div>
                                                </td>
                                                <td class="name">
                                                    <div class="task__table-cell border">
                                                        <?= $point['name'] ?>
                                                    </div>
                                                </td>
                                                <td class="name">
                                                    <div class="task__table-cell border">
                                                        <?= $point['adres'] ?>
                                                    </div>
                                                </td>
                                                <? if ($point['metro']): ?>
                                                    <td class="name">
                                                        <div class="task__table-cell border">
                                                            <?= $point['metro'] ?>
                                                        </div>
                                                    </td>
                                                <? endif; ?>
                                                <td class="name">
                                                    <div class="task__table-cell border">
                                                        <?= $city['date'] ?>
                                                    </div>
                                                </td>
                                                <td class="name">
                                                    <div class="task__table-cell border">
                                                        с <?= $point['btime'] ?> по <?= $point['etime'] ?>
                                                    </div>
                                                </td>
                                                <td class="time">
                                                    <div class="task__table-cell border task__table-index">
                                                        <b
                                                                data-map-project="<?= $project ?>"
                                                                data-map-user="<?= $idus ?>"
                                                                data-map-point="<?= $point['point'] ?>"
                                                                data-map-date="<?= $unix ?>"
                                                                class="js-g-hashint js-get-target all__geo-data"
                                                                title="Посмотреть на карте">
                                                        </b>
                                                    </div>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>


                                        <div class="task__single-table">
                                            <div class="task__tasks-info">
                                                <?php $tasks = sizeof($viData['tasks'][$unix][$p][$idus]); ?>

                                                <h2 class="task__item-title">Задания</h2>
                                                <table class="task__table task__table-info">
                                                    <thead>
                                                    <tr>
                                                        <th class="name">Название</th>
                                                        <th class="task">Описание</th>
                                                        <th class="status">Действие</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <? foreach ($viData['tasks'][$unix][$p][$idus] as $task): ?>
                                                        <tr>
                                                            <td class="title">
                                                                <div class="task__table-cell border">
                                                                    <?= $task['name'] ?>
                                                                </div>
                                                            </td>

                                                            <td class="descr">
                                                                <div class="task__table-cell border">
                                                                    <?= $task['text'] ?>
                                                                </div>
                                                            </td>
                                                            <td class="stat">
                                                                <div class="task__table-cell border">
                                                                    <button data-status='start' class="task_activ">
                                                                        Начать
                                                                    </button>
                                                                    <? /*<button data-status='stop' class="task_activ">Завершить</button>*/ ?>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                    <? endforeach; ?>
                                                    </tbody>
                                                </table>


                                                <p class="task__empty"<?= ($tasks ? ' style="display:none"' : '') ?>>
                                                    Заданий нет</p>
                                            </div>
                                        </div>

                                        <? /**********hiddens*************/ ?>
                                        <input type="hidden" name="project" value="<?= $project ?>">
                                        <input class="task_id-hidden" type="hidden" name="task" value="new">
                                        <input type="hidden" name="user" value="<?= $idus ?>">
                                        <input type="hidden" name="date" value="<?= $unix ?>">
                                        <input type="hidden" name="point" value="<?= $p ?>">
                                        <? /**********hiddens*************/ ?>

                                        <div class="task__single-info-btn">
                                            <a href="<?= $pLink ?>" class="task__add-cancel">НАЗАД</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endforeach;
                    endforeach;
                endforeach;
            endforeach;
            ?>
        </div>
    <?php else: ?>
        <br><br><h2 class="center">Не найдено локаций</h2>
    <?php endif; ?>
</div>