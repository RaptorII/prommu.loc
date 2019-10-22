<div class="login-wrap">

  <svg x="0" y="0" class="svg-bg" />

  <h2 class="login__header">Регистрация</h2>
  <h6 class="login__header">Создайте пароль</h6>

  <div class="login__container">
    <p class="separator center">Пароль должен состоять минимум из шести символов(цифр, букв, знаков препинания)</p>
    <p class="separator center">
      <input type="password" name="password" class="input-password" autocomplete="off" placeholder="Придумайте пароль">
      <?php if (!empty($viData['errors']['password'])): ?>
        <span class="login__error"><?=$viData['errors']['password']?></span>
      <?php endif; ?>
    </p>
    <p class="separator center">
      <input type="password" name="r-password" class="input-r-password" autocomplete="off" placeholder="и введите повторно">
      <?php if (!empty($viData['errors']['r-password'])): ?>
        <span class="login__error"><?=$viData['errors']['r-password']?></span>
      <?php endif; ?>
    </p>

    <p class="input">
      <button type="submit" class="btn-green" data-step="4">Продолжить</button>
    </p>

    <p class="separator">
      <a class="back__away back-away" href="javascript:void(0)">
        Вернуться назад и отредактировать данные
      </a>
    </p>

  </div>
</div>