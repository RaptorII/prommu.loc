<?php
$bUrl = Yii::app()->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'private/page-prof-personal-area.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'vacancy/edit.css');
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'private/personal.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'vacancy/edit.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/nicEdit.js', CClientScript::POS_END);

display($viData->data);

?>
<div class="row">
  <div class="col-xs-12 col-sm-6">
    <? if($viData->data->status==Vacancy::$STATUS_NO_ACTIVE): ?>
      <div class="personal__area--capacity border__red">
        <div class="personal__area--capacity-name text__red">Важно!</div>
        <p class="text__justify text__red">На данный момент Ваша вакансия сохранена, но не опубликована - Вы можете опубликовать вакансию сразу,
          нажав кнопку “ОПУБЛИКОВАТЬ ВАКАНСИЮ” или согласно наших рекомендаций заполнить дополнительные данные,
          которые помогут оперативнее и качественнее, в сжатые сроки, найти необходимый персонал,
          ну и главное проверить корректность введения данных по Вашей вакансии.</p>
        <a href="/<?= MainConfig::$PAGE_VACACTIVATE . "?id={$viData->data->id}" ?>" class="btn__orange">ОПУБЛИКОВАТЬ ВАКАНСИЮ</a>
        <br>
        <br>
      </div>
    <? endif; ?>
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_1',['viData'=>$viData]) ?>
    </div>
    <? if($viData->data->is_actual): ?>
      <div class="personal__area--capacity module">
        <? $this->renderPartial('../user/vacancy/edit/module_2',['viData'=>$viData]) ?>
      </div>
    <? endif; ?>
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_3',['viData'=>$viData]) ?>
    </div>
  </div>
  <div class="col-xs-12 col-sm-6">
    <div class="personal__area--capacity module">
      <? $this->renderPartial('../user/vacancy/edit/module_4',['viData'=>$viData]) ?>
    </div>
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
  </div>
</div>