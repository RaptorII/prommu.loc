<?
  Yii::app()->session['utm'] = false; // сбрасываем ютм метки, уже записали в БД
  if(!count($model->errors))
  {
    UserRegisterPageCounter::set($model->step);
  }
?>
<? if(!Yii::app()->getRequest()->isAjaxRequest): ?>
  <script>var pageCondition = <?=json_encode($model->data['condition']['html'])?>;</script>
<? endif; ?>
<div class="login-wrap">

  <svg x="0" y="0" class="svg-bg" />

  <h2 class="login__header">Регистрация</h2>
  <h6 class="login__header">Введите данные</h6>

  <div class="login__container">
    <?php if (Share::isApplicant($model->data['type'])): ?>
      <p>
        <input type="text" name="name" value="<?=$model->data['name']?>" class="input-name" autocomplete="off" placeholder="Имя">
        <?php if (!empty($model->errors['name'])): ?>
          <span class="login__error"><?=$model->errors['name']?></span>
        <?php endif; ?>
      </p>
      <p>
        <input type="text" name="surname" value="<?=$model->data['surname']?>" class="input-surname" autocomplete="off" placeholder="Фамилия">
        <?php if (!empty($model->errors['surname'])): ?>
          <span class="login__error"><?=$model->errors['surname']?></span>
        <?php endif; ?>
      </p>
    <?php elseif (Share::isEmployer($model->data['type'])): ?>
      <p>
        <input type="text" name="name" value="<?=$model->data['name']?>" class="input-company" autocomplete="off" placeholder="Название компании">
        <?php if (!empty($model->errors['name'])): ?>
          <span class="login__error"><?=$model->errors['name']?></span>
        <?php endif; ?>
      </p>
    <?php endif; ?>
    <p>
      <input
        type="text"
        name="login"
        value="<?=$model->data['login']?>"
        class="input-login"
        autocomplete="off"
        placeholder="Телефон или e-mail">
      <?php if (!empty($model->errors['login'])): ?>
        <span class="login__error"><?=$model->errors['login']?></span>
      <?php endif; ?>
    </p>

    <p class="input">
      <button type="submit" class="btn-green" data-step="2">Продолжить</button>
    </p>

    <?php if(!Share::isEmployer($model->data['type'])): ?>
      <?
        if(Subdomain::isDomain())
        {
          $url = MainConfig::$PAGE_LOGIN . '?service=';
        }
        else
        {
          $arGet = [
            'urh' => $model->user,
            'ursd' => Subdomain::getCacheData()->id
          ];
          $url = 'https://' . Subdomain::getCacheData()->domain->url . MainConfig::$PAGE_LOGIN . '?' . http_build_query($arGet) . '&service=';
        }
      ?>
          <div class="login__social-container">
              <span class="register__preview"><b>Войти через социальные сети:</b></span>
              <div class="reg-social__link-block">
                  <a
                          href="<?=$url . 'facebook'?>"
                          class="reg-social__link fb js-g-hashint"
                          title="facebook">
                <span class="mob-hidden">
                    facebook
                </span>
                  </a>
                  <a
                          href="<?=$url . "vkontakte"?>"
                          class="reg-social__link vk js-g-hashint"
                          title="vkontakte.ru">
                <span class="mob-hidden">
                    vkontakte.ru
                </span>
                  </a>
                  <a
                          href="<?=$url . "odnoklassniki"?>"
                          class="reg-social__link od js-g-hashint"
                          title="odnoklasniki.ru"
                  >
                <span class="mob-hidden">
                    odnoklasniki.ru
                </span>
                  </a>
                  <a
                          href="<?=$url . "google_oauth"?>"
                          class="reg-social__link go js-g-hashint"
                          title="google">
                <span class="mob-hidden">
                    google
                </span>
                  </a>
                  <a
                          href="<?=$url . "yandex_oauth"?>"
                          class="reg-social__link ya js-g-hashint"
                          title="yandex">
                <span class="mob-hidden">
                    yandex
                </span>
                  </a>
              </div>
          </div>
    <? endif; ?>
    <? if(!$model->data['id_user']): ?>
      <p class="separator">
        <a class="back__away back-away" href="javascript:void(0)">
          Вернуться назад и отредактировать данные
        </a>
      </p>
    <? endif; ?>
  </div>
</div>