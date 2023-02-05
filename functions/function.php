<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

/**
 * 建议用户自定义的函数在这里编写
 * @since ym1.0
 */

if (!function_exists('is_function')) {
    /**
     * 判断一个变量是否为函数
     * @param string|object $func 变量
     * @return bool
     */
    function is_function($func) {
        return (is_string($func) && function_exists($func)) || (is_object($func) && ($func instanceof \Closure));
    }
}

if (!function_exists('app_log')) {
    /**
     * 记录应用日志（即端业务日志）
     * @param string $subject 日志主题
     * @param string $logLevel 日志等级
     * - debug 业务调试
     * - info 业务信息
     * - warning 业务警告
     * - error 业务出错
     * @param string|array $params 执行参数
     * @param string|array $result 返回结果
     */
    function app_log($subject, $logLevel, $params, $result)
    {
        if (is_array($params)) {
            $params = \yii\helpers\Json::encode($params);
        }

        if (is_array($result)) {
            $result = \yii\helpers\Json::encode($result);
        }

        $model = new \app\models\AppLog();
        $model->setAttributes([
            'subject' => $subject,
            'log_level' => $logLevel,
            'params_content' => $params,
            'result_content' => $result,
        ]);
        $model->save();
    }
}

if (!function_exists('system_log')) {
    /**
     * 记录系统日志（即yii框架和后台日志）
     * 注：
     *  config/web.php 中 `log`组件配置。
     *
     * @param array|string $error
     * @param string $category 日志种类
     */
    function system_log($error, $category)
    {
        Yii::error($error, '[system] ' . $category);
    }
}