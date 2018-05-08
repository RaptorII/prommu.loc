<?php if(!(MOBILE_DEVICE && !SHOW_APP_MESS)): // optimization ?>
  <?php //Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . '/theme/css/services/services-form.css'); ?>
  <style type="text/css">
    /*  /theme/css/services/services-form.css   */
    #DiSiteWrapp #DiContent #MWwrapper .btn-white-green-2 .order-service__btn:focus,#DiSiteWrapp #DiContent .order-service__input:focus,.form-payment__btn:focus{outline:0}#DiSiteWrapp #DiContent #MWwrapper .mw-win{width:350px;padding:40px 37px 30px;border:none;background:#61b718;background:-moz-linear-gradient(-45deg,#61b718 0,#95db00 25%,#a2d81a 50%,#61b718 75%,#40840d 100%);background:-webkit-linear-gradient(-45deg,#61b718 0,#95db00 25%,#a2d81a 50%,#61b718 75%,#40840d 100%);background:linear-gradient(135deg,#61b718 0,#95db00 25%,#a2d81a 50%,#61b718 75%,#40840d 100%);filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#61b718', endColorstr='#40840d', GradientType=1 )}#DiSiteWrapp #DiContent #MWwrapper .mw-win.dark-ver{left:-175px}#DiSiteWrapp #DiContent #MWwrapper .mw-close{width:29px;height:29px;background:0 0;border:1px solid #FFF;border-radius:15px;top:13px;right:17px}#DiSiteWrapp #DiContent #MWwrapper .mw-close:before{content:'X';width:28px;line-height:28px;display:block;position:absolute;top:0;left:0;color:#FFF;font-size:18px;text-align:center;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif}#DiSiteWrapp #DiContent #MWwrapper .header{margin:0;position:static;border:none}#DiSiteWrapp #DiContent #MWwrapper .header div{position:static;background:0 0;color:#FFF;padding:0;font-weight:400;font-size:30px;font-family:LatoLight,Calibri,Arial,sans-serif;line-height:30px;margin-bottom:30px}#DiSiteWrapp #DiContent #MWwrapper .header2{padding-bottom:20px;margin-bottom:40px;border-bottom:1px solid #FFF;color:#d5e525;font-size:18px;font-weight:400;line-height:18px;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif}#DiSiteWrapp #DiContent #MWwrapper .order-service__label{width:100%;padding-left:35px;margin-bottom:35px;background:url(/theme/pic/service-form/service-icons.png) no-repeat}#DiSiteWrapp #DiContent #MWwrapper .order-service__label.icon1{background-position:0 7px}#DiSiteWrapp #DiContent #MWwrapper .order-service__label.icon2{background-position:0 -41px}#DiSiteWrapp #DiContent #MWwrapper .order-service__label.icon3{background-position:0 -89px}#DiSiteWrapp #DiContent #MWwrapper .order-service__label .order-service__input{width:100%;height:39px;border:1px solid #FFF;background:0 0;color:#FFF;padding:0 8px;font-family:LatoRegular,Calibri,Arial,sans-serif}#DiSiteWrapp #DiContent #MWwrapper .order-service__label .order-service__input.error{border:1px solid red}.order-service__input::-webkit-input-placeholder{color:#FFF}.order-service__input::-moz-placeholder{color:#FFF}.order-service__input:-moz-placeholder{color:#FFF}.order-service__input:-ms-input-placeholder{color:#FFF}#DiSiteWrapp #DiContent #MWwrapper .btn-white-green-2{margin:0}#DiSiteWrapp #DiContent #MWwrapper .btn-white-green-2 .order-service__btn{width:48%;height:38px;border:none;padding:0;background-color:#ff8300;color:#FFF;font-size:18px;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif}.order-service__btn.order-service__btn-reg{display:inline-block;line-height:38px;vertical-align:bottom;text-transform:uppercase;cursor:pointer;position:relative}.order-service__reg-list{position:absolute;bottom:-76px;left:0;display:none}.order-service__btn-reg:hover .order-service__reg-list{display:block}#DiContent #MWwrapper .order-service__btn-reg .order-service__reg-list .items{width:193px;border:none;padding:0}@media (min-width:768px){#DiSiteWrapp #DiContent #MWwrapper .mw-win{width:470px}#DiSiteWrapp #DiContent #MWwrapper .mw-win.dark-ver{left:-235px}#DiSiteWrapp #DiContent #MWwrapper .order-service__label{padding-left:61px}#DiSiteWrapp #DiContent #MWwrapper .btn-white-green-2 .order-service__btn{width:193px}}.disable-form,.premium-form,.push-form,.services-finish-form,.repost-to-social-form,.email-invitation-form,.sms-form{font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;color:#FFF;text-align:center}.disable-form b,.premium-form b,.push-form b,.services-finish-form b,.repost-to-social-form b,.email-invitation-form b,.sms-form b{font-family:RobotoCondensedBold,Calibri,Arial,sans-serif;display:block}.form-str1__header{font-size:16px;text-align:center;line-height:18px}.form-str1__header b{color:#b7fe73}.form-str1__content-utext{font-size:18px;text-transform:uppercase;margin-bottom:20px;line-height:normal}.form-str1__content-dtext{margin-bottom:20px;text-align:justify}.form-str1__points{text-transform:uppercase;font-size:16px;position:relative;padding:0 5px;display:inline-block;line-height:normal}.form-str1__points:after,.form-str1__points:before{content:"";width:8px;height:8px;display:block;position:absolute;top:5px;background-color:#FFF;border-radius:4px}.form-str1__points:before{left:-8px}.form-str1__points:after{right:-8px}.form-str__btn{width:255px;line-height:38px;background-color:#FF8300;position:relative;display:inline-block;margin:20px auto 0;cursor:pointer;font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif;font-size:18px}.form-payment__radio-name,.form-payment__type-name{font-family:LatoRegular,Calibri,Arial,sans-serif;color:#FFF}.form-str__btn-list{position:absolute;bottom:-60px;line-height:30px;left:0;display:none}.form-str__btn:hover .form-str__btn-list{display:block;width:100%}#DiContent #MWwrapper .form-str__btn .form-str__btn-list .item{width:100%;padding:0}@media (min-width:768px){#DiSiteWrapp #DiContent #MWwrapper .light-ver{height:460px}#DiSiteWrapp #DiContent #MWwrapper .light-ver:before{content:'';width:320px;height:499px;position:absolute;left:-149px;bottom:0;background:url(/theme/pic/service-form/services-form-girl.png) no-repeat}}#DiSiteWrapp #DiContent #MWwrapper .mw-win.dark-ver{background:#5aad17;background:-moz-linear-gradient(-45deg,#5aad17 0,#0f4801 100%);background:-webkit-linear-gradient(-45deg,#5aad17 0,#0f4801 100%);background:linear-gradient(135deg,#5aad17 0,#0f4801 100%);filter:progid:DXImageTransform.Microsoft.gradient( startColorstr='#5aad17', endColorstr='#0f4801', GradientType=1 )}.form-str2__header{font-size:18px;text-align:center;line-height:22px;text-transform:uppercase}.form-str2__content,.form-str2__list{font-size:16px;font-weight:700;text-transform:uppercase}.push-form hr{margin:20px 0 25px}.form-str2__content{text-align:justify}.form-str2__list{text-align:left}.form-str2__list li{margin-top:20px;padding:0 0 0 18px;position:relative}.form-str2__list li:before{content:'';width:10px;height:10px;position:absolute;top:5px;left:0;background-color:#FF8300;border-radius:5px}.form-str2__content i{font-style:italic;font-size:18px;text-align:justify;display:block;line-height:normal}.push-form .form-str__btn{margin-top:0}.disable-form i,.services-finish-form i,.repost-to-social-form i,.email-invitation-form i{text-align:right;display:block}.form-payment__type-name{text-transform:uppercase;font-size:16px;margin-bottom:10px;display:inline-block}.form-payment__radio-label{height:25px;display:block;position:relative;margin:0 0 10px;padding:0 0 0 35px;text-transform:uppercase;cursor:pointer;font-size:14px}#DiContent .form-payment__radio-input{display:none}.form-payment__radio-block{height:25px;width:25px;background:#FFF;border:1px solid #c8c8c8;border-radius:2px;position:absolute;left:0;top:0}.form-payment__radio-block:before{content:'';width:11px;height:11px;position:absolute;left:6px;top:6px;display:block}.form-payment__radio-name{vertical-align:sub}.form-payment__btn,.form-payment__calendar-table,.form-payment__period,.form-payment__price,.form-payment__vacancies{font-family:RobotoCondensedRegular,Calibri,Arial,sans-serif}#DiContent .form-payment__radio-label input:checked+.form-payment__radio-block:before{background:#abb820}.form-payment__radio-label:hover .form-payment__radio-block:before{background-color:#e6e6e6}.form-payment__btn{width:193px;height:38px;border:none;padding:0;margin:0 auto;display:block;background-color:#ff8300;color:#FFF;font-size:18px;position:relative;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out;cursor:pointer;z-index:1}.form-payment__btn:before{content:"";position:absolute;z-index:-1;top:0;left:0;right:0;bottom:0;background:#bbc823;-webkit-transform:scaleX(0);transform:scaleX(0);-webkit-transform-origin:0 50%;transform-origin:0 50%;-webkit-transition:all .3s ease-out;-moz-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out}.form-payment__btn:hover:before{-webkit-transform:scaleX(1);transform:scaleX(1)}#calendar-btn.form-payment__btn{width:260px;line-height:38px;text-align:center;margin:0 auto 10px}.form-payment__calendar{background:#FFF;padding:10px;position:absolute;bottom:25px;left:-100px;width:200px;margin-left:50%;border:1px solid #EBEBEB;z-index:2;display:none}.form-payment__calendar-table{width:100%;font-size:14px;text-align:center;color:#646464}.form-payment__calendar-table tbody td,.form-payment__calendar-table thead tr:nth-child(1) td:nth-child(1):hover,.form-payment__calendar-table thead tr:nth-child(1) td:nth-child(3):hover{cursor:pointer}.form-payment__calendar-table .holiday,.form-payment__calendar-table tbody td:nth-child(n+6){color:#ff8300}.form-payment__calendar-table .day.today,.form-payment__calendar-table .day.today:hover{background-color:#abb820;cursor:no-drop}.form-payment__calendar-table .day:hover{background-color:#e6e6e6}.form-payment__calendar-table .empty:hover{background-color:transparent;cursor:default}.form-payment__calendar-table .past:hover{background-color:red;cursor:no-drop}.form-payment__period,.form-payment__price,.form-payment__vacancies{color:#FFF;font-size:16px}.form-payment__result{color:#FFF;font-size:24px;font-family:RobotoCondensedBold,RobotoCondensedRegular,Calibri,Arial,sans-serif;margin-bottom:10px}.form-payment__period{margin-bottom:10px}
  </style>
