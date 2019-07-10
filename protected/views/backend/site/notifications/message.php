<?php
/**
 * Created by PhpStorm.
 * User: Dmitry Derevyanko
 * Date: 08.07.2019
 * Time: 15:29
 */
?>
<h3><?=$this->pageTitle?></h3>
<? if(!$viData['item'] && intval($viData['id'])): ?>
  <div class="alert danger">Данные отсутствуют</div>
<? else: ?>
  <?
    $gcs = Yii::app()->getClientScript();
    $bUrl = Yii::app()->request->baseUrl;
    $gcs->registerScriptFile($bUrl . '/js/notifications/message.js', CClientScript::POS_END);
    $gcs->registerCssFile($bUrl . '/css/notifications/message.css');
    $item = $viData['item'];
    !is_object($item) && $item = (object) ['title'=>'','text'=>''];
  ?>
  <? if($viData['error'] && isset($viData['messages'])): ?>
    <div class="alert danger">- <?=implode('<br>- ', $viData['messages']) ?></div>
  <? endif; ?>
  <div class="row">
    <div class="col-xs-12">
      <form action="" method="POST" id="notification-form">
        <div class="row">
          <div class="hidden-xs col-sm-1 col-md-3"></div>
          <div class="col-xs-12 col-sm-10 col-md-6 send_params">
            <div class="row">
              <div class="col-xs-12">
                <label class="d-label">
                  <span>Заголовок</span>
                  <input type="text" name="title" class="form-control" autocomplete="off" value="<?=$item->title?>">
                </label>
              </div>
              <div class="col-xs-12">
                <div class="bs-callout bs-callout-warning">Поиск выполняется по имени, фамилии, названию компании и id_user</div>
                <label class="d-label" id="receivers_load">
                  <span>Получатели</span>
                </label>
                <input type="text" name="receiver" class="form-control" autocomplete="off" id="receivers_field">
                <div class="col-xs-12"><div id="receivers_list"></div></div>
                <div id="receivers_result">
                  <? if(isset($viData['receiver_new'])): ?>
                    <? $arUser = $viData['users'][$viData['receiver_new']]; ?>
                    <div class="item" data-id="<?=$viData['receiver_new']?>">
                      <img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>">
                      <div class="info">
                        <b><?=$arUser['name']?></b><br>
                        <span><?=(Share::isApplicant($arUser['status']) ? 'соискатель' : 'работодатель')?></span>
                      </div>
                      <div class="links">
                        <a href="/admin/<?=(Share::isApplicant($arUser['status']) ? 'PromoEdit/' : 'EmplEdit/') . $viData['receiver_new']?>" class="glyphicon glyphicon-edit" target="_blank" title="в вдминке"></a>'
                        <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $viData['receiver_new']?>" class="glyphicon glyphicon-new-window" target="_blank" title="в публичке"></a>'
                      </div>
                      <input type="hidden" name="receivers[]" value="<?=$viData['receiver_new']?>">
                      <i class="glyphicon glyphicon-remove"></i>
                    </div>
                  <? elseif(count($viData['receivers'])): ?>
                    <? foreach($viData['receivers'] as $v): ?>
                      <? $arUser = $viData['users'][$v['id_user']]; ?>
                      <div class="item" data-id="<?=$v['id_user']?>">
                        <img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>">
                        <div class="info">
                          <b><?=$arUser['name']?></b><br>
                          <span><?=(Share::isApplicant($arUser['status']) ? 'соискатель' : 'работодатель')?></span>
                          <div class="readed<?=(!empty($v['readed'])?' success':'')?>">
                            <? if(!empty($v['readed'])): ?>
                              <span class="glyphicon glyphicon-ok-sign"></span> Прочитано <?=Share::getDate($v['readed'])?>
                            <? else: ?>
                              <span class="glyphicon glyphicon-remove-sign"></span> Не прочитано
                            <? endif; ?>
                          </div>
                        </div>
                        <div class="links">
                          <a href="/admin/<?=(Share::isApplicant($arUser['status']) ? 'PromoEdit/' : 'EmplEdit/') . $v['id_user']?>" class="glyphicon glyphicon-edit" target="_blank" title="в вдминке"></a>'
                          <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $v['id_user']?>" class="glyphicon glyphicon-new-window" target="_blank" title="в публичке"></a>'
                        </div>
                        <input type="hidden" name="receivers[]" value="<?=$v['id_user']?>">
                      </div>
                    <? endforeach; ?>
                  <? endif; ?>
                </div>
                <div id="receivers_old">
                  <? if(count($viData['receivers'])): ?>
                    <? foreach($viData['receivers'] as $v): ?>
                      <input type="hidden" name="receivers_old[]" value="<?=$v['id_user']?>">
                    <? endforeach; ?>
                  <? endif; ?>
                </div>
              </div>
              <div class="col-xs-12">
                <label class="d-label">
                  <span>Текст сообщения</span>
                </label>
                <textarea name="text" class="d-textarea form-control" id="message_body"><?=$item->text?></textarea>
              </div>
            </div>
          </div>
          <div class="hidden-xs col-sm-1 col-md-3"></div>
        </div>
        <?
        //
        ?>
        <div class="bs-callout bs-callout-warning">Наличие выбранных получателей произведет отправку сообщения при сохранении</div>
        <div class="pull-right">
          <a href="<?=$this->createUrl('')?>" class="btn btn-success d-indent">Назад</a>
          <button type="submit" class="btn btn-success d-indent">Сохранить</button>
        </div>
      </form>
    </div>
  </div>
<? endif; ?>