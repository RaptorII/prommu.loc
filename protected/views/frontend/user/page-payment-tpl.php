<?
$title = 'Оплата услуг PROMMU';
$this->setPageTitle($title);
$this->setBreadcrumbsEx(
  array('Мой профиль', MainConfig::$PAGE_PROFILE),
  array($title = 'Оплата услуг', MainConfig::$PAGE_PAYMENT)
);
$this->ViewModel->addContentClass('page-payment');
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'page-payment.css');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'payment-page.js', CClientScript::POS_END);


//display($viData);

?>
<div class="row">
  <div class="col-xs-12 payment">
    <h1 class="payment__title">Оплата услуг PROMMU</h1>
    <form
      action="<?=MainConfig::$PAGE_PAYMENT?>"
      class="payment-form"
      method="POST"
      id="payment-form"
      data-leg="<?=MainConfig::$PAGE_PAYMENT?>"
      data-ind="<?=MainConfig::$PAGE_PAYMENT?>">
      <?php if($viData['service']=='premium-vacancy'): ?>
        <span class="payment-form__type-name">ПЕРИОД РАБОТЫ ПРЕМИУМ УСЛУГИ</span>
        <?php foreach ($viData['price'] as $id): ?>
          <div class="payment-date">
            <div class="payment__date-id">Для вакансии <?=$id['id_vac']?></div>
            <div class="payment__date-calendar begin">
              <div class="payment__date-error">Дата начала не корректна</div>
              <div class="payment__date-border">
                <span class="payment__date-name">ДАТА НАЧАЛА</span>
                <table id="calendar-begin" class="payment__calendar">
                  <thead>
                  <tr>
                    <td class="month-left">‹</td>
                    <td colspan="5" class="month-name"></td>
                    <td class="month-right">›</td>
                  </tr>
                  <tr>
                    <td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
                  </tr>
                  <tbody>
                </table>
              </div>
            </div>
            <div class="payment__date-calendar end">
              <div class="payment__date-error">Дата окончания не корректна</div>
              <div class="payment__date-border">
                <span class="payment__date-name">ДАТА ОКОНЧАНИЯ</span>
                <table id="calendar-end" class="payment__calendar">
                  <thead>
                  <tr>
                    <td class="month-left">‹</td>
                    <td colspan="5" class="month-name"></td>
                    <td class="month-right">›</td>
                  </tr>
                  <tr>
                    <td>Пн</td><td>Вт</td><td>Ср</td><td>Чт</td><td>Пт</td><td>Сб</td><td>Вс</td>
                  </tr>
                  <tbody>
                </table>
              </div>
            </div>
            <div class="clearfix"></div>
            <table class="payment-form__table">
              <tbody>
              <tr><td>Дата начала</td><td class="payment-begin"></td></tr>
              <tr><td>Дата окончания</td><td class="payment-end"></td></tr>
              <tr><td>Период работы услуги</td><td class="payment-period">0 дней</td></tr>
              <tr><td>Стоимость услуги</td><td class="payment-cost"><span><?=$id['price']?></span> руб/день</td>
              </tbody>
            </table>
            <input type="hidden" name="vacancy[]" value="<?=$id['id_vac']?>">
            <input type="hidden" name="from[]" class="payment-begin-inp">
            <input type="hidden" name="to[]" class="payment-end-inp">
            <input type="hidden" name="period[]" class="payment-period-inp">
            <input type="hidden" name="cost[]" class="payment-cost-inp">
            <input type="hidden" name="vacancy_price[]" value="<?=$id['price']?>">
            <input type="hidden" name="vacancy_city[]" value="<?=$id['region']?>">
          </div>
        <?php endforeach; ?>
        <input type="hidden" name="service" value="premium-vacancy">
        <span class="payment-form__type-name">РЕЗУЛЬТАТ</span>
        <table class="payment-form__table">
          <tbody>
          <tr><td>Выбраных вакансий</td><td id="payment-count"><?=count($viData['vacancy'])?></td></tr>
          <tr><td colspan="2" id="payment-result"></td></tr>
          </tbody>
        </table>
      <?php endif; ?>
      <?if($viData['service']=='sms-informing-staff'):?>
        <?php $result = $viData['app_count'] * $viData['mes_count'] * $viData['price']; ?>
        <span id="payment-result"><?=$result?>рублей</span>
      <?endif;?>
      <?if($viData['service']=='podnyatie-vacansyi-vverh'):?>
        <?php
          $priceAll = 0;
          foreach ($viData['price'] as $item):
            $priceAll = $priceAll + $item['price'];
          endforeach;
        ?>
        <span id="payment-result"><?=$priceAll.' '?> рублей</span>
        <?php foreach ($viData['price'] as $id): ?>
          <input type="hidden" name="vacancy[]" value="<?=$id['id_vac']?>">
          <input type="hidden" name="vacancy_price[]" value="<?=$id['price']?>">
          <input type="hidden" name="vacancy_city[]" value="<?=$id['region']?>">
        <?php endforeach; ?>
        <input type="hidden" name="service" value="podnyatie-vacansyi-vverh">
      <? endif; ?>
      <?if(count($viData['receipt_items'])): // оплата при создании вакансии ?>
        <?
          $price = 0;
          foreach ($viData['receipt_items'] as $v)
          {
            $price += $v['sum'];
            ?>
            <input type="hidden" name="receipt[]" value="<?=$v['id']?>">
            <?
          }
        ?>
        <span id="payment-result"><?=$price.' '?> рублей</span>
      <? endif; ?>
      <? $this->renderPartial('../site/services/legal-fields',['viData'=>$viData]); ?>
      <input type="hidden" name="employer" value="<?= Share::$UserProfile->id?>">

      <div class="center">
        <br>
        <button type="submit" class="payment-form__btn prmu-btn prmu-btn_normal" id="payment-btn"><span>СФОРМИРОВАТЬ СЧЕТ</span></button>
      </div>
    </form>
  </div>
</div>