<?php endif; ?>
<?
  $id = Share::$UserProfile->id;
  $sql = "SELECT s.val phone
      FROM user_attribs s
      WHERE s.id_us = {$id}
      AND s.id_attr = 1";
  $res = Yii::app()->db->createCommand($sql)->queryScalar();
?>
<form action="" class="form-order-tpl tmpl" id="F1serviceOrder" data-header="Заказать услугу">
  <input type="hidden" name="id" id="HiId"/>
  <label class="field-t2-hor order-service__label icon1" title="Имя и Фамилия">
    <input type="text" name="fio" placeholder="Имя и Фамилия" data-field-check='name:ФИО,empty' value="<?= Share::$UserProfile->exInfo->lastname ?>" class="order-service__input fio"/>
  </label>
  <label class="field-t2-hor order-service__label icon2" title="Мобильный телефон">
    <input data-field-filter='digits:+,( );max:12' type="text" id="service-phone" name="tel" placeholder="Телефон" data-field-check='name:Телефон,empty' value="<?= $res ?>" class="order-service__input"/>
  </label>
  <label class="field-t2-hor order-service__label icon3" title="Email">
    <input type="text" name="email" placeholder="Email" value="<?= Share::$UserProfile->exInfo->email ?>" class="order-service__input" id="service-email"/>
  </label>
  <div class="btn-white-green-2 field-t2-hor">
    <button type="submit" class="btn-order-create order-service__btn">Заказать</button>
    <span class="order-service__btn order-service__btn-reg">
      <ul class="order-service__reg-list">
        <li><a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '2'))?>" class="items">Я работодатель</a></li>
        <li><a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '1'))?>" class="items">Я ищу работу</a></li>
      </ul>Регистрация
    </span>
  </div>
  <input type="hidden" class="id" name="id" value="<?= Share::$UserProfile->exInfo->id ?>">
  <input type="hidden" class="referer" name="referer" value="">
  <input type="hidden" class="transition" name="transition" value="">
  <input type="hidden" class="canal" name="canal" value="">
  <input type="hidden" class="campaign" name="campaign" value="">
  <input type="hidden" class="content" name="content" value="">
  <input type="hidden" class="keywords" name="keywords" value="">
  <input type="hidden" class="point" name="point" value="">
  <input type="hidden" class="last_referer" name="last_referer" value="">
