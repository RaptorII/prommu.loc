<?php if(UserRegisterPageCounter::isSetData(Share::$UserProfile->id, UserRegister::$PAGE_USER_LEAD) <= 0): ?>
  <? UserRegisterPageCounter::setByIdUser(Share::$UserProfile->id, UserRegister::$PAGE_USER_LEAD); ?>
  <script>
    document.addEventListener("DOMContentLoaded", function(){
      var yaParams = [{id_user:<?=Share::$UserProfile->id?>,type:"employer"}];
      var cnt = 0;
      setGoal();
      function setGoal()
      {
        cnt++;
        if(cnt>10)
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
    });
  </script>
<? endif; ?>
<?
$bUrl = Yii::app()->baseUrl;
$gcs = Yii::app()->getClientScript();

$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'phone-codes/style.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'private/page-prof-emp.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'private/page-edit-prof-emp.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS  . 'dist/cropper.min.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'register/complete-reg.css');

$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'phone-codes/script.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'phone-codes/script-contact.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'private/page-edit-prof-emp-reg.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/cropper.min.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'register/complete-reg.js', CClientScript::POS_END);

$userInfo = $viData['userAllInfo']['emplInfo'];

?>

<?
/**
 * photoblock
 */
?>
<div class='col-xs-12 col-sm-4 col-lg-3 no-md-relat ppe__logo'>
    <form id="avatar_form">
        <script>
            var imageParams = {
                maxFileSize:<?=Share::$UserProfile->arYiiUpload['maxFileSize']?>,
                fileFormat:<?=json_encode(Share::$UserProfile->arYiiUpload['fileFormat'])?>
            };
            var arSelectCity = <?=json_encode($viData['cities'])?>;
        </script>
        <div class="upp__img-block">
            <?
            $exInfo = Share::$UserProfile->exInfo;
            $photo = Share::isApplicant() ? $exInfo->photo : $exInfo->logo;
            ?>
            <? /*if(empty($photo)): ?>
                <p class="center">
                    Допустимые форматы файлов <?=implode(', ', Share::$UserProfile->arYiiUpload['fileFormat']);?><br>
                    Размер не более <?=Share::$UserProfile->arYiiUpload['maxFileSize']?> Мб.
                </p>
            <? endif;*/ ?>
            <div class="upp__img-block-main avatar__logo-main<?=(empty($photo) ? ' input__error' : '')?>">
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
                  $src = '/theme/pic/register-popup-page/register_popup_r_logo.png'; // Миша, ты обещал картинку, не забудь)
                  $bigSrc = '';
                }
                ?>
                <img
                        src="<?=$src?>"
                        alt="<?=$photo?>"
                        data-name="<?=$photo?>"
                        data-big="<?=$bigSrc?>"
                        id="login-img"
                        class="ppe__logo-main__img<?=(!empty($photo)?' active-logo':'')?>">
            </div>
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
<div class="col-xs-12 col-sm-7 col-lg-8">
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
                                if ($t['selected'])
                                {
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
                            <div class="epe__select-cities" id="multyselect-cities"></div>
                            <?/*?>
                <span class="city-select"><?=$arUserCity['name'] ?><b></b></span>
                <input type='text' name='str-city' value="<?=$arUserCity['name'] ?>"
                       class="epe__input epe__input-city" autocomplete="off">
                <input type="hidden" name="cities[]" value="<?=$arUserCity['id_city'] ?>"
                       id="id-city">
                <ul class="city-list"></ul>
                <?*/?>
                        </div>

                    </div>
                    <?
                    //
                    ?>
                    <div class="epe-data__title"><h2>КОНТАКТНАЯ ИНФОРМАЦИЯ</h2></div>
                    <div class="epe-data__module">
                        <label class="epe__label">
                            <span class="epe__label-name">Ваше имя:</span>
                            <input type='text' name='fname' value="<?=$userInfo['firstname'] ?>"
                                   class="epe__input epe__input-fname epe__required" autocomplete="off">
                        </label>
                        <label class="epe__label">
                            <span class="epe__label-name">Ваша фамилия:</span>
                            <input type='text' name='lname' value="<?=$userInfo['lastname'] ?>"
                                   class="epe__input epe__input-lname" autocomplete="off">
                        </label>
                        <label class="epe__label epe__email" data-error="Указанный e-mail адрес уже используется в системе"
                               for="epe-email">
                            <span class="epe__label-name">Ваш email:</span>
                            <input type='text' name='email' value="<?=filter_var($userInfo['email'], FILTER_VALIDATE_EMAIL)?>"
                                   class="epe__input epe__input-mail epe__required" id="epe-email" autocomplete="off">
                        </label>
                        <div class="epe__label">
                            <span class="epe__label-name epe__phone-name">Телефон:</span>
                            <input type='text' name='user-attribs[mob]' value="<?=Share::getPrettyPhone($viData['userAllInfo']['userAttribs'][1]['val'])['phone']?>"
                                   class="epe__input epe__phone epe__input-phone" id="phone-code" autocomplete="off">
                        </div>
                    </div>

                    <div class="epe-data__title"><h2>Контактные данные</h2></div>
                    <div class="epe-data__module">
                        <label class="epe__label">
                            <span class="epe__label-name">Контактное лицо:</span>
                            <input type='text' name='contact' value="<?=$userInfo['contact'] ?>"
                                   class="epe__input epe__input-contact epe__required" autocomplete="off">
                        </label>

                        <div class="epe__label">
                            <span class="epe__label-name epe__phone-name">Телефон:</span>
                            <input type='text' name='user-attribs[mob-contact]' value="<?=Share::getPrettyPhone($viData['userAllInfo']['userAttribs'][2]['val'])['phone']?>"
                                   class="epe__input epe__phone epe__input-phone" id="phone-code-contact" autocomplete="off">
                        </div>

                        <label class="epe__label epe__email" data-error="Указанный e-mail адрес уже используется в системе"
                               for="epe-email-contact">
                            <span class="epe__label-name">Email:</span>
                            <input type='text' name='user-attribs[email-contact]' value="<?=filter_var($viData['userAllInfo']['userAttribs'][194]['val'], FILTER_VALIDATE_EMAIL)?>"
                                   class="epe__input epe__input-mail epe__required" id="epe-email-contact" autocomplete="off">
                        </label>
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
            <input type="hidden" name="register_complete" value="Y">
        </form>
    </div>
</div>
<?php
/**
 * end form
 *
 */
?>
<?//  *****************  //?>
<div id="error_messege" class="tmpl">
    <div class="prmu__popup">Для того что бы Ваша компания была доступна для просмотра всем соискателям и Вы могли добавлять вакансии и приглашать нужный Вам персонал, необходимо заполнить все обязательные поля, они выделены красной рамкой.<br>Спасибо за понимание</div>
</div>
