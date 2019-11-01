<?php if(empty($_GET['uid'])): ?>
<?php
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'phone-codes/style.css');
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-edit-prof-app.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/page-edit-prof-app.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'phone-codes/script.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'dist/jquery-ui.min.css'); 

  $attrAll = $viData['userInfo']['userAttribs'];
  $attr = array_values($attrAll)[0];
  // city
  //if(!sizeof($_GET['city']))
    $arUserCities = $viData['userInfo']['userCities'][0];

  $Q1 = Yii::app()->db->createCommand()
    ->select('t.id_city id, t.name, t.ismetro, t.id_co')
    ->from('city t')
    ->limit(10000);
  $arCities = $Q1->queryAll();
  $arTemp = array();
  //$_GET['city'] = urldecode($_GET['city']);
  //$_GET['city'] = urldecode($_GET['city']);
  foreach ($arCities as $city){
    /*if($_GET['city'] == $city['name']){
      $arUserCities[$city['id']] = $city;
      $res = Yii::app()->db->createCommand()
        ->update('user_city', array('id_city' => $city['id']), 
        'id_user=:id_user', array(':id_user' => Share::$UserProfile->exInfo->id));
        break;
    }*/
    $arTemp[$city['id']] = $city['name'];
  }
  // оптимизируем массив городов для JS
  $arCities = array_unique($arTemp);
  asort($arCities);
  //
  $Q1 = Yii::app()->db->createCommand()
    ->select('m.id, m.id_city, m.name')
    ->from('metro m')
    ->limit(10000);
  $arMetroes = $Q1->queryAll();
  // оптимизируем массив метро для JS
  $arTemp = array();
  foreach ($arMetroes as $m){
    $arTemp[$m['id_city']][$m['id']] = $m['name'];
  }

  $arMetroes = $arTemp;
  //  birthday
  /*if($_GET['birthday']){
    $birthday = explode(".", $_GET['birthday']);
    $bday = $birthday[0];
    $bmon = $birthday[1];
    $byear = $birthday[2];

    $dateBirth = "{$bday}-{$bmon}-{$byear}";
    $dates = date('Y-m-d', strtotime($dateBirth));
    $res = Yii::app()->db->createCommand()
      ->update('resume', array(
      'birthday' =>  $dates,
      'mdate' => date('Y-m-d H:i:s'),     
    ), 'id_user=:id_user', array(':id_user' => $attr['id_user']));

    $res = Yii::app()->db->createCommand()
      ->update('promo', array(
        'birthday' =>  $dates,
    ), 'user_id=:id', array(':id' => $attr['id_user']));
  }
  else{
    $bday = date("d", $date = strtotime($attr['bday']));
    $bmon = date("m", $date);
    $byear = date("Y", $date);
  }*/
  // posotions
  $arPosts = array();
  $strPosts = '';
  foreach($viData['posts'] as $val){
    $arPosts[$val['id']] = $val;
    $arPosts[$val['id']]['newname'] = ($val['key']=='custpo' ? ('- '.$val['val'].' -') : $val['val']);
    
    $arPosts[$val['id']]['checked'] = '';
    if($_GET['position'] == $val['id']){
      /*$res = Yii::app()->db->createCommand()
        ->update('user_mech', array(
                'crdate' => date('Y-m-d H:i:s'),
                'id_mech' => $val['id'],
                'isshow' => 0, 
            ), 'id_us=:id', array(':id' =>  $attr['id_user']));*/
      $arPosts[$val['id']]['checked'] = "checked";
    } 
    elseif($val['isshow1'] && !$_GET['npopup']) // если это не после модального окна, то проверяем
      $arPosts[$val['id']]['checked'] = "checked";

    if($arPosts[$val['id']]['checked']!=''){
      $strPosts .= ($strPosts==''? '' : ',') . $arPosts[$val['id']]['newname'];
    }
  }
  $arPayment = array();
  foreach ($viData['userInfo']['userDolj'][0] as $val) {
    if($_GET['npopup'] && $_GET['position']) {
      foreach ($arPosts as $k => $v) {
        if($v['checked'] === 'checked') {
          $arPayment[$_GET['position']]['pt'] = 0;
          $arPayment[$_GET['position']]['type'] = 'Час';
        }
      }
    }
    else {
      if($val['pay']>0)
        $arPayment[$val['idpost']]['pay'] = round($val['pay']);
      $arPayment[$val['idpost']]['pt'] = $val['pt'];
      switch ($val['pt']) {
        case 0: $arPayment[$val['idpost']]['type'] = 'Час'; break;
        case 1: $arPayment[$val['idpost']]['type'] = 'Неделя'; break;
        case 2: $arPayment[$val['idpost']]['type'] = 'Месяц'; break;
        case 3: $arPayment[$val['idpost']]['type'] = 'Посещение'; break;
      }
    }
  }
  // appearance
  $arAppear = array(11=>'hcolor',12=>'hlen',13=>'ycolor',14=>'chest',15=>'waist',16=>'thigh');
  $arAppearName = array(11=>'Цвет волос',12=>'Длина волос',13=>'Цвет глаз',14=>'Размер груди',15=>'Объем талии',16=>'Объем бедер');
  $arDays = array(1=>'ПН', 2=>'ВТ', 3=>'СР', 4=>'ЧВ', 5=>'ПТ', 6=>'СБ', 7=>'ВС');
  // Телефон
  if(!$_GET['phone'] && !$attr['phone-code']){ // закрыли попап не сохранив
    $city = (new Geo())->getUserGeo();   
    foreach($viData['countries'] as $c)
      if($c['id_co']==$city['country'])
        $attr['phone-code'] = $c['phone'];

  }
  else if($_GET['phone']){  // пошли через попап
    $attr['phone'] = urldecode($_GET['phone']);
    $attr['phone'] = urldecode($_GET['phone']);
    $attr['phone-code'] = $_GET['__phone_prefix'];
  }
  //  email
  $attr['email'] = filter_var($attr['email'], FILTER_VALIDATE_EMAIL);
  // messangers
  $arMess = array(); 
  $idViber = $this->ViewModel->isInArray($attrAll, 'key', 'viber');
  empty($attrAll[$idViber]['val']) ? : $arMess[] = 'Viber';
  $idWhatsApp = $this->ViewModel->isInArray($attrAll, 'key', 'whatsapp');
  empty($attrAll[$idWhatsApp]['val']) ? : $arMess[] = 'WhatsApp';
  $idTelegram = $this->ViewModel->isInArray($attrAll, 'key', 'telegram');
  empty($attrAll[$idTelegram]['val']) ? : $arMess[] = 'Telegram';
  $idGoogleAllo = $this->ViewModel->isInArray($attrAll, 'key', 'googleallo');
  empty($attrAll[$idGoogleAllo]['val']) ? : $arMess[] = 'Google Allo';
  // additional phones
  $arAdPhones = array();
  foreach($attrAll as $p)
    if(strpos($p['name'], 'admob')!==false && !empty($p['val']))
      $arAdPhones[] = $p;

