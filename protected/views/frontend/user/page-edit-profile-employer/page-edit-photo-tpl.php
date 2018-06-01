<?php 
    Yii::app()->getClientScript()->registerCssFile('/theme/css/private/page-edit-photo.css');
    Yii::app()->getClientScript()->registerScriptFile('/theme/js/private/page-edit-photo.js', CClientScript::POS_END);
?>
<div class="col-xs-12 photo-pages">
    <div class="btn-white-green-wr -left">
        <a href="<?= MainConfig::$PAGE_EDIT_PROFILE ?>" class="photo-pages__btn-back">&lt вернуться к редактированию профиля</a>
    </div>
    <?php if( count($viData['userPhotos']) < MainConfig::$EMPLOYER_MAX_PHOTOS ): ?>
        <p class="photo-pages__alert">Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</p>
        <div class="photo-pages__btn-block" id="load-img-module">
            <div class="photo-pages__load" id="btn-load-image">Загрузить фото</div>
            <div class="photo-pages__webcam" id="btn-get-snapshot">Сделать снимок</div>
            <div class="clearfix"></div>
        </div>
    <?php else: ?>
        <div class="comm-mess-box">Максимальное кол-во фотографий для профиля: <?= MainConfig::$EMPLOYER_MAX_PHOTOS ?></div>
    <?php endif; ?>
    <div class="row photo-list"> 
        <?php $url = DS . MainConfig::$PATH_EMPL_LOGO . DS; ?>   
        <?php if( count($viData['userPhotos']) > 0 ): ?>
            <?php foreach ($viData['userPhotos'] as $key => $val): ?>
                <div class="col-xs-12 col-sm-4 col-lg-3">
                    <div class="photos__item <?=$val['ismain']==1 ? "main" : ''?>">
                        <a href="<?= $url . $val['photo'] . '000.jpg' ?>" class="photos__item-link">
                            <img src="<?= $url . $val['photo'] . '400.jpg' ?>" alt="" class="photos__item-img">
                        </a>
                        <?php if($val['ismain']): ?>
                            <span class="photos__item-select active"></span>
                        <?php else: ?>
                            <a href="<?=$this->ViewModel->replaceInUrl('','dm',$val['id'])?>" class="photos__item-select js-g-hashint" title="Установить"></a>
                        <?php endif; ?>
                        <?php if(count($viData['userPhotos']) > 1): ?>
                            <a href="<?=$this->ViewModel->replaceInUrl('','del',$val['id'])?>" class="photos__item-delete js-g-hashint" title="Удалить"></a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-xs-12 photo-pages__no-photo">Нет загруженных фото</div>
        <?php endif; ?>
    </div>
    <input type="hidden" name="logo" id='HiLogo'>
</div>
<?php if( count($viData['userInfo']['userPhotos']) < MainConfig::$APPLICANT_MAX_PHOTOS ): ?>
    <?php require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/popup-load-img.php'; ?>
<? endif; ?>