<?php 
    $bUrl = Yii::app()->baseUrl;
    Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/register-form/register-form.css');
    Yii::app()->getClientScript()->registerCssFile($bUrl.'/theme/css/phone-codes/style.css'); 
    Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/register-form/register-form.js', CClientScript::POS_END);
    Yii::app()->getClientScript()->registerScriptFile($bUrl.'/theme/js/phone-codes/script.js', CClientScript::POS_END);
    //Yii::app()->getClientScript()->registerScriptFile('https://www.google.com/recaptcha/api.js', CClientScript::POS_END);
    $this->setBreadcrumbs($title = 'Регистрация работодателя', $this->createUrl(MainConfig::$PAGE_REGISTER, array('p' => '2')));
    $this->pageTitle = $title;

    // если вводился телефон - давайте работать с телефоном
    $phone = '';
    $phoneCode = 0; 
    if($viData['inputData']['email'] && !filter_var($viData['inputData']['email'], FILTER_VALIDATE_EMAIL)){
        $pos = strpos($viData['inputData']['email'], '(');
        $phone = substr($viData['inputData']['email'], $pos);
        $phoneCode = str_replace($phone, '', $viData['inputData']['email']);
        $phoneCode = substr($phoneCode, 1);
    }
?>
<script type="text/javascript">var selectPhoneCode = <?=json_encode($phoneCode)?></script>
<div class='row'>
  <div class='col-xs-12 register-wrapp employer'>
        <?php
            $admin =  $_GET['admin'];
        ?>
        <div class="register__reg-header">
            <div class="reg-header__pic"></div>
            <div class="reg-header__text">
                <span class="reg-header__point green">&bull;</span>
                <span class="reg-header__point green mob-visible">&bull;</span>
                <span class="reg-header__point green mob-visible">&bull;</span>
                <h1 class='reg-header__title'>Регистрация <span class="mob-hidden med-hidden">нового</span> работодателя</h1>
                <span class="reg-header__point green">&bull;</span>
                <span class="reg-header__point green mob-hidden">&bull;</span>
                <span class="reg-header__point green mob-hidden">&bull;</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-8 register__reg-form">
            <span class="register__preview">Потратив несколько минут своего времени для регистрации, Вы получаете в дальнейшем возможности поиска необходимого персонала, создание вакансий.<br>Если Вы уже зарегистрированы - можете <a href="<?=MainConfig::$PAGE_LOGIN?>">авторизоваться</a>.</span>
            <?php if( $viData['error'] ): ?><div class="message red"><?= $viData['message'] ?></div><?php endif; ?>
            <form action='<?=MainConfig::$PAGE_REGISTER?>' id='F1registerEmpl' method='POST' data-email="<?=MainConfig::$PAGE_REGISTER?>" data-phone="<?=MainConfig::$PAGE_PHONE?>" class="register-form">
                <label class="reg-form__label emp1" title="Название компании">
                    <?php if( $viData['element'] == 'name' ): ?>
                        <span class="red"><?= $viData['hint'] ?></span>
                    <?php endif; ?>
                    <input id='EdName' name='name' type='text' value="<?= $viData['inputData']['name'] ?>" placeholder="Название компании" class="reg-form__input">
                </label>

                <div class="reg-form__selection">
                    <?php $treg = $viData['inputData']['type-reg']?>
                    <label class='reg-form__label-radio'>
                        <input name='type-reg' type='radio' value='1'<?=($treg==1?' checked':(empty($treg)?' checked':''))?>>
                        <span class="reg-form__radio"><span></span></span>
                        Регистрация с помощью Email
                    </label>
                    <br>
                    <label class='reg-form__label-radio'>
                        <input name='type-reg' type='radio' value='2'<?=($treg==2?' checked':'')?>>
                        <span class="reg-form__radio"><span></span></span>
                        Регистрация с помощью номера телефона
                    </label>
                </div>

                <label class="reg-form__label emp2" >
                    <?php if( $viData['element'] == 'email' ): ?>
                        <span class="red"><?= $viData['hint'] ?></span>
                    <?php endif; ?>
                    <input id='EdEmail' name='email' type='email' data-field-check='name:Email,empty,email' value="<?= $viData['inputData']['email'] ?>" placeholder="Email" class="reg-form__input">
                    <input id='phone-code' type="text" name="phone" placeholder="Телефон">
                </label>
                <label class="reg-form__label emp3" title="Пароль">
                    <?php if( $viData['element'] == 'pass' ): ?>
                        <span class="red"><?= $viData['hint'] ?></span>
                    <?php endif; ?>
                    <input id='EdPass' name='pass' type='password' data-field-check='name:Пароль,empty,password:#EdPassRep' value="<?= $viData['inputData']['pass'] ?>" placeholder="Пароль" class="reg-form__input">
                </label>
                <label class="reg-form__label emp4" title="Подтвердите пароль">
                    <input id='EdPassRep' type='password' data-field-check='name:Подтверждение,empty' name="passrep" value="<?= $viData['inputData']['passrep'] ?>"  placeholder="Подтвердите пароль" class="reg-form__input">
                </label>
                <?/*<div class="g-recaptcha" data-sitekey="6Lf2oE0UAAAAAKL5IvtsS1yytkQDqIAPg1t-ilNB"></div>*/?>
                <div class='btn-reg btn-orange-wr'>
                    <button class='hvr-sweep-to-right reg-form__btn' type='submit'>Зарегистрироваться</button>
                </div>
                 <input  name='type' type='hidden'  value="3" >
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
                <a href="<?=MainConfig::$PAGE_CONDITIONS?>" target="_blank">условия использования</a>
                сайта
            </span>
        </div>
       <div class="col-xs-12 col-sm-4 register__reg-social">
            <span class="register__preview">Либо зарегистрируйтесь через социальные сети:</span>
            <div class="reg-social__link-block">
                <a href="/user/login?service=facebook&type=3" class="reg-social__link fb" title="facebook" ><span class="mob-hidden">facebook</span></a>
                <a href="/user/login?service=vkontakte&type=3" class="reg-social__link vk js-g-hashint" title="vkontakte.ru" ><span class="mob-hidden">vkontakte.ru</span></a>
                <a href="/user/login?service=mailru&type=3" class="reg-social__link ml js-g-hashint" title="mail.ru"><span class="mob-hidden">mail.ru</a>
                <a href="/user/login?service=odnoklassniki&type=3" class="reg-social__link od js-g-hashint" title="odnoklasniki.ru"><span class="mob-hidden">odnoklasniki.ru</span></a>
                <a href="/user/login?service=google_oauth&type=3" class="reg-social__link go " title="google+"><span class="mob-hidden">google+</span></a>  
            </div>

        </div>
    </div>
</div>