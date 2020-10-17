<?php

/**
 * 导航项配置
 * @see yii\bootstrap\Nav
 * @see app\builder\helper\NavHelper::getItems()
 */

use app\builder\helper\NavHelper;

return [
    // 个人中心
    [
        'label' => '个人中心',
        'items' => [
            NavHelper::getMyBrand(),
            '<li class="divider"></li>',
            ['label' => '运维脚本', 'url' => '/admin/ops-script/index'],
            ['label' => '应用日志', 'url' => '/admin/app-log/index'],
        ],
    ],
];