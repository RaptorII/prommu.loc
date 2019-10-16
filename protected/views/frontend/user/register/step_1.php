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


    <form id="register_form">

        <div class="login-wrap">

            <svg x="0" y="0" class="svg-bg" />

            <h2 class="login__header">Регистрация</h2>
            <h6 class="login__header">Выберите, что вас интересует</h6>

            <div class="login__container">

                <p class="input">
                    <label for="radio-1" class="btn-orange">Я ищу работу</label>
                    <label class="txt">
                        Я "Валера" и не заметил, что сайт для поиска
                        временной работы, и я ищу работу мечты
                    </label>
                    <input type="radio" name="type" value="<?= UserProfile::$APPLICANT ?>" id="radio-1" class="input-type">
                </p>

                <p class="input">
                    <label for="radio-2" class="btn-orange">Я ищу сотрудников</label>
                    <label class="txt">
                        Хочу разместить вакансии и найти сотрудников
                    </label>
                    <input type="radio" name="type" value="<?= UserProfile::$EMPLOYER ?>" id="radio-2" class="input-type">
                </p>

            </div>
        </div>
    </form>




