<?php
$title = 'Новое обращение';
$this->setPageTitle($title);
$this->breadcrumbs = ['Обратная связь'=>['/feedback'], $title];
$bUrl = Yii::app()->request->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . '/css/template.css');
$gcs->registerCssFile($bUrl . '/css/notifications/message.css');
$gcs->registerCssFile($bUrl . '/css/feedback/new.css');
$gcs->registerScriptFile($bUrl . '/js/feedback/new.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . '/js/nicEdit.js', CClientScript::POS_END);
?>
<? if(count($viData['errors'])): ?>
  <div class="alert danger">- <?=implode('<br>- ', $viData['errors']) ?></div>
<? endif; ?>
<h3><?=$this->pageTitle?></h3>
<form method="post" id="service_form">
  <div class="row">
    <div class="col-xs-12">
      <div class="row">
        <div class="hidden-xs hidden-sm col-md-2"></div>
        <div class="col-xs-12 col-md-8">
          <table class="table table-bordered template-table">
            <tbody>
              <tr>
                <td><b>Получатель</b></td>
                <td>
                  <? if(intval($viData['id_user'])): ?>
                    <div id="receivers_result">
                      <div class="item" data-id="<?=$viData['receiver']['id']?>">
                        <img src="<?=$viData['receiver']['src']?>" alt="<?=$viData['receiver']['name']?>" title="<?=$viData['receiver']['name']?>">
                        <div class="info">
                          <b title="<?=$viData['receiver']['name']?>"><?=$viData['receiver']['name']?></b><br>
                          <span><?=(Share::isApplicant($viData['receiver']['status']) ? 'соискатель' : 'работодатель')?></span>
                        </div>
                        <div class="links">
                          <a href="<?=$viData['receiver']['profile_admin']?>" class="glyphicon glyphicon-edit" target="_blank" title="Ссылка на профиль в вдминистративной части сайта"></a>
                          <a href="<?=$viData['receiver']['profile']?>" class="glyphicon glyphicon-new-window" target="_blank" title="Ссылка на профиль в публичной части сайта"></a>
                        </div>
                        <input type="hidden" name="Feedback[receiver]" value="<?=$viData['receiver']['id']?>">
                      </div>
                    </div>
                  <? else: ?>
                    <div class="bs-callout bs-callout-warning">Поиск выполняется по имени, фамилии, названию компании и id_user</div>
                    <label class="d-label nomargin" id="receivers_load">
                      <input type="text" name="receiver" class="form-control" autocomplete="off" id="receivers_field">
                    </label>
                    <div class="col-xs-12"><div id="receivers_list"></div></div>
                    <div id="receivers_result"></div>
                  <? endif; ?>
                </td>
              </tr>
              <tr>
                <td><b>Направление запроса</b></td>
                <td>
                  <select name="Feedback[direct]" class="form-control d-small">
                    <? foreach ($viData['directs'] as $v): ?>
                      <option value="<?=$v['id']?>"<?=$v['id']==$viData['direct']?' selected':''?>><?=$v['name']?></option>
                    <? endforeach; ?>
                  </select>
                </td>
              </tr>
              <tr>
                <td><b>Тематика запроса</b></td>
                <td>
                  <input type="text" name="Feedback[theme]" class="form-control d-small" value="<?=$viData['theme']?>">
                </td>
              </tr>
              <tr>
                <td><b>Сообщение</b></td>
                <td>
                  <div id="text_form_panel"></div>
                  <textarea name="Feedback[text]" class="form-control" id="text_form"><?=$viData['text']?></textarea>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="pull-right">
            <button type="submit" class="btn btn-success">Отправить</button>
            <? if(Yii::app()->getRequest()->getParam('back')=='profile'): ?>
              <a href="<?=$this->createUrl('..'.$viData['receiver']['profile_admin'] . '?anchor=tab_feedback')?>" class="btn btn-success d-indent">Профиль пользователя</a>
            <? endif; ?>
            <a href="<?=$this->createUrl('/feedback')?>" class="btn btn-success d-indent">Все обращения</a>
          </div>
        </div>
        <div class="hidden-xs col-sm-1 col-md-3"></div>
      </div>
    </div>
  </div>
</form>