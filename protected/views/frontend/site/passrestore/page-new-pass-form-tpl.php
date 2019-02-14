<? Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'passrestore/style.css'); ?>
<? if( $mess = Yii::app()->user->getFlash('Result') ): Yii::app()->user->setFlash('Result', null); ?>
    <br><br><p class=" -center"><?= $mess['message'] ?></p>
<? else: ?>
    <? if( $viData['noform'] == 1 ): ?>
        <div class="-red -center"><?= $viData['message'] ?></div><br><br>
    <? else: ?>
        <div class="row" id="restore-pass">
            <div class="col-sm-12">
                <h1 class="-center">Введите новый пароль</h1><br>
            </div>
            <? if( $viData['message'] ): ?>
                <div class="col-xs-12">
                    <div class="-red -center"><?= $viData['message'] ?></div><br>
                </div>
            <? endif; ?>
            <div class="col-xs-10 col-xs-push-1 col-sm-4 col-sm-push-4">
                <form action="/<?= MainConfig::$PAGE_NEW_PASS ?>" method="post">
                    <label>
                        <input type="password" name="pass" value="" placeholder="Пароль" />
                    </label>
                    <label>
                        <input type="password" name="passrep" value="" placeholder="Подтверждение" />
                    </label>
                    <button type="submit" class="prmu-btn"><span>Далее</span></button>
                    <input type="hidden" name="t" value="<?= $viData['token'] ?>"/>
                    <input type="hidden" name="uid" value="<?= $viData['idus'] ?>"/>
                </form>
                <script type="text/javascript">
                    jQuery(function($){ $("[name=pass]").focus(); });
                </script>
            </div>
        </div>
    <? endif; ?>
<? endif; ?>