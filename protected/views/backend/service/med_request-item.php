<?php
  $id = Yii::app()->getRequest()->getParam('id');
  $serviceTitle = Services::getServiceName('medbook');
  $title = 'Заказ #' . $id;
  $this->setPageTitle($title);
  $backLink = '/service/med_request';
  $this->breadcrumbs = ['Все услуги'=>['/service'], $serviceTitle=>[$backLink], $title];
  $model = new MedCard();
  $model->setAdminViewed($id);
  $viData = $model->getOrder($id);
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();

  $gcs->registerCssFile($bUrl . '/css/service/item.css');
  $gcs->registerScriptFile($bUrl . '/js/service/item.js', CClientScript::POS_END);
  $gcs->registerScriptFile($bUrl . '/js/nicEdit.js', CClientScript::POS_HEAD);
?>
  <h3><?=$this->pageTitle?></h3>
<? if(!$viData['item'] && intval($id)): ?>
  <div class="alert danger">Данные отсутствуют</div>
<? else: ?>
  <form method="post" enctype="multipart/form-data">
    <div class="row">
      <div class="hidden-xs hidden-sm col-md-2"></div>
      <div class="col-xs-12 col-sm-6 col-md-4">
        <?
        //
        ?>
        <h4>Личные данные</h4>
        <label class="d-label">
          <span>Фамилия</span>
          <input class="form-control" value="<?=$viData['item']['fff']?>" name="Card[fff]">
        </label>
        <label class="d-label">
          <span>Имя</span>
          <input class="form-control" value="<?=$viData['item']['iii']?>" name="Card[iii]">
        </label>
        <label class="d-label">
          <span>Телефон</span>
          <input class="form-control" value="<?=$viData['item']['tel']?>" name="Card[tel]">
        </label>
        <label class="d-label">
          <span>Email</span>
          <input class="form-control" value="<?=$viData['item']['email']?>" name="Card[email]">
        </label>
        <label class="d-label">
          <span>Выбранный адрес</span>
          <? $arIndex = MedCard::getIndex(); ?>
          <select name="Card[regaddr]" class="form-control">
            <? foreach ($arIndex as $v): ?>
              <option value="<?=$v?>"<?=($viData['item']['regaddr']==$v)?' selected':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </label>
        <label class="d-label">
          <span>Способ оплаты</span>
          <? $arIndex = MedCard::getPayType(); ?>
          <select name="Card[pay]" class="form-control">
            <? foreach ($arIndex as $v): ?>
              <option value="<?=$v?>"<?=($viData['item']['pay']==$v)?' selected':''?>><?=$v?></option>
            <? endforeach; ?>
          </select>
        </label>
        <label class="d-label">
          <span>Комментарий юзера</span>
          <textarea name="Card[comment]" class="form-control"><?=$viData['item']['comment']?></textarea>
        </label>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-4">
        <?
        //
        ?>
        <h4>Общая информация</h4>
        <label class="d-label">
          <span>Дата создания</span>
          <input class="form-control" value="<?=Share::getPrettyDate($viData['item']['crdate'])?>" name="Card[crdate]" disabled>
        </label>
        <label class="d-label">
          <span>Статус</span>
          <select name="Card[status]" class="form-control">
            <option value="0"<?=($viData['item']['status']==0)?' selected':''?>>Новая</option>
            <option value="1"<?=($viData['item']['status']==1)?' selected':''?>>Просмотрена</option>
            <option value="2"<?=($viData['item']['status']==2)?' selected':''?>>Отменена</option>
            <option value="3"<?=($viData['item']['status']==3)?' selected':''?>>Обработка</option>
            <option value="4"<?=($viData['item']['status']==4)?' selected':''?>>Не хватает данных</option>
            <option value="5"<?=($viData['item']['status']==5)?' selected':''?>>Выполнена</option>
          </select>
        </label>
        <label class="d-label">
          <span>Комментарий админа</span>
          <div id="comment-panel"></div>
          <textarea name="Card[comad]" class="form-control" id="comment"><?=$viData['item']['comad']?></textarea>
        </label>
      </div>
      <div class="hidden-xs hidden-sm col-md-2"></div>
      <div class="col-xs-12">
        <div class="pull-right">
          <button type="submit" class="btn btn-success">Сохранить</button>
          <a href="<?=$this->createUrl($backLink)?>" class="btn btn-success">Назад</a>
          <a href="<?=$this->createUrl('/service')?>" class="btn btn-success">Все услуги</a>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </form>
<? endif; ?>