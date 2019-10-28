<script src="https://www.google.com/jsapi"></script>
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
  Yii::app()->getClientScript()->registerCssFile('/theme/css/private/page-prof-app.css');
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
    if($attr['index'] || $viData['profileEffect']<40 || !$ismoder){
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
      <?php if($attr['confirmPhone'] || $attr['confirmEmail']): ?>
        <div class="confirmed-user js-g-hashint" title="Личность соискателя является подлинной">ПРОВЕРЕН</div>
      <?php endif; ?>
      <? if(!empty($info['self_employed'])): ?>
        <div class="self_employed-user js-g-hashint" title="Налоговый статус соискателя">САМОЗАНЯТЫЙ</div>
      <? endif; ?>
      <?php if($cntComments): ?>
        <div class="ppp__logo-comm">
          <span class="ppp__subtitle">Отзывы:</span>
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


      <?php if( $flagOwnProfile ): ?>
        <div class='affective-block'>
          <div class='affective-perc'>
            <div class='progr' style="width: <?= $viData['profileEffect'] ?>%">
              <div class='text'><?= $viData['profileEffect'] ?>%</div>
            </div>
          </div>
          <div class='affective'>Эффективность<br/>размещения</div>
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
        <? if(!$info['self_employed']): ?>
          <div class="ppp__self-employed">
              <a href="<?=MainConfig::$VIEW_SELF_EMPLOYED?>" class="prmu-btn prmu-btn_normal">
                <span>Стать самозанятым</span>
              </a>
              <? if(!$info['self_employed']): ?>
                  <span class="ppp__self-employed-question prmu-btn prmu-btn_normal"><span>?</span></span>
              <? endif; ?>
              <div class="ppp__self-employed-message prmu__popup" id="self_employed_message">
                <h3>Причина регистрироваться на Prommu.com под статусом «Самозанятого»</h3>
                <p>В Госдуме РФ еще в ноябре 2018 года был принят законопроект, позволяющий гражданам, работающим на себя и не являющихся ИП, выйти из «серой зоны» и выполнять свою конституционную обязанность по уплате налогов с доходов. Prommu.com как один из крупнейших порталов поиска временной работы в Москве поддерживает эту инициативу. Мы предоставляем возможность зарегистрироваться на нашем портале под статусом «Самозанятого» сотрудника. Это откроет для вас перспективы более честного и солидного заработка.</p>
                <h3>Аргументы в пользу регистрации под статусом «Самозанятого»</h3>
                <p>Выгоды от такого решения очевидны:</p>
                <ul>
                  <li>Выполнение конституционной обязанности по уплате налогов.</li>
                  <li>Инвестиции в собственное будущее – отчисления в пенсионный и социальный фонды.</li>
                  <li>Повышенное доверие со стороны работодателей.</li>
                  <li>Более широкий выбор вакансий.</li>
                  <li>Статусное преимущество перед другими соискателями.</li>
                </ul>
                <p>Налоги – неотъемлемый аспект благосостояния общества. Для самозанятых сотрудников налоговая ставка составляет всего 4% и 6% при обслуживании физических и юридических лиц соответственно. При этом львиная доля отчислений направляется на социальное страхование и пенсионное обеспечение. Регистрируясь на Prommu.com под статусом «Самозанятого» сотрудника, вы раскрываете перед собой перспективы множество возможностей получения стабильного дохода от участия в интересных проектах. Больше вакансий, выше доверие работодателей, легализация своей деятельности – удобно, выгодно, современно!</p>
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
          <span class="ppp__checkbox <?=($attr['ismed'] ? 'active' : '')?>">Медкнижка</span>
          <span class="ppp__checkbox <?=($attr['ishasavto'] ? 'active' : '')?>">Автомобиль</span>
          <span class="ppp__checkbox <?=($attr['smart'] ? 'active' : '')?>">Смартфон</span>
        </div>
        <div class="ppp__attr-block2">
          <span class="ppp__checkbox <?=($attr['cardPrommu'] ? 'active' : '')?>">Наличие банковской карты Prommu</span>
          <span class="ppp__checkbox <?=($attr['card'] ? 'active' : '')?>">Наличие другой банковской карты</span>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
    <?
    //    VACANCIES
    ?>
    <div class="ppp__module-title"><h2>ЦЕЛЕВАЯ ВАКАНСИЯ </h2></div>
    <div class="ppp__module">
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
              <div class="ppp__field ppp__post-field">
                <span class="ppp__field-name">Ожидаемая оплата: </span>
                <span class="ppp__field-val"><?=$post['pay']?></span>
                <em>руб</em>
              </div>
              <div class="ppp__field ppp__post-field">
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
          <div class="ppp__city-title"><b>ГОРОД </b></div>
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
          <h3 class="ppp__cities-title">Дни недели:</h3>
          <div class="ppp__days-checkboxes">
            <?php foreach($arDays as $idDay => $name): ?>
              <div class="ppp__day">
                <span class="ppp__checkbox <?=array_key_exists($idDay, $info['userWdays'][$city['id']]) ? 'active' : ''?>"><?=$name?></span>
              </div>
            <?php endforeach; ?>
          </div>
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
      $arRes = array();
      foreach($info['userAttribs'] as $val)
        if($val['idpar'] == 40) $arRes[] = $val['name'];
      if(sizeof($arRes)): ?>
        <div class="ppp__field">
          <span class="ppp__field-name">Иностранные языки:</span>
          <span class="ppp__field-val"><?=implode(', ', $arRes)?></span>
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