<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

$coreNode = include \Yii::getAlias('@builder/database/menu/node.php');
$coreWhiteList = include \Yii::getAlias('@builder/database/menu/whitelist.php');

/**
 * 菜单
 * @author cleverstone
 * @since ym1.0
 */
$items = [
    // 配置需要RBAC授权访问的菜单项
    // 参数配置请结合`auth_menu`数据表和`rbacManager`组件
    // 该配置项会加载更新到数据表`auth_menu`
    // - label      菜单名称 [默认--]
    // - src        源 [默认--]
    // - icon       图标 [默认--]
    // - label_type 类型，1、菜单 2、功能 [默认1]
    // - link_type  链接类型：1、路由；2、外链 [默认1]
    // - dump_way   跳转方式：_self：内部，_blank：外部 [默认_self]
    // - desc       备注 [默认'']
    // - sort       排序 [默认0]
    // - items      子项 [默认[]]
    'auth' => [
        /*首页*/
        [
            'label' => '首页',
            'src' => 'admin/index/index',
            'icon' => 'glyphicon glyphicon-home',
        ],
        /*后台管理*/
        [
            'label' => '后台管理',
            'src' => '/admin/manager/index',
            'icon' => 'glyphicon glyphicon-blackboard',
            'items' => [
                [
                    'label' => '管理员',
                    'src' => 'admin/manager/index',
                    'icon' => 'glyphicon glyphicon-user',
                ],
                [
                    'label' => '菜单',
                    'src' => 'admin/menu/index',
                    'icon' => 'fa fa-bars',
                ],
                [
                    'label' => '管理组',
                    'src' => 'admin/group/index',
                    'icon' => 'fa fa-list-alt',
                ],
                [
                    'label' => '运维脚本',
                    'src' => 'admin/ops-script/index',
                    'icon' => 'glyphicon glyphicon-flash',
                ],
                [
                    'label' => '队列监控',
                    'src' => 'admin/ops-queue/index',
                    'icon' => 'fa fa-line-chart',
                ],
                [
                    'label' => '计划任务监控',
                    'src' => 'admin/ops-cron/index',
                    'icon' => 'fa fa-tasks',
                ],
                [
                    'label' => '错误日志',
                    'src' => 'admin/error-log/index',
                    'icon' => 'fa fa-exclamation-triangle',
                ],
                [
                    'label' => '应用日志',
                    'src' => 'admin/app-log/index',
                    'icon' => 'glyphicon glyphicon-phone',
                ],
                [
                    'label' => '管理员登录日志',
                    'src' => 'admin/admin-login-log/index',
                    'icon' => 'glyphicon glyphicon-cloud',
                ],
                [
                    'label' => '管理员操作日志',
                    'src' => 'admin/admin-behavior-log/index',
                    'icon' => 'glyphicon glyphicon-calendar',
                ],
                [
                    'label' => '手机区号管理',
                    'src' => 'admin/area-code/index',
                    'icon' => 'glyphicon glyphicon-phone-alt',
                ],
                [
                    'label' => '系统设置',
                    'src' => 'admin/system-setting/index',
                    'icon' => 'glyphicon glyphicon-cog',
                ],
            ],
        ],
    ],
    // 配置不需要RBAC授权访问的菜单项，改配置用于页面展示控制。
    // 控制器中`action-id`需要加入属性[$undetectedActions]中
    // 如果使用`ViewBuilder`进行视图组件构建，则需要加入该项配置。
    // 如果自定义页面，则不需要加入该项配置
    // 参数
    // - label 名称
    // - src 路由，格式：module/controller/action
    'whiteLists' => [
        //['label' => '', 'src' => ''],
    ],
];

// 合并节点
if (!empty($coreNode)) {
    $items['auth'] = array_merge($items['auth'], $coreNode);
}

// 合并白名单
if (!empty($coreWhiteList)) {
    $items['whiteLists'] = array_merge($items['whiteLists'], $coreWhiteList);
}

return $items;