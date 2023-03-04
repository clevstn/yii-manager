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
 * 站点配置
 * @author cleverstone
 * @since ym1.0
 */
class WebsiteGroup extends Group
{
    /**
     * {@inheritDoc}
     */
    public $name = '站点配置';

    /**
     * {@inheritDoc}
     */
    public $code = 'WEBSITE_GROUP';

    /**
     * {@inheritDoc}
     */
    public $desc = '站点配置分组';

    /**
     * {@inheritDoc}
     */
    public $formTips = '站点配置分组';

    /**
     * {@inheritDoc}
     */
    public function define()
    {
        return [
            $this->normalizeItem(
                'WEBSITE_SWITCH',
                '0',
                SystemConfig::SW,
                '',
                '站点维护开关',
                '0:关闭 1:开启, 当开启后, 站点将显示站点维护标语, 站点除超级管理员外将无法登录',
                '当开启后, 站点将显示站点维护标语, 站点除超级管理员外将无法登录!'
            ),
            $this->normalizeItem(
                'WEBSITE_DENY_TIPS',
                '站点维护中.我们工程师正在努力抢修,请您耐心等待...',
                SystemConfig::TEXTAREA,
                '',
                '站点维护标语',
                '关闭后,站点除超级管理员外将禁止访问',
                ''
            ),
        ];
    }
}