<?
  Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'services/page-push.css');
  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'services/page-push.js', CClientScript::POS_END);
  $rq = Yii::app()->getRequest();

  //display($viData['lala']);

  if(!$rq->getParam('vacancy')):
  //
  //  Выбор вакансии
  //
?>
  <div class="row">
    <div class="col-xs-12">
      <?php if(sizeof($viData['vacs'])): ?>
        <h2 class="service__title">ВЫБЕРИТЕ ВАКАНСИЮ ДЛЯ PUSH УВЕДОМЛЕНИЙ</h2>
        <form action="" method="POST">
          <div class="service__vac-list">
          <?php foreach ($viData['vacs'] as $key => $val): ?>
            <label class="service-vac__item">
              <div class="service-vac__item-bg">
                <span class="service-vac__item-title"><?=$val['title'] ?></span>
              </div>
              <input type="radio" name="vacancy" value="<?=$val['id']?>" class="service-vac__item-input">
            </label>      
          <?php endforeach; ?>
          </div>
          <button class="service__btn prmu-btn prmu-btn_normal pull-right" id="vac-btn">
            <span>Выбрать персонал для PUSH уведомления</span>
          </button>
        </form> 
      <?php else: ?>
        <br>
        <h2 class="service__title center">У ВАС НЕТ АКТИВНЫХ ВАКАНСИЙ</h2>
        <div class="center">
          <a href="<?=MainConfig::$PAGE_VACPUB?>" class="service__btn visible prmu-btn prmu-btn_normal">
            <span>ДОБАВИТЬ ВАКАНСИЮ</span>
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php elseif(!$rq->getParam('users')): ?>
  <script type="text/javascript">
    var arSelectCity = <?=json_encode($viData['workers']['city'])?>;
    var AJAX_GET_PROMO = "<?='/user'.MainConfig::$PAGE_SERVICES_PUSH?>";
  </script>
  <div class='row'>
    <?
    //    FILTER
    ?>
    <div class="filter__veil"></div>
    <div class='col-xs-12 col-sm-4 col-md-3'>
      <div class="filter__vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
      <form action="" id="promo-filter" method="get"><? require_once 'ankety-filter.php'; ?></form>
    </div>
    <?
    //    CONTENT
    ?>
    <div class='col-xs-12 col-sm-8 col-md-9'>
      <div class='view-radio clearfix'>
        <h1 class="main-h1">Выбрать персонал для PUSH информирования</h1>
        <form action="<?=MainConfig::$PAGE_PAYMENT ?>" method="POST" id="workers-form">
          <span class="workers-form__cnt">Выбрано получателей: <span id="mess-wcount">0</span></span>
          <div class="service__switch">
            <span class="service__switch-name">Выбрать всех</span>
            <input type="checkbox" name="ntf-push" id="all-workers" value="1"/>
            <label for="all-workers">
              <span data-enable="вкл." data-disable="выкл."></span>
            </label>
          </div>
          <button type="submit" class="workers-form-btn off prmu-btn prmu-btn_normal" id="workers-btn">
            <span>сформировать PUSH уведомления</span>
          </button>
          <input type="hidden" name="users" id="mess-workers">
          <input type="hidden" name="users-cnt" id="mess-wcount-inp" value="0">
          <input type="hidden" name="vacancy" value="<?=$rq->getParam('vacancy')?>">
          <input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
          <input type="hidden" name="service" value="push-notification">
        </form>
      </div>
      <div id="promo-content"><? require_once 'ankety-ajax.php'; ?></div>
    </div>
  </div>
<?php else: ?>
  <?php 
    //    Оплата сообщений
    //
    $appCount = $rq->getParam('users-cnt'); 
  ?>
  <div class="row">
    <div class="col-xs-12">
      <form action="<?=MainConfig::$PAGE_PAYMENT?>" method="POST" class="smss__result-form">
      <h1 class="smss-result__title">ТЕКСТ РАССЫЛКИ</h1>
			<span class="smss-result__result" style="font-size:23px">Работодатель <?=Share::$UserProfile->exInfo->name?> приглашает на вакансию <a href="https://prommu.com/vacancy/<?=$rq->getParam('vacpush')?>">https://prommu.com/vacancy/<?=$rq->getParam('vacpush')?></a></span></br>
        <h1 class="smss-result__title">РАСЧЕТ СТОИМОСТИ УСЛУГИ</h1>
        <table class="smss-result__table">
          <tr>
            <td>Количество получателей</td>
            <td><?=$appCount?></td>
          </tr>
        </table>
        <button class="smss-result__btn">Перейти к оплате</button>
        <input type="hidden" name="vacancy" value="<?=$rq->getParam('vacpush')?>">
        <input type="hidden" name="users-cnt" value="<?=$appCount?>">
        <input type="hidden" name="users" value="<?=$rq->getParam('users')?>">
        <input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
        <input type="hidden" name="service" value="push-notification">
      </form>
    </div>
  </div>
<?php endif; ?>