<?php
require(__DIR__ . '/../vendor/autoload.php');
$env = new \xapon\YiiEnvironment\Environment();
//set yii constants and custom aliases from config
$env->setup();
//$env->showDebug(); // show produced environment configuration
//run app
(new yii\web\Application($env->configWeb))->run();