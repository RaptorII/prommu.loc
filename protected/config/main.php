<?php

include_once dirname(__FILE__).'/../includes/functions.php';

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'prommu.com',
    'sourceLanguage' => 'ru',
    'preload' => array(
        'log',
    ),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.models.factory.*',
        'application.models.exceptions.*',
        'application.models.project.*',
        'application.models.mailing.*',
        'application.models.services.*',
        'application.components.*',
        'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.eauth.*',
        'ext.eauth.services.*',
        'ext.yiiUpload.*',
    ),

    // используемые приложением поведения
    'behaviors' => array(
        'runEnd' => array(
            'class' => 'application.behaviors.WebApplicationEndBehavior',
        ),
    ),

    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => '123456',
            'ipFilters' => array('127.0.0.1', '::1'),
        ),

    ),

    // application components
    'components' => array(
        'loid' => array(
        'class' => 'ext.lightopenid.loid',
	'session'=>[
      		'timeout'=>10*365*24*60*60,
		],
    ),
    'yexcel' => array(
        'class' => 'ext.yexcel.Yexcel'
    ),
    'eauth' => array(
       'class' => 'ext.eauth.EAuth',
            'popup' => false, 
            'cache' => false, 
            'cacheExpire' => 0,
            'services' => array( 
            'mailru' => array(
                //http://api.mail.ru/sites/my/add?siteid=755858&step=4
                'class' => 'MailruOAuthService',
                'client_id' => '755858',
                'client_secret' => '7812200760adc664a84a6c7a19b3f80d',
            ),
            'google_oauth' => array(
                   //https://console.developers.google.com/apis/credentials?project=devprommu
                    'class' => 'GoogleOAuthService',
                    'client_id' => '539704812956-gq2kulh26ra8rbpvbn8s6pvbandsmbe9.apps.googleusercontent.com',
                    'client_secret' => 'MJBrU57vM4hEN396lN1YsUPv',
                    'title' => 'Google (OAuth)',
                ),
            'facebook' => array(
                    //https://developers.facebook.com/apps/1433357213420977/settings/
                    'class' => 'FacebookOAuthService',
                    'client_id' => '1433357213420977',
                    'client_secret' => '50c978a980e66889c328e96d99077fdd',
                ),
            'yandex_oauth' => array(
                    // register your app here: https://oauth.yandex.ru/client/my
                    'class' => 'YandexOAuthService',
                    'client_id' => 'e730911b9e9d4f35a8f911d5d7d05cc9',
                    'client_secret' => '07dd2865b0824a2bb4c7b770e12f27f1',
                    'title' => 'Yandex (OAuth)',
                ),
            'odnoklassniki' => array(
                    //https://apiok.ru/dev/app/create
                    'class' => 'OdnoklassnikiOAuthService',
                    'client_id' => '1254733056',
                    'client_public' => 'CBAGLKMLEBABABABA',
                    'client_secret' => 'DE01D9AB22434EE7AA456602',
                    'title' => 'Odnokl.',
                ),
            'vkontakte' => array(
                    // register your aphttps://vk.com/editapp?id=6174058&section=options
                    'class' => 'VKontakteOAuthService',
                    'client_id' => '6174058',
                    'client_secret' => 'MLyJGzwKFAs7O5pXT3mO',
                ),
            ),
         ),
        'user' => array(
            'allowAutoLogin' => true,
        ),
        'authManager' => array(
            'class' => 'CPhpAuthManager',
        ),

        'swiftMailer' => array(
            'class' => 'ext.swiftMailer.SwiftMailer',
        ),

        'Carbon' => array(
            'class' => 'ext.Carbon.Carbon',
        ),

        // 'debug' => array(           
        //     'class' => 'ext.yii2-debug.Yii2Debug', // manual installation
        //     'allowedIPs' => ['46.39.83.225'],
        //     'highlightCode' => true,
        // ),

        'cache'=>array(
            //'class'=>'system.caching.CDummyCache', // заглушка без кеша
            //'class'=>'system.caching.CFileCache',
            'class'=>'system.caching.CMemCache',
            //'useMemcached' => true,
            'servers'=>array(
                array('host'=>'127.0.0.1', 'port'=>11211, 'weight'=>100),
            ),
        ),

        'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                //'ankety' => 'site/ankety',

                [
                    'class' => 'application.components.SearchRule',
                ],


                'api.<api:[a-z_]+>' => 'api/api',
                'api' => 'api/index',
                'cron.<cron:[a-z_]+>' => 'cron/cron',
                'cron' => 'cron/index',
                '<controller:service>' => 'service/index',
                '<controller:service>/<action:[\w-]+>'=>'<controller>/<action>',
                '<controller:service>/<action:(service_order|card_request|med_request)>/<id:\d+>'=>'<controller>/<action>',
                '<controller:service>/<action:(service_cloud|outstaffing)>/<service:[\w-]+>'=>'<controller>/<action>',
                '<controller:service>/<action:(service_cloud|outstaffing)>/<service:[\w-]+>/<id:\d+>'=>'<controller>/<action>',

                'gii'=>'gii',
                'gii/<controller:\w+>'=>'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
                'vacancies' => 'vacancies/index',
                //'promo' => 'site/ankety',
                // отдельные страницы
                '<action:(razrabotchikam|regulations|work-for-students)>' => 'site/page/<action>',
                // для разделов со страницами
                '<page:(services|articles|ideas)>/<id:[\w-]+>' => 'site/<page>',
                '<page:about>/<section:[\w-]+>' => 'site/<page>',
                '<page:about>/<section:[\w-]+>/<id:[\w-]+>' => 'site/<page>',
                'imfiles' => 'site/imfiles',
                '<controller:user>/<action:services>/<id:[\w-]+>' => '<controller>/<action>',
                // profile
                '<controller:user>/<action:editprofile>/<section:photos>' => '<controller>/<action>',
                // projects
                '<controller:user>/<action:projects>/<id:[\w-]+>' => '<controller>/<action>',
                '<controller:user>/<action:projects>/<id:user-card>/<user_id:\d+>' => '<controller>/<action>',
                '<controller:user>/<action:projects>/<id:[\w-]+>/<section:[\w-]+>' => '<controller>/<action>',
                '<controller:user>/<action:projects>/<id:[\w-]+>/<section:route>/<user_id:\d+>' => '<controller>/<action>',
                '<controller:user>/<action:projects>/<id:[\w-]+>/<section:geo>/<user_id:\d+>' => '<controller>/<action>',
                '<controller:user>/<action:projects>/<id:[\w-]+>/<section:users-select>/<point:\d+>' => '<controller>/<action>',
                // chats
                '<controller:user>/<action:chats>/<section:[\w-]+>' => '<controller>/<action>',
                '<controller:user>/<action:chats>/<section:feedback>/<id:\d+>' => '<controller>/<action>',
                '<controller:user>/<action:chats>/<section:vacancies>/<vacancy:\d+>' => '<controller>/<action>',
                '<controller:user>/<action:chats>/<section:vacancies>/<vacancy:\d+>/<id:\d+>' => '<controller>/<action>',
                // applicant vacancies
                '<controller:user>/<action:vacancies>/<id:\d+>' => '<controller>/<action>',
                '<controller:user>/<action:vacancies>/<section:[\w-]+>' => '<controller>/<action>',
                '<controller:user>/<action:vacancies>/<section:archive>/<id:\d+>' => '<controller>/<action>',
                // vacancy
                '<controller:site>/<action:vacancy>/<id:\d+>/<section:invited>' => '<controller>/<action>',
                // все action с цифрами на общий контроллер
                '<action>/<id:\d+>' => 'site/<action>',
                // для всех страниц
                '<action1:\w+>-<action2:\w+>' => 'site/<action1>_<action2>',
                '<action>' => 'site/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        // uncomment the following to use a MySQL database

           'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=promo_dev',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'WWFf57EfyY4fcaFh',
            'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
        ),
		

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                    'enabled' => true
                    /*
                    'class'=>'CProfileLogRoute', 
                    'levels'=>'profile',            // вывод запросов к БД
                    'enabled'=>true
                    */
                ),
            ),
        ),
    ),

    'params' => array(
        // this is used in contact page
        'adminEmail' => 'web.dev@prommu.ru',
        'site' => 'prommu.com',
    ),
);
