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