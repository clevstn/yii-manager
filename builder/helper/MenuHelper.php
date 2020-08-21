<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/19
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\helper;

use Yii;
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
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function getItems()
    {
        return [
            // Important: you need to specify url as 'controller/action',
            // not just as 'controller' even if default action is used.
            [
                'label' => '首页',
                'url' => ['/admin/index/index'],
                'icon' => 'glyphicon glyphicon-th-large',
            ],
            // 'Products' menu item will be selected as long as the route is 'product/index'
            [
                'label' => '会员管理',
                'url' => '',
                'icon' => 'glyphicon glyphicon-shopping-cart',
                'items' => [
                    [
                        'label' => '会员列表',
                        'url' => ['/admin/site/test1', 'tag' => 'new'],
                        'icon' => 'glyphicon glyphicon-list-alt',
                    ],
                    [
                        'label' => '账户管理',
                        'url' => ['/admin/site/test2', 'tag' => 'popular'],
                        'icon' => 'glyphicon glyphicon-envelope',
                    ],
                    [
                        'label' => '银行卡',
                        'url' => ['/admin/site/test3', 'tag' => 'popular'],
                        'icon' => 'glyphicon glyphicon-globe',
                    ],
                ],
            ],
            [
                'label' => '系统设置',
                'url' => ['/admin/site/login'],
                'icon' => 'glyphicon glyphicon-hdd',
                'visible' => Yii::$app->adminUser->isGuest,
            ],
        ];
    }
}