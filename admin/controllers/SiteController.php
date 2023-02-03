<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\admin\controllers;

use Yii;
use yii\web\Response;
use app\models\AdminUser;
use yii\base\UserException;
use app\builder\common\CommonController;
use app\models\AdminUserLoginLog as LoginLog;

/**
 * 站点相关
 * @author cleverstone
 * @since ym1.0
 */
class SiteController extends CommonController
{
    // 最大尝试登陆次数
    const MAX_ATTEMPT_SIZE = 10;
    // 超出最大尝试次数之后,封停时间(秒)
    const FREEZE_TIME = 1200;
    // 消息场景
    const MESSAGE_SCENARIO_SMS = 'message';
    const MESSAGE_SCENARIO_EMAIL = 'email';

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
     * @var string 用户临时会话记录标识
     */
    protected $tempSessionIdentify = '__user_temp_session';

    /**
     * @var string 尝试登陆次数记录标识
     */
    protected $attemptLoginIdentify = '__attempt_login_size';

    /**
     * @var string 用户基本认证信息闪存标识
     */
    protected $loginBaseFlashIdentify = '__user_login_base_tmp_session';

    /**
     * @var string 用户安全认证信息闪存标识
     */
    protected $loginSafeFlashIdentify = '__user_login_safe_tmp_session';

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
            $userData = AdminUser::activeQuery(['id', 'photo'])->where(['username' => $usernameOrEmail])->orWhere(['email' => $usernameOrEmail])->one();
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
        // 登录-基本认证,记录登录日志
        if ($action->id == 'login' && $this->isPost) {
            if ($result instanceof Response) {
                $data = $result->data;
            } else {
                $data = $result;
            }

            // 获取闪存数据
            $flashData = Yii::$app->session->getFlash($this->loginBaseFlashIdentify, null);
            // 记录登录日志
            LoginLog::in([
                'admin_user_id' => empty($flashData) ? 0 : $flashData['id'],
                'identify_type' => LoginLog::IDENTIFY_TYPE_BASE,
                'client_info' => $this->request->userAgent ?: '',
                'attempt_info' => export_str($this->post),
                'attempt_status' => !empty($data['code']) && $data['code'] == 200 ? LoginLog::ATTEMPT_SUCCESS : LoginLog::ATTEMPT_FAILED,
                'error_type' => !empty($data['msg']) ? $data['msg'] : '',
                'login_ip' => $this->request->userIP ?: '',
            ]);
        }

        // 登录-安全认证,记录登录日志
        if ($action->id == 'safe-validate' && $this->isPost) {
            // 获取闪存数据
            $flashData = Yii::$app->session->getFlash($this->loginSafeFlashIdentify, null);
            if (!empty($flashData)) {
                if ($result instanceof Response) {
                    $data = $result->data;
                } else {
                    $data = $result;
                }

                // 记录登录日志
                LoginLog::in([
                    'admin_user_id' => $flashData['id'],
                    'identify_type' => AdminUser::getLoginLogIdentifyType($flashData['safeWay']),
                    'client_info' => $this->request->userAgent ?: '',
                    'attempt_info' => export_str($this->post),
                    'attempt_status' => !empty($data['code']) && $data['code'] == 200 ? LoginLog::ATTEMPT_SUCCESS : LoginLog::ATTEMPT_FAILED,
                    'error_type' => !empty($data['msg']) ? $data['msg'] : '',
                    'login_ip' => $this->request->userIP ?: '',
                ]);
            }
        }

