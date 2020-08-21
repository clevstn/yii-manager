<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\assets;

/**
 * Select2资源包<Bootstrap3主题>
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Select2Asset extends BaseAsset
{
    /**
     * @var array css路径
     * @since 1.0
     */
    public $css = [
        'libs/select2-4.1.0/css/select2.min.css',
        'libs/select2-bootstrap3-theme/select2-bootstrap.min.css',
    ];

    /**
     * @var array js路径
     * @since 1.0
     */
    public $js = [
        'libs/select2-4.1.0/js/select2.min.js',
        /* i18n */
        'libs/select2-4.1.0/js/i18n/zh-CN.js',
    ];

    /**
     * @var array 依赖
     * @since 1.0
     */
    public $depends = [
        JqueryAsset::class,
    ];
}