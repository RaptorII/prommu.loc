<meta name="robots" content="noindex,nofollow">
<? 
  $id = $viData['userInfo']['id_user'];
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-prof-emp.css'); 
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/page-prof-emp.js', CClientScript::POS_END);

if(!in_array(Share::$UserProfile->type, [2,3])): ?>
  <? 
    $title = 'Профиль работодателя - ' . $viData['userInfo']['name'];
    $this->pageTitle = $title; 
  ?>
    </div> <?// content-block?>
    <h1 class="user-profile-page__title"><?=$title?></h1>
  </div> <?// container?>
  <hr class="user-profile-page__line">
  <div class="container" >
    <div class="content-block">
<? endif; ?>
<div class='row'>
  <div class='col-xs-12 col-sm-4 col-lg-3 no-md-relat ppe__logo'>
    <div class="upp__img-block">
      <div class="upp__img-block-main">
        <?
          $cookieView = Yii::app()->request->cookies['popup_photo']->value;
          $bigSrc = Share::getPhoto($id, 3, $viData['userInfo']['logo'], 'big');
          $src = Share::getPhoto($id, 3, $viData['userInfo']['logo'], 'small');
        ?>
        <? if($viData['userInfo']['logo'] && $bigSrc): ?>
          <a 
            href="<?=$bigSrc?>"
            class="js-g-hashint upp__img-block-main-link profile__logo-full"
            title="<?=$viData['userInfo']['name']?>">
            <img src="<?=$src?>" alt="Работодатель <?=$viData['userInfo']['name']?> prommu.com">
          </a>
        <? else: ?>
          <img src="<?=$src?>" alt="Работодатель <?=$viData['userInfo']['name']?> prommu.com">
          <?
            if($flagOwnProfile && !$cookieView) // предупреждение, что нет фоток
            {
              Yii::app()->request->cookies['popup_photo'] = new CHttpCookie('popup_photo', 1);
              $message = '<p>У вас не загружено еще ни одной фотографии.<br>Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</p>';
              Yii::app()->user->setFlash('prommu_flash', $message);    
            }
          ?>
        <? endif; ?>
        <?if( $flagOwnProfile ):?>
          <a href="/user/editprofile?ep=1" class="upp__change-logo">Изменить аватар</a>
        <?php elseif($viData['userInfo']['is_online']): ?>
          <span class="upp-logo__item-onl"><span>В сети</span>
        <?php endif; ?>
      </div>
    </div>
    <div class="upp__logo-more">
      <? $i=0; ?>
      <? foreach ($viData['userPhotos'] as $key => $val): ?>
        <?
          $bigSrc = Share::getPhoto($id, 3, $val['photo'], 'big');
          $src = Share::getPhoto($id, 3, $val['photo'], 'small');
          if(!$val['photo'] || !$bigSrc)
            continue;
        ?>
        <div class="upp__img-block-more <?=($i>2?'off':'')?>">
          <a href="<?=$bigSrc?>" class="profile__logo-full">
            <img 
              src="<?=$src?>"
              alt="Соискатель <?=$viData['userInfo']['name']?> prommu.com">
          </a>
        </div>
        <? if($i==3): ?>
          <span class="upp-logo-more__link">Смотреть еще</span>
        <? endif; ?>
        <? $i++; ?>
      <? endforeach; ?>
      <div class="clearfix"></div> 
    </div>
    <? if(!$flagOwnProfile): ?>
      <div class="upp-logo-main__active">
        <span class="disable"><b>На сайте:</b> <?=$viData['userInfo']['time_on_site']?></span>
      </div>
      <div class="upp-logo-main__active">
        <?if(!$viData['userInfo']['is_online']):?>
          <span class="disable">Был(а) на сервисе: <?=date_format(date_create($viData['userInfo']['mdate']), 'd.m.Y');?></span>
        <?endif;?>
      </div>
    <? endif; ?>
    <div class='center-box'>
      <?php if(!$flagOwnProfile && ($viData['userAllInfo']['emplInfo']['confirmEmail'] || $viData['userAllInfo']['emplInfo']['confirmPhone'])): ?>
        <div class="confirmed-user js-g-hashint" title="Личность работодателя является подлинной">ПРОВЕРЕН</div>
      <?php endif; ?>
      <?php if( $flagOwnProfile ): ?>
        <a class='ppe__logo-btn prmu-btn' href='<?= MainConfig::$PAGE_EDIT_PROFILE ?>'><span>Редактировать профиль</span></a>
        <a class='ppe__logo-btn prmu-btn' href='<?= MainConfig::$PAGE_SETTINGS ?>'><span>Настройки профиля</span></a>
        <a class='ppe__logo-btn prmu-btn' href='<?= MainConfig::$PAGE_CHATS_LIST ?>'><span>Мои сообщения</span></a>
      <? endif; ?>  
      <? if(Share::isApplicant()): ?>
        <? if(Share::$UserProfile->hasAccessToChat($id)): ?>
          <div class="center">
            <h3 class='unpubl'>Есть доступные чаты с этим работодателем</h3>
            <a href="<?=MainConfig::$PAGE_CHATS_LIST_VACANCIES?>" class="prmu-btn prmu-btn_normal">
              <span>Перейти в чаты</span>
            </a>
          </div>
        <? else: ?>
          <h3 class='unpubl'>Сообщения можно писать только, при одобрении работодателем на опубликованной им вакансии</h3>
        <? endif; ?>
      <? endif; ?>
    </div>
  </div>
  <?
  /*
  *     parameters
  */
  ?>
  <div class='col-xs-12 col-sm-8 col-lg-9 ppe__content'>
    <h2 class="upp__title"><?=$viData['userInfo']['name']?></h2>

      <?php
      if( ($action = $this->action->getId()) == 'profile' ) $action = 'company-profile-own' ?>
      <?php
      /**
       * form
       *
       */

      //css
      Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS .'register/complete-reg.css');

      if( $action == 'company-profile-own' &&  Share::$UserProfile->exInfo->isblocked == 3 ): ?>
          <div class="center red" style="display:none; opacity: 0">
              Необходимо перейти в редактирование профиля и заполнить необходимые поля. После этого ваш профиль будет
              отображаться в общем списке соискателей и поиске на сайте, а также вы сможете откликаться на понравившиеся
              вакансии.
              <? echo Share::$UserProfile->checkRequiredFields()['mess']?>
          </div>

          <div class="complete__reg">
              <form id="complete__form">
                  <div class="complete__wrap">

                      <p class="complete__head center">
                          Необходимо активировать аккаунт
                      </p>
                      <p class="complete__txt center">
                          Чтобы получить доступ к новым возможностям - укажите данные
                      </p>

                      <span class="complete__about">Контактное лицо:</span>
                      <div class="complete__cover complete__company">
                          <div class="complete__name">
                              <input type="text" required="">
                              <span class="highlight"></span>
                              <span class="bar"></span>
                              <label >Имя/фамилия</label>
                          </div>

                          <div class="complete__email">
                              <input type="text" required="">
                              <span class="highlight"></span>
                              <span class="bar"></span>
                              <label >e-mail</label>

                          </div>

                          <div class="complete__phone">
                              <input type="text" required="">
                              <span class="highlight"></span>
                              <span class="bar"></span>
                              <label >phone</label>
                          </div>

                          <div class="complete__city">
                              <input type="text" required="">
                              <span class="highlight"></span>
                              <span class="bar"></span>
                              <label >city</label>
                          </div>

                      </div>

                      <span class="complete__about">Тип компании:</span>
                      <div class="complete__prof">

                          <ul id="complete__prof-list" class="complete__prof-list">
                              <?php
                              for($i=0; $i<3; $i++){
                                  ?>
                                  <li>
                                      <input type="checkbox" name="donjnost[]" value="<?=$i?>" id="post-<?=$i?>" class="complete__prof-item">
                                      <label for="post-<?=$i?>" class="prof-item">Прямой работодатель<b></b></label>
                                  </li>

                                  <?php
                              }
                              ?>
                          </ul>


                      </div>

                      <p class="complete__txt center">
                          После активации вам станет доступен каталог всех соискателей со всеми функциями
                      </p>

                      <p class="input center">
                          <button type="submit" class="btn__orange" data-step="">Активировать профиль</button>
                      </p>

                  </div>
              </form>
          </div>
      <?php
      endif;
      /**
       * end form
       */

      ?>

    <div class="upp__rating-block">
      <span class="upp__subtitle">Общий рейтинг </span>
      <ul class="upp__star-block">
        <li class="full"></li>
      </ul>     
      <div class="upp__subtitle"><?=Share::getRating($viData['userInfo']['rate'],$viData['userInfo']['rate_neg'])?></div><br/>
    </div>
    <hr class="upp__line">
    <table class="upp__table">
      <tbody>
        <?php foreach ($viData['rating']['pointRate'] as $key => $val): ?>
          <tr>
            <td class="upp__table-name">
              <span><?=$viData['rating']['rateNames'][$key]?></span>
            </td>
            <td class="upp__table-cnt">
              <span class="upp__table-cnt-plus js-g-hashint" title="Положительная оценка"><?=$val[0]?></span>
            </td>
            <td class="upp__table-cnt">
              <span class="upp__table-cnt-zero js-g-hashint" title="Нейтральная оценка">0</span>
            </td>
            <td class="upp__table-cnt">
              <span class="upp__table-cnt-minus js-g-hashint" title="Отрицательная оценка"><?=$val[1]?></span>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if(sizeof($viData['lastJobs']['jobs'])>0): ?>
    <hr class="upp__line">
    <span class="upp__subtitle">Размещенные вакансии <?=$viData['lastJobs']['count']?></span>
    <hr class="upp__line">
      <?php foreach ($viData['lastJobs']['jobs'] as $vacancy): ?>
        <div class='upp__project-item'>
          <div class="upp__project-info">
            <a class='upp__project-vacancy' href='<?= MainConfig::$PAGE_VACANCY . DS . $vacancy['id'] ?>'><?= $vacancy['title'] ?></a>
            <span class="dates">(<?= $vacancy['crdate'] . ' - ' . $vacancy['remdate'] ?>)</span>
          </div>
          <a href="<?=MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $vacancy['id'] ?>" class="upp__project-item-messages js-g-hashint" title="Обратная связь" style="color:#212121"><?=$vacancy['discuss_cnt']?></a>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class='vacancies-block'>
      <div class='vacancies'>
        <?php foreach ($viData['lastResp']['jobs'] as $val): ?>
          <b>
            <span><?= $val['cou'] ?></span>
            <a class='black-green' href='?p=company-response-list&id=<?= $val['id'] ?>'><?= $val['name'] ?></a>
          </b>
        <?php endforeach; ?>
      </div>
    </div>
    <?
    //    INFO
    ?>
    <?php
    $allInfo = $viData['userAllInfo']['emplInfo'];
    $allAttr = $viData['userAllInfo']['userAttribs'];
    $isBlocked = Share::$UserProfile->exInfo->isblocked==3;

    ?>
    <br>
    <div class="ppe__module-title"><h2>ОСНОВНАЯ ИНФОРМАЦИЯ</h2></div>
    <div class="ppe__module">
      <div class="ppe__field<?=($isBlocked && !$allInfo['name'] ?' error':'')?>">
        <span class="ppe__field-name">Название компании:</span>
        <span class="ppe__field-val"><?=$allInfo['name']?></span>
      </div>
      <?php
        $id = $this->ViewModel->isInArray($viData['userAllInfo']['cotype'], 'id', $allInfo['type']);
        if($id>=0): ?>
        <div class="ppe__field">
          <span class="ppe__field-name">Тип компании:</span>
          <span class="ppe__field-val"><?=$viData['userAllInfo']['cotype'][$id]['name']?></span>
        </div>
      <?php endif; ?>
      <div class="ppe__field<?=($isBlocked && !$viData['userAllInfo']['userCities'][0]['name'] ?' error':'')?>">
        <span class="ppe__field-name">Город:</span>
        <span class="ppe__field-val"><?=$viData['userAllInfo']['userCities'][0]['name']?></span>
      </div>
      <?php if(strlen($allAttr[99]['val'])>0): ?>
        <div class="ppe__field">
          <span class="ppe__field-name">Web Сайт:</span>
          <span class="ppe__field-val"><?=$allAttr[99]['val']?></span>
        </div>
      <?php endif; ?>
      <?php if(strlen($allInfo['aboutme'])): ?>
        <div class="ppe__field ppe__about">
          <span class="ppe__field-name">О компании:</span>
          <div class="ppe__field-val"><?=$allInfo['aboutme']?></div>
        </div>
      <?php endif; ?>
    </div>
    <br>
    <?php if($flagOwnProfile): // инфа для владельца ?>
      <div class="ppe__module-title"><h2>КОНТАКТНАЯ ИНФОРМАЦИЯ</h2></div>
      <div class="ppe__module">
        <?php if(strlen($allInfo['firstname'])>0): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Имя:</span>
            <span class="ppe__field-val"><?=$allInfo['firstname']?></span>
          </div>
        <?php endif; ?>
        <?php if(strlen($allInfo['lastname'])>0): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Фамилия:</span>
            <span class="ppe__field-val"><?=$allInfo['lastname']?></span>
          </div>
        <?php endif; ?>
        <?php if(strlen($allInfo['contact'])>0): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Контактное лицо:</span>
            <span class="ppe__field-val"><?=$allInfo['contact']?></span>
          </div>
        <?php endif; ?>
        <? $attrVal = $this->ViewModel->isInArray($allAttr, 'key', 'inn'); ?>
        <? if(!empty($allAttr[$attrVal]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">ИНН:</span>
            <span class="ppe__field-val"><?=$allAttr[$attrVal]['val']?></span>
          </div>
        <? endif; ?>
        <? $attrVal = $this->ViewModel->isInArray($allAttr, 'key', 'legalindex'); ?>
        <? if(!empty($allAttr[$attrVal]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Юридический адрес:</span>
            <span class="ppe__field-val"><?=$allAttr[$attrVal]['val']?></span>
          </div>
        <? endif; ?>
        <div class="ppe__field<?=($isBlocked && !$allInfo['email'] ?' error':'')?>">
          <span class="ppe__field-name">Email:</span>
          <span class="ppe__field-val"><?=$allInfo['email']?></span>
        </div>
        <div class="ppe__field<?=($isBlocked && !$allAttr[1]['val'] ?' error':'')?>">
          <span class="ppe__field-name">Телефон:</span>
          <span class="ppe__field-val"><?=$allAttr[1]['val']?></span>
        </div>
        <? $attrVal = $this->ViewModel->isInArray($allAttr, 'key', 'stationaryphone'); ?>
        <? if(!empty($allAttr[$attrVal]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Городской телефон:</span>
            <span class="ppe__field-val"><?=$allAttr[$attrVal]['val']?></span>
          </div>
        <? endif; ?>
        <?php
          $idViber = $this->ViewModel->isInArray($allAttr, 'key', 'viber');
          $idWhatsApp = $this->ViewModel->isInArray($allAttr, 'key', 'whatsapp');
          $idTelegram = $this->ViewModel->isInArray($allAttr, 'key', 'telegram');
          $idGoogleAllo = $this->ViewModel->isInArray($allAttr, 'key', 'googleallo');
        ?>
        <?php if(!empty($allAttr[$idViber]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Viber:</span>
            <span class="ppe__field-val"><?=$allAttr[$idViber]['val']?></span>
          </div>
        <?php endif; ?>
        <?php if(!empty($allAttr[$idWhatsApp]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">WhatsApp:</span>
            <span class="ppe__field-val"><?=$allAttr[$idWhatsApp]['val']?></span>
          </div>
        <?php endif; ?>
        <?php if(!empty($allAttr[$idTelegram]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Telegram:</span>
            <span class="ppe__field-val"><?=$allAttr[$idTelegram]['val']?></span>
          </div>
        <?php endif; ?>
        <?php if(!empty($allAttr[$idGoogleAllo]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Google Allo:</span>
            <span class="ppe__field-val"><?=$allAttr[$idGoogleAllo]['val']?></span>
          </div>
        <?php endif; ?>
        <?php if(strlen($allAttr[100]['val'])>0): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Должность:</span>
            <span class="ppe__field-val"><?=$allAttr[100]['val']?></span>
          </div>
        <?php endif; ?>
        <?
          $isNews = false;
          foreach ($allAttr as $v)
            $v['key']=='isnews' && $isNews=$v['val'];
        ?> 
        <? if($isNews): ?>
          <div class="ppe__checkbox <?=$isNews ? 'active' : ''?>">Получение новостей об изменениях и новых возможностях на сайте</div>
        <? endif; ?>
      </div>
      <div class="ppe__module">
        <a class='prmu-btn' href='<?= MainConfig::$PAGE_EDIT_PROFILE ?>'><span>Редактировать профиль</span></a>
      </div>
    <? endif; ?>
    <? if(Share::$UserProfile->showContactData($viData['userInfo']['id_user'], 'employer')): // вывод данных для С, который сотрудничает ?>
      <div class="ppe__module-title"><h2>КОНТАКТНАЯ ИНФОРМАЦИЯ</h2></div>
      <div class="ppe__module">
        <div class="ppe__field">
          <span class="ppe__field-name">Email:</span>
          <span class="ppe__field-val"><?=$allInfo['email']?></span>
        </div>
        <div class="ppe__field">
          <span class="ppe__field-name">Телефон:</span>
          <span class="ppe__field-val"><?=$allAttr[1]['val']?></span>
        </div>
        <?
          $idViber = $this->ViewModel->isInArray($allAttr, 'key', 'viber');
          $idWhatsApp = $this->ViewModel->isInArray($allAttr, 'key', 'whatsapp');
          $idTelegram = $this->ViewModel->isInArray($allAttr, 'key', 'telegram');
          $idGoogleAllo = $this->ViewModel->isInArray($allAttr, 'key', 'googleallo');
        ?>
        <? if(!empty($allAttr[$idViber]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Viber:</span>
            <span class="ppe__field-val"><?=$allAttr[$idViber]['val']?></span>
          </div>
        <? endif; ?>
        <? if(!empty($allAttr[$idWhatsApp]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">WhatsApp:</span>
            <span class="ppe__field-val"><?=$allAttr[$idWhatsApp]['val']?></span>
          </div>
        <? endif; ?>
        <? if(!empty($allAttr[$idTelegram]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Telegram:</span>
            <span class="ppe__field-val"><?=$allAttr[$idTelegram]['val']?></span>
          </div>
        <? endif; ?>
        <? if(!empty($allAttr[$idGoogleAllo]['val'])): ?>
          <div class="ppe__field">
            <span class="ppe__field-name">Google Allo:</span>
            <span class="ppe__field-val"><?=$allAttr[$idGoogleAllo]['val']?></span>
          </div>
        <? endif; ?>
      </div>
    <? endif; ?>
</div>