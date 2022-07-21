<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\helper;

use app\builder\widgets\Menu;

/**
 * 菜单助手
 * @author cleverstone
 * @since 1.0
 */
class MenuHelper
{

    /**
     * 渲染菜单
     * @return string
     * @throws \Exception
     */
    public static function render()
    {
        $context = new static();
        return Menu::widget([
            'items' => $context->getItems(),
        ]);
    }

    /**
     * 获取菜单项
     * Important: you need to specify url as 'controller/action',
     * not just as 'controller' even if default action is used.
     * @return array
     */
    public function getItems()
    {
        return [
            [
                'label' => '首页',
                'url' => ['/admin/index/index'],
                'icon' => 'glyphicon glyphicon-home',
            ],
            // 'Products' menu item will be selected as long as the route is 'product/index'
            [
                'label' => '后台管理',
                'url' => '',
                'icon' => 'glyphicon glyphicon-blackboard',
                'items' => [
                    [
                        'label' => '管理员',
                        'url' => ['/admin/manager/index'],
                        'icon' => 'glyphicon glyphicon-user',
                    ],
                    [
                        'label' => '菜单',
                        'url' => ['/admin/menu/index'],
                        'icon' => 'fa fa-bars',
                    ],
                    [
                        'label' => '管理组',
                        'url' => ['/admin/group/index'],
                        'icon' => 'fa fa-list-alt',
                    ],
                    [
                        'label' => '运维脚本',
                        'url' => ['/admin/ops-script/index'],
                        'icon' => 'glyphicon glyphicon-flash',
                    ],
                    [
                        'label' => '队列监控',
                        'url' => ['/admin/ops-queue/index'],
                        'icon' => 'fa fa-line-chart',
                    ],
                    [
                        'label' => '计划任务监控',
                        'url' => ['/admin/ops-cron/index'],
                        'icon' => 'fa fa-tasks',
                    ],
                    [
                        'label' => '错误日志',
                        'url' => ['/admin/error-log/index'],
                        'icon' => 'fa fa-exclamation-triangle',
                    ],
                    [
                        'label' => '应用日志',
                        'url' => ['/admin/app-log/index'],
                        'icon' => 'glyphicon glyphicon-phone',
                    ],
                    [
                        'label' => '管理员登录日志',
                        'url' => ['/admin/admin-login-log/index'],
                        'icon' => 'glyphicon glyphicon-cloud',
                    ],
                    [
                        'label' => '管理员操作日志',
                        'url' => ['/admin/admin-behavior-log/index'],
                        'icon' => 'glyphicon glyphicon-calendar',
                    ],
                    [
                        'label' => '手机区号管理',
                        'url' => ['/admin/area-code/index'],
                        'icon' => 'glyphicon glyphicon-phone-alt',
                    ],
                    [
                        'label' => '系统设置',
                        'url' => ['/admin/system-setting/index'],
                        'icon' => 'glyphicon glyphicon-cog',
                    ],
                ],
            ],
        ];
    }
}