//    echo '<pre>';
//    print_r($viData);
//    echo '</pre>';
?>
  <?php if( $viErrorData['err'] ): ?>
    <div class="err-msg-block">При сохранении данных профиля произошла ошибка. <?= $viErrorData['msg'] ?></div>
  <?php endif; ?>
  <script type="text/javascript">
    var arCities = <?=json_encode($arCities)?>,
        arMetroes = <?=json_encode($arMetroes)?>,
        selectPhoneCode = <?=json_encode($attr['phone-code'])?>;
  </script>
  <div class="edit-profile-applicant">
    <div class="epa__header">
      <h1 class="epa__header-title"><?=$attr['firstname'] . ' ' . $attr['lastname']?></h1>
    </div>
    <div class="epa__content">
      <div class="epa__content-logo">
        <div class="epa__logo-content">
          <img src="<?=Share::getPhoto($attr['id_user'],2,$attr['photo'],'medium',$attr['isman'])?>" alt="" id="epa-logo" class="epa__logo-img">
          <a href="<?=MainConfig::$PAGE_EDIT_PROFILE . '?ep=1'?>" class="epa__logo-edit">Изменить аватар</a>
        </div>
        <? $cntPhotos = count($viData['userInfo']['userPhotos']); ?>
        <? if( $cntPhotos < Share::$UserProfile->photosMax ): ?>
          <?
            $arYiiUpload = Share::$UserProfile->arYiiUpload;
            $difPhotos = Share::$UserProfile->photosMax - $cntPhotos;
            // если доступно к загрузке менее 5и фото
            $arYiiUpload['fileLimit']>$difPhotos && $arYiiUpload['fileLimit']=$difPhotos;
          ?>
          <div class="center">
            <? $this->widget('YiiUploadWidget',$arYiiUpload); ?>
          </div>
        <? endif; ?>
        <?php if(!$attr['confirmEmail'] && !empty($attr['email'])): ?>
          <div class="confirm-user email">Необходимо подтвердить почту</div>
        <?php endif; ?>
        <?php if(!$attr['confirmPhone'] && !empty($attr['phone'])): ?>
          <div class="confirm-user phone">Необходимо подтвердить телефон</div>
        <?php endif; ?>
        <ul class='epa__logo-name-list'>
          <li class="epa__logo-name">Основная информация</li>
          <li class="epa__logo-name">Контактная информация</li>
          <li class="epa__logo-name">Целевая вакансия</li>
          <li class="epa__logo-name">Место и время работы</li>
          <li class="epa__logo-name">Внешние данные</li>
          <li class="epa__logo-name">Доп. информация</li>
        </ul>
      </div>
      <?
      /*
      *   CONTENT
      */
      ?>
      <div class="epa__content-data">
        <form action='' method='post' id="epa-edit-form">
          <div class="epa__content-title"><h2>Основная информация</h2></div>
          <div class="epa__content-module" id="main-module">
            <label class="epa__label epa__firstname">
              <span class="epa__label-name">Имя:</span>
              <input type="text" name="name" value="<?=trim($attr['firstname'])?>" class="epa__input epa__required" data-name="Имя">
            </label>    
            <label class="epa__label epa__lastname">
              <span class="epa__label-name">Фамилия:</span>
              <input type="text" name="lastname" value="<?=trim($attr['lastname'])?>" class="epa__input epa__required" data-name="Фамилия">
            </label>
            <div class="epa__label epa__date epa__select <?=((($attr['bday']=='01.01.1970')||($attr['bday']==''))?' error':'')?>">
              <span class="epa__label-name">Дата рождения:</span>
              <input 
                type="text" 
                name="bdate" 
                id="birthday" 
                autocomplete="off" 
                value="<?=($attr['bday']=='01.01.1970'?'':$attr['bday'])?>"
                class="epa__input">
            </div>
            <?php $gender = (!empty($_GET['sex']) ? $_GET['sex'] : $attr['isman']) ?>
            <div class="epa__attr-block">
              <div class="epa__attr-block1">
                <input type="radio" name="sex" id="epa-male" class="epa__hidden" value="1" <?=($gender ? 'checked' : '')?>>
                <label class="epa__checkbox" for="epa-male">Мужчина</label>
                <input type="checkbox" name="hasmedbook" id="epa-med" class="epa__hidden" value="1" <?=($attr['ismed'] ? 'checked' : '')?>>
                <label class="epa__checkbox" for="epa-med">Медкнижка</label>
                <input type="checkbox" name="hasavto" id="epa-auto" class="epa__hidden" value="1" <?=($attr['ishasavto'] ? 'checked' : '')?>>
                <label class="epa__checkbox" for="epa-auto">Автомобиль</label>
                <input type="checkbox" name="smart" id="epa-smart" class="epa__hidden" value="1" <?=($attr['smart'] ? 'checked' : '')?>>
                <label class="epa__checkbox" for="epa-smart">Смартфон</label>
              </div>
              <div class="epa__attr-block2">
                <input type="radio" name="sex" id="epa-female" class="epa__hidden" value="0" <?=($gender ? '' : 'checked')?>>
                <label class="epa__checkbox epa__checkbox-famale" for="epa-female">Женщина</label>
                <input type="checkbox" name="promm" id="epa-pcard" class="epa__hidden" value="1" <?=($attr['cardPrommu'] ? 'checked' : '')?>>
                <label class="epa__checkbox" for="epa-pcard">Наличие банковской карты Prommu</label>
                <input type="checkbox" name="card" id="epa-card" class="epa__hidden" value="1" <?=($attr['card'] ? 'checked' : '')?>>
                <label class="epa__checkbox" for="epa-card">Наличие другой банковской карты</label>             
              </div>
              <div class="clearfix"></div>
            </div>
            <div class="center">
              <button type="submit" class="epa__save-btn prmu-btn prmu-btn_normal">
                <span>СОХРАНИТЬ ИЗМЕНЕНИЯ</span>
              </button>
            </div>
          </div>
          <?
          // CONTACTS 
          ?>
          <div class="epa__content-title"><h2>Контактная информация</h2></div>
          <div class="epa__content-module" id="contacts-module">
            <div class="epa__label">
              <span class="epa__label-name epa__phone-name">Телефон:</span>
                <input type='text' name='user-attribs[mob]' value="<?=$attr['phone']?>" class="epa__input epa__phone" id="phone-code">
                <span class="epa__add-phone-btn js-g-hashint" title="Добавить еще телефон">+</span>
                <span class="epa__confirm<?=($attr['confirmPhone']?' complete':'')?>" id="conf-phone">
                  <?php if(!$attr['confirmPhone']): ?>
                    <p>Телефон не подтвержден. <em>Подтвердить</em></p>
                  <?php else: ?>
                    <p>Телефон подтвержден.</p>
                  <?php endif; ?>
                </span>
            </div>
            <div class="epa__confirm-block" id="conf-phone-block">
              <span class="epa__confirm-text">На Ваш телефон выслан код для подтверждения. Введите его в это поле!</span>
              <label class="epa__label">
                <span class="epa__label-name">Код:</span>
                <input type='text' name='confirm-code' value="" class="epa__input" id="conf-phone-inp" maxlength="4">
              </label>
              <div class="epa__confirm-btn hvr-sweep-to-right btn__orange">ПРОВЕРИТЬ</div>
              <div class="clearfix"></div>
            </div>
            <?php 
              if(sizeof($arAdPhones) && !empty($attr['phone'])): 
                foreach ($arAdPhones as $phone):
            ?>        
              <label class="epa__label epa__add-phone">
                <span class="epa__label-name epa__phone-name">Доп. Телефон:</span>
                <input type="text" name="user-attribs[<?=$phone['name']?>]" value="<?=$phone['val']?>" class="epa__input epa__phone" autocomplete="off">
              </label>
            <?php 
                endforeach;
              endif; 
            ?>
            <div class="epa__label epa__email" data-error="Указанный e-mail адрес уже используется в системе">
              <span class="epa__label-name">Email:</span>
              <input type="text" name="email" value="<?=$attr['email']?>" class="epa__input epa__required" placeholder="your@email.com" id="epa-email" data-name="Электронная почта">
              <span class="epa__confirm<?=($attr['confirmEmail'] && !empty($attr['email'])?' complete':'')?>" id="conf-email">
                <?php if($attr['confirmEmail'] && !empty($attr['email'])): ?>
                  <p>Почта подтверждена.</p>
                <?php else: ?>
                  <p>Почта не подтверждена. <em>Подтвердить</em></p>
                <?php endif; ?>
              </span>
            </div>
            <div class="epa__confirm-block" id="conf-email-block">
              <span class="epa__confirm-text">На Вашу почту выслан код для подтверждения. Введите его в это поле!</span>
              <label class="epa__label">
                <span class="epa__label-name">Код:</span>
                <input type='text' name='confirm-code' value="" class="epa__input" id="conf-email-inp" maxlength="4">
              </label>
              <div class="epa__confirm-btn hvr-sweep-to-right btn__orange">ПРОВЕРИТЬ</div>
              <div class="clearfix"></div>
            </div>

            <span class="epa__label-name">Мессенджеры:</span>
