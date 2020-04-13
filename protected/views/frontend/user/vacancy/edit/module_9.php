<script>
  var arVacCities = <?=json_encode($viData->data->cities)?>;
  var arVacLocations = <?=json_encode($viData->data->locations)?>;
  var arCitiesNotPaid = <?=json_encode($viData->services->creation_vacancy->cities)?>;
  var arPaymentMain = <?=json_encode(ServiceCloud::PAYMENT_FOR_CREATE)?>;
</script>
<div class="personal__area--capacity-name">Город, адрес, дата и время работы</div>
<?
//
?>
<div class="module_info<?=Yii::app()->getRequest()->isAjaxRequest?' block__hide':''?>">
  <? if($viData->data->is_actual_remdate && !count($viData->services->creation_vacancy->items)): ?>
    <a href="javascript:void(0)" class="personal__area--capacity-edit js-g-hashint" title="Редактировать"></a>
  <? endif; ?>
  <div id="location"></div>
  <br>
</div>
<?
//
?>
<form class="module_form<?=Yii::app()->getRequest()->isAjaxRequest?' block__visible':''?>" method="post">
  <a href="javascript:void(0)" class="personal__area--capacity-cancel js-g-hashint" title="Отмена"></a>
  <div id="location-edit"></div>
  <span class="btn__orange" id="city-add">Добавить город</span>
</form>