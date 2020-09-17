<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/17
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Layer
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class LayerAsset extends BaseAsset
{
    /**
     * @var array
     * @since 1.0
     */
    public $js = [
        'libs/layer/layer.js',
    ];

    /**
     * @var array 依赖
     * @since 1.0
     */
    public $depends = [
        JqueryAsset::class,
    ];
}