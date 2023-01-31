<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\components;

use app\sms\SmsRender;
use yii\base\Component;

/**
 * 短信组件
 *
 * @author cleverstone
 * @since ym1.0
 */
class Sms extends Component
{
    /**
     * 短信配置项
     * @var array
     */
    public $configs;

    /**
     * 发送短信
     * @param string $mobile 手机号
     * @param string $use 服务名称
     * @param array $params 参数
     * - [[template]] string|null 本地自定义短信模板名称，如：default
     * - [[use]] string|null 服务名称，如：登录认证。
     * - [[code]] number|null 验证码
     * - 其他参数
     * @return true|string
     */
    public function send($mobile, $use, array $params)
    {
        $params['smsContent'] = null;
        if (isset($params['template']) && !empty($params['template'])) {
            $smsRender = new SmsRender([
                'viewName' => $params['template'],
                'params' => $params,
            ]);
            // 获取短信内容
            $params['smsContent'] = $smsRender->execute();
            unset($params['template']);
        }

        // 调用三方接口发送短信
        $callRes = $this->callSmsApi($mobile, $params);
        if (true === $callRes) {
            // 记录短信日志
            $this->recordLog();
        }

        return $callRes;
    }

    /**
     * 记录日志
     * @return void
     */
    protected function recordLog()
    {

    }

    /**
     * 调用短信接口，发送短信
     * @param string $mobile 手机号
     * @param array $params 其他参数
     * - [[use]] string|null 服务名称，如：登录认证。
     * - [[code]] number|null 验证码
     * @return true|string
     */
    protected function callSmsApi($mobile, array $params)
    {
        return true;
    }
}