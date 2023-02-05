<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

use app\models\AuthMenu;

$coreNode = include \Yii::getAlias('@builder/database/menu/node.php');
$coreWhiteList = include \Yii::getAlias('@builder/database/menu/whitelist.php');

/**
 * 菜单
 * @author cleverstone
 * @since ym1.0
 */
$items = [
    // 配置需要rbac授权访问的菜单项
    // 参数配置请结合`auth_menu`数据表和`rbacManager`组件
    // 该配置项会加载更新到数据表`auth_menu`
    // - label      菜单名称 [默认'--']
    // - src        源 [默认'']
    // src必须是唯一的，一级栏目如果为目录，则需要自定义src，自定义格式为：固定字段(module)/模块ID/控制器ID
    // 如果数据表中src被手动修改掉，则该文件src也需要同步修改
    // - icon       图标 [默认'']
    // - label_type 类型，1、菜单 2、功能 [默认1]
    // - link_type  链接类型：1、路由；2、外链 [默认1]
    // - dump_way   跳转方式：_self：内部，_blank：外部 [默认_self]
    // - desc       备注 [默认'']  注：用于行为日志记录，建议填写
    // - sort       排序 [默认0]，注：更新时会自动生成
    // - items      子项 [默认[]]
    'auth' => [
        /*后台管理*/
        [
            'label' => '后台管理',
            'src' => 'module/admin/manager',
            'icon' => 'glyphicon glyphicon-blackboard',
            'items' => [
                [
                    'label' => '管理员',
                    'src' => 'admin/manager/index',
                    'icon' => 'glyphicon glyphicon-user',
                    'desc' => '查看管理员列表',
                    'items' => [
                        [
                            'label' => '新增用户',
                            'src' => 'admin/manager/add-user',
                            'icon' => 'fa fa-plus',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '新增管理员',
                        ],
                        [
                            'label' => '基本编辑',
                            'src' => 'admin/manager/edit',
                            'icon' => 'fa fa-pencil-square-o',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '编辑管理员',
                        ],
                        [
                            'label' => '封/解号',
                            'src' => 'admin/manager/toggle',
                            'icon' => 'fa fa-unlock-alt',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '解、封管理员账号',
                        ],
                        [
                            'label' => '更换管理组',
                            'src' => 'admin/manager/group',
                            'icon' => 'fa fa-users',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '为管理员更换管理组',
                        ],
                    ],
                ],
                [
                    'label' => '菜单',
                    'src' => 'admin/menu/index',
                    'icon' => 'fa fa-bars',
                    'desc' => '查看菜单列表',
                ],
                [
                    'label' => '管理组',
                    'src' => 'admin/group/index',
                    'icon' => 'fa fa-list-alt',
                    'desc' => '查看管理组列表',
                ],
                [
                    'label' => '运维脚本',
                    'src' => 'admin/ops-script/index',
                    'icon' => 'glyphicon glyphicon-flash',
                    'desc' => '查看运维脚本列表',
                ],
                [
                    'label' => '队列监控',
                    'src' => 'admin/ops-queue/index',
                    'icon' => 'fa fa-line-chart',
                    'desc' => '查看队列监控列表',
                ],
                [
                    'label' => '计划任务监控',
                    'src' => 'admin/ops-cron/index',
                    'icon' => 'fa fa-tasks',
                    'desc' => '查看计划任务监控列表',
                ],
                [
                    'label' => '系统错误日志',
                    'src' => 'admin/error-log/index',
                    'icon' => 'fa fa-exclamation-triangle',
                    'desc' => '查看错误日志列表',
                ],
                [
                    'label' => '应用日志',
                    'src' => 'admin/app-log/index',
                    'icon' => 'glyphicon glyphicon-phone',
                    'desc' => '查看应用日志列表',
                ],
                [
                    'label' => '管理员登录日志',
                    'src' => 'admin/admin-login-log/index',
                    'icon' => 'glyphicon glyphicon-cloud',
                    'desc' => '查看管理员登录日志列表',
                ],
                [
                    'label' => '管理员操作日志',
                    'src' => 'admin/admin-behavior-log/index',
                    'icon' => 'glyphicon glyphicon-calendar',
                    'desc' => '查看管理员操作日志列表',
                ],
                [
                    'label' => '手机区号管理',
                    'src' => 'admin/area-code/index',
                    'icon' => 'glyphicon glyphicon-phone-alt',
                    'desc' => '查看手机区号管理列表',
                    'items' => [
                        [
                            'label' => '新增',
                            'src' => 'admin/area-code/add',
                            'icon' => 'fa fa-plus',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '新增区号',
                        ],
                        [
                            'label' => '基本编辑',
                            'src' => 'admin/area-code/edit',
                            'icon' => 'fa fa-pencil-square-o',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '编辑区号',
                        ],
                        [
                            'label' => '禁用/启用',
                            'src' => 'admin/area-code/toggle',
                            'icon' => 'fa fa-pencil-square-o',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '禁/启用区号',
                        ],
                    ],
                ],
                [
                    'label' => '系统设置',
                    'src' => 'admin/system-setting/index',
                    'icon' => 'glyphicon glyphicon-cog',
                    'desc' => '查看系统设置列表',
                ],
            ],
        ],
    ],
    // 配置不需要rbac授权访问的菜单项，该配置用于页面展示控制。
    // 注：
    // 1. 控制器中`action-id`需要加入属性[$undetectedActions]中的路由。
    // 2. 如果使用`ViewBuilder`进行视图组件构建，必须加入该项配置；
    //    如果自定义页面，可以不加入该项配置；建议两种情况都加入该配置（可以用于行为记录）。
    //
    // 参数
    // - label 名称
    // - src 路由，格式：module/controller/action
    // - icon 图标
    // - dump_way   跳转方式：_self：内部，_blank：外部 [默认_self]
    // - desc 备注   注：用于行为日志记录，建议填写
    'whiteLists' => [
        //['label' => '', 'src' => '', 'icon' => '', 'dump_way' => '', 'desc' => ''],
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