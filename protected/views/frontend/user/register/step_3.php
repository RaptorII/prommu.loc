<?php
echo '<pre>';
//print_r($viData);
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

      <p class="separator center">
        Код подтверждения отправлен на:
      </p>
      <p class="separator center pad0"><?=$viData['input']['login']?></p>
    <? else: ?>
      <p class="separator center">
        Перейдите по ссылке из письма или введите код вручную
      </p>

      <p class="separator center">
        <input type="text" placeholder="Введите код из письма">
      </p>

      <p class="separator center">
        Код подтверждения отправлен на:
      </p>
      <p class="separator pad0"><?=$viData['input']['login']?></p>

    <? endif; ?>
    <p class="separator">
      <a class="back__away" href="">
          Отправить повторно
      </a>
    </p>
    <p class="input">
      <button type="submit" class="btn-green" data-step="2">Продолжить</button>
    </p>

    <p class="separator">
      <a class="back__away" href="javascript:void(0)">
        Вернуться назад и отредактировать данные
      </a>
    </p>
  </div>
</div>