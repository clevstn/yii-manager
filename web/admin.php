<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true); // `true` or `false`
defined('YII_ENV') or define('YII_ENV', 'dev'); // `dev` or `prod`
defined('BIND_MODULE') or define('BIND_MODULE', 'admin');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
$config['modules']['admin'] = [
    // CRM manager
    'class' => \app\admin\Module::class,
];

(new yii\web\Application($config))->run();