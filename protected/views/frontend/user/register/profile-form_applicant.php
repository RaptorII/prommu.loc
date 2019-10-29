<?php



$gcs = Yii::app()->getClientScript();

$gcs->registerCssFile(MainConfig::$CSS . 'phone-codes/style.css');
$gcs->registerCssFile(MainConfig::$CSS . 'private/page-prof-app.css');
$gcs->registerCssFile(MainConfig::$CSS . 'private/page-edit-prof-app.css');
$gcs->registerCssFile(MainConfig::$CSS . 'dist/jquery-ui.min.css');
$gcs->registerCssFile(MainConfig::$CSS . 'register/complete-reg.css');
$gcs->registerCssFile(MainConfig::$CSS . 'jslib/bootstrap-datepicker/css/bootstrap-datepicker.min.css');

$gcs->registerScriptFile(MainConfig::$JS . 'phone-codes/script.js', CClientScript::POS_END);
$gcs->registerScriptFile(MainConfig::$JS . 'private/page-edit-prof-app-reg.js', CClientScript::POS_END);

//
$attr = array_values($viData['userInfo']['userAttribs'])[0];

// city
$arUserCities = $viData['userInfo']['userCities'][0];

$arCities = Yii::app()->db->createCommand()
  ->select('t.id_city id, t.name, t.ismetro, t.id_co')
  ->from('city t')
  ->limit(10000)
  ->queryAll();
$arTemp = array();
foreach ($arCities as $city)
{
  $arTemp[$city['id']] = $city['name'];
}

/*
 *
 */
// city

// оптимизируем массив городов для JS
$arCities = array_unique($arTemp);
asort($arCities);

echo"<pre>";
print_r($arUserCities);
echo"</pre>";

/*
 *
 */

// Телефон
$city = (new Geo())->getUserGeo();
foreach ($viData['countries'] as $c)
{
  $c['id_co']==$city['country'] && $attr['phone-code']=$c['phone'];
}
// email
$attr['email'] = filter_var($attr['email'], FILTER_VALIDATE_EMAIL);

// additional phones
$arAdPhones = array();
foreach ($attrAll as $p)
  if (strpos($p['name'], 'admob') !== false && !empty($p['val']))
    $arAdPhones[] = $p;
?>
<?
/**
 *
 */
?>

