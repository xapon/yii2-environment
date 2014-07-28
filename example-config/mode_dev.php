<?php

/**
 * Development configuration
 * Usage:
 * - Local website
 * - Local DB
 * - Show all details on each error
 * - Gii module enabled
 */
return [
    'yiiDebug' => true,
    'configWeb' => [
        'bootstrap' => ['debug'],
        'modules' => [
            'debug' => 'yii\debug\Module',
            'gii' => 'yii\gii\Module',
        ],
        'components' => [
        ],
        'params'=>[
        ]
    ],
];
