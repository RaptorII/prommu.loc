<?
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'private/popup-load-img.css');
  Yii::app()->getClientScript()->registerCssFile(MainConfig::$CSS . 'dist/cropper.min.css');
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'private/popup-load-img.js', CClientScript::POS_END);
  Yii::app()->getClientScript()->registerScriptFile(MainConfig::$JS . 'dist/cropper.min.js', CClientScript::POS_END);
?>
<canvas class="snapshot__canvas"></canvas>
<div class="body__content">
  <div class="load-img__bg-veil"></div>
  <div class="load-img__form">
    <span class="load-img__close"></span>
    <span class="load-img__load"></span>
    <div class="load-img__mess"></div>
    <div class="load-img__err-btn">OK</div>
    <div class="load-img__snapshot">
      <video autoplay playsinline></video>
      <div class="load-img__shot-btn"></div>
      <img src="" class="load-img__shot-res">
      <div class="load-img__shot-btns">
        <div class="load-img__shot-done"></div>
        <div class="load-img__shot-rst"></div>
      </div>   
    </div>
    <div class="load-img__crop">
      <div>
        <div class="load-img__crop-cont"></div>
        <div class="load-img__prev-cont">
          <div class="load-img__prev load-img__prev-lg"></div>
          <div class="load-img__prev load-img__prev-sm"></div>
        </div>
      </div>
      <img alt="" class="load-img__crop-img">
      <div class="load-img__btn-block">
        <span class="cropper__save save-crop" title="Сохранить"></span>  
        <span class="cropper__rotate-left" title="Повернуть на 90 градусов влево"></span>
        <span class="cropper__rotate-right" title="Повернуть на 90 градусов вправо"></span>
      </div>
    </div>
    <form method="post" enctype="multipart/form-data" id="form-load-img">
      <input type="file" name="photo" id="input-load-img">
    </form>
  </div>
</div>