<?php
if(!$viData->data->is_actual_remdate && $viData->data->date_public)
  $col = 3;
elseif(!$viData->data->is_actual_remdate)
  $col = 4;
elseif ($viData->data->is_actual)
  $col = 3;
elseif ($viData->data->date_public)
  $col = 4;
else
  $col = 6;
?>
<div class="col-xs-12">
  <div class="row vacancy__buttons">
    <? if($viData->data->date_public): ?>
      <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
        <p class="vacancy__date-public">Вакансия опубликована: <span class="text__nowrap"><?=$viData->data->date_public?></span></p>
      </div>
    <? endif; ?>
    <? if(!$viData->data->is_actual_remdate): // Вакансия завершена ?>
      <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
        <a href='<?=MainConfig::$PAGE_REVIEWS?>' class="btn__orange">Оценить персонал</a>
      </div>
    <? endif; ?>
    <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
      <a href="<?=MainConfig::$PAGE_VACPUB . "?duplicate=Y&id={$viData->data->id}"?>" class="btn__orange">Дублировать вакансию</a>
      <span class="form__field-hint tooltip" title="Выберите одну должность, которая необходима Вам для набора персонала.
            Если Вам необходимо подобрать несколько должностей Вы сможете дублировать размещенную
            вакансию и при этом изменить должность или другие параметры вакансии"></span>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
      <a href="<?=MainConfig::$VIEW_CHECK_SELF_EMPLOYED?>" class="btn__orange">Проверка налогового статуса</a>
    </div>
    <? if($viData->data->is_actual && $viData->data->is_actual_remdate): ?>
      <div class="col-xs-12 col-sm-6 col-md-<?=$col?> vacancy__buttons-item">
        <a href="javascript:void(0)" class="btn__orange" id="deactivate">Скрыть вакансию</a>
      </div>
    <? endif; ?>
  </div>
  <div class="personal__area--separator"></div>
</div>
<div class="clearfix"></div>
