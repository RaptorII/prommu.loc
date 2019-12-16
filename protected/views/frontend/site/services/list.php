<?php
$bUrl = Yii::app()->baseUrl;
$gcs = Yii::app()->getClientScript();
$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'services/list.css');
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/jquery.maskedinput.min.js', CClientScript::POS_END);
$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'services/list.js', CClientScript::POS_END);
$arApp = ['geolocation-staff','prommu_card','medical-record']; // то, что доступно соискателю
$arCustom = ['outstaffing','personal-manager-outsourcing','medical-record']; // По запросу
$arGuest = ['prommu_card','medical-record']; // для гостя
$hasHistory = (!Share::isGuest() && $viData['history']['cnt']>0);
?>
<div class="row">
    <div class="col-xs-12">
        <a class="download__btn download__btn-flt-right download__btn-inst" href='/theme/pdf/Instruction-PROMMU-com-service.pdf' target="_blank" title="Скачать иструкцию пользования сервисом PROMMU.com"></a>
    </div>
  <div class="col-xs-12 services">

    <?
    // user
    ?>
    <? if($hasHistory): ?>
      <div class="row services__sections">
        <div class="col-xs-12 col-sm-6">
          <div class="services__sections-item icn-cogs-prommu" data-section="services">
            <span>Список услуг</span>
          </div>
        </div>
        <div class="col-xs-12 col-sm-6">
          <div class="services__sections-item icn-checked-circle-prommu" data-section="history">
            <span>Мои услуги</span>
          </div>
        </div>
      </div>
      <div class="services__history disable" id="history">
        <? if($hasHistory): ?>
          <div>
            <div class="services__back">
              <span class="icn-arrow-left-l-prommu"></span>
              <span>Назад</span>
            </div>
          </div>
          <? foreach($viData['history']['items'] as $v): ?>
            <div class="history__item">
              <div class="history__item-icon <?=$v['type']?>"></div>
              <?
              //
              ?>
              Тип услуги: <?=$v['name']?><br>
              <?
              //
              ?>
              <? if(in_array($v['type'],['email','push','sms','repost','vacancy','outsourcing','outstaffing'])): ?>
                Вакансия: <a href="<?=MainConfig::$PAGE_VACANCY . DS . $v['vacancy']?>" target="_blank"><?=$viData['history']['vacancies'][$v['vacancy']]['title']?></a><br>
              <? endif; ?>
              <? if($v['type']=='vacpub'): ?>
                Вакансия: <a href="<?=MainConfig::$PAGE_VACANCY . DS . $v['vacancy']?>" target="_blank"><?=$viData['history']['vacancies'][$v['vacancy']]['title']?></a><br>
              <? endif; ?>
              <?
              //
              ?>
              <? if($v['cost']>0): ?>
                Стоимость: <?=$v['cost']?> руб.<br>
              <? endif; ?>
              <?
              //
              ?>
              <? if(isset($v['date'])): ?>
                Время и дата: <?=date('G:i d.m.Y',strtotime($v['date']))?><br>
              <? endif; ?>
              <?
              //
              ?>
              <? if(isset($v['status'])): ?>
                Состояние: <?=$v['status']?><br>
              <? endif; ?>
              <?
              //
              ?>
              <? if($v['type']=='repost'): ?>
                Соцсеть: <div class="history__item-social"><span class="<?
                  switch ($v['data']['user'])
                  {
                    case 'vk': echo 'icn-vk-icon-prommu vk'; break;
                    case 'fb': echo 'icn-facebook-icon-prommu fb'; break;
                    case 'telegram': echo 'icn-telegram-icon telegram'; break;
                  }
                  ?>"></span></div><br>
              <? endif; ?>
              <? if(isset($v['payment_legal'])): ?>
                <a href="<?=$v['payment_legal']?>" target="_blank">Счет для юридического лица</a><br>
              <? endif; ?>
              <?
              //
              ?>
              <? if(isset($v['users'])): ?>
                <div class="history__item-users" data-id="<?=$v['id']?>">
                  <div class="history__users-name">Приглашенные: <?=count($v['users'])?></div>
                  <div class="history__users-ajax"></div>
                </div>
              <? endif; ?>
            </div>
          <? endforeach; ?>
        <? endif; ?>
      </div>
    <? endif; ?>
    <div class="services__list<?=$hasHistory?' disable':''?>" id="services">
      <? if($hasHistory): ?>
        <div>
          <div class="services__back">
            <span class="icn-arrow-left-l-prommu"></span>
            <span>Назад</span>
          </div>
        </div>
      <? endif; ?>
      <? foreach ($viData['menu'][0] as $m): ?>
        <?
          if(!in_array($m['icon'], $arApp) && Share::isApplicant())
            continue;
        ?>
        <? if($m['parent_id']==0 && !is_array($viData['menu'][$m['id']])): ?>
          <div class="row services__item">
            <div class="col-xs-12 col-sm-6 services__prev">
              <div class="services__item-icon <?=$m['icon']?>"></div>
              <? $cnt = iconv_strlen($m['name'],'UTF-8'); ?>
              <div class="services__item-label upper subsmall<?=($cnt>30?' small':'')?>"><?=$m['name']?></div>
              <div class="services__item-descr"><?=$m['anons']?></div>
            </div>
            <div class="col-xs-12 col-sm-6 services__detail">
              <a href="<?=$m['link']?>">Подробнее</a>
            </div>
            <div class="services__price-order">
              <? foreach ($prices['prices'][$m['icon']] as $price): ?>
                <div>
                  <div class="services__price">
                    <? if(in_array($m['icon'], $arCustom)): ?>
                      <div class="services__price-item">По запросу</div>
                    <? else: ?>
                      <div class="services__price-item">
                        <?echo $price['price']
                          ? '<b>'.$price['price'] . ' &#8381</b> ' . $price['comment']
                          : '<span>Бесплатно</span>'?>
                      </div>
                    <? endif; ?>
                  </div>
                  <div
                    class="services__order order-service"
                    data-id="<?=$m['icon']?>"
                    data-type="<?=$m['icon']?>"
                  >
                    <? if(Share::isEmployer() && $m['icon']=='creation-vacancy'): ?>
                      <a href="<?=MainConfig::$PAGE_VACPUB?>" class="user">Разместить Вакансию</a>
                    <? elseif(Share::isGuest() && $m['icon']=='creation-vacancy'): ?>
                      <a href="javascript:void(0)">Заказать</a>
                    <? elseif($m['icon']=='geolocation-staff'): ?>
                      <a href="javascript:void(0)" class="disable">В разработке</a>
                    <? elseif(!Share::isGuest() || in_array($m['icon'], $arGuest)): ?>
                      <?
                        sizeof($prices['prices'][$m['icon']]) > 1
                        ? $link = '/user' . $m['link'] //. '?type=' . $price['id']
                        : $link = '/user' . $m['link'];
                      ?>
                      <a href="<?=$link?>" class="user">Заказать</a>
                    <? else: ?>
                      <a href="javascript:void(0)">Заказать</a>
                    <? endif; ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              <? endforeach; ?>
            </div>
          </div>
        <? else: ?>
          <? // вывод второго уровня  ?>
          <div class="row services__item-sub">
            <div class="col-xs-12 col-sm-6 services__parent">
              <div class="services__item-icon <?=$m['icon']?>"></div>
              <? $cnt = iconv_strlen($m['name'],'UTF-8'); ?>
              <div class="services__item-label upper subsmall<?=($cnt>30?' subsmall':'')?>"><?=$m['name']?></div>
            </div>
            <div class="clearfix"></div>
            <? foreach ($viData['menu'][$m['id']] as $s): ?>
              <div class="services__sublevel">
                <div class="col-xs-12 col-sm-6 services__item-descr">
                  <div class="services__sub">
                    <div class="services__sub-label <?=$s['icon']?>"><?=$s['name']?></div>
                    <div class="services__sub-descr"><?=$s['anons']?></div>
                  </div>
                </div>
                <div class="col-xs-12 col-sm-6 services__detail">
                  <a href="<?=$s['link']?>">Подробнее</a>
                </div>
                <div class="services__price-order">
                  <? foreach ($prices['prices'][$s['icon']] as $price): ?>
                    <div>
                      <div class="services__price">
                        <? if(in_array($m['icon'], $arCustom)): ?>
                          <div class="services__price-item">По запросу</div>
                        <? else: ?>
                          <div class="services__price-item">
                            <?echo $price['price']
                              ? '<b>'.$price['price'] . ' &#8381</b> ' . $price['comment']
                              : '<span>Бесплатно</span>'?>
                          </div>
                        <? endif; ?>
                      </div>
                      <div
                        class="services__order order-service"
                        data-id="<?=$s['icon']?>"
                        data-type="<?=$s['icon']?>"
                      >
                        <? if(!Share::isGuest() || in_array($s['icon'], $arGuest)): ?>
                          <?
                            sizeof($prices['prices'][$s['icon']]) > 1
                            ? $link = '/user' . $s['link'] //. '?type=' . $price['id']
                            : $link = '/user' . $s['link'];
                          ?>
                          <a href="<?=$link?>" class="user">Заказать</a>
                        <? else: ?>
                          <a href="javascript:void(0)">Заказать</a>
                        <? endif; ?>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  <? endforeach; ?>
                </div>
                <div class="clearfix"></div>
              </div>
            <? endforeach; ?>
          </div>
        <? endif; ?>
      <? endforeach; ?>
    </div>
	</div>
</div>
<script type="text/javascript">
	var arSuccessMess = <?=json_encode(Yii::app()->user->getFlash('success'))?>;
</script>
<? require __DIR__ . '/popups.php'; ?>