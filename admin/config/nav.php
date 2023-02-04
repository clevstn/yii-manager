<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

use app\builder\helper\NavHelper;

/**
 * 顶部导航
 * @see yii\bootstrap\Nav
 * @see app\builder\helper\NavHelper::getItems()
 * @since ym1.0
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
            'label' => '<i class="glyphicon glyphicon-home"></i>&nbsp;' . t('My', 'app.admin'),
            'items' => [
                NavHelper::getMyBrand(),
                '<li class="divider"></li>',
                ['label' => '<i class="glyphicon glyphicon-edit"></i>&nbsp;' . t('Personal Center', 'app.admin'), 'url' => '#'],
                ['label' => '<i class="glyphicon glyphicon-off"></i>&nbsp;' . t('sign out', 'app.admin'), 'url' => '/admin/site/logout'],
            ],
        ],

    ],
];