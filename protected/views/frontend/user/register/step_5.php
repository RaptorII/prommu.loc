<?php
Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . '/snap/snap.svg-min.js');
?>

    <form id="register_form">

        <div class="login-wrap">

            <svg x="0" y="0" class="svg-bg" />

            <h2 class="login__header">Регистрация</h2>
            <h6 class="login__header">Подтвердите e-mail</h6>

            <div class="login__container">
                <p>
                    Введите код из письма
                </p>

                <p>
                    <input type="text" placeholder="Введите код из письма">
                </p>

                <p>
                    Код подтверждения отправлен на:
                </p>
                <p>valeriyTerkin@mail.ru</p>

                <p>
                    <a href="">
                        Отправить повторно
                    </a>
                </p>

                <p class="input">
                    <label for="radio-5" class="btn-green">Продолжить</label>
                    <input type="radio" name="radio" id="radio-5">
                </p>

                <p>
                    <a class= "back__away" href="#" onClick="backAway()">
                        Вернуться назад и отредактировать данные
                    </a>
                </p>

            </div>
        </div>

    </form>

