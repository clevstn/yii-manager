<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\logic;

/**
 * 消息相关
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Message
{
    // 场景 - 邮件
    const SCENARIO_EMAIL = 'email';
    // 场景 - 短信
    const SCENARIO_MESSAGE = 'message';

    /**
     * 发送消息
     * @param string $key email场景下: 邮箱 / message场景下: 手机号
     * @param string $scenario 场景
     * - scenario string [email] 邮件 [message] 短信
     *
     * @return true|string
     */
    public static function send($key, $scenario = self::SCENARIO_EMAIL)
    {
        switch ($scenario) {
            case self::SCENARIO_EMAIL:   // 邮件
                return self::sendEmail($key);
            case self::SCENARIO_MESSAGE: // 短信
                return self::sendSMS($key);
            default:
                return t('unknown message scenarios');
        }
    }

    public static function sendEmail($email)
    {
        
    }

    public static function sendSMS($mobile)
    {

    }
}