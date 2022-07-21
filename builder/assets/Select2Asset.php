<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\assets;

use yii\web\JqueryAsset;

/**
 * Select2资源包<Bootstrap3主题>
 * @author cleverstone
 * @since 1.0
 */
class Select2Asset extends BaseAsset
{
    /**
     * @var array css路径
     */
    public $css = [
        'libs/select2-4.1.0/css/select2.min.css',
        'libs/select2-bootstrap3-theme/select2-bootstrap.min.css',
    ];

    /**
     * @var array js路径
     */
    public $js = [
        'libs/select2-4.1.0/js/select2.min.js',
        /* i18n */
        'libs/select2-4.1.0/js/i18n/zh-CN.js',
    ];

    /**
     * @var array 依赖
     */
    public $depends = [
        JqueryAsset::class,
    ];
}