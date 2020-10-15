<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true); // `true` or `false`
defined('YII_ENV') or define('YII_ENV', 'dev'); // `dev` or `prod`

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
$config['modules']['v1'] = [
    // Resource Version 1.0
    'class' => \app\api\v1\Module::class,
];
$config['modules']['v2'] = [
    // Resource Version 2.0
    'class' => \app\api\v2\Module::class,
];

$bindMap = YII_ENV_DEV ? ['debug', 'gii', 'v1', 'v2'] : ['v1', 'v2'];
defined('BIND_MODULE') or define('BIND_MODULE', $bindMap);

(new yii\web\Application($config))->run();