<?
  $bUrl = Yii::app()->baseUrl;
  // Cropper
  Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/jslib/cropperjs/cropper.min.css');
  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/jslib/cropperjs/cropper.min.js', CClientScript::POS_END);
?>

<meta name="robots" content="noindex">
<?php if(empty($_GET['uid'])):?>
  <?php
    Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/phone-codes/style.css');
    Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/private/page-edit-prof-emp.css');
    Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/phone-codes/script.js', CClientScript::POS_END);
    Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/private/page-edit-prof-emp.js', CClientScript::POS_END);
    // all Cities
    $Q1 = Yii::app()->db->createCommand()
      ->select('t.id_city id, t.name, t.ismetro, t.id_co')
      ->from('city t')
      ->limit(10000);
    $arCities = $Q1->queryAll();
    $arCitiesJS = array();
    $cityId = 0;
    foreach ($arCities as $key => $c){
      if($_GET['city'] == $c['name']){
        $cityId = $c['id'];
        $cityName = $_GET['city'];
        Yii::app()->db->createCommand()
          ->update('user_city', 
            array('id_city'=>$c['id']), 
            'id_user = :id', 
            array(':id' => Share::$UserProfile->exInfo->id)
          );
      }
      $arCitiesJS[$c['id']] = $c['name'];
    }
    if(!$cityId){
      $cityId = $viData['userCities'][0]['id'];
      $cityName = $viData['userCities'][0]['name'];
    }

    // оптимизируем массив городов для JS
    $arCitiesJS = array_unique($arCitiesJS);
    asort($arCitiesJS);
    // logo SRC for social
    $G_LOGO_SRC = DS . MainConfig::$PATH_EMPL_LOGO . DS . (!Share::$UserProfile->exInfo->logo ?  $_GET['logo'].'400.jpg' : (Share::$UserProfile->exInfo->logo ? Share::$UserProfile->exInfo->logo . '400.jpg' : $_GET['logo'].'400.jpg'));
    // social 
    $attrAll = $viData['userAttribs'];
    $arMess = array();
    $idViber = $this->ViewModel->isInArray($attrAll, 'key', 'viber');
    $idWhatsApp = $this->ViewModel->isInArray($attrAll, 'key', 'whatsapp');
    $idTelegram = $this->ViewModel->isInArray($attrAll, 'key', 'telegram');
    $idGoogleAllo = $this->ViewModel->isInArray($attrAll, 'key', 'googleallo');
    empty($attrAll[$idViber]['val']) ? : $arMess[] = 'Viber';
    empty($attrAll[$idWhatsApp]['val']) ? : $arMess[] = 'WhatsApp';
    empty($attrAll[$idTelegram]['val']) ? : $arMess[] = 'Telegram';
    empty($attrAll[$idGoogleAllo]['val']) ? : $arMess[] = 'Google Allo';
    // Телефон
    $phone = $attrAll[1]['phone'];
    $emplInfo = $viData['emplInfo'];
    if(!$_GET['phone'] && !$attrAll[1]['phone-code']){ // закрыли попап не сохранив
      $city = (new Geo())->getUserGeo();   
      foreach($viData['countries'] as $c)
        if($c['id_co']==$city['country'])
          $attrAll[1]['phone-code'] = $c['phone'];
    }
    else if($_GET['phone']){  // пошли через попап
      $phone = $_GET['phone'];
      $attrAll[1]['phone-code'] = $_GET['__phone_prefix'];
    }    
    //  email
    $emplInfo['email'] = filter_var($emplInfo['email'], FILTER_VALIDATE_EMAIL);
  ?>
  <?php if( $viErrorData['err'] ): ?>
    <div class="err-msg-block">При сохранении данных профиля произошла ошибка. <?= $viErrorData['msg'] ?></div>
  <?php endif; ?>
  <script type="text/javascript">
    var arCities = <?=json_encode($arCitiesJS);?>;
    var selectPhoneCode = <?=json_encode($attrAll[1]['phone-code'])?>;
    G_VARS.countryID = <?= $viData['userCities'][0]['id_co'] ?>;
    jQuery(function($){
      var mess = <?=$_GET['mess'] ? $_GET['mess'] : 0 ?>;
      var rate = <?=$_GET['rate'] ? $_GET['rate'] : 0 ?>;
      var all = <?=$_GET['all'] ? $_GET['all'] : 0 ?>;
      var invite = <?=$_GET['invite'] ? $_GET['invite'] : 0 ?>;
      var workday = <?=$_GET['workday'] ? $_GET['workday'] : 0 ?>;
      if(all || workday || invite || mess || rate) {
        $.post( "https://prommu.com/user/push/?mess=" + mess + "&rate=" + rate + "&invite=" + 
        invite + "&workday=" + workday, function( data ) {});
      }
    });
  </script>
  <form action='' id='F1compprof' method='post' class="edit-profile-employer">
    <div class='col-xs-12'>
      <div class="epe__header">
        <h1 class="epe__header-title"><?=$emplInfo['name']?></h1>
      </div>
    </div>
    <div class="col-xs-12 col-md-8 col-md-push-2 epe__content">
      <div class="row">
        <div class="epe__logo">
          <div class='epe__logo-img'>
            <img src='<?=$G_LOGO_SRC?>' alt="">
            <a href="<?=MainConfig::$PAGE_EDIT_PROFILE . '?ep=1'?>" class="epe__logo-edit">Изменить аватар</a>
          </div>
          <div class="epe-logo__btn-block" id="load-img-module">
            <div class="epe-logo__load js-g-hashint" id="btn-load-image" title="Выбрать изображение"></div>
            <div class="epe-logo__webcam js-g-hashint" id="btn-get-snapshot" title="Сделать снимок"></div>
            <div class="clearfix"></div>
          </div>
          <?php if(!$emplInfo['confirmEmail'] && !empty($emplInfo['email'])): ?>
            <div class="confirm-user email">Необходимо подтвердить почту</div>
          <?php endif; ?>
          <?php if(!$emplInfo['confirmPhone'] && !empty($phone)): ?>
            <div class="confirm-user phone">Необходимо подтвердить телефон</div>
          <?php endif; ?>    
        </div>
        <div class="epe__data">
          <div class="row">
            <div class="col-xs-12">
              <div class="epe-data__title"><h2>ОСНОВНАЯ ИНФОРМАЦИЯ</h2></div>
              <div class="epe-data__module">
                <label class="epe__label">
                  <span class="epe__label-name">Название компании:</span>
                  <input type='text' name='name' value="<?= $emplInfo['name'] ?>" class="epe__input epe__input-name epe__required">
                </label>
                <div class="epe__label epe__select">
                  <?php 
                    $strInp = '';
                    $typeName = '';
                    foreach($viData['cotype'] as $t){
                      $strInp .= '<li><input type="radio" name="companyType" value="' . $t['id'] . '" id="type' . $t['id'] . '"';
                      if($_GET['position']==$t['id']){
                        $strInp .= ' checked';
                        $typeName = $t['name'];
                      }
                      elseif($t['selected']){
                        $strInp .= ' checked';
                        $typeName = $t['name'];
                      }
                      $strInp .= '><label for="type' . $t['id'] . '">' . $t['name'] . '</label></li>';
                    }
                  ?>
                  <span class="epe__label-name">Тип компании:</span>
                  <span class="epe__input epe__input-type" id="epe-str-type"><?=$typeName?></span>
                  <div class="epe__label-veil" id="epe-veil-type"></div>
                  <ul class="epe__select-list" id="epe-list-type"><i class="epe__select-list-icon">ОК</i><?=$strInp?></ul>
                </div>
                <div class="epe__label">
                  <span class="epe__label-name">Город:</span>
                  <input type='text' name='str-city' value="<?=$cityName?>" class="epe__input epe__input-city" autocomplete="off">
                  <input type="hidden" name="cities[]" value="<?=$cityId?>" id="id-city">
                  <ul class="city-list"></ul>
                </div>
                <label class="epe__label">
                  <span class="epe__label-name">Web Сайт:</span>
                  <input type='text' name='user-attribs[site]' value="<?=$viData['userAttribs'][99]['val']?>" class="epe__input epe__input-site">
                </label>
              </div>
              <?
              //
              ?>
              <div class="epe-data__title"><h2>КОНТАКТНАЯ ИНФОРМАЦИЯ</h2></div>
              <div class="epe-data__module">
                <label class="epe__label">
                  <span class="epe__label-name">Ваше имя:</span>
                  <input type='text' name='fname' value="<?=$emplInfo['firstname']?>" class="epe__input epe__input-fname">
                </label>
                <label class="epe__label">
                  <span class="epe__label-name">Ваша фамилия:</span>
                  <input type='text' name='lname' value="<?=$emplInfo['lastname']?>" class="epe__input epe__input-lname">
                </label>
                <label class="epe__label epe__email" data-error="Указанный e-mail адрес уже используется в системе" for="epe-email">
                  <span class="epe__label-name">Ваша email:</span>
                  <input type='text' name='email' value="<?=$emplInfo['email']?>" class="epe__input epe__input-mail epe__required" id="epe-email">
                  <span class="epe__confirm<?=($emplInfo['confirmEmail'] && !empty($emplInfo['email'])?' complete':'')?>" id="conf-email">
                    <?php if($emplInfo['confirmEmail'] && !empty($emplInfo['email'])): ?>
                      <p>Почта подтверждена.</p>
                    <?php else: ?>
                      <p>Почта не подтверждена. <em>Подтвердить</em></p>
                    <?php endif; ?>
                  </span>          
                </label>
                <div class="epe__confirm-block" id="conf-email-block">
                  <span class="epe__confirm-text">На Вашу почту выслан код для подтверждения. Введите его в это поле!</span>
                  <label class="epe__label">
                    <span class="epe__label-name">Проверочный код:</span>
                    <input type='text' name='confirm-code' value="" class="epe__input" id="conf-email-inp" maxlength="6">
                  </label>
                  <dir class="epe__confirm-btn hvr-sweep-to-right">ПРОВЕРИТЬ</dir>
                  <dir class="clearfix"></dir>
                </div>
                <div class="epe__label">
                  <span class="epe__label-name epe__phone-name">Телефон:</span>
                  <input type='text' name='user-attribs[mob]' value="<?=$phone?>" class="epe__input epe__phone epe__input-phone" id="phone-code">
                  <span class="epe__confirm<?=($emplInfo['confirmPhone']?' complete':'')?>" id="conf-phone">
                    <?php if(!$emplInfo['confirmPhone']): ?>
                      <p>Телефон не подтвержден. <em>Подтвердить</em></p>
                    <?php else: ?>
                      <p>Телефон подтвержден.</p>
                    <?php endif; ?>
                  </span>
                </div>
                <div class="epe__confirm-block" id="conf-phone-block">
                  <span class="epe__confirm-text">На Ваш телефон выслан код для подтверждения. Введите его в это поле!</span>
                  <label class="epe__label">
                    <span class="epe__label-name">Проверочный код:</span>
                    <input type='text' name='confirm-code' value="" class="epe__input" id="conf-phone-inp" maxlength="6">
                  </label>
                  <dir class="epe__confirm-btn hvr-sweep-to-right">ПРОВЕРИТЬ</dir>
                  <dir class="clearfix"></dir>
                </div>
                <div class="epe__label epe__mess epe__select">
                  <span class="epe__label-name">Мессенджеры:</span>
                  <input type="text" name="epe-str-mess" value="<?=implode(',',$arMess)?>" class="epe__input epe__input-soc" id="epe-str-mess" disabled>
                  <div class="epe__label-veil" id="epe-veil-mess"></div>
                  <ul class="epe__select-list" id="epe-list-mess">
                      <i class="epe__select-list-icon">OK</i>
                      <li>
                          <input type="checkbox" name="epe-mess-viber" value="1" id="epe-mess-viber" data-mess="viber" <?=empty($attrAll[$idViber]['val'])?'':'checked'?>>
                          <label for="epe-mess-viber">Viber<b></b></label>
                      </li>
                      <li>
                          <input type="checkbox" name="epe-mess-wapp" value="1" id="epe-mess-wapp" data-mess="wapp" <?=empty($attrAll[$idWhatsApp]['val'])?'':'checked'?>>
                          <label for="epe-mess-wapp">WhatsApp<b></b></label>
                      </li>
                      <li>
                          <input type="checkbox" name="epe-mess-tele" value="1" id="epe-mess-tele" data-mess="tele" <?=empty($attrAll[$idTelegram]['val'])?'':'checked'?>>
                          <label for="epe-mess-tele">Telegram<b></b></label>
                      </li>
                      <li>
                          <input type="checkbox" name="epe-mess-allo" value="1" id="epe-mess-allo" data-mess="allo" <?=empty($attrAll[$idGoogleAllo]['val'])?'':'checked'?>>
                          <label for="epe-mess-allo">Google Allo<b></b></label>
                      </li>
                  </ul>
                </div>
                <div class="epe__mess epe__mess-viber <?=empty($attrAll[$idViber]['val'])?'off':''?>">
                  <div class="epe__label">
                    <span class="epe__label-name epe__phone-name">Viber:</span>
                    <input type="text" name="user-attribs[viber]" value="<?=$attrAll[$idViber]['val']?>" class="epe__input epe__phone phone-input" autocomplete="off">
                    <ul class="phone-list"></ul>
                  </div>
                </div>
                <div class="epe__mess epe__mess-wapp <?=empty($attrAll[$idWhatsApp]['val'])?'off':''?>">
                  <div class="epe__label">
                    <span class="epe__label-name epe__phone-name">WhatsApp:</span>
                    <input type="text" name="user-attribs[whatsapp]" value="<?=$attrAll[$idWhatsApp]['val']?>" class="epe__input epe__phone phone-input" autocomplete="off">
                    <ul class="phone-list"></ul>
                  </div>
                </div>
                <div class="epe__mess epe__mess-tele <?=empty($attrAll[$idTelegram]['val'])?'off':''?>">
                  <div class="epe__label">
                    <span class="epe__label-name epe__phone-name">Telegram:</span>
                    <input type="text" name="user-attribs[telegram]" value="<?=$attrAll[$idTelegram]['val']?>" class="epe__input epe__phone phone-input" autocomplete="off">
                    <ul class="phone-list"></ul>
                  </div>
                </div>
                <div class="epe__mess epe__mess-allo <?=empty($attrAll[$idGoogleAllo]['val'])?'off':''?>">
                  <div class="epe__label">
                    <span class="epe__label-name epe__phone-name">Google Allo:</span>
                    <input type="text" name="user-attribs[googleallo]" value="<?=$attrAll[$idGoogleAllo]['val']?>" class="epe__input epe__phone phone-input" autocomplete="off">
                    <ul class="phone-list"></ul>
                  </div>
                </div>
                <label class="epe__label">
                  <span class="epe__label-name">Ваша должность:</span>
                  <input type='text' name='user-attribs[post]' value="<?=$viData['userAttribs'][100]['val']?>" class="epe__input epe__input-pos">
                </label>
                <input type='checkbox' name='user-attribs[isnews]' value="1" <?=$viData['userAttribs'][108]['val'] ? 'checked' : '' ?> class="epe__hidden" id="subscribtion">
                <label class="epe__checkbox" for="subscribtion">Получать новости об изменениях и новых возможностях на сайте</label>
                <button class='epe__btn' type='submit'>Сохранить изменения</button>
                <input type="hidden" name="logo" id="HiLogo"/>
                <input type="hidden" name="savest" value="1"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
