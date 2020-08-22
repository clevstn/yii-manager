<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/22
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Spinner modal
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class SpinnerAsset extends BaseAsset
{
    /**
     * @var array js路径
     * @since 1.0
     */
    public $js = [
        'libs/js/spinner.js',
    ];

    /**
     * @var array
     * @since 1.0
     */
    public $depends = [
        JqueryAsset::class,
    ];
}