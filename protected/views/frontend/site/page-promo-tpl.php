<?php

$bUrl = Yii::app()->baseUrl;
$gcs = Yii::app()->getClientScript();

$gcs->registerCssFile($bUrl . MainConfig::$CSS . 'site_template/static_page.css');

$gcs->registerScriptFile($bUrl . MainConfig::$JS . 'site_template/static_page_promo.js', CClientScript::POS_END);

?>

<h1 class="static__title static__legasy">Найдите работу в день размещения анкеты</h1>
<div class="static_head">
    Prommu - сервис №1 для поиска временной работы в сфере BTL и Event-мероприятий
</div>

<div class="static__paralax static__promo parallax">
    <img class="static__img-d" src="/theme/pic/static-page/students-h-up.jpg" alt="">
    <div class="container">
        <div class="row flex768up">
            <div class="col-xs-12 col-sm-7 col-md-6 mark-block">
                <div class="box box-mark">
                    <div class="box__ico green">
                        <span class="hands-touch__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head ">
                            Прямые работодатели
                        </div>
                        <div class="box__txt">
                            Мы сотрудничаем с рекламными, кадровыми, модельными агентствами, прямыми
                            работодателями и всеми, кто ищет временный персонал.
                        </div>
                    </div>
                </div>
                <div class="box box-mark">
                    <div class="box__ico green">
                        <span class="mishen__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head">
                            Специализация на временной работе
                        </div>
                        <div class="box__txt">
                            Prommu.com поможет Вам быстро, удобно и надежно найти временную работу на промо,
                            событийных мероприятиях, выставках и рекламных акциях.
                        </div>
                    </div>
                </div>
                <div class="box box-mark">
                    <div class="box__ico green">
                        <span class="varanty__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head">
                            Гарантия оплаты труда
                        </div>
                        <div class="box__txt">
                            Вы можете получить банковскую карту нашего сервиса, которая позволяет получать заработную
                            плату без рисков сразу после проделанной работы.
                        </div>
                    </div>
                </div>
                <div class="box box-mark">
                    <div class="box__ico green">
                        <span class="unic-star__icon"></span>
                    </div>
                    <div class="box__about">
                        <div class="box__head">
                            Уникальная анкета и условия работы
                        </div>
                        <div class="box__txt">
                            Вы можете указать дни недели, место и удобное время для работы. На PROMMU можно
                            работать так, как удобно именно Вам.
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-6 flex768up">
                <!--                <img class="static__img" src="/theme/pic/static-page/ma-up.png" alt="">-->
                <? if(Share::isApplicant()): ?>
                    <a class="btn__orange btn__ads" href="<?=MainConfig::$PAGE_VACPUB?>">Разместить анкету<br>бесплатно</a>
                <? elseif(Share::isEmployer()): ?>
                    <a class="btn__orange btn__ads employer_public_anc" href="javascript:void(0)">Разместить вакансию<br>бесплатно</a>
                <? else: ?>
                    <a class="btn__orange btn__ads" href="<?=MainConfig::$PAGE_REGISTER?>">Разместить анкету<br>бесплатно</a>
                <? endif; ?>
            </div>
        </div>
    </div>
</div>


<div class="static__job">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="static__title static__legasy">
                    Почему для временной работы выбирают PROMMU.com
                </h3>
            </div>
        </div>
        <div class="row flex768up">

            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="clock__icon green centered-elem"></span>
                    </div>
                    <span class="box__txt center">
                        Почасовая работа в дневное время.
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="cloud-moon__icon green centered-elem"></span>
                    </div>
                    <span class="box__txt center">
                        Подработка в вечернее или ночное время.
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="beach__icon green centered-elem"></span>
                    </div>
                    <span class="box__txt center">
                        Подработка по выходным.
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="arrow-qplay__icon green centered-elem"></span>
                    </div>
                    <span class="box__txt center">
                        Разовое участие в мероприятиях (акциях).
                    </span>
                </div>
            </div>

        </div>

        <div class="row flex768up">
            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="job__icon green centered-elem"></span>
                    </div>
                    <div class="box__name center text-uppercase">
                        Много вакансий
                    </div>
                    <span class="box__txt center">
                        Самая большая база вакансий на временную работу, которая ежедневно пополняется десятками
                        новых предложений.
                    </span>

                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="hand-finger__icon green centered-elem"></span>
                    </div>
                    <div class="box__name center text-uppercase">
                        PUSH уведомления
                    </div>
                    <span class="box__txt center">
                        Получайте уведомления онлайн о новых вакансиях и других событиях на портале. Будьте первым,
                        кто откликнется на интересную вакансию.
                    </span>

                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="phone-bell__icon green centered-elem"></span>
                    </div>
                    <div class="box__name center text-uppercase">
                        Мобильное приложение
                    </div>
                    <span class="box__txt center">
                       Все удобства и функционал портала Prommu.com теперь в вашем мобильном. Упустить интересную
                       подработку невозможно!
                    </span>

                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3  col-lg-3 flex768up">
                <div class="box_wrap">
                    <div class="box__rnd">
                        <span class="shield__icon green centered-elem"></span>
                    </div>
                    <div class="box__name center text-uppercase">
                        Безопасное сотрудничество
                    </div>
                    <span class="box__txt center">
                       Все вакансии работодателей проходят жесткую проверку перед публикацией на сайте. Получив
                       нашу карту, безопасность и надежность оплаты труда составит 100%.
                    </span>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3" >
                <? if(Share::isApplicant()): ?>
                    <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_VACPUB?>">Зарегистрироваться</a>
                <? elseif(Share::isEmployer()): ?>
                    <a class="btn__orange btn__sized center text-uppercase employer_public_anc" href="javascript:void(0)">Зарегистрироваться</a>
                <? else: ?>
                    <a class="btn__orange btn__sized center text-uppercase" href="<?=MainConfig::$PAGE_REGISTER?>">Зарегистрироваться</a>
                <? endif; ?>
            </div>
        </div>

    </div>

