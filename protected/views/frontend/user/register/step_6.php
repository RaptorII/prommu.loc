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
        <h6 class="login__header">Создайте пароль</h6>

        <div class="login__container">

            <p>
                <input type="password" placeholder="Придумайте пароль">
            </p>
            <p>
                <input type="password" placeholder="и введите повторно">
            </p>

            <p class="input">
                <label for="radio-6" class="btn-green">Продолжить</label>
                <input type="radio" name="radio" id="radio-6">
            </p>

            <p>
                <a class= "back__away" href="#" onClick="backAway()">
                    Вернуться назад и отредактировать данные
                </a>
            </p>

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