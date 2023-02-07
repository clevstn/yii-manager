<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\eventhandlers;

use Yii;
use app\models\AdminUser;
use yii\base\ActionEvent;
use app\admin\controllers\SiteController;

/**
 * 路由控制
 * @author cleverstone
 * @since ym1.0
 */
class RouteControlHandler
{
    /**
     * @param ActionEvent $event
     */
    public static function handleClick($event)
    {
        static::banCheck($event);
        static::sso($event);
    }

    /**
     * 单点登录
     * @param ActionEvent $event
     */
    protected static function sso($event)
    {
        $sso = Yii::$app->config->get('ADMIN_GROUP.ADMIN_SSO', 0);
        /* @var AdminUser $identify */
        $identify = Yii::$app->adminUser->identity;
        if ($sso == 1 && $identify) {
            $dbAccessToken = $identify->access_token;
            $sessionAccessToken = Yii::$app->session->get(SiteController::$accessTokenIdentify);

            if (strcmp($dbAccessToken, $sessionAccessToken) !== 0) {
                // 退出登录
                Yii::$app->adminUser->logout(true);
            }
        }
    }

    /**
     * 封号检查
     * @param ActionEvent $event
     */
    protected static function banCheck($event)
    {

    }
}