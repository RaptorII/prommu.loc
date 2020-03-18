<?php $this->ViewModel->init(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <? $this->renderPartial('../layouts/header_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <?php if(MOBILE_DEVICE): //mob device ?>
    <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php endif;?>
  <title><?='Публикация вакансии'?></title>
  <meta name="language" content="ru"/>
  <meta name="google-site-verification" content="c2duy0oE7VkxAtjVxH--abHQtP-aYvzCQERllgdLOOQ"/>
  <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
  <meta property="og:image" content="https://prommu.com/images/logo.png" />
  <?php
  $bUrl = Yii::app()->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCoreScript('jquery');
  $gcs->registerCssFile($bUrl . MainConfig::$CSS  . 'form/style.css');
  $gcs->registerCssFile($bUrl . MainConfig::$CSS  . 'private/personal.css');
  $gcs->registerCssFile($bUrl . MainConfig::$CSS . 'dist/jquery-ui.min.css');
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'private/personal.js', CClientScript::POS_HEAD);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'vacancy/create.js', CClientScript::POS_HEAD);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'snap/snap.svg-min.js', CClientScript::POS_END);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . '/dist/libs.js', CClientScript::POS_END);
  $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'dist/nicEdit.js', CClientScript::POS_END);
  ?>
</head>
  <body>
    <? $this->renderPartial('../layouts/body_partial/' . Subdomain::getCacheData()->id); // data for every site ?>
    <div class="container container-medium">
      <div class="container__logo">
        <? $this->renderPartial('../layouts/form/logo'); ?>
      </div>
      <form method="post" data-params='{"ajax":"true"}'><?php echo $content; ?></form>
    </div>
  </body>
</html>