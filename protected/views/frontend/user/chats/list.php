<?
    $bUrl = Yii::app()->baseUrl . '/theme/';
    Yii::app()->getClientScript()->registerCssFile($bUrl.'css/chats/list.css');
    Yii::app()->getClientScript()->registerScriptFile($bUrl.'js/chats/list.js', CClientScript::POS_END);
?>
<div class="chat__all">
    <h1>Сообщения</h1>
    <a href="<?=MainConfig::$PAGE_CHATS_LIST_VACANCIES?>" class="chat__item">
        <div class="chat__item-name">Сообщения по вакансиям</div>
        <div class="chat__item-info">
            <div class="chat__info-col">
                <div class="chat__info-header">Кол-во участников</div>
                <div class="chat__info-descr"><?=$viData['vacancies']['cnt-users']?></div>
            </div>
            <div class="chat__info-col">
                <div class="chat__info-header">Сообщений</div>
                <div class="chat__info-descr"><?=$viData['vacancies']['cnt-mess']?></div>
            </div>
            <div class="chat__info-col">
                <div class="chat__info-header">Не прочитанные</div>
                <div class="chat__info-descr chat__info-noread"><?=$viData['vacancies']['cnt-noread']?></div>
            </div>
        </div>
    </a>

    <a href="<?=MainConfig::$PAGE_CHATS_LIST_FEEDBACK?>" class="chat__item">
        <div class="chat__item-name">Обратная связь</div>
        <div class="chat__item-info">
            <div class="chat__info-col"></div>
            <div class="chat__info-col">
                <div class="chat__info-header">Сообщений</div>
                <div class="chat__info-descr"><?=$viData['feedback']['cnt-mess']?></div>
            </div>
            <div class="chat__info-col">
                <div class="chat__info-header">Не прочитанные</div>
                <div class="chat__info-descr chat__info-noread"><?=$viData['feedback']['cnt-noread']?></div>
            </div>
        </div>
    </a>
</div>