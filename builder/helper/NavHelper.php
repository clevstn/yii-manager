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
 * 导航渲染助手
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class NavHelper
{
    /**
     * Render nav items
     * @return string
     * @throws \Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function renderItems()
    {
        return Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => [
                Yii::$app->adminUser->isGuest ? (
                ['label' => '<i class="glyphicon glyphicon-log-out"></i><span>&nbsp;登录</span>', 'url' => ['/admin/site/login'], 'encode' => false]
                ) : (
                    '<li>'
                    . Html::beginForm(['/admin/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->adminUser->identity->username . ')',
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                    . '</li>'
                )
            ],
        ]);
    }
}