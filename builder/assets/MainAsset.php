<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/18
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

/**
 * Main Asset
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class MainAsset extends CommonAsset
{
    /**
     * @var array
     * @since 1.0
     */
    public $css = [
        'libs/css/common.css',
    ];

    /**
     * @var array
     * @since 1.0
     */
    public $js = [
        'libs/js/common.js',
        'libs/js/service.js',
    ];
}