<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\assets;

/**
 * 站点相关静态资源
 * @author cleverstone
 * @since ym1.0
 */
class SiteAsset extends BaseAsset
{
    /**
     * @var array css路径
     */
    public $css = [
        'admin_static/site/active.css',
    ];

    /**
     * @var array js路径
     */
    public $js = [
        'admin_static/site/login.js',
    ];
}