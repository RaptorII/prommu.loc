<?php
$bUrl = Yii::app()->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'private/page-prof-personal-area.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'vacancy/edit.css');
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'dist/jquery-ui.min.css');
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'private/personal.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'vacancy/edit.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/nicEdit.js', CClientScript::POS_END);
$this->pageTitle = $viData->data->title;
$this->breadcrumbs = [
  "Мои вакансии" => MainConfig::PAGE_USER_VACANCIES_LIST,
  $viData->data->title
];
?>
<div class="row" id="edit_vacancy">
  <?
  if($viData->data->date_public)
  {
    $this->renderPartial('../user/vacancy/top',['viData'=>$viData]);
    $this->renderPartial('../user/vacancy/edit/buttons',['viData'=>$viData]);
  }
  ?>
  <div class="vacancy__masonry">
    <? $this->renderPartial('../user/vacancy/edit/module_1',['viData'=>$viData]) ?>
    <div class="vacancy__module">
      <? $this->renderPartial('../user/vacancy/edit/module_2',['viData'=>$viData]) ?>
    </div>
    <? $this->renderPartial('../user/vacancy/edit/module_3',['viData'=>$viData]) ?>
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