<?php endif; ?>
<?php if(!empty($_GET['uid'])): ?>
  <?
  //
  //    POPUP       
  //
  Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/register-popup/register-popup-emp.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/phone-codes/style.css'); 
  Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/phone-codes/script.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/register-popup/register-popup-emp.css');

    $city = (new Geo())->getUserGeo(); 
    $attr = $viData['userAttribs'];
    $G_LOGO_SRC = DS . MainConfig::$PATH_EMPL_LOGO . DS . (!Share::$UserProfile->exInfo->logo ?  '' : Share::$UserProfile->exInfo->logo . '.jpg');
    // Телефон
    foreach($viData['countries'] as $c){
      if($attr[1]['val']){ // регистрация через телефон
        if($c['phone']==$attr[1]['phone-code']){
          $city['country'] = $c['id_co'];
          break;
        }
      }
      else if($c['id_co']==$city['country']){ // регистрация через почту
        $attr[1]['phone-code'] = $c['phone'];
      }
    }
  ?>
  <script type="text/javascript">
    var selectPhoneCode = <?=json_encode($attr[1]['phone-code'])?>;
    var country = <?=json_encode($city['country'])?>;
    var arCountries = <?=json_encode($viData['countries'])?>;
  </script>
  <div class="register-popup js-popup show">
    <div class="register-popup__header">
      <h1 class="rp-header__title">ПОЗДРАВЛЯЕМ ВАС С УСПЕШНОЙ РЕГИСТРАЦИЕЙ</h1> 
      <a class="rp-header__close-btn close" href="<?=MainConfig::$PAGE_EDIT_PROFILE?>">&#10006</a>
    </div>
    <div class="register-popup__content1">
      <div class="rp-content1__block">
        <form action="#" class='js-form register-popup-form' id="popup-form">
          <p class="rp-content1__descr">Для того, чтобы ваши вакансии увидели все соискатели, а также , чтобы самостоятельно начать приглашать соискателей необходимо заполнить обязательные данные о вашей компании</p>
          <?//if($_GET['photos'] == ''):?>
            <div class="rp-content1__logo">
              <span class="rp-content1__logo-img">
                <img src="/theme/pic/register-popup-page/register_popup_r_logo.png" id="company-img">
              </span>
              <span class="rp-content1__logo-text">Добавление логотипа повысит узнаваемость бренда среди соискателей<span class="rp-content1__warning">Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</span></span>
              <input type="hidden" name="logo" id="HiLogo" class="required-inp"/>
            </div>
            <div class="rp-content1__btn-block" id="load-img-module">        
              <div class="rp-btn-block__load js-g-hashint" id="btn-load-image" title="Выбрать изображение"></div>
              <div class="rp-btn-block__webcam js-g-hashint" id="btn-get-snapshot" title="Сделать снимок"></div>
              <div class="clearfix"></div>
            </div>
          <?//endif;?>
         
          <div class="rp-content1__inputs">
            <input type="text" name="phone" value="<?=$attr[1]['phone']?>" id="phone-code" class="required-inp">
            <span class="rp-content1__inputs-city">
              <input type="text" name="city" value="<?=$city['city']?>" class="rp-content1__inputs-input required-inp" id="city-input" autocomplete="off" >
              <ul id="city-list"></ul>
            </span>
            <div class="clearfix"></div>
          </div>
         
          <div class="rp-content1__push">
            <span class="rp-content1__push-text">Включить PUSH уведомления</span>
            <div class="rp-content1__push-switch-block">
               <input id="push-checkbox" name="push" value="2" type="checkbox" checked="checked">
               <label for="push-checkbox" class="rp-content1__push-label">
                <div class="rp-content1__push-switch" data-checked="ДА" data-unchecked="НЕТ"></div>
               </label>
            </div>
            <span class="rp-content1__push-props" id="push-props">настроить</span>
          </div>
          <button class="rp-content1__button off" id="company-btn">сохранить и продолжить</button>
          
          <input name="all" value="0" type="hidden">
          <input name="rate" value="2" type="hidden">
          <input name="invite" value="2" type="hidden">
          <input name="mess" value="2" type="hidden">
          <input name="workday" value="2" type="hidden">
        </form>
      </div>
    </div>
    <div class="register-popup__content2">
      <div class="rp-content2__block">
        <h2 class="rp-content2__title">Теперь Вы можете найти временный персонал, который полностью отвечает Вашим требованиям</h2>
        <hr class="rp-content2__line">
        <p class="rp-content2__descr">Кроме того Вы получате возможность:</p>
        <div class="rp-c2__possibility-list">
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico1"></span>
            <span class="rp-c2-possibility__item-text">Публиковать вакансии</span>
          </div>
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico2"></span>
            <span class="rp-c2-possibility__item-text">Отбирать соискателей согласно отзывам о них и рейтингу</span>
          </div>
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico3"></span>
            <span class="rp-c2-possibility__item-text">Пользоваться допфункциями:
              <ul>
                <li>премиум-вакансия</li>
                <li>геолокация в мобильном приложении</li>
                <li>приглашение соискателей на вакансии</li>
                <li>виртуальный личный менеджер</li>
              </ul>
            </span>
          </div>
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico4"></span>
            <span class="rp-c2-possibility__item-text">Использовать мобильное приложение PROMMU для Android и IOS</span>
          </div>
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico5"></span>
            <span class="rp-c2-possibility__item-text">Использовать API cервис для интеграции анкет соискателей к себе на сайт</span>
          </div>
          <div class="clearfix"></div>
        </div>
        <p class="rp-content2__bottom">...и это не полный перечень возможностей нашего сервиса!</p>
      </div>
    </div>
  </div>
  <script type="text/javascript">
 
  </script>
  <?
  /*
  *
  *   push
  *
  */
  ?>
  <form method="post" id="" class="push-popup__form">
    <div class="pp-form__field">
      <span class="pp-form__checkbox-name">Отключить все оповещения?</span>
      <div class="pp-form__checkbox events">
         <input id="all" name="push-1" value="2" type="checkbox">
         <label for="all" class="pp-form__checkbox-label">
          <div class="pp-form__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
         </label>
      </div>
    </div>
    <div class="pp-form__all-props">
      <hr>
      <span class="pp-form__text">НАСТРОИТЬ ПУШ УВЕДОМЛЕНИЯ</span>
      <div class="pp-form__field">
        <span class="pp-form__checkbox-name">Отзыв/Рейтинг</span>
        <div class="pp-form__checkbox">
          <input id="rate" name="push-2" value="2" type="checkbox" checked>
          <label for="rate" class="pp-form__checkbox-label">
            <div class="pp-form__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
          </label>
        </div>
      </div>
      <div class="pp-form__field">
        <span class="pp-form__checkbox-name">Отклики на вакансию</span>
        <div class="pp-form__checkbox">
           <input id="invite" name="push-3" value="2" type="checkbox" checked>
           <label for="invite" class="pp-form__checkbox-label">
            <div class="pp-form__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
           </label>
        </div>
      </div>
      <div class="pp-form__field">
        <span class="pp-form__checkbox-name">Сообщения</span>
        <div class="pp-form__checkbox">
           <input id="mess" name="push-4" value="2" type="checkbox" checked>
           <label for="mess" class="pp-form__checkbox-label">
            <div class="pp-form__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
           </label>
        </div>
      </div>
      <div class="pp-form__field">
        <span class="pp-form__checkbox-name">День деактивации вакансии</span>
        <div class="pp-form__checkbox">
           <input id="workday" name="push-5" value="2" type="checkbox" checked>
           <label for="workday" class="pp-form__checkbox-label">
            <div class="pp-form__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
           </label>
        </div>
      </div>
    </div>
    <button class="pp-form__button" id="pash-save-btn">Сохранить</button>
  </form>
<?php endif; ?>
<?php require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/popup-load-img.php'; ?>