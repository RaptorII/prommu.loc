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
        <input type="text" value="<?=($viData['input']['confirm_code'] ? $viData['input']['code'] : '')?>" class="input-code" autocomplete="off" placeholder="Введите код из SMS">
        <?php if (!empty($viData['errors']['code'])): ?>
          <span class="login__error"><?=$viData['errors']['code']?></span>
        <?php endif; ?>
      </p>

      <p class="separator center">
        Код подтверждения отправлен на:
      </p>
      <p class="separator center pad0"><?=$viData['input']['login']?></p>
    <? else: ?>
      <p class="separator center">
        Перейдите по ссылке из письма или введите код вручную(если письмо не отображается - проверьте раздел "Спам")
      </p>

      <p class="separator center">
        <input type="text" name="code" value="<?=($viData['input']['confirm_code'] ? $viData['input']['code'] : '')?>" class="input-code" autocomplete="off" placeholder="Введите код из письма">
        <?php if (!empty($viData['errors']['code'])): ?>
          <span class="login__error"><?=$viData['errors']['code']?></span>
        <?php endif; ?>
      </p>

      <p class="separator center">
        Код подтверждения отправлен на:
      </p>
      <p class="separator center pad0"><?=$viData['input']['login']?></p>

    <? endif; ?>
    <? if(!$viData['input']['confirm_code']): ?>
      <p class="separator">
        <? $isRepeat = $viData['time_to_repeat'] == 0;  ?>
        <a href="javascript:void(0)" class="back__away repeat-code<?=(!$isRepeat)?' grey':''?>"><?=(!$isRepeat
            ? 'Повторная отправка кода будет доступна через <span>' . $viData['time_to_repeat'] . '</span>сек.'
            : 'Отправить повторно')?></a>
      </p>
    <? endif; ?>

    <p class="input">
      <button type="submit" class="btn-green" data-step="3">Продолжить</button>
    </p>

    <p class="separator">
      <a class="back__away back-away" href="javascript:void(0)">
        Вернуться назад и отредактировать данные
      </a>
    </p>
  </div>
</div>