<form id="register_form">
  <? if(Share::isApplicant($viData['input']['type'])): ?>
    <label>
      <span>Имя</span>
      <input type="text" value="<?=$viData['input']['name']?>" name="name" class="input-name" autocomplete="off" placeholder="Александр">
    </label>
    <br>
    <label>
      <span>Фамилия</span>
      <input type="text" value="<?=$viData['input']['surname']?>" name="surname" class="input-surname" autocomplete="off" placeholder="Иванов">
    </label>
  <? elseif(Share::isEmployer($viData['input']['type'])): ?>
    <label>
      <span>Название компании</span>
      <input type="text" value="<?=$viData['input']['name']?>" name="name" class="input-name" autocomplete="off" placeholder="Евразия">
    </label>
  <? endif; ?>
  <br>
  <label>
    <span>Мобильный телефон или электронная почта</span>
    <input type="text" value="<?=$viData['input']['login']?>" name="login" class="input-login" autocomplete="off" placeholder="+7-123-456-78-90 или your-email@mail.ru">
  </label>
<?
echo '<pre>';
print_r($viData);
echo '</pre>';
?>
</form>