        return parent::afterAction($action, $result);
    }

    /**
     * 登录 - 基本校验
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionLogin()
    {
        if ($this->isGet) {
            if (Yii::$app->session->has($this->tempSessionIdentify)) {
                Yii::$app->session->remove($this->tempSessionIdentify);
            }

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

                // 闪存用户信息
                Yii::$app->session->setFlash($this->loginBaseFlashIdentify, $userData);

                // 检查最大登陆次数
                $attemptSize = $size = Yii::$app->cache->get($this->attemptLoginIdentify);
                if ($attemptSize && $attemptSize >= self::MAX_ATTEMPT_SIZE) {
                    // 封停当前登陆管理员
                    $freezeUntilDate = date('Y-m-d H:i:s', time() + self::FREEZE_TIME);
                    AdminUser::freezeUser($userData['id'], $freezeUntilDate);
                    // 初始化尝试次数
                    Yii::$app->cache->delete($this->attemptLoginIdentify);
                    // 返回冻结信息
                    return $this->asFail(
                        t('due to too many password errors, your account has been suspended until {date}.', 'app.admin', ['date' => $freezeUntilDate])
                    );
                }

                // 校验用户是否已被封停
                if (
                    $userData['status'] == AdminUser::STATUS_DENY
                    && AdminUser::isDisabled($userData['id'], $userData['deny_end_time'])
                ) {
                    return $this->asFail(
                        !$userData['deny_end_time'] ?
                            t('the account has been permanently suspended', 'app.admin') :
                            t('the account has been suspended until {date}', 'app.admin', ['date' => $userData['deny_end_time']])
                    );
                }

                // 校验用户密码是否正确
                if (!$userData->validatePassword($password)) {
                    $size = Yii::$app->cache->get($this->attemptLoginIdentify);

                    // 设置尝试登陆次数缓存时间为超限冻结时间(秒)
                    if (empty($size)) {
                        Yii::$app->cache->set($this->attemptLoginIdentify, 1, self::FREEZE_TIME);
                    } else {
                        Yii::$app->cache->set($this->attemptLoginIdentify, ++$size, self::FREEZE_TIME);
                    }

                    return $this->asFail(t('the login password was entered incorrectly', 'app.admin'));
                }

                // 基本校验成功,初始化尝试登陆次数
                Yii::$app->cache->delete($this->attemptLoginIdentify);

                // 获取登录管理员安全认证方式
                $safeWays = $adminUser->getSafeWays($userData['id']);

                // 仅仅基本认证
                if ($safeWays == AdminUser::SAFE_AUTH_CLOSE) {
                    /* @var \yii\web\IdentityInterface $userData */
                    $isUser = Yii::$app->adminUser->login($userData, 3 * 86400);
                    if ($isUser) {
                        return $this->asSuccess(t('login successful', 'app.admin'), $this->homeUrl);
                    }

                    return $this->asFail(t('login failed', 'app.admin'));
                } else {
                    // 使用安全认证
                    Yii::$app->getSession()->set($this->tempSessionIdentify, [
                        'id' => $userData['id'],
                        'safeWay' => $safeWays,
                    ]);
                    return $this->asSuccess(t('authentication success', 'app.admin'), '/admin/site/safe-validate');
                }
            }

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
        $tempSessionUser = Yii::$app->getSession()->get($this->tempSessionIdentify);
        if (empty($tempSessionUser)) {
            if ($this->isGet) {
                // 不存在临时会话,则返回登录页
                return $this->redirect('/admin/site/login');
            } else {
                return $this->asUnauthorized();
            }
        }

        if ($this->isGet) {
            $this->view->params['ways'] = $tempSessionUser['safeWay'];
            return $this->render('login-safe');
        } else {
            // 闪存用户信息
            Yii::$app->session->setFlash($this->loginSafeFlashIdentify, $tempSessionUser);

            // 获取表单
            $bodyParams = $this->post;
            $safeCode = !empty($bodyParams['safe_code']) ? $bodyParams['safe_code'] : null;

            // 验证认证码
            $model = new AdminUser();
            $result = $model->verifySafeAuth($tempSessionUser['id'], $tempSessionUser['safeWay'], $safeCode);

            if (true === $result) {
                $isUser = Yii::$app->adminUser->login(AdminUser::findOne($tempSessionUser['id']), 3 * 86400);
                if ($isUser) {
                    // 删除临时会话数据
                    Yii::$app->session->remove($this->tempSessionIdentify);
                    return $this->asSuccess(t('authentication success', 'app.admin'));
                } else {
                    return $this->asFail(t('login failed', 'app.admin'));
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
     * @return string|Response
     * @throws \Throwable
     */
    public function actionSend()
    {
        // 检查临时会话是否存在
        $tempSessionUser = Yii::$app->getSession()->get($this->tempSessionIdentify);
        if (empty($tempSessionUser)) {
            if ($this->isGet) {
                // 不存在临时会话,则返回登录页
                return $this->redirect('/admin/site/login');
            } else {
                return $this->asUnauthorized();
            }
        }

        // 检查参数是否正确
        $bodyParams = $this->post;
        if (empty($bodyParams['scenario'])) {
            return $this->asFail(t('parameter error', 'app.admin'));
        }

        // 获取用户信息
        $userData = AdminUser::activeQuery(['email', 'an', 'mobile'])->where(['id' => $tempSessionUser['id']])->one();
        if (empty($userData)) {
            return $this->asFail(t('the user does not exist', 'app.admin'));
        }

        // 获取[[key]]
        $sendResult = false;
        switch ($bodyParams['scenario']) {
            case self::MESSAGE_SCENARIO_EMAIL:   // 邮件
                $key = $userData['email'];
                // 发送邮件
                // ...
                break;
            case self::MESSAGE_SCENARIO_SMS: // 短信
                $key = $userData['an'] . ' ' . $userData['mobile'];
                // 发送消息
                $sendResult = Yii::$app->sms->send($key, '登录安全认证', [
                    'template' => 'default',
                    'use' => '登录安全认证',
                    'code' => random_number(),
                ]);
                break;
            default:
                return $this->asFail(t('parameter error', 'app.admin'));
        }

        if (true === $sendResult) {
            return $this->asSuccess(t('has been sent', 'app.admin'));
        }

        return $this->asFail($sendResult);
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