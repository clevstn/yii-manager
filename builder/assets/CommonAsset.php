<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

use yii\web\AssetBundle;

/**
 * 公共资源包
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class CommonAsset extends AssetBundle
{
    /**
     * asset source path
     * @var string
     */
    public $sourcePath = '@builder/static';

    /**
     * css bundle
     * @var array
     */
    public $css = [
        'libs/css/common.css',
    ];

    /**
     * js bundle
     * @var array
     */
    public $js = [
        'libs/js/common.js',
    ];

    /**
     * depend bundle
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}