<!DOCTYPE html>
<html lang="ru">
<head>
  <? $this->renderPartial('../layouts/header_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <?php if(MOBILE_DEVICE): //mob device ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php endif;?>
  <title><?='Регистрация'?></title>
  <meta name="language" content="ru"/>
  <meta name="google-site-verification" content="c2duy0oE7VkxAtjVxH--abHQtP-aYvzCQERllgdLOOQ"/>
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <meta property="og:image" content="https://prommu.com/images/logo.png" />
  <?php
  $bUrl = Yii::app()->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCoreScript('jquery');
  $gcs->registerCssFile($bUrl . MainConfig::$CSS  . 'form/style.css');
  $gcs->registerCssFile($bUrl . MainConfig::$CSS  . 'dist/cropper.min.css');
  $gcs->registerCssFile($bUrl . MainConfig::$JS . 'dist/fancybox/jquery.fancybox.css');

  $gcs->registerScriptFile($bUrl . '/jslib/sourcebuster.min.js', CClientScript::POS_HEAD);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'register/script.js', CClientScript::POS_HEAD);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'snap/snap.svg-min.js', CClientScript::POS_END);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/cropper.min.js', CClientScript::POS_END);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/fancybox/jquery.fancybox.js', CClientScript::POS_END);
  ?>
</head>
  <body>
    <? $this->renderPartial('../layouts/body_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
    <div class="login">
      <div class="login__logo">
        <span class="logo">
          <? $this->renderPartial('../layouts/form/logo'); ?>
        </span>
      </div>
      <form id="register_form" method="post"><?php echo $content; ?><input type="hidden" id="data_input" name="data"></form>
      <div class="login__txt">
        <p>Регистрируясь вы принимаете <a href="javascript:void(0)" id="my_fancybox" >Политику использования данных</a></p>
      </div>
    </div>
    <script>
      var arUrlSteps = <?=json_encode(UserRegister::$URL_STEPS)?>;
      var domainName = "<?=Subdomain::getSiteName()?>";
    </script>
  </body>
</html>
