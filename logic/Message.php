<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\logic;

use Yii;
use app\sms\SmsRender;

/**
 * 消息相关
 * @author HiLi
 * @since 1.0
 */
class Message
{
    // 场景 - 邮件
    const SCENARIO_EMAIL = 'email';
    // 场景 - 短信
    const SCENARIO_MESSAGE = 'message';

    /**
     * 同步发送消息
     * @param string $key email场景下: 邮箱 / message场景下: 手机号
     * @param string $template 短信/邮箱模板名称
     * @param array $params 短信/邮箱模板参数
     * @param string $scenario 消息场景
     * - scenario string [email] 邮件 [message] 短信
     * @throws \Throwable
     * @return true|string
     */
    public static function sendSync($key, $template, array $params, $scenario = self::SCENARIO_EMAIL)
    {
        switch ($scenario) {
            case self::SCENARIO_EMAIL:   // 邮件
                return self::sendSyncEmail($key, $template, $params);
            case self::SCENARIO_MESSAGE: // 短信
                return self::sendSyncSMS($key, $template, $params);
            default:
                return t('unknown message scenarios');
        }
    }

    /**
     * 同步发送邮件
     * @param string $email 邮箱
     * @param string $template 模板名称
     * @param array $params 模板参数
     * @return true|string
     */
    public static function sendSyncEmail($email, $template, array $params)
    {
        return true;
    }

    /**
     * 同步发送短信
     * @param string $mobile 手机号
     * @param string $template 模板名称
     * @param array $params 模板参数
     * @throws \Throwable
     * @return true|string
     */
    public static function sendSyncSMS($mobile, $template, array $params)
    {
        // 获取短信签名
        $smsSign = Yii::$app->params['app_sign'];
        // 检查[[params]]中是否存在[[use]]字段
        if (empty($params['use'])) {
            return t('The parameter [[use]] is required');
        }

        $use = $params['use'];
        // 获取短信内容
        $smsRender = new SmsRender([
            'viewName' => $template,
            'params' => $params,
        ]);

        $smsContent = $smsRender->execute();
        // 调用三方接口发送短信
        $_ = new static();
        $callRes = $_->callSmsApi();
        if (true === $callRes) {
            // 记录短信日志

        }

        return $callRes;
    }

    /**
     * 调用短信接口,发送短信
     * @return bool
     */
    protected function callSmsApi()
    {
        return true;
    }
}