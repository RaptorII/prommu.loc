<?php
$bUrl = Yii::app()->baseUrl;
$gcs = Yii::app()->getClientScript();

$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'site_template/static_page.css');

$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'site_template/static_page.js', CClientScript::POS_END);
?>

<h1 class="static__title static__legasy">Найдите сотрудника в день размещения вакансии</h1>
<div class="static_head">
    Prommu - сервис №1 по подбору персонала для BTL и Event-мероприятий
</div>

<div class="static__paralax parallax">
    <img class="static__img-d" src="/theme/pic/static-page/hands.jpg" alt="">
    <div class="container">
        <div class="row flex768up">
            <div class="col-xs-12 col-sm-7 col-md-6 mark-block">
                <div class="box box-mark">
                    <div class="box__ico green">
                        <span class="free__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head">
                            Бесплатное размещение
                        </div>
                        <div class="box__txt">
                            Размещение обычных вакансий на портале - бесплатно!
                        </div>
                    </div>
                </div>
                <div class="box box-mark">
                    <div class="box__ico green">
                        <span class="moder__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head">
                            Модерация соискателей
                        </div>
                        <div class="box__txt">
                            Перед попаданием в базу каждый соискатель
                            проходит проверку модераторами.
                        </div>
                    </div>
                </div>
                <div class="box box-mark">
                    <div class="box__ico green">
                        <span class="phone__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head">
                            Мобильное приложение
                        </div>
                        <div class="box__txt">
                            Все возможности портала в мобильном устройстве.
                        </div>
                    </div>
                </div>
                <div class="box box-mark">
                    <div class="box__ico green">
                        <span class="geo__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head">
                            Геолокация
                        </div>
                        <div class="box__txt">
                            Отслеживание местоположения персонала,
                            полный контроль над всеми перемещениями.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-6 flex768up">
              <? if(Share::isEmployer()): ?>
                <?=VacancyView::createVacancyLink('Разместить вакансию<br>бесплатно','btn__orange btn__ads')?>
              <? elseif(Share::isApplicant()): ?>
                <a class="btn__orange btn__ads applicant_create_vac" href="javascript:void(0)">Разместить вакансию<br>бесплатно</a>
              <? else: ?>
                <a class="btn__orange btn__ads" href="<?=MainConfig::$PAGE_LOGIN?>">Разместить вакансию<br>бесплатно</a>
              <? endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="static__map">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="static__title static__legasy">
                    Размещение вакансий во всех городах России
                </h3>
            </div>

            <div class="col-xs-12 col-sm-7 col-md-6">
                <div class="box">
                    <div class="box__ico">
                        <span class="trend-money__icon orange"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head green">
                            50 тыс
                        </div>
                        <div class="box__txt grey">
                            уникальных посетелей в месяц
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="box__ico">
                        <span class="pen-list__icon orange"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head green">
                            21 тыс
                        </div>
                        <div class="box__txt grey">
                            проверенных и актуальных резюме в базе
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="box__ico">
                        <span class="man-gears__icon orange"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head green">
                            2 тыс
                        </div>
                        <div class="box__txt grey">
                            работодателей используют Prommu.com
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-5 col-md-6">
                <div class="map__rus">
                        <?php echo file_get_contents("theme/pic/static-page/rus.svg"); ?>
                </div>
            </div>

        </div>
    </div>
</div>


