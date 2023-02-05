<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$db1 = require __DIR__ . '/db1.php';

$config = [
    'id' => 'yii-manager-basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands\cliscript',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@tests' => '@app/tests',
        '@builder' => '@app/builder',
    ],
    'modules' => [
        // 计划任务模块
        'cron' => [
            'class' => \app\commands\cronjobs\Module::class,
        ],
        // 队列任务模块
        'queue' => [
            'class' => \app\commands\queuejobs\Module::class,
        ],
        // 守护进程模块
        'process' => [
            'class' => \app\commands\processjobs\Module::class,
        ],
    ],
    'components' => [
        // RBAC组件
        'rbacManager' => [
            'class' => 'app\components\RbacManager',
            'cache' => 'cache',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    //'class' => 'yii\log\FileTarget',
                    'class' => 'app\components\LogDbTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'db1' => $db1,
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
