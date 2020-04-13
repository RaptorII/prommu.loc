<?php
$bUrl = Yii::app()->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'private/page-prof-personal-area.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'vacancy/edit.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'dist/jquery-ui.min.css');
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'private/personal.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'vacancy/edit.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/nicEdit.js', CClientScript::POS_END);
?>
<div class="row">
  <? if(!$viData->data->is_actual_remdate): ?>
    <div class="col-xs-12">
      <a href="<?=MainConfig::$PAGE_VACPUB . "?duplicate=Y&id={$viData->data->id}"?>" class="btn__orange">Дублировать вакансию</a>
      <div class="personal__area--separator"></div>
    </div>
  <? endif; ?>
  <div class="col-xs-12 col-sm-6">
    <? //if(!($viData->data->status==Vacancy::$STATUS_NO_ACTIVE && count($viData->services->creation_vacancy->items))): ?>
    <div id="activate-block">
      <? $this->renderPartial('../user/vacancy/edit/module_1',['viData'=>$viData]) ?>
    </div>
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_2',['viData'=>$viData]) ?>
    </div>
    <? if($viData->data->is_actual): ?>
      <div class="personal__area--capacity module">
        <? $this->renderPartial('../user/vacancy/edit/module_3',['viData'=>$viData]) ?>
      </div>
    <? endif; ?>
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_4',['viData'=>$viData]) ?>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6">
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_5',['viData'=>$viData]) ?>
    </div>
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_6',['viData'=>$viData]) ?>
    </div>
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_7',['viData'=>$viData]) ?>
    </div>
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_8',['viData'=>$viData]) ?>
    </div>
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_9',['viData'=>$viData]) ?>
    </div>
  </div>
</div>