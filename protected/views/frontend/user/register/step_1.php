<?php // ?>
<?// if (count($viData['errors'])): ?>
<!--    <div class="danger">- --><?//= implode('<br>- ', $viData['errors']); ?><!--</div>-->
<?// endif; ?>
<!--<form id="register_form">-->
<!--    <label>-->
<!--        <span>Я ищу работу</span>-->
<!--        <input type="radio" value="--><?//= UserProfile::$APPLICANT ?><!--" name="type" class="input-type">-->
<!--    </label>-->
<!--    <br>-->
<!--    <label>-->
<!--        <span>Я ищу сотрудников</span>-->
<!--        <input type="radio" value="--><?//= UserProfile::$EMPLOYER ?><!--" name="type" class="input-type">-->
<!--    </label>-->
<!--    <br>-->
<!--    <small>Регистрируясь на ресурсе Prommu Вы даете согласие на обработку своих <a href="#">персональных данных</a>.-->
<!--    </small>-->
<!--</form>-->
<?php // ?>
<?php

Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . '/snap/snap.svg-min.js');
?>

<div class="login">

    <div class="login__logo">
        <a class="logo" href="/"></a>
    </div>

    <form id="register_form">

        <div class="login-wrap">

            <svg x="0" y="0" class="svg-bg" />

            <h2 class="login__header">Регистрация</h2>
            <h6 class="login__header">Выберите, что вас интересует</h6>

            <div class="login__container">
                <p>
                    <a class="btn-orange" href="/user/register?step=2">
                        Я ищу работу
                        <span class="txt">
                            Я "Валера" и не заметил, что сайт для поиска временной работы, и я ищу работу мечты
                        </span>
                    </a>
                </p>
                <p>
                    <a class="btn-orange" href="/user/register?step=3">
                        Я ищу сотрудников
                        <span class="txt">
                            Хочу разместить вакансии и найти сотрудников
                        </span>
                    </a>
                </p>
            </div>
        </div>
    </form>

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

<!--<div class="homeHeaderContainer">-->
<!--    <svg x="0" y="0" class="svg-bg" />-->
<!--</div>-->

