<?
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/page-edit-photo.css');
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'dist/magnific-popup-min.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/page-edit-photo.js', CClientScript::POS_END); 
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'dist/jquery.magnific-popup.min.js', CClientScript::POS_END);
  $cntPhotos = count($viData['userPhotos']);
  $arYiiUpload = Share::$UserProfile->arYiiUpload;
?>
<div class="col-xs-12 photo-pages">
  <div class="btn-white-green-wr -left">
    <a href="<?= MainConfig::$PAGE_EDIT_PROFILE ?>" class="photo-pages__btn-back">&lt вернуться к редактированию профиля</a>
  </div>
  <? if( $cntPhotos < Share::$UserProfile->photosMax ): ?>
    <p class="photo-pages__alert">Добавляйте пожалуйста логотип своей компании или личные фото. В случае несоответствия фотографий Вы не сможете пройти модерацию! Спасибо за понимание!</p>
    <?  
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
      <? foreach ($viData['userPhotos'] as $key => $val): ?>
        <div class="col-xs-12 col-sm-4 col-lg-3">
          <div class="photos__item <?=$val['ismain']==1 ? "main" : ''?>">
            <a href="<?=$val['src_big']?>" class="photos__item-link" title="<?=$val['signature']?>">
              <img src="<?=$val['src_small']?>" alt="<?=$val['signature']?>" class="photos__item-img">
            </a>
            <? if($val['ismain']): ?>
              <span class="photos__item-select active"></span>
            <? else: ?>
              <a href="<?=$this->ViewModel->replaceInUrl('','dm',$val['id'])?>" class="photos__item-select js-g-hashint" title="Установить"></a>
            <? endif; ?>
            <?
              $arYiiUpload2['arEditImage'] = [
                  'url'=>$val['src_big'],
                  'signature'=>$val['signature'],
                  'name'=>$val['photo']
                ];
              $this->widget('YiiUploadWidget',$arYiiUpload2); 
            ?>
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
</div>