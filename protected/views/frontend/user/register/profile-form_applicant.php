<?php if(UserRegisterPageCounter::isSetData(Share::$UserProfile->id, UserRegister::$PAGE_USER_LEAD) <= 0): ?>
    <? 
        $id = Share::$UserProfile->id;
        $texs = "Лид $id";
        $sendto ="https://api.telegram.org/bot525649107:AAFWUj7O8t6V-GGt3ldzP3QBEuZOzOz-ij8/sendMessage?chat_id=@metrikaprommu&text=$texs";
        file_get_contents($sendto);
    ?>
    
  <? UserRegisterPageCounter::setByIdUser(Share::$UserProfile->id, UserRegister::$PAGE_USER_LEAD); ?>
  <script>
    document.addEventListener("DOMContentLoaded", function(){
      var yaParams = [{id_user:<?=Share::$UserProfile->id?>,type:"applicant"}];
      var cnt = 0;
      setGoal();
      function setGoal()
      {
        cnt++;
        if(cnt>20)
        {
          return;
        }
        if(typeof yaCounter23945542 === 'object')
        {
          yaCounter23945542.reachGoal(5,{params:yaParams});
        }
        else
        {
          setTimeout(function(){ setGoal() },500);
        }
      }
      //
      setGA();
      function setGA()
      {
        cnt++;
        if(cnt>20)
        {
          return;
        }
        if(typeof dataLayer === 'object')
        {
          dataLayer.push({'event': 'registration_success'});
        }
        else
        {
          setTimeout(function(){ setGA() },500);
        }
      }
    });
  </script>
<? endif; ?>
<?
$bUrl = Yii::app()->baseUrl;
$gcs = Yii::app()->getClientScript();

$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'phone-codes/style.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'private/page-prof-app.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'private/page-edit-prof-app.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'dist/jquery-ui.min.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS  . 'dist/cropper.min.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'register/complete-reg.css');

$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'phone-codes/script.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'private/page-edit-prof-app-reg.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/cropper.min.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'register/complete-reg.js', CClientScript::POS_END);
//
$attr = array_values($viData['userInfo']['userAttribs'])[0];
// city
$arUserCity = [];
$arCities = Yii::app()->db->createCommand()
    ->select('t.id_city id, t.name, t.ismetro, t.id_co')
    ->from('city t')
    ->limit(10000)
    ->queryAll();
$arTemp = array();
foreach ($arCities as $city)
{
    if($city['id']==Subdomain::getCacheData()->id)
    {
        $arUserCity = ['id' => $city['id'], 'name' => $city['name']];
    }
    $arTemp[$city['id']] = $city['name'];
}
// оптимизируем массив городов для JS
$arCities = array_unique($arTemp);
asort($arCities);
?>
<script type="text/javascript">
    let arCities = <?=json_encode($arCities)?>;
