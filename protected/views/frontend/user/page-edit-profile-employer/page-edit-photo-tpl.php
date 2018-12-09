<?
    Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-edit-photo.css');
    Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'dist/magnific-popup-min.css');
    Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/page-edit-photo.js', CClientScript::POS_END); 
    Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'dist/jquery.magnific-popup.min.js', CClientScript::POS_END);
?>
<div class="col-xs-12 photo-pages">
    <div class="btn-white-green-wr -left">
        <a href="<?= MainConfig::$PAGE_EDIT_PROFILE ?>" class="photo-pages__btn-back">&lt вернуться к редактированию профиля</a>
    </div>
    <? if( count($viData['userPhotos']) < MainConfig::$EMPLOYER_MAX_PHOTOS ): ?>
        <p class="photo-pages__alert">Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</p>
        <div class="photo-pages__btn-block" id="load-img-module">
            <div class="photo-pages__load" id="btn-load-image">Загрузить фото</div>
            <div class="photo-pages__webcam" id="btn-get-snapshot">Сделать снимок</div>
            <div class="clearfix"></div>
        </div>
    <? else: ?>
        <div class="comm-mess-box">Максимальное кол-во фотографий для профиля: <?= MainConfig::$EMPLOYER_MAX_PHOTOS ?></div>
    <? endif; ?>
    <div class="row photo-list">   
        <? if( count($viData['userPhotos']) > 0 ): ?>
            <? foreach ($viData['userPhotos'] as $key => $val): ?>
                <div class="col-xs-12 col-sm-4 col-lg-3">
                    <div class="photos__item <?=$val['ismain']==1 ? "main" : ''?>">
                        <a href="<?=$val['src_big']?>" class="photos__item-link">
                            <img src="<?=$val['src_small']?>" alt="" class="photos__item-img">
                        </a>
                        <? if($val['ismain']): ?>
                            <span class="photos__item-select active"></span>
                        <? else: ?>
                            <a href="<?=$this->ViewModel->replaceInUrl('','dm',$val['id'])?>" class="photos__item-select js-g-hashint" title="Установить"></a>
                        <? endif; ?>
                        <? if(count($viData['userPhotos']) > 1): ?>
                            <a href="<?=$this->ViewModel->replaceInUrl('','del',$val['id'])?>" class="photos__item-delete js-g-hashint" title="Удалить"></a>
                        <? endif; ?>
                    </div>
                </div>
            <? endforeach; ?>
        <? else: ?>
            <div class="col-xs-12 photo-pages__no-photo">Нет загруженных фото</div>
        <? endif; ?>
    </div>
    <input type="hidden" name="logo" id='HiLogo'>
</div>
<? if( count($viData['userPhotos']) < MainConfig::$APPLICANT_MAX_PHOTOS ): ?>
    <? require $_SERVER["DOCUMENT_ROOT"] . '/protected/views/frontend/user/popup-load-img.php'; ?>
<? endif; ?>