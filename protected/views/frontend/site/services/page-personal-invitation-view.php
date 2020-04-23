<?
$rq = Yii::app()->getRequest();
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'services/services-email-page.css');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'services/services-email-page.js', CClientScript::POS_END);
if(!$rq->getParam('vacancy')):?>
    <div class="row">
        <div class="col-xs-12">
            <?php if(sizeof($viData['vacs'])): ?>
                <h2 class="service__title">ВЫБЕРИТЕ ВАКАНСИЮ ДЛЯ ПРИГЛАШЕНИЯ ПЕРСОНАЛА</h2>
                <form action="" method="POST">
                    <div class="service__vac-list">
                        <?php foreach ($viData['vacs'] as $key => $val): ?>
                            <label class="service-vac__item  service-vac__person-inv">
                                <div class="service-vac__item-bg">
                                    <span class="service-vac__item-title"><?=$val['title'] ?></span>
                                </div>
                                <input type="radio" name="vacancy" value="<?=$val['id']?>" class="service-vac__item-input">
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <input type="hidden" name="vacancy" id="vacancy">
                    <button class="service__btn prmu-btn prmu-btn_normal pull-right" id="vac-btn"><span>Выбрать персонал для персонального приглашения</span></button>
                </form>
            <?php else: ?>
                <br>
                <h2 class="service__title center">У ВАС НЕТ АКТИВНЫХ ВАКАНСИЙ ПРОШЕДШИХ МОДЕРАЦИЮ</h2>
                <a href="<?=MainConfig::$PAGE_VACPUB?>" class="service__btn--vac visible prmu-btn prmu-btn_normal"><span>ДОБАВИТЬ ВАКАНСИЮ</span></a>
            <?php endif; ?>
        </div>
    </div>
<?php elseif(!$rq->getParam('users')): ?>
    <?
    //    Выбор соискателей
    //
    $vacancy = $rq->getParam('vacancy');
    ?>
    <script type="text/javascript">
        var arSelectCity = <?=json_encode($viData['workers']['city'])?>;
        var AJAX_GET_PROMO = "<?='/user'.MainConfig::$PAGE_SERVICES_PERSONAL_INVITATION?>";
    </script>
    <div class='row'>
        <?
        //		FILTER
        ?>
        <div class="filter__veil"></div>
        <div class='col-xs-12 col-sm-4 col-md-3'>
            <div class="filter__vis hidden-sm hidden-md hidden-lg hidden-xl">ФИЛЬТР</div>
            <form action="" id="promo-filter" method="get">
                <? require_once 'ankety-filter.php'; ?>
            </form>
        </div>
        <?
        //		CONTENT
        ?>

        <div class='col-xs-12 col-sm-8 col-md-9'>
            <div class='view-radio clearfix'>
                <h1 class="main-h1">Выбрать персонал для приглашения</h1>
                <form action="" method="POST" id="workers-form">
                    <?php if($viData['price']!=0 && $viData['price']<1): ?>
                        <div class="price-warning">Стоимость отправки сообщения для одного соискателя составляет <b><?=$viData['price']?> руб.</b><br/>Сумма минимальной платежной операции - <b>1 руб.</b></div>
                    <?php endif; ?>
                    <span class="workers-form__cnt">Выбрано получателей: <span id="mess-wcount">0</span></span>
                    <div class="service__switch">
                        <span class="service__switch-name">Выбрать всех</span>
                        <input type="checkbox" name="ntf-push" id="all-workers" value="1"/>
                        <label for="all-workers">
                            <span data-enable="вкл." data-disable="выкл."></span>
                        </label>
                    </div>
                    <button type="submit" class="prmu-btn prmu-btn_normal off" id="workers-btn"><span>Отправить приглашение</span></button>
                    <input type="hidden" name="users" id="mess-workers">
                    <input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
                    <input type="hidden" name="users-cnt" id="mess-wcount-inp" value="0">
                    <input type="hidden" name="vacancy" value="<?=$rq->getParam('vacancy')?>">
                </form>
            </div>
            <div id="promo-content"><? require_once 'ankety-ajax.php'; ?></div>
<!--            <div id="promo-content">--><?// $this->renderPartial(MainConfig::$VIEWS_SERVICE_ANKETY_AJAX,['viData'=>$viData])?><!--</div>-->
        </div>
    </div>
    <?
