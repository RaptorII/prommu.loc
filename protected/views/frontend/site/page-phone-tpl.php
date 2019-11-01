<?php 
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/theme/css/register-form/register-form.css');
Yii::app()->getClientScript()->registerScriptFile('/theme/js/register-form/register-form.js', CClientScript::POS_END);
$this->setBreadcrumbs($title = 'Регистрация соискателя', $this->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '1')));
$this->pageTitle = $title;
?>

<div class='row'>
    <div class='col-xs-12 register-wrapp competitor'>

        <?php $admin =  $_GET['admin']; ?>
        <div class="register__reg-header">
            <div class="reg-header__pic"></div>
            <div class="reg-header__text">
              <span class="reg-header__point orange">&bull;</span>
              <span class="reg-header__point orange">&bull;</span>
              <span class="reg-header__point orange">&bull;</span>
              <h1 class='reg-header__title'>Регистрация соискателя</h1>
              <span class="reg-header__point orange">&bull;</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-8 register__reg-form">
            <span class="register__preview">Потратив несколько минут своего времени для регистрации, Вы получаете возможность найти работу. Если Вы уже зарегистрированы - можете <a href="<?=MainConfig::$PAGE_LOGIN?>">авторизоваться</a>.</span>
            <?php if( $viData['message'] ): ?><div id="Di1Message" class="message red"><?= $viData['message'] ?></div><?php endif; ?>
            <form action='/phone/?register=1' id='F1registerAppl' method='GET'>
                <label class="reg-form__label com1 js-g-hashint" title="Имя">
                    <?php if( $viData['element'] == 'name' ): ?>
                        <span class="red"><?= $viData['hint'] ?></span>
                    <?php endif; ?>
                    <input id='EdName' name='name' type='text'  value="<?= $viData['inputData']['name'] ?>" placeholder="Имя" class="reg-form__input">
                </label>

                <label class="reg-form__label com2 js-g-hashint" title="Фамилия">
                    <?php if( $viData['element'] == 'lname' ): ?>
                        <span class="red"><?= $viData['hint'] ?></span>
                    <?php endif; ?>
                    <input id='EdLname' name='lname' type='text'  value="<?= $viData['inputData']['lname'] ?>" placeholder="Фамилия" class="reg-form__input">
                </label>

                <div class="clearfix"></div>

                <div class="reg-form__selection">
                    <label class='reg-form__label-radio'>
                        <input name='type-reg' type='radio' value='1' checked="checked">
                        <span class="reg-form__radio"><span></span></span>
                        Регистрация с помощью Email
                    </label>
                    <br>
                    <label class='reg-form__label-radio'>
                        <input name='type-reg' type='radio' value='2'>
                        <span class="reg-form__radio"><span></span></span>
                        Регистрация с помощью номера телефона
                    </label>
                </div>

                <label class="reg-form__label com3 js-g-hashint" title="Email или телефон" id="reg-form-field">
                    <?php if( $viData['element'] == 'email' ): ?>
                        <span class="red"><?= $viData['hint'] ?></span>
                    <?php endif; ?>
                    <input id='EdEmail' name='email' type='text' data-field-check='name:Email,empty,email' value="<?= $viData['inputData']['email'] ?>" placeholder="Email" class="reg-form__input">
                </label>

                <label class="reg-form__label com4">
                    <span class="reg-form__label-name mob-hidden">Наличие смартфона</span>
                    <span class="reg-form__label-name mob-visible">Cмартфон</span>
                    <label class='reg-form__label-radio js-g-hashint' title="есть">
                        <input name='smart' type='radio' value='1' checked="checked">
                        <span class="reg-form__radio"><span></span></span>
                        Да
                    </label>
                    <label class='reg-form__label-radio js-g-hashint' title="нет">
                        <input name='smart' type='radio' value='0'>
                        <span class="reg-form__radio"><span></span></span>
                        Нет
                    </label>
                </label>

                <label class="reg-form__label com5">
                    <span class="reg-form__label-name">Пол</span>
                    <label class='reg-form__label-radio js-g-hashint' title="мужской">
                        <input name='sex' type='radio' value='1' checked="checked">
                        <span class="reg-form__radio"><span></span></span>
                        М
                    </label>
                    <label class='reg-form__label-radio js-g-hashint' title="женский">
                        <input name='sex' type='radio' value='0'>
                        <span class="reg-form__radio"><span></span></span>
                        Ж
                    </label>
                </label>

                <div class="clearfix"></div>

                <label class="reg-form__label com6 js-g-hashint" title="Пароль">
                    <?php if( $viData['element'] == 'pass' ): ?>
                        <span class="red"><?= $viData['hint'] ?></span>
                    <?php endif; ?>
                    <input id='EdPass' name='pass' type='password' data-field-check='name:Пароль,empty,password:#EdPassRep' placeholder="Пароль" class="reg-form__input">
                </label>

                <label class="reg-form__label com7 js-g-hashint" title="Подтвердите пароль">
                    <input id='EdPassRep' type='password' name="passrep" placeholder="Подтвердите пароль" class="reg-form__input">
                </label>

                <div class='btn-reg btn-orange-wr'>
                    <button class='hvr-sweep-to-right reg-form__btn btn__orange' type='submit'>Зарегистрироваться</button>
                </div>

                <input name='p' type='hidden' value='1'>
                <input type="hidden" class="referer" name="register" value="1">
                <input type="hidden" class="referer" name="referer" value="">
                <input type="hidden" class="transition" name="transition" value="">
                <input type="hidden" class="canal" name="canal" value="">
                <input type="hidden" class="campaign" name="campaign" value="">
                <input type="hidden" class="content" name="content" value="">
                <input type="hidden" class="keywords" name="keywords" value="">
                <input type="hidden" class="point" name="point" value="">
                <input type="hidden" class="last_referer" name="last_referer" value="">
                <input type="hidden" class="admin" name="admin" value="<?= $admin; ?>">
            </form>
            <span class="reg-form__description">
                Нажав кнопку зарегистрироваться, я тем самым подтверждаю, что принимаю
                <a href='https://prommu.com/services/conditions' target="_blank">условия использования</a>
                сайта
            </span>
        </div>
        <div class="col-xs-12 col-sm-4 register__reg-social">
            <span class="register__preview">Либо зарегистрируйтесь через социальные сети:</span>
            <div class="reg-social__link-block">
                <a href="/user/login?service=facebook" class="reg-social__link fb js-g-hashint" title="facebook" ><span class="mob-hidden">facebook</span></a>
                <a href="/user/login?service=vkontakte" class="reg-social__link vk js-g-hashint" title="vkontakte.ru" ><span class="mob-hidden">vkontakte.ru</span></a>
