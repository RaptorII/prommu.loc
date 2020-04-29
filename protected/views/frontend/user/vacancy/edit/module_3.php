<? $linkService = MainConfig::$PAGE_ORDER_SERVICE . "?id={$viData->data->id}&service="; ?>
<div class="personal__area--capacity">
  <div class="personal__area--capacity-name">Аналитика</div>
  <div class="vacancy__services">
    <div class="vacancy__services-title">Бесплатные услуги</div>
    <div class="vacancy__services-list">
      <div class="vacancy__services-item">
        <? if(!empty($viData->services->vkontakte)): ?>
          <a href="<?=$viData->services->vkontakte?>"
            class="hint vacancy__services-link prmu-icon icon-vkontakte vacancy__services-link-active"
            title="Услуга заказана">
            <span>Вакансия в группе ВК</span>
          </a>
        <? else: ?>
          <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$viData->data->id}&soc=1&page=0"?>"
             class="vacancy__services-link prmu-icon icon-vkontakte">
            <span>Публикация в группе ВК</span>
          </a>
        <? endif; ?>
      </div>
      <?  //  ?>
      <div class="vacancy__services-item">
        <? if(!empty($viData->services->facebook)): ?>
          <a href="<?=$viData->services->facebook?>"
             class="hint vacancy__services-link prmu-icon icon-facebook vacancy__services-link-active"
             title="Услуга заказана">
            <span>Вакансия в группе Facebook</span>
          </a>
        <? else: ?>
          <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$viData->data->id}&soc=2&page=0"?>"
             class="vacancy__services-link prmu-icon icon-facebook">
            <span>Публикация в группе Facebook</span>
          </a>
        <? endif; ?>
      </div>
      <?  //  ?>
      <div class="vacancy__services-item">
        <? if(!empty($viData->services->telegram)): ?>
          <a href="<?=$viData->services->telegram?>"
             class="hint vacancy__services-link prmu-icon icon-telegram vacancy__services-link-active"
             title="Услуга заказана">
            <span>Вакансия в Telegram канале</span>
          </a>
        <? else: ?>
          <a href="<?=MainConfig::$PAGE_VACTOSOCIAL . "?id={$viData->data->id}&soc=3&page=0"?>"
             class="vacancy__services-link prmu-icon icon-telegram">
            <span>Публикация в Telegram канале</span>
          </a>
        <? endif; ?>
      </div>
      <?  //  ?>
      <div class="vacancy__services-item">
        <? if(count($viData->services->push->items)): ?>
          <a href="<?=$linkService . "push"?>"
             class="hint vacancy__services-link prmu-icon icon-push vacancy__services-link-active"
             title="Услуга заказана">
            <span>PUSH информирование</span>
          </a>
        <? else: ?>
          <a href="<?=$linkService . "push"?>"
             class="vacancy__services-link prmu-icon icon-push">
            <span>PUSH информирование</span>
          </a>
        <? endif; ?>
      </div>
    </div>
  </div>
  <?
  //
  ?>
  <div class="vacancy__services">
    <div class="vacancy__services-title">Платные услуги</div>
    <div class="vacancy__services-list">
      <div class="vacancy__services-item">
        <? if(count($viData->services->premium->items)): ?>
          <a href="<?=$linkService . "premium"?>"
             class="hint vacancy__services-link prmu-icon icon-premium-vacancy vacancy__services-link-active"
             title="Услуга заказана">
            <span>Статус "Премиум"</span>
          </a>
        <? else: ?>
          <a href="<?=$linkService . "premium"?>"
             class="vacancy__services-link prmu-icon icon-premium-vacancy">
            <span>Статус "Премиум"</span>
          </a>
        <? endif; ?>
        <div class="form__field-hint tooltip" title="<?=$viData->services->info['premium-vacancy']['anons']?>"></div>
      </div>
      <?  //  ?>
      <div class="vacancy__services-item">
        <? if(count($viData->services->upvacancy->items)): ?>
          <a href="<?=$linkService . "upvacancy"?>"
             class="hint vacancy__services-link prmu-icon icon-podnyatie-vacansyi-vverh vacancy__services-link-active"
             title="Услуга заказана">
            <span>Поднятие вакансии</span>
          </a>
        <? else: ?>
          <a href="<?=$linkService . "upvacancy"?>"
             class="vacancy__services-link prmu-icon icon-podnyatie-vacansyi-vverh">
            <span>Поднятие вакансии</span>
          </a>
        <? endif; ?>
        <div class="form__field-hint tooltip" title="<?=$viData->services->info['podnyatie-vacansyi-vverh']['anons']?>"></div>
      </div>
      <?  //  ?>
      <div class="vacancy__services-item">
        <? if(count($viData->services->{"personal-invitation"}->items)): ?>
          <a href="<?=$linkService . "personal-invitation"?>"
             class="hint vacancy__services-link prmu-icon icon-personal-invitation vacancy__services-link-active"
             title="Услуга заказана">
            <span>Личное приглашение</span>
          </a>
        <? else: ?>
          <a href="<?=$linkService . "personal-invitation"?>"
             class="vacancy__services-link prmu-icon icon-personal-invitation">
            <span>Личное приглашение</span>
          </a>
        <? endif; ?>
        <div class="form__field-hint tooltip" title="<?=$viData->services->info['personal-invitation']['anons']?>"></div>
      </div>
      <?  //  ?>
      <div class="vacancy__services-item">
        <? if(count($viData->services->email->items)): ?>
          <a href="<?=$linkService . "email"?>"
             class="hint vacancy__services-link prmu-icon icon-email-invitation vacancy__services-link-active"
             title="Услуга заказана">
            <span>EMAIL информирование</span>
          </a>
        <? else: ?>
          <a href="<?=$linkService . "email"?>"
             class="vacancy__services-link prmu-icon icon-email-invitation">
            <span>EMAIL информирование</span>
          </a>
        <? endif; ?>
        <div class="form__field-hint tooltip" title="<?=$viData->services->info['email-invitation']['anons']?>"></div>
      </div>
      <?  //  ?>
      <div class="vacancy__services-item">
        <? if(count($viData->services->sms->items)): ?>
          <a href="<?=$linkService . "sms"?>"
             class="hint vacancy__services-link prmu-icon icon-sms-informing-staff vacancy__services-link-active"
             title="Услуга заказана">
            <span>СМС информирование</span>
          </a>
        <? else: ?>
          <a href="<?=$linkService . "sms"?>"
             class="vacancy__services-link prmu-icon icon-sms-informing-staff">
            <span>СМС информирование</span>
          </a>
        <? endif; ?>
        <div class="form__field-hint tooltip" title="<?=$viData->services->info['sms-informing-staff']['anons']?>"></div>
      </div>
      <?  //  ?>
      <div class="vacancy__services-item">
        <? if(count($viData->services->outsourcing->items)): ?>
          <a href="<?=$linkService . "outsourcing"?>"
             class="hint vacancy__services-link prmu-icon icon-personal-manager-outsourcing vacancy__services-link-active"
             title="Услуга заказана">
            <span>Личный менеджер</span>
          </a>
        <? else: ?>
          <a href="<?=$linkService . "outsourcing"?>"
             class="vacancy__services-link prmu-icon icon-personal-manager-outsourcing">
            <span>Личный менеджер</span>
          </a>
        <? endif; ?>
        <div class="form__field-hint tooltip" title="<?=$viData->services->info['personal-manager-outsourcing']['anons']?>"></div>
      </div>
      <?  //  ?>
      <div class="vacancy__services-item">
        <? if(count($viData->services->outstaffing->items)): ?>
          <a href="<?=$linkService . "outstaffing"?>"
             class="hint vacancy__services-link prmu-icon icon-outstaffing vacancy__services-link-active"
             title="Услуга заказана">
            <span>Аутстаффинг персонала</span>
          </a>
        <? else: ?>
          <a href="<?=$linkService . "outstaffing"?>"
             class="vacancy__services-link prmu-icon icon-outstaffing">
            <span>Аутстаффинг персонала</span>
          </a>
        <? endif; ?>
        <div class="form__field-hint tooltip" title="<?=$viData->services->info['outstaffing']['anons']?>"></div>
      </div>
    </div>
  </div>
</div>