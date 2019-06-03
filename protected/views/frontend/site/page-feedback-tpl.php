<?php Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/theme/css/feedback/style.css'); ?>
<?php if( $mess = Yii::app()->user->getFlash('Message') ): ?>
    <div class="msg-block"><?= $mess['message'] ?></div>
    <script type="application/javascript">
        var type = '<?= $mess['type'] ?>';
        if( parseInt(type) == 2 ) var type = 'Соискатель';
        else var type =  'Работодатель';

        window.dataLayer = window.dataLayer || [];
        dataLayer.push({
            'event' : type
        });
    </script>
<?php else: ?>
    <?php
        $isUser = in_array(Share::$UserProfile->type, [2,3]);
        if(!$isUser){
            Yii::app()->getClientScript()->registerScriptFile('https://www.google.com/recaptcha/api.js', CClientScript::POS_END);
        }
        Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/theme/js/feedback/script.js', CClientScript::POS_END);
    ?>
    <div class='row'>
        <div class='col-xs-12'>
            <form action='' id='F1feedback' method='post' class="feedback-page__form">
                <h1 class="feedback-page__title">обратная связь</h1>
                <?php if(!empty($error)): ?>
                <div class="error-block -center -red"><?=$error?></div>
            <?php endif; ?>
                <?php if(!$isUser): ?>
                    <label class="feedback-page__label feedback-page__label-select">
                        <select name="type" id="CBtype" class="feedback-page__select" title="Выберите тип запроса" equired>
                            <option value="" disabled<?=(!in_array($viData['type'], [2,3]) ? ' selected': '')?>>Тип запроса (Соискатель / Работодатель)</option>
                            <option value="2"<?=($viData['type']==2 ? ' selected': '')?>>Соискатель</option>
                            <option value="3"<?=($viData['type']==3 ? ' selected': '')?>>Работодатель</option>
                        </select>
                    </label>
                <?php endif; ?>
                <label class="feedback-page__label">
                    <?
                    $name = '';
                    if(!empty(Share::$UserProfile->exInfo->name)){
                        $name .= Share::$UserProfile->exInfo->name;
                    }
                    if(!empty(Share::$UserProfile->exInfo->firstname)){
                        $name .= ' '.Share::$UserProfile->exInfo->firstname;
                    } 
                    ?>
                    <input data-field-check='name:Имя,empty,max:100' id='EdName' name='name' type='text' value="<?=($viData['name'] ? $viData['name'] : $name)?>" class="feedback-page__input" placeholder="Имя" title="Имя">
                </label>
                <label class="feedback-page__label">
                    <input data-field-check='name:Email,empty,email' id='EdEmail' name='email' type='text' value="<?=($viData['email'] ? $viData['email'] : Share::$UserProfile->exInfo->email )?>" class="feedback-page__input" placeholder="Email" title="Email">
                </label>

                <label class="feedback-page__label">
                    <input data-field-check='name:Тема,empty,max:100' id='EdTheme' name='theme' type='text' class="feedback-page__input" placeholder="Тематика запроса" title="Тематика запроса" value="<?=($viData['theme'] ? $viData['theme'] : '')?>">
                </label>


                <label class="feedback-page__label">
                    <select data-field-check='name:Направление,empty,max:100' id='EdWay' name='direct' type='text' class="feedback-page__input" placeholder="Направление запроса" title="Направление запроса">
                        <option value="0" selected disabled>Направление запроса</option>
                        <?foreach ($viData['directs'] as $key => $value):?>
                            <option value="<?=$value['id']?>"><?=$value['name']?></option>
                        <?endforeach;?>
                    </select>
                </label>

                <label class="feedback-page__label">
                    <textarea name="text" data-field-check='name:Текст,empty' id='MText' placeholder="Сообщение" class="feedback-page__textarea" title="Сообщение"><?=($viData['text'] ? $viData['text'] : '')?></textarea>
                </label>
                <div class="clearfix"></div>
                <? if($viData['use_recaptcha']==true): ?>
                    <div class="g-recaptcha-parent">
                        <?php if( $viData['element'] == 'recaptcha' ): ?>
                            <span class="red"><?= $viData['hint'] ?></span>
                        <?php endif; ?>
                        <div class="g-recaptcha" data-sitekey="6Lf2oE0UAAAAAKL5IvtsS1yytkQDqIAPg1t-ilNB"></div>
                    </div>
                <? endif; ?>
                <div class="clearfix"></div>
                <button type="submit" class="feedback-page__button">Отправить</button>
                <div class="clearfix"></div>
                <input type="hidden" name="autotype" value="<?= Share::$UserProfile->type ?>"/>
                <input type="hidden" class="id" name="id" value="<?= Share::$UserProfile->exInfo->id ?>">
                <input type="hidden" class="referer" name="referer" value="">
                <input type="hidden" class="transition" name="transition" value="">
                <input type="hidden" class="canal" name="canal" value="">
                <input type="hidden" class="campaign" name="campaign" value="">
                <input type="hidden" class="content" name="content" value="">
                <input type="hidden" class="keywords" name="keywords" value="">
                <input type="hidden" class="point" name="point" value="">
                <input type="hidden" class="last_referer" name="last_referer" value="">
            </form>
        </div>
    </div>
<?php endif; ?>