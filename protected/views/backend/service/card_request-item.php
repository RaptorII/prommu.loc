<?php
  $id = Yii::app()->getRequest()->getParam('id');
  $serviceTitle = Services::getServiceName('card');
  $title = 'Заказ #' . $id;
  $this->setPageTitle($title);
  $backLink = '/service/card_request';
  $this->breadcrumbs = ['Все услуги'=>['/service'], $serviceTitle=>[$backLink], $title];
  $model = new UserCard();
  $model->setAdminViewed($id);
  $model = new CardRequest();
  $viData = $model->getCard($id);
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();
  // Magnific Popup
  $gcs->registerCssFile('/theme/css/dist/magnific-popup-min.css');
  $gcs->registerScriptFile('/theme/js/dist/jquery.magnific-popup.min.js', CClientScript::POS_END);

  $gcs->registerCssFile($bUrl . '/css/service/item.css');
  $gcs->registerScriptFile($bUrl . '/js/service/item.js', CClientScript::POS_END);
  $gcs->registerScriptFile($bUrl . '/js/nicEdit.js', CClientScript::POS_HEAD);
?>
<h3><?=$this->pageTitle?></h3>
<? if(!is_array($viData) && intval($id)): ?>
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
          <input class="form-control" value="<?=$viData['fff']?>" name="Card[fff]">
        </label>
        <label class="d-label">
          <span>Имя</span>
          <input class="form-control" value="<?=$viData['iii']?>" name="Card[iii]">
        </label>
        <label class="d-label">
          <span>Отчество</span>
          <input class="form-control" value="<?=$viData['ooo']?>" name="Card[ooo]">
        </label>
        <label class="d-label">
          <span>Дата рождения (дд.мм.гггг)</span>
          <?
            echo $this->widget('zii.widgets.jui.CJuiDatePicker',
              [
                'name'=>'Card[borndate]',
                'value'=>$viData['borndate'],
                'options' => ['changeMonth' => true],
                'htmlOptions' => ['class' => 'form-control d-small', 'autocomplete' => 'off']
              ],
              true
            );
          ?>
        </label>
        <label class="d-label">
          <span>Место рождения</span>
          <input class="form-control" value="<?=$viData['bornplace']?>" name="Card[bornplace]">
        </label>
        <label class="d-label">
          <span>Телефон</span>
          <input class="form-control" value="<?=$viData['bornplace']?>" name="Card[bornplace]">
        </label>
        <?
        //
        ?>
        <h4>Паспортные данные</h4>
        <label class="d-label">
          <span>Тип удостоверения личности (21-паспорт)</span>
          <input class="form-control" value="<?=$viData['doctype']?>" name="Card[doctype]">
        </label>
        <label class="d-label">
          <span>Серия паспорта</span>
          <input class="form-control" value="<?=$viData['docser']?>" name="Card[docser]">
        </label>
        <label class="d-label">
          <span>Номер паспорта</span>
          <input class="form-control" value="<?=$viData['docnum']?>" name="Card[docnum]">
        </label>
        <label class="d-label">
          <span>Дата выдачи (дд.мм.гггг)</span>
          <?
          echo $this->widget('zii.widgets.jui.CJuiDatePicker',
            [
              'name'=>'Card[docdate]',
              'value'=>$viData['docdate'],
              'options' => ['changeMonth' => true],
              'htmlOptions' => ['class' => 'form-control d-small', 'autocomplete' => 'off']
            ],
            true
          );
          ?>
        </label>
        <label class="d-label">
          <span>Код подразделения выдавший документ (паспорт)</span>
          <input class="form-control" value="<?=$viData['docorgcode']?>" name="Card[docorgcode]">
        </label>
        <label class="d-label">
          <span>Адрес прописки</span>
          <input class="form-control" value="<?=$viData['regaddr']?>" name="Card[regaddr]">
        </label>
        <label class="d-label">
          <span>Адрес фактического проживания</span>
          <input class="form-control" value="<?=$viData['liveaddr']?>" name="Card[liveaddr]">
        </label>
        <label class="d-label">
          <span>Кем выдан документ (паспорт)</span>
          <input class="form-control" value="<?=$viData['docorgname']?>" name="Card[docorgname]">
        </label>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-4">
        <?
        //
        ?>
        <h4>Общая информация</h4>
        <label class="d-label">
          <span>Фирма</span>
          <input class="form-control" value="<?=$viData['name']?>" name="Card[name]">
        </label>
        <label class="d-label">
          <span>Должность</span>
          <input class="form-control" value="<?=$viData['post']?>" name="Card[post]" disabled>
        </label>
        <label class="d-label">
          <span>Дата создания</span>
          <input class="form-control" value="<?=Share::getPrettyDate($viData['crdate'])?>" name="Card[crdate]" disabled>
        </label>
        <label class="d-label">
          <span>Комментарий юзера</span>
          <input class="form-control" value="<?=$viData['comment']?>" name="Card[comment]">
        </label>
        <label class="d-label">
          <span>Статус</span>
          <select name="Card[status]" class="form-control">
            <option value="0"<?=($viData['status']==0)?' selected':''?>>Новая</option>
            <option value="1"<?=($viData['status']==1)?' selected':''?>>Просмотрена</option>
            <option value="2"<?=($viData['status']==2)?' selected':''?>>Отменена</option>
            <option value="3"<?=($viData['status']==3)?' selected':''?>>Обработка</option>
            <option value="4"<?=($viData['status']==4)?' selected':''?>>Не хватает данных</option>
            <option value="5"<?=($viData['status']==5)?' selected':''?>>Выполнена</option>
          </select>
        </label>
        <label class="d-label">
          <span>Комментарий админа</span>
          <div id="comment-panel"></div>
          <textarea name="Card[comad]" class="form-control" id="comment"><?=$viData['comad']?></textarea>
        </label>
        <? if(!empty($viData['files'])): ?>
          <? $arFiles = explode(',', $viData['files']); ?>
          <div class="d-label">
            <span>Сканы</span>
              <div class="service_images">
                <? foreach ($arFiles as $v): ?>
                  <a  href="<?=Settings::getFilesUrl() . UserCard::$FILE_PATH . $v . UserCard::$IMG_SUFFIX . '.jpg'?>" target="_blank" class="service_images-link">
                    <img src="<?=Settings::getFilesUrl() . UserCard::$FILE_PATH . $v . UserCard::$SMALL_IMG_SUFFIX . '.jpg'?>">
                  </a>
                <? endforeach; ?>
              </div>
          </div>
        <? endif; ?>
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