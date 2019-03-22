<meta name="robots" content="noindex,nofollow">
<? 
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
<?php
  
   $id = $viData['userInfo']['id_user'];
   
      $sql = "SELECT r.id, r.id_user idus,r.web, name , r.logo, r.rate, r.rate_neg
                , cast(r.rate AS SIGNED) - ABS(cast(r.rate_neg as signed)) avg_rate,
                 (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 0 AND mm.isneg = 0 AND mm.isactive = 1 AND mm.id_empl = r.id) commpos,
                   (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 0 AND mm.isneg = 1 AND mm.isactive = 1 AND mm.id_empl = r.id) commneg
                , (SELECT COUNT(id) FROM comments mm WHERE mm.iseorp = 0 AND mm.id_promo = r.id) comment_count
                   ,(SELECT COUNT(*) cou FROM empl_vacations v WHERE v.id_user = r.id_user AND v.status = 1 AND v.ismoder = 100) vaccount
            FROM employer r
            WHERE r.id_user = {$id}
            ORDER BY avg_rate DESC
            LIMIT 6";
        $result = Yii::app()->db->createCommand($sql)
        ->queryAll();

        $rate = $result[0]['rate'] + $result[0]['rate_neg'];
        $rating = $result[0]['commpos'] + $result[0]['commneg'];
        $rates = ($rate/$rating) * 10;

        if($result[0]['vaccount']){
            $vacancy = 10;
        }

        if($result[0]['commpos'] - $result[0]['commneg'] > 0){
            $comment = 25;
        } 

        if($result[0]['logo']){
            $logo = 2;
        }
        if($result[0]['web']){
            $web = 2;
        }
        $result = $web + $logo + $comment + $rates + $vacancy + 2 + 2;


  $id_promo = Share::$UserProfile->id;
    $sql = "SELECT
         r.id_user id
    FROM vacation_stat s
    INNER JOIN empl_vacations e ON e.id = s.id_vac
    INNER JOIN employer em ON em.id_user = e.id_user
    INNER JOIN resume r ON s.id_promo = r.id
    INNER JOIN user ru ON ru.id_user = r.id_user
    INNER JOIN user eu ON eu.id_user = em.id_user
    WHERE s.status = 5 OR s.status = 6
      AND e.id_user = {$idus}
      AND r.id_user = {$id_promo}";
    /** @var $res CDbCommand */
    $res = Yii::app()->db->createCommand($sql);
    $data = $res->queryScalar(); 
    if($data == $id_promo)
      $data = Share::$UserProfile->id;
    else 
      $datas = 1;
