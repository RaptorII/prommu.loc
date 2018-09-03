<?php
    $pLink = MainConfig::$PAGE_PROJECT_LIST . '/' . $project;
    $bUrl = Yii::app()->baseUrl;

    Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item.css');
    Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/additional.js', CClientScript::POS_END);
    Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/phone-codes/projects.js', CClientScript::POS_END);
    Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/get-personal.js', CClientScript::POS_END);
    Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/new.css');
    Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/phone-codes/style.css');
    Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/item-staff.css');

    /***********UNIVERSAL FILTER************/
    Yii::app()->getClientScript()->registerScriptFile($bUrl . '/theme/js/projects/universal-filter.js', CClientScript::POS_END);
    Yii::app()->getClientScript()->registerCssFile($bUrl . '/theme/css/projects/universal-filter.css');
    /***********UNIVERSAL FILTER************/

$arFilterData = [
    'ID' => $project, //Обязательное свойство!
    'FILTER_ADDITIONAL_VALUE' => [
        'SECTION_ID' => Yii::app()->request->getParam('section')
    ],
    'FILTER_SETTINGS' => [
        0 => [
            'NAME' => 'Имя',
            'TYPE' => 'text',
            'INPUT_NAME' => 'fname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        1 => [
            'NAME' => 'Статус',
            'TYPE' => 'select',
            'INPUT_NAME' => 'status',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Подтверждено',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Не подтверждено',
                    'id' => '2'
                ]

            ],
            'DATA_DEFAULT' => '0'
        ],
        2 => [
            'NAME' => 'Город',
            'TYPE' => 'select',
            'INPUT_NAME' => 'city',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ]
            ],
            'DATA_DEFAULT' => '0',
            'CONDITION' => [
                'BLOCKED' => 'true',
                'PARENT_ID' => '4',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '1',
                    1 => '2'
                ]
            ]
        ],
        3 => [
            'NAME' => 'Фамилия',
            'TYPE' => 'text',
            'INPUT_NAME' => 'lname',
            'DATA' => [],
            'DATA_DEFAULT' => '',
            'PLACEHOLDER' => ''
        ],
        4 => [
            'NAME' => 'Привязка к адресу',
            'TYPE' => 'select',
            'INPUT_NAME' => 'point',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ],
                1 => [
                    'title' => 'Привязан',
                    'id' => '1'
                ],
                2 => [
                    'title' => 'Не привязан',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '0'
        ],
        5 => [
            'NAME' => 'Название ТТ',
            'TYPE' => 'select',
            'INPUT_NAME' => 'tt_name',
            'DATA' => [
                0 => [
                    'title' => 'Название 1',
                    'id' => '1'
                ],
                1 => [
                    'title' => 'Название 2',
                    'id' => '0'
                ],
                2 => [
                    'title' => 'Все',
                    'id' => '2'
                ]
            ],
            'DATA_DEFAULT' => '2',
            'CONDITION' => [
                'BLOCKED' => 'true',
                'PARENT_ID' => '4',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '1',
                    1 => '2'
                ]
            ]
        ],
        6 => [
            'TYPE' => 'block',
        ],
        7 => [
            'TYPE' => 'block',
        ],
        8 => [
            'NAME' => 'Адрес ТТ',
            'TYPE' => 'select',
            'INPUT_NAME' => 'tt_location',
            'DATA' => [
                0 => [
                    'title' => 'Адрес ТТ 1',
                    'id' => '1'
                ],
                1 => [
                    'title' => 'Адрес ТТ 2',
                    'id' => '0'
                ],
                2 => [
                    'title' => 'Адрес ТТ 3',
                    'id' => '2'
                ],
                3 => [
                    'title' => 'Все',
                    'id' => '3'
                ]
            ],
            'DATA_DEFAULT' => '2',
            'CONDITION' => [
                'BLOCKED' => 'true',
                'PARENT_ID' => '4',
                'PARENT_VALUE' => '',
                'PARENT_VALUE_ID' => [
                    0 => '1',
                    1 => '2'
                ]
            ]
        ],
        9 => [
            'TYPE' => 'block',
        ],
        10 => [
            'TYPE' => 'block',
        ],
        11 => [
            'NAME' => 'Метро',
            'TYPE' => 'select',
            'INPUT_NAME' => 'metro',
            'DATA' => [
                0 => [
                    'title' => 'Все',
                    'id' => '0'
                ]
            ]
        ]
    ],
];
foreach ($viData['filter']['cities'] as $id => $v)
    $arFilterData['FILTER_SETTINGS'][2]['DATA'][$id] = ['title' => $v['city'],'id' => $id];
