<? $module = 8; ?>
<div class="personal__area--capacity-name">Дополнительные требования</div>
<?
//
?>
<div class="module_info<?=$viData->error_moodule==$module?' block__hide':''?>">
  <? if($viData->data->is_actual_remdate && !count($viData->services->creation_vacancy->items)): ?>
    <a href="javascript:void(0)" class="personal__area--capacity-edit js-g-hashint" title="Редактировать"></a>
  <? endif; ?>
  <? if(!$viData->data->additional): ?>
    <div class="form__field">
      <div class="form__content-flex form__flex-vmiddle">
        <div class="form__content-indent form__content-hint">Раздел не заполнен...</div>
      </div>
    </div>
  <? else: ?>
    <? if($viData->data->ismed): ?>
      <div class="group ppe__field">
        <div class="group__info">Медкнижка</div>
      </div>
    <? endif; ?>
    <? if($viData->data->isavto): ?>
      <div class="group ppe__field">
        <div class="group__info">Автомобиль</div>
      </div>
    <? endif; ?>
    <? if($viData->data->smart): ?>
      <div class="group ppe__field">
        <div class="group__info">Смартфон</div>
      </div>
    <? endif; ?>
    <? if($viData->data->cardPrommu): ?>
      <div class="group ppe__field">
        <div class="group__info">Наличие банковской карты Prommu</div>
      </div>
    <? endif; ?>
    <? if($viData->data->card): ?>
      <div class="group ppe__field">
        <div class="group__info">Наличие банковской карты</div>
      </div>
    <? endif; ?>
    <br>
  <? endif; ?>
</div>
<?
//
?>
<form class="module_form<?=$viData->error_moodule==$module?' block__visible':''?>" method="post" data-params='{"ajax":"true"}'>
  <a href="javascript:void(0)" class="personal__area--capacity-cancel js-g-hashint" title="Отмена"></a>
  <div class="form__field">
    <div class="form__field-content">
      <div class="form__content-flex form__flex-wrap">
        <div class="form__content-3 form__content-pindent form__content-mfull">
          <input
            type="checkbox"
            name="medbook"
            value="1"
            id="medbook"
            class="form__field-checkbox"
            <?=$viData->data->ismed?' checked="checked"':''?>>
          <label for="medbook" class="form__checkbox-label">Медкнижка</label>
        </div>
        <div class="form__content-3 form__content-pindent form__content-mfull">
          <input
            type="checkbox"
            name="car"
            value="1"
            id="car"
            class="form__field-checkbox"
            <?=$viData->data->isavto?' checked="checked"':''?>>
          <label for="car" class="form__checkbox-label">Автомобиль</label>
        </div>
        <div class="form__content-3 form__content-pindent form__content-mfull">
          <input
            type="checkbox"
            name="smartphone"
            value="1"
            id="smartphone"
            class="form__field-checkbox"
            <?=$viData->data->smart?' checked="checked"':''?>>
          <label for="smartphone" class="form__checkbox-label">Смартфон</label>
        </div>
      </div>
    </div>
  </div>
  <div class="form__field">
    <div class="form__field-content">
      <div class="form__content-flex form__flex-wrap">
        <div class="form__content-pindent">
          <input
            type="checkbox"
            name="card_prommu"
            value="1"
            id="card_prommu"
            class="form__field-checkbox"
            <?=$viData->data->cardPrommu?' checked="checked"':''?>>
          <label for="card_prommu" class="form__checkbox-label">Наличие банковской карты Prommu</label>
        </div>
        <div class="form__content-pindent">
          <input
            type="checkbox"
            name="card"
            value="1"
            id="card"
            class="form__field-checkbox"
            <?=$viData->data->card?' checked="checked"':''?>>
          <label for="card" class="form__checkbox-label">Наличие банковской карты</label>
        </div>
      </div>
    </div>
  </div>
  <div class="form__container">
    <button type="submit" class="btn__orange">Сохранить</button>
  </div>
  <input type="hidden" name="module" value="<?=$module?>" class="prmu-required">
</form>