<div class="static__brand">
    <div class=" container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="static__title static__legasy">
                    2073 компании подбирают сотрудников на PROMMU.com
                </h3>
            </div>
        </div>
        <div class="row static__brand-mob">
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="static__brand-pic">
                    <?php echo '<img src="/theme/pic/static-page/partners/status.jpg">'; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="static__brand-pic">
                    <?php echo '<img src="/theme/pic/static-page/partners/ice.jpg">'; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="static__brand-pic">
                    <?php echo '<img src="/theme/pic/static-page/partners/bbdo.jpg">'; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="static__brand-pic">
                    <?php echo '<img src="/theme/pic/static-page/partners/creon.jpg">'; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="static__brand-pic">
                    <?php echo '<img src="/theme/pic/static-page/partners/acula.jpg">'; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="static__brand-pic">
                    <?php echo '<img src="/theme/pic/static-page/partners/atomic.jpg">'; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="static__brand-pic">
                    <?php echo '<img src="/theme/pic/static-page/partners/concol.jpg">'; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                <div class="static__brand-pic">
                    <?php echo '<img src="/theme/pic/static-page/partners/brand.jpg">'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="static__vac">
    <div class="container">
        <div class="row flex768up">
            <div class="col-xs-12 col-md-3">
                <div class="box__rnd">
                    <span class="mans-list__icon green"></span>
                </div>
            </div>
            <div class="col-xs-12 col-md-9">
                <h3 class="static__title">
                    <? if(Share::isEmployer()): ?>
                      <?=VacancyView::createVacancyLink('Публикация вакансий')?>
                    <? elseif(Share::isApplicant()): ?>
                        <a class="applicant_service" href="javascript:void(0)">Публикация вакансий</a>
                    <? else: ?>
                        <a class="" href="<?=MainConfig::$PAGE_REGISTER?>">Публикация вакансий</a>
                    <? endif; ?>
                </h3>
                <p>
                    Простой и удобный способ подобрать персонал на PROMMU.com
                </p>
                <p>
                    Публикация обычной вакансии - оптимальный вариант, если требуется небольшое количество персонала
                    и есть время на подбор кандидатов.
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <? if(Share::isEmployer()): ?>
                  <?=VacancyView::createVacancyLink('Разместить','btn__orange btn__sized center text-uppercase')?>
                <? elseif(Share::isApplicant()): ?>
                    <a class="btn__orange btn__sized center text-uppercase applicant_service" href="javascript:void(0)">Разместить</a>
                <? else: ?>
                    <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_LOGIN?>">Разместить</a>
                <? endif; ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <div  class="btn__orange-rnd center text-uppercase">
                    Бесплатно
                </div>
            </div>
        </div>
        <div class="row">
                <div class="separator"></div>
        </div>

        <div class="row flex768up">
            <div class="col-xs-12 col-md-3">
                <div class="box__rnd">
                    <span class="list-pencil__icon green"></span>
                </div>
            </div>
            <div class="col-xs-12 col-md-9">
                <div class="row">
                    <h3 class="static__title">
                        <? if(Share::isEmployer()): ?>
                            <a class="" href="<?='/user' . MainConfig::$PAGE_SERVICES_PREMIUM?>">Публикация премиум вакансий</a>
                        <? elseif(Share::isApplicant()): ?>
                            <a class="applicant_service" href="javascript:void(0)">Публикация премиум вакансий</a>
                        <? else: ?>
                            <a class="" href="<?=MainConfig::$PAGE_REGISTER?>">Публикация премиум вакансий</a>
                        <? endif; ?>
                    </h3>
                    <p>
                        Премиум вакансии ускоряют процесс подбора персонала в несколько раз. Такие вакансии выделяются
                        рамкой и демонстрируются вне очереди на главной странице сайта и в списке вакансий, не опускаясь
                        ниже первой страницы.
                    </p>
                    <p>
                        Привлекает максимальное количество откликов со стороны Соискателей.
                    </p>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <? if(Share::isEmployer()): ?>
                    <a class="btn__orange btn__sized center text-uppercase" href="<?='/user' . MainConfig::$PAGE_SERVICES_PREMIUM?>">Заказать</a>
                <? elseif(Share::isApplicant()): ?>
                    <a class="btn__orange btn__sized center text-uppercase applicant_service" href="javascript:void(0)">Заказать</a>
                <? else: ?>
                    <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_REGISTER?>">Заказать</a>
                <? endif; ?>
            </div>

            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <div  class="btn__orange-rnd center text-uppercase ">
                    Московская область - 100 Р в день
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <div  class="btn__orange-rnd center text-uppercase">
                    СПБ и область - 50 Р в день
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <div  class="btn__orange-rnd center text-uppercase">
                    Регионы - 25 Р в день
                </div>
            </div>
        </div>

        <div class="row">
            <div class="separator"></div>
        </div>

        <div class="row  flex768up">
            <div class="col-xs-12 col-md-3">
                <div class="box__rnd">
                    <span class="mans__icon green"></span>
                </div>
            </div>
            <div class="col-xs-12 col-md-9">
                <div class="row">
                    <h3 class="static__title">
                        <? if(Share::isEmployer()): ?>