<!--              skype-->
            <label class="epa__label epa__skype">
              <span class="epa__label-name">Skype:</span>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'skype') ?>
              <input type="text" name="user-attribs[skype]" value="<?=($id ? $attrAll[$id]['val'] : '')?>" class="epa__input">
            </label>

<!-- Other messengers-->
            <label class="epa__label epa__mess-viber <?=empty($attrAll[$idViber]['val'])?'off':''?>">
              <span class="epa__label-name epa__phone-name">Viber:</span>
              <input type="text" name="user-attribs[viber]" value="<?=$attrAll[$idViber]['val']?>" class="epa__input epa__phone phone-input" autocomplete="off">
              <ul class="phone-list"></ul>
            </label>
            <label class="epa__label epa__mess-wapp <?=empty($attrAll[$idWhatsApp]['val'])?'off':''?>">
              <span class="epa__label-name epa__phone-name">WhatsApp:</span>
              <input type="text" name="user-attribs[whatsapp]" value="<?=$attrAll[$idWhatsApp]['val']?>" class="epa__input epa__phone phone-input" autocomplete="off">
              <ul class="phone-list"></ul>
            </label>
            <label class="epa__label epa__mess-tele <?=empty($attrAll[$idTelegram]['val'])?'off':''?>">
              <span class="epa__label-name epa__phone-name">Telegram:</span>
              <input type="text" name="user-attribs[telegram]" value="<?=$attrAll[$idTelegram]['val']?>" class="epa__input epa__phone phone-input" autocomplete="off">
              <ul class="phone-list"></ul>
            </label>
            <label class="epa__label epa__mess-allo <?=empty($attrAll[$idGoogleAllo]['val'])?'off':''?>">
              <span class="epa__label-name epa__phone-name">Google Allo:</span>
              <input type="text" name="user-attribs[googleallo]" value="<?=$attrAll[$idGoogleAllo]['val']?>" class="epa__input epa__phone phone-input" autocomplete="off">
              <ul class="phone-list"></ul>
            </label>

<!-- Social netvorks-->
            <span class="epa__label-name">Социальные сети:</span>
            <label class="epa__label epa__soc-vk">
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'vk') ?>
              <span class="epa__label-name">Страница ВКонтакте (сылка):</span>
              <input type="text" name="user-attribs[vk]" value="<?=($id ? $attrAll[$id]['val'] : '')?>" class="epa__input" placeholder="vk.com/">
            </label>
            <label class="epa__label epa__soc-fb">
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'fb') ?>
              <span class="epa__label-name">Страница Facebook (ссылка):</span>
              <input type="text" name="user-attribs[fb]" value="<?=($id ? $attrAll[$id]['val'] : '')?>" class="epa__input" placeholder="fb.com/">
            </label>
            <label class="epa__label epa__soc-ok">
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'ok') ?>
              <span class="epa__label-name">Страница Одноклассники (сылка):</span>
              <input type="text" name="user-attribs[ok]" value="<?=($id ? $attrAll[$id]['val'] : '')?>" class="epa__input" placeholder="ok.ru/">
            </label>
            <label class="epa__label epa__soc-mail">
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'mail') ?>
              <span class="epa__label-name">Страница Mail.ru (почта):</span>
              <input type="text" name="user-attribs[mail]" value="<?=($id ? $attrAll[$id]['val'] : '')?>" class="epa__input" placeholder="your@mail.com" id="epa-mail">
            </label>
            <label class="epa__label epa__soc-google">
              <span class="epa__label-name">Страница Google+ (почта):</span>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'google') ?>  
              <input type="text" name="user-attribs[google]" value="<?=($id ? $attrAll[$id]['val'] : '')?>" class="epa__input" placeholder="your@gmail.com" id="epa-gmail">
            </label>
            <?php
            /**
             * shut down Messenger list
             * 4.06.2019
             */
            if(!empty($attr['phone'])&&(1==2)): ?>
              <div class="epa__label epa__messenger epa__select">
                <span class="epa__label-name">Мессенджеры:</span>
                <input type="text" name="epa-str-mess" value="<?=implode(',',$arMess)?>" class="epa__input" id="epa-str-messenger" disabled>
                <div class="epa__label-veil" id="epa-veil-messenger"></div>
                <ul class="epa__select-list" id="epa-list-messenger">
                    <i class="epa__select-list-icon">OK</i>
                    <li>
                        <input type="checkbox" name="epa-mess-viber" value="1" id="epa-mess-viber" data-mess="viber" <?=empty($attrAll[$idViber]['val'])?'':'checked'?>>
                        <label for="epa-mess-viber">Viber<b></b></label>
                    </li>
                    <li>
                        <input type="checkbox" name="epa-mess-wapp" value="1" id="epa-mess-wapp" data-mess="wapp" <?=empty($attrAll[$idWhatsApp]['val'])?'':'checked'?>>
                        <label for="epa-mess-wapp">WhatsApp<b></b></label>
                    </li>
                    <li>
                        <input type="checkbox" name="epa-mess-tele" value="1" id="epa-mess-tele" data-mess="tele" <?=empty($attrAll[$idTelegram]['val'])?'':'checked'?>>
                        <label for="epa-mess-tele">Telegram<b></b></label>
                    </li>
                    <li>
                        <input type="checkbox" name="epa-mess-allo" value="1" id="epa-mess-allo" data-mess="allo" <?=empty($attrAll[$idGoogleAllo]['val'])?'':'checked'?>>
                        <label for="epa-mess-allo">Google Allo<b></b></label>
                    </li>
                </ul>
                <div class="epa__mess-hint <?=(sizeof($arMess)?'':'off')?>" style="display:none">Этот номер используете? Если нет - впишите пожалуйста нужный!</div>
              </div>
              <label class="epa__label epa__mess-viber <?=empty($attrAll[$idViber]['val'])?'off':''?>">
                <span class="epa__label-name epa__phone-name">Viber:</span>
                <input type="text" name="user-attribs[viber]" value="<?=$attrAll[$idViber]['val']?>" class="epa__input epa__phone phone-input" autocomplete="off">
                <ul class="phone-list"></ul>
              </label>
              <label class="epa__label epa__mess-wapp <?=empty($attrAll[$idWhatsApp]['val'])?'off':''?>">
                <span class="epa__label-name epa__phone-name">WhatsApp:</span>
                <input type="text" name="user-attribs[whatsapp]" value="<?=$attrAll[$idWhatsApp]['val']?>" class="epa__input epa__phone phone-input" autocomplete="off">
                <ul class="phone-list"></ul>
              </label>
              <label class="epa__label epa__mess-tele <?=empty($attrAll[$idTelegram]['val'])?'off':''?>">
                <span class="epa__label-name epa__phone-name">Telegram:</span>
                <input type="text" name="user-attribs[telegram]" value="<?=$attrAll[$idTelegram]['val']?>" class="epa__input epa__phone phone-input" autocomplete="off">
                <ul class="phone-list"></ul>
              </label>
              <label class="epa__label epa__mess-allo <?=empty($attrAll[$idGoogleAllo]['val'])?'off':''?>">
                <span class="epa__label-name epa__phone-name">Google Allo:</span>
                <input type="text" name="user-attribs[googleallo]" value="<?=$attrAll[$idGoogleAllo]['val']?>" class="epa__input epa__phone phone-input" autocomplete="off">
                <ul class="phone-list"></ul>
              </label>
            <?php endif; ?>
            <label class="epa__label epa__soc-other">
              <span class="epa__label-name">Другое:</span>
              <input type="text" name="user-attribs[custcont]" value="<?=$attrAll[39]['val']?>" class="epa__input">
            </label>
            <div class="center">
              <button type="submit" class="epa__save-btn prmu-btn prmu-btn_normal">
                <span>СОХРАНИТЬ ИЗМЕНЕНИЯ</span>
              </button>
            </div>
          </div>
          <?
          // VACANCIES 
          ?>
          <div class="epa__content-title"><h2>Целевая вакансия</h2></div>
          <div class="epa__content-module">
            <h3 class="epa__posts-title">Выберите должности, на которых желаете работать</h3>
            <?php
