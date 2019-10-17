<div class="login-wrap">

  <svg x="0" y="0" class="svg-bg" />

  <h2 class="login__header">Регистрация</h2>
  <h6 class="login__header">Введите данные</h6>

  <div class="login__container">
    <?php if (Share::isApplicant($viData['input']['type'])): ?>
      <p>
        <input type="text" name="name" value="<?=$viData['input']['name']?>" class="input-name" autocomplete="off" placeholder="Имя">
        <?php if (!empty($viData['errors']['name'])): ?>
          <span class="login__error"><?=$viData['errors']['name']?></span>
        <?php endif; ?>
      </p>
      <p>
        <input type="text" name="surname" value="<?=$viData['input']['surname']?>" class="input-surname" autocomplete="off" placeholder="Фамилия">
        <?php if (!empty($viData['errors']['surname'])): ?>
          <span class="login__error"><?=$viData['errors']['surname']?></span>
        <?php endif; ?>
      </p>
    <?php elseif (Share::isEmployer($viData['input']['type'])): ?>
      <p>
        <input type="text" name="name" value="<?=$viData['input']['name']?>" class="input-company" autocomplete="off" placeholder="Название компании">
        <?php if (!empty($viData['errors']['name'])): ?>
          <span class="login__error"><?=$viData['errors']['name']?></span>
        <?php endif; ?>
      </p>
    <?php endif; ?>
    <p>
      <input
        type="text"
        name="login"
        value="<?=$viData['input']['login']?>"
        class="input-login"
        autocomplete="off"
        placeholder="Телефон или e-mail">
      <?php if (!empty($viData['errors']['login'])): ?>
        <span class="login__error"><?=$viData['errors']['login']?></span>
      <?php endif; ?>
    </p>

    <p class="input">
      <button type="submit" class="btn-green" data-step="2">Продолжить</button>
    </p>

    <div class="login__social-container">
      <span class="register__preview"></span>
      <div class="reg-social__link-block">
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
        <a href="/user/login?service=yandex_oauth" class="reg-social__link ya js-g-hashint" title="yandex">
                <span class="mob-hidden">
                    yandex
                </span>
        </a>
      </div>
    </div>
  </div>
</div>