<?
$bUrl = Yii::app()->baseUrl . '/theme/';
Yii::app()->getClientScript()->registerCssFile($bUrl . 'css/projects/user-card.css');
Yii::app()->getClientScript()->registerScriptFile($bUrl . 'js/projects/user-card.js', CClientScript::POS_END);

Yii::app()->getClientScript()->registerCssFile('/theme/css/private/page-prof-app.css');
Yii::app()->getClientScript()->registerScriptFile("/theme/js/private/page-prof-app.js", CClientScript::POS_END);
?>


<div class="container">
    <h1 class="user-profile-page__title">Профиль персонала - Александр Хузин</h1>
</div>

<div class="content-block">
    <div class="private-profile-page for-guest">
        <div class="ppp__logo">
            <div class="ppp__logo-main">
                <a href="/images/applic/20181011062340976000.jpg"
                   class="js-g-hashint ppp-logo-main__link ppp__logo-full tooltipstered">
                    <img src="/images/applic/20181011062340976400.jpg" alt="Соискатель Хузин prommu.com"
                         class="ppp-logo-main__img">
                </a>

            </div>
            <div class="ppp__logo-rating">
                <ul class="ppp__star-block">
                    <li class="full"></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
                <span class="ppp__subtitle">15 из 100</span>
            </div>
            <div class="confirmed-user js-g-hashint tooltipstered">ПРОВЕРЕН</div>
            <div class="ppp__logo-more">
                <div class="clearfix"></div>
            </div>
            <div class="center-box">


                <div class="clearfix"></div>
            </div>
        </div>
        <div class="ppp__content">
            <h1 class="ppp__content-title">Александр Хузин</h1>
            <div class="ppp__module-title"><h2>ОСНОВНАЯ ИНФОРМАЦИЯ</h2></div>
            <div class="ppp__module">
                <div class="ppp__field">
                    <span class="ppp__field-name">Имя:</span>
                    <span class="ppp__field-val">Александр</span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Фамилия:</span>
                    <span class="ppp__field-val">Хузин</span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Дата рождения:</span>
                    <span class="ppp__field-val">10/07/1968</span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Пол:</span>
                    <span class="ppp__field-val">мужской</span>
                </div>
            </div>


            <div class="ppp__module-title"><h2>ПРОЕКТ</h2></div>
            <div class="ppp__module">
                <div class="ppp__field">
                    <span class="ppp__field-val">Проект 1</span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-val">Проект 2</span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-val">Проект 3</span>
                </div>
            </div>

            <div class="ppp__module-title"><h2>Города</h2></div>
            <div class="ppp__module">
                <div class="ppp__period-list">
                    <div class="ppp__field">
                        <span class="ppp__field-val">Аллабино</span>
                    </div>
                    <div class="ppp__field">
                        <span class="ppp__field-val">Москва</span>
                    </div>
                    <div class="ppp__field">
                        <span class="ppp__field-val">Донецк</span>
                    </div>
                    <div class="ppp__field">
                        <span class="ppp__field-val">Санкт-Петербург</span>
                    </div>
                </div>
            </div>


            <div class="ppp__module-title"><h2>ДОЛЖНОСТЬ</h2></div>
            <div class="ppp__module">
                <div class="ppp__field">
                    <span class="ppp__field-name">Проект1:</span>
                    <span class="ppp__field-val">Должность1</span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Проект2:</span>
                    <span class="ppp__field-val">Должность2</span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Проект2:</span>
                    <span class="ppp__field-val">Должность3</span>
                </div>
            </div>


            <div class="ppp__module-title"><h2>КОНТАКТЫ</h2></div>
            <div class="ppp__module">
                <div class="ppp__field">
                    <span class="ppp__field-name">Email:</span>
                    <span class="ppp__field-val">email@email.ru</span>
                </div>
                <div class="ppp__field">
                    <span class="ppp__field-name">Телефон:</span>
                    <span class="ppp__field-val">+38(090)-999-99-99</span>
                </div>
                <div class="ppp__period-list">
                    <div class="ppp__field">
                        <div class="ppp__field-name ppp__field-fix">Viber</div>
                        <span class="ppp__field-val ppp__field-fix">+38(090)-999-99-99</span>
                    </div>
                    <div class="ppp__field">
                        <div class="ppp__field-name ppp__field-fix">Telegram</div>
                        <span class="ppp__field-val ppp__field-fix">+38(090)-999-99-99</span>
                    </div>
                    <div class="ppp__field">
                        <div class="ppp__field-name ppp__field-fix">Еще меседжер</div>
                        <span class="ppp__field-val ppp__field-fix">+38(090)-999-99-99</span>
                    </div>
                </div>
            </div>

            <div class="ppp__module-title"><h2>обратная связь</h2></div>
            <div class="ppp__module">
                <form>
                    <textarea class="ppp__module-feedback" name="message"></textarea>
                    <div class="btn-auth btn-orange-wr">
                        <button class="hvr-sweep-to-right auth-form__btn" type="submit">Отправить</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

