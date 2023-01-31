<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

use app\builder\helper\NavHelper;

/**
 * 导航项配置
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
            'label' => '<i class="glyphicon glyphicon-home"></i>&nbsp;' . t('My'),
            'items' => [
                NavHelper::getMyBrand(),
                '<li class="divider"></li>',
                ['label' => '<i class="glyphicon glyphicon-edit"></i>&nbsp;' . t('Personal Settings'), 'url' => '#'],
                ['label' => '<i class="glyphicon glyphicon-off"></i>&nbsp;' . t('sign out', 'app.admin'), 'url' => '/admin/site/logout'],
            ],
        ],

    ],
];