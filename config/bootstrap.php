<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/6
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

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
    // 后台brand title
    'admin_title' => 'Yii-Manager CRM',
    // 分组配置
    'group_config' => [
        \app\builder\database\config\WebsiteGroup::class,
        \app\builder\database\config\AdminGroup::class,
        \app\builder\database\config\EmailGroup::class,
        \app\builder\database\config\UploadGroup::class,
    ],
];