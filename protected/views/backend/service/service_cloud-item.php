<?php
  $id = Yii::app()->getRequest()->getParam('id');
  $service = Yii::app()->getRequest()->getParam('service');
  $service=='creation_vacancy' && $service='creation-vacancy';
  $serviceTitle = Services::getServiceName($service);
  $title = 'Заказ #' . $id;
  $this->setPageTitle($title);
  $backLink = '/service/service_cloud/'.$service;
  $this->breadcrumbs = ['Все услуги'=>['/service'], $serviceTitle=>[$backLink], $title];
  $model = new Service();
  $model->setAdminViewed($id);
  $viData = $model->getOrder($id);
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCssFile($bUrl . '/css/service/item.css');
  $gcs->registerScriptFile($bUrl . '/js/service/item.js', CClientScript::POS_END);
?>
  <h3><?=$this->pageTitle?></h3>
<? if(!$viData['item'] && intval($id)): ?>
  <div class="alert danger">Данные отсутствуют</div>
<? else: ?>
  <? $order = $viData['item']; ?>
  <form method="post" id="service_form">
    <div class="row">
      <div class="col-xs-12">
        <div class="row">
          <div class="hidden-xs hidden-sm col-md-2"></div>
          <div class="col-xs-12 col-md-8">
            <table class="table table-bordered template-table">
              <tbody>
                <tr><td><b>Наименование услуги</b></td><td><?=$serviceTitle?></td></tr>
                <tr>
                  <td><b>ID</b></td>
                  <td>
                    <?=$order->id?>
                    <input type="hidden" name="Service_cloud[id]" value="<?=$order->id?>">
                  </td>
                </tr>
                <tr>
                  <td><b>ID_USER работодателя</b></td>
                  <td><?=$order->id_user?></td>
                </tr>
                <tr>
                  <td><b>Работодатель</b></td>
                  <td>
                    <?=AdminView::getLink($viData['employer']['profile_admin'],$viData['employer']['name'])?>
                    <input type="hidden" name="Service_cloud[id_user]" value="<?=$order->id_user?>">
                  </td>
                </tr>
                <tr>
                  <td><b>ID вакансии</b></td>
                  <td><?=$order->name?></td>
                </tr>
                <tr>
                  <td><b>Вакансия</b></td>
                  <td>
                    <?=AdminView::getLink("/admin/VacancyEdit/".$viData['vacancy']['id'],$viData['vacancy']['title'])?>
                    <input type="hidden" name="Service_cloud[vacancy]" value="<?=$viData['vacancy']['id']?>">
                  </td>
                </tr>
                <? if($service=='vacancy'): ?>
                  <tr><td colspan="2"></td></tr>
                  <tr><td><b>Дата начала</b></td><td><?=Share::getDate(strtotime($order->bdate),"d.m.Y")?></td></tr>
                  <tr><td><b>Дата окончания</b></td><td><?=Share::getDate(strtotime($order->bdate),"d.m.Y")?></td></tr>
                <? endif; ?>
<<<<<<< HEAD
                <? if(in_array($service,['vacancy','email','sms','upvacancy','creation-vacancy'])): ?>
=======
                <? if(in_array($service,['vacancy','email','sms','upvacancy','personal-invitation'])): ?>
>>>>>>> add new service Personal-invitation
                  <tr><td><b>Cумма</b></td><td><?=$order->sum?></td></tr>
                  <tr>
                    <td>
                      <b>Состояние</b>
                      <span class="label label-<?=(!$order->status ? 'warning' : 'success')?>"><?=(!$order->status ? 'Не оплачено' : 'Оплачено')?></span>
                    </td>
                    <td>
                      <select name="Service_cloud[status]" class="form-control d-small">
                        <option value="0"<?=(!$order->status?' selected':'')?>>Не оплачено</option>
                        <option value="1"<?=($order->status?' selected':'')?>>Оплачено</option>
                      </select>
                    </td>
                  </tr>
                  <tr>
                    <td><b>Транзакция</b></td>
                    <td><input type="text" class="form-control d-small" name="Service_cloud[key]" value="<?=$order->key?>"></td>
                  </tr>
                  <tr>
                    <td><b>Юр. счет</b></td>
                    <td><?=AdminView::getLink(MainConfig::$PAGE_LEGAL_ENTITY_RECEIPT.$order->legal,$order->legal)?>
                    </td>
                  </tr>
                <? endif; ?>
                <? if($service=='repost'): ?>
                  <tr>
                    <td><b>Социальная сеть</b></td>
                    <td>
                      <?
                      switch ($order->text)
                      {
                        case 'fb': echo 'Facebook'; break;
                        case 'vk': echo 'Vkontakte'; break;
                        case 'telegram': echo 'Telegram'; break;
                      }
                      ?>
                    </td>
                  </tr>
                <? elseif($service=='api'): ?>
                  <tr><td><b>Услуга API</b></td><td><?=$order->text?></td></tr>
                <? elseif($service=='api'): ?>
                  <tr><td><b>Сообщение соискателям</b></td><td><?=$order->text?></td></tr>
                <? endif; ?>
                <? if(in_array($service,['email','push','sms']) && count($viData['applicants'])): ?>
                  <tr>
                    <td><b>Соискатели(<?=count($viData['applicants'])?>)</b></td>
                    <td>
                      <div class="applicants_td">
                      <?
                        foreach ($viData['applicants'] as $v)
                        {
                          echo AdminView::getLink($v['profile_admin'],$v['name'] . '(' . $v['id'] . ')') . '<br>';
                        }
                        if(count($viData['applicants'])>20)
                        {
                          ?><a href="javascript:void(0)" class="btn btn-success">...</a><?
                        }
                      ?>
                      </div>
                    </td>
                  </tr>
                <? endif; ?>
                <tr><td><b>Дата</b></td><td><?=Share::getPrettyDate($order->date)?></td></tr>
              </tbody>
            </table>
            <div class="pull-right">
<<<<<<< HEAD
              <? if(in_array($service,['vacancy','email','sms','upvacancy','creation-vacancy']) && !empty($order->legal)): ?>
=======
              <? if(in_array($service,['vacancy','email','sms','upvacancy','personal-invitation']) && !empty($order->legal)): ?>
>>>>>>> add new service Personal-invitation
                <? if(!$order->status): ?>
                  <span class="btn btn-success d-indent" id="start_service">Запустить услугу</span>
                <? endif; ?>
                <button type="submit" class="btn btn-success d-indent">Сохранить</button>
              <? endif; ?>
              <a href="<?=$this->createUrl($backLink)?>" class="btn btn-success d-indent">Назад</a>
              <a href="<?=$this->createUrl('/service')?>" class="btn btn-success d-indent">Все услуги</a>
            </div>
          </div>
          <div class="hidden-xs col-sm-1 col-md-3"></div>
        </div>
      </div>
    </div>
    <input type="checkbox" value="1" name="Service_cloud[start_service]" class="hide" id="start_service-input">
    <input type="hidden" name="Service_cloud[service]" value="<?=$service?>">
  </form>
<? endif; ?>