//                echo '<div class="epa__posts-list">';
//
//                foreach($arPosts as $post)
//                  if($post['checked']!='')
//                    echo '<b> ' . $post['newname'] . '</b>';
//
//                echo '</div>';
            ?>
            <div class="epa__label epa__posts epa__select">
              <span class="epa__label-name">Должность:</span>

              <ul id="epa-list-posts" class="epa__select-list epa__select-lst-vsbl" >
                <?php foreach($arPosts as $post):?>
                  <li>
                    <input type="checkbox" name="donjnost[]" value="<?=$post['id']?>" id="epa-post-<?=$post['id']?>" <?=$post['checked']?>>
                    <label for="epa-post-<?=$post['id']?>"><?=$post['newname']?><b></b></label>
                  </li>
                <?php endforeach;?>
              </ul>



                <?php
                /*
                ?>
              <input type="text" name="epa-str-posts" value="<?=$strPosts?>" class="epa__input" id="epa-str-posts" disabled>
              <div class="epa__label-veil" id="epa-veil-posts"></div>
                */
                ?>
              <?php
                  /*
                  <ul class="epa__select-list" id="epa-list-posts">
                      <i class="epa__select-list-icon">OK</i>
                  */
              ?>
                  <?php foreach($arPosts as $post):?>
                  <?php
                  /*
                    <li>
                      <input type="checkbox" name="donjnost[]" value="<?=$post['id']?>" id="epa-post-<?=$post['id']?>" <?=$post['checked']?>>
                      <label for="epa-post-<?=$post['id']?>"><?=$post['newname']?><b></b></label>
                    </li>
                  */
                    ?>
                  <?php endforeach;?>
                  <?/*<li class="epa__posts-new" id="epa-posts-add"><span>Другая вакансия</span></li>
                  <li class="epa__posts-new" id="epa-posts-save">
                    <input type="text" name="epa-new-vac" id="epa-posts-field">
                    <span>Сохранить</span>
                  </li>*/?>
              <?php
                  //</ul>
              ?>
            </div>
            <div class="epa__post-detail">
              <?php foreach($arPosts as $post): ?>
                <?php if($post['checked']!=''): ?>
                  <div class="epa__post-block" data-id="<?=$post['id']?>">
                    <div class="epa__post-name"><?=$post['newname']?></div>
                    <div class="epa__post-close"></div>
                    <label class="epa__label epa__payment">
                      <span class="epa__label-name">Ожидаемая оплата:</span>
                      <input type="text" name="post[<?=$post['id']?>][payment]" value="<?=isset($arPayment[$post['id']]['pay']) ? $arPayment[$post['id']]['pay'] : ''?>" class="epa__input epa__required" data-name="Ожидаемая оплата">
                      <em>руб</em>
                    </label>
                    <label class="epa__label epa__select">
                      <input type="text" name="epa-str-period" value="<?=$arPayment[$post['id']]['type']?>" class="epa__input epa__post-period" disabled>
                      <div class="epa__label-veil epa__post-veil"></div>
                      <ul class="epa__select-list epa__post-list">
                          <i class="epa__select-list-icon epa__post-btn">OK</i>
                          <li>
                            <input type="radio" name="post[<?=$post['id']?>][hwm]" value="0" <?=$arPayment[$post['id']]['pt']==0 ? 'checked' : ''?>>
                            <label>Час</label>
                          </li>
                          <li>
                            <input type="radio" name="post[<?=$post['id']?>][hwm]" value="1" <?=$arPayment[$post['id']]['pt']==1 ? 'checked' : ''?>>
                            <label>Неделю</label>
                          </li>
                          <li>
                            <input type="radio" name="post[<?=$post['id']?>][hwm]" value="2" <?=$arPayment[$post['id']]['pt']==2 ? 'checked' : ''?>>
                            <label>Месяц</label>
                          </li>
                          <li>
                            <input type="radio" name="post[<?=$post['id']?>][hwm]" value="3" <?=$arPayment[$post['id']]['pt']==3 ? 'checked' : ''?>>
                            <label>Посещение</label>
                          </li>
                      </ul>
                    </label>
                    <?php 
                      $arRes = array();
                      $name = 'без опыта';
                      $checked = false;
                      foreach($viData['expir'] as $val){
                        $arRes[$val['id']] = $val;
                        $key = $this->ViewModel->isInArray($viData['userInfo']['userDolj'][0], 'id_attr', $val['id']);
                        if($key>0 && $viData['userInfo']['userDolj'][0][$key]['idpost']==$post['id']){
                          $arRes[$val['id']]['checked'] = 'checked';
                          $name = $val['name'];
                          $checked = true;
                        }                        
                      }
                      if(!$checked)
                        $arRes[32]['checked'] = 'checked';
                    ?>           
                    <label class="epa__label epa__select epa__post-experience">
                      <span class="epa__label-name">Опыт работы:</span>
                      <input type="text" name="epa-str-period" value="<?=$name?>" class="epa__input epa__post-period" disabled>
                      <div class="epa__label-veil epa__post-veil"></div>
                      <ul class="epa__select-list epa__post-list">
                          <i class="epa__select-list-icon epa__post-btn">OK</i>
                          <?php foreach($arRes as $val): ?>
                            <li>
                              <input type="radio" name="exp[<?=$post['id']?>][level]" value="<?=$val['id']?>" <?=$val['checked']?>>
                              <label><?=$val['name']?><b></b></label>
                            </li>
                          <?php endforeach; ?>
                      </ul>
                    </label>
                  </div>
                <?php endif; ?>
              <?php endforeach;?>
              <div class="clearfix"></div> 
            </div>
            <div class="center">
              <button type="submit" class="epa__save-btn prmu-btn prmu-btn_normal">
                <span>СОХРАНИТЬ ИЗМЕНЕНИЯ</span>
              </button>
            </div>
          </div>
          <?
          // LOCATION 
          ?>
          <div class="epa__content-title"><h2>Удобное место и время работы</h2></div>
          <div class="epa__content-module" id="city-module">
            <h3 class="epa__cities-title">Опубликованные города претендента</h3>
            <div class="epa__cities-list">
              <div>
                <?php foreach($arUserCities as $city): ?> 
                  <b><?=$city['name']?></b>
                <?php endforeach; ?>               
              </div>
              <?php
                /**
                * shut down button 'Add City'
                * 04.06.2018
                */
