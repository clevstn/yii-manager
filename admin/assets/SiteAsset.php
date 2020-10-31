<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\assets;

/**
 * 站点相关静态资源
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
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