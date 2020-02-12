<?
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . Share::$cssAsset['modalwindow.css']);
Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl . MainConfig::$CSS . 'vacancy/style.css');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl . MainConfig::$JS . 'vac-info/script.js', CClientScript::POS_END);

$section = Yii::app()->getRequest()->getParam('section');
$link = MainConfig::$PAGE_VACANCY . DS . $id;
switch ($section)
{
    case MainConfig::$VACANCY_APPROVED:
        $title = "Утвержденные";
        break;
    case MainConfig::$VACANCY_INVITED:
        $title = "Приглашенные";
        break;
    case MainConfig::$VACANCY_RESPONDED:
        $title = "Откликнувшиеся";
        break;
    case MainConfig::$VACANCY_DEFERRED:
        $title = "Отложенные";
        break;
    case MainConfig::$VACANCY_REJECTED:
        $title = "Отклоненные";
        break;
    case MainConfig::$VACANCY_REFUSED:
        $title = "Отказавшиеся";
        break;
}
$this->pageTitle = $title;
$this->setBreadcrumbsEx([$viData['vacancy']['title'], $link]);
$this->setBreadcrumbsEx([$title, $link . DS . $section]);
$model = new ResponsesEmpl();

//    display($viData); //v

?>
<a href="<?=$link?>" class="vacancy_info__back-link"><span><</span> Назад</a>
<div class="tabs-block">
    <a href="<?=$link . DS . MainConfig::$VACANCY_APPROVED?>"
       class="tabs-block__link<?=($section==MainConfig::$VACANCY_APPROVED ? ' active' : '')?>">
        Утвержденные
        <b><?=$viData['cnt'][MainConfig::$VACANCY_APPROVED] ? "({$viData['cnt'][MainConfig::$VACANCY_APPROVED]})" : ""?></b>
    </a>
    <a href="<?=$link . DS . MainConfig::$VACANCY_INVITED?>"
       class="tabs-block__link<?=($section==MainConfig::$VACANCY_INVITED ? ' active' : '')?>">
        Приглашенные
        <b><?=$viData['cnt'][MainConfig::$VACANCY_INVITED] ? "({$viData['cnt'][MainConfig::$VACANCY_INVITED]})" : ""?></b>
    </a>
    <a href="<?=$link . DS . MainConfig::$VACANCY_RESPONDED?>"
       class="tabs-block__link<?=($section==MainConfig::$VACANCY_RESPONDED ? ' active' : '')?>">
        Откликнувшиеся
        <b><?=$viData['cnt'][MainConfig::$VACANCY_RESPONDED] ? "({$viData['cnt'][MainConfig::$VACANCY_RESPONDED]})" : ""?></b>
    </a>
    <a href="<?=$link . DS . MainConfig::$VACANCY_DEFERRED?>"
       class="tabs-block__link<?=($section==MainConfig::$VACANCY_DEFERRED ? ' active' : '')?>">
        Отложенные
        <b><?=$viData['cnt'][MainConfig::$VACANCY_DEFERRED] ? "({$viData['cnt'][MainConfig::$VACANCY_DEFERRED]})" : ""?></b>
    </a>
    <a href="<?=$link . DS . MainConfig::$VACANCY_REJECTED?>"
       class="tabs-block__link<?=($section==MainConfig::$VACANCY_REJECTED ? ' active' : '')?>">
        Отклоненные
        <b><?=$viData['cnt'][MainConfig::$VACANCY_REJECTED] ? "({$viData['cnt'][MainConfig::$VACANCY_REJECTED]})" : ""?></b>
    </a>
    <a href="<?=$link . DS . MainConfig::$VACANCY_REFUSED?>"
       class="tabs-block__link<?=($section==MainConfig::$VACANCY_REFUSED ? ' active' : '')?>">
        Отказавшиеся
        <b><?=$viData['cnt'][MainConfig::$VACANCY_REFUSED] ? "({$viData['cnt'][MainConfig::$VACANCY_REFUSED]})" : ""?></b>
    </a>