//                echo '<span class="epa__add-city-btn epa__city-btn js-g-hashint" title="Добавить город" >+</span>';
              ?>
            </div>
            <div class="epa__cities-block-list">
              <?php foreach($arUserCities as $city): ?>
                <div class="epa__city-item" data-idcity="<?=$city['id']?>">
                  <div class="epa__city-title">
                    <b>ГОРОД </b>
                    <span class="epa__city-del"></span>
                  </div>
                  <div class="epa__label epa__select epa__city">
                    <span class="epa__label-name">Город:</span>
                    <span class="epa__city-err">Такой город уже выбран</span>
                    <input type="text" name="cityname[]" value="<?=$city['name']?>" class="epa__input city-input" autocomplete="off">
                    <ul class="city-list"></ul>
                  </div>
                  <?php if($city['ismetro']):?>
                    <div class="epa__label epa__select epa__metro">
                      <span class="epa__label-name">Метро:</span>
                      <input type="text" name="str-metro-name" value="" class="epa__input metro-input">
                      <ul class="metro-list"></ul>
                    </div>
                    <div class="epa__metro-list">
                      <?php 
                        if(sizeof($viData['userInfo']['userMetro']))
                          foreach($viData['userInfo']['userMetro'][0] as $idMetro => $metro)
                            if($metro['idcity']==$city['id']):?>
                              <div class="epa__label epa__metro-item">
                                <span class="epa__metro-close"></span>
                                <span class="epa__label-name">Метро:</span>
                                <input type="text" name="metro-str" value="<?=$metro['name']?>" class="epa__input metro-input" disabled>
                                <input type="hidden" name="metro[]" value="<?=$idMetro?>">
                              </div>
                      <?php endif; ?>
                    </div>
                    <div class="clearfix"></div>
                  <? endif; ?>
                  <h3 class="epa__cities-title">Удобное время работы:</h3>
                  <div class="epa__days-checkboxes">
                    <?php foreach($arDays as $idDay => $name): ?>
                      <div class="epa__day">
                        <input type="checkbox" name="days[]" value="<?=$idDay?>" class="epa__day-input" data-day="<?=$name?>" <?=array_key_exists($idDay, $viData['userInfo']['userWdays'][$city['id']]) ? 'checked' : ''?>>
                        <label class="epa__checkbox"><?=$name?></label>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <div class="epa__period-list">
                    <?php
                      if(sizeof($viData['userInfo']['userWdays'][$city['id']]))
                        foreach($viData['userInfo']['userWdays'][$city['id']] as $idDay => $t):?>
                          <? $value = 'С ' . explode(':', $t['timeb'])[0] . ' до ' . explode(':', $t['timee'])[0]?>
                          <div class="epa__label epa__period " data-id="<?=$idDay?>">
                            <span class="epa__period-close"></span>
                            <div class="epa__period-error"><span>С</span><b></b><span>до</span><b></b></div>
                            <span class="epa__label-name"><i><?=$arDays[$idDay]?></i>, Время дня:</span>
                            <input type="text" name="time[<?=$city['id']?>][<?=$idDay?>]" class="epa__input epa__required" value="<?=$value?>" data-name="Временной период" autocomplete="off">
                          </div>
                    <?php endforeach; ?>
                  </div>
                  <div class="clearfix"></div>
                </div>
              <?php endforeach; ?>
            </div>
            <?php
            /**
             * shut down button 'Add City'
             * 04.06.2018
             */
            //<span class="epa__btn epa__add-city-btn">Добавить город</span>
            ?>
            <div class="clearfix"></div>
            <div class="center">
              <button type="submit" class="epa__save-btn prmu-btn prmu-btn_normal">
                <span>СОХРАНИТЬ ИЗМЕНЕНИЯ</span>
              </button>
            </div>
          </div>
          <?
          // APPEARANCE
          ?>
          <div class="epa__content-title"><h2>Внешние данные</h2></div>
          <div class="epa__content-module">
            <label class="epa__label epa__app-manh">
              <span class="epa__label-name">Рост:</span>
              <input type="text" name="user-attribs[manh]" value="<?=$attrAll[9]['val']?>" class="epa__input" id="epa-height">
            </label>
            <label class="epa__label epa__app-weig">
              <span class="epa__label-name">Вес:</span>
              <input type="text" name="user-attribs[weig]" value="<?=$attrAll[10]['val']?>" class="epa__input" id="epa-weight">
            </label>
            <?php
              foreach($arAppear as $appId => $app):?>
                <div class="epa__label epa__app-<?=$app?> epa__select">
                  <span class="epa__label-name"><?=$arAppearName[$appId]?>:</span>
                  <?php 
                    $arRes = array(); 
                    $name = '';
                    foreach($viData['userDictionaryAttrs'] as $val)
                      if($val['idpar'] == $appId){
                        $arRes[$val['id']] = $val;
                        if($this->ViewModel->isInArray($attrAll, 'id_attr', $val['id'])){
                          $arRes[$val['id']]['select'] = 'checked';
                          $name = $val['name'];
                        }
                      }
                  ?>
                  <input type="text" name="epa-str-<?=$app?>" value="<?=$name?>" class="epa__input" id="epa-str-<?=$app?>" disabled>
                  <div class="epa__label-veil" id="epa-veil-<?=$app?>"></div>
                  <ul class="epa__select-list" id="epa-list-<?=$app?>">
                    <i class="epa__select-list-icon">OK</i>
                    <?php foreach($arRes as $id => $val): ?>
                        <li>
                            <input type="radio" name="user-attribs[<?=$app?>]" value="<?=$id?>" id="epa-<?=$app?>-<?=$id?>" <?=$val['select']?>>
                            <label for="epa-<?=$app?>-<?=$id?>"><?=$val['name']?><b></b></label>
                        </li>
                    <?php endforeach; ?>
                  </ul>
                </div>
            <?php endforeach; ?>
            <div class="center">
              <button type="submit" class="epa__save-btn prmu-btn prmu-btn_normal">
                <span>СОХРАНИТЬ ИЗМЕНЕНИЯ</span>
              </button>
            </div>
          </div>
          <?
          //  ADDITIONAL
          ?>
          <div class="epa__content-title"><h2>Дополнительная информация</h2></div>
          <div class="epa__content-module">
            <div class="epa__label epa__education epa__select">
              <span class="epa__label-name">Образование:</span>
              <?php 
                $arRes = array(); 
                $name = '';
                foreach($viData['userDictionaryAttrs'] as $val)
                  if($val['idpar'] == 69){
                    $arRes[$val['id']] = $val;
                    if($this->ViewModel->isInArray($attrAll, 'id_attr', $val['id'])){
                      $arRes[$val['id']]['checked'] = 'checked';
                      $name .= ($name=='' ? '' : ',') . $val['name'];
                    }
                  }
              ?>
              <input type="text" name="epa-str-education" value="<?=$name?>" class="epa__input" id="epa-str-education" disabled>
              <div class="epa__label-veil" id="epa-veil-education"></div>
              <ul class="epa__select-list" id="epa-list-education">
                  <i class="epa__select-list-icon">OK</i>
                  <?php foreach($arRes as $edu): ?>
                    <li>
                      <input type="radio" name="user-attribs[edu]" value="<?=$edu['id']?>" id="epa-education-<?=$edu['id']?>" data-name="Образование" <?=$edu['checked']?>>
                      <label for="epa-education-<?=$edu['id']?>"><?=$edu['name']?><b></b></label>
                    </li> 
                  <?php endforeach; ?>
              </ul>
            </div>
            <div class="epa__label epa__language epa__select">
              <span class="epa__label-name">Иностранные языки:</span>
              <?php 
                $arRes = array(); 
                $name = '';
                foreach($viData['langs'] as $val){
                  $arRes[$val['id']] = $val;
                  if($this->ViewModel->isInArray($attrAll, 'id_attr', $val['id'])){
                    $arRes[$val['id']]['checked'] = 'checked';
                    $name .= ($name=='' ? '' : ',') . $val['name'];
                  }
                }
              ?>
              <input type="text" name="epa-str-language" value="<?=$name?>" class="epa__input" id="epa-str-language" disabled>
              <div class="epa__label-veil" id="epa-veil-language"></div>
              <ul class="epa__select-list" id="epa-list-language">
                  <i class="epa__select-list-icon">OK</i>
                  <?php foreach($arRes as $lang): ?>
                    <li>
                      <input type="checkbox" name="langs[]" value="<?=$lang['id']?>" id="epa-language-<?=$lang['id']?>" data-name="Иностранные языки" <?=$lang['checked']?>>
                      <label for="epa-language-<?=$lang['id']?>"><?=$lang['name']?><b></b></label>
                    </li> 
                  <?php endforeach; ?>
              </ul>
            </div>
            <label class="epa__label epa__about">
              <span class="epa__label-name">О себе: <span style="display: none">(текст до 2000 символов)</span></span>
              <textarea name="about-mself" class="epa__textarea epa__required" placeholder="Укажите навыки и знания, которые помогут вам справиться с желаемой работой или дополнительную информацию, которая поможет работодателю лучше узнать Вас" data-name="О себе"><?=$attr['aboutme']?></textarea>
            </label>
            <?
              $isNews = false;
              foreach ($attrAll as $v)
                $v['key']=='isnews' && $isNews = $v['val'];
            ?>
            <div class="epa__attr-block">
                <input type="checkbox" name="user-attribs[isnews]" id="epa-isnews" class="epa__hidden" value="1" <?=$isNews ? 'checked' : ''?>>
                <label class="epa__checkbox epa__checkbox-news" for="epa-isnews">Получать новости об изменениях и новых возможностях на сайте</label>
            </div>

            <div class="epa__req-list">Необходимо заполнить: <div></div></div>
            <div class="center">
              <button type="submit" class="epa__save-btn prmu-btn prmu-btn_normal">
                <span>СОХРАНИТЬ ИЗМЕНЕНИЯ</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?
  /*
  *
  */
  ?>
  <div id="epa-post-single">
    <div class="epa__post-block" data-id="NEWID">
      <div class="epa__post-name">NEWNAME</div>
      <div class="epa__post-close"></div>
      <label class="epa__label epa__payment">
        <span class="epa__label-name">Ожидаемая оплата: </span>
        <input type="text" name="post[NEWID][payment]" value="" class="epa__input epa__required " data-name="Ожидаемая оплата" autocomplete="off">
        <em>руб</em>
      </label>
      <label class="epa__label epa__select">
        <input type="text" name="epa-str-period" value="Час" class="epa__input epa__post-period" disabled>
        <div class="epa__label-veil epa__post-veil"></div>
        <ul class="epa__select-list epa__post-list">
            <i class="epa__select-list-icon epa__post-btn">OK</i>
            <li>
              <input type="radio" name="post[NEWID][hwm]" value="0" checked>
              <label>Час</label>
            </li>
            <li>
              <input type="radio" name="post[NEWID][hwm]" value="1">
              <label>Неделю</label>
            </li>
            <li>
              <input type="radio" name="post[NEWID][hwm]" value="2">
              <label>Месяц</label>
            </li>
            <li>
              <input type="radio" name="post[NEWID][hwm]" value="3">
              <label>Посещение</label>
            </li>
        </ul>
      </label>
      <label class="epa__label epa__select epa__post-experience">
        <span class="epa__label-name">Опыт работы:</span>
        <input type="text" name="epa-str-period" value="без опыта" class="epa__input epa__post-period" disabled>
        <div class="epa__label-veil epa__post-veil"></div>
        <ul class="epa__select-list epa__post-list">
            <i class="epa__select-list-icon epa__post-btn">OK</i>
            <?php foreach($viData['expir'] as $val): ?>
              <li>
                <input type="radio" name="exp[NEWID][level]" value="<?=$val['id']?>" <?=($val['id']==32?'checked':'')?>>
                <label><?=$val['name']?><b></b></label>
              </li>
            <?php endforeach; ?>
        </ul>
      </label>
    </div>
  </div>
  <?//  *****************  //?>
  <div id="add-city-content">
    <div class="epa__city-item" data-idcity="NEWID">
      <div class="epa__city-title">
        <b>ГОРОД </b>
        <span class="epa__city-del"></span>
      </div>
      <div class="epa__label epa__select epa__city">
        <span class="epa__label-name">Город:</span>
        <span class="epa__city-err">Такой город уже выбран</span>
        <input type="text" name="cityname[]" value="" class="epa__input city-input" autocomplete="off">
        <ul class="city-list"></ul>
      </div>
      <h3 class="epa__cities-title">Удобное время работы:</h3>
      <div class="epa__days-checkboxes">
        <div class="epa__day">
          <input type="checkbox" name="days[NEWID]" value="1" class="epa__day-input" data-day="ПН">
          <label class="epa__checkbox">ПН</label>
        </div>
        <div class="epa__day">
          <input type="checkbox" name="days[NEWID]" value="2" class="epa__day-input" data-day="ВТ">
          <label class="epa__checkbox">ВТ</label>
        </div>
        <div class="epa__day">
          <input type="checkbox" name="days[NEWID]" value="3" class="epa__day-input" data-day="СР">
          <label class="epa__checkbox">СР</label>
        </div>
        <div class="epa__day">
          <input type="checkbox" name="days[NEWID]" value="4" class="epa__day-input" data-day="ЧВ">
          <label class="epa__checkbox">ЧВ</label>
        </div>
        <div class="epa__day">
          <input type="checkbox" name="days[NEWID]" value="5" class="epa__day-input" data-day="ПТ">
          <label class="epa__checkbox">ПТ</label>
        </div>
        <div class="epa__day">
          <input type="checkbox" name="days[NEWID]" value="6" class="epa__day-input" data-day="СБ">
          <label class="epa__checkbox">СБ</label>
        </div>
        <div class="epa__day">
          <input type="checkbox" name="days[NEWID]" value="7" class="epa__day-input" data-day="ВС">
          <label class="epa__checkbox">ВС</label>
        </div>
      </div>
      <div class="epa__period-list"></div>
      <div class="clearfix"></div>
    </div> 
  </div>
  <?//  *****************  //?>
  <div id="add-day-period">
    <div class="epa__label epa__period" data-id="NEWDAY">
      <span class="epa__period-close"></span>
      <div class="epa__period-error"><span>С</span><b></b><span>до</span><b></b></div>
      <span class="epa__label-name"><i></i>, Время дня:</span>
      <input type="text" name="time[NEWID][NEWDAY]" class="epa__input epa__required" c>
    </div>
  </div>
  <?//  *****************  //?>
  <div id="add-metro-content">
    <div class="epa__label epa__select epa__metro">
      <span class="epa__label-name">Метро:</span>
      <input type="text" name="str-metro-name" value="" class="epa__input metro-input">
      <ul class="metro-list"></ul>
    </div>
    <div class="epa__metro-list"></div>
    <div class="clearfix"></div>
  </div>
  <?//  *****************  //?>
  <div id="add-metro-item">
    <div class="epa__label epa__metro-item">
      <span class="epa__metro-close"></span>
      <span class="epa__label-name">Метро:</span>
      <input type="text" name="metro-str" value="NAMEMETRO" class="epa__input metro-input" disabled>
      <input type="hidden" name="metro[]" value="IDMETRO">
    </div>
  </div>
  <div id="add-additional-phone">
    <label class="epa__label epa__add-phone">
      <span class="epa__label-name epa__phone-name">Доп. Телефон:</span>
      <input type="text" name="user-attribs[admobNEWNUM]" value="" class="epa__input epa__phone" autocomplete="off">
    </label>
  </div>
  <?//  *****************  //?>
  <div id="error_messege" class="tmpl">
    <div class="prmu__popup">Для того что бы Ваша анкета была доступна для просмотра всем работодателям и Вы могли откликаться на понравившиеся Вам вакансии, необходимо заполнить все обязательные поля, они выделены красной рамкой.<br>Спасибо за понимание</div>
  </div>
