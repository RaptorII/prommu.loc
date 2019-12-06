<? if(!Yii::app()->getRequest()->isAjaxRequest): ?>
  <script>var pageCondition = <?=json_encode($model->data['condition']['html'])?>;</script>
<? endif; ?>
<?
  if(!count($model->errors))
  {
    UserRegisterPageCounter::set($model->step);
  }
?>
<script>
  // yandex metric
  setGoal();
  function setGoal(){ typeof yaCounter23945542 === 'object' ? yaCounter23945542.reachGoal(2) : setTimeout(function(){ setGoal() },1000); }
</script>
<div class="login-wrap">

  <svg x="0" y="0" class="svg-bg" />

  <h2 class="login__header">Регистрация</h2>
  <? if($model->data['login_type']==UserRegister::$LOGIN_TYPE_PHONE): ?>
    <h6 class="login__header">Подтвердите номер телефона</h6>
  <? else: ?>
    <h6 class="login__header">Подтвердите e-mail</h6>
  <? endif; ?>
  <div class="login__container">
    <? if($model->data['login_type']==UserRegister::$LOGIN_TYPE_PHONE): ?>
      <p>
        <input type="text" name="code" value="<?=($model->data['is_confirm'] ? $model->data['code'] : '')?>" class="input-code" autocomplete="off" placeholder="Введите код из SMS">
        <?php if (!empty($model->errors['code'])): ?>
          <span class="login__error"><?=$model->errors['code']?></span>
        <?php endif; ?>
      </p>

      <p class="separator center">
        Код подтверждения отправлен на:
      </p>
      <p class="separator center pad0"><?=$model->data['login']?></p>
    <? else: ?>
      <p class="separator center">
        Перейдите по ссылке из письма или введите код вручную
      </p>
      <p class="separator center pad0 hinfo">
          (если письмо не отображается - проверьте раздел "Спам")
      </p>

      <p class="separator center">
        <input type="text" name="code" value="<?=($model->data['is_confirm'] ? $model->data['code'] : '')?>" class="input-code" autocomplete="off" placeholder="Введите код из письма">
        <?php if (!empty($model->errors['code'])): ?>
          <span class="login__error"><?=$model->errors['code']?></span>
        <?php endif; ?>
      </p>

      <p class="separator center">
        Код подтверждения отправлен на:
      </p>
      <p class="separator center pad0"><?=$model->data['login']?></p>

    <? endif; ?>
    <? if(!$model->data['is_confirm']): ?>
      <p class="separator">
        <? $isRepeat = $model->data['time_to_repeat'] == 0;  ?>
        <a href="javascript:void(0)" class="back__away repeat-code<?=(!$isRepeat)?' grey':''?>"><?=(!$isRepeat
            ? 'Повторная отправка кода будет доступна через <em><span>' . $model->data['time_to_repeat'] . '</span>&nbsp;сек.</em>'
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