<!--                            <div class="service_select">-->
                                <span>Приглашение персонала на вакансии</span>
                                <ul>
                                    <li>
                                        <a href="<?='/user' . MainConfig::$PAGE_SERVICES_PERSONAL_INVITATION?>">Персональное приглашение</a>
                                    </li>
                                    <li>
                                        <a href="<?='/user' . MainConfig::$PAGE_SERVICES_EMAIL?>">E-mail</a>
                                    </li>
                                    <li>
                                        <a href="<?='/user' . MainConfig::$PAGE_SERVICES_SMS?>">SMS</a>
                                    </li>
                                    <li>
                                        <a href="<?='/user' . MainConfig::$PAGE_SERVICES_PUSH?>">Push - уведомления</a>
                                    </li>
                                </ul>
                            </div>
                        <? elseif(Share::isApplicant()): ?>
                            <a class="applicant_service" href="javascript:void(0)">Приглашение персонала на вакансии</a>
                        <? else: ?>
                            <a class="" href="<?=MainConfig::$PAGE_REGISTER?>">Приглашение персонала на вакансии</a>
                        <? endif; ?>
                    </h3>
                    <p>
                        Не дожидайтесь, пока Соискатели обратят внимание на вашу вакансию, информируйте о ней
                        самостоятельно! Ускоряет процесс поиска сотрудников в 4 раза!
                    </p>
                    <p>
                        Достаточно выбрать канал связи и вакансию, рассылка отправится онлайн!
                    </p>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <? if(Share::isEmployer()): ?>
                    <div class="btn__orange btn__sized center text-uppercase service_select">
                        <span>Пригласить</span>
                        <ul>
                            <li>
                                <a href="<?='/user' . MainConfig::$PAGE_SERVICES_EMAIL?>">E-mail</a>
                            </li>
                            <li>
                                <a href="<?='/user' . MainConfig::$PAGE_SERVICES_SMS?>">SMS</a>
                            </li>
                            <li>
                                <a href="<?='/user' . MainConfig::$PAGE_SERVICES_PUSH?>">Push - уведомления</a>
                            </li>
                        </ul>
                    </div>
                <? elseif(Share::isApplicant()): ?>
                    <a class="btn__orange btn__sized center text-uppercase applicant_service" href="javascript:void(0)">Заказать</a>
                <? else: ?>
                    <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_REGISTER?>">Заказать</a>
                <? endif; ?>
            </div>

            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <div  class="btn__orange-rnd center text-uppercase static__drop">
                    E-MAIL
                    <div class="static__drop-wrap">
                        <div class="drop__item">
                            Вся Россия - 500 р
                        </div>
                        <div class="drop__item">
                            МСК и область - 300 р
                        </div>
                        <div class="drop__item">
                            СПБ и область - 200 р
                        </div>
                        <div class="drop__item">
                            Регионы - 100 р
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <div  class="btn__orange-rnd center text-uppercase static__drop">
                    SMS
                    <div class="static__drop-wrap">
                        <div class="drop__item">
                            Вся Россия - 4 р за SMS
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-3 col-md-offset-0">
                <div  class="btn__orange-rnd center text-uppercase static__drop">
                    PUSh -Уведомления
                    <div class="static__drop-wrap">
                        <div class="drop__item">
                            Бесплатно
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="static__outs grey-bg" >
    <div id="particle-canvas">

