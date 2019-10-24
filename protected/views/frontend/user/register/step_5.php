<?
$userInfo = $model->profile->exInfo;
$hasPhoto = !empty($userInfo->photo);
?>
<form id="register_form">

  <div class="login-wrap">

    <svg x="0" y="0" class="svg-bg" />

    <h2 class="login__header">Регистрация</h2>
    <h6 class="login__header">Загрузите фото</h6>

    <div class="login__container">

      <div class="login__photo">
        <p class="center separator">
          <?=(Share::isApplicant($userInfo->status)?'Работодатели':'Соискатели')?> оценят вашу открытость
        </p>
        <div class="login__photo-img">
          <?
            if($hasPhoto)
            {
              $src = Share::getPhoto($userInfo->id, $userInfo->status, $userInfo->photo);
              $bigSrc = Share::getPhoto($userInfo->id, $userInfo->status, $userInfo->photo, 'big');
              $name = $userInfo->photo . '.jpg';
            }
            else
            {
              $src = '/theme/pic/register-popup-page/register_popup_r_logo.png'; // Миша, ты обещал картинку, не забудь)
              $bigSrc = '';
              $name = '';
            }
          ?>
          <img
            src="<?=$src?>"
            alt="<?=$name?>"
            data-name="<?=$name?>"
            data-big="<?=$bigSrc?>"
            id="login-img"
            class="login-img<?=$hasPhoto?' active-logo':''?>">
        </div>

        <?php if (!empty($viData['errors']['avatar'])): ?>
          <p class="separator center">
            <span class="login__error"><?=$viData['errors']['avatar']?></span>
          </p>
        <?php endif; ?>

        <p class="separator center">
          Допустимые форматы фалов <?=implode(', ', $model->profile->arYiiUpload['fileFormat']);?>
        </p>
        <p class="separator center pad0">
          Размер не более <?=$model->profile->arYiiUpload['maxFileSize']?> Мб.
        </p>

        <p class="separator center upload-block">
          <span class="btn-orange btn-upload">Загрузить фото</span>
          <span class="input"><input type="file" name="upload" class="input-upload"></span>
        </p>
      </div>

      <div class="login__social-container">
        <span class="register__preview" data-txt="или загрузи из социальных сетей:"></span>
        <div class="reg-social__link-block" >
          <a href="/user/login?service=facebook" class="reg-social__link fb js-g-hashint" title="facebook" >
                        <span class="mob-hidden">
                            facebook
                        </span>
          </a>
          <a href="/user/login?service=vkontakte" class="reg-social__link vk js-g-hashint" title="vkontakte.ru" >
                        <span class="mob-hidden">
                            vkontakte.ru
                        </span>
          </a>
          <a href="/user/login?service=mailru" class="reg-social__link ml js-g-hashint" title="mail.ru">
                        <span class="mob-hidden">
                            mail.ru
                        </span>
          </a>
          <a href="/user/login?service=odnoklassniki" class="reg-social__link od js-g-hashint" title="odnoklasniki.ru">
                        <span class="mob-hidden">
                            odnoklasniki.ru
                        </span>
          </a>
          <a href="/user/login?service=google_oauth" class="reg-social__link go js-g-hashint" title="google">
                        <span class="mob-hidden">
                            google
                        </span>
          </a>

          </a>
        </div>
      </div>

      <p class="input">
        <button type="submit" class="btn-green" data-step="5">Завершить регистрацию</button>
      </p>

      <p class="separator">
        <a class="back__away back-away" href="javascript:void(0)">
          Вернуться назад и отредактировать данные
        </a>
      </p>

    </div>


  </div>
  <input type="hidden" name="href" value="<?=MainConfig::$PAGE_PROFILE?>">
</form>