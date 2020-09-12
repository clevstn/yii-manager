<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/12
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * icheck库
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class IcheckAsset extends BaseAsset
{
    /**
     * @var array css路径
     * @since 1.0
     */
    public $css = [
        'libs/icheck/skins/minimal/_all.css',
    ];

    /**
     * @var array js路径
     * @since 1.0
     */
    public $js = [
        'libs/icheck/js/icheck.min.js',
    ];

    /**
     * @var array 依赖
     * @since 1.0
     */
    public $depends = [
        JqueryAsset::class,
    ];
}