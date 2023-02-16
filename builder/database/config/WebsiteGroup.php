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
 * 网站配置
 * @author cleverstone
 * @since ym1.0
 */
class WebsiteGroup extends Group
{
    /**
     * {@inheritDoc}
     */
    public $name = '网站配置';

    /**
     * {@inheritDoc}
     */
    public $code = 'WEBSITE_GROUP';

    /**
     * {@inheritDoc}
     */
    public $desc = '网站配置分组';

    /**
     * {@inheritDoc}
     */
    public $formTips = '网站配置分组';

    /**
     * {@inheritDoc}
     */
    public function define()
    {
        return [
            $this->normalizeItem('WEBSITE_SWITCH', '0', SystemConfig::SW, '', '网站维护开关', '0:关闭 1:开启, 当开启后, 网站将显示网站维护标语, 网站将无法使用', '当开启后, 网站将显示网站维护标语, 网站将无法使用'),
            $this->normalizeItem('WEBSITE_DENY_TIPS', '网站维护中.我们工程师正在努力抢修,请您耐心等待...', SystemConfig::TEXTAREA, '', '网站维护标语', '关闭后,网站将禁止访问', ''),
        ];
    }
}