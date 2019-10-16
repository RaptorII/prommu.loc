<?php
?>

<form id="register_form">

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
</form>