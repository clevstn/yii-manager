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
        // 应用配置管理器
        'config' => [
            'class' => 'app\components\ConfigManager',
            'cache' => 'cache',
        ],
        // 短信发送组件
        'sms' => [
            'class' => 'app\components\Sms',
            'configs' => [
                // `key` 是该接口的方法名
                'feiGe' => [
                    // 是否使用该接口
                    'enabled' => true,
                    // 接口自定义参数
                    'apiUrl' => 'https://api.4321.sh/sms/send',
                    'apiKey' => 'N909493562',
                    'secret' => '90949011bea77f48',
                    'signId' => '170407',
                ],
            ],
        ],
        // RBAC组件
        'rbacManager' => [
            'class' => 'app\components\RbacManager',
            'cache' => 'cache',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        // 邮件管理器
        'mailManager' => [
            'class' => 'app\components\MailManager',
            'sender' => 'mailer',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            // 当`true`时：开启调试模式，邮件不真正发送，只是保存到`@runtime/mail`。
            // `false`时：想要真正发送，需要配置`transport`参数。
            'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => 'yang_hui_lei@163.com',
                'password' => 'cuicaijuan521',
                'port' => '994',
                'encryption' => 'ssl',
            ],
            /**
             * @see yii\swiftmailer\Message
             */
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => 'yang_hui_lei@163.com',
                // 'replyTo' => '',
                // 'cc' => '',
                // 'bcc' => '',
                // 'subject' => '',
                // 'headers' => [],
            ],
        ],
        'log' => [
            'targets' => [
                'db' => [
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
