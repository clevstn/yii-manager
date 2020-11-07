<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

use app\builder\helper\NavHelper;

/**
 * 导航项配置
 * @see yii\bootstrap\Nav
 * @see app\builder\helper\NavHelper::getItems()
 * @since 1.0
 */
return [
    // 导航左侧
    'left' => [
        // e.g.
    ],
    // 导航右侧
    'right' => [
        // 个人中心
        [
            'label' => '<i class="glyphicon glyphicon-home"></i>&nbsp;我的',
            'items' => [
                NavHelper::getMyBrand(),
                '<li class="divider"></li>',
                ['label' => '<i class="glyphicon glyphicon-edit"></i>&nbsp;个人设置', 'url' => '#'],
                ['label' => '<i class="glyphicon glyphicon-off"></i>&nbsp;退出', 'url' => '/admin/site/logout'],
            ],
        ],

    ],
];