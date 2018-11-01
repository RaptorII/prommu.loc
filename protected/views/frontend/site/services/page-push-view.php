<?php
  Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/theme/css/services/page-push.css');
  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/services/page-push.js', CClientScript::POS_END);
  if(!Yii::app()->getRequest()->getParam('vacancy')):
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
          <button class="service__btn" id="vac-btn">Выбрать персонал для PUSH уведомления</button>
        </form> 
      <?php else: ?>
        <h2 class="service__title">У ВАС НЕТ АКТИВНЫХ ВАКАНСИЙ</h2>
        <a href="<?=MainConfig::$PAGE_VACPUB?>" class="service__btn visible">ДОБАВИТЬ ВАКАНСИЮ</a>
      <?php endif; ?>
    </div>
  </div>
<?php elseif(!Yii::app()->getRequest()->getParam('users')): ?>
  <script type="text/javascript">
    var arIdies = <?=json_encode($viData['workers']['promos'])?>;
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
      <form action="" id="promo-filter" method="get">
        <div class='filter'>
          <div class='filter__item filter-cities'>
            <div class='filter__item-name opened'>Город</div>
            <div class='filter__item-content opened'>
                <div class="fav__select-cities" id="multyselect-cities"></div>
            </div>
          </div>
          <div class='filter__item filter-posts'>
            <div class='filter__item-name opened'>Должность</div>
            <div class='filter__item-content opened'>
              <div class='right-box'>
                <?php
                $sel = 0;
                foreach($viData['workers']['posts'] as $p)
                  if($p['selected']) $sel++;
                ?>
                <input name='posts-all' type='checkbox' id="f-all-posts" class="filter__chbox-inp"<?=sizeof($viData['workers']['posts'])==$sel ?' checked':''?>>
                <label class='filter__chbox-lab' for="f-all-posts">Выбрать все / снять все</label>
                <?php foreach($viData['workers']['posts'] as $p): ?>
                  <input name='posts[]' value="<?=$p['id']?>" type='checkbox' id="f-post-<?=$p['id']?>" class="filter__chbox-inp" <?=$p['selected'] ? 'checked' : ''?>>
                  <label class='filter__chbox-lab' for="f-post-<?=$p['id']?>"><?=$p['name']?></label>
                <?php endforeach; ?>
              </div>
              <span class="more-posts">Показать все</span>
            </div>
          </div>
          <div class='filter__item filter-age'>
            <div class='filter__item-name opened'>Возраст</div>
            <div class='filter__item-content opened'>
              <div class="filter__age">
                <label>
                  <span>от</span>
                  <input name=af type='text' value="<?=$_POST['af']?>">
                </label>
                <label>
                  <span>до</span>
                  <input name='at' type='text' value="<?=$_POST['at']?>">
                </label> 
                <div class="filter__age-btn">ОК</div>
              </div>
            </div>
          </div>
          <div class='filter__item filter-sex'>
            <div class='filter__item-name opened'>Пол</div>
            <div class='filter__item-content opened'>
              <div class='right-box'>
                <input name='sm' type='checkbox' value='1' class="filter__chbox-inp" id="f-male"<?=($_POST['sm']?' checked':'')?>>
                <label class="filter__chbox-lab" for="f-male">Мужской</label>
                <input name='sf' type='checkbox' value='1' class="filter__chbox-inp" id="f-female"<?=($_POST['sf']?' checked':'')?>>
                <label class="filter__chbox-lab" for="f-female">Женский</label>
              </div>
            </div>
          </div>
          <div class='filter__item filter-additional'>
            <div class='filter__item-name opened'>Дополнительно</div>
            <div class='filter__item-content opened'>
              <div class='right-box'>
                <input name='mb' type='checkbox' value='1' class="filter__chbox-inp" id="f-med"<?=($_POST['mb']?' checked':'')?>>
                <label class="filter__chbox-lab" for="f-med">Наличие медкнижки</label>
                <input name='avto' type='checkbox' value='1' class="filter__chbox-inp" id="f-auto"<?=($_POST['avto']?' checked':'')?>>
                <label class="filter__chbox-lab" for="f-auto">Наличие автомобиля</label>
                <input name='smart' type='checkbox' value='1' class="filter__chbox-inp" id="f-smart"<?=($_POST['smart']?' checked':'')?>>
                <label class="filter__chbox-lab" for="f-smart">Наличие смартфона</label>
                <input name='cardPrommu' type='checkbox' value='1' class="filter__chbox-inp" id="f-pcard"<?=($_POST['cardPrommu']?' checked':'')?>>
                <label class="filter__chbox-lab" for="f-pcard">Банковская карта Prommu</label>
                <input name='card' type='checkbox' value='1' class="filter__chbox-inp" id="f-card"<?=($_POST['card']?' checked':'')?>>
                <label class="filter__chbox-lab" for="f-card">Банковская карта</label>
              </div>
            </div>
          </div>
          </div>
      </form>
    </div>
    <?
    //    CONTENT
    ?>
    <div class='col-xs-12 col-sm-8 col-md-9 sms-service'>
      <div class='view-radio clearfix'>
        <h1 class="main-h1">Выбрать персонал для PUSH информирования</h1>
        <form action="<?=MainConfig::$PAGE_PAYMENT ?>" method="POST" id="workers-form">
          <?php /*if($viData['price']!=0 && $viData['price']<1): ?>
            <div class="price-warning">Стоимость отправки сообщения для одного соискателя составляет <b><?=$viData['price']?> руб.</b><br/>Сумма минимальной платежной операции - <b>1 руб.</b></div>
          <?php endif;*/ ?>
          <span class="workers-form__cnt">Выбрано получателей: <span id="mess-wcount">0</span></span>
          <div class="service__switch">
            <span class="service__switch-name">Выбрать всех</span>
            <input type="checkbox" name="ntf-push" id="all-workers" value="1"/>
            <label for="all-workers">
              <span data-enable="вкл." data-disable="выкл."></span>
            </label>
          </div>
          <button type="submit" class="workers-form-btn off" id="workers-btn">сформировать PUSH уведомления</button>
          <input type="hidden" name="users" id="mess-workers">
          <input type="hidden" name="users-cnt" id="mess-wcount-inp" value="0">
          <input type="hidden" name="vacancy" value="<?=Yii::app()->getRequest()->getParam('vacancy')?>">
          <?php //if($viData['price']==0): ?>
            <input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
            <input type="hidden" name="service" value="push-notification">           
          <?php //endif; ?>
        </form>
      </div>
      <div id="promo-content">
        <div class='questionnaire'>
          <div>
            <?=$this->ViewModel->declOfNum($viData['app_count'], array('Найдена', 'Найдено', 'Найдено'))?>
            <b><?=$viData['app_count']?></b>
            <?=$this->ViewModel->declOfNum($viData['app_count'], array('Анкета', 'Анкеты', 'Анкет'))?>
          </div>
        </div>
        <div class='row vacancy table-view'>
          <?if( $viData['workers']['promo'] ):?>
            <?$i=1;?>
            <?foreach ($viData['workers']['promo'] as $item):?>
              <div class='col-xs-12 col-sm-6 col-md-4'>
                <?
                  $G_NOLIKES = 1;
                  $G_ALT = 'Соискатель ' . $item['firstname'] . ' ' . $item['lastname'] . ' prommu.com';
                  $G_LOGO_LINK = MainConfig::$PAGE_PROFILE_COMMON . DS . $item['id_user'];
                  if($item['sex'] === '1'){
                    $G_LOGO_SRC = DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$item['photo'] ? MainConfig::$DEF_LOGO : $item['photo'] . '400.jpg');
                  }
                  else 
                    $G_LOGO_SRC = DS . MainConfig::$PATH_APPLIC_LOGO . DS . (!$item['photo'] ? MainConfig::$DEF_LOGO_F : $item['photo'] . '400.jpg');
                  $G_COMP_FIO = $item['firstname'] . ' ' . $item['lastname'] . ', ' . $item['age'];
                  $G_RATE_POS = $item['rate'];
                  $G_RATE_NEG = $item['rate_neg'];
                  $G_COMMENTS_POS = $item['comm'];
                  $G_COMMENTS_NEG = $item['commneg'];
                  $G_TMPL_PH1 = '';
                  if( $item['ishasavto'] === '1' ) $G_TMPL_PH1 = "<div class='ico ico-avto js-g-hashint' title='Есть автомобиль'></div>";
                  if( $item['ismed'] === '1' ) $G_TMPL_PH1 .= '<div class="ico ico-med js-g-hashint" title="Есть медкнижка"></div>';
                  $G_TMPL_PH1 = "<div class='med-avto'>{$G_TMPL_PH1}</div>";
                  include $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user' . DS . MainConfig::$VIEWS_COMM_LOGO_TPL . ".php";
                ?>
                <input type="checkbox" name="promo[]" value="<?=$item['id_user']?>" class="promo_inp" id="promo<?=$item['id_user']?>">
                <label class="smss-promo__label" for="promo<?=$item['id_user']?>"></label>
              </div>
              <?if($i % 2 == 0):?>
                <div class="clear visible-sm"></div>
              <?endif?>
              <?if( $i % 3 == 0 ):?>
                <div class="clear visible-md visible-lg"></div>
              <?endif?>
              <?$i++;?>
            <?endforeach?>
          <?else:?>
            <div class="col-xs-12">Нет подходящих соискателей</div>
          <?endif;?>
        </div>
        <br>
        <br>
        <div class='paging-wrapp hidden-xs'>
        <?// display pagination
          $this->widget('CLinkPager', array(
              'pages' => $viData['pages'],
              'htmlOptions' => array('class' => 'paging-wrapp'),
              'firstPageLabel' => '1',
              'prevPageLabel' => 'Назад',
              'nextPageLabel' => 'Вперед',
              'header' => ''
            )
          )?>
        </div>
      </div>
    </div>
  </div>
