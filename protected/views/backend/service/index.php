<?php
  $title = 'Все услуги';
  $this->setPageTitle($title);
  $this->breadcrumbs = [$title];
?>
<div class = "col-xs-12 col-sm-8 col-md-4">
  <ul class="nav user__menu" id="tablist">
    <li>
      <a href="<?=$this->createUrl('service/service_order')?>">
        <i class="glyphicon glyphicon-envelope"></i>
        <span>Заказ услуг гостями</span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/service_cloud/creation_vacancy')?>">
        <i class="glyphicon glyphicon-floppy-disk"></i>
        <span><?=Services::getServiceName('creation-vacancy')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/service_cloud/vacancy')?>">
        <i class="glyphicon glyphicon-star-empty"></i>
        <span><?=Services::getServiceName('vacancy')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/service_cloud/upvacancy')?>">
        <i class="glyphicon glyphicon-level-up"></i>
        <span><?=Services::getServiceName('upvacancy')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/service_cloud/email')?>">
        <i class="glyphicon">@</i>
        <span><?=Services::getServiceName('email')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/service_cloud/push')?>">
        <i class="glyphicon glyphicon-comment"></i>
        <span><?=Services::getServiceName('push')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/service_cloud/sms')?>">
        <i class="glyphicon glyphicon-envelope"></i>
        <span><?=Services::getServiceName('sms')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/service_cloud/repost')?>">
        <i class="glyphicon glyphicon-bullhorn"></i>
        <span><?=Services::getServiceName('repost')?></span>
      </a>
    </li>
    <li>
      <a href="#" onclick="alert('Страница в разработке'); return false">
        <i class="glyphicon glyphicon-globe"></i>
        <span>Геолокация</span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/outstaffing/outsourcing')?>">
        <i class="glyphicon glyphicon-check"></i>
        <span><?=Services::getServiceName('outsourcing')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/outstaffing/outstaffing')?>">
        <i class="glyphicon glyphicon-edit"></i>
        <span><?=Services::getServiceName('outstaffing')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/card_request')?>">
        <i class="glyphicon glyphicon-credit-card"></i>
        <span><?=Services::getServiceName('card')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/med_request')?>">
        <i class="glyphicon glyphicon-plus-sign"></i>
        <span><?=Services::getServiceName('medbook')?></span>
      </a>
    </li>
    <li>
      <a href="<?=$this->createUrl('service/service_cloud/api')?>">
        <i class="glyphicon glyphicon-cog"></i>
        <span><?=Services::getServiceName('api')?></span>
      </a>
    </li>
  </ul>
</div>