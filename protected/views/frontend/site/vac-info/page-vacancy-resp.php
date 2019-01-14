<?
    Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'vac-info/style.css');
    Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'vac-info/script.js', CClientScript::POS_END);
    //
    // Установка метаданных и заголовка
    //
    // закрываем от индексации
    if ($viData['vac']['index'])
        Yii::app()->clientScript->registerMetaTag('noindex,nofollow', 'robots', null, array());
    // устанавливаем h1
    $this->ViewModel->setViewData('pageTitle', '<h1>' . $viData['vac']['meta_h1'] . '</h1>');
    // устанавливаем description
    Yii::app()->clientScript->registerMetaTag($viData['vac']['meta_description'], 'description');

    $info = Yii::app()->getRequest()->getParam('info');
    switch ($info) {
        case 'resp':
            $title = "Откликнувшиеся на вакансию";
            break; // откликнувшиеся
        case 'reject':
            $title = "Отклоненные заявки";
            break; // отклоненные
        case 'aside':
            $title = "Отложенные заявки";
            break;   // отложенные
        case 'refuse':
            $title = "Отказавшиеся от предложения";
            break;   // Отказавшиеся
        case 'approv':
            $title = "Утвержденные на вакансию";
            break;   // Утвержденные
        default:
            $title = $viData['vac']['meta_title'];
    }
    $this->pageTitle = $title;
    $this->setBreadcrumbsEx(array($viData['vac']['meta_h1'], $_SERVER['REDIRECT_URL']));
    $this->setBreadcrumbsEx(array($title, $_SERVER['REQUEST_URI']));
    //
    $arResp = $viData['vacResponses'];
    $tab = Yii::app()->getRequest()->getParam('info');
?>
<a href="<?=MainConfig::$PAGE_VACANCY . DS . $viData['vac']['id']?>" class="vacs-info__back-link"><span><</span> Назад</a>
<div class="tabs-block">
    <a href="?info=approv"
       class="tabs-block__link<?= ($info == 'approv' ? ' active' : '') ?>">Утвержденные<b><?= ($arResp['counts'][8] ? "({$arResp['counts'][8]})" : "") ?></b></a>
    <a href="?info=resp"
       class="tabs-block__link<?= ($info == 'resp' ? ' active' : '') ?>">Откликнувшиеся<b><?= ($arResp['counts'][4] ? "({$arResp['counts'][4]})" : "") ?></b></a>
    <a href="?info=aside"
       class="tabs-block__link<?= ($info == 'aside' ? ' active' : '') ?>">Отложенные<b><?= ($arResp['counts'][1] ? "({$arResp['counts'][1]})" : "") ?></b></a>
    <a href="?info=reject"
       class="tabs-block__link<?= ($info == 'reject' ? ' active' : '') ?>">Отклоненные<b><?= ($arResp['counts'][3] ? "({$arResp['counts'][3]})" : "") ?></b></a>
    <a href="?info=refuse"
       class="tabs-block__link<?= ($info == 'refuse' ? ' active' : '') ?>">Отказавшиеся<b><?= ($arResp['counts'][5] ? "({$arResp['counts'][5]})" : "") ?></b></a>
    <?/*?><a href="?info=dialog" class="tabs-block__link<?= ($info == 'dialog' ? ' active' : '') ?>">Чат
        вакансии<b><?= ($arResp['countsDiscuss'] ? "({$arResp['countsDiscuss']})" : "") ?></b></a><?*/?>
</div>

