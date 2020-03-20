<?
$module = 6;
$arSalary = VacancyView::getSalary(
  $viData->data->shour,
  $viData->data->sweek,
  $viData->data->smonth,
  $viData->data->svisit
)
?>
<div class="personal__area--capacity-name">Оплата</div>
<?
//
?>
<div class="module_info<?=$viData->error_moodule==$module?' block__hide':''?>">
  <a href="javascript:void(0)" class="personal__area--capacity-edit js-g-hashint" title="Редактировать"></a>
  <div class="group ppe__field">
    <div class="group__about">Заработная плата</div>
    <div class="group__info"><?=$arSalary['full']?></div>
  </div>
  <div class="group ppe__field">
    <div class="group__about"><?=$viData->data->properties['paylims']['name']?></div>
    <div class="group__info"><?=$viData->data->properties['paylims']['value']?></div>
  </div>
  <? if(!empty($viData->data->properties['salary-comment']['value'])): ?>
    <div class="group ppe__field">
      <div class="group__about">Комментарии</div>
      <div class="group__info"><?=$viData->data->properties['salary-comment']['value']?></div>
    </div>
  <? endif; ?>
  <br>
</div>
<?
//
?>
<form class="module_form<?=$viData->error_moodule==$module?' block__visible':''?>" method="post" data-params='{"ajax":"true"}'>
  <a href="javascript:void(0)" class="personal__area--capacity-cancel js-g-hashint" title="Отмена"></a>
  <div class="form__field form__field-first">
    <label class="form__field-label text__nowrap">Заработная плата <span class="text__red">*</span></label>
    <div class="form__field-content form__content-indent form__content-hint">
      <? if($viData->errors['salary']): ?>
        <span class="prmu-error-mess">Поле обязательно к заполнению</span>
      <? endif; ?>
      <div class="form__content-flex">
        <div class="form__content-2">
          <input
            type="text"
            name="salary"
            value="<?=$arSalary['salary']?>"
            class="form__field-input prmu-required prmu-check<?=($viData->errors['salary']?' prmu-error':'')?>"
            data-params='{"limit":"6","regexp":"\\D+","parent_tag":".form__field-content","message":"Поле обязательно к заполнению"}'
            autocomplete="off">
        </div>
        <div class="form__field-dash"> </div>
        <div class="form__content-2">
          <div class="form__field-input form__field-select prmu-required<?=($viData->errors['salary_type']?' prmu-error':'')?>" id="salary">
            <select name="salary_type">
              <? foreach (Vacancy::SALARY_TYPE as $key => $v): ?>
                <option value="<?=$key?>"<?=$arSalary['salary_type']==$key?' selected="selected"':''?>><?=$v?></option>
              <? endforeach; ?>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div class="form__field-hint tooltip" title="Подсказка будет подтягиваться соответственно региону"></div>
  </div>
  <div class="form__field">
    <label class="form__field-label text__nowrap">Сроки оплаты</label>
    <div class="form__field-content form__content-indent">
      <? if($viData->errors['salary_time']): ?>
        <span class="prmu-error-mess">Поле обязательно к заполнению</span>
      <? endif; ?>
      <div class="form__field-input form__field-select prmu-required<?=($viData->errors['salary_time']?' prmu-error':'')?>" id="salary_time">
        <select name="salary_time">
          <? foreach ($viData->attributes->lists['paylims'] as $key => $v): ?>
            <option value="<?=$key?>"<?=$viData->data->properties['paylims']['id']==$key?' selected="selected"':''?>><?=$v?></option>
          <? endforeach; ?>
        </select>
      </div>
    </div>
  </div>
  <div class="form__field">
    <label class="form__field-label text__nowrap">Комментарии</label>
    <div class="form__field-content form__content-indent">
        <textarea
          class="form__field-input form__textarea"
          name="salary_comment"><?=$viData->data->properties['salary-comment']['value']?></textarea>
    </div>
  </div>
  <div class="form__container">
    <button type="submit" class="btn__orange">Сохранить</button>
  </div>
  <input type="hidden" name="module" value="<?=$module?>">
</form>