<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$db1 = require __DIR__ . '/db1.php';

use app\builder\filters\BeforeResponseFilter;
use app\builder\filters\BeforeHandleActionFilter;

$config = [
    'id' => 'basic',    // 特别注意：该ID在多入口部署中，禁止修改；必须是basic。
    'name' => 'YII MANAGER CRM',
    'language' => 'en-US', // 终端语言/目标语言
    'sourceLanguage' => 'en-US', // 代码源语言,用于[[I18n]]翻译的源语言,即: messages/zh-CN/app.php中的[[key]]所使用的语言.
    'timeZone' => 'Asia/Shanghai',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@builder' => '@app/builder',
        '@api' => '@app/api',
    ],
    'as beforeHandleAction' => BeforeHandleActionFilter::class,
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'XaAN1rnY43OVTxmc',
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'as beforeResponseSend' => BeforeResponseFilter::class,
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity_front', 'httpOnly' => true],
            'idParam' => '__user_id',
            'returnUrlParam' => '__front_returnUrl',

            // when `enableAutoLogin` is `false`
            'authTimeoutParam' => '__front_expire',
            'absoluteAuthTimeoutParam' => '__front_absoluteExpire',
        ],
        'adminUser' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\AdminUser',
            'enableAutoLogin' => true,
            'loginUrl' => ['admin/site/login'],
            'identityCookie' => ['name' => '_identity_backend', 'httpOnly' => true],
            'idParam' => '__admin_id',
            'returnUrlParam' => '__admin_returnUrl',

            // when `enableAutoLogin` is `false`
            'authTimeoutParam' => '__admin_expire',
            'absoluteAuthTimeoutParam' => '__admin_absoluteExpire',
        ],
        'errorHandler' => [
            'errorAction' => 'error/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource', // 翻译类
                    //'basePath' => '@app/messages', // 消息源根目录,不设置则使用[[应用主体]]默认的
                    //'sourceLanguage' => 'en-US', // 源语言,不设置则使用[[应用主体]]默认的
                    'fileMap' => [
                        // 种类 => 种类文件
                        'app' => 'app.php',
                        'app.admin' => 'admin.php',
                    ],
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'db' => $db,
        'db1' => $db1,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => YII_ENV_DEV,
            /* @see compress/assets.php */
            //'bundles' => require(__DIR__ . '/../compress/assets-prod.php'),
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
