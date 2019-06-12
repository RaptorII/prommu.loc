<?php 
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-edit-photo.css');
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'dist/magnific-popup-min.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/page-edit-photo.js', CClientScript::POS_END); 
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'dist/jquery.magnific-popup.min.js', CClientScript::POS_END);
  $cntPhotos = count($viData['userInfo']['userPhotos']);
  $profile = reset($viData['userInfo']['userAttribs']);
?>
<div class="col-xs-12 photo-pages">
  <div class="btn-white-green-wr -left">
    <a href="<?= MainConfig::$PAGE_EDIT_PROFILE ?>" class="photo-pages__btn-back">&lt вернуться к редактированию профиля</a>
  </div>
  <? if( $cntPhotos < Share::$UserProfile->photosMax ): ?>
    <p class="photo-pages__alert">Добавляйте только свои личные фото, иначе Вы не сможете пройти модерацию! Спасибо за понимание!</p>
    <?
      $arYiiUpload = Share::$UserProfile->arYiiUpload;
      $difPhotos = Share::$UserProfile->photosMax - $cntPhotos;
      // если доступно к загрузке менее 5и фото
      $arYiiUpload['fileLimit']>$difPhotos && $arYiiUpload['fileLimit']=$difPhotos;
      $arYiiUpload['cssClassButton']='photo-pages__upload';
    ?>
    <div class="center">
      <? $this->widget('YiiUploadWidget',$arYiiUpload); ?>
    </div>
  <? else: ?>
    <p class="photo-pages__alert">Максимальное кол-во фотографий для профиля: <?= Share::$UserProfile->photosMax ?></p>
  <? endif; ?>
  <div class="row photo-list">   
    <? if($cntPhotos): ?>
      <?
        $arYiiUpload2 = $arYiiUpload;
        $arYiiUpload2['cssClassButton']='photos__item-edit';
        $arYiiUpload2['callButtonText']='';
        $arYiiUpload2['objSaveMethod']='editPhoto';
      ?>
      <? foreach ($viData['userInfo']['userPhotos'] as $key => $v): ?>
        <?
          $bigSrc = Share::getPhoto($profile['id_user'],2,$v['photo'],'big',$profile['isman']);
          $src = Share::getPhoto($profile['id_user'],2,$v['photo'],'medium',$profile['isman']);
        ?>
        <div class="col-xs-12 col-sm-4 col-lg-3">
          <div class="photos__item <?=$v['ismain']==1 ? "main" : ''?>">
            <? if(!$v['photo'] || !$bigSrc): // фото отсутствует и не редактируется ?>
              <span class="photos__item-nolink" title="<?=$v['signature']?>">
                <img src="<?=$src?>" class="photos__item-img" alt="<?=$v['signature']?>">
              </span>
            <? else: ?>
              <a href="<?=$bigSrc?>" class="photos__item-link" title="<?=$v['signature']?>">
                <img src="<?=$src?>" class="photos__item-img" alt="<?=$v['signature']?>">
              </a>
              <? // кнопка редактирования фото
                $arYiiUpload2['arEditImage'] = [
                    'url' => $bigSrc,
                    'signature' => $v['signature']?:'',
                    'name' => $v['photo']
                  ];
                $this->widget('YiiUploadWidget',$arYiiUpload2); 
              ?>
              <? if(!$v['ismain']): // показываем что можно установить в качестве лого ?>
                <a href="<?=$this->ViewModel->replaceInUrl('','dm',$v['id'])?>" class="photos__item-select js-g-hashint" title="Установить"></a>
              <? endif; ?>
            <? endif; ?>
            <? if($v['ismain']): // показываем что это лого ?>
              <span class="photos__item-select active"></span>
            <? endif; ?>
            <? if(count($viData['userInfo']['userPhotos']) > 1): // удалять можно если фото больше чем 1 ?>
              <a href="<?=$this->ViewModel->replaceInUrl('','del',$v['id'])?>" class="photos__item-delete js-g-hashint" title="Удалить"></a>
            <? endif; ?>
          </div>
        </div>
      <? endforeach; ?>
    <? else: ?>
      <div class="col-xs-12 photo-pages__no-photo">Нет загруженных фото</div>
    <? endif; ?>
  </div>
</div>