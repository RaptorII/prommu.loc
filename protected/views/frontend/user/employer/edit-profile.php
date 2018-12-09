<?
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'phone-codes/style.css');
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-edit-prof-emp.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'phone-codes/script.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/page-edit-prof-emp.js', CClientScript::POS_END);
?>
<meta name="robots" content="noindex">
<? if( $viErrorData['err'] ): ?>
  <div class="err-msg-block">При сохранении данных профиля произошла ошибка. <?= $viErrorData['msg'] ?></div>
<? endif; ?>
<form action='' id='F1compprof' method='post' class="edit-profile-employer">
  <div class='col-xs-12'>
    <div class="epe__header">
      <h1 class="epe__header-title"><?=$viData['info']['name']?></h1>
    </div>
  </div>
  <div class="col-xs-12 col-md-8 col-md-push-2 epe__content">
    <div class="row">
      <div class="epe__logo">
        <div class='epe__logo-img'>
          <img src='<?=$viData['info']['src']?>' alt="<?=$viData['info']['name']?>">
          <a href="<?=MainConfig::$PAGE_USER_PHOTOS?>" class="epe__logo-edit">Изменить аватар</a>
        </div>
        <div class="epe-logo__btn-block" id="load-img-module">
          <div class="epe-logo__load js-g-hashint" id="btn-load-image" title="Выбрать изображение"></div>
          <div class="epe-logo__webcam js-g-hashint" id="btn-get-snapshot" title="Сделать снимок"></div>
          <div class="clearfix"></div>
        </div>
        <?php if(!$viData['info']['confirmEmail'] && !empty($viData['info']['email'])): ?>
          <div class="confirm-user email">Необходимо подтвердить почту</div>
        <?php endif; ?>
        <?php if(!$viData['info']['confirmPhone'] && !empty($viData['phone'])): ?>
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
                <input type='text' name='name' value="<?=$viData['info']['name']?>" class="epe__input epe__input-name epe__required" autocomplete="off">
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
              <div class="epe__label city-field">
                <span class="epe__label-name">Город:</span>
                <span class="city-select"><?=$viData['userCities']['name']?><b></b></span>
                <input type='text' name='str-city' value="<?=$viData['userCities']['name']?>" class="epe__input epe__input-city" autocomplete="off">
                <input type="hidden" name="cities[]" value="<?=$viData['userCities']['id_city']?>" id="id-city">
                <ul class="city-list"></ul>
              </div>
              <label class="epe__label">
                <span class="epe__label-name">Web Сайт:</span>
                <input type='text' name='user-attribs[site]' value="<?=$viData['attribs']['site']['val']?>" class="epe__input epe__input-site" autocomplete="off">
              </label>
            </div>
            <?
            //
            ?>
            <div class="epe-data__title"><h2>КОНТАКТНАЯ ИНФОРМАЦИЯ</h2></div>
            <div class="epe-data__module">
              <label class="epe__label">
                <span class="epe__label-name">Ваше имя:</span>
                <input type='text' name='fname' value="<?=$viData['info']['firstname']?>" class="epe__input epe__input-fname epe__required" autocomplete="off">
              </label>
              <label class="epe__label">
                <span class="epe__label-name">Ваша фамилия:</span>
                <input type='text' name='lname' value="<?=$viData['info']['lastname']?>" class="epe__input epe__input-lname" autocomplete="off">
              </label>
              <label class="epe__label">
                <span class="epe__label-name">Контактное лицо:</span>
                <input type='text' name='contact' value="<?=$viData['info']['contact']?>" class="epe__input epe__input-contact" autocomplete="off">
              </label>
              <label class="epe__label epe__email" data-error="Указанный e-mail адрес уже используется в системе" for="epe-email">
                <span class="epe__label-name">Ваша email:</span>
                <input type='text' name='email' value="<?=$viData['info']['email']?>" class="epe__input epe__input-mail epe__required" id="epe-email" autocomplete="off">
                <span class="epe__confirm<?=($viData['info']['confirmEmail'] && !empty($viData['info']['email'])?' complete':'')?>" id="conf-email">
                  <?php if($viData['info']['confirmEmail'] && !empty($viData['info']['email'])): ?>
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
                  <input type='text' name='confirm-code' value="" class="epe__input" id="conf-email-inp" maxlength="6" autocomplete="off">
                </label>
                <dir class="epe__confirm-btn hvr-sweep-to-right">ПРОВЕРИТЬ</dir>
                <dir class="clearfix"></dir>
              </div>
              <div class="epe__label">
                <span class="epe__label-name epe__phone-name">Телефон:</span>
                <input type='text' name='user-attribs[mob]' value="<?=$viData['phone']?>" class="epe__input epe__phone epe__input-phone" id="phone-code" autocomplete="off">
                <span class="epe__confirm<?=($viData['info']['confirmPhone']?' complete':'')?>" id="conf-phone">
                  <?php if(!$viData['info']['confirmPhone']): ?>
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
                  <input type='text' name='confirm-code' value="" class="epe__input" id="conf-phone-inp" maxlength="6" autocomplete="off">
                </label>
                <dir class="epe__confirm-btn hvr-sweep-to-right">ПРОВЕРИТЬ</dir>
                <dir class="clearfix"></dir>
              </div>
              <div class="epe__label epe__mess epe__select">
                <span class="epe__label-name">Мессенджеры:</span>
                <input type="text" name="epe-str-mess" value="<?=$viData['messengers']?>" class="epe__input epe__input-soc" id="epe-str-mess" disabled>
                <div class="epe__label-veil" id="epe-veil-mess"></div>
                <ul class="epe__select-list" id="epe-list-mess">
                    <i class="epe__select-list-icon">OK</i>
                    <li>
                        <input type="checkbox" name="epe-mess-viber" value="1" id="epe-mess-viber" data-mess="viber" <?=empty($viData['attribs']['viber']['val'])?'':'checked'?>>
                        <label for="epe-mess-viber">Viber<b></b></label>
                    </li>
                    <li>
                        <input type="checkbox" name="epe-mess-wapp" value="1" id="epe-mess-wapp" data-mess="wapp" <?=empty($viData['attribs']['whatsapp']['val'])?'':'checked'?>>
                        <label for="epe-mess-wapp">WhatsApp<b></b></label>
                    </li>
                    <li>
                        <input type="checkbox" name="epe-mess-tele" value="1" id="epe-mess-tele" data-mess="tele" <?=empty($viData['attribs']['telegram']['val'])?'':'checked'?>>
                        <label for="epe-mess-tele">Telegram<b></b></label>
                    </li>
                    <li>
                        <input type="checkbox" name="epe-mess-allo" value="1" id="epe-mess-allo" data-mess="allo" <?=empty($viData['attribs']['googleallo']['val'])?'':'checked'?>>
                        <label for="epe-mess-allo">Google Allo<b></b></label>
                    </li>
                </ul>
              </div>
              <div class="epe__mess epe__mess-viber <?=empty($viData['attribs']['viber']['val'])?'off':''?>">
                <div class="epe__label">
                  <span class="epe__label-name epe__phone-name">Viber:</span>
                  <input type="text" name="user-attribs[viber]" value="<?=$viData['attribs']['viber']['val']?>" class="epe__input epe__phone phone-input" autocomplete="off">
                  <ul class="phone-list"></ul>
                </div>
              </div>
              <div class="epe__mess epe__mess-wapp <?=empty($viData['attribs']['whatsapp']['val'])?'off':''?>">
                <div class="epe__label">
                  <span class="epe__label-name epe__phone-name">WhatsApp:</span>
                  <input type="text" name="user-attribs[whatsapp]" value="<?=$viData['attribs']['whatsapp']['val']?>" class="epe__input epe__phone phone-input" autocomplete="off">
                  <ul class="phone-list"></ul>
                </div>
              </div>
              <div class="epe__mess epe__mess-tele <?=empty($viData['attribs']['telegram']['val'])?'off':''?>">
                <div class="epe__label">
                  <span class="epe__label-name epe__phone-name">Telegram:</span>
                  <input type="text" name="user-attribs[telegram]" value="<?=$viData['attribs']['telegram']['val']?>" class="epe__input epe__phone phone-input" autocomplete="off">
                  <ul class="phone-list"></ul>
                </div>
              </div>
              <div class="epe__mess epe__mess-allo <?=empty($viData['attribs']['googleallo']['val'])?'off':''?>">
                <div class="epe__label">
                  <span class="epe__label-name epe__phone-name">Google Allo:</span>
                  <input type="text" name="user-attribs[googleallo]" value="<?=$viData['attribs']['googleallo']['val']?>" class="epe__input epe__phone phone-input" autocomplete="off">
                  <ul class="phone-list"></ul>
                </div>
              </div>
              <label class="epe__label">
                <span class="epe__label-name">Ваша должность:</span>
                <input type='text' name='user-attribs[post]' value="<?=$viData['attribs']['post']['val']?>" class="epe__input epe__input-pos" autocomplete="off">
              </label>
              <input type='checkbox' name='user-attribs[isnews]' value="1" <?=$viData['attribs']['isnews']['val'] ? 'checked' : '' ?> class="epe__hidden" id="subscribtion">
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
<? // записываются данные о пуше ?>
<script type="text/javascript">
  var selectPhoneCode = <?=json_encode($viData['phone-code'])?>;
  G_VARS.countryID = <?= $viData['userCities']['id_country'] ?>;
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
<? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/popup-load-img.php'; ?>