<div class="private-profile-page <?=(!$flagOwnProfile?'for-guest':'')?>">
    <?php if( $viData['error'] ): ?>
        <div class="comm-mess-box"><?= $viData['message'] ?></div>
    <?php else: ?>
    <div class="ppp__logo">
        <div class="ppp__logo-main">
            <?
            $cookieView = Yii::app()->request->cookies['popup_photo']->value;
            $bigSrc = Share::getPhoto($attr['id_user'], 2, $attr['photo'], 'big', $attr['isman']);
            $src = Share::getPhoto($attr['id_user'], 2, $attr['photo'], 'medium', $attr['isman']);
            ?>
            <? if($attr['photo'] && $bigSrc): ?>
                <a href="<?=$bigSrc?>" class="js-g-hashint ppp-logo-main__link ppp__logo-full" title="<?=$h1title?>">
                    <img
                            src="<?=$src?>"
                            alt='Соискатель <?=$attr['lastname']?> prommu.com'
                            class="ppp-logo-main__img">
                </a>
            <? else: ?>
                <img
                        src="<?=$src?>"
                        alt='Соискатель <?=$attr['lastname']?> prommu.com'
                        class="ppp-logo-main__img">
                <?
                if($flagOwnProfile && !$cookieView) // предупреждение, что нет фоток
                {
                    Yii::app()->request->cookies['popup_photo'] = new CHttpCookie('popup_photo', 1);
                    $message = '<p>У вас не загружено еще ни одной фотографии.<br>Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</p>';
                    Yii::app()->user->setFlash('prommu_flash', $message);
                }
                ?>
            <? endif; ?>

            <?php if(!$flagOwnProfile && $attr['is_online']): ?>
            <span class="ppp-logo__item-onl"><span>В сети</span>
      <?php endif; ?>
                <?if($flagOwnProfile):?>
                    <a href="<?=MainConfig::$PAGE_EDIT_PROFILE . '?ep=1'?>" class="ppp-logo-main__change">Изменить аватар</a>
                <?endif;?>
        </div>
        <? if(!$flagOwnProfile): ?>
            <div class="ppp-logo-main__active">
                <span class="disable"><b>На сайте:</b> <?=$info['time_on_site']?></span>
            </div>
            <div class="ppp-logo-main__active">
                <?if(!$attr['is_online']):?>
                    <span class="disable">Был<?=$attr['isman']?'':'а'?> на сервисе: <?=date_format(date_create($attr['mdate']), 'd.m.Y');?></span>
                <?endif;?>
            </div>
            <div class="ppp__logo-rating">
                <ul class="ppp__star-block"><li class="full"></li></ul>
                <span class="ppp__subtitle"><?=Share::getRating($attr['rate'],$attr['rate_neg'])?></span>
            </div>
            <?php if($attr['confirmPhone'] || $attr['confirmEmail']): ?>
                <div class="confirmed-user js-g-hashint" title="Личность соискателя является подлинной">ПРОВЕРЕН</div>
            <?php endif; ?>
            <? if(!empty($info['self_employed'])): ?>
                <div class="self_employed-user js-g-hashint" title="Налоговый статус соискателя">САМОЗАНЯТЫЙ</div>
            <? endif; ?>
            <?php if($cntComments): ?>
                <div class="ppp__logo-comm">
                    <span class="ppp__subtitle">Отзывы:</span>
                    <span class="upp__review upp__review-red js-g-hashint" title="Отрицательные отзывы">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link"><?=$viData['lastComments']['count'][1]?></a>
          </span>
                    <span class="upp__review upp__review-green js-g-hashint" title="Положительные отзывы">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link"><?=$viData['lastComments']['count'][0]?></a>
          </span>
                    <span class="ppp__logo-allrev">Всего:</span>
                    <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>"><?=$cntComments?></a>
                </div>
            <?php endif; ?>
        <?endif;?>
        <div class="ppp__logo-more">
            <? $i=0; ?>
            <? foreach($info['userPhotos'] as $v): ?>
                <?
                $bigSrc = Share::getPhoto($attr['id_user'], 2, $v['photo'], 'big', $attr['isman']);
                $src = Share::getPhoto($attr['id_user'], 2, $v['photo'], 'small', $attr['isman']);
                if(!$v['photo'] || !$bigSrc || $v['ismain']) // если фото нет или фото главное
                    continue;
                ?>
                <div class="ppp-logo__item">
                    <a href="<?=$bigSrc?>" class="ppp-logo-item__link ppp__logo-full">
                        <img
                                src="<?=$src?>"
                                alt="Соискатель <?=$attr['lastname']?> prommu.com"
                                class="ppp-logo-item__img">
                    </a>
                </div>
                <?
                $i++;
                if($i==6) break;
                if($i==3) echo '<div class="clearfix"></div>';
                ?>
            <? endforeach; ?>
            <div class="clearfix"></div>
        </div>
        <div class='center-box'>
            <?php if( $flagOwnProfile ): ?>
                <a class='ppp__btn btn__orange' href='<?= MainConfig::$PAGE_EDIT_PROFILE ?>' style="margin-bottom: 10px">Редактировать профиль</a>
                <a class='ppp__btn btn__orange' href='<?= MainConfig::$PAGE_SETTINGS ?>' style="margin-bottom: 10px">Настройки профиля</a>
                <a class='ppp__btn btn__orange' href='<?= MainConfig::$PAGE_CHATS_LIST ?>'>Мои сообщения</a>
            <?php elseif( Share::$UserProfile->type == 3 && $ismoder): ?>
                <div class='js-btn-invite btn-white-green-wr'>
                    <a href='#'>Пригласить на вакансию</a>
                </div>
            <?php endif; ?>
            <?php  if(Share::$UserProfile->type == 3 && !$ismoder):?>
                <div class='btn-update btn-orange-sm-wr'>
                    <a class='hvr-sweep-to-right btn__orange' href='#'>Невозможно отправить сообщение</a>
                    <h3 class='unpubl'>Отправлять сообщения и приглашения на вакансию можно только при успешном прохождении модерации</h3>
                </div>

            <?php endif; ?>


            <?php if( $flagOwnProfile ): ?>
                <div class='affective-block'>
                    <div class='affective-perc'>
                        <div class='progr' style="width: <?= $viData['profileEffect'] ?>%">
                            <div class='text'><?= $viData['profileEffect'] ?>%</div>
                        </div>
                    </div>
                    <div class='affective'>Эффективность<br/>размещения</div>
                </div>
            <?php endif; ?>
            <div class="clearfix"></div>
        </div>
    </div>


