<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\components;

use app\models\ShortMsgRecord;
use app\sms\SmsRender;
use yii\base\Component;
use yii\helpers\Json;

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
     * @param array $params 参数
     * ----- 必填参数
     * - [[smsContent]] string|null 短信内容，[[template]]和[[smsContent]]二选其一
     * - [[template]] string|null 本地自定义短信模板名称，如：default，[[template]]和[[smsContent]]二选其一，其优先级大于[[smsContent]]
     * - [[use]] string 服务名称，如：登录认证
     *
     * ------ 非必填参数，用于短信模板或其他
     * - [[code]] number|null 验证码
     * - [[sendUser]] int 发送人ID，即管理员ID
     * - 其他参数
     * @return true|string
     */
    public function send($mobile, array $params)
    {
        if (!empty($params['template'])) {
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
        if ($callRes === true) {
            // 记录短信日志
            $this->recordLog($mobile, $params);
        }

        return $callRes;
    }

    /**
     * 记录日志
     * @param string $mobile 手机号
     * @param array $params 其他参数
     */
    protected function recordLog($mobile, $params)
    {
        $model = new ShortMsgRecord();
        $model->service_name = $params['use'];
        $model->msg_content = $params['smsContent'];
        $model->auth_code = isset($params['code']) ? (string)$params['code'] : '';
        $model->send_user = isset($params['sendUser']) ? $params['sendUser'] : 0;
        $model->receive_mobile = $mobile;
        $model->send_time = now();
        if (!$model->save()) {
            system_log_error($model->error, __METHOD__);
        }
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
        foreach ($this->configs as $method => $config) {
            if (isset($config['enabled']) && $config['enabled'] === true) {
                return $this->{$method}($mobile, $params);
            }
        }

        system_log_error(t('No interface is available', 'app.admin'), __METHOD__);
        return false;
    }

    /**
     * 飞鸽传书短信服务
     * @param $mobile
     * @param array $params
     * @return true|string
     */
    protected function feiGe($mobile, array $params)
    {
        $config = $this->configs['feige'];

        $requestData = [
            'apikey'  =>  $config['apiKey'],
            'secret' => $config['secret'],
            'content' => $params['smsContent'],
            'mobile' => $mobile,
            'sign_id' => $config['signId'],
        ];

        $result = curl_request($config['apiUrl'], 'post', 'json', $requestData);
        if ($result['code'] == 200) {
            $responseData = Json::decode($result['result']);
            if ($responseData['code'] == 0) {
                return true;
            }

            system_log_error($result, __METHOD__);
            return $responseData['msg'];
        }

        system_log_error($result, __METHOD__);
        return $result['result'];
    }
}