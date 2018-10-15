<?php
error_reporting(1);
ini_set('display_errors', '1');
// -------- WA -------------
define('DOCROOT', $_SERVER['DOCUMENT_ROOT']);
define('DS', '/');

//
define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_ENABLE_EXCEPTION_HANDLER', false);
error_reporting(0);
error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL);
// -------- -- -------------

// PROMO
// change the following paths if necessary
$yii=dirname(__FILE__).'/framework/yii.php';
//$config=dirname(__FILE__).'/protected/config/main.php';
$config = dirname(__FILE__).'/protected/config/frontend.php';

// // remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3); // specify how many levels of call stack should be shown in each log message

require_once($yii);


//Yii::createWebApplication($config)->run();
// стартуем приложение с помощью нашего WebApplicaitonEndBehavior, указав ему, что нужно загрузить фронтенд
Yii::createWebApplication($config)->runEnd('frontend');


//Yii::app()->cache->flush();
