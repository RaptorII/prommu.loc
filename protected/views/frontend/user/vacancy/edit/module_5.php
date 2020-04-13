<? $module = 5; ?>
<div class="personal__area--capacity-name">Общая информация</div>
<?
//
?>
<div class="module_info<?=$viData->error_moodule==$module?' block__hide':''?>">
  <? if($viData->data->is_actual_remdate && !count($viData->services->creation_vacancy->items)): ?>
    <a href="javascript:void(0)" class="personal__area--capacity-edit js-g-hashint" title="Редактировать"></a>
  <? endif; ?>
  <div class="group ppe__field">
    <div class="group__about">Описание вакансии (требования)</div>
    <div class="group__info group__info-normalize"><?echo html_entity_decode($viData->data->requirements)?></div>
  </div>
  <? if(!empty($viData->data->duties)): ?>
    <div class="group ppe__field">
      <div class="group__about">Обязанности</div>
      <div class="group__info group__info-normalize"><?echo html_entity_decode($viData->data->duties)?></div>
    </div>
  <? endif; ?>
  <? if(!empty($viData->data->conditions)): ?>
    <div class="group ppe__field">
      <div class="group__about">Условия</div>
      <div class="group__info group__info-normalize"><?echo html_entity_decode($viData->data->conditions)?></div>
    </div>
  <? endif; ?>
  <br>
</div>
<?
//
?>
<form class="module_form<?=$viData->error_moodule==$module?' block__visible':''?>" method="post" data-params='{"ajax":"true"}'>
  <a href="javascript:void(0)" class="personal__area--capacity-cancel js-g-hashint" title="Отмена"></a>
  <div class="form__container">
    <div class="form__field">
      <label class="form__field-label text__nowrap">Описание <span class="text__red">*</span></label>
      <div class="form__field-content form__content-indent form__content-hint form__field-niceditor">
        <? if($viData->errors['requirements']): ?>
          <span class="prmu-error-mess">Поле обязательно к заполнению</span>
        <? endif; ?>
        <div id="requirements_panel" class="form__textarea-panel"></div>
        <textarea
          class="form__field-input form__textarea prmu-required"
          id="requirements"
          data-params='{"parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
          name="requirements"><?=$viData->data->requirements?></textarea>
      </div>
      <div class="form__field-hint tooltip" title="Подсказка:<br>Раздача листовок согласно адресной программы;<br>Возраст: от 18 лет;<br>Активные ответственные девушки и парни;<br>Одеты опрятно;<br>Ответственные;<br>Коммуникабельные;<br>Веселые"></div>
    </div>
    <div class="form__field">
      <label class="form__field-label text__nowrap">Обязанности</label>
      <div class="form__field-content form__content-indent form__content-hint form__field-niceditor">
        <div id="duties_panel" class="form__textarea-panel"></div>
        <textarea
          class="form__field-input form__textarea"
          id="duties"
          name="duties"><?=$viData->data->duties?></textarea>
      </div>
      <div class="form__field-hint tooltip" title="Подсказка:<br>Раздача листовок только для целевой аудитории: девушкам от 20 до 35 лет. Листовки забрать на офисе в районе локации за 15 минут до старта работы"></div>
    </div>
    <div class="form__field">
      <label class="form__field-label text__nowrap">Условия</label>
      <div class="form__field-content form__content-indent form__content-hint form__field-niceditor">
        <div id="conditions_panel" class="form__textarea-panel"></div>
        <textarea
          class="form__field-input form__textarea"
          id="conditions"
          name="conditions"><?=$viData->data->conditions?></textarea>
      </div>
      <div class="form__field-hint tooltip" title="Подсказка:<br>Работа на улице в соответствии с графиком и адресом; Выплата ЗП по окончанию проекта на банковскую карту согласно отработанных часов"></div>
    </div>
    <button type="submit" class="btn__orange">Сохранить</button>
  </div>
  <input type="hidden" name="module" value="<?=$module?>">
</form>