<?
/**
 * end
 */
?>




<script type="text/javascript">
  let arCities = <?=json_encode($arCities)?>;
</script>


<?php
/**
 * form
 *
 */
?>
<div class="edit-profile-applicant ">
  <div class="complete__reg">
    <form action='/user/editprofile' method='post' id="epa-edit-form">

      <p class="complete__head center">
        Необходимо активировать свой аккаунт
      </p>
      <p class="complete__txt center">
        Чтобы попасть в базу данных и получить доступ к каталогу вакансий - укажите данные
      </p>

      <div class="epa__content-title"><h2>Основная информация</h2></div>
      <div class="epa__content-module" id="main-module">
        <label class="epa__label epa__firstname">
          <span class="epa__label-name">Имя:</span>
          <input type="text" name="name" value="<?= trim($attr['firstname']) ?>"
                 class="epa__input epa__required" data-name="Имя">
        </label>
        <label class="epa__label epa__lastname">
          <span class="epa__label-name">Фамилия:</span>
          <input type="text" name="lastname" value="<?= trim($attr['lastname']) ?>"
                 class="epa__input epa__required" data-name="Фамилия">
        </label>
        <div class="epa__label epa__date epa__select<?= ($attr['bday'] == '01.01.1970' ? ' error' : '') ?>">
          <span class="epa__label-name">Дата рождения:</span>
          <input
            type="text"
            name="bdate"
            id="birthday"
            autocomplete="off"
            value="<?= ($attr['bday'] == '01.01.1970' ? '' : $attr['bday']) ?>"
            class="epa__input">
        </div>
        <?php $gender = (!empty($_GET['sex']) ? $_GET['sex'] : $attr['isman']) ?>
        <div class="epa__attr-block">
          <div class="epa__attr-block1">
            <input type="radio" name="sex" id="epa-male" class="epa__hidden"
                   value="1" <?= ($gender ? 'checked' : '') ?>>
            <label class="epa__checkbox" for="epa-male">Мужчина</label>
          </div>
          <div class="epa__attr-block2">
            <input type="radio" name="sex" id="epa-female" class="epa__hidden"
                   value="0" <?= ($gender ? '' : 'checked') ?>>
            <label class="epa__checkbox epa__checkbox-famale" for="epa-female">Женщина</label>
          </div>
          <div class="clearfix"></div>
        </div>

      </div>

      <div class="epa__content-title"><h2>Контактная информация</h2></div>
      <div class="epa__content-module" id="contacts-module">
        <div class="epa__label">
          <span class="epa__label-name epa__phone-name">Телефон:</span>
          <input type='text' name='user-attribs[mob]' value="<?= $attr['phone'] ?>"
                 class="epa__input epa__phone" id="phone-code">
          <span class="epa__add-phone-btn js-g-hashint" title="Добавить еще телефон">+</span>
          <span class="epa__confirm<?= ($attr['confirmPhone'] ? ' complete' : '') ?>" id="conf-phone">
                          <?php if (!$attr['confirmPhone']): ?>
                            <p>Телефон не подтвержден. <em>Подтвердить</em></p>
                          <?php else: ?>
                            <p>Телефон подтвержден.</p>
                          <?php endif; ?>
                        </span>
        </div>
        <div class="epa__confirm-block" id="conf-phone-block">
          <span class="epa__confirm-text">На Ваш телефон выслан код для подтверждения. Введите его в это поле!</span>
          <label class="epa__label">
            <span class="epa__label-name">Код:</span>
            <input type='text' name='confirm-code' value="" class="epa__input" id="conf-phone-inp"
                   maxlength="6">
          </label>
          <div class="epa__confirm-btn hvr-sweep-to-right btn__orange">ПРОВЕРИТЬ</div>
          <div class="clearfix"></div>
        </div>
        <?php
        if (sizeof($arAdPhones) && !empty($attr['phone'])):
          foreach ($arAdPhones as $phone):
            ?>
            <label class="epa__label epa__add-phone">
              <span class="epa__label-name epa__phone-name">Доп. Телефон:</span>
              <input type="text" name="user-attribs[<?= $phone['name'] ?>]"
                     value="<?= $phone['val'] ?>" class="epa__input epa__phone"
                     autocomplete="off">
            </label>
            <?php
          endforeach;
        endif;
        ?>
        <div class="epa__label epa__email"
             data-error="Указанный e-mail адрес уже используется в системе">
          <span class="epa__label-name">Email:</span>
          <input type="text" name="email" value="<?= $attr['email'] ?>"
                 class="epa__input epa__required" placeholder="your@email.com" id="epa-email"
                 data-name="Электронная почта">
          <span class="epa__confirm<?= ($attr['confirmEmail'] && !empty($attr['email']) ? ' complete' : '') ?>"
                id="conf-email">
                        <?php if ($attr['confirmEmail'] && !empty($attr['email'])): ?>
                          <p>Почта подтверждена.</p>
                        <?php else: ?>
                          <p>Почта не подтверждена. <em>Подтвердить</em></p>
                        <?php endif; ?>
                      </span>
        </div>
        <div class="epa__confirm-block" id="conf-email-block">
          <span class="epa__confirm-text">На Вашу почту выслан код для подтверждения. Введите его в это поле!</span>
          <label class="epa__label">
            <span class="epa__label-name">Код:</span>
            <input type='text' name='confirm-code' value="" class="epa__input" id="conf-email-inp"
                   maxlength="6">
          </label>
          <div class="epa__confirm-btn hvr-sweep-to-right btn__orange">ПРОВЕРИТЬ</div>
          <div class="clearfix"></div>
        </div>
      </div>


      <?php

      // positions
      $arPosts2 = array();
      $strPosts = '';
      foreach ($viData['data']['posts'] as $val) {
        $arPosts2[$val['id']] = $val;
        $arPosts2[$val['id']]['newname'] = ($val['key'] == 'custpo' ? ('- ' . $val['val'] . ' -') : $val['val']);

        $arPosts2[$val['id']]['checked'] = '';

        if ($_GET['position'] == $val['id']) {
          $arPosts2[$val['id']]['checked'] = "checked";
        } elseif ($val['isshow1'] && !$_GET['npopup']) {// если это не после модального окна, то проверяем
          $arPosts2[$val['id']]['checked'] = "checked";
        }

        if ($arPosts2[$val['id']]['checked'] != '') {
          $strPosts .= ($strPosts == '' ? '' : ',') . $arPosts[$val['id']]['newname'];
        }
      }
      $arPayment2 = array();
      foreach ($viData['userInfo']['userDolj'][0] as $val) {
        if ($_GET['npopup'] && $_GET['position']) {
          foreach ($arPosts as $k => $v) {
            if ($v['checked'] === 'checked') {
              $arPayment2[$_GET['position']]['pt'] = 0;
              $arPayment2[$_GET['position']]['type'] = 'Час';
            }
          }
        } else {
          if ($val['pay'] > 0)
            $arPayment2[$val['idpost']]['pay'] = round($val['pay']);
          $arPayment2[$val['idpost']]['pt'] = $val['pt'];
          switch ($val['pt']) {
            case 0:
              $arPayment2[$val['idpost']]['type'] = 'Час';
              break;
            case 1:
              $arPayment2[$val['idpost']]['type'] = 'Неделя';
              break;
            case 2:
              $arPayment2[$val['idpost']]['type'] = 'Месяц';
              break;
            case 3:
              $arPayment2[$val['idpost']]['type'] = 'Посещение';
              break;
          }
        }
      }

      ?>
      <div class="epa__content-title"><h2>Целевая вакансия</h2></div>
      <div class="epa__content-module">
        <h3 class="epa__posts-title">Выберите должности, на которых желаете работать</h3>
        <div class="epa__label epa__posts epa__select">
          <span class="epa__label-name">Должность:</span>
          <ul id="epa-list-posts" class="epa__select-list epa__select-lst-vsbl">
            <?php foreach ($arPosts2 as $post): ?>
              <li>
                <input type="checkbox" name="donjnost[]" value="<?= $post['id'] ?>"
                       id="epa-post-<?= $post['id'] ?>" <?= $post['checked'] ?>>
                <label for="epa-post-<?= $post['id'] ?>"><?= $post['newname'] ?><b></b></label>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>

        <div class="epa__post-detail">
          <?php foreach ($arPosts2 as $post): ?>
            <?php if ($post['checked'] != ''): ?>
              <div class="epa__post-block" data-id="<?= $post['id'] ?>">
                <div class="epa__post-name"><?= $post['newname'] ?></div>
                <div class="epa__post-close"></div>
                <label class="epa__label epa__payment">
                  <span class="epa__label-name">Ожидаемая оплата:</span>
                  <input type="text" name="post[<?= $post['id'] ?>][payment]"
                         value="<?= isset($arPayment2[$post['id']]['pay']) ? $arPayment2[$post['id']]['pay'] : '' ?>"
                         class="epa__input epa__required" data-name="Ожидаемая оплата">
                  <em>руб</em>
                </label>
                <label class="epa__label epa__select">
                  <input type="text" name="epa-str-period"
                         value="<?= $arPayment2[$post['id']]['type'] ?>"
                         class="epa__input epa__post-period" disabled>
                  <div class="epa__label-veil epa__post-veil"></div>
                  <ul class="epa__select-list epa__post-list">
                    <i class="epa__select-list-icon epa__post-btn">OK</i>
                    <li>
                      <input type="radio" name="post[<?= $post['id'] ?>][hwm]"
                             value="0" <?= $arPayment2[$post['id']]['pt'] == 0 ? 'checked' : '' ?>>
                      <label>Час</label>
                    </li>
                    <li>
                      <input type="radio" name="post[<?= $post['id'] ?>][hwm]"
                             value="1" <?= $arPayment2[$post['id']]['pt'] == 1 ? 'checked' : '' ?>>
                      <label>Неделю</label>
                    </li>
                    <li>
                      <input type="radio" name="post[<?= $post['id'] ?>][hwm]"
                             value="2" <?= $arPayment2[$post['id']]['pt'] == 2 ? 'checked' : '' ?>>
                      <label>Месяц</label>
                    </li>
                    <li>
                      <input type="radio" name="post[<?= $post['id'] ?>][hwm]"
                             value="3" <?= $arPayment2[$post['id']]['pt'] == 3 ? 'checked' : '' ?>>
                      <label>Посещение</label>
                    </li>
                  </ul>
                </label>
                <?php
                $arRes = array();
                $name = 'без опыта';
                $checked = false;
                foreach ($viData['data']['expir'] as $val) {
                  $arRes[$val['id']] = $val;
                  $key = $this->ViewModel->isInArray($viData['data']['userInfo']['userDolj'][0], 'id_attr', $val['id']);
                  if ($key > 0 && $viData['data']['userInfo']['userDolj'][0][$key]['idpost'] == $post['id']) {
                    $arRes[$val['id']]['checked'] = 'checked';
                    $name = $val['name'];
                    $checked = true;
                  }
                }
                if (!$checked)
                  $arRes[32]['checked'] = 'checked';
                ?>
                <label class="epa__label epa__select epa__post-experience">
                  <span class="epa__label-name">Опыт работы:</span>
                  <input type="text" name="epa-str-period" value="<?= $name ?>"
                         class="epa__input epa__post-period" disabled>
                  <div class="epa__label-veil epa__post-veil"></div>
                  <ul class="epa__select-list epa__post-list">
                    <i class="epa__select-list-icon epa__post-btn">OK</i>
                    <?php foreach ($arRes as $val): ?>
                      <li>
                        <input type="radio" name="exp[<?= $post['id'] ?>][level]"
                               value="<?= $val['id'] ?>" <?= $val['checked'] ?>>
                        <label><?= $val['name'] ?><b></b></label>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                </label>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
          <div class="clearfix"></div>
        </div>
      </div>


      <div class="epa__content-title"><h2>Удобное место работы</h2></div>
      <div class="epa__content-module" id="city-module">
        <div class="epa__cities-list">
          <div>
            <?php foreach ($arUserCities as $city): ?>
              <b><?= $city['name'] ?></b>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="epa__cities-block-list">
          <?php foreach ($arUserCities as $city): ?>
            <div class="epa__city-item" data-idcity="<?= $city['id'] ?>">
              <div class="epa__city-title">
                <b>ГОРОД </b>
                <span class="epa__city-del"></span>
              </div>
              <div class="epa__label epa__select epa__city">
                <span class="epa__label-name">Город:</span>
                <span class="epa__city-err">Такой город уже выбран</span>
                <input type="text" name="cityname[]" value="<?= $city['name'] ?>"
                       class="epa__input city-input" autocomplete="off">
                <ul class="city-list"></ul>
              </div>

              <div class="clearfix"></div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="clearfix"></div>
      </div>

        <p class="complete__txt center">
            После активации вам станет доступен каталог со свежими вакансиями всех работодателей
        </p>

      <div class="center">
        <button type="submit" class="epa__save-btn prmu-btn prmu-btn_normal">
          <span>Активировать профиль</span>
        </button>
      </div>


    </form>
  </div>
