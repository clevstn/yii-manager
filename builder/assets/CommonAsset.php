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
        PromiseAsset::class,            // Es6 Promise
        BootstrapPluginAsset::class,    // Bootstrap3
        Toastr2::class,                 // ToaStr2
        Select2Asset::class,            // Select2
        YiiAsset::class,                // Yii2 js
        FontAwesomeAsset::class,        // FontAwesome
        SweetAlert2::class,             // SweetAlert2
        SpinnerAsset::class,            // Spinner
    ];
}