<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/22
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

/**
 * Toastr2
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Toastr2 extends BaseAsset
{
    /**
     * @var array css路径
     * @since 1.0
     */
    public $css = [
        'libs/toastr2/toastr.css',
    ];

    /**
     * @var array js路径
     * @since 1.0
     */
    public $js = [
        'libs/toastr2/toastr.min.js',
    ];
}