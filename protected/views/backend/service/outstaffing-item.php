<?php
  $id = Yii::app()->getRequest()->getParam('id');
  $service = Yii::app()->getRequest()->getParam('service');
  $serviceTitle = Services::getServiceName($service);
  $title = 'Заказ #' . $id;
  $this->setPageTitle($title);
  $backLink = '/service/outstaffing/'.$service;
  $this->breadcrumbs = ['Все услуги'=>['/service'], $serviceTitle=>[$backLink], $title];
  $model = new Outstaffing();
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
              <tr><td><b>ID</b></td><td><?=$order->id_key?></td></tr>
              <tr><td><b>Наименование услуги</b></td><td><?=$serviceTitle?></td></tr>
              <tr>
                <td><b>Наименование подуслуг</b>
                </td><td><?=Outstaffing::getService($order)?></td>
              </tr>
              <tr>
                <td><b>Работодатель</b></td>
                <td><?=AdminView::getLink($viData['employer']['profile_admin'],$viData['employer']['name'])?></td>
              </tr>
              <tr>
                <td><b>Вакансии</b></td>
                <td>
                  <?
                  foreach ($viData['vacancies'] as $v)
                  {
                    echo AdminView::getLink('/admin/VacancyEdit/' . $v['id'],$v['title'] . '(' . $v['id'] . ')') . '<br>';
                  }
                  ?>
                </td>
              </tr>
              <tr><td colspan="2"></td></tr>
              <tr><td><b>Email</b></td><td><?=AdminView::getStr($order->email)?></td></tr>
              <tr><td><b>Телефон</b></td><td><?=AdminView::getStr($order->phone)?></td></tr>
              <tr><td><b>Свой вариант</b></td><td><?=AdminView::getStr($order->text)?></td></tr>
              <tr><td colspan="2"></td></tr>
              <tr><td><b>Дата обращения</b></td><td><?=Share::getPrettyDate($order->date)?></td></tr>
            </tbody>
          </table>
          <div class="pull-right">
            <a href="<?=$this->createUrl($backLink)?>" class="btn btn-success d-indent">Назад</a>
          </div>
        </div>
        <div class="hidden-xs col-sm-1 col-md-3"></div>
      </div>
    </div>
  </div>
<? endif; ?>
