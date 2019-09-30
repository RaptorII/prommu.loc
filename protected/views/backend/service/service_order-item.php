<?php
  $id = Yii::app()->getRequest()->getParam('id');
  $title = 'Заказ #' . $id;
  $this->setPageTitle($title);
  $this->breadcrumbs = ['Все услуги'=>['/service'], 'Заказ услуг гостями'=>['/service/guest_order'], $title];
  $model = new ServiceGuestOrder();
  $model->setAdminViewed($id);
  $viData = $model->getOrder($id);
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCssFile($bUrl . '/css/service/item.css');
?>
<h3><?=$this->pageTitle?></h3>
<? if(!$viData['item'] && intval($id)): ?>
  <div class="alert danger">Данные отсутствуют</div>
<? else: ?>
  <? $order = $viData['item']; ?>
  <div class="row">
    <div class="col-xs-12">
      <div class="row">
        <div class="hidden-xs hidden-sm col-md-2"></div>
        <div class="col-xs-12 col-md-8">
          <table class="table table-bordered template-table">
            <tbody>
              <tr><td><b>ID</b></td><td><?=$order->id?></td></tr>
              <tr><td><b>Дата обращения</b></td><td><?=Share::getPrettyDate($order->crdate)?></td></tr>
              <tr><td><b>Наименование услуги</b></td><td><?=Services::getServiceName($order->id_se)?></td></tr>
              <tr><td><b>ФИО</b></td><td><?=$order->fio?></td></tr>
              <tr><td><b>Телефон</b></td><td><?=$order->tel?></td></tr>
              <tr><td><b>Email</b></td><td><?=$order->email?></td></tr>
              <tr><td colspan="2"></td></tr>
              <tr><td><b>Тип трафика</b></td><td><?=$order->referer?></td></tr>
              <tr><td><b>Источник</b></td><td><?=$order->transition?></td></tr>
              <tr><td><b>Канал</b></td><td><?=$order->canal?></td></tr>
              <tr><td><b>Кампания</b></td><td><?=$order->campaign?></td></tr>
              <tr><td><b>Контент</b></td><td><?=$order->content?></td></tr>
              <tr><td><b>Ключевые слова</b></td><td><?=$order->keywords?></td></tr>
              <tr><td><b>Точка входа</b></td><td><?=$order->point?></td></tr>
              <tr><td><b>Реферер</b></td><td><?=$order->last_referer?></td></tr>
              <tr><td><b>Roistat</b></td><td><?=$order->roistat?></td></tr>
            </tbody>
          </table>
          <div class="pull-right">
            <a href="<?=$this->createUrl('service_order')?>" class="btn btn-success d-indent">Назад</a>
          </div>
        </div>
        <div class="hidden-xs col-sm-1 col-md-3"></div>
      </div>
    </div>
  </div>
<? endif; ?>