<?php else: ?>
  <?php 
    //    Оплата сообщений
    //
    $appCount = Yii::app()->getRequest()->getParam('users-cnt'); 
  ?>
  <div class="row">
    <div class="col-xs-12 sms-service">
      <form action="<?=MainConfig::$PAGE_PAYMENT?>" method="POST" class="smss__result-form">
      <h1 class="smss-result__title">ТЕКСТ РАССЫЛКИ</h1>
			<span class="smss-result__result" style="font-size:23px">Работодатель <?=Share::$UserProfile->exInfo->name?> приглашает на вакансию <a href="https://prommu.com/vacancy/<?=Yii::app()->getRequest()->getParam('vacpush')?>">https://prommu.com/vacancy/<?=Yii::app()->getRequest()->getParam('vacpush')?></a></span></br>
        <h1 class="smss-result__title">РАСЧЕТ СТОИМОСТИ УСЛУГИ</h1>
        <table class="smss-result__table">
          <tr>
            <td>Количество получателей</td>
            <td><?=$appCount?></td>
          </tr>
        <?/*  <tr>
            <td>Стоимость отправки одного сообщения</td>
            <td><?=$viData['price']?> руб.</td>
          </tr>
          <tr>
            <td>Сумма минимальной платежной операции</td>
            <td>1 руб.</td>
          </tr> */ ?>
        </table>
        <?php /*$result = $appCount * $viData['price'];?>
        <?php $result = $result<1 ? 1 : $result;?>
        <span class="smss-result__result"><?echo $appCount . ' * ' . $viData['price'] . ' = ' . $result . ' руб.'?></span> */ ?>
        <button class="smss-result__btn">Перейти к оплате</button>
        <input type="hidden" name="vacancy" value="<?=Yii::app()->getRequest()->getParam('vacpush')?>">
        <input type="hidden" name="users-cnt" value="<?=$appCount?>">
        <input type="hidden" name="users" value="<?=Yii::app()->getRequest()->getParam('users')?>">
        <input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
        <input type="hidden" name="service" value="push-notification">
      </form>
    </div>
  </div>
<?php endif; ?>