<div class="vacs-info" data-page="<?= $tab ?>">
    <?php /*if ($tab == 'dialog'): ?>
        <div class="message">
            <?php if ($mess = Yii::app()->user->getFlash('data')): Yii::app()->user->setFlash('data', null) ?>
                <div class="mess-box <?= $mess['error'] ? 'error' : '' ?> -center"><?= $mess['message'] ?></div>
            <?php endif; ?>
        </div>
        <div class="send-mess-block">
            <b>Оставить сообщение:</b>
            <form method="post">
                <textarea class="send-mess" name="mess"></textarea>
                <input type="hidden" name="id" value="<?= $id ?>"/>
                <div class="btn-white-green-wr">
                    <button type="submit">Отправить</button>
                </div>
            </form>
        </div>
        <div class="discuss-block">
            <?php foreach ($arResp['discuss'] as $key => $val): ?>
                <?php if ($val['name']): ?>
                    <div class="message-wrapp empl">
                        <div class="right">
                            <div class="fio"><?= $val['name'] ?></div>
                            <div class="date"><?= $val['crdate'] ?></div>
                        </div>
                        <div class="message"><?= $val['mess'] ?></div>
                    </div>
                <?php else: ?>
                    <div class="message-wrapp promo">
                        <div class="left">
                            <div class="fio"><?= $val['firstname'] . ' ' . $val['lastname'] ?></div>
                            <div class="date"><?= $val['crdate'] ?></div>
                        </div>
                        <div class="message"><?= $val['mess'] ?></div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>


        <?php
        // display pagination
        $this->widget('CLinkPager', array(
            'pages' => $arResp['pages'],
            'htmlOptions' => array('class' => 'paging-wrapp'),
            'firstPageLabel' => '1',
            'prevPageLabel' => 'Назад',
            'nextPageLabel' => 'Вперед',
            'header' => '',
        )) */?>
    <?php if ($tab != 'dialog')://else:
        if ($tab == 'approv')
            $respInd = 8;
        elseif ($tab == 'resp')
            $respInd = 4;
        elseif ($tab == 'reject')
            $respInd = 3;
        elseif ($tab == 'refuse')
            $respInd = 5;
        elseif ($tab == 'aside')
            $respInd = 1;
        ?>
        <?php if ($arResp['counts'][$respInd]): ?>
        <table class="responses-table">
            <thead>
            <tr>
                <th>
                <th>Имя
                <th>Дата отклика
                <th>Статус
                <th colspan="2">
            </tr>
            </thead>
            <tbody>
            <?php foreach ($arResp['responses'][$respInd] as $key => $val): ?>
                <?php
                $name = $val['firstname'] . ' ' . $val['lastname'];
                $s = $val['status'];
                $r = $val['isresponse'];
                ?>
                <tr class="table__row" data-sid="<?= $val['sid'] ?>" data-status="<?= $val['status'] ?>"
                    data-resp="<?= $val['isresponse'] ?>">
                    <td class='table__cell'><img src="<?= $val['logo'] ?>" alt="<?= $name ?>" class="responses__logo">
                    </td>
                    <td class='table__cell'>
                        <a class='black-orange' href='<?= MainConfig::$PAGE_PROFILE_COMMON . DS . $val['idusr'] ?>'
                           title="номер заявки: <?= $val['sid'] ?>"><?= $val['firstname'] ?> <?= $val['lastname'] ?></a>
                    </td>
                    <td class='table__cell'><span class='rdate' title="Дата заявки"><?= $val['rdate'] ?></span></td>
                    <td class='table__cell status-block'>
                        <?
                        if ($r == 1) { // Отозвался С
                            if ($s == 0) echo "Новая";
                            if ($s == 1) echo "Отложенная";
                            if ($s == 3) echo "Отклонена";
                            if (in_array($s, [5, 6, 7])) echo "Заявка на вакансию подтверждена";
                        } else { // Пригласил Р
                            if ($s == 3) echo "Отказавшийся";
                            if (in_array($s, [2, 4])) echo "Приглашение на вакансию отправлено, ожидайте ответа";
                            if (in_array($s, [5, 6, 7])) echo "Приглашение на вакансию принято";
                        }
                        ?>
                    </td>
                    <td class='table__cell'>
                        <? if (!($r == 2 && $s == 3) && !($r == 2 && $s == 4)): ?>
                            <span class="responses__btn change-btn">Изменить</span>
                        <? endif; ?>
                        <? if($s>4): // писать только утвержденным ?>
                            <a href="<?= MainConfig::$PAGE_CHATS_LIST_VACANCIES . DS . $viData['vac']['id'] . DS . $val['idusr'] ?>" class="responses__btn">Написать сообщение</a>
                        <? endif; ?>
                        <? if (in_array($s, [6, 7]) && !$val['id_vac']): ?>
                            <a href="<?= MainConfig::$PAGE_REVIEWS//MainConfig::$PAGE_SETRATE . DS . $val['id'] . DS . $val['idusr']  ?>"
                               class="responses__btn">Оставить отзыв</a>
                        <? endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <br/>
        <?php
        // display pagination
        $this->widget('CLinkPager', array(
            'pages' => $arResp['pages'],
            'htmlOptions' => array('class' => 'paging-wrapp'),
            'firstPageLabel' => '1',
            'prevPageLabel' => 'Назад',
            'nextPageLabel' => 'Вперед',
            'header' => '',
        )) ?>
    <?php else: ?>
        <p>Нет заявок</p>
    <?php endif; ?>
    <?php endif; ?>
</div>