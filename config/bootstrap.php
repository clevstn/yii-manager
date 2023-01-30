<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
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

    // 后台首页路由，用于后台页面
    'admin_url' => '/admin/index/index',
    // 后台站点名，用于后台抬头、登录、页面Title
    'admin_title' => 'YII MANAGER CRM',
    // 默认附件，当前获取的附件不存在时返回。
    'default_photo' => '/media/image/default.jpg',
    // 分组配置，用于应用配置
    'group_config' => [
        WebsiteGroup::class,
        AdminGroup::class,
        EmailGroup::class,
        UploadGroup::class,
    ],
];