</script>
<div class="private-profile-page">
    <?php if( $viData['error'] ): ?>
        <div class="comm-mess-box"><?= $viData['message'] ?></div>
    <?php else: ?>
        <?
        /**
         * блок с аватаром
         */
        ?>
        <div class="ppp__logo">
            <form id="avatar_form">
                <script>
                    var imageParams = {
                        maxFileSize:<?=Share::$UserProfile->arYiiUpload['maxFileSize']?>,
                        fileFormat:<?=json_encode(Share::$UserProfile->arYiiUpload['fileFormat'])?>
                    };
                </script>
                <?
                $exInfo = Share::$UserProfile->exInfo;
                $photo = Share::isApplicant() ? $exInfo->photo : $exInfo->logo;
                ?>
                <div class="ppp__logo-main avatar__logo-main<?=((empty($photo)||isset($arError['photo'])) ? ' input__error' : '')?>">
                    <?
                    if(!empty($photo))
                    {
                      $path = Share::$UserProfile->filesRoot . DS . $photo;
                      $url = Share::$UserProfile->filesUrl . DS . $photo;
                      $fullImage = UserProfile::$ORIGINAL_IMAGE_SUFFIX . '.jpg';
                      $src = $url . '400.jpg';
                      $bigSrc = $url . $fullImage;
                      if(!file_exists($path . '400.jpg') || !file_exists($path . $fullImage))
                      {
                        $src = '/theme/pic/register-popup-page/register_popup_r_logo.png';
                        $bigSrc = '';
                        $photo = '';
                      }
                    }
                    else
                    {
                      $src = '/theme/pic/register-popup-page/register_popup_r_logo.png';
                      $bigSrc = '';
                    }
                    ?>
                    <img
                            src="<?=$src?>"
                            alt="<?=$photo?>"
                            data-name="<?=$photo?>"
                            data-big="<?=$bigSrc?>"
                            id="login-img"
                            class="ppp-logo-main__img<?=((!empty($photo)&&!isset($arError['photo']))?' active-logo':'')?>">
                </div>
                <p class="center">
                  <small>
                    Допустимые форматы: <?=implode(', ', UserProfile::$AR_FILE_FORMAT);?><br>
                    Минимальное разрешение: <?=UserProfile::$MIN_IMAGE_SIZE?>px<br>
                    Максимальное разрешение: <?=UserProfile::$MAX_IMAGE_SIZE?>px<br>
                    Допустимый размеh: до <?=UserProfile::$MAX_FILE_SIZE?> Мб.
                  </small>
                </p>
                <p class="upload-block">
                    <span class="prmu-btn prmu-btn_normal btn-upload"><span>Загрузить фото</span></span>
                    <span class="input"><input type="file" name="upload" class="input-upload hide"></span>
                </p>
            </form>
        </div>
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
                      <div class="col-xs-12 col-sm-6 profile__col">
                        <label class="epa__label epa__firstname<?=(isset($arError['name'])?' error':'')?>">
                          <span class="epa__label-name">Имя:</span>
                          <?
                            $v1 = trim($_POST['name']);
                            $v2 = trim($attr['firstname']);
                          ?>
                          <input type="text" name="name" value="<?=(!empty($v1) ? $v1 : $v2)?>"
                                 class="epa__input epa__required" data-name="Имя" autocomplete="off">
                        </label>
                      </div>
                      <div class="col-xs-12 col-sm-6 profile__col">
                        <label class="epa__label epa__lastname<?=(isset($arError['lastname'])?' error':'')?>">
                          <span class="epa__label-name">Фамилия:</span>
                          <? $value = trim($attr['lastname']); ?>
                          <?
                          $v1 = trim($_POST['lastname']);
                          $v2 = trim($attr['lastname']);
                          ?>
                          <input type="text" name="lastname" value="<?=(!empty($v1) ? $v1 : $v2)?>"
                                 class="epa__input epa__required" data-name="Фамилия" autocomplete="off">
                        </label>
                      </div>
                      <div class="col-xs-12 col-sm-6 profile__col">
                        <div class="epa__label epa__date epa__select <?=((($attr['bday']=='01.01.1970') || ($attr['bday']=='') || isset($arError['bdate']))?' error':'')?>">
                          <span class="epa__label-name">Дата рождения:</span>
                          <?
                          $v1 = $_POST['bday'];
                          $v2 = $attr['bday'] == '01.01.1970' ? '' : $attr['bday'];
                          ?>
                          <input
                            type="text"
                            name="bdate"
                            id="birthday"
                            autocomplete="off"
                            value="<?=((!empty($v1) && !isset($arError['bdate'])) ? $v1 : $v2)?>"
                            class="epa__input">
                        </div>
                      </div>
                      <div class="col-xs-12 col-sm-6 profile__col">
                        <div class="epa__attr-block">
                          <div class="epa__attr-block1">
                            <input type="radio" name="sex" id="epa-male" class="epa__hidden" value="1" <?=($attr['isman'] ? 'checked' : '')?>>
                            <label class="epa__checkbox" for="epa-male">Мужчина</label>
                          </div>
                          <div class="epa__attr-block2">
                            <input type="radio" name="sex" id="epa-female" class="epa__hidden" value="0" >
                            <label class="epa__checkbox epa__checkbox-famale" for="epa-female">Женщина</label>
                          </div>
                          <div class="clearfix"></div>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                    <div class="epa__content-title"><h2>Место работы</h2></div>
                    <div class="epa__content-module" id="city-module">
                        <div class="epa__cities-block-list">
                            <div class="epa__city-item" data-idcity="<?=(!empty($_POST['city'])?$_POST['city']:'')?>">
                                <div class="epa__label epa__select epa__city<?=(isset($arError['city'])?' error':(empty($_POST['city'])?' error':''))?>">
                                    <span class="epa__label-name">Город:</span>
                                    <span class="epa__city-err">Такой город уже выбран</span>
                                    <input type="text" name="cityname[]" value="<?=(!empty($_POST['cityname'][0])?$_POST['cityname'][0]:'')?>"
                                           class="epa__input city-input" autocomplete="off">
                                    <ul class="city-list"></ul>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    <div class="epa__content-title"><h2>Контактная информация</h2></div>
                    <div class="epa__content-module" id="contacts-module">
                        <div id="phone-field" class="epa__label<?=(isset($arError['mob'])?' error':'')?>"
                             data-error="Указанный телефон уже используется в системе">
                            <span class="epa__label-name epa__phone-name">Телефон:</span>
                            <?
                            $v1 = $_POST['user-attribs']['mob'];
                            $v2 = Share::getPrettyPhone($attr['phone'])['phone'];
                            ?>
                            <input type='text'
                                   name='user-attribs[mob]'
                                   value="<?=((!empty($v1) && !isset($arError['mob'])) ? $v1 : $v2)?>"
                                   class="epa__input epa__phone epa__required"
                                   id="phone-code"
                                   autocomplete="off">
                            <span class="epa__add-phone-btn js-g-hashint" title="Добавить еще телефон" id="add_phone">+</span>
                        </div>
                        <div id="email-field" class="epa__label epa__email<?=(isset($arError['email'])?' error':'')?>"
                             data-error="Указанный e-mail адрес уже используется в системе">
                            <span class="epa__label-name">Email:</span>
                            <?
                            $v1 = $_POST['email'];
                            $v2 = filter_var($attr['email'], FILTER_VALIDATE_EMAIL);
                            ?>
                            <input type="text"
                                   name="email"
                                   value="<?=((!empty($v1) && !isset($arError['email'])) ? $v1 : $v2)?>"
                                   class="epa__input epa__required"
                                   placeholder="your@email.com"
                                   id="epa-email"
                                   data-name="Электронная почта"
                                   autocomplete="off">
                        </div>
                    </div>
                    <?php

                    // positions
                    $arPosts = [];
                    $bPosts = false;
                    foreach ($viData['data']['posts'] as $val)
                    {
                      $arPosts[$val['id']] = $val;
                      $arPosts[$val['id']]['newname'] = ($val['key'] == 'custpo' ? ('- ' . $val['val'] . ' -') : $val['val']);
                      $arPosts[$val['id']]['checked'] = '';

                      if($val['isshow1'])
                      {
                        $arPosts[$val['id']]['checked']="checked";
                        $bPosts = true;
                      }
                    }
                    $arPayment2 = array();
                    foreach ($viData['userInfo']['userDolj'][0] as $val)
                    {
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

                    ?>
                    <div class="epa__content-title"><h2>Целевая вакансия</h2></div>
                    <div class="epa__content-module">
                        <h3 class="epa__posts-title">Выберите должности, на которых желаете работать</h3>
                        <div class="epa__label epa__posts<?=(!$bPosts||isset($arError['donjnost']))?' error':''?>">
                            <span class="epa__label-name">Должность:</span>
                            <ul id="epa-list-posts" class="epa__select-list epa__select-lst-vsbl">
                                <?php foreach ($arPosts as $post): ?>
                                    <li>
                                        <input type="checkbox" name="donjnost[]" value="<?= $post['id'] ?>"
                                               id="epa-post-<?= $post['id'] ?>" <?= $post['checked'] ?>>
                                        <label for="epa-post-<?= $post['id'] ?>"><span><?= $post['newname'] ?></span><b></b></label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="epa__post-detail">
                            <?php foreach ($arPosts as $post): ?>
                                <?php if ($post['checked'] != ''): ?>
                                    <div class="epa__post-block" data-id="<?= $post['id'] ?>">
                                        <div class="epa__post-name"><?= $post['newname'] ?></div>
                                        <div class="epa__post-close"></div>
                                        <label class="epa__label epa__payment">
                                            <span class="epa__label-name">Ожидаемая оплата:</span>
                                            <input type="text" name="post[<?= $post['id'] ?>][payment]"
                                                   value="<?= isset($arPayment2[$post['id']]['pay']) ? $arPayment2[$post['id']]['pay'] : '' ?>"
                                                   class="epa__input epa__required<?=(isset($arResult['post'])?' error':'')?>" data-name="Ожидаемая оплата">
                                            <em>руб</em>
                                        </label>
                                        <label class="epa__label epa__select">
                                            <span class="epa__label-name">Оплата за:</span>
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

                    <p class="complete__txt center">
                        После активации вам станет доступен каталог со свежими вакансиями всех работодателей
                    </p>

                    <div class="center">
                        <button type="submit" class="epa__save-btn prmu-btn prmu-btn_normal">
                            <span>Активировать профиль</span>
                        </button>
                    </div>

                    <input type="hidden" name="register_complete" value="Y">
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
            <span class="epa__label-name">Оплата за:</span>
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