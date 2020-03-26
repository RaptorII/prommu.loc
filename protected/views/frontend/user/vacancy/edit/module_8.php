<? $module = 8; ?>
<div class="personal__area--capacity-name">Город, адрес, дата и время работы</div>
<?
//
?>
<div class="module_info<?=$viData->error_moodule==$module?' block__hide':''?>">
  <a href="javascript:void(0)" class="personal__area--capacity-edit js-g-hashint" title="Редактировать"></a>
  <div id="location"></div>
  <script>
    $(document).ready(function () {
      new VacancyGeo({
        'cities':<?=json_encode($viData->data->cities)?>,
        'locations':<?=json_encode($viData->data->locations)?>,
        'selector':'#location'
      })
    });
  </script>
  <br>
</div>
<?
//
?>
<form class="module_form<?=$viData->error_moodule==$module?' block__visible':''?>" method="post" data-params='{"ajax":"true"}'>
  <a href="javascript:void(0)" class="personal__area--capacity-cancel js-g-hashint" title="Отмена"></a>
  <div class="form__container">
    <button type="submit" class="btn__orange">Сохранить</button>
  </div>
  <? foreach ($viData->data->cities as $v1): ?>
  <? endforeach; ?>
  <input type="hidden" name="module" value="<?=$module?>" class="prmu-required">
</form>