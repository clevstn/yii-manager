<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\database\config;

use app\builder\common\Group;
use app\models\SystemConfig;

/**
 * 后台配置
 * @author cleverstone
 * @since ym1.0
 */
class AdminGroup extends Group
{
    /**
     * {@inheritDoc}
     */
    public $name = '后台配置';

    /**
     * {@inheritDoc}
     */
    public $code = 'ADMIN_GROUP';

    /**
     * {@inheritDoc}
     */
    public $desc = '后台配置分组';

    /**
     * {@inheritDoc}
     */
    public $formTips = '后台配置分组';

    /**
     * {@inheritDoc}
     */
    public function define()
    {
        return [
            $this->normalizeItem('ADMIN_SSO', '0', SystemConfig::SW, '0:开启|1:关闭', 'SSO', '是否开启后台单点登录, 0:关闭 1:开启', '开启后, 单个账号禁止多人同时登陆'),
            $this->normalizeItem('ADMIN_CCEE', '0', SystemConfig::SW, '0:开启|1:关闭', '2FA', '是否开启后台安全认证, 0:关闭 1:邮箱认证 2:短信认证 3:MFA认证', '开启后, 后台登录将进行安全校验'),
            $this->normalizeItem('ADMIN_OPERATE_LOG', '1', SystemConfig::SW, '0:开启|1:关闭', '操作日志', '是否开启后台操作日志, 0:关闭 1: 开启', ''),
            $this->normalizeItem('ADMIN_LOGIN_LOG', '1', SystemConfig::SW, '0:开启|1:关闭', '登录日志', '是否开启后台登录日志, 0:关闭 1: 开启', ''),
        ];
    }
}