<!--                <a href="/user/login?service=mailru" class="reg-social__link ml js-g-hashint" title="mail.ru"><span class="mob-hidden">mail.ru</a>-->
                <a href="/user/login?service=odnoklassniki" class="reg-social__link od js-g-hashint" title="odnoklasniki.ru"><span class="mob-hidden">odnoklasniki.ru</span></a>
                <a href="/user/login?service=google_oauth" class="reg-social__link go js-g-hashint" title="google+"><span class="mob-hidden">google+</span></a>
            </div>

        </div>
    </div>
</div>

<?/*
<div class='row'>
  <div class='col-xs-12 register-wrapp'>
<?php 
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/theme/css/register-form.css');

$this->setBreadcrumbs($title = 'Регистрация', $this->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '1')));
$this->pageTitle = $title;
?>

    <?php if($_GET['type'] == 3):?>
      <h1 class='big'>Введите название компании и email </h1>
    <? else:?>
    <h1 class='big'>Введите номер телефона </h1>
  <? endif;?>

    <form action='/phone' id='F1registerAppl' method='get'>
        <input type="hidden" name="lname" value="<?=$viData['lname'];?>" />
        <input type="hidden" name="fname" value="<?=$viData['fname'];?>"/>
        <input type="hidden" name="register" value="1"/>
        <input type="hidden" name="gender" value="<?=$viData['gender'];?>"/>
        <input type="hidden" name="birthday" value="<?=$viData['birthday'] ?>" />
        <input type="hidden" name="type" value="<?=$_GET['type']?>" /> 
        <input type="hidden" name="photos" value="<?=$viData[0]?>" />
        <input type="hidden" name="messenger" value="<?=$viData['id']?>"/>
        
       
      <div class='register'>

      <?php if($_GET['type'] == 3):?>
        <div class="clearfix"></div>
        <label for='EdEmail'>Название компании</label>
         <!--  <span class="red"><?= $viData['hint'] ?></span> -->
        <input id='EdName' name='name' type='text' value="<?= $viData['name'] ?>">
      <? endif;?>
        <div class="clearfix"></div>
        <label for='EdEmail'>Номер телефона</label>
       <!--  <?php if( $viData['element'] == 'email' ): ?>
          <span class="red"><?= $viData['hint'] ?></span>
        <?php endif; ?> -->
        <input id='EdEmails' name='phone' type='text' value="<?= $viData['email'] ?>" class="register-fb__email">

         <label for='EdEmail'>Пароль</label>
       <!--  <?php if( $viData['element'] == 'email' ): ?>
          <span class="red"><?= $viData['hint'] ?></span>
        <?php endif; ?> -->
        <input id='EdPassw' name='pass' type='text' value="<?= $viData['email'] ?>" class="register-fb__email">

        <?php if($_GET['type'] != 3 && !$viData['gender']):?>
         <!--  <div class="clearfix"></div> -->
        <label class="reg-form__label com5">
                   <label for='EdEmail'>Пол</label>
                    <label class='reg-form__label-radio' title="мужской">
                        <input name='gender' type='radio' value='1' checked="checked">
                        <span class="reg-form__radio"><span></span></span>
                        М
                    </label>
                    <label class='reg-form__label-radio' title="женский">
                        <input name='gender' type='radio' value='0'>
                        <span class="reg-form__radio"><span></span></span>
                        Ж
                    </label>
                </label>
    <?php endif; ?>
    <div class="clearfix"></div>
        <div class='btn-reg btn-orange-wr'>
          <button class='hvr-sweep-to-right' type='submit' id="reg-fb-btn">Зарегистрироваться</button>
        </div>
      </div>
      <input type="hidden" class="referer" name="referer" value="">
                <input type="hidden" class="transition" name="transition" value="">
                <input type="hidden" class="canal" name="canal" value="">
                <input type="hidden" class="campaign" name="campaign" value="">
                <input type="hidden" class="content" name="content" value="">
                <input type="hidden" class="keywords" name="keywords" value="">
                <input type="hidden" class="point" name="point" value="">
                <input type="hidden" class="last_referer" name="last_referer" value="">
    </form>

    <small>
      Нажав кнопку зарегистрироваться, я тем самым подтверждаю, что принимаю
      <a href='/<?= MainConfig::$PAGE_PAGES ?>/conditions'>условия использования</a>
      сайта
    </small>

  </div>
</div>
*/?>