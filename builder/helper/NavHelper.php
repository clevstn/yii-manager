<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\helper;

use Yii;
use yii\bootstrap\Nav;
use yii\helpers\Html;

/**
 * 导航助手
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class NavHelper
{
    /**
     * 渲染导航项
     * @return string
     * @throws \Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function renderItems()
    {
        $context = new static();
        return Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $context->getItems(),
        ]);
    }

    /**
     * 个人中心下拉获取标语
     * @return string
     * @author cleverstone
     * @since 1.0
     */
    public static function getMyBrand()
    {
        $adminUser = Yii::$app->adminUser;
        $label = Html::tag('span', '当前登录：' . ($adminUser->isGuest ? '无' : $adminUser->username), [
            'style' => ['margin-top' => '12px']
        ]);
        $imgContent = Html::img('@web/media/image/head.png', [
            'style' => ['width' => '70px', 'height' => '70px'],
        ]);

        return Html::tag(
            'li',
            "{$imgContent}\n{$label}",
            [
                'class' => 'dropdown-header custom-header',
            ]
        );
    }

    /**
     * 获取导航项
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    protected function getItems()
    {
        $navPath = Yii::$app->basePath . '/config/nav.php';
        return include($navPath);
    }
}