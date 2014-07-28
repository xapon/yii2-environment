<?php
/**
 * Local configuration override.
 * Use this to override elements in the config array (combined from main.php and mode_x.php)
 * NOTE: When using a version control system, do NOT commit this file to the repository.
 */
return [
    // Set YII_DEBUG and YII_ENV constants
    //'yiiDebug' => true,
    //'yiiEnv' => 'test',

    // This is the specific Web application configuration for this mode.
    // Supplied config elements will be merged into the main config array.
    'configWeb' => [
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=localhost;dbname=local-db',
                'username' => 'root',
                'password' => 'root',
            ],
        ],
    ],

    // This is the Console application configuration. Any writable
    // ConsoleApplication properties can be configured here.
    // Use '@' to copy from generated configWeb
    'configConsole' => [

        // Application components
        'components' => [

            // Database
            /*'db' => [
                'dsn' => 'mysql:host=LOCAL_HOST;dbname=LOCAL_DB',
                'username' => 'USERNAME',
                'password' => 'PASSWORD',
            ],*/

        ],

    ],
];