<!--    </div>-->
        <div class="static__outs-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <h3 class="static__title static__legasy">
                            Помощь в подборе персонала и аутстаффинг
                        </h3>
                    </div>
                </div>
                <div class="row flex768up">

                    <div class="col-xs-12 col-sm-6 col-md-4 col-md-offset-1 col-lg-3 col-lg-offset-2 ">
                        <div class="box__rnd">
                            <span class="clock-flash__icon green centered-elem"></span>
                        </div>
                        <? if(Share::isApplicant()): ?>
                          <a class="btn__orange btn__sized center text-uppercase applicant_service" href="javascript:void(0)">Аутсорсинг</a>
                        <? else: ?>
                          <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_SERVICES_OUTSOURCING?>">Аутсорсинг</a>
                        <? endif; ?>
                        <span class="box__txt center">
                            Если нужен персонал, но нет времени и возможности самостоятельно заниматься рекрутингом — наш
                            профессиональный HR-менеджер сделает все за Вас.
                        </span>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-md-offset-2 col-lg-3 col-lg-offset-2 ">
                        <div class="box__rnd">
                            <span class="list-clock__icon green centered-elem"></span>
                        </div>
                        <? if(Share::isApplicant()): ?>
                          <a class="btn__orange btn__sized center text-uppercase applicant_service" href="javascript:void(0)">Аутстаффинг</a>
                        <? else: ?>
                          <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_SERVICES_OUTSTAFFING?>">Аутстаффинг</a>
                        <? endif; ?>
                        <span class="box__txt center">
                            Если нужен персонал на краткосрочный период, аутстаффинг сэкономит бюджет и время на расходы по
                            оформлению временного персонала. Мы все сделаем под ключ в рамках Ваших целей и задач.
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<div class="static__outs">
    <div class="container">
        <div class="row flex768up">
            <div class="col-xs-12 col-sm-9 col-md-8 col-lg-7">
                <div class="box">
                    <div class="box__ico green">
                        <span class="checked__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head grey">
                            Подберем подходящих кандидатов
                        </div>
                        <div class="box__txt-list grey">
                            Проведем предварительное интервью, пригласим кандидатов на собеседование
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="box__ico green">
                        <span class="checked__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head grey">
                            Проведем через все этапы собеседования
                        </div>
                        <div class="box__txt-list grey">
                            Отберем заинтересованных кандидатов и проведем все собеседования
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="box__ico green">
                        <span class="checked__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head grey">
                            Организуем работу и проконтролируем выполнение
                        </div>
                        <div class="box__txt-list grey">
                            Фотоотчеты с места работы, контроль выполнения, замена в случае необходимости
                        </div>
                    </div>
                </div>
                <div class="box">
                    <div class="box__ico green">
                        <span class="checked__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head grey">
                            Все трудовые отношения с персоналам возьмем на себя
                        </div>
                        <div class="box__txt-list grey">
                            Оформление на работу, выплата заработной платы, уплата налогов, увольнение и др.
                        </div>
                    </div>
                </div>
                <div class="box">
                    <? if(Share::isEmployer()): ?>
                        <a class="btn__orange btn__sized center text-uppercase width-fix" href="<?=MainConfig::$PAGE_SERVICES_OUTSOURCING?>">ПОЛУЧИТЬ КОНСУЛЬТАЦИЮ</a>
                    <? elseif(Share::isApplicant()): ?>
                        <a class="btn__orange btn__sized center text-uppercase guest__reg-applic width-fix" href="javascript:void(0)">ПОЛУЧИТЬ КОНСУЛЬТАЦИЮ</a>
                    <? else: ?>
                        <a class="btn__orange btn__sized center text-uppercase guest__reg width-fix">ПОЛУЧИТЬ КОНСУЛЬТАЦИЮ</a>
                    <? endif; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-3 col-md-4 col-lg-3 col-lg-offset-1">
                <div class="image__big">
                    <?php echo '<img src="/theme/pic/static-page/businessperson.png">'; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="static__service">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="static__title static__legasy">
                    Услуги для работодателей
                </h3>
            </div>
        </div>
        <div class="row flex768up">

            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="pointer__icon green centered-elem"></span>
                    </div>
                    <div class="box__name">
                        Геолокация
                    </div>
                    <span class="box__txt center">
                        Позволяет создать маршрут передвижения сотрудников в ходе исполнения должностных
                        обязанностей и контролировать их местоположение.
                    </span>
                    <a class="btn__orange btn__sized center text-uppercase geo_message" href="javascript:void(0)">Подробнее</a>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="cards__icon green centered-elem"></span>
                    </div>
                    <div class="box__name">
                        Банковская карта
                    </div>
                    <span class="box__txt center">
                        Карта Промму – это платежный инструмент, который значительно повышает удобство расчета между
                        работодателями и специалистами, занятыми в области BTL и Event.
                    </span>
                    <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_SERVICES_CARD_PROMMU?>">Подробнее</a>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="books__icon green centered-elem"></span>
                    </div>
                    <div class="box__name">
                        Медкнижка
                    </div>
                    <span class="box__txt center">
                       Оформление медицинской книжки для своих сотрудников в несколько кликов онлайн.
                        Максимально быстрое получение.
                    </span>
                    <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_SERVICES_MEDICAL?>">Подробнее</a>
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="api-st__icon green centered-elem"></span>
                    </div>
                    <div class="box__name">
                        API-ключ
                    </div>
                    <span class="box__txt center">
                       Разрабатывайте собственные программные решения на базе API Java Server, используя доступ к
                        базе данных Соискателей нашего сайта.
                    </span>
                    <? if(Share::isApplicant()): ?>
                      <a class="btn__orange btn__sized center text-uppercase applicant_service" href="javascript:void(0)">Подробнее</a>
                    <? else: ?>
                      <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_SERVICES_API?>">Подробнее</a>
                    <? endif; ?>
                </div>
            </div>

        </div>
    </div>

