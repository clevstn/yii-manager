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
    /**
     * 发送消息
     * @param string $key email场景下: 邮箱 / message场景下: 手机号
     * @param string $scenario 场景
     * - scenario string [email] 邮件 [message] 短信
     *
     * @return true|string
     */
    public static function send($key, $scenario = '')
    {
        
        return true;
    }
}