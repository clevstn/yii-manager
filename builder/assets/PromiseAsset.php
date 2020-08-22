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
 * Promise Es6
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class PromiseAsset extends BaseAsset
{
    /**
     * @var array js路径
     * @since 1.0
     */
    public $js = [
        'libs/promise/es6-promise.min.js',
        'libs/promise/es6-promise.auto.min.js',
    ];
}