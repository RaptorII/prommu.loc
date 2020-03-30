<? $module = 8; ?>
<script>
  var arVacCities = <?=json_encode($viData->data->cities)?>;
  var arVacLocations = <?=json_encode($viData->data->locations)?>;
</script>
<div class="personal__area--capacity-name">Город, адрес, дата и время работы</div>
<?
//
?>
<div class="module_info<?=Yii::app()->getRequest()->isAjaxRequest?' block__hide':''?>">
  <a href="javascript:void(0)" class="personal__area--capacity-edit js-g-hashint" title="Редактировать"></a>
  <div id="location"></div>
  <br>
</div>
<?
//
?>
<form class="module_form<?=Yii::app()->getRequest()->isAjaxRequest?' block__visible':''?>" method="post">
  <a href="javascript:void(0)" class="personal__area--capacity-cancel js-g-hashint" title="Отмена"></a>
  <div id="location-edit"></div>
  <input type="hidden" name="module" value="<?=$module?>" class="prmu-required">
  <span class="btn__orange" id="city-add">Добавить город</span>
</form>