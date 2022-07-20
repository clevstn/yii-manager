<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\database\config;

use app\builder\common\Group;
use app\models\SystemConfig;

/**
 * 邮箱配置
 * @author HiLi
 * @since 1.0
 */
class EmailGroup extends Group
{
    /**
     * {@inheritDoc}
     */
    public $name = '邮箱配置';

    /**
     * {@inheritDoc}
     */
    public $code = 'EMAIL_GROUP';

    /**
     * {@inheritDoc}
     */
    public $desc = '邮箱配置';

    /**
     * {@inheritDoc}
     */
    public $formTips = '邮箱配置';

    /**
     * {@inheritDoc}
     */
    public function define()
    {
        return [
            $this->normalizeItem('SMTP_SERVER', 'smtp.qq.com', SystemConfig::TEXT, '', 'SMTP服务器', 'SMTP服务器', 'SMTP服务器, 如:smtp.qq.com/smtp.163.com'),
            $this->normalizeItem('SMTP_PORT', '465', SystemConfig::NUMBER, '', 'SMTP端口', 'SMTP端口', 'SMTP端口,不加密默认25/SSL默认465/TLS默认587'),
            $this->normalizeItem('SMTP_USER', '', SystemConfig::TEXT, '', 'SMTP用户名', 'SMTP用户名', 'SMTP用户名'),
            $this->normalizeItem('SMTP_PASSWORD', '', SystemConfig::TEXT, '', 'SMTP密码', 'SMTP密码', 'SMTP密码'),
            $this->normalizeItem('SMTP_SECRET_WAY', '', SystemConfig::SELECT, 'None:无|SSL:SSL|TLS:TLS', '加密方式', '加密方式,None:无 SSL:对应默认端口465 TLS:对应默认端口587', ''),
            $this->normalizeItem('SMTP_SIGN', '', SystemConfig::TEXT, '', '签名', '发送人签名, 默认使用用户名', '发送人签名, 默认使用用户名'),
        ];
    }
}