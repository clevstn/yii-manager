<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/13
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\admin\controllers;

use Yii;
use yii\web\Response;
use app\builder\common\CommonController;

/**
 *
 * 门面
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class SiteController extends CommonController
{
    /**
     * @var array
     */
    public $actionVerbs = [
        'login' => ['get', 'post'],
    ];

    /**
     * @var array
     */
    public $guestActions = ['login'];

    /**
     * @var array
     */
    public $undetectedActions = ['login'];

    /**
     * 登录
     *
     * @return string|\yii\web\Response
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function actionLogin()
    {
        return $this->render('login');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}