</div>


<div class="static__favorite-job">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="static__title static__legasy">
                    Популярные специальности
                </h3>
            </div>
        </div>
        <div class="row flex768up">
            <div class="favorite-job__wrap">
                <div class="col-xs-6 flex768up">
                    <div class="box_wrap">
                        <div class="box__rnd">
                            <span class="rupor__icon green centered-elem"></span>
                        </div>
                        <div class="box__name center text-uppercase">
                            Промоутер
                        </div>
                        <span class="box__txt center">
                            от 400 рублей в час
                        </span>
                    </div>
                </div>
                <div class="col-xs-6 flex768up">
                    <div class="box_wrap">
                        <div class="box__rnd">
                            <span class="sale__icon green centered-elem"></span>
                        </div>
                        <div class="box__name center text-uppercase">
                            Мерчендайзер
                        </div>
                        <span class="box__txt center">
                            от 35 000 рублей в месяц
                        </span>
                    </div>
                </div>
                <div class="col-xs-6 flex768up">
                    <div class="box_wrap">
                        <div class="box__rnd">
                            <span class="man-w__icon green centered-elem"></span>
                        </div>
                        <div class="box__name center text-uppercase">
                            Супервайзер
                        </div>
                        <span class="box__txt center">
                            от 40 000 рублей в месяц
                        </span>
                    </div>
                </div>
                <div class="col-xs-6 flex768up">
                    <div class="box_wrap">
                        <div class="box__rnd">
                            <span class="controller__icon green centered-elem"></span>
                        </div>
                        <div class="box__name center text-uppercase">
                            Аниматор
                        </div>
                        <span class="box__txt center">
                            от 200 рублей в час
                        </span>
                    </div>
                </div>
                <div class="col-xs-6 flex768up">
                    <div class="box_wrap">
                        <div class="box__rnd">
                            <span class="consultant__icon green centered-elem"></span>
                        </div>
                        <div class="box__name center text-uppercase">
                            Консультант
                        </div>
                        <span class="box__txt center">
                          от 40 000 рублей в месяц
                        </span>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3" >
                <a class="center text-uppercase" href="/#specialties" style="display: block; text-decoration: underline;">
                    Смотреть все специальности
                </a>
            </div>
        </div>

        <div class="row flex768up flex-center">

            <div class="col-xs-12 col-sm-6 col-md-3" >
                <div  class="btn__orange-rnd  center text-uppercase ">
                    Средний заработок
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3" >
                <div  class="btn__orange-rnd  center text-uppercase ">
                    В час от <span style="color: red">300</span>&nbsp;P
                </div>
            </div>

            <div class="col-xs-12 col-sm-6 col-md-3" >
                <div  class="btn__orange-rnd  center text-uppercase ">
                    В месяц от <span style="color: red">30 000</span> Р
                </div>
            </div>

            <div class="col-xs-12 col-md-3">
                <? if(Share::isApplicant()): ?>
                    <a class="btn__orange  center text-uppercase" href="<?=MainConfig::$PAGE_VACPUB?>">Начать зарабатывать</a>
                <? elseif(Share::isEmployer()): ?>
                    <a class="btn__orange  center text-uppercase employer_public_anc" href="javascript:void(0)">Начать зарабатывать</a>
                <? else: ?>
                    <a class="btn__orange  center text-uppercase" href="<?=MainConfig::$PAGE_REGISTER?>">Начать зарабатывать</a>
                <? endif; ?>
            </div>

        </div>
    </div>
</div>

<div class="separator"></div>

<div class="static__map">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="static__title static__legasy">
                    Поиск работы во всех городах России
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
                            актуальных посетителей в месяц
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
                            проверенных и актуальных вакансий в базе
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
                            надежных работодателей Prommu.com
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
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h3 class="static__title static__legasy">
                    Prommu.com сотрудничает с крупными работодателями
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