foreach ($viData['filter']['metros'] as $id => $v)
    $arFilterData['FILTER_SETTINGS'][11]['DATA'][$id] = ['title' => $v['metro'],'id' => $id];
?>

<pre style="height:100px;cursor:pointer" onclick="$(this).css({height:'inherit'})">
<? print_r($viData); ?>
</pre>


<div class="row project">
    <div class="col-xs-12">
        <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/projects/project-nav.php'; ?>
    </div>
</div>

<div class="filter__veil"></div>

<form action="" method="POST" id="update-person">
    <div id="main" class="project__module">


        <div class="prommu__universal-filter__buttoncontainer">
            <span class="prommu__universal-filter__button">ФИЛЬТР ДЛЯ ПЕРСОНАЛА</span>
        </div>
        <div class="project__header">
            <div class="project__header-filter prommu__universal-filter" style="display:none">


                <? foreach ($arFilterData['FILTER_SETTINGS'] as $key => $value): ?>

                    <?
                    if (count($value['CONDITION']['PARENT_VALUE_ID']) > 1):
                        for ($i = 0; $i < count($value['CONDITION']['PARENT_VALUE_ID']); $i++):
                            if ($i == 0) {
                                $parentValueId = $value['CONDITION']['PARENT_VALUE_ID'][$i];
                            } else {
                                $parentValueId .= "," . $value['CONDITION']['PARENT_VALUE_ID'][$i];
                            }
                        endfor;
                    else:
                        $parentValueId = $value['CONDITION']['PARENT_VALUE_ID'];
                    endif; ?>

                    <? switch ($value['TYPE']):
                        case 'text':
                            ?>
                            <div data-type="<?= $value['TYPE'] ?>"
                                 data-id="<?= $key ?>"
                                 data-parent-id="<?= $value['CONDITION']['PARENT_ID'] ?>"
                                 data-parent-value="<?= $value['CONDITION']['PARENT_VALUE'] ?>"
                                 data-parent-value-id="<?= $parentValueId ?>"
                                 class="u-filter__item u-filter__item-<?= $key ?>  <?= ($value['CONDITION']['BLOCKED']) ? 'blocked' : '' ?>">
                                <div class="u-filter__item-title">
                                    <?= $value['NAME']; ?>
                                </div>
                                <div class="u-filter__item-data">
                                    <input
                                            placeholder="<?= $value['PLACEHOLDER'] ?>"
                                            class="u-filter__text"
                                            type="text"
                                            name="<?= $value['INPUT_NAME']; ?>"
                                    />
                                    <input
                                            type="hidden"
                                            class="u-filter__hidden-default"
                                            value="<?= $value['DATA_DEFAULT'] ?>"
                                    />
                                </div>
                            </div>
                            <?
                            break;
                        case 'select':
                            ?>
                            <div data-type="<?= $value['TYPE'] ?>"
                                 data-id="<?= $key ?>"
                                 data-parent-id="<?= $value['CONDITION']['PARENT_ID'] ?>"
                                 data-parent-value="<?= $value['CONDITION']['PARENT_VALUE'] ?>"
                                 data-parent-value-id="<?= $parentValueId ?>"
                                 class="u-filter__item u-filter__item-<?= $key ?> <?= ($value['CONDITION']['BLOCKED']) ? 'blocked' : '' ?>">
                                <div class="u-filter__item-title">
                                    <?= $value['NAME']; ?>
                                </div>
                                <div class="u-filter__item-data">
                                    <span class="u-filter__select"></span>
                                    <ul class="u-filter__ul-hidden">
                                        <? foreach ($value['DATA'] as $d_key => $d_value): ?>
                                            <li class="u-filter__li-hidden"
                                                data-id="<?= $d_value['id']; ?>"><?= $d_value['title']; ?></li>
                                        <? endforeach; ?>
                                    </ul>
                                    <input
                                            type="hidden"
                                            name="<?= $value['INPUT_NAME'] ?>"
                                            class="u-filter__hidden-data"
                                            value="<?= $value['DATA_DEFAULT'] ?>"
                                    />
                                    <input
                                            type="hidden"
                                            class="u-filter__hidden-default"
                                            value="<?= $value['DATA_DEFAULT'] ?>"
                                    />
                                </div>
                            </div>
                            <?
                            break;
                        case 'block':
                            ?>
                            <div data-type="<?= $value['TYPE'] ?>"
                                 class="u-filter__item u-filter__item-<?= $key ?> u-filter__blockitem"></div>
                            <?
                            break;
                    endswitch; ?>
                <? endforeach; ?>



                <? /*if (isset($arFilterData['ID']) && !empty($arFilterData['ID'])): ?>
                    <input type="hidden" name="id" value="<?= $arFilterData['ID'] ?>"/>
                <? endif;*/ ?>
                <? if (count($arFilterData['FILTER_ADDITIONAL_VALUE']) > 0): ?>
                    <? foreach ($arFilterData['FILTER_ADDITIONAL_VALUE'] as $addKey => $addValue): ?>
                        <input type="hidden" name="<?= $addKey ?>" value="<?= $addValue ?>"/>
                    <? endforeach; ?>
                <? endif; ?>
            </div>
        </div>


        <div class="project__control-panel">

            <div class="project__header-xlscontainer">
                <div class="project__header-xls project__xls">
                    <a href="/user/uploadprojectxls?id=<?= $project ?>&type=users" class="project__header-addxls"
                       id="add-program">Изменить текущий персонал</a>
                    <a class="xlscontainer-child" href="/uploads/promo_import.xls" download>Скачать пример для добавления</a>
                    <a href="/uploads/promo_import.xls" download>Скачать текущий персонал</a>
                    <a class="xlscontainer-child" href="/user/uploadprojectxls?id=<?= $project ?>&type=users" class="project__header-addxls"
                       id="add-program">Добавить новый персонал</a>


                </div>
            </div>

            <div class="program__btns control__buttons">

                <span class="control__add-container">
                    <span id="control__add-all" class="control__add-btn">+ ДОБАВИТЬ ПЕРСОНАЛ</span>
                    <ul class="control__add-ul">
                        <li id="control__add-personal">добавить из базы</li>
                        <li id="control__new-personal">пригласить</li>
                    </ul>
                </span>

                <? /*<button type="submit" id="control__save-btn" class="program__save-btn">ПРИМЕНИТЬ</button>*/ ?>
            </div>
        </div>

        <h1 class="project__title personal__title">ПЕРСОНАЛ</h1>
        <div id="staff-content">
            <?php require __DIR__ . '/project-staff-ajax.php'; ?>
        </div>
    </div>


    <? /****************************Скрытые блоки приглашения персонала****************************/ ?>
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
                    <span class="workers-form-btn off" id="workers-btn">СОХРАНИТЬ</span>
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
        <h2 class="project__title">ПРИГЛАСИТЬ В ПРОЕКТ </h2>
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
                <span class="save-btn" id="save-prsnl-cancel">ОТМЕНИТЬ</span>
            </div>
        </div>
    </div>
</form>

<form enctype="multipart/form-data" action="" method="POST" id="base-form">
    <input type="hidden" name="project" class="project-inp" value="<?= $project ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="5242880"/>
    <input type="file" name="xls" id="add-xls-inp" class="hide">
    <input type="hidden" name="xls-users" value="1">
</form>
<div class="hidden" id="invitation-content">
    <div class="project__body project__body-invite invitation-item" data-id="">
        <span class="invitation-del">&#10006</span>
        <div>
            <input type="text" name="" placeholder="Имя" class="invite-inp name">
        </div>
        <div>
            <input type="text" name="" placeholder="Фамилия" class="invite-inp sname">
        </div>
        <div>
            <input type="text" name="" placeholder="Телефон" class="invite-inp phone">
        </div>
        <div>
            <input type="text" name="" placeholder="E-mail" class="invite-inp email">
        </div>
    </div>
</div>
