<?
  if(!count($model->errors))
  {
    UserRegisterPageCounter::set($model->step);
  }
?>
<script>
  var imageParams = {
    maxFileSize:<?=UserProfile::$MAX_FILE_SIZE?>,
    fileFormat:<?=json_encode(UserProfile::$AR_FILE_FORMAT)?>
  };
  <? if(!Yii::app()->getRequest()->isAjaxRequest): ?>
    var pageCondition = <?=json_encode($model->data['condition']['html'])?>;
  <? endif; ?>
</script>
<div class="login-wrap">

  <svg x="0" y="0" class="svg-bg" />

  <h2 class="login__header">Регистрация</h2>
  <h6 class="login__header">Загрузите фото</h6>

  <div class="login__container">

    <div class="login__photo">
      <p class="center separator">
        <?=(Share::isApplicant($model->data['type'])?'Работодатели':'Соискатели')?> оценят вашу открытость
      </p>
      <div class="login__photo-img">
        <?
          if(!empty($model->data['avatar']))
          {
            $path = $model->filesRoot . DS . $model->data['avatar'];
            $url = $model->filesUrl . DS . $model->data['avatar'];
            $fullImage = UserProfile::$ORIGINAL_IMAGE_SUFFIX . '.jpg';
            $src = $url . $model::$EDIT_IMAGE_SUFFIX . '.jpg';
            $bigSrc = $url . $fullImage;
            if(!file_exists($path . $model::$EDIT_IMAGE_SUFFIX . '.jpg') || !file_exists($path . $fullImage))
            {
              $src = '/theme/pic/register-popup-page/register_popup_r_logo.png'; // Миша, ты обещал картинку, не забудь)
              $bigSrc = '';
              $model->data['avatar'] = '';
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
          alt="<?=$model->data['avatar']?>"
          data-name="<?=$model->data['avatar']?>"
          data-big="<?=$bigSrc?>"
          id="login-img"
          class="login-img<?=(!empty($model->data['avatar'])?' active-logo':'')?>">
      </div>

      <?php if (!empty($model->errors['avatar'])): ?>
        <p class="separator center">
          <span class="login__error"><?=$model->errors['avatar']?></span>
        </p>
      <?php endif; ?>

      <p class="separator center">
        Допустимые форматы файлов <?=implode(', ', UserProfile::$AR_FILE_FORMAT);?>
      </p>
      <p class="separator center pad0">
        Размер не более <?=UserProfile::$MAX_FILE_SIZE?> Мб.
      </p>

      <p class="separator center upload-block">
        <span class="btn-orange btn-upload">Загрузить фото</span>
        <span class="input"><input type="file" name="upload" class="input-upload"></span>
      </p>
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