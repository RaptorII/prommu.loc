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

foreach ($arCities as $city)
{
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
// posotions
$arPosts = array();
$strPosts = '';
foreach($viData['posts'] as $val)
{
  $arPosts[$val['id']] = $val;
  $arPosts[$val['id']]['newname'] = ($val['key']=='custpo' ? ('- '.$val['val'].' -') : $val['val']);

  $arPosts[$val['id']]['checked'] = '';
  if($val['isshow1'] && !$_GET['npopup']) // если это не после модального окна, то проверяем
    $arPosts[$val['id']]['checked'] = "checked";

  if($arPosts[$val['id']]['checked']!='')
    $strPosts .= ($strPosts==''? '' : ',') . $arPosts[$val['id']]['newname'];
}
$arPayment = array();
foreach ($viData['userInfo']['userDolj'][0] as $val)
{
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
// appearance
$arAppear = array(11=>'hcolor',12=>'hlen',13=>'ycolor',14=>'chest',15=>'waist',16=>'thigh');
$arAppearName = array(11=>'Цвет волос',12=>'Длина волос',13=>'Цвет глаз',14=>'Размер груди',15=>'Объем талии',16=>'Объем бедер');
$arDays = array(1=>'ПН', 2=>'ВТ', 3=>'СР', 4=>'ЧВ', 5=>'ПТ', 6=>'СБ', 7=>'ВС');
// Телефон
if(!$_GET['phone'] && !$attr['phone-code'])
{ // закрыли попап не сохранив
  $city = (new Geo())->getUserGeo();
  foreach($viData['countries'] as $c)
    if($c['id_co']==$city['country'])
      $attr['phone-code'] = $c['phone'];
}
else if($_GET['phone'])
{  // пошли через попап
  $attr['phone'] = urldecode($_GET['phone']);
  $attr['phone'] = urldecode($_GET['phone']);
  $attr['phone-code'] = $_GET['__phone_prefix'];
}
//  email
$attr['email'] = filter_var($attr['email'], FILTER_VALIDATE_EMAIL);
// additional phones
$arAdPhones = array();
foreach($attrAll as $p)
{
  if(strpos($p['name'], 'admob')!==false && !empty($p['val']))
    $arAdPhones[] = $p;
}
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
          <div class="col-xs-12 col-sm-6 col-lg-4 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Имя:</b>
              <input type="text" name="name" value="<?=trim($attr['firstname'])?>" class="profile__field-input epa__required" data-name="Имя" autocomplete="off">
            </label>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-4 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Фамилия:</b>
              <input type="text" name="lastname" value="<?=trim($attr['lastname'])?>" class="profile__field-input epa__required" data-name="Фамилия" autocomplete="off">
            </label>
          </div>
          <div class="col-xs-12 col-sm-6 col-lg-4 profile__col">
            <div class="profile__field epa__date epa__select <?=((($attr['bday']=='01.01.1970')||($attr['bday']==''))?' error':'')?>">
              <b class="profile__field-name">Дата рождения:</b>
              <input
                type="text"
                name="bdate"
                id="birthday"
                autocomplete="off"
                value="<?=($attr['bday']=='01.01.1970'?'':$attr['bday'])?>"
                class="profile__field-input">
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-xs-12 col-lg-7">
            <div class="row">
              <div class="col-xs-12 col-sm-4 profile__col">
                <div class="row">
                  <div class="col-xs-6 col-sm-12">
                    <input type="radio" name="sex" id="epa-male" class="epa__hidden" value="1" <?=($attr['isman'] ? 'checked' : '')?>>
                    <label class="epa__checkbox" for="epa-male">Мужчина</label>
                  </div>
                  <div class="col-xs-6 col-sm-12">
                    <input type="radio" name="sex" id="epa-female" class="epa__hidden" value="0" <?=($attr['isman'] ? '' : 'checked')?>>
                    <label class="epa__checkbox epa__checkbox-famale" for="epa-female">Женщина</label>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4 profile__col">
                <div class="row">
                  <div class="col-xs-6 col-sm-12">
                    <input type="checkbox" name="hasmedbook" id="epa-med" class="epa__hidden" value="1" <?=($attr['ismed'] ? 'checked' : '')?>>
                    <label class="epa__checkbox" for="epa-med">Медкнижка</label>
                  </div>
                  <div class="col-xs-6 col-sm-12">
                    <input type="checkbox" name="smart" id="epa-smart" class="epa__hidden" value="1" <?=($attr['smart'] ? 'checked' : '')?>>
                    <label class="epa__checkbox" for="epa-smart">Смартфон</label>
                  </div>
                </div>
              </div>
              <div class="col-xs-12 col-sm-4 profile__col">
                <input type="checkbox" name="hasavto" id="epa-auto" class="epa__hidden" value="1" <?=($attr['ishasavto'] ? 'checked' : '')?>>
                <label class="epa__checkbox" for="epa-auto">Автомобиль</label>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-lg-5">
            <div class="row">
              <div class="col-xs-12 profile__col">
                <input type="checkbox" name="promm" id="epa-pcard" class="epa__hidden" value="1" <?=($attr['cardPrommu'] ? 'checked' : '')?>>
                <label class="epa__checkbox" for="epa-pcard">Наличие банковской карты Prommu</label>
                <input type="checkbox" name="card" id="epa-card" class="epa__hidden" value="1" <?=($attr['card'] ? 'checked' : '')?>>
                <label class="epa__checkbox" for="epa-card">Наличие другой банковской карты</label>
              </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <br>
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
          <div id="phone-block">
            <div class="col-xs-12 col-sm-6 profile__col">
              <div class="profile__field" id="phone-field">
                <b class="profile__field-name">Телефон:</b>
                <input type='text' name='user-attribs[mob]' value="<?=$attr['phone']?>" class="profile__field-input" id="phone-code" autocomplete="off">
                <? if(count($arAdPhones)<10): ?>
                  <span class="epa__add-phone-btn js-g-hashint" title="Добавить еще телефон" id="add_phone">+</span>
                <? endif; ?>
                <? /*if($attr['confirmPhone']): ?>
                  <span class="epa__confirm complete js-g-hashint" title="Телефон подтвержден"></span>
                <? else: ?>
                  <span class="epa__confirm js-g-hashint" title="Телефон не подтвержден"></span>
                <? endif;*/ ?>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 profile__col<?=($attr['confirmPhone']?' d-none':'')?>">
              <div class="prmu-btn prmu-btn_normal">
                <span>Подтвердить телефон</span>
              </div>
            </div>
            <div class="clearfix<?=($attr['confirmEmail']?' d-none':'')?>"></div>
          </div>
          <div id="phone-confirm" class="d-none">
            <div class="col-xs-12 profile__col">
              <b>На Ваш телефон выслан код для подтверждения. Введите его в это поле!</b>
            </div>
            <div class="col-xs-12 col-sm-6 profile__col">
              <div class="profile__field">
                <b class="profile__field-name">Код:</b>
                <input type='text' name='email-code' class="profile__field-input" maxlength="4">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 profile__col">
              <div class="prmu-btn prmu-btn_normal">
                <span>Подтвердить</span>
              </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div id="email-block">
            <div class="col-xs-12 col-sm-6 profile__col">
              <div class="profile__field" id="email-field" data-error="Этот e-mail уже используется">
                <b class="profile__field-name">Email:</b>
                <input type="text" name="email" value="<?=$attr['email']?>" class="profile__field-input epa__required" placeholder="your@email.com" id="email_input" data-name="Электронная почта" autocomplete="off">
                <? /*if($attr['confirmEmail']): ?>
                  <span class="epa__confirm complete js-g-hashint" title="Email подтвержден"></span>
                <? else: ?>
                  <span class="epa__confirm js-g-hashint" title="Email не подтвержден"></span>
                <? endif;*/ ?>
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 profile__col<?=($attr['confirmEmail']?' d-none':'')?>">
              <div class="prmu-btn prmu-btn_normal">
                <span>Подтвердить email</span>
              </div>
            </div>
          </div>
          <div id="email-confirm" class="d-none">
            <div class="col-xs-12 profile__col">
              <b>На Вашу почту выслан код для подтверждения. Введите его в это поле!</b>
            </div>
            <div class="col-xs-12 col-sm-6 profile__col">
              <div class="profile__field">
                <b class="profile__field-name">Код:</b>
                <input type='text' name='email-code' class="profile__field-input" maxlength="4">
              </div>
            </div>
            <div class="col-xs-12 col-sm-6 profile__col">
              <div class="prmu-btn prmu-btn_normal">
                <span>Подтвердить</span>
              </div>
            </div>
          </div>
          <? if(count($arAdPhones)): ?>
            <? foreach ($arAdPhones as $k => $v): ?>
              <div class="col-xs-12 col-sm-6 epa__add-phone profile__col">
                <label class="profile__field">
                  <b class="profile__field-name">Доп. Телефон:</b>
                  <input type="text" name="user-attribs[admob<?=$k?>]" value="<?=$v['val']?>" class="profile__field-input epa__phone" autocomplete="off">
                </label>
              </div>
            <? endforeach; ?>
          <? endif; ?>
          <div class="clearfix"></div>

          <b class="profile__field-name">Мессенджеры:</b><br>
          <div class="col-xs-6 col-md-4 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Skype:</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'skype') ?>
              <input type="text" name="user-attribs[skype]" value="<?=($id ? $attrAll[$id]['val'] : '')?>" class="profile__field-input" autocomplete="off">
            </label>
          </div>
          <div class="col-xs-6 col-md-4 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Viber:</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'viber') ?>
              <input type="text" name="user-attribs[viber]" value="<?=$attrAll[$id]['val']?>" class="profile__field-input" autocomplete="off">
            </label>
          </div>
          <div class="col-xs-6 col-md-4 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">WhatsApp:</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'whatsapp') ?>
              <input type="text" name="user-attribs[whatsapp]" value="<?=$attrAll[$id]['val']?>" class="profile__field-input" autocomplete="off">
            </label>
          </div>
          <div class="col-xs-6 col-md-4 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Telegram:</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'telegram') ?>
              <input type="text" name="user-attribs[telegram]" value="<?=$attrAll[$id]['val']?>" class="profile__field-input" autocomplete="off">
            </label>
          </div>
          <div class="col-xs-6 col-md-4 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Google Allo:</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'googleallo') ?>
              <input type="text" name="user-attribs[googleallo]" value="<?=$attrAll[$id]['val']?>" class="profile__field-input" autocomplete="off">
            </label>
          </div>
          <div class="clearfix"></div>

          <b class="profile__field-name">Социальные сети:</b><br>
          <div class="col-xs-12 col-sm-6 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">ВКонтакте (сылка):</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'vk') ?>
              <input type="text" name="user-attribs[vk]" value="<?=$attrAll[$id]['val']?>" class="profile__field-input" autocomplete="off" placeholder="vk.com/">
            </label>
          </div>
          <div class="col-xs-12 col-sm-6 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Facebook (ссылка):</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'fb') ?>
              <input type="text" name="user-attribs[fb]" value="<?=$attrAll[$id]['val']?>" class="profile__field-input" autocomplete="off" placeholder="fb.com/">
            </label>
          </div>
          <div class="col-xs-12 col-sm-6 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Одноклассники (сылка):</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'ok') ?>
              <input type="text" name="user-attribs[ok]" value="<?=$attrAll[$id]['val']?>" class="profile__field-input" autocomplete="off" placeholder="ok.ru/">
            </label>
          </div>
          <div class="col-xs-12 col-sm-6 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Mail.ru (почта):</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'mail') ?>
              <input type="text" name="user-attribs[mail]" value="<?=$attrAll[$id]['val']?>" class="profile__field-input" autocomplete="off" placeholder="your@mail.com">
            </label>
          </div>
          <div class="col-xs-12 col-sm-6 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Google+ (почта):</b>
              <?php $id = $this->ViewModel->isInArray($attrAll, 'key', 'google') ?>
              <input type="text" name="user-attribs[google]" value="<?=$attrAll[$id]['val']?>" class="profile__field-input" autocomplete="off" placeholder="your@gmail.com">
            </label>
          </div>
          <div class="col-xs-12 col-sm-6 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Другое:</b>
              <input type="text" name="user-attribs[custcont]" value="<?=$attrAll[39]['val']?>" class="profile__field-input" autocomplete="off">
            </label>
          </div>
          <div class="clearfix"></div>
          <br>
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
          <b class="profile__field-name">Выберите должности, на которых желаете работать</b>
          <div class="epa__label epa__posts epa__select">
            <b class="epa__label-name profile__field-name">Должность:</b>
            <ul id="epa-list-posts" class="epa__select-list epa__select-lst-vsbl" >
              <?php foreach($arPosts as $post):?>
                <li>
                  <input type="checkbox" name="donjnost[]" value="<?=$post['id']?>" id="epa-post-<?=$post['id']?>" <?=$post['checked']?>>
                  <label for="epa-post-<?=$post['id']?>"><span><?=$post['newname']?></span><b></b></label>
                </li>
              <?php endforeach;?>
            </ul>
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
          <?/*?>
          <h3 class="epa__cities-title">Опубликованные города претендента</h3>
          <div class="epa__cities-list">
            <div>
              <?php foreach($arUserCities as $city): ?>
                <b><?=$city['name']?></b>
              <?php endforeach; ?>
            </div>
            <?php
//                echo '<span class="epa__add-city-btn epa__city-btn js-g-hashint" title="Добавить город" >+</span>';
            ?>
          </div>
          <?*/?>
          <div class="epa__cities-block-list">
            <?php foreach($arUserCities as $city): ?>
              <div class="epa__city-item" data-idcity="<?=$city['id']?>">
                <?/*?>
                <div class="epa__city-title">
                  <b>ГОРОД </b>
                  <span class="epa__city-del"></span>
                </div>
                <?*/?>
                <div class="col-xs-12 profile__col">
                  <div class="profile__field epa__select epa__city">
                    <b class="profile__field-name">Город:</b>
                    <span class="epa__city-err">Такой город уже выбран</span>
                    <input type="text" name="cityname[]" value="<?=$city['name']?>" class="profile__field-input city-input" autocomplete="off">
                    <ul class="city-list"></ul>
                  </div>
                </div>
                <?
                //
                ?>
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
                <?
                //
                ?>
                <b class="epa__label-name profile__field-name">Удобное время работы:</b>
                <div class="col-xs-12 profile__col">
                  <div class="epa__days-checkboxes">
                    <?php foreach($arDays as $idDay => $name): ?>
                      <div class="epa__day">
                        <input type="checkbox" name="days[]" value="<?=$idDay?>" class="epa__day-input" data-day="<?=$name?>" <?=array_key_exists($idDay, $viData['userInfo']['userWdays'][$city['id']]) ? 'checked' : ''?>>
                        <label class="epa__checkbox"><?=$name?></label>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
                <?
                //
                ?>
                <div class="col-xs-12 profile__col">
                  <div class="epa__period-list">
                    <div class="row">
                      <?php if(sizeof($viData['userInfo']['userWdays'][$city['id']])): ?>
                        <?php foreach($viData['userInfo']['userWdays'][$city['id']] as $idDay => $t):?>
                          <? $value = 'С ' . explode(':', $t['timeb'])[0] . ' до ' . explode(':', $t['timee'])[0]?>
                          <div class="col-xs-12 col-sm-6">
                            <div class="profile__field epa__period" data-id="<?=$idDay?>">
                              <span class="epa__period-close"></span>
                              <div class="epa__period-error"><span>С</span><b></b><span>до</span><b></b></div>
                              <b class="profile__field-name"><i><?=$arDays[$idDay]?></i>, Время дня:</b>
                              <input
                                type="text"
                                name="time[<?=$city['id']?>][<?=$idDay?>]"
                                class="profile__field-input epa__required"
                                value="<?=$value?>"
                                data-name="Временной период"
                                autocomplete="off">
                            </div>
                          </div>
                        <?php endforeach; ?>
                      <? endif; ?>
                    </div>
                  </div>
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
          <div class="col-xs-12 col-sm-6 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Рост:</b>
              <input type="text" name="user-attribs[manh]" value="<?=$attrAll[9]['val']?>" id="epa-height" class="profile__field-input" autocomplete="off">
            </label>
          </div>
          <?
          //
          ?>
          <div class="col-xs-12 col-sm-6 profile__col">
            <label class="profile__field">
              <b class="profile__field-name">Вес:</b>
              <input type="text" name="user-attribs[weig]" value="<?=$attrAll[10]['val']?>" id="epa-weight" class="profile__field-input" autocomplete="off">
            </label>
          </div>
          <?
          //
          ?>
          <?php foreach($arAppear as $appId => $app):?>
            <div class="col-xs-12 col-sm-6 profile__col">
              <div class="profile__field epa__app-<?=$app?> epa__select">
                <b class="profile__field-name"><?=$arAppearName[$appId]?>:</b>
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
                <input type="text" name="epa-str-<?=$app?>" value="<?=$name?>" class="profile__field-input" id="epa-str-<?=$app?>" disabled>
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
          <div class="col-xs-12 profile__col">
            <div class="profile__field epa__education epa__select">
              <b class="profile__field-name">Образование:</b>
              <?php
                $arRes = array();
                $name = '';
                foreach($viData['userDictionaryAttrs'] as $val)
                  if($val['idpar'] == 69)
                  {
                    $arRes[$val['id']] = $val;
                    if($this->ViewModel->isInArray($attrAll, 'id_attr', $val['id'])){
                      $arRes[$val['id']]['checked'] = 'checked';
                      $name .= ($name=='' ? '' : ',') . $val['name'];
                    }
                  }
              ?>
              <input type="text" name="epa-str-education" value="<?=$name?>" class="profile__field-input" id="epa-str-education" disabled>
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
          </div>
          <?
          //
          ?>
          <div class="col-xs-12 profile__col">
            <div class="profile__field epa__language epa__select">
              <b class="profile__field-name">Иностранные языки:</b>
              <?php
              $arRes = [];
              $name = '';
              foreach($viData['userDictionaryAttrs'] as $val)
                if($val['idpar'] == 40)
                {
                  $arRes[$val['id']] = $val;
                  if($this->ViewModel->isInArray($attrAll, 'id_attr', $val['id']))
                  {
                    $arRes[$val['id']]['checked'] = 'checked';
                    $name .= ($name=='' ? '' : ', ') . $val['name'];
                  }
                }
              ?>
              <input type="text" name="epa-str-language" value="<?=$name?>" class="profile__field-input" id="epa-str-language" disabled>
              <div class="epa__label-veil" id="epa-veil-language"></div>
              <ul class="epa__select-list" id="epa-list-language">
                <i class="epa__select-list-icon">OK</i>
                <li>
                  <input type="text" name="select-lang" placeholder="поиск языка" value="" class="sel-lang" id="sel-lang">
                </li>
                <?php foreach($arRes as $lang): ?>
                  <li>
                    <input type="checkbox" name="langs[]" value="<?=$lang['id']?>" id="epa-language-<?=$lang['id']?>" data-name="Иностранные языки" <?=$lang['checked']?>>
                    <label for="epa-language-<?=$lang['id']?>"><?=$lang['name']?><b></b></label>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
          <?
          //
          ?>
          <div class="col-xs-12 profile__col">
            <label class="profile__field epa__about">
              <b class="profile__field-name">О себе: <span style="display: none">(текст до 2000 символов)</span></b>
              <textarea
                name="about-mself"
                class="epa__textarea epa__required"
                placeholder="Укажите навыки и знания, которые помогут вам справиться с желаемой работой или дополнительную информацию, которая поможет работодателю лучше узнать Вас"
                data-name="О себе"><?=$attr['aboutme']?></textarea>
            </label>
          </div>
          <?
          //
          ?>
          <?
            $isNews = false;
            foreach ($attrAll as $v)
              $v['key']=='isnews' && $isNews = $v['val'];
          ?>
          <div class="col-xs-12 profile__col">
            <input type="checkbox" name="user-attribs[isnews]" id="epa-isnews" class="epa__hidden" value="1" <?=$isNews ? 'checked' : ''?>>
            <label class="epa__checkbox" for="epa-isnews">Получать новости об изменениях и новых возможностях на сайте</label>
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
  <div class="col-xs-12 col-sm-6">
    <div class="profile__field epa__period" data-id="NEWDAY">
      <span class="epa__period-close"></span>
      <div class="epa__period-error"><span>С</span><b></b><span>до</span><b></b></div>
      <b class="profile__field-name"><i></i>, Время дня:</b>
      <input
        type="text"
        name="time[NEWID][NEWDAY]"
        class="profile__field-input epa__required"
        data-name="Временной период"
        autocomplete="off">
    </div>
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



<?//  *****************  //?>
<div id="add-additional-phone">
  <div class="col-xs-12 col-sm-6 epa__add-phone profile__col">
    <label class="profile__field">
      <b class="profile__field-name">Доп. Телефон:</b>
      <input type="text" name="user-attribs[admobNEWNUM]" value="" class="profile__field-input epa__phone" autocomplete="off">
    </label>
  </div>
</div>
<?//  *****************  //?>
<div id="error_messege" class="tmpl">
  <div class="prmu__popup">Для того что бы Ваша анкета была доступна для просмотра всем работодателям и Вы могли откликаться на понравившиеся Вам вакансии, необходимо заполнить все обязательные поля, они выделены красной рамкой.<br>Спасибо за понимание</div>
</div>