//		Выбор соискателей
    ?>
<?php else: ?>
    <?php
    $appCount = $rq->getParam('users-cnt');
    $vacancy = $rq->getParam('vacancy');
    $cntVacStat = ResponsesApplic::getCntVacStat($vacancy);
    $users = $rq->getParam('users');
    $arUsr = explode(',', $users);
    $cntUsers   = count($arUsr);
    $usrVS = ResponsesApplic::getInvitedUsrFromVacStat($vacancy);

    $cntInvtUsr = 0;
    for ($i=0; $i<count($usrVS); ++$i)
    {
        for ($j=0; $j<=count($arUsr); ++$j) {
            if ($usrVS[$i]['id_user'] == $arUsr[$j]) {
                display($usrVS[$i]['id_user']);
                display($arUsr[$j]);
                //выпилить из строки данных $users
                $users = str_replace($usrVS[$i]['id_user'] . ',', '', $users);
                if (count($users) == 1)
                    $users = str_replace($usrVS[$i]['id_user'], '', $users );
                $cntInvtUsr++;
            }
        }
    }

    if ($users) {
        $cntUsers = count(explode(',', $users));
    } else {
        $cntUsers = 0;
    }

    if ($viData['is_pay']) {
        $price = 0;
    } else {
        if (($cntVacStat <= 10) && ($cntVacStat + $cntUsers) > 10) {
            $price = $viData['price'];
        } elseif (($cntVacStat + $cntUsers) <= 10) {
            $price = 0;
        } elseif ($cntVacStat > 10) {
            $price = $viData['price'];
        }
    }

    //display($viData['is_pay']);
    ?>
    <div class="row">
        <div class="col-xs-12">
            <form action="<?=MainConfig::$PAGE_PAYMENT?>" method="POST" class="smss__result-form">
                </br></br>
                <h1 class="smss-result__title">Услуга </br> "Персональное приглашение"</h1>
                <? if  ((!$viData['is_pay'])&&(($cntVacStat <= 10) && ($cntVacStat + $cntUsers) > 10)): ?>
                    <p>
                        Превышен лимит бесплатных приглашений! Оплатите услугу.
                    </p>
                <? elseif   (!$viData['is_pay']):?>
                    <p>
                        Помните - первые 10 приглашений для каждой вакансии - <b>бесплатно</b>!
                    </p>
                <? else:?>
                    <p>
                        Услуга <b>оплачена</b>!
                    </p>
                <? endif; ?>
                <table class="smss-result__table">
                    <tr>
                        <td>Всего приглашённых</td>
                        <td><?=$cntVacStat?></td>
                    </tr>
                    <tr>
                        <td>Количество уникальных получателей</td>
                        <td><?=$cntUsers?></td>
                    </tr>
                    <? if ($cntInvtUsr):?>
                    <tr>
                        <td>Из выбранных уже приглашено</td>
                        <td><?=$cntInvtUsr?></td>
                    </tr>
                    <? endif; ?>
                    <tr>
                        <td>К оплате</td>
                        <td> <?= $price ?> руб.</td>
                    </tr>
                </table>
                <span class="smss-result__result"></span>
                <? if((($cntVacStat + $cntUsers) > 10) && (!$viData['is_pay'])):
                    $this->renderPartial('../site/services/legal-fields',['viData'=>$viData]);
                endif; ?>
                <br>
                <br>
                <div class="center">
                    <button class="prmu-btn prmu-btn_normal" id="email_pay_btn">
                        <? if($price > 0 ):?>
                            <span>Перейти к оплате</span>
                        <? else: ?>
                            <span>Пригласить</span>
                        <? endif; ?>
                    </button>
                </div>

                <input type="hidden" name="vacancy" value="<?=$vacancy?>">
                <input type="hidden" name="users-cnt" value="<?=$appCount?>">
                <input type="hidden" name="users" value="<?=$users?>">
                <input type="hidden" name="employer" value="<?=Share::$UserProfile->id?>">
                <input type="hidden" name="service" value="personal-invitation">
                <input type="hidden" name="price" value="<?=$price?>">
            </form>
        </div>
    </div>
<?php endif; ?>
