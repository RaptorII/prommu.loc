<?php
//
//  достижение цели для нового пользователя (после активации профиля)
//
if($flagOwnProfile && UserRegisterPageCounter::isSetData(Share::$UserProfile->id, UserRegister::$PAGE_ACTIVE_PROFILE) <= 0):
?>
  <? UserRegisterPageCounter::setByIdUser(Share::$UserProfile->id, UserRegister::$PAGE_ACTIVE_PROFILE); ?>
  <script>
    document.addEventListener("DOMContentLoaded", function(){
      var yaParams = [{id_user:<?=Share::$UserProfile->id?>,type:"applicant"}];
      var cnt = 0;
      setGoal();
      function setGoal()
      {
        cnt++;
        if(cnt>10)
        {
          return;
        }
        if(typeof yaCounter23945542 === 'object')
        {
          yaCounter23945542.reachGoal(6,{params:yaParams});
        }
        else
        {
          setTimeout(function(){ setGoal() },500);
        }
      }
    });
  </script>
<?
endif;
//
//
//
?>
<?/*?>
<script src="https://www.google.com/jsapi"></script>
<?*/?>
<?php
  $attr = array_values($viData['userInfo']['userAttribs'])[0];
  $idPromo = $attr['id'];
  $info = $viData['userInfo'];
  $h1title = $attr['firstname'] . ' ' . $attr['lastname'];
?>
<?php if(!in_array(Share::$UserProfile->type, [2,3])): ?>
    </div> <?// content-block?>
    <h1 class="user-profile-page__title"><?=(!empty($attr['meta_h1']) ? $attr['meta_h1'] : 'Профиль соискателя - ' . $h1title) // установить H1 ?></h1>
  </div> <?// container?>
  <hr class="user-profile-page__line">
  <div class="container" >
    <div class="content-block">
<?php endif; ?>
<?
//  Yii::app()->getClientScript()->registerCssFile('/theme/css/private/page-prof-app.css');
//  Yii::app()->getClientScript()->registerCssFile('/theme/css/private/page-prof-personal-area.css');
  Yii::app()->getClientScript()->registerScriptFile("/theme/js/private/page-prof-app.js", CClientScript::POS_END);
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'dist/magnific-popup-min.css');
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . Share::$cssAsset['modalwindow.css']);
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'dist/jquery.magnific-popup.min.js', CClientScript::POS_END);
  //
  // Установка метаданных и заголовка
  //
  if(!$flagOwnProfile){
    $sql = "SELECT ismoder FROM user WHERE id_user = " . $attr['id_user'];
    $ismoder = Yii::app()->db->createCommand($sql)->queryScalar();
    $date1 = new DateTime();
    $date2 = new DateTime($attr['bday']);
    $birthday = $date1->diff($date2)->y;
    $edu = '';
    $arLang = array();
    foreach($info['userAttribs'] as $v)
    {
      $v['idpar']==69 && $edu = $v['name']; // edu
      $v['idpar'] == 40 && $arLang[] = $v['name']; // lang
    }

    $arSeoParams = array(
      'firstname' => $attr['firstname'],
      'lastname' => $attr['lastname'],
      'cities' => current($info['userCities']),
      'posts' => current($info['userDolj']),
      'isman' => $attr['isman'],
      'years' => $birthday . ' ' . Share::endingYears($birthday),
      'education' => $edu,
      'lang' => $arLang
    );
    $arSeo = Seo::getMetaForApp($arSeoParams);
    // закрываем от индексации
    if($attr['index'] || $viData['profile_filling']<UserProfileApplic::$INDEX_PROFILE_FILLING || !$ismoder)
    {
      Yii::app()->clientScript->registerMetaTag('noindex,nofollow','robots', null, array());
    }
    // устанавливаем title
    if(empty($attr['meta_title']))
      $attr['meta_title'] = $arSeo['meta_title'];
    $this->pageTitle = $attr['meta_title'];

    // устанавливаем description
    if(empty($attr['meta_description']))
      $attr['meta_description'] = $arSeo['meta_description'];
    Yii::app()->clientScript->registerMetaTag($attr['meta_description'], 'description');
  }
  $cntComments = $viData['lastComments']['count'][0] + $viData['lastComments']['count'][1];

  $arPosts = array();
  foreach($info['userDolj'][0] as $post){

    $arPosts[$post['idpost']]['val'] = $post['val'];
    if(!$post['isshow']){
      $arPosts[$post['idpost']]['pay'] = $post['pay']>0 ? round($post['pay']) : '';
      switch ($post['pt']) {
        case 0: $arPosts[$post['idpost']]['pt'] = 'Час'; break;
        case 1: $arPosts[$post['idpost']]['pt'] = 'Неделя'; break;
        case 2: $arPosts[$post['idpost']]['pt'] = 'Месяц'; break;
        case 3: $arPosts[$post['idpost']]['pt'] = 'Посещение'; break;
      }
    }
    if($post['isshow'])
      $arPosts[$post['idpost']]['pname'] = $post['pname'];
  }
  $arDays = array(1=>'ПН', 2=>'ВТ', 3=>'СР', 4=>'ЧВ', 5=>'ПТ', 6=>'СБ', 7=>'ВС');

  $id_empl = Share::$UserProfile->id;
  $sql = "SELECT em.id_user FROM employer em WHERE em.id_user = {$id_empl} AND em.ismoder = 1";
  $ismoder = Yii::app()->db->createCommand($sql)->queryScalar();

?>

<?php

if (!share::isApplicant()):
    Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-prof-app.css');

//start
?>

