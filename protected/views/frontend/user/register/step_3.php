<?php
echo '<pre>';
print_r($viData);
echo '</pre>';
?>
<div class="login-wrap">

  <svg x="0" y="0" class="svg-bg" />

  <h2 class="login__header">Регистрация</h2>
  <? if($viData['input']['login_type']==UserRegister::$LOGIN_TYPE_PHONE): ?>
    <h6 class="login__header">Подтвердите номер телефона</h6>
  <? else: ?>
    <h6 class="login__header">Подтвердите e-mail</h6>
  <? endif; ?>
  <div class="login__container">
    <? if($viData['input']['login_type']==UserRegister::$LOGIN_TYPE_PHONE): ?>
      <p>
        <input type="text" placeholder="Введите код из SMS">
      </p>

      <p>
        Код подтверждения отправлен на:
      </p>
      <p><?=$viData['input']['login']?></p>
    <? else: ?>
      <p>
        Перейдите по ссылке из письма или введите код вручную
      </p>

      <p>
        <input type="text" placeholder="Введите код из письма">
      </p>

      <p>
        Код подтверждения отправлен на:
      </p>
      <p><?=$viData['input']['login']?></p>
    <? endif; ?>
    <p class="input">
      <label for="radio-4" class="btn-green">Продолжить</label>
      <input type="radio" name="radio" id="radio-4">
    </p>

    <p>
      <a href="">
        Отправить повторно
      </a>
    </p>

    <p>
      <a class="back__away" href="javascript:void(0)">
        Вернуться назад и отредактировать данные
      </a>
    </p>
  </div>
</div>