</form>
<div class="order-success-tpl tmpl">Ваш заказ оформлен успешно, наш менеджер свяжется с вами</div>
<?
/*
*
*   SMS
*
*/
?>
<div class="services-form sms-form tmpl">
  <div class="form-str1__header">Уважаемый пользователь!<br>Для того что бы использовать данную услугу Вам необходимо<b>ЗАРЕГИСТРИРОВАТЬСЯ</b></div>
  <hr>
  <div class="form-str1__content">
    <p class="form-str1__content-utext">После регистрации<br>Вы сможете<b>создавать вакансии</b>для поиска необходимого персонала</p>
    <p class="form-str1__content-dtext">А для более быстрого поиска персонала для своей вакансии, Вы сможете использовать услугу рассылку СМС сообщений по выбранному персоналу, используя при этом удобные фильтры</p>
    <span class="form-str1__points">по нужному городу</span><br>
    <span class="form-str1__points">по нужным должностям персонала</span>
    <div class="btn-white-green-2 field-t2-hor">
      <span class="form-str__btn">
        <ul class="form-str__btn-list">
          <li><a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '2'))?>" class="item">Я работодатель</a></li>
          <li><a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '1'))?>" class="item">Я ищу работу</a></li>
        </ul>ЗАРЕГИСТРИРОВАТЬСЯ
      </span>
    </div>
  </div>
