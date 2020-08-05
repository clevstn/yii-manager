<?php
/**
 * Introduce:
 * The `compiler.jar` download for https://github.com/google/closure-compiler, The Plugins used of Js compress and merge.
 *  Command params:
 *  --js [[Specifies the JS file to be compressed]]
 *  --js_output_file [[The compressed JS file name and position]]
 *  --create_source_map [[File name and location of the compressed SourceMap]]
 *  --source_map_format [[SourceMap version]]
 *
 * The `yuicompressor.jar` download for https://github.com/yui/yuicompressor, The Plugins used of Css compress and merge.
 *
 * Step 1:
 * Dependent Java environment, The java installation address:
 * https://www.oracle.com/java/technologies/javase/javase-jdk8-downloads.html
 *
 * Step 2:
 * Production publish package command:
 * ```
 * Console execute:
 * yii asset compress/assets.php compress/assets-prod.php
 *
 * Step 3:
 * configure file "config/web.php":
 *    'assetManager' => [
 *          'appendTimestamp' => true,
 *          'bundles' => require(__DIR__ . '/../compress/assets-prod.php'),
 *     ],
 * ```
 */

// In the console environment, some path aliases may not exist. Please define these:
Yii::setAlias('@webroot', __DIR__ . '/../web');
Yii::setAlias('@web', '/');

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'java -jar compiler.jar --js {from} --js_output_file {to}',
    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'java -jar yuicompressor.jar --type css {from} -o {to}',
    // Whether to delete asset source after compression:
    'deleteSource' => false,
    // The list of asset bundles to compress:
    'bundles' => [
        'app\assets\AppAsset',
        'app\assets\SiteAsset',
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ],
    // Asset bundle for compression output:
    'targets' => [
        'all' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/min',
            'baseUrl' => '@web/min',
            'js' => 'js/all-{hash}.js',
            'css' => 'css/all-{hash}.css',
            'depends' => [
                'yii\web\YiiAsset',
                'yii\web\JqueryAsset',
                'yii\bootstrap\BootstrapAsset',
            ],
        ],
        'app' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/min',
            'baseUrl' => '@web/min',
            'js' => 'js/app-{hash}.js',
            'css' => 'css/app-{hash}.css',
            'depends' => [
                'app\assets\AppAsset',
            ],
        ],
        'site' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/min',
            'baseUrl' => '@web/min',
            'js' => 'js/site-{hash}.js',
            'css' => 'css/site-{hash}.css',
            'depends' => [
                'app\assets\SiteAsset',
            ],
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ],
];