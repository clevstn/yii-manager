<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\controllers;

use app\models\AdminUser;
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
        'check-user' => ['post'],
        'login' => ['get', 'post'],
        'logout' => ['get', 'post'],
    ];

    /**
     * {@inheritdoc}
     */
    public $guestActions = [
        'login',
        'check-user',
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'logout'
    ];

    /**
     * {@inheritdoc}
     */
    public $layout = 'partial';

    /**
     * 检查用户名是否存在
     * @return string
     */
    public function actionCheckUser()
    {
        $user = !empty($this->post['username']) ? $this->post['username'] : null;
        if ($user) {
            $userData = AdminUser::query('id')->where(['username' => $user])->orWhere(['email' => $user])->one();
            if (!empty($userData)) {
                return $this->asSuccess('success');
            }
        }

        return $this->asFail('用户不存在');
    }

    /**
     * 登录
     * @return string
     */
    public function actionLogin()
    {
        return $this->render('login');
    }

    /**
     * 退出
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->adminUser->logout();
        return $this->goHome();
    }
}