</div>
<?php
/**
 * end form
 *
 */

/**
 * elements
 */

?>

    <?php endif; ?>
</div>

<div id="epa-post-single">
  <div class="epa__post-block" data-id="NEWID">
    <div class="epa__post-name">NEWNAME</div>
    <div class="epa__post-close"></div>
    <label class="epa__label epa__payment">
      <span class="epa__label-name">Ожидаемая оплата: </span>
      <input type="text" name="post[NEWID][payment]" value="" class="epa__input epa__required "
             data-name="Ожидаемая оплата" autocomplete="off">
      <em>руб</em>
    </label>
    <label class="epa__label epa__select">
      <input type="text" name="epa-str-period" value="Час" class="epa__input epa__post-period" disabled>
      <div class="epa__label-veil epa__post-veil"></div>
      <ul class="epa__select-list epa__post-list">
        <i class="epa__select-list-icon epa__post-btn">OK</i>
        <li>
          <input type="radio" name="post[NEWID][hwm]" value="0" checked>
          <label>Час</label>
        </li>
        <li>
          <input type="radio" name="post[NEWID][hwm]" value="1">
          <label>Неделю</label>
        </li>
        <li>
          <input type="radio" name="post[NEWID][hwm]" value="2">
          <label>Месяц</label>
        </li>
        <li>
          <input type="radio" name="post[NEWID][hwm]" value="3">
          <label>Посещение</label>
        </li>
      </ul>
    </label>
    <label class="epa__label epa__select epa__post-experience">
      <span class="epa__label-name">Опыт работы:</span>
      <input type="text" name="epa-str-period" value="без опыта" class="epa__input epa__post-period"
             disabled>
      <div class="epa__label-veil epa__post-veil"></div>
      <ul class="epa__select-list epa__post-list">
        <i class="epa__select-list-icon epa__post-btn">OK</i>
        <?php foreach ($viData['data']['expir'] as $val): ?>
          <li>
            <input type="radio" name="exp[NEWID][level]"
                   value="<?= $val['id'] ?>" <?= ($val['id'] == 32 ? 'checked' : '') ?>>
            <label><?= $val['name'] ?><b></b></label>
          </li>
        <?php endforeach; ?>
      </ul>
    </label>
  </div>
