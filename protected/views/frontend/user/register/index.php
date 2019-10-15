<!DOCTYPE html>
<html lang="ru">
<head>
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
    $gcs->registerCssFile($bUrl . MainConfig::$CSS  . 'register/style.css');
    $gcs->registerScriptFile($bUrl . MainConfig::$JS . 'register/script.js', CClientScript::POS_END);
  ?>
</head>
<body><?php echo $content; ?></body>
</html>
