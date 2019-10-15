<? if(count($viData['errors'])): ?>
  <div class="danger">- <?=implode('<br>- ', $viData['errors']); ?></div>
<? endif; ?>
<form id="register_form">
  <label>
    <span>Я ищу работу</span>
    <input type="radio" value="<?=UserProfile::$APPLICANT?>" name="type" class="input-type">
  </label>
  <br>
  <label>
    <span>Я ищу сотрудников</span>
    <input type="radio" value="<?=UserProfile::$EMPLOYER?>" name="type" class="input-type">
  </label>
  <br>
  <small>Регистрируясь на ресурсе Prommu Вы даете согласие на обработку своих <a href="#">персональных данных</a>.</small>
</form>