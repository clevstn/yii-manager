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

    const MAX_ATTEMPT_SIZE = 10;   // 最大尝试登陆次数
    const FREEZE_TIME = 1200;      // 超出最大尝试次数之后, 封停时间(秒)

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

    protected $tempSessionIdentify = '__admin_user_temp_session';   // 用户临时会话记录标识
    protected $attemptLoginIdentify = '__admin_user_attempt_login_size'; // 尝试登陆次数记录标识
    protected $loginBaseFlashIdentify = '__admin_user_login_base_tmp_session'; // 用户基本认证信息闪存标识
    protected $loginSafeFlashIdentify = '__admin_user_login_safe_tmp_session'; // 用户安全认证信息闪存标识
    public static $accessTokenIdentify = '__admin_user_access_token'; // 用户访问令牌记录标识
    public static $flashErrorIdentify = '__admin_user_flash_error'; // 用户重定向登录返回的错误信息

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
            $userData = AdminUser::activeQuery(['id', 'photo'])
                ->where(['username' => $usernameOrEmail])
                ->orWhere(['email' => $usernameOrEmail])
                ->one();

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
        $isRecord = Yii::$app->config->get('ADMIN_GROUP.ADMIN_LOGIN_LOG', 1);
        // 记录登录日志
        if (
            (
                $action->id == 'login'
                || $action->id == 'safe-validate'
            )
            && $this->isPost
            && $isRecord == 1
        ) {
            $session = Yii::$app->session;

            // 返回结果
            if ($result instanceof Response) {
                $data = $result->data;
            } else {
                $data = $result;
            }

            // 闪存的登录数据
            $flashData = [];
            switch ($action->id) {
                case 'login': // 基本认证
                    $flashData = $session->getFlash($this->loginBaseFlashIdentify, null);
                    break;
                case 'safe-validate': // 二次认证
                    $flashData = $session->getFlash($this->loginSafeFlashIdentify, null);
                    break;
            }

            // 管理员ID
            $adminId = isset($flashData['id']) ? $flashData['id'] : 0;
            // 认证方式
            $identifyType = isset($flashData['safeWay']) ? AdminUser::getLoginLogIdentifyType($flashData['safeWay']) : LoginLog::IDENTIFY_TYPE_BASE;
            // 客户端信息
            $clientInfo = $this->request->userAgent ?: '';
            // 尝试信息
            $attemptInfo = export_str($this->post);
            // 尝试结果
            $attemptStatus = LoginLog::ATTEMPT_FAILED;
            if (isset($data['code']) && $data['code'] == $this->responseSuccessCode) {
                $attemptStatus = LoginLog::ATTEMPT_SUCCESS;
            }

            // 错误类型
            $errorType = !empty($data['msg']) ? $data['msg'] : '';
            $loginIp = $this->request->userIP ?: '';

            $prepare = [
                'admin_user_id' => $adminId,        // 管理员ID
                'identify_type' => $identifyType,   // 认证类型
                'client_info' => $clientInfo,       // 客户端信息
                'attempt_info' => $attemptInfo,     // 尝试信息
                'attempt_status' => $attemptStatus, // 尝试结果
                'error_type' => $errorType,         // 错误类型
                'login_ip' => $loginIp,             // 登录IP
            ];

            // 记录登录日志
            $loginLog = new LoginLog();
            $loginLog->setAttributes($prepare);

            if (!$loginLog->save()) {
                system_log_error($loginLog->error, __METHOD__);
            }
        }

        return parent::afterAction($action, $result);
    }

    /**
     * 登录 - 基本校验
     * @return string
     * @throws \Exception
     */
    public function actionLogin()
    {
        if ($this->isGet) {
            if (Yii::$app->session->has($this->tempSessionIdentify)) {
                Yii::$app->session->remove($this->tempSessionIdentify);
            }

            $flashError = Yii::$app->session->getFlash(self::$flashErrorIdentify, null);
            return $this->render('login-base', [
                'errorMsg' => $flashError,
            ]);
        } else {
            $adminUser = new AdminUser();
            $adminUser->setScenario('login-base');

            if ($adminUser->load($this->post) && $adminUser->validate()) {
                $usernameOrEmail = $this->post['usernameOrEmail'];
                $password = $this->post['password'];

                /* @var AdminUser $userData */
                $userData = AdminUser::find()->where(['username' => $usernameOrEmail])->orWhere(['email' => $usernameOrEmail])->one();
                // 校验用户是否存在
                if (empty($userData)) {
                    return $this->asFail(t('the user does not exist', 'app.admin'));
                }

                // 闪存用户信息
                Yii::$app->session->setFlash($this->loginBaseFlashIdentify, [
                    'id' => $userData['id'],
                    'safeWay' => AdminUser::SAFE_AUTH_CLOSE,
                ]);

                // 尝试登录唯一标记
                $uniqueAttemptLoginIdentity = $this->attemptLoginIdentify . '_' . $userData['username'];

                // 检查最大登陆次数
                $attemptSize = $size = Yii::$app->cache->get($uniqueAttemptLoginIdentity);
                if ($attemptSize && $attemptSize >= self::MAX_ATTEMPT_SIZE) {
                    // 封停当前登陆管理员
                    $freezeUntilDate = date('Y-m-d H:i:s', time() + self::FREEZE_TIME);
                    AdminUser::banUser($userData['id'], $freezeUntilDate);
                    // 初始化尝试次数
                    Yii::$app->cache->delete($uniqueAttemptLoginIdentity);

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
                    // 设置尝试登陆次数缓存时间为超限冻结时间(秒)
                    if (empty($size)) {
                        Yii::$app->cache->set($uniqueAttemptLoginIdentity, 1, self::FREEZE_TIME);
                    } else {
                        Yii::$app->cache->set($uniqueAttemptLoginIdentity, ++$size, self::FREEZE_TIME);
                    }

                    return $this->asFail(t('the login password was entered incorrectly', 'app.admin'));
                }

                // 基本校验成功,初始化尝试登陆次数
                Yii::$app->cache->delete($uniqueAttemptLoginIdentity);

                // 获取2FA
                $safeWays = Yii::$app->config->get('ADMIN_GROUP.ADMIN_CCEE', AdminUser::SAFE_AUTH_CLOSE);

                // 仅仅基本认证
                if ($safeWays == AdminUser::SAFE_AUTH_CLOSE) {
                    $userData->setScenario('access-token');
                    $userData->access_token = $userData['id'] . random_string();

                    if ($userData->save()) {
                        /* @var \yii\web\IdentityInterface $userData */
                        // 自动登录过期设置三天
                        $isUser = Yii::$app->adminUser->login($userData, 3 * 86400);

                        if ($isUser) {
                            // 访问令牌加入会话
                            Yii::$app->session->set(self::$accessTokenIdentify, $userData->access_token);

                            return $this->asSuccess(t('login successful', 'app.admin'), $this->homeUrl);
                        }

                        return $this->asFail(t('login failed', 'app.admin'));
                    } else {
                        return $this->asFail($userData->error);
                    }

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
     * 登录 - 2FA
     * @return string|Response
     * @throws \yii\base\Exception
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
            $one = AdminUser::findOne($tempSessionUser['id']);
            if (empty($one)) {
                return $this->asFail(t('the user does not exist', 'app.admin'));
            }

            $result = $one->verifySafeAuth($one, $tempSessionUser['safeWay'], $safeCode);

            if (true === $result) {
                $one->setScenario('access-token');
                $one->access_token = $tempSessionUser['id'] . random_string();

                if ($one->save()) {
                    $isUser = Yii::$app->adminUser->login($one, 3 * 86400);

                    if ($isUser) {
                        // 访问令牌加入会话
                        Yii::$app->session->set(self::$accessTokenIdentify, $one->access_token);

                        // 删除临时会话数据
                        Yii::$app->session->remove($this->tempSessionIdentify);
                        return $this->asSuccess(t('authentication success', 'app.admin'));
                    } else {
                        return $this->asFail(t('login failed', 'app.admin'));
                    }
                } else {
                    return $this->asFail($one->error);
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

        switch ($bodyParams['scenario']) {
            case self::MESSAGE_SCENARIO_EMAIL:   // 邮件
                $key = $userData['email'];
                // 发送邮件
                $sendResult = Yii::$app->mailManager->sendHtml($key, [
                    'template' => 'default/html',
                    'use' => '登录校验',
                    'code' => random_number(),
                ]);
                break;
            case self::MESSAGE_SCENARIO_SMS: // 短信
                $key = $userData['mobile'];
                // 发送消息
                $sendResult = Yii::$app->sms->send($key, [
                    'template' => 'default',
                    'use' => '登录校验',
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