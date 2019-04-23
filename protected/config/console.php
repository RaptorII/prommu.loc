<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
//        'application.models.factory.*',
        'application.components.*',
    ),

	// application components
	'components'=>array(
        'swiftMailer' => array(
            'class' => 'ext.swiftMailer.SwiftMailer',
        ),

//        'Carbon' => array(
//            'class' => 'ext.Carbon.Carbon',
//        ),

		'db'=>array(
//			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
            'connectionString' => 'mysql:host=localhost;dbname=promo_dev',
//            'connectionString' => 'mysql:host=localhost;dbname=promo',
            'emulatePrepare' => true,
            'username' => 'promo_dev',
            'password' => 'c24QwYQdIfp4va',
            'charset' => 'utf8',
		),
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=testdrive',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
		),
		*/
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);