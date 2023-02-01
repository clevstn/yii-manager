<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

/**
 * 菜单
 * @since ym1.0
 */
return [
    // 配置需要RBAC授权访问的菜单项
    // 参数配置请结合`auth_menu`数据表
    // - label      菜单名称
    // - icon       图标
    // - label_type 类型，1、菜单 2、功能
    // - src        源
    // - link_type  链接类型：1、路由；2、外链
    // - dump_way   跳转方式：_self：内部，_blank：外部
    // - desc       备注
    // - sort       排序
    'auth' => [
        [
            'label' => '首页',
            'src' => 'admin/index/index',
            'icon' => 'glyphicon glyphicon-home',
            'label_type' => '',
            'link_type' => '',
            'dump_way' => '',
            'desc' => '',
            'sort' => '',
        ],
    ],
    // 配置不需要RBAC授权访问的菜单项，改配置用于页面展示控制。
    // 控制器中`action-id`需要加入属性[$undetectedActions]中
    // 如果使用`ViewBuilder`进行视图组件构建，则需要加入该项配置。
    // 如果自定义页面，则不需要加入该项配置
    // 参数
    // - name 名称
    // - src 路由，格式：module/controller/action
    'whiteLists' => [
        //['name' => '', 'src' => ''],
    ],
];