</div>
<?//  *****************  //?>
<div id="add-city-content">
  <div class="epa__city-item" data-idcity="NEWID">
    <div class="epa__city-title">
      <b>ГОРОД </b>
      <span class="epa__city-del"></span>
    </div>
    <div class="epa__label epa__select epa__city">
      <span class="epa__label-name">Город:</span>
      <span class="epa__city-err">Такой город уже выбран</span>
      <input type="text" name="cityname[]" value="" class="epa__input city-input" autocomplete="off">
      <ul class="city-list"></ul>
    </div>
    <h3 class="epa__cities-title">Удобное время работы:</h3>
    <div class="epa__days-checkboxes">
      <div class="epa__day">
        <input type="checkbox" name="days[NEWID]" value="1" class="epa__day-input" data-day="ПН">
        <label class="epa__checkbox">ПН</label>
      </div>
      <div class="epa__day">
        <input type="checkbox" name="days[NEWID]" value="2" class="epa__day-input" data-day="ВТ">
        <label class="epa__checkbox">ВТ</label>
      </div>
      <div class="epa__day">
        <input type="checkbox" name="days[NEWID]" value="3" class="epa__day-input" data-day="СР">
        <label class="epa__checkbox">СР</label>
      </div>
      <div class="epa__day">
        <input type="checkbox" name="days[NEWID]" value="4" class="epa__day-input" data-day="ЧВ">
        <label class="epa__checkbox">ЧВ</label>
      </div>
      <div class="epa__day">
        <input type="checkbox" name="days[NEWID]" value="5" class="epa__day-input" data-day="ПТ">
        <label class="epa__checkbox">ПТ</label>
      </div>
      <div class="epa__day">
        <input type="checkbox" name="days[NEWID]" value="6" class="epa__day-input" data-day="СБ">
        <label class="epa__checkbox">СБ</label>
      </div>
      <div class="epa__day">
        <input type="checkbox" name="days[NEWID]" value="7" class="epa__day-input" data-day="ВС">
        <label class="epa__checkbox">ВС</label>
      </div>
    </div>
    <div class="epa__period-list"></div>
    <div class="clearfix"></div>
  </div>
</div>

<?//  *****************  //?>

<div id="add-additional-phone">
  <label class="epa__label epa__add-phone">
    <span class="epa__label-name epa__phone-name">Доп. Телефон:</span>
    <input type="text" name="user-attribs[admobNEWNUM]" value="" class="epa__input epa__phone"
           autocomplete="off">
  </label>
</div>
<?//  *****************  //?>
<div id="error_messege" class="tmpl">
  <div class="prmu__popup">Для того что бы Ваша анкета была доступна для просмотра всем работодателям и Вы
    могли откликаться на понравившиеся Вам вакансии, необходимо заполнить все обязательные поля, они
    выделены красной рамкой.<br>Спасибо за понимание
  </div>
</div>