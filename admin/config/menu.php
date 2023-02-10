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
    // - is_quick   是否允许设置为快捷操作，0：不可以 1：可以；默认：0 注意：快捷操作为get请求，不可动态传参请求，只能打开独立窗口，请根据功能实际情况进行设置。
    // - items      子项 [默认[]]
    'auth' => [
        /*后台管理*/
        [
            'label' => '后台管理',
            'src' => 'module/admin/manager',
            'icon' => 'glyphicon glyphicon-blackboard',
            'items' => [
                [
                    'label' => '管理员管理',
                    'src' => 'admin/manager/index',
                    'icon' => 'glyphicon glyphicon-user',
                    'desc' => '查看管理员列表',
                    'is_quick' => 1,
                    'items' => [
                        [
                            'label' => '新增管理员',
                            'src' => 'admin/manager/add-user',
                            'icon' => 'fa fa-plus',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '新增管理员',
                            'is_quick' => 1,
                        ],
                        [
                            'label' => '管理员编辑',
                            'src' => 'admin/manager/edit',
                            'icon' => 'fa fa-pencil-square-o',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '编辑管理员',
                        ],
                        [
                            'label' => '封/解管理员账号',
                            'src' => 'admin/manager/toggle',
                            'icon' => 'fa fa-unlock-alt',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '解/封管理员账号',
                        ],
                        [
                            'label' => '更换管理组',
                            'src' => 'admin/manager/group',
                            'icon' => 'fa fa-users',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '为管理员更换管理组',
                        ],
                        [
                            'label' => 'MFA绑定',
                            'src' => 'admin/manager/mfa-bind',
                            'icon' => 'fa fa-google-plus',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '为管理员绑定MFA',
                        ],
                    ],
                ],
                [
                    'label' => '菜单管理',
                    'src' => 'admin/menu/index',
                    'icon' => 'fa fa-bars',
                    'desc' => '查看菜单列表',
                    'is_quick' => 1,
                    'items' => [
                        [
                            'label' => '从本地更新菜单',
                            'src' => 'admin/menu/update',
                            'icon' => 'glyphicon glyphicon-open',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '从本地更新菜单列表',
                        ],
                        [
                            'label' => '编辑菜单',
                            'src' => 'admin/menu/edit',
                            'icon' => 'glyphicon glyphicon-edit',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '编辑菜单',
                        ],
                    ],
                ],
                [
                    'label' => '管理组管理',
                    'src' => 'admin/group/index',
                    'icon' => 'fa fa-list-alt',
                    'desc' => '查看管理组列表',
                    'is_quick' => 1,
                    'items' => [
                        [
                            'label' => '新增管理组',
                            'src' => 'admin/group/add',
                            'icon' => 'fa fa-plus',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '新增管理组',
                            'is_quick' => 1,
                        ],
                        [
                            'label' => '管理组编辑',
                            'src' => 'admin/group/edit',
                            'icon' => 'fa fa-pencil-square-o',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '编辑管理组',
                        ],
                        [
                            'label' => '禁/启用管理组',
                            'src' => 'admin/group/toggle',
                            'icon' => 'fa fa-unlock-alt',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '禁/启用管理组',
                        ],
                        [
                            'label' => '分配管理组权限',
                            'src' => 'admin/group/dispatch',
                            'icon' => 'glyphicon glyphicon-random',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '分配管理组权限',
                        ],
                    ],
                ],
                [
                    'label' => '运维脚本',
                    'src' => 'admin/ops-script/index',
                    'icon' => 'glyphicon glyphicon-flash',
                    'desc' => '查看运维脚本',
                    'is_quick' => 1,
                ],
                [
                    'label' => '队列监控',
                    'src' => 'admin/ops-queue/index',
                    'icon' => 'fa fa-line-chart',
                    'desc' => '查看队列监控',
                    'is_quick' => 1,
                ],
                [
                    'label' => '计划任务监控',
                    'src' => 'admin/ops-cron/index',
                    'icon' => 'fa fa-tasks',
                    'desc' => '查看计划任务监控',
                    'is_quick' => 1,
                ],
                [
                    'label' => '系统日志',
                    'src' => 'admin/system-log/index',
                    'icon' => 'fa fa-exclamation-triangle',
                    'desc' => '查看错误日志',
                    'is_quick' => 1,
                ],
                [
                    'label' => '应用日志',
                    'src' => 'admin/app-log/index',
                    'icon' => 'glyphicon glyphicon-phone',
                    'desc' => '查看应用日志',
                    'is_quick' => 1,
                ],
                [
                    'label' => '管理员登录日志',
                    'src' => 'admin/admin-login-log/index',
                    'icon' => 'glyphicon glyphicon-cloud',
                    'desc' => '查看管理员登录日志',
                    'is_quick' => 1,
                ],
                [
                    'label' => '管理员操作日志',
                    'src' => 'admin/admin-behavior-log/index',
                    'icon' => 'glyphicon glyphicon-calendar',
                    'desc' => '查看管理员操作日志',
                    'is_quick' => 1,
                ],
                [
                    'label' => '邮件记录列表',
                    'src' => 'admin/email-record/index',
                    'icon' => 'fa fa-envelope-open-o',
                    'desc' => '查看邮件记录列表',
                    'is_quick' => 1,
                ],
                [
                    'label' => '短信记录列表',
                    'src' => 'admin/sms-record/index',
                    'icon' => 'fa fa-commenting-o',
                    'desc' => '查看短信记录列表',
                    'is_quick' => 1,
                ],
                [
                    'label' => '手机区号管理',
                    'src' => 'admin/area-code/index',
                    'icon' => 'glyphicon glyphicon-phone-alt',
                    'desc' => '查看手机区号管理列表',
                    'is_quick' => 1,
                    'items' => [
                        [
                            'label' => '新增区号',
                            'src' => 'admin/area-code/add',
                            'icon' => 'fa fa-plus',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '新增区号',
                            'is_quick' => 1,
                        ],
                        [
                            'label' => '编辑区号',
                            'src' => 'admin/area-code/edit',
                            'icon' => 'fa fa-pencil-square-o',
                            'label_type' => AuthMenu::LABEL_TYPE_FUNCTION,
                            'desc' => '编辑区号',
                        ],
                        [
                            'label' => '禁/启用区号',
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
                    'desc' => '查看系统设置项',
                    'is_quick' => 1,
                ],
            ],
        ],
    ],
    // 配置不需要rbac授权访问的菜单项，该配置用于页面展示控制。
    // 注：
    // 1. 控制器中`action-id`需要加入属性[$undetectedActions]中的路由。
    // 2. 如果使用`ViewBuilder`进行视图组件构建，必须加入该项配置；
    //    如果自定义页面，可以不加入该项配置；建议两种情况都加入该配置
    // 作用：
    // 1. 可用于页面控件根据rbac控制进行隐藏显示
    // 2. 可用于行为记录功能
    // 3. 可用于首页快捷项功能
    //
    // 参数
    // - label 名称
    // - src 路由，格式：module/controller/action
    // - icon 图标
    // - dump_way   跳转方式：_self：内部，_blank：外部 [默认_self]
    // - desc 备注   注：用于行为日志记录，建议填写
    // - is_quick 是否允许设置为快捷操作，0：不可以 1：可以；注意：快捷操作为get请求，不可动态传参请求，只能打开独立窗口，请根据功能实际情况进行设置。
    'whiteLists' => [
        [
            'label' => '退出',
            'src' => 'admin/site/logout',
            'icon' => 'glyphicon glyphicon-off',
            'dump_way' => '_self',
            'desc' => '退出登录',
            'is_quick' => 0,
        ],
        [
            'label' => '个人中心',
            'src' => 'admin/home/index',
            'icon' => 'glyphicon glyphicon-user',
            'dump_way' => '_self',
            'desc' => '个人中心设置',
            'is_quick' => 0,
        ],
        [
            'label' => '首页',
            'src' => 'admin/index/index',
            'icon' => 'glyphicon glyphicon-home',
            'dump_way' => '_self',
            'desc' => '查看首页汇总统计',
            'is_quick' => 0,
        ],
        [
            'label' => '点击设置',
            'src' => 'admin/index/quick-setting',
            'icon' => 'glyphicon glyphicon-cog',
            'dump_way' => '_self',
            'desc' => '设置快捷操作项',
            'is_quick' => 0,
        ],
        [
            'label' => '快捷菜单',
            'src' => 'admin/index/quick-list',
            'icon' => '',
            'dump_way' => '_self',
            'desc' => '获取快捷菜单列表',
            'is_quick' => 0,
        ],
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