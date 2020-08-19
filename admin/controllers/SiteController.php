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
    public $guestActions = [
        'login',
        'test1',
        'test2',
        'test3',
    ];

    /**
     * @var array
     */
    public $undetectedActions = [
        'login',
        'test1',
        'test2',
        'test3',
    ];

    /**
     * 登录
     * @return string|\yii\web\Response
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function actionLogin()
    {
        return $this->render('login', ['param' => 'login']);
    }

    /**
     * test1
     * @return string|\yii\web\Response
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function actionTest1()
    {
        return $this->render('login', ['param' => 'test1']);
    }

    /**
     * test2
     * @return string|\yii\web\Response
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function actionTest2()
    {
        return $this->render('login', ['param' => 'test2']);
    }

    /**
     * test3
     * @return string|\yii\web\Response
     * @author cleverstone <yang_hui_lei@163.com>
     */
    public function actionTest3()
    {
        return $this->render('login', ['param' => 'test3']);
    }

    /**
     * Logout action.
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}