</div>
<div class="vacancy_info" data-page="<?=$section?>">
    <? if($section==MainConfig::$VACANCY_INVITED): ?>
        <?
        // вкладка с приглашенными
        ?>
        <? if(!count($viData['items'])): ?>
            <div class="center">
                <h1>На эту вакансию вы пока не приглашали соискателей</h1>
            </div>
        <? else: ?>
            <div class="center">
                <div class="invited-list__services">
                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE . "?id=$id&service=email"?>" class="prmu-btn prmu-btn_normal">
                        <span>EMAIL рассылка</span>
                    </a>
                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE . "?id=$id&service=sms"?>" class="prmu-btn prmu-btn_normal">
                        <span>СМС рассылка</span>
                    </a>
                    <a href="<?=MainConfig::$PAGE_ORDER_SERVICE . "?id=$id&service=push"?>" class="prmu-btn prmu-btn_normal">
                        <span>Push уведомления</span>
                    </a>
                </div>
            </div>
            <div class="vacancy-invited__list">
                <? foreach ($viData['items'] as $v): ?>
                    <div class="vacancy-invited__item">
                        <? $arUser = $viData['users'][$v['user']]; ?>
                        <div class="vacancy-invited__item-logo">
                            <img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>">
                        </div>
                        <div>
                            <div class="vacancy-invited__item-title"><?=$arUser['name']?></div>
                            <div class="vacancy-invited__item-link">
                                <a href="<?=$arUser['profile']?>" class="prmu-btn prmu-btn_small">
                                    <span>Профиль</span>
                                </a>
                            </div>
                        </div>
                        <div class="vacancy-invited__item-right center">
                            <div>Дата приглашения: <b><?=$v['date']?></b></div>
                            <div>Статус: <b><?=$v['status']?></b></div>
                            <div>Тип: <b><?=$v['type']?></b></div>
                        </div>
                    </div>
                <? endforeach; ?>
            </div>
            <div class="vacancy-invited__pages">
                <? $this->widget('CLinkPager', array(
                    'pages' => $viData['pages'],
                    'htmlOptions' => ['class'=>'paging-wrapp'],
                    'firstPageLabel' => '1',
                    'prevPageLabel' => 'Назад',
                    'nextPageLabel' => 'Вперед',
                    'header' => ''
                )); ?>
            </div>
        <? endif; ?>
    <? else: ?>
        <?
        // остальные вкладки
        ?>
        <div class="vacancy_info--scroll-ico">
            <? if (count($viData['items'])): ?>
                <table class="responses-table">
                    <thead>
                    <tr><th><th>Имя<th>Дата отклика<th>Статус<th colspan="2"></tr>
                    </thead>
                    <tbody>
                    <? foreach ($viData['items'] as $v): ?>
                        <? $arUser = $viData['users'][$v['user']]; ?>
                        <tr class="table__row"
                            data-sid="<?=$v['sid']?>"
                            data-status="<?=$v['status']?>"
                            data-resp="<?=$v['isresponse']?>">
                            <td class='table__cell'>
                                <img src="<?=$arUser['src']?>" alt="<?=$arUser['name']?>" class="responses__logo">
                            </td>
                            <td class='table__cell'>
                                <a class='black-orange' href="<?=$arUser['profile']?>"
                                   title="номер заявки: <?=$v['sid']?>"><?=$arUser['name']?></a>
                            </td>
                            <td class='table__cell'><span class='rdate' title="Дата заявки"><?=$v['rdate']?></span></td>
                            <td class='table__cell status-block'><?=$model->getStatus($v['isresponse'],$v['status'])?></td>
                            <td class='table__cell control-block'>
                                <? if (!($v['isresponse'] == 2 && in_array($v['status'],[Responses::$STATUS_REJECT,Responses::$STATUS_EMPLOYER_ACCEPT]))): ?>
                                    <span class="control-block-cont"></span>
                                    <span class="responses__btn change-btn hide__button-change">Изменить</span>
                                <? endif; ?>
                                <? if($v['status'] > Responses::$STATUS_EMPLOYER_ACCEPT): // писать только утвержденным ?>
                                    <a href="<?= MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $id . DS . $v['idusr'] ?>" class="responses__btn btn__green">Написать сообщение</a>
                                <? endif; ?>
                                <? if (in_array($v['status'], [Responses::$STATUS_BEFORE_RATING, Responses::$STATUS_APPLICANT_RATED])): ?>
                                    <a href="<?=MainConfig::$PAGE_SETRATE . DS . $id . DS . $v['idusr']?>"
                                       class="responses__btn btn__orange">Оставить отзыв</a>
                                <? endif; ?>
                            </td>
                        </tr>
                    <? endforeach; ?>
                    </tbody>
                </table>
                <br/>
                <? $this->widget('CLinkPager', array(
                    'pages' => $arResp['pages'],
                    'htmlOptions' => array('class' => 'paging-wrapp'),
                    'firstPageLabel' => '1',
                    'prevPageLabel' => 'Назад',
                    'nextPageLabel' => 'Вперед',
                    'header' => '',
                )) ?>
            <? else: ?>
                <p>Нет заявок</p>
            <? endif; ?>
        </div>
    <? endif; ?>
</div>