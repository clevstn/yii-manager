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
            $this->normalizeItem('ADMIN_ALL', '1', SystemConfig::SW, '0:开启|1:关闭', '尝试登陆限制', '是否开启后台尝试登陆限制, 0:关闭 1开启', '开启后, 登录密码错误次数受限'),
            $this->normalizeItem('ADMIN_ALL_SIZE', '10', SystemConfig::NUMBER, '', '错误次数', '后台尝试登陆密码错误次数, 默认10次', '尝试登陆限制开启后生效'),
            $this->normalizeItem('ADMIN_ALL_DENY_TIME', '600', SystemConfig::NUMBER, '', '封停时间(秒)', '后台尝试登陆失败封停时间,单位秒', '后台尝试登陆失败封停时间,单位秒'),
            $this->normalizeItem('ADMIN_DEFAULT_PAGE', '20', SystemConfig::NUMBER, '', '分页数量', '后台列表每页数据条数', '后台列表每页数据条数'),
        ];
    }
}