</div>


<div class="container" style="display: block;">
    <div class="row">
        <div class="col-xs-12">
            <p>
                Мы гарантируем рекламным, кадровым агентствам и прямым работодателям быстрый поиск персонала,
                а также минимизацию риска при поиске. Чтобы найти подходящего работника, достаточно создать вакансию и
                указать нужные требования, для этого есть огромное количество подсказок.
            </p>

            <p>
                Наш сайт не охватывает весь HR рынок, но разместив вакансию - Вы сможете найти такой персонал: промоутер,
                тайный покупатель, мерчендайзер, супервайзер, хостесс, курьер, интервьюер, аниматор, кассир, ростовая
                кукла, модель, консультант, роллер, скейтер и многих других узких специальностей.
            </p>

            <p>
                Если вашей организации требуется тайный покупатель, то укажите, что этот человек должен быть ответственным,
                коммуникабельным, умеющим работать по четким инструкциям. Сегодня очень много людей стремятся найти
                дополнительный заработок или к примеру: заработать тайным покупателем, поскольку эта деятельность
                предусматривает возможность самостоятельно планировать рабочий график, но с учетом указанных временных
                параметров, а аутстаффинг Prommu создает площадку где Вам нужно просто разместить подобную вакансию - и
                персонал готов к выполнению поставленных задач.
            </p>

            <p>
                Пользуясь услугами сервиса Prommu, работодатель может нанять промоутера в кратчайшие сроки для
                промо-мероприятия. И исходя из поставленных задач можно выбрать супервайзера, который будет контролировать
                работу выбранных специалистов для достижения максимального результата.
            </p>

            <p>
                Хотите нанять сотрудника с гарантией эффективного результата? В сервисе Prommu есть ряд дополнительных
                услуг, которые помогут разобраться в любой сложившейся ситуации.
            </p>
        </div>
    </div>
</div>