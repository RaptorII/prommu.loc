<?php
$bUrl = Yii::app()->request->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . '/css/template.css');
$model->getData();
?>
  <h3><?=$this->pageTitle?></h3>
<? if(!is_object($model->item) && intval($model->getId)): ?>
  <div class="alert danger">Данные отсутствуют</div>
<? else: ?>
  <div class="row">
    <div class="hidden-xs hidden-sm col-md-2"></div>
    <div class="col-xs-12 col-sm-8">
      <table class="table table-bordered template-table">
        <tbody>
        <tr><td><b>ID</b></td><td><?=$model->item->id?></td></tr>
        <tr><td><b>Дата создания</b></td><td><?=Share::getDate($model->item->date)?></td></tr>
        <tr><td><b>Уникальный идентификатор пользователя</b></td><td><?=$model->item->user?></td></tr>
        <? if($model->item->id_user): ?>
          <tr><td><b>ID юзера в системе</b></td><td><?=$model->item->id_user
              . ' ' . AdminView::getUserProfileLink($model->item->id_user,$model->item->type)?></td></tr>
        <? endif; ?>
        <tr><td><b>Тип</b></td><td><?=Share::isEmployer($model->item->type) ? 'Работодатель' : 'Соискатель'?></td></tr>
        <? if($model->item->login): ?>
          <? if($model->item->login_type==UserRegister::$LOGIN_TYPE_PHONE): ?>
            <? $phone = Share::getPrettyPhone($model->item->login) ?>
            <tr><td><b>Телефон</b></td><td><?=$phone['code'] . $phone['phone']?></td></tr>
          <? else: ?>
            <tr><td><b>Email</b></td><td><?=$model->item->login?></td></tr>
          <? endif; ?>
        <? endif; ?>
        <? if(Share::isEmployer($model->item->type) && !empty($model->item->name)): ?>
          <tr><td><b>Компания</b></td><td><?=$model->item->name?></td></tr>
        <? elseif(!empty($model->item->name)): ?>
          <tr><td><b>Имя</b></td><td><?=$model->item->name?></td></tr>
          <tr><td><b>Фамилия</b></td><td><?=$model->item->surname?></td></tr>
        <? endif; ?>
        <tr><td><b>Подтверждение кодом</b></td><td><?=$model->item->is_confirm ? 'Да' : 'Нет'?></td></tr>
        <? if($model->item->is_confirm_time): ?>
          <tr><td><b>Время подтверждения</b></td><td><?=Share::getDate($model->item->is_confirm_time)?></td></tr>
        <? endif; ?>
        <tr><td><b>Регистрация через соцсети</b></td><td><?=$model->item->social ? 'Да' : 'Нет'?></td></tr>
        <tr><td colspan="2"><b>СЕО</b><br></td></tr>
        <tr><td><b>referer</b></td><td><?=$model->item->referer?></td></tr>
        <tr><td><b>transition</b></td><td><?=$model->item->transition?></td></tr>
        <tr><td><b>canal</b></td><td><?=$model->item->canal?></td></tr>
        <tr><td><b>campaign</b></td><td><?=$model->item->campaign?></td></tr>
        <tr><td><b>content</b></td><td><?=$model->item->content?></td></tr>
        <tr><td><b>keywords</b></td><td><?=$model->item->keywords?></td></tr>
        <tr><td><b>point</b></td><td><?=$model->item->point?></td></tr>
        <tr><td><b>last_referer</b></td><td><?=$model->item->last_referer?></td></tr>
        <tr><td><b>ip</b></td><td><?=$model->item->ip?></td></tr>
        <tr><td><b>pm_source</b></td><td><?=$model->item->pm_source?></td></tr>
        <tr><td><b>client</b></td><td><?=$model->item->client?></td></tr>
        <tr><td><b>Сайт</b></td><td><?=AdminView::getSubdomain($model->item->subdomen)?></td></tr>
        </tbody>
      </table>
    </div>
    <div class="hidden-xs hidden-sm col-md-2"></div>
    <div class="col-xs-12">
      <div class="pull-right">
        <a href="<?=$this->createUrl('/admin/register?user=' . $model->getType . '&state=' . $model->getState)?>" class="btn btn-success">Назад</a>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
  <style type="text/css">
    .template-table{
      background-color: #FFFFFF;
      font-size: 14px;
    }
    .template-table td:first-child{ width: 25% }
    .template-table tbody tr td,.template-table tbody tr th{ padding: 5px; }
    .template-table tbody tr:nth-child(odd){ background-color: #f4f4f4 }
  </style>
<? endif; ?>