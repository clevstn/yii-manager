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
 * jQuery资源包
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class JqueryAsset extends AssetBundle
{
    /**
     * @var string 源路径
     * @since 1.0
     */
    public $sourcePath = '@bower/jquery/dist';

    /**
     * @var array js
     * @since 1.0
     */
    public $js = [
        'jquery.min.js',
    ];
}