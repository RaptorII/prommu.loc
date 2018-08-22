<?php
//$bUrl = Yii::app()->baseUrl;
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/get-personal.js', CClientScript::POS_END);
Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');

$arPromo = array(
    0 => array(
        'image' => '/images/applic/20180503073112204100.jpg',
        'ttlink' => '',
        'name' => 'Джон Смит',
        'city' => 'Moskow',
        'status' => 1
    ),
    1 => array(
        'image' => '/images/applic/20180503073112204100.jpg',
        'ttlink' => '',
        'name' => 'Джон Смит',
        'city' => 'Moskow',
        'status' => 0
    ),
    2 => array(
        'image' => '/images/applic/20180428142455264100.jpg',
        'ttlink' => '',
        'name' => 'Sasha Meet',
        'city' => 'Moskow',
        'status' => 1
    ),
    3 => array(
        'image' => '/images/applic/20180428142455264100.jpg',
        'ttlink' => '',
        'name' => 'Sasha Meet',
        'city' => 'Moskow',
        'status' => 1
    )
);


?>

<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($arPromo); ?>
</pre>

<div class="row project">
    <div class="col-xs-12">
        <div class="project__tabs">
            <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/project-nav.php'; ?>
        </div>
    </div>
</div>
<form action="/" method="POST" id="update-person">
    <div id="main" class="project__module">
        <div class="project__xls">
            <!--<label for="person_xls_add">-->
            <a href="#" id="add-program">Добавить персонал на проект</a>
            <!--</label>-->
            <input id="person_xls_add" type="file" name="person_xls" class="hide" accept="xls">
            <a href="/uploads/example.xls" download>Скачать пример для добавления</a>
        </div>


        <div class="project__control-panel">
            <div class="program__btns control__buttons">
                <span id="control__new-personal" class="control__add-btn">+ ПРИГЛАСИТЬ ПЕРСОНАЛ</span>
                <span id="control__add-personal" class="control__add-btn">+ ДОБАВИТЬ ПЕРСОНАЛ</span>
                <a href="#" id="control__save-btn" class="program__save-btn">СОХРАНИТЬ</a>
            </div>
        </div>

        <h1 class="project__title personal__title">ПЕРСОНАЛ</h1>
        <div class="row">
            <? for ($i = 0; $i < count($arPromo); $i++): ?>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <div class="personal__item">

                        <img class="<?= ($arPromo[$i]['status'] == 0) ? 'personal__deact' : ''; ?>"
                             src="<?= $arPromo[$i]['image']; ?>">

                        <div class="personal__item-name"><?= $arPromo[$i]['name']; ?></div>
                        <div class="personal__item-add">
                            <a href="<?= $arPromo[$i]['ttlink']; ?>">Закрепленные адреса</a>
                        </div>
                        <div class="personal__item-city"><?= $arPromo[$i]['city']; ?></div>
                    </div>
                </div>
            <? endfor; ?>
            <!--<div class="col-xs-12 col-sm-4 col-md-3">-->
            <!--  <div class="personal__item">-->
            <!--    <img src="/images/applic/20180503073112204100.jpg">-->
            <!--    <div class="personal__item-name">Ибадулаев Павел</div>-->
            <!--    <div class="personal__item-add">-->
            <!--      <a href="#">Закрепленные адреса</a>-->
            <!--    </div>-->
            <!--    <div class="personal__item-city">Москва</div>-->
            <!--  </div>-->
            <!--</div>-->
            <!--<div class="col-xs-12 col-sm-4 col-md-3">-->
            <!--  <div class="personal__item">-->
            <!--    <img src="/images/applic/20180430140946442100.jpg">-->
            <!--    <div class="personal__item-name">Немич Константин</div>-->
            <!--    <div class="personal__item-add">-->
            <!--      <a href="#">Закрепленные адреса</a>-->
            <!--    </div>-->
            <!--    <div class="personal__item-city">Новгород</div>-->
            <!--  </div>-->
            <!--</div>-->
            <!--<div class="col-xs-12 col-sm-4 col-md-3">-->
            <!--  <div class="personal__item">-->
            <!--    <img src="/images/applic/20180428142455264100.jpg">-->
            <!--    <div class="personal__item-name">Простова Ольга</div>-->
            <!--    <div class="personal__item-add">-->
            <!--      <a href="#">Закрепленные адреса</a>-->
            <!--    </div>-->
            <!--    <div class="personal__item-city">Рыбинск</div>-->
            <!--  </div>-->
            <!--</div>-->
        </div>
    </div>

    <div id="addition" class="project__module">
        <h2 class="project__title">ДОБАВИТЬ НОВЫЙ ПЕРСОНАЛ НА ПРОЕКТ </h2>
        <?php
        $viData['app_idies'] = array();
        foreach ($viData['workers']['promos'] as $key => $idus)
            $viData['app_idies'][] = intval($idus['id_user']);
        ?>
        <script type="text/javascript">
            var arIdies = <?=json_encode($viData['app_idies'])?>;
            var AJAX_GET_PROMO = "<?=MainConfig::$PAGE_SERVICES_PUSH?>";
        </script>
        <div class='row'>
            <? //		FILTER 		?>
            <div class="filter__veil"></div>
            <div class='col-xs-12 col-sm-4 col-md-3'>
                <div class="filter__vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
                <div id="promo-filter">
                    <div class='filter'>
                        <div class='filter__item filter-cities'>
                            <div class='filter__item-name opened'>Город</div>
                            <div class='filter__item-content opened'>
                                <div class="fav__select-cities" id="filter-city">
                                    <ul class="filter-city-select">
                                        <li data-id="0">
                                            <input type="text" name="fc" class="city-inp" autocomplete="off">
                                        </li>
                                    </ul>
                                    <ul class="select-list"></ul>
                                </div>
                            </div>
                        </div>
                        <div class='filter__item filter-posts'>
                            <div class='filter__item-name opened'>Должность</div>
                            <div class='filter__item-content opened'>
                                <div class='right-box'>
                                    <?php
                                    $sel = 0;
                                    foreach ($viData['workers']['posts'] as $p)
                                        if ($p['selected']) $sel++;
                                    ?>
                                    <input name='posts-all' type='checkbox' id="f-all-posts"
                                           class="filter__chbox-inp"<?= sizeof($viData['workers']['posts']) == $sel ? ' checked' : '' ?>>
                                    <label class='filter__chbox-lab' for="f-all-posts">Выбрать все / снять все</label>
                                    <?php foreach ($viData['workers']['posts'] as $p): ?>
                                        <input name='posts[]' value="<?= $p['id'] ?>" type='checkbox'
                                               id="f-post-<?= $p['id'] ?>"
                                               class="filter__chbox-inp" <?= $p['selected'] ? 'checked' : '' ?>>
                                        <label class='filter__chbox-lab'
                                               for="f-post-<?= $p['id'] ?>"><?= $p['name'] ?></label>
                                    <?php endforeach; ?>
                                </div>
                                <span class="more-posts">Показать все</span>
                            </div>
                        </div>
                        <div class='filter__item filter-age'>
                            <div class='filter__item-name opened'>Возраст</div>
                            <div class='filter__item-content opened'>
                                <div class="filter__age">
                                    <label>
                                        <span>от</span>
                                        <input name=af type='text' value="<?= $_POST['af'] ?>">
                                    </label>
                                    <label>
                                        <span>до</span>
                                        <input name='at' type='text' value="<?= $_POST['at'] ?>">
                                    </label>
                                    <div class="filter__age-btn">ОК</div>
                                </div>
                            </div>
                        </div>
                        <div class='filter__item filter-sex'>
                            <div class='filter__item-name opened'>Пол</div>
                            <div class='filter__item-content opened'>
                                <div class='right-box'>
                                    <input name='sm' type='checkbox' value='1' class="filter__chbox-inp"
                                           id="f-male"<?= ($_POST['sm'] ? ' checked' : '') ?>>
                                    <label class="filter__chbox-lab" for="f-male">Мужской</label>
                                    <input name='sf' type='checkbox' value='1' class="filter__chbox-inp"
                                           id="f-female"<?= ($_POST['sf'] ? ' checked' : '') ?>>
                                    <label class="filter__chbox-lab" for="f-female">Женский</label>
                                </div>
                            </div>
                        </div>
                        <div class='filter__item filter-additional'>
                            <div class='filter__item-name opened'>Дополнительно</div>
                            <div class='filter__item-content opened'>
                                <div class='right-box'>
                                    <input name='mb' type='checkbox' value='1' class="filter__chbox-inp"
                                           id="f-med"<?= ($_POST['mb'] ? ' checked' : '') ?>>
                                    <label class="filter__chbox-lab" for="f-med">Наличие медкнижки</label>
                                    <input name='avto' type='checkbox' value='1' class="filter__chbox-inp"
                                           id="f-auto"<?= ($_POST['avto'] ? ' checked' : '') ?>>
                                    <label class="filter__chbox-lab" for="f-auto">Наличие автомобиля</label>
                                    <input name='smart' type='checkbox' value='1' class="filter__chbox-inp"
                                           id="f-smart"<?= ($_POST['smart'] ? ' checked' : '') ?>>
                                    <label class="filter__chbox-lab" for="f-smart">Наличие смартфона</label>
                                    <input name='cardPrommu' type='checkbox' value='1' class="filter__chbox-inp"
                                           id="f-pcard"<?= ($_POST['cardPrommu'] ? ' checked' : '') ?>>
                                    <label class="filter__chbox-lab" for="f-pcard">Банковская карта Prommu</label>
                                    <input name='card' type='checkbox' value='1' class="filter__chbox-inp"
                                           id="f-card"<?= ($_POST['card'] ? ' checked' : '') ?>>
                                    <label class="filter__chbox-lab" for="f-card">Банковская карта</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php //    CONTENT 		?>
            <div class='col-xs-12 col-sm-8 col-md-9'>
                <div id="workers-form">
                    <span class="workers-form__cnt">Выбрано соискателей: <span id="mess-wcount">0</span></span>
                    <div class="service__switch">
                        <span class="service__switch-name">Выбрать всех</span>
                        <input type="checkbox" name="ntf-push" id="all-workers" value="1"/>
                        <label for="all-workers">
                            <span data-enable="вкл." data-disable="выкл."></span>
                        </label>
                    </div>
                    <span class="workers-form-btn off" id="workers-btn">НАЗАД</span>
                    <input type="hidden" name="users" id="mess-workers">
                    <input type="hidden" name="users-cnt" id="mess-wcount-inp" value="0">
                </div>
                <div id="promo-content">
                    <div class='questionnaire'>
                        <div>
                            <?= $this->ViewModel->declOfNum($viData['app_count'], array('Найдена', 'Найдено', 'Найдено')) ?>
                            <b><?= $viData['app_count'] ?></b>
                            <?= $this->ViewModel->declOfNum($viData['app_count'], array('Анкета', 'Анкеты', 'Анкет')) ?>
                        </div>
                    </div>
                    <div class='row vacancy table-view'>
                        <? if ($viData['workers']['promo']): ?>
                            <? $i = 1; ?>
                            <? foreach ($viData['workers']['promo'] as $item): ?>
                                <div class='col-xs-12 col-sm-6 col-md-4'>
                                    <?
                                    $G_NOLIKES = 1;
                                    $G_ALT = 'Соискатель ' . $item['firstname'] . ' ' . $item['lastname'] . ' prommu.com';
                                    $G_LOGO_LINK = MainConfig::$PAGE_PROFILE_COMMON . DS . $item['id_user'];
                                    if ($item['sex'] === '1') {
                                        $G_LOGO_SRC = DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$item['photo'] ? MainConfig::$DEF_LOGO : $item['photo'] . '400.jpg');
                                    } else
                                        $G_LOGO_SRC = DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$item['photo'] ? MainConfig::$DEF_LOGO_F : $item['photo'] . '400.jpg');
                                    $G_COMP_FIO = $item['firstname'] . ' ' . $item['lastname'] . ', ' . $item['age'];
                                    $G_RATE_POS = $item['rate'];
                                    $G_RATE_NEG = $item['rate_neg'];
                                    $G_COMMENTS_POS = $item['comm'];
                                    $G_COMMENTS_NEG = $item['commneg'];
                                    $G_TMPL_PH1 = '';
                                    if ($item['ishasavto'] === '1') $G_TMPL_PH1 = "<div class='ico ico-avto js-g-hashint' title='Есть автомобиль'></div>";
                                    if ($item['ismed'] === '1') $G_TMPL_PH1 .= '<div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>';
                                    $G_TMPL_PH1 = "<div class='med-avto'>{$G_TMPL_PH1}</div>";
                                    include $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user' . DS . MainConfig::$VIEWS_COMM_LOGO_TPL . ".php";
                                    ?>
                                    <input type="checkbox" name="promo[]" value="<?= $item['id_user'] ?>"
                                           class="promo_inp" id="promo<?= $item['id_user'] ?>">
                                    <label class="smss-promo__label" for="promo<?= $item['id_user'] ?>"></label>
                                </div>
                                <? if ($i % 2 == 0): ?>
                                    <div class="clear visible-sm"></div>
                                <? endif ?>
                                <? if ($i % 3 == 0): ?>
                                    <div class="clear visible-md visible-lg"></div>
                                <? endif ?>
                                <? $i++; ?>
                            <? endforeach ?>
                        <? else: ?>
                            <div class="col-xs-12">Нет подходящих соискателей</div>
                        <? endif; ?>
                    </div>
                    <br>
                    <br>
                    <div class='paging-wrapp hidden-xs'>
                        <?php
                        $this->widget('CLinkPager', array(
                                'pages' => $viData['pages'],
                                'htmlOptions' => array('class' => 'paging-wrapp'),
                                'firstPageLabel' => '1',
                                'prevPageLabel' => 'Назад',
                                'nextPageLabel' => 'Вперед',
                                'header' => ''
                            )
                        ) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="invitation" class="project__module">
        <h2 class="project__title">ПРИГЛАСИТЬ В ПРОЕКТ <span></span></h2>
        <div class="project__body project__body-invite invitation-item" data-id="0">
            <span class="invitation-del">&#10006</span>
            <div>
                <input type="text" name="inv-name[0]" placeholder="Имя" class="invite-inp name">
            </div>
            <div>
                <input type="text" name="inv-sname[0]" placeholder="Фамилия" class="invite-inp sname">
            </div>
            <div>
                <input type="text" name="inv-phone[0]" placeholder="Телефон" class="invite-inp phone">
            </div>
            <div>
                <input type="text" name="inv-email[0]" placeholder="E-mail" class="invite-inp email">
            </div>
        </div>
        <div class="project__all-btns">
            <div class="project__invite-btns">
                <span class="project__btn-white" id="add-prsnl-btn" data-event="main">+ДОБАВИТЬ ЕЩЕ ПЕРСОНАЛ</span>
                <span class="save-btn" id="save-prsnl-btn" data-event="main">СОХРАНИТЬ</span>
            </div>
        </div>
    </div>
</form>
