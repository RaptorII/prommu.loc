<meta name="robots" content="noindex,nofollow">
<?
$id = $viData['userInfo']['id_user'];
Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-prof-emp.css');
Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/page-prof-emp.js', CClientScript::POS_END);

if(!in_array(Share::$UserProfile->type, [2,3])): ?>
<?
$title = 'Профиль работодателя - ' . $viData['userInfo']['name'];
$this->pageTitle = $title;
?>
</div> <?// content-block?>
<h1 class="user-profile-page__title"><?=$title?></h1>
</div> <?// container?>
<hr class="user-profile-page__line">
<div class="container" >
    <div class="content-block">
        <? endif; ?>
        <div class='row'>
            <div class='col-xs-12 col-sm-4 col-lg-3 no-md-relat ppe__logo'>
                <div class="upp__img-block">
                    <div class="upp__img-block-main">
                        <?
                        $cookieView = Yii::app()->request->cookies['popup_photo']->value;
                        $bigSrc = Share::getPhoto($id, 3, $viData['userInfo']['logo'], 'big');
                        $src = Share::getPhoto($id, 3, $viData['userInfo']['logo'], 'small');
                        ?>
                        <? if($viData['userInfo']['logo'] && $bigSrc): ?>
                            <a
                                    href="<?=$bigSrc?>"
                                    class="js-g-hashint upp__img-block-main-link profile__logo-full"
                                    title="<?=$viData['userInfo']['name']?>">
                                <img src="<?=$src?>" alt="Работодатель <?=$viData['userInfo']['name']?> prommu.com">
                            </a>
                        <? else: ?>
                            <img src="<?=$src?>" alt="Работодатель <?=$viData['userInfo']['name']?> prommu.com">
                            <?
                            if($flagOwnProfile && !$cookieView) // предупреждение, что нет фоток
                            {
                                Yii::app()->request->cookies['popup_photo'] = new CHttpCookie('popup_photo', 1);
                                $message = '<p>У вас не загружено еще ни одной фотографии.<br>Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</p>';
                                Yii::app()->user->setFlash('prommu_flash', $message);
                            }
                            ?>
                        <? endif; ?>
                        <?if( $flagOwnProfile ):?>
                            <a href="/user/editprofile?ep=1" class="upp__change-logo">Изменить аватар</a>
                        <?php elseif($viData['userInfo']['is_online']): ?>
                        <span class="upp-logo__item-onl"><span>В сети</span>
              <?php endif; ?>
                    </div>
                </div>
                <div class="upp__logo-more">
                    <? $i=0; ?>
                    <? foreach ($viData['userPhotos'] as $key => $val): ?>
                        <?
                        $bigSrc = Share::getPhoto($id, 3, $val['photo'], 'big');
                        $src = Share::getPhoto($id, 3, $val['photo'], 'small');
                        if(!$val['photo'] || !$bigSrc)
                            continue;
                        ?>
                        <div class="upp__img-block-more <?=($i>2?'off':'')?>">
                            <a href="<?=$bigSrc?>" class="profile__logo-full">
                                <img
                                        src="<?=$src?>"
                                        alt="Соискатель <?=$viData['userInfo']['name']?> prommu.com">
                            </a>
                        </div>
                        <? if($i==3): ?>
                            <span class="upp-logo-more__link">Смотреть еще</span>
                        <? endif; ?>
                        <? $i++; ?>
                    <? endforeach; ?>
                    <div class="clearfix"></div>
                </div>
                <? if(!$flagOwnProfile): ?>
                    <div class="upp-logo-main__active">
                        <span class="disable"><b>На сайте:</b> <?=$viData['userInfo']['time_on_site']?></span>
                    </div>
                    <div class="upp-logo-main__active">
                        <?if(!$viData['userInfo']['is_online']):?>
                            <span class="disable">Был(а) на сервисе: <?=date_format(date_create($viData['userInfo']['mdate']), 'd.m.Y');?></span>
                        <?endif;?>
                    </div>
                <? endif; ?>
                <div class='center-box'>
                    <?php if(!$flagOwnProfile && ($viData['userAllInfo']['emplInfo']['confirmEmail'] || $viData['userAllInfo']['emplInfo']['confirmPhone'])): ?>
                        <div class="confirmed-user js-g-hashint" title="Личность работодателя является подлинной">ПРОВЕРЕН</div>
                    <?php endif; ?>
                    <?php if( $flagOwnProfile ): ?>
                        <a class='ppe__logo-btn prmu-btn' href='<?= MainConfig::$PAGE_EDIT_PROFILE ?>'><span>Редактировать профиль</span></a>
                        <a class='ppe__logo-btn prmu-btn' href='<?= MainConfig::$PAGE_SETTINGS ?>'><span>Настройки профиля</span></a>
                        <a class='ppe__logo-btn prmu-btn' href='<?= MainConfig::$PAGE_CHATS_LIST ?>'><span>Мои сообщения</span></a>

                        <?/*efficiency*/?>
                        <div class='affective-block'>
                            <div class='affective-perc'>
                                <div class='progr' style="width: <?= $viData['efficiency'] ?>%">
                                    <div class='text'><?= $viData['efficiency'] ?>%</div>
                                </div>
                            </div>

                            <div class="affective__wrap">
                                <div class='affective'>Эффективность<br/>размещения</div>

                                <?php
                                if  ($viData['efficiency'] != 100) {
                                    ?>
                                    <span class="question_popup prmu-btn prmu-btn_normal"><span>?</span></span>
                                    <div class="prmu__popup popup__msg" >
                                        <h3>Уважаемый работодатель!</h3>
                                        <p>Для эффективного размещения профиля необходимо заполнить его в полном объёме.</p>
                                        <p>Информация по незаполнению данных в личном профиле для 100% эффективности.</p>

                                        <ul>
                                            <? if (empty($viData['userInfo']['logo']) ||
                                                (count($viData['userPhotos']) < 1)) { ?>
                                                <h4>Фото</h4>
                                            <? }
                                            if (empty($viData['userInfo']['logo'])) { ?>
                                                <li> Основное - 10% </li>
                                            <? }
                                            if (count($viData['userPhotos']) < 1) { ?>
                                                <li> Дополнительные - 10% </li>
                                            <? }

                                            if (empty($viData['userAllInfo']['emplInfo']['name'])        ||
                                                empty($viData['userAllInfo']['emplInfo']['type'])        ||
                                                empty($viData['userAllInfo']['userCities']['0']['name']) ||
                                                empty($viData['userAllInfo']['userAttribs']['99']['val'])) {
                                                echo '<h4>Основная информация</h4>';
                                            }
                                            if (empty($viData['userAllInfo']['emplInfo']['name'])) { ?>
                                                <li> Название компании - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['emplInfo']['type'])) { ?>
                                                <li> Тип компании - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['userCities']['0']['name'])) { ?>
                                                <li> Город - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['userAttribs']['99']['val'])) { ?>
                                                <li> Сайт - 5% </li>
                                            <? }

                                            if (empty($viData['userAllInfo']['emplInfo']['firstname'])     ||
                                                empty($viData['userAllInfo']['emplInfo']['lastname'])      ||
                                                empty($viData['userAllInfo']['emplInfo']['contact'])       ||
                                                empty($viData['userAllInfo']['userAttribs']['176']['val']) ||
                                                empty($viData['userAllInfo']['userAttribs']['177']['val']) ||
                                                empty($viData['userAllInfo']['emplInfo']['email'])         ||
                                                empty($viData['userAllInfo']['userAttribs']['175']['val']) ||
                                                empty($viData['userAllInfo']['userAttribs']['100']['val'])
                                            ) {
                                                echo '<h4>Контактная информация</h4>';
                                            }

                                            if (empty($viData['userAllInfo']['emplInfo']['firstname'])) { ?>
                                                <li> Имя -5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['emplInfo']['lastname'])) { ?>
                                                <li> Фамилия - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['emplInfo']['contact'])) { ?>
                                                <li> Контактное лицо - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['userAttribs']['176']['val'])) { ?>
                                                <li> ИНН - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['userAttribs']['177']['val'])) { ?>
                                                <li> Юридический адрес - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['emplInfo']['email'])) { ?>
                                                <li> Е-меил - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['userAttribs']['1']['val'])) { ?>
                                                <li> Телефон - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['userAttribs']['175']['val'])) { ?>
                                                <li> Городской телефон - 5% </li>
                                            <? }
                                            if (!empty($viData['userAllInfo']['userAttribs']['157']['val']) ||
                                                !empty($viData['userAllInfo']['userAttribs']['4']['val']) ||
                                                !empty($viData['userAllInfo']['userAttribs']['5']['val']) ||
                                                !empty($viData['userAllInfo']['userAttribs']['156']['val']) ||
                                                !empty($viData['userAllInfo']['userAttribs']['158']['val'])) {
                                            } else {
                                                echo '<li> Мессенджеры (хотя бы один) - 5% </li>';
                                            }
                                            if (empty($viData['userAllInfo']['userAttribs']['100']['val'])) { ?>
                                                <li> Должность - 5% </li>
                                            <? }
                                            if (empty($viData['userAllInfo']['emplInfo']['aboutme'])) { ?>
                                                <h4>О компании - 10% </h4>
                                            <? } ?>
                                        </ul>
                                    </div>
                                <? } ?>

                          </div>
                        </div>

                        <div class='affective-block'>
                            <div class='affective'>Инструкция пользования сервисом PROMMU</div>
                            <a class='ppp__btn btn__orange' href='/theme/pdf/Instruction-PROMMU-com.pdf' style="margin-bottom: 10px" target="_blank">Скачать</a>
                        </div>

                        <?/**/?>

                    <? endif; ?>
                    <? if(Share::isApplicant()): ?>
                        <? if(Share::$UserProfile->hasAccessToChat($id)): ?>
                            <div class="center">
                                <h3 class='unpubl'>Есть доступные чаты с этим работодателем</h3>
                                <a href="<?=MainConfig::$PAGE_CHATS_LIST_VACANCIES?>" class="prmu-btn prmu-btn_normal">
                                    <span>Перейти в чаты</span>
                                </a>
                            </div>
                        <? else: ?>
                            <h3 class='unpubl'>Сообщения можно писать только, при одобрении работодателем на опубликованной им вакансии</h3>
                        <? endif; ?>
                    <? endif; ?>
                </div>
            </div>
            <?
            /*
            *     parameters
            */
            ?>
            <div class='col-xs-12 col-sm-8 col-lg-9 ppe__content'>
                <h2 class="upp__title"><?=$viData['userInfo']['name']?></h2>
                <div class="upp__rating-block">
                    <span class="upp__subtitle">Общий рейтинг</span>
                    <ul class="upp__star-block">
                        <li class="full"></li>
                    </ul>
                    <div class="upp__subtitle"><?=Share::getRating($viData['userInfo']['rate'],$viData['userInfo']['rate_neg'])?></div><br/>
                </div>
                <hr class="upp__line">
                <table class="upp__table">
                    <tbody>
                    <?php foreach ($viData['rating']['pointRate'] as $key => $val): ?>
                        <tr>
                            <td class="upp__table-name">
                                <span><?=$viData['rating']['rateNames'][$key]?></span>
                            </td>
                            <td class="upp__table-cnt">
                                <span class="upp__table-cnt-plus js-g-hashint" title="Положительная оценка"><?=$val[0]?></span>
                            </td>
                            <td class="upp__table-cnt">
                                <span class="upp__table-cnt-zero js-g-hashint" title="Нейтральная оценка">0</span>
                            </td>
                            <td class="upp__table-cnt">
                                <span class="upp__table-cnt-minus js-g-hashint" title="Отрицательная оценка"><?=$val[1]?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if(sizeof($viData['lastJobs']['jobs'])>0): ?>
                    <hr class="upp__line">
                    <span class="upp__subtitle">Размещенные вакансии <?=$viData['lastJobs']['count']?></span>
                    <hr class="upp__line">
                    <?php foreach ($viData['lastJobs']['jobs'] as $vacancy): ?>
                        <div class='upp__project-item'>
                            <div class="upp__project-info">
                                <a class='upp__project-vacancy' href='<?= MainConfig::$PAGE_VACANCY . DS . $vacancy['id'] ?>'><?= $vacancy['title'] ?></a>
                                <span class="dates">(<?= $vacancy['crdate'] . ' - ' . $vacancy['remdate'] ?>)</span>
                            </div>
                            <a href="<?=MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $vacancy['id'] ?>" class="upp__project-item-messages js-g-hashint" title="Обратная связь" style="color:#212121"><?=$vacancy['discuss_cnt']?></a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class='vacancies-block'>
                    <div class='vacancies'>
                        <?php foreach ($viData['lastResp']['jobs'] as $val): ?>
                            <b>
                                <span><?= $val['cou'] ?></span>
                                <a class='black-green' href='?p=company-response-list&id=<?= $val['id'] ?>'><?= $val['name'] ?></a>
                            </b>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?
                //    INFO
                ?>
                <?php

                //display($viData);

                $allInfo = $viData['userAllInfo']['emplInfo'];
                $allAttr = $viData['userAllInfo']['userAttribs'];
                $isBlocked = Share::$UserProfile->exInfo->isblocked==3;

                ?>
                <br>
                <div class="ppe__module-title"><h2>ОСНОВНАЯ ИНФОРМАЦИЯ</h2></div>
                <div class="ppe__module">
                    <div class="ppe__field<?=($isBlocked && !$allInfo['name'] ?' error':'')?>">
                        <span class="ppe__field-name">Название компании:</span>
                        <span class="ppe__field-val"><?=$allInfo['name']?></span>
                    </div>
                    <?php
                    $id = $this->ViewModel->isInArray($viData['userAllInfo']['cotype'], 'id', $allInfo['type']);
                    if($id>=0): ?>
                        <div class="ppe__field">
                            <span class="ppe__field-name">Тип компании:</span>
                            <span class="ppe__field-val"><?=$viData['userAllInfo']['cotype'][$id]['name']?></span>
                        </div>
                    <?php endif; ?>
                    <div class="ppe__field<?=($isBlocked && !count($viData['cities_names']) ?' error':'')?>">
                        <span class="ppe__field-name">Город:</span>
                        <span class="ppe__field-val"><?=implode(', ', $viData['cities_names']);?></span>
                    </div>
                    <?php if(strlen($allAttr[99]['val'])>0): ?>
                        <div class="ppe__field">
                            <span class="ppe__field-name">Web Сайт:</span>
                            <span class="ppe__field-val"><?=$allAttr[99]['val']?></span>
                        </div>
                    <?php endif; ?>
                    <?php if(strlen($allInfo['aboutme'])): ?>
                        <div class="ppe__field ppe__about">
                            <span class="ppe__field-name">О компании:</span>
                            <div class="ppe__field-val"><?=$allInfo['aboutme']?></div>
                        </div>
                    <?php endif; ?>
                </div>
                <br>
                <?php if($flagOwnProfile): // инфа для владельца ?>
                    <div class="ppe__module-title"><h2>КОНТАКТНАЯ ИНФОРМАЦИЯ</h2></div>
                    <div class="ppe__module">
                        <?php if(strlen($allInfo['firstname'])>0): ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Имя:</span>
                                <span class="ppe__field-val"><?=$allInfo['firstname']?></span>
                            </div>
                        <?php endif; ?>
                        <?php if(strlen($allInfo['lastname'])>0): ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Фамилия:</span>
                                <span class="ppe__field-val"><?=$allInfo['lastname']?></span>
                            </div>
                        <?php endif; ?>
                        <?php if(strlen($allInfo['contact'])>0): ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Контактное лицо:</span>
                                <span class="ppe__field-val"><?=$allInfo['contact']?></span>
                            </div>
                        <?php endif; ?>
                        <? $attrVal = $this->ViewModel->isInArray($allAttr, 'key', 'inn'); ?>
                        <?php if(isset($allAttr[$attrVal]['val']) && $allAttr[$attrVal]['val'] !== '') : ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">ИНН:</span>
                                <span class="ppe__field-val"><?=$allAttr[$attrVal]['val']?></span>
                            </div>
                        <? endif; ?>
                        <? $attrVal = $this->ViewModel->isInArray($allAttr, 'key', 'legalindex'); ?>
                        <?php if(isset($allAttr[$attrVal]['val']) && $allAttr[$attrVal]['val'] !== '') : ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Юридический адрес:</span>
                                <span class="ppe__field-val"><?=$allAttr[$attrVal]['val']?></span>
                            </div>
                        <? endif; ?>
                        <div class="ppe__field<?=($isBlocked && !$allInfo['email'] ?' error':'')?>">
                            <span class="ppe__field-name">Email:</span>
                            <span class="ppe__field-val"><?=$allInfo['email']?></span>
                        </div>
                        <div class="ppe__field<?=($isBlocked && !$allAttr[1]['val'] ?' error':'')?>">
                            <span class="ppe__field-name">Телефон:</span>
                            <span class="ppe__field-val"><?=$allAttr[1]['val']?></span>
                        </div>

                        <? $attrVal = $this->ViewModel->isInArray($allAttr, 'key', 'stationaryphone'); ?>
                        <?php if(isset($allAttr[$attrVal]['val']) && $allAttr[$attrVal]['val'] !== '') : ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Городской телефон:</span>
                                <span class="ppe__field-val"><?=$allAttr[$attrVal]['val']?></span>
                            </div>
                        <? endif; ?>

                        <?php
                        $idViber = $this->ViewModel->isInArray($allAttr, 'key', 'viber');
                        $idWhatsApp = $this->ViewModel->isInArray($allAttr, 'key', 'whatsapp');
                        $idTelegram = $this->ViewModel->isInArray($allAttr, 'key', 'telegram');
                        $idGoogleAllo = $this->ViewModel->isInArray($allAttr, 'key', 'googleallo');
                        ?>
                        <?php if(isset($allAttr[$idViber]['val']) && $allAttr[$idViber]['val'] !== '') : ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Viber:</span>
                                <span class="ppe__field-val"><?=$allAttr[$idViber]['val']?></span>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($allAttr[$idWhatsApp]['val']) && $allAttr[$idWhatsApp]['val'] !== '') : ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">WhatsApp:</span>
                                <span class="ppe__field-val"><?=$allAttr[$idWhatsApp]['val']?></span>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($allAttr[$idTelegram]['val']) && $allAttr[$idTelegram]['val'] !== '') : ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Telegram:</span>
                                <span class="ppe__field-val"><?=$allAttr[$idTelegram]['val']?></span>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($allAttr[$idGoogleAllo]['val']) && $allAttr[$idGoogleAllo]['val'] !== '') : ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Google Allo:</span>
                                <span class="ppe__field-val"><?=$allAttr[$idGoogleAllo]['val']?></span>
                            </div>
                        <?php endif; ?>
                        <?php if(strlen($allAttr[100]['val'])>0): ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Должность:</span>
                                <span class="ppe__field-val"><?=$allAttr[100]['val']?></span>
                            </div>
                        <?php endif; ?>
                        <?
                        $isNews = false;
                        foreach ($allAttr as $v)
                            $v['key']=='isnews' && $isNews=$v['val'];
                        ?>
                        <? if($isNews): ?>
                            <div class="ppe__checkbox <?=$isNews ? 'active' : ''?>">Получение новостей об изменениях и новых возможностях на сайте</div>
                        <? endif; ?>
                    </div>
                    <div class="ppe__module">
                        <a class='prmu-btn' href='<?= MainConfig::$PAGE_EDIT_PROFILE ?>'><span>Редактировать профиль</span></a>
                    </div>
                <? endif; ?>
                <? if(Share::$UserProfile->showContactData($viData['userInfo']['id_user'], 'employer')): // вывод данных для С, который сотрудничает ?>
                    <div class="ppe__module-title"><h2>КОНТАКТНАЯ ИНФОРМАЦИЯ</h2></div>
                    <div class="ppe__module">
                        <div class="ppe__field">
                            <span class="ppe__field-name">Email:</span>
                            <span class="ppe__field-val"><?=$allInfo['email']?></span>
                        </div>
                        <div class="ppe__field">
                            <span class="ppe__field-name">Телефон:</span>
                            <span class="ppe__field-val"><?=$allAttr[1]['val']?></span>
                        </div>
                        <?
                        $idViber = $this->ViewModel->isInArray($allAttr, 'key', 'viber');
                        $idWhatsApp = $this->ViewModel->isInArray($allAttr, 'key', 'whatsapp');
                        $idTelegram = $this->ViewModel->isInArray($allAttr, 'key', 'telegram');
                        $idGoogleAllo = $this->ViewModel->isInArray($allAttr, 'key', 'googleallo');
                        ?>
                        <? if(!empty($allAttr[$idViber]['val'])): ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Viber:</span>
                                <span class="ppe__field-val"><?=$allAttr[$idViber]['val']?></span>
                            </div>
                        <? endif; ?>
                        <? if(!empty($allAttr[$idWhatsApp]['val'])): ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">WhatsApp:</span>
                                <span class="ppe__field-val"><?=$allAttr[$idWhatsApp]['val']?></span>
                            </div>
                        <? endif; ?>
                        <? if(!empty($allAttr[$idTelegram]['val'])): ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Telegram:</span>
                                <span class="ppe__field-val"><?=$allAttr[$idTelegram]['val']?></span>
                            </div>
                        <? endif; ?>
                        <? if(!empty($allAttr[$idGoogleAllo]['val'])): ?>
                            <div class="ppe__field">
                                <span class="ppe__field-name">Google Allo:</span>
                                <span class="ppe__field-val"><?=$allAttr[$idGoogleAllo]['val']?></span>
                            </div>
                        <? endif; ?>
                    </div>
                <? endif; ?>
            </div>