<?php if( $mess = Yii::app()->user->getFlash('Result') ): Yii::app()->user->setFlash('Result', null); ?>
    <br />
    <br />
    <p class=" -center"><?= $mess['message'] ?></p>
<?php else: ?>
    <div class="row mt20">
        <div class="col-xs-12">
            <p class="-center">Для восстановления пароля необходимо ввести E-mail или Мобильный номер телефона, указанный Вами при регистрации.<br>При восстановлении пароля с помощью номера телефона необходимо указывать код страны и телефон в подходящем формате.<br>Для РФ это +7(888)888-88-88, для Украины +380(88)888-88-88, для Белоруссии +375(88)888-88-88</p>
        </div>
    </div>
    <div class="restore-form row mt20">
        <div class="col-xs-12 col-sm-8 col-sm-push-2 col-md-6 col-md-push-3">
            <form action="/<?= MainConfig::$PAGE_PASS_RESTORE ?>" method="post">
                <?php if( $viData['message'] ): ?>
                    <div class="-red -center"><?= $viData['message'] ?></div>
                    <br />
                <?php endif; ?>
                <label class="field-hor">
                    <b style="white-space:nowrap">E-mail или Моб. телефон</b><input type="text" name="email" value="<?= $viData['email'] ?>"/>
                </label>
                <div class="btn-orange-sm-wr">
                    <button type="submit" class="hvr-sweep-to-right">Далее</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
