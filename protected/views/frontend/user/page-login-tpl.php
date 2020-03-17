<?php 
  $bUrl = Yii::app()->baseUrl;
  Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/login-form/login-form.css');
  Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/phone-codes/style.css'); 
  Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/login-form/login-form.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/phone-codes/script.js', CClientScript::POS_END);
?>
<div class='row'>
  <div class='col-xs-12 auth-form__wrap'>
    <div class="row auth-form__header">
        <div class="col-xs-12 col-sm-12">
            <h1 class='auth-form__title'>Вход</h1>
        </div>
<!--        <div class="col-xs-12 col-sm-4"></div>-->
    </div>

    <div class="row auth-form__content">
        <div class="col-xs-12 col-sm-8 auth-form__form">
          <span class="auth-form__preview">
            Введите свои данные:
          </span>
          <?php if( $errMess = Yii::app()->user->getFlash('auErrMess') ): Yii::app()->user->setFlash('auErrMess', null) ?>
            <div class="message red"><?= $errMess ?></div>
          <?php endif; ?>
          <form action='<?= MainConfig::$PAGE_AUTH ?>' id='F1registerAppl' method='post'>
            <div class="auth-form__selection">
              <label class='auth-form__label-radio'>
                <input name='type-reg' type='radio' value='1' checked="checked">
                <span class="auth-form__radio"><span></span></span>
                Войти с помощью Email
              </label>
              <br>
              <label class='auth-form__label-radio'>
                <input name='type-reg' type='radio' value='2'>
                <span class="auth-form__radio"><span></span></span>
                Войти с помощью номера телефона
              </label>
            </div>
            <label class="auth-form__label email js-g-hashint" title="Email или телефон">
              <input id='EdEmail' name='login' type='text' class="auth-form__input" placeholder="Email">
              <input id='phone-code' name='phone' type='text' class="auth-form__input" placeholder="Телефон">
            </label>
            <label class="auth-form__label pass js-g-hashint" title="Пароль">
              <input id='EdPass' name='passw' type='password' class="auth-form__input" placeholder="Пароль">
            </label>
            <label class='auth-form__label remember js-g-hashint' title="Запомнить меня">
              <span class="auth-form__label-name">Запомнить меня</span>
              <input id='ChkRemember' name='remember' type='checkbox' value="1" checked>
              <span class="auth-form__checkbox"></span>
            </label>
            <div class='btn-auth btn-orange-wr'>
                <button class='hvr-sweep-to-right auth-form__btn btn__orange' type='submit'>Вход</button>
            </div>
            <div class="auth-form__restore">
              <a href='/<?= MainConfig::$PAGE_PASS_RESTORE ?>'>Забыли пароль?</a>
            </div>
          </form>

          <div class="row auth-form__bottom">
            <div class="col-xs-12 center">
                <span class='auth-form__preview'>Ещё не регистрировались у нас на сервисе?</span>
                <span class='auth-form__preview'>Жмите на кнопку, регистрация занимает не более пяти минут.</span>

                <div class="auth-form__reg-link btn__orange">
                    <a href="/user/register">
                        Регистрация
                    <!--<ul class="auth-form__reg-list">
                        <li><a href="<?= Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '2')) ?>" class="items">Я работодатель</a></li>
                        <li><a href="<?= Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '1')) ?>" class="items">Я ищу работу</a></li>
                    </ul>
                    -->
                    </a>
                </div>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-4 auth-form__social">
          <span class="auth-form__preview">Либо войдите через социальные сети:</span>
          <div class="auth-form__social-link-block">
              <a href="/user/login?service=facebook" class="auth-form__social-link fb js-g-hashint" title="facebook"><span class="mob-hidden">facebook</span></a>
              <a href="/user/login?service=vkontakte" class="auth-form__social-link vk js-g-hashint" title="vkontakte.ru"><span class="mob-hidden">vkontakte.ru</span></a>
    <!--          <a href="/user/login?service=mailru" class="auth-form__social-link ml js-g-hashint" title="mail.ru"><span class="mob-hidden">mail.ru</a>-->
              <a href="/user/login?service=odnoklassniki" class="auth-form__social-link od js-g-hashint" title="odnoklasniki.ru"><span class="mob-hidden">odnoklasniki.ru</span></a>
              <a href="/user/login?service=google_oauth" class="auth-form__social-link go js-g-hashint" title="google"><span class="mob-hidden">google</span></a>
              <a href="/user/login?service=yandex_oauth" class="auth-form__social-link ya js-g-hashint tooltipstered"><span class="mob-hidden">yandex</span></a>
          </div>
        </div>
    </div>

  </div>
</div>