<?php
/**
 * @copyright Copyright (c) 2020 cleverstone
 */

namespace app\components;

use app\models\EmailRecord;
use yii\base\Component;
use yii\di\Instance;

/**
 * 邮件发送器
 * @author cleverstone
 * @since ym1.0
 */
class MailManager extends Component
{
    /**
     * @var null|\yii\swiftmailer\Mailer
     */
    public $sender = 'mailer';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();

        $this->sender = Instance::ensure($this->sender, 'yii\mail\MailerInterface');
    }

    /**
     * 验证校验码
     * @param string $email 邮箱
     * @param int $code 校验码
     * @param int $expireTime 过期时间（s）
     * @return bool
     */
    public function verifyCode($email, $code, $expireTime = 300)
    {
        $one = EmailRecord::query(['send_time', 'id'])
            ->where(['receive_email' => $email, 'auth_code' => $code])
            ->one();

        if (empty($one)) {
            return false;
        }

        $sendTime = strtotime($one['send_time']);
        if ($sendTime + $expireTime >= time()) {
            return true;
        }

        return false;
    }

    /**
     * 发送HTML邮件
     * @param string $receiveEmail 接受邮件地址
     * @param array $params 参数
     * ----- 必填参数
     * - [[template]] string 本地自定义邮件模板名称，如：default/html
     * - [[use]] string 服务名称，如：登录认证
     *
     * ------ 非必填参数，用于邮件模板和邮件记录
     * - [[code]] number|null 验证码
     * - [[sendUser]] int 发送人ID，即管理员ID
     * - 其他参数
     * @return true|string
     */
    public function sendHtml($receiveEmail, array $params)
    {
        if ($this->sender->useFileTransport && YII_ENV_PROD) {
            return t('The mail function is unavailable, Please check the Mailer configuration information', 'app.admin');
        }

        if (empty($receiveEmail)) {
            return t('{param} can not be empty', 'app.admin', ['param' => 'email']);
        }

        /* @var \yii\mail\MessageInterface $messageManager */
        $messageManager = $this->sender->compose($params['template'], $params);
        /* @var \Swift_SmtpTransport $transport */
        $transport = $this->sender->transport;

        $result = $messageManager
            ->setFrom([$transport->getUsername() => \Yii::$app->params['admin_team_name']])
            ->setTo($receiveEmail)
            ->setSubject($params['use'])
            ->send();

        if ($result !== true) {
            $params['receiveEmail'] = $receiveEmail;
            system_log_error($params, __METHOD__);

            return t('Email sending failure error unknown', 'app.admin');
        }

        // 记录邮件日志
        $html = $this->sender->render($params['template'], $params, $this->sender->htmlLayout);
        $this->recordEmail($receiveEmail, $html, $params);

        return true;
    }

    /**
     * 记录邮件
     * @param string $receiveEmail 接收邮件
     * @param string $emailContent 内容
     * @param array $params 参数
     * ----- 必填参数
     * - [[use]] string 服务名称，如：登录认证
     *
     * ------ 非必填参数
     * - [[code]] number|null 验证码
     * - [[sendUser]] int 发送人ID，即管理员ID
     */
    public function recordEmail($receiveEmail, $emailContent, array $params)
    {
        $model = new EmailRecord();
        $model->service_name = $params['use'];
        $model->receive_email = $receiveEmail;
        $model->email_content = $emailContent;
        $model->auth_code = isset($params['code']) ? (string)$params['code'] : '';
        $model->send_user = isset($params['sendUser']) ? $params['sendUser'] : 0;
        $model->send_time = now();
        if (!$model->save()) {
            system_log_error($model->error, __METHOD__);
        }
    }
}