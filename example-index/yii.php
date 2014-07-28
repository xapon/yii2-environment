#!/usr/bin/env php
<?php
/**
 * Yii console bootstrap file.
 */

require(__DIR__ . '/vendor/autoload.php');
$env = new \xapon\YiiEnvironment\Environment(
    //let Environment detect mode by itself
    null,
    //help Environment find the config directory
    __DIR__.'/config'
);
$env->setup();
$app=new yii\console\Application($env->configConsole);
$exitCode = $app->run();
exit($exitCode);