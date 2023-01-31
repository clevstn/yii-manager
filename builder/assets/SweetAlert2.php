<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\assets;

/**
 * SweetAlert2
 * @author cleverstone
 * @since ym1.0
 */
class SweetAlert2 extends BaseAsset
{
    /**
     * @var array css路径
     */
    public $css = [
//        'libs/sweetalert2/theme/sweetalert2-bootstrap4.min.css',
//        'libs/sweetalert2/theme/sweetalert2-default.min.css',
//        'libs/sweetalert2/theme/sweetalert2-borderless.min.css',
//        'libs/sweetalert2/theme/sweetalert2-dark.min.css',
        'libs/sweetalert2/theme/sweetalert2-minimal.min.css',
//        'libs/sweetalert2/theme/sweetalert2-material-ui.min.css',
//        'libs/sweetalert2/theme/sweetalert2-wordpress-admin.min.css',
//        'libs/sweetalert2/theme/sweetalert2-bulma.min.css',
    ];

    /**
     * @var array js路径
     */
    public $js = [
        'libs/sweetalert2/sweetalert2.min.js',
    ];

    /**
     * 注册依赖包
     * @var string[]
     */
    public $depends = [
        PromiseAsset::class,            // Es6 Promise
    ];
}