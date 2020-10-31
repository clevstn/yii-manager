<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\controllers;

use Yii;
use yii\web\Response;
use app\builder\common\CommonController;

/**
 * 站点相关
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class SiteController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'login' => ['get', 'post'],
    ];

    /**
     * {@inheritdoc}
     */
    public $guestActions = [
        'login',
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'login',
    ];

    /**
     * login
     * @return string
     */
    public function actionLogin()
    {
        return $this->render('login');
    }

    /**
     * logout
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}