//
<?// if (count($viData['errors'])): ?>
<!--    <div class="danger">- --><?//= implode('<br>- ', $viData['errors']); ?><!--</div>-->
<?// endif; ?>
<!--<form id="register_form">-->
<!--    --><?// if (Share::isApplicant($viData['input']['type'])): ?>
<!--        <label>-->
<!--            <span>Имя</span>-->
<!--            <input type="text" value="--><?//= $viData['input']['name'] ?><!--" name="name" class="input-name" autocomplete="off"-->
<!--                   placeholder="Александр">-->
<!--        </label>-->
<!--        <br>-->
<!--        <label>-->
<!--            <span>Фамилия</span>-->
<!--            <input type="text" value="--><?//= $viData['input']['surname'] ?><!--" name="surname" class="input-surname"-->
<!--                   autocomplete="off" placeholder="Иванов">-->
<!--        </label>-->
<!--    --><?// elseif (Share::isEmployer($viData['input']['type'])): ?>
<!--        <label>-->
<!--            <span>Название компании</span>-->
<!--            <input type="text" value="--><?//= $viData['input']['name'] ?><!--" name="name" class="input-name" autocomplete="off"-->
<!--                   placeholder="Евразия">-->
<!--        </label>-->
<!--    --><?// endif; ?>
<!--    <br>-->
<!--    <label>-->
<!--        <span>Мобильный телефон или электронная почта</span>-->
<!--        <input type="text" value="--><?//= $viData['input']['login'] ?><!--" name="login" class="input-login" autocomplete="off"-->
<!--               placeholder="+7-123-456-78-90 или your-email@mail.ru">-->
<!--    </label>-->
<!--</form>-->
//

<?php
Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . '/snap/snap.svg-min.js');
?>

<div class="login">

    <div class="login__logo">
        <a class="logo" href="/"></a>
    </div>

    <div class="login-wrap">

        <svg x="0" y="0" class="svg-bg" />

        <h2 class="login__header">Регистрация</h2>
        <h6 class="login__header">Введите данные</h6>

        <div class="login__container">

            <p>
                <input type="text" placeholder="Имя">
            </p>
            <p>
                <input type="text" placeholder="Фамилия">
            </p>
            <p>
                <input type="text" placeholder="Телефон или e-mail">
            </p>

            <p>
                <a class="btn-green" href="/user/register?step=4">
                    Продолжить
                </a>
            </p>

            <div class="login__social-container">
                <span class="register__preview"></span>
                <div class="reg-social__link-block">
                    <a href="/user/login?service=facebook" class="reg-social__link fb js-g-hashint" title="facebook" >
                    <span class="mob-hidden">
                        facebook
                    </span>
                    </a>
                    <a href="/user/login?service=vkontakte" class="reg-social__link vk js-g-hashint" title="vkontakte.ru" >
                    <span class="mob-hidden">
                        vkontakte.ru
                    </span>
                    </a>
                    <a href="/user/login?service=mailru" class="reg-social__link ml js-g-hashint" title="mail.ru">
                    <span class="mob-hidden">
                        mail.ru
                    </span>
                    </a>
                    <a href="/user/login?service=odnoklassniki" class="reg-social__link od js-g-hashint" title="odnoklasniki.ru">
                    <span class="mob-hidden">
                        odnoklasniki.ru
                    </span>
                    </a>
                    <a href="/user/login?service=google_oauth" class="reg-social__link go js-g-hashint" title="google">
                    <span class="mob-hidden">
                        google
                    </span>
                    </a>
                    <a href="/user/login?service=yandex_oauth" class="reg-social__link ya js-g-hashint" title="yandex">
                    <span class="mob-hidden">
                        yandex
                    </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="login__txt">
        <p>
            Регистрация и авторизация означает согласие с
            <a href="">“Лицензионным соглашением“</a>,
            <a href="">“Политикой в области обработки и обеспечения безопасности персональных данных“</a>,
            а также
            <a href="">“Соглашением об оказании услуг по содействию в трудоустройстве“</a>.
        </p>
    </div>
</div>

