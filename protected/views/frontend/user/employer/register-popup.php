<?
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'phone-codes/style.css');
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'register-popup/register-popup-emp.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'register-popup/register-popup-emp.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'phone-codes/script.js', CClientScript::POS_END);
?>
<meta name="robots" content="noindex">
<div class="register-popup js-popup show">
  <div class="register-popup__header">
    <h1 class="rp-header__title">ПОЗДРАВЛЯЕМ ВАС С УСПЕШНОЙ РЕГИСТРАЦИЕЙ</h1> 
    <a class="rp-header__close-btn close" href="<?=MainConfig::$PAGE_EDIT_PROFILE?>">&#10006</a>
  </div>
  <div class="register-popup__content1">
    <div class="rp-content1__block">
      <form action="#" class='js-form register-popup-form' id="popup-form">
        <p class="rp-content1__descr">Для того, чтобы ваши вакансии увидели все соискатели, а также , чтобы самостоятельно начать приглашать соискателей необходимо заполнить обязательные данные о вашей компании</p>
        <div class="rp-content1__logo">
          <span class="rp-content1__logo-img">
            <img src="<?=(!empty($viData['info']['src']) ? $viData['info']['src'] : '/theme/pic/register-popup-page/register_popup_r_logo.png')?>" id="company-img">
          </span>
          <span class="rp-content1__logo-text">Добавление логотипа повысит узнаваемость бренда среди соискателей<span class="rp-content1__warning">Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</span></span>
          <input type="hidden" name="logo" id="HiLogo" class="required-inp" value="<?=(!empty($viData['info']['src']) ? $viData['info']['src'] : '')?>" />
        </div>
        <div class="rp-content1__btn-block" id="load-img-module">        
          <div class="rp-btn-block__load js-g-hashint" id="btn-load-image" title="Выбрать изображение"></div>
          <div class="rp-btn-block__webcam js-g-hashint" id="btn-get-snapshot" title="Сделать снимок"></div>
          <div class="clearfix"></div>
        </div>
       
        <div class="rp-content1__inputs">
          <input type="text" name="phone" value="<?=$viData['phone']?>" id="phone-code" class="required-inp">
          <span class="rp-content1__inputs-city">
            <input type="text" name="city" value="<?=$viData['userCities']['name']?>" class="rp-content1__inputs-input required-inp" id="city-input" autocomplete="off" >
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
        <input type="hidden" name="npopup" value="1">
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
  var selectPhoneCode = <?=json_encode($viData['phone-code'])?>;
  var country = <?=json_encode($viData['userCities']['id_country'])?>;
  var arCountries = <?=json_encode($viData['countries'])?>;
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
<? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/popup-load-img.php'; ?>