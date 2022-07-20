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
     * @param string $key email场景下: 邮箱地址 / message场景下: 手机号码
     * @param string $template 短信/邮箱模板名称
     * @param string $use 业务用途
     * @param array $params 短信/邮箱模板参数
     * @param string $scenario 消息场景
     * - scenario string [email] 邮件 [message] 短信
     * @throws \Throwable
     * @return true|string
     */
    public static function sendSync($key, $template, $use, array $params = [], $scenario = self::SCENARIO_EMAIL)
    {
        switch ($scenario) {
            case self::SCENARIO_EMAIL:   // 邮件
                return self::sendSyncEmail($key, $template, $use, $params);
            case self::SCENARIO_MESSAGE: // 短信
                return self::sendSyncSMS($key, $template, $use, $params);
            default:
                return t('unknown message scenarios');
        }
    }

    /**
     * 同步发送邮件
     * @param string $email 邮箱
     * @param string $template 模板名称
     * @param string $use 业务用途
     * @param array $params 模板参数
     * @return true|string
     */
    public static function sendSyncEmail($email, $template, $use, array $params = [])
    {
        return true;
    }

    /**
     * 同步发送短信
     * @param string $mobile 手机号
     * @param string $template 模板名称
     * @param string $use 业务用途
     * @param array $params 模板参数
     * @throws \Throwable
     * @return true|string
     */
    public static function sendSyncSMS($mobile, $template, $use, array $params = [])
    {
        // 获取短信签名
        $smsSign = Yii::$app->params['app_sign'];
        // 检查[[params]]中是否存在[[use]]字段
        // 如果不存在则添加[[use]]参数
        if (empty($params['use'])) {
            $params['use'] = $use;
        }

        // 获取短信内容
        $smsRender = new SmsRender([
            'viewName' => $template,
            'params' => $params,
        ]);

        $smsContent = $smsRender->execute();
        // 调用三方接口发送短信
        $_ = new static();
        $callRes = $_->callSmsApi($mobile, $smsSign, $smsContent);
        if (true === $callRes) {
            // 记录短信日志

        }

        return $callRes;
    }

    /**
     * 调用短信接口,发送短信
     * @param string $mobile 手机号
     * @param string $smsSign 短信签名
     * @param string $smsContent 短信内容
     * @return true|string
     */
    protected function callSmsApi($mobile, $smsSign, $smsContent)
    {
        return true;
    }
}