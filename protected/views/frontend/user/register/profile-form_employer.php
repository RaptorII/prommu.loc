<?php
$gcs = Yii::app()->getClientScript();

$gcs->registerCssFile(MainConfig::$CSS . 'phone-codes/style.css');
Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-prof-emp.css');
$gcs->registerCssFile(MainConfig::$CSS .'register/complete-reg.css');
$gcs->registerCssFile(MainConfig::$CSS . 'private/page-edit-prof-emp.css');

$gcs->registerScriptFile(MainConfig::$JS . 'phone-codes/script.js', CClientScript::POS_END);
$gcs->registerScriptFile(MainConfig::$JS . 'private/page-edit-prof-emp.js', CClientScript::POS_END);
?>

<?
/**
 * photoblock
 */
?>

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
/**
 * end photoblock
 */
?>

<?php
/**
 * form
 *
 */
?>
<div class="col-xs-12 col-sm-6 col-lg-9">
    <div class="complete__reg">
      <form action='/user/editprofile' id='F1compprof' method='post' class="edit-profile-employer">

        <p class="complete__head center">
          Необходимо активировать аккаунт
        </p>
        <p class="complete__txt center">
          Чтобы получить доступ к новым возможностям - укажите данные
        </p>

        <div class="row">
          <div class="col-xs-12">
            <div class="epe-data__title"><h2>ОСНОВНАЯ ИНФОРМАЦИЯ</h2></div>
            <div class="epe-data__module">
              <label class="epe__label">
                <span class="epe__label-name">Компания:</span>
                <input type='text' name='name' value="<?= $viData['userInfo']['name'] ?>"
                       class="epe__input epe__input-name epe__required" autocomplete="off">
              </label>
              <div class="epe__label epe__select">
                <?php
                $strInp = '';
                $typeName = '';
                foreach ($viData['userAllInfo']['cotype'] as $t) {
                  $strInp .= '<li><input type="radio" name="companyType" value="' . $t['id'] . '" id="type' . $t['id'] . '"';
                  if ($_GET['position'] == $t['id']) {
                    $strInp .= ' checked';
                    $typeName = $t['name'];
                  } elseif ($t['selected']) {
                    $strInp .= ' checked';
                    $typeName = $t['name'];
                  }
                  $strInp .= '><label for="type' . $t['id'] . '">' . $t['name'] . '</label></li>';
                }
                ?>
                <span class="epe__label-name">Тип компании:</span>
                <span class="epe__input epe__input-type" id="epe-str-type"><?= $typeName ?></span>
                <div class="epe__label-veil" id="epe-veil-type"></div>
                <ul class="epe__select-list" id="epe-list-type"><i
                    class="epe__select-list-icon">ОК</i><?= $strInp ?></ul>
              </div>
              <div class="epe__label city-field">
                <span class="epe__label-name">Город:</span>
                <span class="city-select"><?= $viData['userAllInfo']['userCities'][0]['name'] ?><b></b></span>
                <input type='text' name='str-city' value="<?= $viData['userAllInfo']['userCities'][0]['name'] ?>"
                       class="epe__input epe__input-city" autocomplete="off">
                <input type="hidden" name="cities[]" value="<?= $viData['userAllInfo']['userCities'][0]['id_city'] ?>"
                       id="id-city">
                <ul class="city-list"></ul>
              </div>

            </div>
            <?
            //
            ?>
            <div class="epe-data__title"><h2>КОНТАКТНАЯ ИНФОРМАЦИЯ</h2></div>
            <div class="epe-data__module">
              <label class="epe__label">
                <span class="epe__label-name">Ваше имя:</span>
                <input type='text' name='fname' value="<?= $viData['userAllInfo']['emplInfo']['firstname'] ?>"
                       class="epe__input epe__input-fname epe__required" autocomplete="off">
              </label>
              <label class="epe__label">
                <span class="epe__label-name">Ваша фамилия:</span>
                <input type='text' name='lname' value="<?= $viData['userAllInfo']['emplInfo']['lastname'] ?>"
                       class="epe__input epe__input-lname" autocomplete="off">
              </label>
              <label class="epe__label">
                <span class="epe__label-name">Контактное лицо:</span>
                <input type='text' name='contact' value="<?= $viData['userAllInfo']['emplInfo']['contact'] ?>"
                       class="epe__input epe__input-contact epe__required" autocomplete="off">
              </label>

              <label class="epe__label epe__email" data-error="Указанный e-mail адрес уже используется в системе"
                     for="epe-email">
                <span class="epe__label-name">Ваш email:</span>
                <input type='text' name='email' value="<?= $viData['userAllInfo']['emplInfo']['email'] ?>"
                       class="epe__input epe__input-mail epe__required" id="epe-email" autocomplete="off">
                <span class="epe__confirm<?= ($viData['userAllInfo']['emplInfo']['confirmEmail'] && !empty($viData['userAllInfo']['emplInfo']['email']) ? ' complete' : '') ?>"
                      id="conf-email">
                                <?php if ($viData['userAllInfo']['emplInfo']['confirmEmail'] && !empty($viData['userAllInfo']['emplInfo']['email'])): ?>
                                  <p>Почта подтверждена.</p>
                                <?php else: ?>
                                  <p>Почта не подтверждена. <em>Подтвердить</em></p>
                                <?php endif; ?>
                                </span>
              </label>
              <div class="epe__confirm-block" id="conf-email-block">
                <span class="epe__confirm-text">На Вашу почту выслан код для подтверждения. Введите его в это поле!</span>
                <label class="epe__label">
                  <span class="epe__label-name">Проверочный код:</span>
                  <input type='text' name='confirm-code' value="" class="epe__input" id="conf-email-inp"
                         maxlength="6" autocomplete="off">
                </label>
                <div class="epe__confirm-btn hvr-sweep-to-right btn__orange">ПРОВЕРИТЬ</div>
                <div class="clearfix"></div>
              </div>
              <div class="epe__label">
                <span class="epe__label-name epe__phone-name">Телефон:</span>
                <input type='text' name='user-attribs[mob]' value="<?= substr($viData['userAllInfo']['userAttribs'][1]['val'], 2,20) ?>"
                       class="epe__input epe__phone epe__input-phone" id="phone-code" autocomplete="off">
                <span class="epe__confirm<?= ($viData['info']['confirmPhone'] ? ' complete' : '') ?>"
                      id="conf-phone">
                                <?php if (!$viData['info']['confirmPhone']): ?>
                                  <p>Телефон не подтвержден. <em>Подтвердить</em></p>
                                <?php else: ?>
                                  <p>Телефон подтвержден.</p>
                                <?php endif; ?>
                                </span>
              </div>
              <div class="epe__confirm-block" id="conf-phone-block">
                <span class="epe__confirm-text">На Ваш телефон выслан код для подтверждения. Введите его в это поле!</span>
                <label class="epe__label">
                  <span class="epe__label-name">Проверочный код:</span>
                  <input type='text' name='confirm-code' value="" class="epe__input" id="conf-phone-inp"
                         maxlength="6" autocomplete="off">
                </label>
                <div class="epe__confirm-btn hvr-sweep-to-right btn__orange">ПРОВЕРИТЬ</div>
                <div class="clearfix"></div>
              </div>

              <p class="complete__txt center">
                После активации вам станет доступен каталог всех соискателей со всеми функциями
              </p>

              <div class="center">
                <button class='epe__btn prmu-btn prmu-btn_normal' type='submit'>
                  <span>Активировать профиль</span>
                </button>
              </div>
              <input type="hidden" name="savest" value="1"/>
            </div>
          </div>
        </div>

      </form>
    </div>
</div>
<?php
/**
 * end form
 *
 */
?>