<?php endif; ?>
<? 
/*
*       POPUP
*/
?>
<?php if(!empty($_GET['uid'])): ?>
  <?
    Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'register-popup/register-popup-app.js', CClientScript::POS_END);
    Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'phone-codes/style.css'); 
    Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'phone-codes/script.js', CClientScript::POS_END);
    Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'register-popup/register-popup-app.css');
    Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'dist/jquery-ui.min.css'); 

    //$arGeo = (new Geo())->getUserGeo();
    $arGeo = array('country'=>1);
    $attr = reset($viData['userInfo']['userAttribs']);

    foreach($viData['countries'] as $v)
    {
      if(!empty($attr['val']) && $v['phone']==$attr['phone-code'])
      { // регистрация через телефон
        $arGeo['country'] = $v['id_co'];
        break;
      }
      else if(filter_var($attr['email'], FILTER_VALIDATE_EMAIL) && $v['id_co']==$arGeo['country'])
      { // регистрация через почту
        $attr['phone-code'] = $v['phone'];
        break;
      }
    }

    if(empty($arGeo['city']) || empty($arGeo['country']))
    {
      $cityId = Subdomain::getCacheData()->id;
      $sql = Yii::app()->db->createCommand()
              ->select("id_city, name, id_co")
              ->from('city')
              ->where('id_city=:id', [':id' => $cityId])
              ->queryRow();

      $arGeo['country'] = $sql['id_co'];
      $arGeo['city'] = $sql['name']; 
      $arGeo['id_city'] = $sql['id_city']; 
    }
  ?>
  <script type="text/javascript">
    var selectPhoneCode = <?=json_encode($attr['phone-code'])?>;
    var country = <?=json_encode($arGeo['country'])?>;
    var arCountries = <?=json_encode($viData['countries'])?>;
    var arPosts = <?=json_encode($viData['posts'])?>;
  </script>
  <div class="register-popup">
    <div class="register-popup__veil"></div>
    <div class="register-popup__header">
      <h1 class="rp-header__title">ПОЗДРАВЛЯЕМ ВАС С УСПЕШНОЙ РЕГИСТРАЦИЕЙ</h1> 
      <a class="rp-header__close-btn" href="javascript:void(0)">&#10006</a>
    </div>
    <div class="register-popup__content1">
      <div class="rp-content1__block">
        <form class='js-form register-popup-form' id="popup-form">
          <p class="rp-content1__descr">Для того, чтобы Вашу анкету увидели все работодатели, чтобы начать искать работу и откликаться на вакансии необходимо заполнить обязательные данные о себе</p>
          <div class="rp-content1__logo">
            <span class="rp-content1__logo-img">
              <? 
                $src = Share::getPhoto($attr['id_user'],2,$attr['photo'],'small',$attr['isman']);
                if(strrpos($src,'logo_applicant')!==false)
                {
                  $src = '/theme/pic/register-popup-page/register_popup_r_logo.png';
                }
              ?>
              <img src="<?=Share::getPhoto($attr['id_user'],2,$attr['photo'],'small',$attr['isman'])?>" id="applicant-img">
            </span>
            <span class="rp-content1__text">Добавление Вашей фотографии повысит привлекательность анкеты и увеличит шансы что работодатель выберет именно Вас<span class="rp-content1__warning">Добавляйте только свои личные фото, иначе Вы не сможете пройти модерацию! Спасибо за понимание!</span></span>
          </div>
          <? $cntPhotos = count($viData['userInfo']['userPhotos']); ?>
          <? if( $cntPhotos < Share::$UserProfile->photosMax ): ?>
            <?
              $arYiiUpload = Share::$UserProfile->arYiiUpload;
              $difPhotos = Share::$UserProfile->photosMax - $cntPhotos;
              // если доступно к загрузке менее 5и фото
              $arYiiUpload['fileLimit']>$difPhotos && $arYiiUpload['fileLimit']=$difPhotos;
              $arYiiUpload['cssClassButton']='rp-content1__upload';
            ?>
            <div class="rp-content1__btn-block center">
              <? $this->widget('YiiUploadWidget',$arYiiUpload); ?>
            </div>
          <? endif; ?>
          <div class="rp-content1__inputs">
            <?
            // birthday
            ?>
            <?//if($_GET['birthday'] != "type"):?>
              <div class="rp-content1__inputs-row">
                <input type="text" name="birthday" id="datepicker" class="custom-calendar rp-content1__inputs-input required-inp" placeholder="Дата рождения" autocomplete="off">
                <span class="rp-content1__text">Укажите Вашу дату рождения.<br><span class="rp-content1__warning">Возраст должен быть не менее 14 лет</span></span>
                <div class="clearfix"></div>
              </div>
            <?//endif;?>
            <?
            // phone
            ?>
            <div class="rp-content1__inputs-row">
              <input type="text" name="phone" value="<?=$attr['phone']?>" id="phone-code" class="required-inp">
              <span class="rp-content1__text">Номер телефона позволит Вам использовать дополнительный функционал сервиса бесплатно.</span>
            </div>
            <?
            // city
            ?>
            <div class="rp-content1__inputs-row">
              <span class="rp-content1__select-arrow city">
                <input type="text" name="city_input" value="<?=$arGeo['city']?>" class="rp-content1__inputs-input city required-inp" id="city_input" autocomplete="off" >
                <ul id="city_list"></ul>
                <input type="hidden" name="city" id="city_hidden" value="<?=$arGeo['id_city']?>" data-name="<?=$arGeo['city']?>">
              </span>
              <span class="rp-content1__text">Ваш город</span>
              <div class="clearfix"></div>
            </div>
            <?
            // position
            ?>
            <div class="rp-content1__inputs-row">
                <span class="rp-content1__select-arrow city">
                  <input 
                  type="text" 
                  name="position_input" 
                  autocomplete="off" 
                  class="rp-content1__inputs-input required-inp" 
                  placeholder="Должность"
                  id="post_input"
                  >
                  <input type="hidden" name="position" id="post_hidden">
                  <ul id="post_list">
                    <li data-id="0">Список пуст</li>
                    <? foreach($viData['posts'] as $p): ?>
                      <li data-id="<?=$p['id']?>"><?=$p['val']?></li>
                    <? endforeach; ?>
                  </ul>
                </span>
                <span class="rp-content1__text">Укажите должность, на которой вы хотите работать (в анкете можно указать несколько должностей).</span>
                <div class="clearfix"></div>
              </div>
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

          <div class="center">
            <button class="prmu-btn prmu-btn_normal" id="form_btn">
              <span>Продолжить</span>
            </button>
          </div>
          <input name="all" value="0" type="hidden">
          <input name="rate" value="2" type="hidden">
          <input name="respond" value="2" type="hidden">
          <input name="mess" value="2" type="hidden">
          <input name="workday" value="2" type="hidden">
          <input type="hidden" name="npopup" value="1">
        </form>
      </div>
    </div>
    <div class="register-popup__content2">
      <div class="rp-content2__block">
        <h2 class="rp-content2__title">Теперь Вы можете найти временную работу, которая полностью отвечает Вашим требованиям</h2>
        <hr class="rp-content2__line">
        <p class="rp-content2__descr">Кроме того Вы получате возможность:</p>
        <div class="rp-c2__possibility-list">
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico1"></span>
            <span class="rp-c2-possibility__item-text">Работать с проверенными работодателями и откликаться на вакансии</span>
          </div>
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico2"></span>
            <span class="rp-c2-possibility__item-text">Настроить автоматическое оповещение по вакансиям</span>
          </div>
          <div class="clearfix"></div>
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico3"></span>
            <span class="rp-c2-possibility__item-text">Получать PUSH-уведомления и управлять ими</span>
          </div>
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico4"></span>
            <span class="rp-c2-possibility__item-text">Оставлять отзывы и комментарии о работодателях</span>
          </div>
          <div class="clearfix"></div>
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico5"></span>
            <span class="rp-c2-possibility__item-text">Проставлять рейтинг работодателями, с которыми работали</span>
          </div>
          <div class="rp-c2-possibility__item">
            <span class="rp-c2-possibility__item-img ico6"></span>
            <span class="rp-c2-possibility__item-text">Использовать мобильное приложение PROMMU для Android и IOS</span>
          </div>
          <div class="clearfix"></div>
        </div>
        <p class="rp-content2__bottom">...и это не полный перечень возможностей нашего сервиса!</p>
      </div>
    </div>
  </div>
  <?
  /*
  *
  *   push properties popup
  *
  */
  ?>
  <form method="get" id="" class="push-popup__form" action="/user/push">
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
          <input id="rate" name="push-4" value="2" type="checkbox" checked>
          <label for="rate" class="pp-form__checkbox-label">
            <div class="pp-form__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
          </label>
        </div>
      </div>
      <div class="pp-form__field">
        <span class="pp-form__checkbox-name">Приглашения на вакансии</span>
        <div class="pp-form__checkbox">
           <input id="respond" name="push-6" value="2" type="checkbox" checked>
           <label for="respond" class="pp-form__checkbox-label">
            <div class="pp-form__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
           </label>
        </div>
      </div>
      <div class="pp-form__field">
        <span class="pp-form__checkbox-name">Сообщения</span>
        <div class="pp-form__checkbox">
           <input id="mess" name="push-7" value="2" type="checkbox" checked>
           <label for="mess" class="pp-form__checkbox-label">
            <div class="pp-form__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
           </label>
        </div>
      </div>
      <div class="pp-form__field">
        <span class="pp-form__checkbox-name">День начала работы на вакансии</span>
        <div class="pp-form__checkbox">
           <input id="workday" name="push-8" value="2" type="checkbox" checked>
           <label for="workday" class="pp-form__checkbox-label">
            <div class="pp-form__checkbox-switch" data-checked="Да" data-unchecked="Нет"></div>
           </label>
        </div>
      </div>
    </div>
    <button class="pp-form__button" id="pash-save-btn">Сохранить</button>
  </form>
  <?
  //
  ?>
  <div class="tmpl rp-header__close-mess">
    <div class="prmu__popup">Мы заметили, что Вы не заполнили некоторые данные о себе, для поиска работы и для Работодателей которые ищут персонал - они очень важны и мы рекомендуем их заполнить
      <div>
        <a href="javascript:void(0)" data-fancybox-close>Заполнить данные</a>
        <a href="<?=MainConfig::$PAGE_EDIT_PROFILE?>">Идти дальше</a>
      </div>
    </div>
  </div>
<?endif;?>