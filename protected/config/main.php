<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => '微信营销运营平台',
    'language' => 'zh_cn',
    'defaultController' => 'wechat',

    // preloading 'log' component
    'preload' => array('log'),

    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.components.wechat.*',
    ),

    'modules' => array(
        // uncomment the following to enable the Gii tool

        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1','*'),
        ),
        'gift'=>array(
            'defaultController' => 'manager',
        ),
        'scratch'=>array(
            'defaultController' => 'manager',
        ),
		'wheel'=>array(
            'defaultController' => 'manager',
        ),
        'egg'=>array(
            'defaultController' => 'manager',
        ),
        'registration'=>array(
            'defaultController' => 'manager',
        )

    ),

    // application components
    'components' => array(
        'cache'=>array(
            'class'=>'system.caching.CFileCache',
            'directoryLevel'=>2//缓存目录深度
        ),
        'request' => array(
            'enableCsrfValidation' => false,
            'enableCookieValidation' => true,
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        // uncomment the following to enable URLs in path-format

        'authManager' => array(
            'urlManager' => array( /*'urlFormat'=>'path',
            'showScriptName'=>false,*/
            'rules'=>array(
                '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
            ),
            )
        ),

//		'db'=>array(
//			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
//		),
        // uncomment the following to use a MySQL database

        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=cmge',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => ''
        ),

        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            /*'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                    'categories' => 'system.*',
                ),
                // uncomment the following to show log messages on web pages

                array(
                    'class' => 'CWebLogRoute',
                ),*/
                'routes' => array(
                    array(
                        'class' => 'CFileLogRoute',
                        'levels' => 'error, warning',
                    ),
                //debug
                /*array(
                    'class'=>'CWebLogRoute',  'levels'=>'trace, info, error, warning',
                ),
                array(
                    'class'=>'CFileLogRoute',  'levels'=>'trace, info, error, warning',
                ),*/
                // uncomment the following to show log messages on web pages
                /*
                array(
                    'class'=>'CWebLogRoute',
                ),
                */
            ),
        ),

    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'adminEmail' => 'webmaster@example.com',
        'siteUrl' => 'http://192.168.102.222:82',
        'imagePath'=>'upload/images',
        'scratchPath' => 'upload/market/scratch',
        'wheelPath'=>'upload/market/wheel',
    ),

);