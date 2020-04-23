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
<div class="row" id="edit_vacancy">
  <? if(!$viData->data->is_actual_remdate): ?>
    <div class="col-xs-12">
      <a href="<?=MainConfig::$PAGE_VACPUB . "?duplicate=Y&id={$viData->data->id}"?>" class="btn__orange">Дублировать вакансию</a>
      <div class="personal__area--separator"></div>
    </div>
  <? endif; ?>
  <div class="vacancy__masonry <?//personal__area personal__area-flex?>">
    <? //if(!($viData->data->status==Vacancy::$STATUS_NO_ACTIVE && count($viData->services->creation_vacancy->items))): ?>
    <div class="vacancy__module" id="activate_module">
      <? $this->renderPartial('../user/vacancy/edit/module_1',['viData'=>$viData]) ?>
    </div>
    <div class="vacancy__module">
      <? $this->renderPartial('../user/vacancy/edit/module_2',['viData'=>$viData]) ?>
    </div>
    <? if($viData->data->is_actual): ?>
      <div class="vacancy__module">
        <? $this->renderPartial('../user/vacancy/edit/module_3',['viData'=>$viData]) ?>
      </div>
    <? endif; ?>
    <div class="vacancy__module">
      <? $this->renderPartial('../user/vacancy/edit/module_4',['viData'=>$viData]) ?>
    </div>
    <div class="vacancy__module">
      <? $this->renderPartial('../user/vacancy/edit/module_5',['viData'=>$viData]) ?>
    </div>
    <div class="vacancy__module">
      <? $this->renderPartial('../user/vacancy/edit/module_6',['viData'=>$viData]) ?>
    </div>
    <div class="vacancy__module">
      <? $this->renderPartial('../user/vacancy/edit/module_7',['viData'=>$viData]) ?>
    </div>
    <div class="vacancy__module">
      <? $this->renderPartial('../user/vacancy/edit/module_8',['viData'=>$viData]) ?>
    </div>
    <div class="clearfix"></div>
  </div>
  <div class="vacancy__module" id="geo_module">
    <? $this->renderPartial('../user/vacancy/edit/module_9',['viData'=>$viData]) ?>
  </div>
</div>