</div>
<?
/*
*
*   PREMIUM
*
*/
?>
<div class="services-form premium-form tmpl">
  <div class="form-str1__header">Уважаемый пользователь!<br>Для того что бы использовать данную услугу Вам необходимо<b>ЗАРЕГИСТРИРОВАТЬСЯ</b></div>
  <hr>
  <div class="form-str1__content">
    <p class="form-str1__content-utext">После регистрации<br>Вы сможете<b>создавать вакансии</b>для поиска необходимого персонала</p>
    <p class="form-str1__content-dtext">А услуга ПРЕМИУМ даст преимущество и выделит Вас среди большого количества остальных работодателей, и ускорит поиск подходящих кандидатов для выполнения Ваших заданий</p>
    <div class="btn-white-green-2 field-t2-hor">
      <span class="form-str__btn">
        <ul class="form-str__btn-list">
          <li><a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '2'))?>" class="item">Я работодатель</a></li>
          <li><a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '1'))?>" class="item">Я ищу работу</a></li>
        </ul>ЗАРЕГИСТРИРОВАТЬСЯ
      </span>
    </div>
  </div>
</div>
<?
/*
*
*   PUSH
*
*/
?>
<div class="services-form push-form tmpl">
  <div class="form-str2__header">уважаемый пользователь,<br>для того, что бы пользоваться данной услугой вам необходимо<br>зарегестрироваться</div>
  <hr>
  <p class="form-str2__content">
    После регистрации Вы сможете всегда быть в курсе событий по следующим изменениям:
    <ul class="form-str2__list">
      <li>Отклик на созданную вакансию Соискателем</li>
      <li>Сообщение по созданной вакансии от выбранного Соискателя</li>
      <li>Подтверждение Соискателем на приглашение в участии созданной вакансии</li>
      <li>Изменение рейтинга</li>
      <li>Появление нового отзыва</li>
    </ul>
    <hr>
      <i>* При создании Вакансии Вы сможете настраивать нужные оповещения и оставлять включенными только нужные:</i>
    <hr>
  </p>
  <div class="btn-white-green-2 field-t2-hor">
    <span class="form-str__btn">
      <ul class="form-str__btn-list">
        <li><a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '2'))?>" class="item">Я работодатель</a></li>
        <li><a href="<?=Yii::app()->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '1'))?>" class="item">Я ищу работу</a></li>
      </ul>ЗАРЕГИСТРИРОВАТЬСЯ
    </span>
  </div>
</div>
<?
/*
*
*   SERVICE IN DEVELOPMENT
*
*/
?>
<div class="services-form disable-form tmpl">
  <div class="form-str2__header">Извините. Данная услуга еще в разработке.<br>Как только она активизируется - мы обязательно оповестим Вас.</div>
  <br>
  <i>С найлучшими пожеланиями команда Промму!</i>
</div>
<?
/*
*
*   SERVICE IS ORDERED
*
*/
?>
<div class="services-form services-finish-form tmpl">
  <div class="form-str2__header">Ваш запрос по услуге успешно отправлен. Наш менеджер свяжется с Вами в течении ближайшего времени.</div>
  <br>
  <i>С найлучшими пожеланиями команда Промму!</i>
</div>
<?
/*
*
*   REPOST TO SOCIAL
*
*/
?>
<div class="services-form repost-to-social-form tmpl">
  <div class="form-str2__header">Ваши вакансии будут размещены в группах промму в ближайшее время. Ссылку на репост Вы найдете на странице вакансии</div>
  <br>
  <i>С найлучшими пожеланиями команда Промму!</i>
</div>
<?
/*
*
*   REPOST TO SOCIAL
*
*/
?>
<div class="services-form email-invitation-form tmpl">
  <div class="form-str2__header">Выбраным соискателям разослано приглашение на вакансию</div>
  <br>
  <i>С найлучшими пожеланиями команда Промму!</i>
</div>