<div class="private-profile-page <?=(!$flagOwnProfile?'for-guest':'')?>">
  <?php if( $viData['error'] ): ?>
    <div class="comm-mess-box"><?= $viData['message'] ?></div>
  <?php else: ?>
  <div class="ppp__logo">
    <div class="ppp__logo-main">
      <?
      	$cookieView = Yii::app()->request->cookies['popup_photo']->value;
        $bigSrc = Share::getPhoto($attr['id_user'], 2, $attr['photo'], 'big', $attr['isman']);
        $src = Share::getPhoto($attr['id_user'], 2, $attr['photo'], 'medium', $attr['isman']);
      ?>
      <? if($attr['photo'] && $bigSrc): ?>
        <a href="<?=$bigSrc?>" class="js-g-hashint ppp-logo-main__link ppp__logo-full" title="<?=$h1title?>">
          <img
            src="<?=$src?>"
            alt='Соискатель <?=$attr['lastname']?> prommu.com'
            class="ppp-logo-main__img">
        </a>
      <? else: ?>
        <img
          src="<?=$src?>"
          alt='Соискатель <?=$attr['lastname']?> prommu.com'
          class="ppp-logo-main__img">
        <?
          if($flagOwnProfile && !$cookieView) // предупреждение, что нет фоток
          {
            Yii::app()->request->cookies['popup_photo'] = new CHttpCookie('popup_photo', 1);
            $message = '<p>У вас не загружено еще ни одной фотографии.<br>Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</p>';
            Yii::app()->user->setFlash('prommu_flash', $message);
          }
        ?>
      <? endif; ?>

      <?php if(!$flagOwnProfile && $attr['is_online']): ?>
        <span class="ppp-logo__item-onl"><span>В сети</span>
      <?php endif; ?>
      <?if($flagOwnProfile):?>
        <a href="<?=MainConfig::$PAGE_EDIT_PROFILE . '?ep=1'?>" class="ppp-logo-main__change">Изменить аватар</a>
      <?endif;?>
    </div>
    <? if(!$flagOwnProfile): ?>
      <div class="ppp-logo-main__active">
        <span class="disable"><b>На сайте:</b> <?=$info['time_on_site']?></span>
      </div>
      <div class="ppp-logo-main__active">
        <?if(!$attr['is_online']):?>
          <span class="disable">Был<?=$attr['isman']?'':'а'?> на сервисе: <?=date_format(date_create($attr['mdate']), 'd.m.Y');?></span>
        <?endif;?>
      </div>
      <div class="ppp__logo-rating">
        <ul class="ppp__star-block"><li class="full"></li></ul>
        <span class="ppp__subtitle"><?=Share::getRating($attr['rate'],$attr['rate_neg'])?></span>
      </div>
      <?php if($cntComments): ?>
        <div class="ppp__logo-comm">
          <span class="ppp__subtitle">Отзывы: </span>
          <span class="upp__review upp__review-red js-g-hashint" title="Отрицательные отзывы">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link"><?=$viData['lastComments']['count'][1]?></a>
          </span>
          <span class="upp__review upp__review-green js-g-hashint" title="Положительные отзывы">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link"><?=$viData['lastComments']['count'][0]?></a>
          </span>
          <span class="ppp__logo-allrev">Всего:</span>
          <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>"><?=$cntComments?></a>
        </div>
      <?php endif; ?>
    <?endif;?>

    <div class="ppp__logo-more">
      <? $i=0; ?>
      <? foreach($info['userPhotos'] as $v): ?>
        <?
          $bigSrc = Share::getPhoto($attr['id_user'], 2, $v['photo'], 'big', $attr['isman']);
          $src = Share::getPhoto($attr['id_user'], 2, $v['photo'], 'small', $attr['isman']);
          if(!$v['photo'] || !$bigSrc || $v['ismain']) // если фото нет или фото главное
            continue;
        ?>
        <div class="ppp-logo__item">
          <a href="<?=$bigSrc?>" class="ppp-logo-item__link ppp__logo-full">
            <img
              src="<?=$src?>"
              alt="Соискатель <?=$attr['lastname']?> prommu.com"
              class="ppp-logo-item__img">
          </a>
        </div>
        <?
          $i++;
          if($i==6) break;
          if($i==3) echo '<div class="clearfix"></div>';
        ?>
      <? endforeach; ?>
      <div class="clearfix"></div>
    </div>

    <?php if($attr['confirmPhone'] || $attr['confirmEmail']): ?>
      <div class="confirmed-user js-g-hashint" title="Личность соискателя является подлинной">ПРОВЕРЕН</div>
    <?php endif; ?>

    <? if(!empty($info['self_employed'])): ?>
      <div class="self_employed-user js-g-hashint" title="Налоговый статус соискателя">САМОЗАНЯТЫЙ</div>
    <? endif; ?>

    <div class='center-box'>
      <?php if( $flagOwnProfile ): ?>
        <a class='ppp__btn btn__orange' href='<?= MainConfig::$PAGE_EDIT_PROFILE ?>' style="margin-bottom: 10px">Редактировать профиль</a>
        <a class='ppp__btn btn__orange' href='<?= MainConfig::$PAGE_SETTINGS ?>' style="margin-bottom: 10px">Настройки профиля</a>
        <a class='ppp__btn btn__orange' href='<?= MainConfig::$PAGE_CHATS_LIST ?>'>Мои сообщения</a>
      <?php elseif( Share::$UserProfile->type == 3 && $ismoder): ?>
        <div class='js-btn-invite btn-white-green-wr'>
          <a href='#'>Пригласить на вакансию</a>
        </div>
      <?php endif; ?>
      <?php  if(Share::$UserProfile->type == 3 && !$ismoder):?>
        <div class='btn-update btn-orange-sm-wr'>
          <a class='hvr-sweep-to-right btn__orange' href='#'>Невозможно отправить сообщение</a>
          <h3 class='unpubl'>Отправлять сообщения и приглашения на вакансию можно только при успешном прохождении модерации</h3>
        </div>
      <?php endif; ?>

      <?php
        $idCity = array_keys($viData['userInfo']['userCities'][0]);
        $arLocation = (new Geo)->getCity( $idCity[0] );

         if ( $arLocation['region'] == 1307
            && !Share::isGuest()
            && Share::isApplicant()
            ): ?>
        <div class="messenger">
            <span class="messenger__txt">
                Узнавай о самых свежих вакансиях самым первым
            </span>
            <div class="messenger__item">
                  <a href="https://t.me/prommucom" target="_blank" class="messenger__item--link">
                      <span class="icn-telegram-icon"></span>
                      <span class="messenger__name">Telegram</span>
                  </a>
            </div>
        </div>
      <?php endif; ?>

      <?php if( $flagOwnProfile ): ?>
        <div class='affective-block'>
          <div class='affective-perc'>
            <div class='progr' style="width: <?= $viData['profile_filling'] ?>%">
              <div class='text'><?= $viData['profile_filling'] ?>%</div>
            </div>
          </div>

          <div class="affective__wrap">
            <div class='affective'>Эффективность<br/>размещения</div>

            <?php

             if  ($viData['profile_filling'] != 100) {
                 ?>
                 <span class="question_popup prmu-btn prmu-btn_normal"><span>?</span></span>
                 <div class="prmu__popup popup__msg" >
                    <h3>Уважаемый соискатель!</h3>
                    <p>Для эффективного размещения профиля необходимо заполнить его в полном объёме.</p>
                    <p>Информация по незаполнению данных в личном профиле для 100% эффективности.</p>
                    <ul>
                        <? if (empty($viData['userInfo']['userAttribs']['1']['photo']) ||
                              (count($info['userPhotos']) <= 1)) { ?>
                            <h4>Фото</h4>
                        <? }
                        if (empty($viData['userInfo']['userAttribs']['1']['photo'])) { ?>
                            <li> Основное фото - 10% </li>
                        <? }
                        if ( count($info['userPhotos']) <= 1) {
                            echo '<li> Дополнительные фото - 10% </li>';
                         }
                        If (empty($viData['userInfo']['userAttribs']['1']['firstname']) ||
                            empty($viData['userInfo']['userAttribs']['1']['lastname'])  ||
                            empty($viData['userInfo']['userAttribs']['1']['bday'])      ||
                            empty($viData['userInfo']['userAttribs']['1']['isman'])    ) {
                            echo "<h4>Основная информация</h4>";
                        }
                        If (empty($viData['userInfo']['userAttribs']['1']['firstname'])) {
                            echo '<li> Имя - 1% </li>';
                        }
                        If (empty($viData['userInfo']['userAttribs']['1']['lastname'])) {
                            echo '<li> Фамилия - 1% </li>';
                        }
                        If (empty($viData['userInfo']['userAttribs']['1']['bday'])) {
                            echo '<li> Дата рождения - 1% </li>';
                        }
                        If (empty($viData['userInfo']['userAttribs']['1']['isman'])) {
                            echo '<li> Пол - 1% </li>';
                        }
                        if (($viData['userInfo']['userAttribs']['1']['ismed'] != '0' ) ||
                            ($viData['userInfo']['userAttribs']['1']['smart'] != '0' ) ||
                            ($viData['userInfo']['userAttribs']['1']['card']  != '0' ) ||
                            ($viData['userInfo']['userAttribs']['1']['ishasavto']) != '0' ) {
                        } else {
                            echo '<li> Доп. данные (авто / мед книга / смартфон / наличие банк карт) - 1%</li>';
                        }
                        ?>
                        <?
                        If (empty($viData['userInfo']['userAttribs']['1']['phone']) ||
                            empty($viData['userInfo']['userAttribs']['1']['email'])

                            ) {
                            echo'<h4>Контактная информация</h4>';
                            }

                        If (empty($viData['userInfo']['userAttribs']['1']['phone'])) {
                            echo '<li> Телефон -5% </li>';
                        }
                        If (empty($viData['userInfo']['userAttribs']['1']['email'])) {
                            echo '<li> Е-меил - 5% </li>';
                        }
                        if (!empty($viData['userInfo']['userAttribs']['4']['val'])   || //messangers
                            !empty($viData['userInfo']['userAttribs']['157']['val']) ||
                            !empty($viData['userInfo']['userAttribs']['156']['val']) ||
                            !empty($viData['userInfo']['userAttribs']['158']['val']) ||
                            !empty($viData['userInfo']['userAttribs']['5']['val'])) {
                        } else {
                            echo '<li> Мессенджеры - 5% </li>';
                        }
                        if (!empty($viData['userInfo']['userAttribs']['6']['val'])   || //social networks
                            !empty($viData['userInfo']['userAttribs']['160']['val']) ||
                            !empty($viData['userInfo']['userAttribs']['161']['val']) ||
                            !empty($viData['userInfo']['userAttribs']['162']['val']) ||
                            !empty($viData['userInfo']['userAttribs']['7']['val'])) {
                        } else {
                            echo '<li> Социальные сети  - 5% </li>';
                        }
                        ?>
                        <?
                        //echo sizeof($viData['userInfo']['userDolj']['0']);
                        If (sizeof($viData['userInfo']['userDolj']['0']) == 0 ) { //заполнены поля должностей
                            echo '<h4>Целевая вакансия - 5%</h4>';
                            echo '<li> Должность </li>';
                            echo '<li> Ожидаемая оплата </li>';
                            echo '<li> Опыт работы </li>';
                        }

                        If ((sizeof($viData['userInfo']['userCities']['0']) == 0) ||
                            (sizeof($viData['userInfo']['userWdays']) == 0) ) {
                            echo '<h4>Удобное место и время работы</h4>';
                        }
                        If ((sizeof($viData['userInfo']['userCities']['0']) == 0)) {
                            echo '<li> Город - 1% </li>';
                        }
                        If (sizeof($viData['userInfo']['userWdays']) == 0) {
                            echo '<li> Удобное время работы - 4% </li>';
                        }
                        If (isset($viData['userInfo']['userAttribs']['9']['val'])  ||
                            isset($viData['userInfo']['userAttribs']['10']['val']) ||
                            isset($viData['userInfo']['userAttribs']['17']['name'])||
                            isset($viData['userInfo']['userAttribs']['18']['name'])||
                            isset($viData['userInfo']['userAttribs']['19']['name'])||
                            isset($viData['userInfo']['userAttribs']['20']['name'])||
                            isset($viData['userInfo']['userAttribs']['21']['name'])||
                            isset($viData['userInfo']['userAttribs']['22']['name'])||
                            isset($viData['userInfo']['userAttribs']['23']['name'])||
                            isset($viData['userInfo']['userAttribs']['24']['name'])||
                            isset($viData['userInfo']['userAttribs']['25']['name'])||
                            isset($viData['userInfo']['userAttribs']['26']['name'])||
                            isset($viData['userInfo']['userAttribs']['27']['name'])||
                            isset($viData['userInfo']['userAttribs']['28']['name'])||
                            isset($viData['userInfo']['userAttribs']['29']['name'])||
                            isset($viData['userInfo']['userAttribs']['30']['name'])||
                            isset($viData['userInfo']['userAttribs']['75']['name'])||
                            isset($viData['userInfo']['userAttribs']['76']['name'])||
                            isset($viData['userInfo']['userAttribs']['77']['name'])||
                            isset($viData['userInfo']['userAttribs']['78']['name'])||
                            isset($viData['userInfo']['userAttribs']['79']['name'])||
                            isset($viData['userInfo']['userAttribs']['80']['name'])||
                            isset($viData['userInfo']['userAttribs']['81']['name'])||
                            isset($viData['userInfo']['userAttribs']['82']['name'])||
                            isset($viData['userInfo']['userAttribs']['83']['name'])||
                            isset($viData['userInfo']['userAttribs']['84']['name'])||
                            isset($viData['userInfo']['userAttribs']['85']['name'])||
                            isset($viData['userInfo']['userAttribs']['86']['name'])||
                            isset($viData['userInfo']['userAttribs']['87']['name'])||
                            isset($viData['userInfo']['userAttribs']['88']['name'])||
                            isset($viData['userInfo']['userAttribs']['89']['name'])||
                            isset($viData['userInfo']['userAttribs']['90']['name'])||
                            isset($viData['userInfo']['userAttribs']['91']['name'])||
                            isset($viData['userInfo']['userAttribs']['92']['name'])||
                            isset($viData['userInfo']['userAttribs']['93']['name'])||
                            isset($viData['userInfo']['userAttribs']['94']['name'])||
                            isset($viData['userInfo']['userAttribs']['95']['name'])||
                            isset($viData['userInfo']['userAttribs']['96']['name'])||
                            isset($viData['userInfo']['userAttribs']['97']['name']) ) {
                        }else{
                            echo '<h4>Внешние данные</h4>';
                        }
                        If (empty($viData['userInfo']['userAttribs']['9']['val'])) {
                            echo '<li>Рост - 3% </li>';
                        }
                        If (empty($viData['userInfo']['userAttribs']['10']['val'])) {
                            echo '<li>Вес - 3% </li>';
                        }
                        If (isset($viData['userInfo']['userAttribs']['17']['name']) ||
                            isset($viData['userInfo']['userAttribs']['18']['name']) ||
                            isset($viData['userInfo']['userAttribs']['19']['name']) ||
                            isset($viData['userInfo']['userAttribs']['20']['name']) ||
                            isset($viData['userInfo']['userAttribs']['21']['name']) ||
                            isset($viData['userInfo']['userAttribs']['22']['name']) ) {
                        } else {
                            echo '<li>Цвет волос - 3% </li>';
                        }
                        If (isset($viData['userInfo']['userAttribs']['75']['name']) ||
                            isset($viData['userInfo']['userAttribs']['76']['name']) ||
                            isset($viData['userInfo']['userAttribs']['77']['name']) ||
                            isset($viData['userInfo']['userAttribs']['78']['name']) ||
                            isset($viData['userInfo']['userAttribs']['79']['name'])) {
                        }else{
                            echo '<li>Длина волос - 3% </li>';
                        }
                        If (isset($viData['userInfo']['userAttribs']['23']['name']) ||
                            isset($viData['userInfo']['userAttribs']['24']['name']) ||
                            isset($viData['userInfo']['userAttribs']['25']['name']) ||
                            isset($viData['userInfo']['userAttribs']['26']['name']) ||
                            isset($viData['userInfo']['userAttribs']['27']['name']) ||
                            isset($viData['userInfo']['userAttribs']['28']['name']) ||
                            isset($viData['userInfo']['userAttribs']['29']['name']) ||
                            isset($viData['userInfo']['userAttribs']['30']['name']) ) { //8
                        }else{
                            echo '<li>Цвет глаз  - 3% </li>';
                        }
                        If (isset($viData['userInfo']['userAttribs']['80']['name']) ||
                            isset($viData['userInfo']['userAttribs']['81']['name']) ||
                            isset($viData['userInfo']['userAttribs']['82']['name']) ||
                            isset($viData['userInfo']['userAttribs']['83']['name']) ||
                            isset($viData['userInfo']['userAttribs']['84']['name']) ||
                            isset($viData['userInfo']['userAttribs']['85']['name']) ) { //6
                        }else{
                            echo '<li>Размер груди - 3% </li>';
                        }
                        If (isset($viData['userInfo']['userAttribs']['86']['name']) ||
                            isset($viData['userInfo']['userAttribs']['87']['name']) ||
                            isset($viData['userInfo']['userAttribs']['88']['name']) ||
                            isset($viData['userInfo']['userAttribs']['89']['name']) ||
                            isset($viData['userInfo']['userAttribs']['90']['name']) ||
                            isset($viData['userInfo']['userAttribs']['91']['name']) ) { //6
                        }else{
                            echo '<li>Объем талии  - 3% </li>';
                        }
                        If (isset($viData['userInfo']['userAttribs']['92']['name']) ||
                            isset($viData['userInfo']['userAttribs']['93']['name']) ||
                            isset($viData['userInfo']['userAttribs']['94']['name']) ||
                            isset($viData['userInfo']['userAttribs']['95']['name']) ||
                            isset($viData['userInfo']['userAttribs']['96']['name']) ||
                            isset($viData['userInfo']['userAttribs']['97']['name']) ) { //6
                        }else{
                            echo '<li>Объем бедер  - 3% </li>';
                        }
                        ?>
                        <?
                        If (empty($viData['userInfo']['userAttribs']['1']['aboutme'])) {
                            echo '<h4>Дополнительная информация</h4>';
                        }
                        If (isset($viData['userInfo']['userAttribs']['70']['name']) ||
                            isset($viData['userInfo']['userAttribs']['71']['name']) ||
                            isset($viData['userInfo']['userAttribs']['72']['name']) ||
                            isset($viData['userInfo']['userAttribs']['73']['name']) ) { //obrazovanie
                        } else {
                            echo '<li>Образование - 5%</li>';
                        }
                        If (empty($viData['userInfo']['userAttribs']['1']['aboutme']))  {
                            echo '<li>О себе - 11%</li>';
                        }

                        for($i=41; $i<69; $i++){ //language
                            $isLang = false;
                            if ($viData['userInfo']['userAttribs'][$i]['name']){
                                $isLang = true;
                            }
                            If ($isLang) {
                                break;
                            }else {
                                If ($i==68){
                                    echo '<li>Иностранные языки - 5%</li>';
                                    break;
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
             <? } ?>

          </div>
        </div>

        <div class="affective-block">
            <div class="affective-block__instruction">Скачать иструкцию пользования сервисом <b>PROMMU.com </b></div>
            <a class="download__btn" href='/theme/pdf/Instruction-PROMMU-com-all-app.pdf' target="_blank"></a>
        </div>

      <?php endif; ?>
      <div class="clearfix"></div>
    </div>
  </div>
  <?
  /*
  *   CONTENT
  */
  ?>
  <div class="ppp__content">
    <h2 class="ppp__content-title"><?=$h1title?></h2>
    <?
    //    RATING
    ?>
    <?php if( $flagOwnProfile ): ?>
      <div class="ppp__rating">
        <div class="ppp__rating-block">
          <span class="ppp__subtitle">Общий рейтинг</span>
          <ul class="ppp__star-block"><li class="full"></li></ul>
          <span class="ppp__subtitle"><?=Share::getRating($attr['rate'],$attr['rate_neg'])?></span>
        </div>
        <? if(!$info['self_employed'] && $info['self_employed_region']): ?>
          <div class="ppp__self-employed">
              <a href="<?=MainConfig::$VIEW_SELF_EMPLOYED?>" class="prmu-btn prmu-btn_normal">
                <span>Стать самозанятым</span>
              </a>
              <? if(!$info['self_employed']): ?>
                  <span class="ppp__self-employed-question prmu-btn prmu-btn_normal"><span>?</span></span>
              <? endif; ?>
              <div class="ppp__self-employed-message prmu__popup" id="self_employed_message">

                  <h3>Причина регистрироваться на Prommu.com под статусом «Самозанятого»</h3>

                  <div class="ppp__self--inner-row">
                    <div class="ppp__self--img">
                        <div class="money-check-alt"></div>
                    </div>
                    <div class="ppp__self--txt">
                        <span class="ppp__self--txt-head">
                            Налог всего 4-6%.
                        </span>
                        <span class="ppp__self--txt-about">
                            Подоходный налог 13% заменён на налог на професиональную деятельность (НПД) всего 4-6%.
                        </span>
                    </div>
                  </div>

                  <div class="ppp__self--inner-row">
                    <div class="ppp__self--img">
                        <div class="handshake"></div>
                    </div>
                    <div class="ppp__self--txt">
                        <span class="ppp__self--txt-head">
                            Никаких "Серых" схем.
                        </span>
                        <span class="ppp__self--txt-about">
                            Полная легализация отношений. Дополнительные возможности для личной защиты перед законом.
                            Минимизация налоговых рисков.
                        </span>
                    </div>
                  </div>

                  <div class="ppp__self--inner-row">
                    <div class="ppp__self--img">
                        <div class="credit-card"></div>
                    </div>
                    <div class="ppp__self--txt">
                        <span class="ppp__self--txt-head">
                            Кредит в банке стало получить проще.
                        </span>
                        <span class="ppp__self--txt-about">
                            Можно предоставить официальную справку о доходах в банк. Справка формируется в приложении
                            или через веб-кабинет22.
                        </span>
                    </div>
                  </div>

                  <div class="ppp__self--inner-row">
                    <div class="ppp__self--img">
                        <div class="biking"></div>
                    </div>
                    <div class="ppp__self--txt">
                        <span class="ppp__self--txt-head">
                            Удобно и никуда не надо ехать.
                        </span>
                        <span class="ppp__self--txt-about">
                            Не нужно посещать налоговую и подавать документы для регистрации самозанятым. Регистрация
                            осуществляется с мобильного телефона через приложение "Мой налог" и занимает 20 минут.
                        </span>
                    </div>
                  </div>

                  <div class="ppp__self--inner-row">
                    <div class="ppp__self--img">
                        <div class="envelop-open-dollar"></div>
                    </div>
                    <div class="ppp__self--txt">
                        <span class="ppp__self--txt-head">
                            Нет обязательных платежей.
                        </span>
                        <span class="ppp__self--txt-about">
                            Нет прибыли - нет налога. Ничего не теряеете.
                        </span>
                    </div>
                  </div>

                  <div class="ppp__self--inner-row">
                    <div class="ppp__self--img">
                        <div class="home-heart"></div>
                    </div>
                    <div class="ppp__self--txt">
                        <span class="ppp__self--txt-head">
                            Доступная ипотека.
                        </span>
                        <span class="ppp__self--txt-about">
                            Подтверждение о доходах самозанятого позволяет банку рассчитать более оптимальную ставку
                            на ипотеку.
                        </span>
                    </div>
                  </div>

                  <div class="ppp__self--inner-row">
                    <div class="ppp__self--img">
                        <div class="cut"></div>
                    </div>
                    <div class="ppp__self--txt">
                        <span class="ppp__self--txt-head">
                            Налоговый вычет 10 000 руб.
                        </span>
                        <span class="ppp__self--txt-about">
                            Возможность получения вычета со ставки 4% в размере 1% и со ставки :% величиной 2% в
                            пределах 10 тысяч рублей {п.2 ст.12 ФЗ №422}.
                        </span>
                    </div>
                  </div>

                  <div class="ppp__self--inner-row">
                    <div class="ppp__self--img">
                        <div class="box-check"></div>
                    </div>
                    <div class="ppp__self--txt">
                        <span class="ppp__self--txt-head">
                            Это ничего не стоит, но очень полезно.
                        </span>
                        <span class="ppp__self--txt-about">
                            Это бесплатно и быстро. Регистрация в качестве самозанятого ни к чему не обязывает,
                            но может пригодиться в самый нужный момент.
                            Возможно, после того, как наберётся нужное количество самозанятых государство может
                            ограничить возможность такой простой регистрации или ввести новые требования.
                        </span>
                    </div>
                  </div>

              </div>
          </div>
        <? endif; ?>
        <hr class="ppp__line">
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
      </div>
    <?php endif; ?>
    <?
    //    LAST PROJECTS
    ?>
    <?php if($viData['lastJobs']['count']>0): ?>
      <hr class="ppp__line">
      <span class="ppp__subtitle">Кол-во отработанных проектов <b class="-green"><?=$viData['lastJobs']['count']?></b></span>
      <hr class="ppp__line">
        <?php for($i=0; $n=$cntComments, $i<$n, $i<3; $i++):
          $work = $viData['lastJobs']['jobs'][$i];
          if(isset($work)): ?>
            <div class="ppp__work-item">
              <div class="ppp-work-item__logo">
                <a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $work['idus']?>">
                  <img src="<?=Share::getPhoto($work['idus'], 3, $work['logo'])?>" alt="Работодатель <?=$work['name']?> prommu.com" class="js-g-hashint" title="<?=$work['name']?>">
                </a>
              </div>
              <div class="ppp-work-item__data">
                <a href="<?=MainConfig::$PAGE_VACANCY . DS . $work['id']?>"><?=$work['title']?></a>
                <span class="ppp-work-item__date"><?=$work['remdate']?></span>
              </div>
            </div>
          <?php endif;
        endfor; ?>
    <?php endif; ?>
    <?php if($flagOwnProfile): ?>
      <hr class="ppp__line">
      <?php if($cntComments): ?>
        <span class="ppp__subtitle">Всего отзывов: <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>"><b class="-green"><?=$cntComments?></b></a></span>
      <?php else: ?>
        <span class="ppp__subtitle">Отзывы отсутствуют</span><br><br>
      <?php endif;?>
      <?php if($cntComments): ?>
        <hr class="ppp__line">
        <div class="upp__reviews-cnt">
          <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__reviews-name">Отрицательных:</a>
          <span class="upp__review upp__review-red">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link"><?=$viData['lastComments']['count'][1]?></a>
          </span>
        </div>
        <div class="upp__reviews-cnt">
          <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__reviews-name">Положительных:</a>
          <span class="upp__review upp__review-green">
            <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link"><?=$viData['lastComments']['count'][0]?></a>
          </span>
        </div>
        <div class="ppp__comments">
          <?php for($i=0; $n=$cntComments, $i<$n, $i<2; $i++):
            $comm = $viData['lastComments']['comments'][$i];
            if(isset($comm)): ?>
            <div class="ppp__comments-item <?=$comm['isneg']?'negative':''?>">
              <div class="ppp__comm-name"><a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $comm['id_user']?>"><?=$comm['fio']?></a> <?=DateTime::createFromFormat('d.m.y', $comm['crdate'])->format('d/m/Y');?></div>
              <div><?=$comm['message']?></div>
            </div>
          <?php endif;
          endfor; ?>
        </div>
        <?php if($cntComments>2): ?>
          <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="ppp__btn ppp__allreview-btn btn__orange">все отзывы</a>
        <?php endif; ?>
      <?php endif; ?>
    <?php endif; ?>
    <?
    //    MAIN INFO
    ?>
    <?php $isBlocked = Share::$UserProfile->exInfo->isblocked==3; ?>
    <div class="ppp__module-title"><h2>ОСНОВНАЯ ИНФОРМАЦИЯ</h2></div>
    <div class="ppp__module">
      <div class="ppp__field<?=($isBlocked && !$attr['firstname'] && $flagOwnProfile?' error':'')?>">
        <span class="ppp__field-name">Имя:</span>
        <span class="ppp__field-val"><?=trim($attr['firstname'])?></span>
      </div>
      <div class="ppp__field<?=($isBlocked && !$attr['lastname'] && $flagOwnProfile?' error':'')?>">
        <span class="ppp__field-name">Фамилия:</span>
        <span class="ppp__field-val"><?=trim($attr['lastname'])?></span>
      </div>
      <? if(!empty($attr['bday'])): ?>
        <div class="ppp__field">
          <span class="ppp__field-name">Дата рождения:</span>
          <span class="ppp__field-val"><?=DateTime::createFromFormat('d.m.Y', $attr['bday'])->format('d/m/Y');?></span>
        </div>
      <? endif; ?>
      <? if($isBlocked && $flagOwnProfile && !$attr['val']): // предупреждение владельца о пустых полях ?>
        <div class="ppp__field error">
          <span class="ppp__field-name">Телефон:</span>
          <span class="ppp__field-val"></span>
        </div>
      <? endif; ?>
      <? if($isBlocked && $flagOwnProfile && !$attr['email']): // предупреждение владельца о пустых полях ?>
        <div class="ppp__field error">
          <span class="ppp__field-name">Email:</span>
          <span class="ppp__field-val"></span>
        </div>
      <? endif; ?>
      <? if(Share::$UserProfile->showContactData($idPromo,'applicant')): // вывод данных для Р, который сотрудничает ?>
        <? if(!empty($attr['phone'])): ?>
          <div class="ppp__field">
            <span class="ppp__field-name">Телефон:</span>
            <span class="ppp__field-val"><?='+' . $attr['phone-code'] . $attr['phone']?></span>
          </div>
        <? endif; ?>
        <? if(!empty($attr['email'])): ?>
          <div class="ppp__field">
            <span class="ppp__field-name">Email:</span>
            <span class="ppp__field-val"><?=$attr['email']?></span>
          </div>
        <? endif; ?>
      <? endif; ?>
      <div class="ppp__field">
        <span class="ppp__field-name">Пол:</span>
        <span class="ppp__field-val"><?=($attr['isman'] ? 'мужской' : 'женский')?></span>
      </div>
      <div class="ppp__checkboxes">
        <div class="ppp__attr-block1">
          <? if($attr['ismed']): ?>
            <span class="ppp__checkbox <?=($attr['ismed'] ? 'active' : '')?>">Медкнижка</span>
          <? endif; ?>
          <? if($attr['ishasavto']): ?>
            <span class="ppp__checkbox <?=($attr['ishasavto'] ? 'active' : '')?>">Автомобиль</span>
          <? endif; ?>
          <? if($attr['smart']): ?>
            <span class="ppp__checkbox <?=($attr['smart'] ? 'active' : '')?>">Смартфон</span>
          <? endif; ?>
        </div>
        <div class="ppp__attr-block2">
          <? if($attr['cardPrommu']): ?>
            <span class="ppp__checkbox <?=($attr['cardPrommu'] ? 'active' : '')?>">Наличие банковской карты Prommu</span>
          <? endif; ?>
          <? if($attr['card']): ?>
            <span class="ppp__checkbox <?=($attr['card'] ? 'active' : '')?>">Наличие другой банковской карты</span>
          <? endif; ?>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    <?
    //    VACANCIES
    ?>
    <div class="ppp__module-title"><h2>ЦЕЛЕВАЯ ВАКАНСИЯ </h2></div>
    <div class="ppp__module ppp__module-posts">
        <?php
        /**
        * Shut down  viewed Target Vacancy
        * 4.06.2019
        */
        if (1==2 ) {
        ?>
            <div class="ppp__field<?=($isBlocked && !$info['userDolj'][1] && $flagOwnProfile?' error':'')?>">
                <span class="ppp__field-name ppp__field-target-vacancy">Целевые вакансии:</span>
                <span class="ppp__field-val ppp__field-target-vacancy"><?=$info['userDolj'][1]?></span>
            </div>
        <?php } ?>

      <?php if(sizeof($arPosts) && $info['userDolj'][1]): ?>
        <div class="ppp__post-list">
          <?php //$i=0;
          ?>
          <?php foreach ($arPosts as $post): ?>
            <div class="ppp__post-item">
              <div class="ppp__post-name"><b>Должность: </b><span><?=$post['val']?></span></div>
              <div class="ppp__field ppp__post-field ppp__post-field-pay">
                <span class="ppp__field-name">Ожидаемая оплата: </span>
                <span class="ppp__field-val"><?=$post['pay']?></span>
                <em>руб</em>
              </div>
              <div class="ppp__field ppp__post-field">
                <span class="ppp__field-name">Оплата за: </span>
                <span class="ppp__field-val"><?=$post['pt']?></span>
              </div>
              <div class="ppp__field ppp__post-field ppp__post-exp">
                <span class="ppp__field-name">Опыт работы:</span>
                <span class="ppp__field-val"><?=$post['pname']?></span>
              </div>
            </div>
            <?php
//                $i++;
//                if($i%2==0):
//                    echo'<div class="ppp__clear-two"></div>';
//                elseif($i%3==0):
//                    echo'<div class="ppp__clear-three"></div>';
//                endif;
            ?>
          <?php endforeach; ?>
          <div class="clearfix"></div>
        </div>
      <?php else: ?>
        <div class="ppp__subtitle">Пока нет вакансий</div><br>
      <?php endif; ?>
    </div>
    <?
    // APPEARANCE
    ?>
    <div class="ppp__module-title"><h2>ВНЕШНИЕ ДАННЫЕ</h2></div>
    <div class="ppp__module">
      <? $empty = true; ?>
      <? if(!empty($info['userAttribs'][9]['val'])): ?>
        <div class="ppp__field">
          <span class="ppp__field-name">Рост:</span>
          <span class="ppp__field-val"><?=$info['userAttribs'][9]['val']?></span>
        </div>
        <? $empty = false; ?>
      <? endif; ?>
      <? if(!empty($info['userAttribs'][10]['val'])): ?>
        <div class="ppp__field">
          <span class="ppp__field-name">Вес:</span>
          <span class="ppp__field-val"><?=$info['userAttribs'][10]['val']?></span>
        </div>
        <? $empty = false; ?>
      <? endif; ?>
      <?
        $arAttrib = array(11=>'Цвет волос',12=>'Длина волос',13=>'Цвет глаз',14=>'Объем груди',15=>'Объем талии',16=>'Объем бедер');
        foreach($arAttrib as $id => $name):?>
        <? if($data = $info['userAttribs'][$this->ViewModel->isInArray($info['userAttribs'], 'idpar', $id)]['name']): ?>
          <div class="ppp__field">
            <span class="ppp__field-name"><?=$name?>:</span>
            <span class="ppp__field-val"><?=$data?></span>
          </div>
          <? $empty = false; ?>
        <? endif; ?>
      <? endforeach; ?>
      <? if($empty): ?>
        <div class="ppp__subtitle">Не заполнено</div>
      <? endif; ?>
      <br>
    </div>
    <?
    // LOCATION AND TIME
    ?>
    <div class="ppp__module-title"><h2>Удобное место и время работы</h2></div>
    <div class="ppp__module">
      <div class="ppp__city-list">
        <?php foreach($info['userCities'][0] as $city): ?>
          <?/*?>
          <div class="ppp__city-title"><b>ГОРОД </b></div>
           <?*/?>
          <div class="ppp__field">
            <span class="ppp__field-name">Город:</span>
            <span class="ppp__field-val"><?=$city['name']?></span>
          </div>
          <?php if($city['ismetro']):?>
            <div class="ppp__field">
              <span class="ppp__field-name">Метро:</span>
              <?php
                if(sizeof($info['userMetro'])){
                  $arMetroes = array();
                  foreach($info['userMetro'][0] as $idMetro => $metro)
                    if($metro['idcity']==$city['id'])
                      $arMetroes[] = $metro['name'];
                }
              ?>
              <span class="ppp__field-val"><?=implode(', ', $arMetroes)?></span>
            </div>
          <? endif; ?>
          <?/*?>
          <h3 class="ppp__cities-title">Дни недели:</h3>
          <div class="ppp__days-checkboxes">
            <?php foreach($arDays as $idDay => $name): ?>
              <div class="ppp__day">
                <span class="ppp__checkbox <?=array_key_exists($idDay, $info['userWdays'][$city['id']]) ? 'active' : ''?>"><?=$name?></span>
              </div>
            <?php endforeach; ?>
          </div>
          <?*/?>
          <div class="ppp__period-list">
            <?php
              if(sizeof($info['userWdays'][$city['id']]))
                foreach($info['userWdays'][$city['id']] as $idDay => $t):?>
                  <? $value = 'С ' . explode(':', $t['timeb'])[0] . ' до ' . explode(':', $t['timee'])[0]?>
                  <div class="ppp__field">
                    <span class="ppp__field-name"><?=$arDays[$idDay]?>, Время дня:</span>
                    <span class="ppp__field-val"><?=$value?></span>
                  </div>
            <?php endforeach; ?>
          </div>
          <div class="clearfix"></div>
        <?php endforeach; ?>
      </div>
    </div>
    <br>
    <?
    // ADDITIONAL INFO
    ?>
    <div class="ppp__module-title"><h2>ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ</h2></div>
    <div class="ppp__module">
      <?php
        $empty = true;
        $name = '';
        foreach($info['userAttribs'] as $val)
          if($val['idpar'] == 69) $name = $val['name'];
      ?>
      <? if(strlen($name)): ?>
        <div class="ppp__field">
          <span class="ppp__field-name">Образование:</span>
          <span class="ppp__field-val"><?=$name?></span>
        </div>
        <? $empty = false; ?>
      <? endif;
      $arLangSmall = $arLangBig = [];
      $cnt=0;
      foreach($info['userAttribs'] as $v)
      {
        if($v['idpar'] == 40)
        {
          $cnt++;
          $cnt>3 ? $arLangBig[]=$v['name'] : $arLangSmall[]=$v['name'];
        }
      }
      if(sizeof($arLangSmall)): ?>
        <div class="ppp__field ppp__field-lang">
          <span class="ppp__field-name">Иностранные языки:</span>
          <div class="ppp__field-val">
            <span><?=implode(', ', $arLangSmall)?></span>
            <? if(count($arLangBig)): ?>
              <em>...</em>
              <span style="display:none"><?=implode(', ', $arLangBig)?></span>
            <? endif; ?>
          </div>
        </div>
        <? $empty = false; ?>
      <? endif; ?>
      <? if(strlen($attr['aboutme']) || ($isBlocked && $flagOwnProfile)): ?>
        <div class="ppp__about<?=($isBlocked && $flagOwnProfile && !$attr['aboutme']?' error':'')?>">
          <span class="ppp__about-name">О себе:</span>
          <div class="ppp__about-val"><?=$attr['aboutme']?></div>
          <? $empty = false; ?>
        </div>
      <? endif; ?>
      <?
        $isNews = false;
        foreach ($info['userAttribs'] as $v)
          $v['key']=='isnews' && $isNews = $v['val'];
      ?>
      <? if($isNews && $flagOwnProfile): ?>
        <div class="ppp__isnews">
          <div class="ppp__checkbox <?=$isNews ? 'active' : ''?>">Получение новостей об изменениях и новых возможностях на сайте</div>
        </div>
      <? endif; ?>
      <? if($empty): ?>
        <div class="ppp__subtitle">Не заполнено</div>
      <? endif; ?>
    </div>
  <div class='center-box'>
    <?php if( $flagOwnProfile ): ?>
      <a class='ppp__btn btn__orange' href='<?= MainConfig::$PAGE_EDIT_PROFILE ?>' style="max-width:250px;margin:0 auto 10px">Редактировать профиль</a>
    <?php endif; ?>
  </div>
  </div>
</div>
  <?/*
  *
  *
  *
  *
  */?>
  <script type="text/javascript">
    $(document).ready(function(){
      G_VARS.App.customProps.idPromo = '<?=$Profile->exInfo->id_resume?>';
    });
  </script>
  <script id="TplInvVacs" type="text/template" data-btn="Пригласить">
      <div class="vac-form">
          <p>Выберите вакансию на которую вы хотите пригласить соискателя</p>
          <label for="CbVacs">
              <b></b>
              <select id="CbVacs">
              </select>
          </label>
      </div>
  </script>

  <script id="TplInvSuccess" type="text/template">
      <p class="message"></p>
  </script>

  <script id="TplInvNoVacs" type="text/template">
      <p class="message">У вас нет активных вакансий. Для создания вакансии перейдите на <a href="<?= MainConfig::$PAGE_VACPUB ?>">эту страницу</a></p>
  </script>
  <template id='TPLAddComment'></template>
<? endif; ?>

<?php //end
    elseif(Share::isApplicant()):
    Yii::app()->getClientScript()->registerCssFile('/theme/css/private/page-prof-personal-area.css');
    // new
?>

<?
    $cookieView = Yii::app()->request->cookies['popup_photo']->value;
    $bigSrc = Share::getPhoto($attr['id_user'], 2, $attr['photo'], 'big', $attr['isman']);
    $src = Share::getPhoto($attr['id_user'], 2, $attr['photo'], 'medium', $attr['isman']);

//    echo date("d.m.Y", strtotime($viData['userInfo']['userAttribs'][1]['mdate']));
//    display($viData);
//    display($cookieView);
//    display($bigSrc);
//    display($src);
//    display($viData['userInfo']);
//    display($viData['userInfo']['rateByUser']);
?>

<div class="row">
    <div class="col-xs-12">
        <div class="personal__area personal__area-flex">
            <div class="personal__area--item">
                <div class="upp__img-block-main">
                    <? if($attr['photo'] && $bigSrc): ?>
                        <a href="<?=$bigSrc?>" class="js-g-hashint upp__img-block-main-link profile__logo-full" title="<?=$h1title?>">
                          <img
                            src="<?=$src?>"
                            alt='Соискатель <?=$attr['lastname']?> prommu.com'
                            class="ppp-logo-main__img">
                        </a>
                        <? else: ?>
                        <img
                          src="<?=$src?>"
                          alt='Соискатель <?=$attr['lastname']?> prommu.com'
                          class="ppp-logo-main__img">
                        <?
                          if($flagOwnProfile && !$cookieView) // предупреждение, что нет фоток
                          {
                            Yii::app()->request->cookies['popup_photo'] = new CHttpCookie('popup_photo', 1);
                            $message = '<p>У вас не загружено еще ни одной фотографии.<br>
                                Добавляйте пожалуйста логотип своей компании или личные фото. 
                                В случае несоответствия фотографий Вы не сможете пройти модерацию! 
                                Спасибо за понимание!</p>';
                            Yii::app()->user->setFlash('prommu_flash', $message);
                          }
                        ?>
                    <? endif; ?>

                    <? if( $flagOwnProfile && $attr['is_online'] ) :?>
                        <a href="<?=MainConfig::$PAGE_EDIT_PROFILE . '?ep=1'?>" class="upp__change-logo">Изменить аватар</a>
                    <?php endif; ?>
                </div>
                <?php if($flagOwnProfile && $attr['is_online']): ?>
                    <span class="upp-logo__item-onl"><span>В сети</span>
                <? endif; ?>
                <?if(!$attr['is_online']):?>
                    <span class="disable">Был<?=$attr['isman']?'':'а'?> на сервисе: <?=date_format(date_create($attr['mdate']), 'd.m.Y');?></span>
            <?endif;?>
            </div>

            <div class="personal__area--item">
                <span class="upp__title">
                    <?=$attr['firstname']?> <?=$attr['lastname']?>
                    <span class="upp__title--date js-g-hashint" title="Дата регистрации">
                        <?= date("d.m.Y", strtotime($viData['userInfo']['userAttribs'][1]['mdate'])); ?>
                    </span>
                </span>
                <div class="upp__rating-block">
                    <span class="upp__subtitle">Общий рейтинг:</span>
                    <div class="upp__subtitle">
                        <?=Share::getRating($attr['rate'],$attr['rate_neg'])?>
                        <span class="upp__info">
                            <i class="icn-info-three"></i>
                            <span class="upp__info--hint">
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
                            </span>
                        </span>
                    </div>

                </div>
            </div>


            <div class="personal__area--item personal__area--item-index">
                <div class="upp__rating-block">
                    <?php if($attr['confirmPhone'] || $attr['confirmEmail']): ?>
                        <div class="confirmed-user js-g-hashint" title="Личность соискателя является подлинной">ПРОВЕРЕН</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="personal__area--item personal__area--item-index">
                <? if ($flagOwnProfile): ?>
                <div class="upp__rating-block">
                    <? if(!empty($info['self_employed'])): ?>
                        <div class="self_employed-user js-g-hashint" title="Налоговый статус соискателя">САМОЗАНЯТЫЙ</div>
                    <? endif; ?>

                    <? // ?>
                    <? if(!$info['self_employed'] && $info['self_employed_region']): ?>
                        <div class="upp__rating-block">
                            <a href="<?=MainConfig::$VIEW_SELF_EMPLOYED?>" class="prmu-btn prmu-btn_normal">
                                <span>Стать самозанятым</span>
                            </a>
                            <? if(!$info['self_employed']): ?>
                                <span class="ppp__self-employed-question ">
                                    <i class="icn-question-two"></i>
                                </span>
                            <? endif; ?>
                            <div class="ppp__self-employed-message prmu__popup" id="self_employed_message">

                                <h3>Причина регистрироваться на Prommu.com под статусом «Самозанятого»</h3>

                                <div class="ppp__self--inner-row">
                                    <div class="ppp__self--img">
                                        <div class="money-check-alt"></div>
                                    </div>
                                    <div class="ppp__self--txt">
                                        <span class="ppp__self--txt-head">
                                            Налог всего 4-6%.
                                        </span>
                                        <span class="ppp__self--txt-about">
                                            Подоходный налог 13% заменён на налог на професиональную деятельность (НПД)
                                            всего 4-6%.
                                        </span>
                                    </div>
                                </div>

                                <div class="ppp__self--inner-row">
                                    <div class="ppp__self--img">
                                        <div class="handshake"></div>
                                    </div>
                                    <div class="ppp__self--txt">
                                        <span class="ppp__self--txt-head">
                                            Никаких "Серых" схем.
                                        </span>
                                        <span class="ppp__self--txt-about">
                                            Полная легализация отношений. Дополнительные возможности для личной защиты
                                            перед законом. Минимизация налоговых рисков.
                                        </span>
                                    </div>
                                </div>

                                <div class="ppp__self--inner-row">
                                    <div class="ppp__self--img">
                                        <div class="credit-card"></div>
                                    </div>
                                    <div class="ppp__self--txt">
                                        <span class="ppp__self--txt-head">
                                            Кредит в банке стало получить проще.
                                        </span>
                                        <span class="ppp__self--txt-about">
                                            Можно предоставить официальную справку о доходах в банк. Справка формируется
                                            в приложении или через веб-кабинет22.
                                        </span>
                                    </div>
                                </div>

                                <div class="ppp__self--inner-row">
                                    <div class="ppp__self--img">
                                        <div class="biking"></div>
                                    </div>
                                    <div class="ppp__self--txt">
                                        <span class="ppp__self--txt-head">
                                            Удобно и никуда не надо ехать.
                                        </span>
                                            <span class="ppp__self--txt-about">
                                            Не нужно посещать налоговую и подавать документы для регистрации самозанятым.
                                            Регистрация осуществляется с мобильного телефона через приложение "Мой налог"
                                            и занимает 20 минут.
                                        </span>
                                    </div>
                                </div>

                                <div class="ppp__self--inner-row">
                                    <div class="ppp__self--img">
                                        <div class="envelop-open-dollar"></div>
                                    </div>
                                    <div class="ppp__self--txt">
                                        <span class="ppp__self--txt-head">
                                            Нет обязательных платежей.
                                        </span>
                                        <span class="ppp__self--txt-about">
                                            Нет прибыли - нет налога. Ничего не теряеете.
                                        </span>
                                    </div>
                                </div>

                                <div class="ppp__self--inner-row">
                                    <div class="ppp__self--img">
                                        <div class="home-heart"></div>
                                    </div>
                                    <div class="ppp__self--txt">
                                        <span class="ppp__self--txt-head">
                                            Доступная ипотека.
                                        </span>
                                            <span class="ppp__self--txt-about">
                                            Подтверждение о доходах самозанятого позволяет банку рассчитать более
                                            оптимальную ставку на ипотеку.
                                        </span>
                                    </div>
                                </div>

                                <div class="ppp__self--inner-row">
                                    <div class="ppp__self--img">
                                        <div class="cut"></div>
                                    </div>
                                    <div class="ppp__self--txt">
                                        <span class="ppp__self--txt-head">
                                            Налоговый вычет 10 000 руб.
                                        </span>
                                        <span class="ppp__self--txt-about">
                                            Возможность получения вычета со ставки 4% в размере 1% и со ставки :% величиной 2% в
                                            пределах 10 тысяч рублей {п.2 ст.12 ФЗ №422}.
                                        </span>
                                    </div>
                                </div>

                                <div class="ppp__self--inner-row">
                                    <div class="ppp__self--img">
                                        <div class="box-check"></div>
                                    </div>
                                    <div class="ppp__self--txt">
                                        <span class="ppp__self--txt-head">
                                            Это ничего не стоит, но очень полезно.
                                        </span>
                                        <span class="ppp__self--txt-about">
                                            Это бесплатно и быстро. Регистрация в качестве самозанятого ни к чему не обязывает,
                                            но может пригодиться в самый нужный момент.
                                            Возможно, после того, как наберётся нужное количество самозанятых государство может
                                            ограничить возможность такой простой регистрации или ввести новые требования.
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <? endif; ?>
                    <? // ?>

                </div>
                <? endif; ?>
            </div>

            <div class="personal__area--item personal__area--item-index">
                <? if ($flagOwnProfile): ?>
                <div class="upp__rating-affective">
                    <div class="upp__subtitle">
                        <span class="upp__subtitle">Эффективность размещения:</span>
                        <span class="upp__info question_popup">
                            <i class="icn-question-two"></i>
                        </span>
                    </div>
                    <div class="personal__area--affective">
                        <div class="personal__area--indicator" style="width: <?= $viData['profile_filling'] ?>%"></div>
                        <div class="personal__area--number"><?= $viData['profile_filling'] ?>%</div>
                    </div>

                    <?//efficiency popup?>
                        <div class="affective__wrap">
                            <?php
                             if ($viData['profile_filling'] != 100) {
                                 ?>
                                 <div class="prmu__popup popup__msg" >
                                    <h3>Уважаемый соискатель!</h3>
                                    <p>Для эффективного размещения профиля необходимо заполнить его в полном объёме.</p>
                                    <p>Информация по незаполнению данных в личном профиле для 100% эффективности.</p>
                                    <ul>
                                        <? if (empty($viData['userInfo']['userAttribs']['1']['photo']) ||
                                              (count($info['userPhotos']) <= 1)) { ?>
                                            <h4>Фото</h4>
                                        <? }
                                        if (empty($viData['userInfo']['userAttribs']['1']['photo'])) { ?>
                                            <li> Основное фото - 10% </li>
                                        <? }
                                        if ( count($info['userPhotos']) <= 1) {
                                            echo '<li> Дополнительные фото - 10% </li>';
                                         }
                                        If (empty($viData['userInfo']['userAttribs']['1']['firstname']) ||
                                            empty($viData['userInfo']['userAttribs']['1']['lastname'])  ||
                                            empty($viData['userInfo']['userAttribs']['1']['bday'])      ||
                                            empty($viData['userInfo']['userAttribs']['1']['isman'])    ) {
                                            echo "<h4>Основная информация</h4>";
                                        }
                                        If (empty($viData['userInfo']['userAttribs']['1']['firstname'])) {
                                            echo '<li> Имя - 1% </li>';
                                        }
                                        If (empty($viData['userInfo']['userAttribs']['1']['lastname'])) {
                                            echo '<li> Фамилия - 1% </li>';
                                        }
                                        If (empty($viData['userInfo']['userAttribs']['1']['bday'])) {
                                            echo '<li> Дата рождения - 1% </li>';
                                        }
                                        If (empty($viData['userInfo']['userAttribs']['1']['isman'])) {
                                            echo '<li> Пол - 1% </li>';
                                        }
                                        if (($viData['userInfo']['userAttribs']['1']['ismed'] != '0' ) ||
                                            ($viData['userInfo']['userAttribs']['1']['smart'] != '0' ) ||
                                            ($viData['userInfo']['userAttribs']['1']['card']  != '0' ) ||
                                            ($viData['userInfo']['userAttribs']['1']['ishasavto']) != '0' ) {
                                        } else {
                                            echo '<li> Доп. данные (авто / мед книга / смартфон / наличие банк карт) - 1%</li>';
                                        }
                                        ?>
                                        <?
                                        If (empty($viData['userInfo']['userAttribs']['1']['phone']) ||
                                            empty($viData['userInfo']['userAttribs']['1']['email'])

                                            ) {
                                            echo'<h4>Контактная информация</h4>';
                                            }

                                        If (empty($viData['userInfo']['userAttribs']['1']['phone'])) {
                                            echo '<li> Телефон -5% </li>';
                                        }
                                        If (empty($viData['userInfo']['userAttribs']['1']['email'])) {
                                            echo '<li> Е-меил - 5% </li>';
                                        }
                                        if (!empty($viData['userInfo']['userAttribs']['4']['val'])   || //messangers
                                            !empty($viData['userInfo']['userAttribs']['157']['val']) ||
                                            !empty($viData['userInfo']['userAttribs']['156']['val']) ||
                                            !empty($viData['userInfo']['userAttribs']['158']['val']) ||
                                            !empty($viData['userInfo']['userAttribs']['5']['val'])) {
                                        } else {
                                            echo '<li> Мессенджеры - 5% </li>';
                                        }
                                        if (!empty($viData['userInfo']['userAttribs']['6']['val'])   || //social networks
                                            !empty($viData['userInfo']['userAttribs']['160']['val']) ||
                                            !empty($viData['userInfo']['userAttribs']['161']['val']) ||
                                            !empty($viData['userInfo']['userAttribs']['162']['val']) ||
                                            !empty($viData['userInfo']['userAttribs']['7']['val'])) {
                                        } else {
                                            echo '<li> Социальные сети  - 5% </li>';
                                        }
                                        ?>
                                        <?
                                        //echo sizeof($viData['userInfo']['userDolj']['0']);
                                        If (sizeof($viData['userInfo']['userDolj']['0']) == 0 ) { //заполнены поля должностей
                                            echo '<h4>Целевая вакансия - 5%</h4>';
                                            echo '<li> Должность </li>';
                                            echo '<li> Ожидаемая оплата </li>';
                                            echo '<li> Опыт работы </li>';
                                        }

                                        If ((sizeof($viData['userInfo']['userCities']['0']) == 0) ||
                                            (sizeof($viData['userInfo']['userWdays']) == 0) ) {
                                            echo '<h4>Удобное место и время работы</h4>';
                                        }
                                        If ((sizeof($viData['userInfo']['userCities']['0']) == 0)) {
                                            echo '<li> Город - 1% </li>';
                                        }
                                        If (sizeof($viData['userInfo']['userWdays']) == 0) {
                                            echo '<li> Удобное время работы - 4% </li>';
                                        }
                                        If (isset($viData['userInfo']['userAttribs']['9']['val'])  ||
                                            isset($viData['userInfo']['userAttribs']['10']['val']) ||
                                            isset($viData['userInfo']['userAttribs']['17']['name'])||
                                            isset($viData['userInfo']['userAttribs']['18']['name'])||
                                            isset($viData['userInfo']['userAttribs']['19']['name'])||
                                            isset($viData['userInfo']['userAttribs']['20']['name'])||
                                            isset($viData['userInfo']['userAttribs']['21']['name'])||
                                            isset($viData['userInfo']['userAttribs']['22']['name'])||
                                            isset($viData['userInfo']['userAttribs']['23']['name'])||
                                            isset($viData['userInfo']['userAttribs']['24']['name'])||
                                            isset($viData['userInfo']['userAttribs']['25']['name'])||
                                            isset($viData['userInfo']['userAttribs']['26']['name'])||
                                            isset($viData['userInfo']['userAttribs']['27']['name'])||
                                            isset($viData['userInfo']['userAttribs']['28']['name'])||
                                            isset($viData['userInfo']['userAttribs']['29']['name'])||
                                            isset($viData['userInfo']['userAttribs']['30']['name'])||
                                            isset($viData['userInfo']['userAttribs']['75']['name'])||
                                            isset($viData['userInfo']['userAttribs']['76']['name'])||
                                            isset($viData['userInfo']['userAttribs']['77']['name'])||
                                            isset($viData['userInfo']['userAttribs']['78']['name'])||
                                            isset($viData['userInfo']['userAttribs']['79']['name'])||
                                            isset($viData['userInfo']['userAttribs']['80']['name'])||
                                            isset($viData['userInfo']['userAttribs']['81']['name'])||
                                            isset($viData['userInfo']['userAttribs']['82']['name'])||
                                            isset($viData['userInfo']['userAttribs']['83']['name'])||
                                            isset($viData['userInfo']['userAttribs']['84']['name'])||
                                            isset($viData['userInfo']['userAttribs']['85']['name'])||
                                            isset($viData['userInfo']['userAttribs']['86']['name'])||
                                            isset($viData['userInfo']['userAttribs']['87']['name'])||
                                            isset($viData['userInfo']['userAttribs']['88']['name'])||
                                            isset($viData['userInfo']['userAttribs']['89']['name'])||
                                            isset($viData['userInfo']['userAttribs']['90']['name'])||
                                            isset($viData['userInfo']['userAttribs']['91']['name'])||
                                            isset($viData['userInfo']['userAttribs']['92']['name'])||
                                            isset($viData['userInfo']['userAttribs']['93']['name'])||
                                            isset($viData['userInfo']['userAttribs']['94']['name'])||
                                            isset($viData['userInfo']['userAttribs']['95']['name'])||
                                            isset($viData['userInfo']['userAttribs']['96']['name'])||
                                            isset($viData['userInfo']['userAttribs']['97']['name']) ) {
                                        }else{
                                            echo '<h4>Внешние данные</h4>';
                                        }
                                        If (empty($viData['userInfo']['userAttribs']['9']['val'])) {
                                            echo '<li>Рост - 3% </li>';
                                        }
                                        If (empty($viData['userInfo']['userAttribs']['10']['val'])) {
                                            echo '<li>Вес - 3% </li>';
                                        }
                                        If (isset($viData['userInfo']['userAttribs']['17']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['18']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['19']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['20']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['21']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['22']['name']) ) {
                                        } else {
                                            echo '<li>Цвет волос - 3% </li>';
                                        }
                                        If (isset($viData['userInfo']['userAttribs']['75']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['76']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['77']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['78']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['79']['name'])) {
                                        }else{
                                            echo '<li>Длина волос - 3% </li>';
                                        }
                                        If (isset($viData['userInfo']['userAttribs']['23']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['24']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['25']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['26']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['27']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['28']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['29']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['30']['name']) ) { //8
                                        }else{
                                            echo '<li>Цвет глаз  - 3% </li>';
                                        }
                                        If (isset($viData['userInfo']['userAttribs']['80']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['81']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['82']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['83']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['84']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['85']['name']) ) { //6
                                        }else{
                                            echo '<li>Размер груди - 3% </li>';
                                        }
                                        If (isset($viData['userInfo']['userAttribs']['86']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['87']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['88']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['89']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['90']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['91']['name']) ) { //6
                                        }else{
                                            echo '<li>Объем талии  - 3% </li>';
                                        }
                                        If (isset($viData['userInfo']['userAttribs']['92']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['93']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['94']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['95']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['96']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['97']['name']) ) { //6
                                        }else{
                                            echo '<li>Объем бедер  - 3% </li>';
                                        }
                                        ?>
                                        <?
                                        If (empty($viData['userInfo']['userAttribs']['1']['aboutme'])) {
                                            echo '<h4>Дополнительная информация</h4>';
                                        }
                                        If (isset($viData['userInfo']['userAttribs']['70']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['71']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['72']['name']) ||
                                            isset($viData['userInfo']['userAttribs']['73']['name']) ) { //obrazovanie
                                        } else {
                                            echo '<li>Образование - 5%</li>';
                                        }
                                        If (empty($viData['userInfo']['userAttribs']['1']['aboutme']))  {
                                            echo '<li>О себе - 11%</li>';
                                        }

                                        for($i=41; $i<69; $i++){ //language
                                            $isLang = false;
                                            if ($viData['userInfo']['userAttribs'][$i]['name']){
                                                $isLang = true;
                                            }
                                            If ($isLang) {
                                                break;
                                            }else {
                                                If ($i==68){
                                                    echo '<li>Иностранные языки - 5%</li>';
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                             <? } ?>
                        </div>
                    <?//end efficiency popup?>

                </div>
                <? endif; ?>
            </div>

        </div>
    </div>
</div>

<div class="personal__area--separator"></div>

<div class="row personal__area personal__area-flex">
    <?//1th column?>
    <?php $isBlocked = Share::$UserProfile->exInfo->isblocked==3; ?>
    <div class="col-xs-12 col-sm-4 personal__area--flex-column" >
        <div class="personal__area--capacity">
            <div class="personal__area--capacity-name">
                Основная информация
            </div>

            <?php if ($flagOwnProfile) :?>
                <a href="<?= MainConfig::$PAGE_EDIT_PROFILE ?>"
                   class="personal__area--capacity-edit js-g-hashint"
                   title="Редактировать профиль">
                </a>
            <? endif; ?>

            <div class="group">
                <div class="group__about">Имя</div>
                <div class="group__info"><?=trim($attr['firstname'])?></div>
            </div>

            <div class="group">
                <div class="group__about">Фамилия</div>
                <div class="group__info"><?=trim($attr['lastname'])?></div>
            </div>

            <div class="group">
                <div class="group__about">Дата рождения</div>
                <div class="group__info"><?=DateTime::createFromFormat('d.m.Y', $attr['bday'])->format('d/m/Y');?></div>
            </div>

            <div class="group">
                <div class="group__about">Пол</div>
                <div class="group__info"><?=($attr['isman'] ? 'мужской' : 'женский')?></div>
            </div>

            <? if($attr['ismed']): ?>
                <div class="group">
                    <div class="group__about">Медкнижка</div>
                    <div class="group__info"><?=($attr['ismed'] ? 'В наличии' : '')?></div>
                </div>
            <? endif; ?>
            <? if($attr['ishasavto']): ?>
                <div class="group">
                    <div class="group__about">Автомобиль</div>
                    <div class="group__info"><?=($attr['ishasavto'] ? 'В наличии' : '')?></div>
                </div>
            <? endif; ?>
            <? if($attr['smart']): ?>
                <div class="group">
                    <div class="group__about">Смартфон</div>
                    <div class="group__info"><?=($attr['smart'] ? 'В наличии' : '')?></div>
                </div>
            <? endif; ?>

            <? if($attr['cardPrommu']): ?>
                <div class="group">
                    <div class="group__about">Наличие банковской карты Prommu</div>
                    <div class="group__info"><?=($attr['cardPrommu'] ? 'В наличии' : '')?></div>
                </div>
            <? endif; ?>

            <? if($attr['card']): ?>
                <div class="group">
                    <div class="group__about">Наличие другой банковской карты</div>
                    <div class="group__info"><?=($attr['card'] ? 'В наличии' : '')?></div>
                </div>
            <? endif; ?>

        </div>

        <div class="personal__area--capacity">
            <div class="personal__area--capacity-name">
                Оценки
            </div>

            <div class="group">
            <?php
            $i=0;
            ?>
            <?php
            if(count($viData['userInfo']['rateByUser'])){
                foreach ($viData['userInfo']['rateByUser'] as $key => $val): ?>
                    <? if ($i>=4) break; ?>
                    <div class="group__line">
                        <? $arItem = reset($val); ?>
                        <?= $arItem['name']; ?>
                        <div class="group__wrap">
                            <?php foreach ($val as $k => $item): ?>
                                <span
                                        class="group__point <?= "p" . $item['point'] ?> js-g-hashint -js-g-hinttop"
                                        title="Оценка
                                                <?= $viData['rating']['rateNames'][$k] ?>
                                                <?= (int)$item['point'] === 1 ? 'положительная' : ((int)$item['point'] === 0 ? 'нейтральная' : 'отрицательная') ?>
                                        ">
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php
                    $i++;
                endforeach;
            } else { ?>
                <div class="group">
                    <span class="upp__subtitle">
                        Оценки отсутствуют
                    </span>
                </div>
            <? } ?>

            </div>
            <div class="personal__area--button">
                <a href="/rate" class="btn__orange">
                    Подробнее...
                </a>

            </div>
        </div>

        <div class="personal__area--capacity">
            <div class="personal__area--capacity-name">
                Отзывы
            </div>

            <?php if($flagOwnProfile): ?>

                <div class="personal__area--capacity-data">
                    <?php if($cntComments): ?>
                    <span class="ppp__subtitle">
                        <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link js-g-hashint" title="Всего отзывов">
                            <b class="-green"><?=$cntComments?></b>
                        </a>
                    </span>
                    (
                    <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link -green js-g-hashint" title="Положительных отзывов">
                        <?=$viData['lastComments']['count'][0]?>
                    </a>
                    /
                    <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="upp__link red js-g-hashint" title="Отрицательных отзывов">
                        <?=$viData['lastComments']['count'][1]?>
                    </a>
                    )
                    <?endif?>
                </div>

                <?php if(!$cntComments): ?>
                    <span class="ppp__subtitle">Отзывы отсутствуют</span>
                    <div class="personal__area--button">
                        <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="btn__orange">
                            Подробнее...
                        </a>
                    </div>
                <?php endif;?>

                <?php if($cntComments): ?>
                    <div class="ppp__comments">
                        <?php for($i=0; $n=$cntComments, $i<$n, $i<2; $i++):
                            $comm = $viData['lastComments']['comments'][$i];
                            if(isset($comm)): ?>
                                <div class="ppp__comments-item <?=$comm['isneg']?'negative':''?>">
                                    <div class="ppp__comm-name"><a href="<?=MainConfig::$PAGE_PROFILE_COMMON . DS . $comm['id_user']?>"><?=$comm['fio']?></a> <?=DateTime::createFromFormat('d.m.y', $comm['crdate'])->format('d/m/Y');?></div>
                                    <div>
                                        <?=mb_strimwidth($comm['message'], 0, 100, "...");?>
                                    </div>
                                </div>
                            <?php endif;
                        endfor; ?>
                    </div>
                    <hr class="upp__line">
                    <div class="personal__area--button">
                        <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="btn__orange">
                            Подробнее...
                        </a>
                    </div>
                <?php endif; ?>

            <? else: ?>
                <div class="group">
                    <span class="upp__subtitle">
                        Отзывы отсутствуют
                    </span>
                </div>
                <div class="personal__area--button">
                    <a href="<?=DS.MainConfig::$PAGE_COMMENTS.DS.$idus?>" class="btn__orange">
                        Подробнее...
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <?//2th column?>
    <div class="col-xs-12 col-sm-4 personal__area--flex-column" >
        <div class="personal__area--capacity">
            <div class="personal__area--capacity-name">
                Удобное место и время работы
            </div>

            <?php if ($flagOwnProfile) :?>
                <a href="<?= MainConfig::$PAGE_EDIT_PROFILE ?>"
                   class="personal__area--capacity-edit js-g-hashint"
                   title="Редактировать профиль">
                </a>
            <? endif; ?>

            <?php foreach($info['userCities'][0] as $city): ?>
                <? if ($city['name']): ?>
                    <div class="group">
                        <div class="group__about">Город</div>
                        <div class="group__info"><?=$city['name']?></div>
                    </div>
                <? endif; ?>
                <?php if($city['ismetro']):
                    if(sizeof($info['userMetro'])){
                        $arMetroes = array();
                        foreach($info['userMetro'][0] as $idMetro => $metro)
                            if($metro['idcity']==$city['id'])
                                $arMetroes[] = $metro['name'];
                    }
                    if ($arMetroes):
                        ?>
                        <div class="group">
                            <div class="group__about">Метро</div>

                            <?
                            if (count($arMetroes)<=2): ?>
                                <div class="group__info"><?=implode(', ', $arMetroes)?></div>
                            <?
                            endif;
                            ?>

                            <?
                            if (count($arMetroes)>2): ?>
                                <div class="group__info">
                                    <?=$arMetroes[1].', '?>
                                    <?=$arMetroes[2].'... '?>
                                    <div class="group__info--drop">
                                        <?=implode(', ', $arMetroes)?>
                                    </div>
                                </div>
                            <?
                            endif;
                            ?>
                        </div>
                    <? endif; ?>
                <? endif; ?>
            <?
            if(sizeof($info['userWdays'][$city['id']])):
                echo '<div class="group">';
                echo '<div class="group__about">Дни недели</div>';
                    foreach($info['userWdays'][$city['id']] as $idDay => $t):?>
                    <? $value = 'С ' . explode(':', $t['timeb'])[0] . ' до ' . explode(':', $t['timee'])[0]?>
                    <div class="group__info">
                        <span><?=$arDays[$idDay]?>, Время дня:</span>
                        <span><?=$value?></span>
                    </div>
                    <?php endforeach;
                echo '</div>';
            endif; ?>

            <?endforeach;?>
        </div>

        <div class="personal__area--capacity">
            <div class="personal__area--capacity-name">
                Внешние данные
            </div>

            <?php if ($flagOwnProfile) :?>
                <a href="<?= MainConfig::$PAGE_EDIT_PROFILE ?>"
                   class="personal__area--capacity-edit js-g-hashint"
                   title="Редактировать профиль">
                </a>
            <? endif; ?>

            <? $empty = true; ?>
            <? if(!empty($info['userAttribs'][9]['val'])): ?>
                <div class="group">
                    <span class="group__about--<?=$info['userAttribs'][9]['id_attr']?>"></span>
                    <span class="group__about">Рост:</span>
                    <span class="group__info--view"><?=$info['userAttribs'][9]['val']?></span>
                </div>
                <? $empty = false; ?>
            <? endif; ?>
            <? if(!empty($info['userAttribs'][10]['val'])): ?>
                <div class="group">
                    <span class="group__about--<?=$info['userAttribs'][10]['id_attr']?>"></span>
                    <span class="group__about">Вес:</span>
                    <span class="group__info--view"><?=$info['userAttribs'][10]['val']?></span>
                </div>
                <? $empty = false; ?>
            <? endif; ?>
            <?
            $arAttrib = array(11=>'Цвет волос',12=>'Длина волос',13=>'Цвет глаз',14=>'Объем груди',15=>'Объем талии',16=>'Объем бедер');
            foreach($arAttrib as $id => $name):?>
                <? if($data = $info['userAttribs'][$this->ViewModel->isInArray($info['userAttribs'], 'idpar', $id)]['name']): ?>
                    <div class="group">
                        <span class="group__about--<?=$id?>"></span>
                        <span class="group__about"><?=$name?>:</span>
                        <span class="group__info--view"><?=$data?></span>
                    </div>
                    <? $empty = false; ?>
                <? endif; ?>
            <? endforeach; ?>
            <? if($empty): ?>
                <div class="ppp__subtitle">Не заполнено</div>
            <? endif; ?>

        </div>

        <div class="personal__area--capacity">
            <div class="personal__area--capacity-name">
                Дополнительная информация
            </div>

            <?php if ($flagOwnProfile) :?>
                <a href="<?= MainConfig::$PAGE_EDIT_PROFILE ?>"
                   class="personal__area--capacity-edit js-g-hashint"
                   title="Редактировать профиль">
                </a>
            <? endif; ?>

            <?php
            $empty = true;
            $name = '';
            foreach($info['userAttribs'] as $val)
                if($val['idpar'] == 69) $name = $val['name'];
            ?>

            <? if(strlen($name)): ?>
                <div class="group">
                    <div class="group__about">Образование:</div>
                    <div class="group__info"><?=$name?></div>
                </div>
            <? $empty = false; ?>
            <? endif;
                $arLangSmall = $arLangBig = [];
                $cnt=0;
            ?>
            <?php
            foreach($info['userAttribs'] as $v)
            {
            if($v['idpar'] == 40)
            {
            $cnt++;
            $cnt>3 ? $arLangBig[]=$v['name'] : $arLangSmall[]=$v['name'];
            }
            }
            if(sizeof($arLangSmall)): ?>
            <div class="group">
                <div class="group__about">Иностранные языки</div>
                <div class="group__info">
                    <span><?=implode(', ', $arLangSmall)?></span>
                    <? if(count($arLangBig)): ?>
                        <em>...</em>
                        <span style="display:none"><?=implode(', ', $arLangBig)?></span>
                    <? endif; ?>
                </div>
            </div>
            <? $empty = false; ?>
            <? endif; ?>
            <? if(strlen($attr['aboutme']) || ($isBlocked && $flagOwnProfile)): ?>
                <div class="group <?=($isBlocked && $flagOwnProfile && !$attr['aboutme']?' error':'')?>">
                    <span class="group__about">О себе</span>
                    <div class="group__info"><?=$attr['aboutme']?></div>
                    <? $empty = false; ?>
                </div>
            <? endif; ?>
            <?
            $isNews = false;
            foreach ($info['userAttribs'] as $v)
                $v['key']=='isnews' && $isNews = $v['val'];
            ?>
            <? if($isNews && $flagOwnProfile): ?>
                <div class="ppp__isnews">
                    <div class="ppe__checkbox <?=$isNews ? 'active' : ''?>">Получение новостей об изменениях и новых возможностях на сайте</div>
                </div>
            <? endif; ?>
            <? if($empty): ?>
                <div class="ppp__subtitle">Не заполнено</div>
            <? endif; ?>

        </div>
    </div>

    <?//3th xolumn?>
    <div class="col-xs-12 col-sm-4" >
        <div class="personal__area--capacity personal__area--capacity-height">
            <div class="personal__area--capacity-name">
                Целевая вакансия
            </div>

            <div class="personal__area--capacity-scroll">
            <?php if(sizeof($arPosts) && $info['userDolj'][1]): ?>

                <?php foreach ($arPosts as $post): ?>
                    <div class="personal__area--vac">
                        <div class="personal__area--vac-item">
                            <span class="personal__area--vac-name">
                                Должность:
                            </span>
                            <span class="personal__area--vac-value">
                                <?=$post['val']?>
                            </span>
                        </div>
                        <div class="personal__area--vac-item">
                            <span class="personal__area--vac-name">
                                Ожидаемая оплата:
                            </span>
                            <span class="personal__area--vac-value">
                                <?=$post['pay']?>
                            </span>
                        </div>
                        <div class="personal__area--vac-item">
                            <span class="personal__area--vac-name">
                                Оплата за:
                            </span>
                            <span class="personal__area--vac-value">
                                <?=$post['pt']?>
                            </span>
                        </div>
                        <div class="personal__area--vac-item">
                            <span class="personal__area--vac-name">
                                Опыт работы:
                            </span>
                            <span class="personal__area--vac-value">
                                <?=$post['pname']?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div>Пока нет вакансий</div>
            <? endif; ?>
            </div>

        </div>

    </div>



</div>

<?php
endif;
//end
?>
<? /* ?>



 <? */ ?>