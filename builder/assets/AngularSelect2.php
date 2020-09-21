<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Angular-select2
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class AngularSelect2 extends BaseAsset
{
    public $js = [
        'libs/angular-1.7.5/angular-select2.js',
    ];

    /**
     * @var array 依赖
     * @since 1.0
     */
    public $depends = [
        JqueryAsset::class,
        Select2Asset::class,
        AngularAsset::class,
    ];
}