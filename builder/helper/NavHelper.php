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
use yii\helpers\Html;
use yii\bootstrap\Nav;

/**
 * 导航助手
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class NavHelper
{
    /**
     * 导航左侧项
     * @return string
     * @throws \Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function renderLeftItems()
    {
        $context = new static();
        return Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'encodeLabels' => false,
            'items' => $context->getItems()['left'],
        ]);
    }

    /**
     * 导航右侧项
     * @return string
     * @throws \Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function renderRightItems()
    {
        $context = new static();
        return Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'encodeLabels' => false,
            'items' => $context->getItems()['right'],
        ]);
    }

    /**
     * 我的Brand
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