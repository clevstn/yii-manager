<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin\controllers;

use Yii;
use yii\web\Response;
use app\models\AdminUser;
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
        'logout' => ['post'],
        'safe-validate' => ['get', 'post'],
    ];

    /**
     * {@inheritdoc}
     */
    public $guestActions = [
        'login',
        'check-user',
        'safe-validate',
    ];

    /**
     * {@inheritdoc}
     */
    public $undetectedActions = [
        'logout'
    ];

    /**
     * @var string 用户临时存储[ID]
     */
    protected $tempCheckIdentify = 'user-temp-login-id';

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
        $usernameOrEmail = !empty($this->post['usernameOrEmail']) ? $this->post['usernameOrEmail'] : null;
        if ($usernameOrEmail) {
            $userData = AdminUser::query('id')->where(['username' => $usernameOrEmail])->orWhere(['email' => $usernameOrEmail])->one();
            if (!empty($userData)) {
                return $this->asSuccess('success');
            }
        }

        return $this->asFail('用户不存在');
    }

    /**
     * 登录 - 基本校验
     * @return string
     */
    public function actionLogin()
    {
        if ($this->isGet) {
            return $this->render('login-base');
        } else {
            $adminUser = new AdminUser();
            $adminUser->setScenario('login-base');
            if ($adminUser->load($this->post) && $adminUser->validate()) {
                $usernameOrEmail = $this->post['usernameOrEmail'];
                $password = $this->post['password'];
                $userData = AdminUser::query(['id', 'password', 'status', 'deny_end_time'])->where(['username' => $usernameOrEmail])->orWhere(['email' => $usernameOrEmail])->one();
                // 校验用户是否存在
                if (empty($userData)) {
                    return $this->asFail('系统不存在该用户');
                }

                // 校验用户是否已被封停
                if ($userData['status'] == AdminUser::STATUS_DENY) {
                    return $this->asFail($userData['deny_end_time'] ? '该账号已被永久封停' : '该账号已被封停,封停截止时间:' . $userData['deny_end_time']);
                }

                // 校验用户密码是否正确
                if (!$userData->validatePassword($password)) {
                    return $this->asFail('登录密码输入错误');
                }

                Yii::$app->getSession()->set($this->tempCheckIdentify, $userData['id']);
                return $this->asSuccess('登录校验成功');
            }

            // 登录提交
            return $this->asFail($adminUser->error);
        }
    }

    /**
     * 登录 - 安全认证
     * @return string|Response
     */
    public function actionSafeValidate()
    {
        // 是否存在临时会话
        $tempUserId = Yii::$app->getSession()->get($this->tempCheckIdentify);
        if (empty($tempUserId)) {
            // 不存在临时会话,则返回登录页
            return $this->redirect('/admin/site/login');
        }

        if ($this->isGet) {
            return $this->render('login-safe');
        } else {
            return $this->asSuccess('安全认证成功');
        }
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