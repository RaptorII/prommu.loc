<?php
?>

<form id="register_form">

    <div class="login-wrap">

        <svg x="0" y="0" class="svg-bg" />

        <h2 class="login__header">Регистрация</h2>
        <h6 class="login__header">Загрузите фото</h6>

        <div class="login__container">

            <div class="login__photo">
                <p class="center separator">
                    Работодатели оценят вашу открытость
                </p>
                <div class="login__photo-img">
                    <img src="<?=Share::getPhoto($attr['id_user'],2,$attr['photo'],'medium',$attr['isman'])?>" alt="" id="login-img" class="login-img">
                </div>

                <p class="separator center">
                    Допустимые форматы фалов *.jpg и *.png
                </p>
                <p class="separator center pad0">
                    Размер не более 5 Мб
                </p>

                <p class="input">
                    <a href="<?=MainConfig::$PAGE_EDIT_PROFILE . '?ep=1'?>" class="btn-orange">
                        Загрузить фото
                    </a>
                </p>
                <? $cntPhotos = count($viData['userInfo']['userPhotos']); ?>
                <? if( $cntPhotos < Share::$UserProfile->photosMax ): ?>
                    <?
                    $arYiiUpload = Share::$UserProfile->arYiiUpload;
                    $difPhotos = Share::$UserProfile->photosMax - $cntPhotos;
                    // если доступно к загрузке менее 5и фото
                    $arYiiUpload['fileLimit']>$difPhotos && $arYiiUpload['fileLimit']=$difPhotos;
                    ?>
                    <div class="center">
                        <? $this->widget('YiiUploadWidget',$arYiiUpload); ?>
                    </div>
                <? endif; ?>

            </div>

            <div class="login__social-container">
                <span class="register__preview" data-txt="или загрузи из социальных сетей:"></span>
                <div class="reg-social__link-block" >
                    <a href="/user/login?service=facebook" class="reg-social__link fb js-g-hashint" title="facebook" >
                        <span class="mob-hidden">
                            facebook
                        </span>
                    </a>
                    <a href="/user/login?service=vkontakte" class="reg-social__link vk js-g-hashint" title="vkontakte.ru" >
                        <span class="mob-hidden">
                            vkontakte.ru
                        </span>
                    </a>
                    <a href="/user/login?service=mailru" class="reg-social__link ml js-g-hashint" title="mail.ru">
                        <span class="mob-hidden">
                            mail.ru
                        </span>
                    </a>
                    <a href="/user/login?service=odnoklassniki" class="reg-social__link od js-g-hashint" title="odnoklasniki.ru">
                        <span class="mob-hidden">
                            odnoklasniki.ru
                        </span>
                    </a>
                    <a href="/user/login?service=google_oauth" class="reg-social__link go js-g-hashint" title="google">
                        <span class="mob-hidden">
                            google
                        </span>
                    </a>

                    </a>
                </div>
            </div>

            <p class="input">
                <label for="radio-6" class="btn-green">Завершить регистрацию</label>
                <input type="radio" name="radio" id="radio-6">
            </p>

            <p class="separator">
                <a class= "back__away" href="#" onClick="backAway()">
                    Вернуться назад и отредактировать данные
                </a>
            </p>

        </div>


    </div>
</form>