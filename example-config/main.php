<?php

/**
 * Main configuration.
 * All properties can be overridden in mode_<mode>.php files
 */

return [

    // Set yiiPath
    'yiiPath' => __DIR__ . '/../vendor/yiisoft/yii2/Yii.php',

    // Set YII_DEBUG flag
    'yiiDebug' => true,
    // Set YII_ENV constant. In empty, current mode will be used instead
    //'yiiEnv'=>'test',


    // Aliases
    'aliases' => [
        // uncomment the following to define a path alias
        //'@local' => 'path/to/local-folder'
    ],

    // This is the main Web application configuration. Any writable
    // CWebApplication properties can be configured here.
    'configWeb' => [
        'basePath' => dirname(__DIR__),
        'name' => 'My Web Application',


        // Preloading 'log' component
        'bootstrap' => ['log'],
        
        // Application components
        'components' => [
        
            'user' => [
                // enable cookie-based authentication
                'allowAutoLogin' => true,
            ],

            // Database
            'db' => [
                'dsn' => '', //override in config/mode_<mode>.php
                'username' => '', //override in config/mode_<mode>.php
                'password' => '', //override in config/mode_<mode>.php
                'charset' => 'utf8',
            ],
        ],

    ],

    // This is the Console application configuration. Any writable
    // ConsoleApplication properties can be configured here.
    // Leave array empty if not used.
    // Use value '@' to copy from generated configWeb.
    'configConsole' => [
    
        'basePath' =>'@',
        'name' => 'My Console Application',
        
        // Aliases
        'aliases' => '@',

        // Preloading components
        'bootstrap' => '@',

        // Autoloading model and component classes
        'import'=>'@',

        'controllerMap' => [
            'fixture' => [
                'class' => 'yii\faker\FixtureController',
            ],
        ],

        // Application componentshome
        'components'=>[

            // Database
            'db'=>'@',

        ],

    ],

];
