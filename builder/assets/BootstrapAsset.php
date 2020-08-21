<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

use yii\web\AssetBundle;

/**
 * bootstrap3资源包
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class BootstrapAsset extends AssetBundle
{
    /**
     * @var string 源路径
     * @since 1.0
     */
    public $sourcePath = '@bower/bootstrap/dist';

    /**
     * @var array js
     * @since 1.0
     */
    public $js = [
        'js/bootstrap.min.js',
    ];

    /**
     * @var array css
     * @since 1.0
     */
    public $css = [
        'css/bootstrap.min.css',
        'css/bootstrap-theme.min.css',
    ];

    /**
     * @var array 依赖
     * @since 1.0
     */
    public $depends = [
        JqueryAsset::class,
    ];
}