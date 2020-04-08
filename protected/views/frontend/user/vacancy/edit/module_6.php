<? $module = 6; ?>
<div class="personal__area--capacity-name">Налоговый статус</div>
<?
//
?>
<div class="module_info<?=$viData->error_moodule==$module?' block__hide':''?>">
  <? if(!$viData->data->is_actual_remdate): ?>
    <a href="javascript:void(0)" class="personal__area--capacity-edit js-g-hashint" title="Редактировать"></a>
  <? endif; ?>
  <div class="group ppe__field">
    <div class="group__about">Статус специалиста, которого ищете</div>
    <div class="group__info"><?=Vacancy::SELF_EMPLOYED[$viData->data->self_employed]?></div>
  </div>
  <br>
</div>
<?
//
?>
<form class="module_form<?=$viData->error_moodule==$module?' block__visible':''?>" method="post" data-params='{"ajax":"true"}'>
  <a href="javascript:void(0)" class="personal__area--capacity-cancel js-g-hashint" title="Отмена"></a>
  <div class="form__field">
    <div class="form__field-content form__content-indent">
      <div class="form__field-input form__field-select prmu-required<?=($viData->errors['self_employed']?' prmu-error':'')?>" id="self_employed">
        <select name="self_employed">
          <? foreach (Vacancy::SELF_EMPLOYED as $key => $v): ?>
            <option value="<?=$key?>"<?=$viData->data->self_employed==$key?' selected="selected"':''?>><?=$v?></option>
          <? endforeach; ?>
        </select>
      </div>
    </div>
  </div>
  <div class="form__container">
    <button type="submit" class="btn__orange">Сохранить</button>
  </div>
  <input type="hidden" name="module" value="<?=$module?>">
</form>