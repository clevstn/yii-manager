<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\helper;

use Yii;
use app\builder\widgets\Menu;

/**
 * 菜单渲染助手
 * @author cleverstone
 * @since ym1.0
 */
class MenuHelper
{

    /**
     * 渲染菜单
     * @return string
     * @throws \Throwable
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
     * ```php
     * return [
     *      [
     *          'label' => '首页',
     *          'url' => ['/admin/index/index'],
     *          'icon' => 'glyphicon glyphicon-home',
     *      ],
     *      [
     *          'label' => '后台管理',
     *          'url' => '/admin/manager/index',
     *          'icon' => 'glyphicon glyphicon-blackboard',
     *          'items' => [
     *              [
     *                   'label' => '管理员',
     *                   'url' => ['/admin/manager/index'],
     *                   'icon' => 'glyphicon glyphicon-user',
     *              ]
     *          ],
     *      ],
     * ];
     * ```
     */
    public function getItems()
    {
        if ($identify = get_admin_user_identify()) {
            $items = Yii::$app->rbacManager->getBannerByGroup($identify->group);
            $items = array_merge([
                // 首页
                [
                    'label' => '首页',
                    // 用于yii\widgets\Menu->isItemActive()
                    'url' => ['/admin/index/index'],
                    // 用于视图外链
                    'src' => 'admin/index/index',
                    'icon' => 'glyphicon glyphicon-home',
                    'dump_way' => '_self',
                ]
            ], $items, [
                // 退出
                [
                    'label' => '退出',
                    // 用于yii\widgets\Menu->isItemActive()
                    'url' => ['/admin/site/logout'],
                    // 用于视图外链
                    'src' => 'admin/site/logout',
                    'icon' => 'glyphicon glyphicon-off',
                    'dump_way' => '_self',
                ]
            ]);

            return $items;
        }

        return [];
    }
}