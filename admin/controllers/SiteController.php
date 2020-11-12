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
use yii\base\UserException;
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
        'logout' => ['get'],
        'safe-validate' => ['get', 'post'],
        'send' => ['post'],
    ];

    /**
     * {@inheritdoc}
     */
    public $guestActions = [
        'login',
        'check-user',
        'safe-validate',
        'send',
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
            $userData = AdminUser::query(['id', 'photo'])->where(['username' => $usernameOrEmail])->orWhere(['email' => $usernameOrEmail])->one();
            if (!empty($userData)) {
                $photoUrl = attach_url($userData['photo']);
                return $this->asSuccess('success', [
                    'photo_url' => $photoUrl,
                ]);
            }
        }

        return $this->asFail(t('the user does not exist', 'app.admin'));
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
                $userData = AdminUser::find()->where(['username' => $usernameOrEmail])->orWhere(['email' => $usernameOrEmail])->one();
                // 校验用户是否存在
                if (empty($userData)) {
                    return $this->asFail('系统不存在该用户');
                }

                // 校验用户是否已被封停
                if ($userData['status'] == AdminUser::STATUS_DENY) {
                    return $this->asFail(!$userData['deny_end_time'] ? '该账号已被永久封停' : '该账号已被封停,封停截止时间:' . $userData['deny_end_time']);
                }

                // 校验用户密码是否正确
                if (!$userData->validatePassword($password)) {
                    return $this->asFail('登录密码输入错误');
                }

                $safeWays = $adminUser->getSafeWays($userData['id']);
                if ($safeWays == AdminUser::SAFE_AUTH_CLOSE) {
                    /* @var \yii\web\IdentityInterface $userData */
                    $isUser = Yii::$app->adminUser->login($userData, 3 * 86400);
                    if ($isUser) {
                        return $this->asSuccess('登录成功', $this->homeUrl);
                    }

                    return $this->asFail('登录失败');
                } else {
                    Yii::$app->getSession()->set($this->tempCheckIdentify, [
                        'id' => $userData['id'],
                        'safeWay' => $safeWays,
                    ]);
                    return $this->asSuccess('认证成功,即将开启安全认证...', '/admin/site/safe-validate');
                }
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
        $tempSessionUser = Yii::$app->getSession()->get($this->tempCheckIdentify);
        if (empty($tempSessionUser)) {
            // 不存在临时会话,则返回登录页
            return $this->redirect('/admin/site/login');
        }

        if ($this->isGet) {
            $this->view->params['ways'] = $tempSessionUser['safeWay'];
            return $this->render('login-safe');
        } else {
            $bodyParams = $this->post;
            // 认证码
            $safeCode = $bodyParams['safe_code'];
            $model = new AdminUser();
            $result = $model->verifySafeAuth($tempSessionUser['id'], $tempSessionUser['safeWay'], $safeCode);
            if (true === $result) {
                $isUser = Yii::$app->adminUser->login(AdminUser::findOne($tempSessionUser['id']), 3 * 86400);
                if ($isUser) {
                    return $this->asSuccess('安全认证成功');
                } else {
                    return $this->asFail('尝试登录失败');
                }

            } else {
                return $this->asFail($result);
            }

        }
    }

    /**
     * 登录 - 发送邮箱验证码/短信验证码
     * - scenario string [email] 邮件 [message] 短信
     *
     * @return string
     */
    public function actionSend()
    {
        $bodyParams = $this->post;
        return $this->asSuccess('已发送');
    }

    /**
     * 退出
     * @return Response
     * @throws UserException
     */
    public function actionLogout()
    {
        $isGuest = Yii::$app->adminUser->logout();
        if ($isGuest) {
            return $this->goHome();
        }

        throw new UserException('Logout failure. ');
    }
}