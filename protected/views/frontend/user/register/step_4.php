<?php
?>

<form id="register_form">

    <div class="login-wrap">

        <svg x="0" y="0" class="svg-bg" />

        <h2 class="login__header">Регистрация</h2>
        <h6 class="login__header">Подтвердите номер телефона</h6>

        <div class="login__container">

            <p>
                <input type="text" placeholder="Введите код из SMS">
            </p>

            <p class="separator">
                Код подтверждения отправлен на:
            </p>
            <p class="separator">+3 745 123-32-32</p>

            <p class="input">
                <label for="radio-4" class="btn-green">Продолжить</label>
                <input type="radio" name="radio" id="radio-4">
            </p>

            <p class="separator">
                <a class= "back__away" href="#" onClick="backAway()">
                    Вернуться назад и отредактировать данные
                </a>
            </p>

        </div>
    </div>
</form>