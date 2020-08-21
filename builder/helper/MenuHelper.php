<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/19
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\helper;

use app\builder\widgets\Menu;

/**
 * 菜单渲染助手
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class MenuHelper
{

    /**
     * 渲染菜单
     * @return string
     * @throws \Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
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
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
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
                'label' => '运维管理',
                'url' => '',
                'icon' => 'glyphicon glyphicon-blackboard',
                'items' => [
                    [
                        'label' => '运维脚本',
                        'url' => ['/admin/site/test3', 'tag' => 'popular'],
                        'icon' => 'glyphicon glyphicon-globe',
                    ],
                    [
                        'label' => '计划任务监控',
                        'url' => ['/admin/site/test2', 'tag' => 'popular'],
                        'icon' => 'glyphicon glyphicon-envelope',
                    ],
                    [
                        'label' => '队列监控',
                        'url' => ['/admin/site/test1'],
                        'icon' => 'glyphicon glyphicon-list-alt',
                    ],
                ],
            ],
        ];
    }
}