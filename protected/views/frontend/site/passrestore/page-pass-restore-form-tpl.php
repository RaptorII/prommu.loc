<? Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'passrestore/style.css'); ?>
<? if(Yii::app()->user->hasFlash('success')): ?>
    <br><br><p class=" -center"><?=Yii::app()->user->getFlash('success')?></p>
<? else: ?>
    <div class="row" id="restore-pass">
        <div class="col-xs-12">
            <h1 class="-center">ВОССТАНОВЛЕНИЕ ПАРОЛЯ</h1><br>
            <p class="-center">Для восстановления пароля необходимо ввести E-mail или Мобильный номер телефона, указанный Вами при регистрации.<br>При восстановлении пароля с помощью номера телефона необходимо указывать код страны и телефон в подходящем формате.<br>Для РФ это +7(888)888-88-88, для Украины +380(88)888-88-88, для Белоруссии +375(88)888-88-88</p><br>
        </div>
        <div class="col-xs-10 col-xs-push-1 col-sm-4 col-sm-push-4">
            <form method="POST">
                <? if(!empty($viData['message'])): ?>
                  <div class="-red -center"><?= $viData['message'] ?></div><br />
                <? endif; ?>
                <label>
                    <input
                      type="text"
                      name="email"
                      value="<?=Yii::app()->getRequest()->getParam('email')?>"
                      placeholder="E-mail или Моб. телефон"
                      autocomplete="off"
                    />
                </label>
                <button type="submit" class="prmu-btn"><span>Далее</span></button>
            </form>
        </div>
    </div>
<? endif; ?>