<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

use yii\web\YiiAsset;
use yii\bootstrap\BootstrapPluginAsset;

/**
 * 公共依赖包
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class CommonAsset extends BaseAsset
{
    /**
     * 注册依赖包
     * @var array
     */
    public $depends = [
        BootstrapPluginAsset::class,    // bootstrap3
        Select2Asset::class,            // select2
        YiiAsset::class,                // yii2 js
        FontAwesomeAsset::class,        // fontAwesome图标库
    ];
}