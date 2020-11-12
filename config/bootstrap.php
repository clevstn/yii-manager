<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

use app\builder\database\config\AdminGroup;
use app\builder\database\config\EmailGroup;
use app\builder\database\config\UploadGroup;
use app\builder\database\config\WebsiteGroup;

/**
 * This is used for yii-manager parameters.
 * Please the users config the parameters to `params.php`.
 */
return [
    // +----------------------------------------------------------------------
    // | 后台配置
    // +----------------------------------------------------------------------

    // 首页
    'admin_url' => '/admin/index/index',
    // 后台[商标]
    'admin_title' => 'YII MANAGER CRM',
    // 默认头像Url
    'default_photo' => '/media/image/default.jpg',
    // 分组配置
    'group_config' => [
        WebsiteGroup::class,
        AdminGroup::class,
        EmailGroup::class,
        UploadGroup::class,
    ],
];