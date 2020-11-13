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
use app\models\AdminUserLoginLog as LoginLog;
use function Webmozart\Assert\Tests\StaticAnalysis\email;

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
     * 后置操作
     * @param \yii\base\Action $action
     * @param mixed $result
     * @return mixed
     */
    public function afterAction($action, $result)
    {
        if ($action->id == 'login' && $this->isPost) {
            if ($result instanceof Response) {
                $data = $result->data;
            } else {
                $data = $result;
            }

            $userData = Yii::$app->session->getFlash('__user', null);
            LoginLog::in([
                'admin_user_id' => empty($userData) ? 0 : $userData['id'],
                'identify_type' => LoginLog::IDENTIFY_TYPE_BASE,
                'client_info' => $this->request->userAgent ?: '',
                'attempt_info' => export_str($this->post),
                'attempt_status' => !empty($data['code']) && $data['code'] == 200 ? LoginLog::ATTEMPT_SUCCESS : LoginLog::ATTEMPT_FAILED,
                'error_type' => !empty($data['msg']) ? $data['msg'] : '',
                'login_ip' => $this->request->userIP ?: '',
            ]);
        }

        return parent::afterAction($action, $result);
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
                    return $this->asFail(t('the user does not exist', 'app.admin'));
                }

                Yii::$app->session->setFlash('__user', $userData);

                // 校验用户是否已被封停
                if ($userData['status'] == AdminUser::STATUS_DENY) {
                    return $this->asFail(
                        !$userData['deny_end_time'] ?
                            t('The account has been permanently suspended', 'app.admin') :
                            t('The account has been suspended until {date}', 'app.admin', ['date' => $userData['deny_end_time']])
                    );
                }

                // 校验用户密码是否正确
                if (!$userData->validatePassword($password)) {
                    return $this->asFail(t('The login password was entered incorrectly', 'app.admin'));
                }

                $safeWays = $adminUser->getSafeWays($userData['id']);
                if ($safeWays == AdminUser::SAFE_AUTH_CLOSE) {
                    /* @var \yii\web\IdentityInterface $userData */
                    $isUser = Yii::$app->adminUser->login($userData, 3 * 86400);
                    if ($isUser) {
                        return $this->asSuccess(t('Login successful', 'app.admin'), $this->homeUrl);
                    }

                    return $this->asFail(t('Login failed', 'app.admin'));
                } else {
                    Yii::$app->getSession()->set($this->tempCheckIdentify, [
                        'id' => $userData['id'],
                        'safeWay' => $safeWays,
                    ]);
                    return $this->asSuccess(t('Authentication success', 'app.admin'), '/admin/site/safe-validate');
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
                    return $this->asSuccess(t('Authentication success', 'app.admin'));
                } else {
                    return $this->asFail(t('Login failed', 'app.admin'));
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
        return $this->asSuccess(t('Has been sent', 'app.admin'));
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