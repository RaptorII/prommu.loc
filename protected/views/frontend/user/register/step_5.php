<?
$exInfo = $model->profile->exInfo;
$photo = Share::isApplicant($model->profile->type) ? $exInfo->photo : $exInfo->logo;
?>
<script>
  var imageParams = {
    maxFileSize:<?=$model->profile->arYiiUpload['maxFileSize']?>,
    fileFormat:<?=json_encode($model->profile->arYiiUpload['fileFormat'])?>
  };
</script>
<form id="register_form">

  <div class="login-wrap">

    <svg x="0" y="0" class="svg-bg" />

    <h2 class="login__header">Регистрация</h2>
    <h6 class="login__header">Загрузите фото</h6>

    <div class="login__container">

      <div class="login__photo">
        <p class="center separator">
          <?=(Share::isApplicant($exInfo->status)?'Работодатели':'Соискатели')?> оценят вашу открытость
        </p>
        <div class="login__photo-img">
          <?
            if(!empty($photo))
            {
              $src = Share::getPhoto($exInfo->id, $exInfo->status, $photo);
              $bigSrc = Share::getPhoto($exInfo->id, $exInfo->status, $photo, 'big');
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
            class="login-img<?=(!empty($photo)?' active-logo':'')?>">
        </div>

        <?php if (!empty($model->errors['avatar'])): ?>
          <p class="separator center">
            <span class="login__error"><?=$model->errors['avatar']?></span>
          </p>
        <?php endif; ?>

        <p class="separator center">
          Допустимые форматы файлов <?=implode(', ', $model->profile->arYiiUpload['fileFormat']);?>
        </p>
        <p class="separator center pad0">
          Размер не более <?=$model->profile->arYiiUpload['maxFileSize']?> Мб.
        </p>

        <p class="separator center upload-block">
          <span class="btn-orange btn-upload">Загрузить фото</span>
          <span class="input"><input type="file" name="upload" class="input-upload"></span>
        </p>
      </div>

      <div class="login__social-container five">
        <span class="register__preview"><b>или загрузи из социальных сетей:</b></span>
        <div class="reg-social__link-block" >
          <!--<a href="/user/login?service=facebook" class="reg-social__link fb js-g-hashint" title="facebook" >
                        <span class="mob-hidden">
                            facebook
                        </span>
          </a>-->
          <a href="/user/login?service=vkontakte" class="reg-social__link vk js-g-hashint" title="vkontakte.ru" >
                        <span class="mob-hidden">
                            vkontakte.ru
                        </span>
          </a>
          <!--<a href="/user/login?service=mailru" class="reg-social__link ml js-g-hashint" title="mail.ru">
                        <span class="mob-hidden">
                            mail.ru
                        </span>
          </a>-->
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


    <div class="popup" id="popup" style="display: none;">
        <div class="popuptext" id="popup__reg">
            <?php echo $model->data['condition']['html']; ?>
        </div>
    </div>

</form>