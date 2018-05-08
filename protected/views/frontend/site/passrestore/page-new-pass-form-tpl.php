<?php if( $mess = Yii::app()->user->getFlash('Result') ): Yii::app()->user->setFlash('Result', null); ?>
    <br />
    <br />
    <p class=" -center"><?= $mess['message'] ?></p>
<?php else: ?>

    <?php if( $viData['noform'] == 1 ): ?>
        <br /><br />
        <div class  ="-red -center"><?= $viData['message'] ?></div>
    <?php else: ?>
        <div class="row mt20">
            <div class="col-sm-6 col-sm-push-3">
                <p class="-center">Введите новый пароль</p>
            </div>
        </div>
        <div class="new-pass-form row mt20">
            <div class="col-xs-12">
                <?php if( $viData['message'] ): ?>
                    <div class="-red -center"><?= $viData['message'] ?></div>
                    <br />
                <?php endif; ?>
            </div>
            <div class="col-xs-10 col-xs-push-1  col-sm-6 col-sm-push-3">
                <form action="/<?= MainConfig::$PAGE_NEW_PASS ?>" method="post">
                    <input type="hidden" name="t" value="<?= $viData['token'] ?>"/>
                    <input type="hidden" name="uid" value="<?= $viData['idus'] ?>"/>
                    <label class="field-hor">
                        <b>Пароль</b><input type="password" name="pass" value=""/>
                    </label>
                    <label class="field-hor">
                        <b>Подтверждение</b><input type="password" name="passrep" value=""/>
                    </label>
                    <div class="btn btn-orange-sm-wr">
                        <button type="submit" class="hvr-sweep-to-right">Далее</button>
                    </div>
                </form>

                <script type="text/javascript">
                    $("[name=pass]").focus();
                </script>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>
