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
        static::sso($event);
        static::banCheck($event);
    }

    /**
     * 单点登录
     * @param $event
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
                Yii::$app->adminUser->logout(false);
                Yii::$app->session->setFlash(SiteController::$flashErrorIdentify, '您的账号已在另一台设备登录，当前设备已下线！');
                Yii::$app->response->redirect('/admin/site/login');

                $event->isValid = false;
                $event->handled = true;
            }
        }
    }

    /**
     * 封号检查
     * @param ActionEvent $event
     */
    protected static function banCheck($event)
    {
        /* @var AdminUser $identify */
        $identify = Yii::$app->adminUser->identity;

        // 校验用户是否已被封停
        if ($identify && $identify->status == AdminUser::STATUS_DENY) {
            $errorMsg =  !$identify->deny_end_time ?
                t('the account has been permanently suspended', 'app.admin') :
                t('the account has been suspended until {date}', 'app.admin', ['date' => $identify->deny_end_time]);

            // 退出登录
            Yii::$app->adminUser->logout(false);
            Yii::$app->session->setFlash(SiteController::$flashErrorIdentify, $errorMsg);
            Yii::$app->response->redirect('/admin/site/login');

            $event->isValid = false;
            $event->handled = true;
        }
    }
}