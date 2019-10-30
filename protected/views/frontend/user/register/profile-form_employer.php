<?php
$gcs = Yii::app()->getClientScript();

$gcs->registerCssFile(MainConfig::$CSS . 'phone-codes/style.css');
Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-prof-emp.css');
$gcs->registerCssFile(MainConfig::$CSS . 'private/page-edit-prof-emp.css');
$gcs->registerCssFile(MainConfig::$CSS .'register/complete-reg.css');

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
          <img src="<?=Share::getPhoto(
            $viData['userInfo']['id_user'],
            UserProfile::$EMPLOYER,
            $viData['userInfo']['logo'],
            'medium'
          );?>" alt="Работодатель <?=$viData['userInfo']['name']?> prommu.com">
        </div>
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
<div class="col-xs-12 col-sm-7 col-lg-9">
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