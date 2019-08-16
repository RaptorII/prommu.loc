<? if( $mess = Yii::app()->user->getFlash('Result') ): Yii::app()->user->setFlash('Result', null); ?>
    <br/><br/><p class=" -center"><?= $mess['message'] ?></p>
<? else: ?>
    <?
        Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'phone/getcode.js', CClientScript::POS_END);
        Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'phone/getcode.css');
    ?>
    <div class="row mt20">
        <div class="col-xs-12">
            <p class="-center">Для подтверждения регистрации введите код с смс</p>
        </div>
    </div>
    <div class="restore-form row mt20">
        <div class="col-xs-12 col-sm-6 col-sm-push-3 col-md-4 col-md-push-4">
            <form action="/phone" method="GET" id="get-code-form">
                <? if( $viData['message'] ): ?>
                    <div class="-red -center"><?= $viData['message'] ?></div><br/>
                <? endif; ?>
                <? if($_GET['error']): ?>
                    <div class="-red -center">Вы ввели неверный код. Пожалуйста, повторите попытку!</div><br/>
                <? endif; ?>
                <label class="field-hor">
                    <b>Код</b><input type="text" name="code" id="code-field" maxlength="6" />
                    <input type="hidden" name="id" value="<?= $_GET['id']?>"/>
                     <input type="hidden" name="phone" value="<?= $_GET['phone']?>"/>
                </label>
                <div class="btn-orange-sm-wr">
                    <button type="submit" class="hvr-sweep-to-right btn__orange">Далее</button>
                </div>
                <div class="repeat-sending">Отправить код еще раз</div>
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
<? endif; ?>