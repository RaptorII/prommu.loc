<?
Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'chats/list.css');
Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'chats/list.js', CClientScript::POS_END);
?>
<div class="chat__all">

    <h1>Сообщения</h1>
    <div class="chat-list__wrap-scroll-ico">

        <a href="<?=MainConfig::$PAGE_CHATS_LIST_VACANCIES?>" class="chat__item">
            <span class="chat__item-name">Сообщения по вакансиям</span>
            <span class="chat__item-info">
                <span class="chat__info-col">
                    <span class="chat__info-header">Кол-во участников</span>
                    <span class="chat__info-descr"><?=$viData['vacancies']['cnt-users']?></span>
                </span>
                <span class="chat__info-col">
                    <span class="chat__info-header">Сообщений</span>
                    <span class="chat__info-descr"><?=$viData['vacancies']['cnt-mess']?></span>
                </span>
                <? if($viData['vacancies']['cnt-noread']>0): ?>
                    <span class="chat__info-col">
                        <span class="chat__info-header">Не прочитанные</span>
                        <span class="chat__info-descr chat__info-noread"><?=$viData['vacancies']['cnt-noread']?></span>
                    </span>
                <? endif; ?>
            </span>
        </a>

        <a href="<?=MainConfig::$PAGE_CHATS_LIST_FEEDBACK?>" class="chat__item">
            <span class="chat__item-name">Обратная связь</span>
            <span class="chat__item-info">
                <span class="chat__info-col"></span>
                <span class="chat__info-col">
                    <span class="chat__info-header">Сообщений</span>
                    <span class="chat__info-descr"><?=$viData['feedback']['cnt-mess']?></span>
                </span>
                <? if($viData['feedback']['cnt-noread']>0): ?>
                    <span class="chat__info-col">
                        <span class="chat__info-header">Не прочитанные</span>
                        <span class="chat__info-descr chat__info-noread"><?=$viData['feedback']['cnt-noread']?></span>
                    </span>
                <? endif; ?>
            </span>
        </a>

    </div>
</div>