?>
<script type="text/javascript">//G_VARS.Modal = <?= $modal; ?>;</script>
<div class='row'>
  <div class='col-xs-12 col-sm-4 col-lg-3 no-md-relat ppe__logo'>
    <div class="upp__img-block">
      <div class="upp__img-block-main">
        <?php $hasphoto = $viData['userInfo']['logo']; ?>
        <?php if( $hasphoto ): ?>
          <a 
            href="<?=Share::getPhoto(3, $hasphoto, 'big')?>"
            class="js-g-hashint upp__img-block-main-link profile__logo-full"
            title="<?=$viData['userInfo']['name']?>">
            <img 
              src="<?=Share::getPhoto(3, $hasphoto, 'small')?>"
              alt="Работодатель <?=$viData['userInfo']['name']?> prommu.com">
          </a>
        <?php else: ?>
          <img 
            src="<?=Share::getPhoto(3, $hasphoto, 'small')?>"
            alt="Работодатель <?=$viData['userInfo']['name']?> prommu.com">
        <?php endif; ?>
        <?if( $flagOwnProfile ):?>
          <a href="/user/editprofile?ep=1" class="upp__change-logo">Изменить аватар</a>
        <?php elseif($viData['userInfo']['is_online']): ?>
          <span class="upp-logo__item-onl"><span>В сети</span>
        <?php endif; ?>
      </div>
    </div>
    <div class="upp__logo-more">
      <? $i=0; ?>
      <?php foreach ($viData['userPhotos'] as $key => $val): ?>
        <div class="upp__img-block-more <?=($i>2?'off':'')?>">
          <a 
            href="<?=Share::getPhoto(3, $val['photo'], 'big')?>" 
            class="profile__logo-full">
            <img 
              src="<?=Share::getPhoto(3, $val['photo'], 'small')?>"
              alt="Соискатель <?=$viData['userInfo']['name']?> prommu.com">
          </a>
        </div>
        <? if($i==3): ?>
          <span class="upp-logo-more__link">Смотреть еще</span>
        <? endif; ?>
        <? $i++; ?>
      <?php endforeach; ?>
      <div class="clearfix"></div> 
    </div>
    <? if(!$flagOwnProfile): ?>
      <div class="upp-logo-main__active">
        <?if(!$viData['userInfo']['is_online']):?>
          <span class="disable"><i></i>Был(а) на сервисе: <?=date_format(date_create($viData['userInfo']['mdate']), 'd.m.Y');?></span>
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
      <? if(Share::$UserProfile->type == 2 && $datas == 1):?>
        <div class='btn-update btn-orange-sm-wr'>
          <a class='hvr-sweep-to-right' href='#'>Невозможно отправить сообщение</a>
          <h3 class='unpubl'>Сообщения можно писать только, при одобрении работодателем на опубликованной им вакансии</h3>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <?
  /*
  *     parameters
  */
  ?>
  <div class='col-xs-12 col-sm-8 col-lg-9 ppe__content'>
    <h2 class="upp__title"><?=$viData['userInfo']['name']?></h2>
    <div class="upp__rating-block">
      <?php if(Share::$UserProfile->type==3): ?>
        <p>Как считается рейтинг</p>
      <?php endif; ?>
      <span class="upp__subtitle">Общий рейтинг</span> 
      <ul class="upp__star-block">
        <?php
          $rt = $result;
          $stars = 0;
          if($rt > 0 && $rt <= 20)
            $stars = 1;
          elseif($rt > 20 && $rt <= 40)
            $stars = 2;
          elseif($rt > 40 && $rt <= 60)
            $stars = 3;
          elseif($rt > 60 && $rt <= 80)
            $stars = 4;
          elseif($rt > 80 && $rt <= 100)
            $stars = 5;
          for($i=1; $i<=5; $i++):
            if($i>$stars):?><li></li><?else:?><li class="full"></li><?endif;?>
          <?php endfor; ?>
      </ul>     
      <span class="upp__subtitle"><?=$result?> из 100 баллов</span><br/>
    </div>
 <!--    <span class="upp__subtitle">(2/2)  - email, (2/2) - подтвержденный телефон, (<?=$logo?>/2) - логотип компании, (<?=$web?>/2) - сайт компании, (2/7) - 10 месяцев на сайте, (<?=$vacancy ?>/10) - опубликованные вакансии,  (<?=$comment?>/25) - отзывы, (<?=$rates?>/50) - оценки рейтинга</span> -->
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
        <div class="ppe__field<?=($isBlocked && !$allInfo['email'] ?' error':'')?>">
          <span class="ppe__field-name">Email:</span>
          <span class="ppe__field-val"><?=$allInfo['email']?></span>
        </div>
        <div class="ppe__field<?=($isBlocked && !$allAttr[1]['val'] ?' error':'')?>">
          <span class="ppe__field-name">Телефон:</span>
          <span class="ppe__field-val"><?=$allAttr[1]['val']?></span>
        </div>
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
<?
  $cookieView = Yii::app()->request->cookies['popup_photo']->value;
  $fullPath = Subdomain::domainRoot() . DS . MainConfig::$PATH_EMPL_LOGO 
    . DS . $viData['userInfo']['logo'] . '400.jpg';

  if($flagOwnProfile && !file_exists($fullPath) && !$cookieView)
  {
    Yii::app()->request->cookies['popup_photo'] = new CHttpCookie('popup_photo', 1);
    $message = '<p>У вас не загружено еще ни одной фотографии.<br>Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</p>';
    Yii::app()->user->setFlash('prommu_flash', $message);    
  }
?>