<?
    $bUrl = Yii::app()->request->baseUrl;
    $cs = Yii::app()->getClientScript();
    $cs->registerCssFile($bUrl . MainConfig::$CSS . '/private/self-employed.css');
    $cs->registerScriptFile($bUrl . MainConfig::$JS . 'private/self-employed.js', CClientScript::POS_END);
?>
<div class="self-employed">
<? if(!$viData['inn']): ?>
    <h1>Как стать самозанятым</h1>
    <p>Это займет немного времени, бумажные документы не потребуются</p>
    <ol>
        <li>Установите официальное приложение <a href="https://play.google.com/store/apps/details?id=com.gnivts.selfemployed&hl=ru" rel="nofollow" target="_blank">"Мой налог"</a> или перейдите по этой <a href="https://lknpd.nalog.ru/auth/login" rel="nofollow" target="_blank">ссылке</a> и зарегистрируйтесь в качестве самозанятого</li>
        <li>Вернитесь на эту страницу и завершите идентификацию</li>
    </ol>
    <form id="self_employed_form" method="post">
        <div class="self-employed__tab">
            <div class="self-employed__tab-title"><?=$viData['agreement']['name']?></div>
            <div class="self-employed__tab-content"><?echo $viData['agreement']['html']?></div>
        </div>
        <label class="self-employed_label">
            <span>Я прочитал и даю согласие</span>
            <input type="checkbox" name="agreement">
            <span class="self-employed_checkbox"><span></span></span>
        </label>
        <?
        //
        ?>
        <div class="self-employed__tab">
            <div class="self-employed__tab-title"><? echo 'Оферта'; ?></div>
            <div class="self-employed__tab-content"><? echo 'Текст оферты'; ?></div>
        </div>
        <label class="self-employed_label">
            <span>Я прочитал и даю согласие</span>
            <input type="checkbox" name="oferta">
            <span class="self-employed_checkbox"><span></span></span>
        </label>
        <?
        //
        ?>
        <div class="self-employed__tab">
            <div class="self-employed__tab-title"><? echo 'Маркетинговая оферта'; ?></div>
            <div class="self-employed__tab-content"><? echo 'Текст маркетинговой оферты'; ?></div>
        </div>
        <label class="self-employed_label">
            <span>Я прочитал и даю согласие</span>
            <input type="checkbox" name="market_oferta">
            <span class="self-employed_checkbox"><span></span></span>
        </label><br>
        <?
        //
        ?>
        <label class="self-employed_label self-employed_label-text">
            <span>ИНН</span>
            <input type="text" id="inn_input" name="inn" autocomplete="off">
        </label>
        <?
        //
        ?>
        <div class="center">
            <div class="self-employed_btn prmu-btn prmu-btn_normal disable" id="form_btn">
                <span>Начать</span>
            </div>
        </div>
    </form>
<? else: ?>
    <h1>Личный счет</h1>
    <h2>Страница в разработке</h2>
<? endif; ?>
</div>