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

if (!function_exists('system_log_error')) {
    /**
     * 记录类型为错误系统日志（即yii框架和后台日志）
     * 注：
     *  config/web.php 中 `log`组件配置。
     *
     * @param array|string $error
     * @param string $category 日志种类
     */
    function system_log_error($error, $category)
    {
        Yii::error($error, '[admin]' . $category);
    }
}

if (!function_exists('system_log_info')) {
    /**
     * 记录类型为信息的系统日志（即yii框架和后台日志）
     * 注：
     *  config/web.php 中 `log`组件配置。
     *
     * @param array|string $info
     * @param string $category 日志种类
     * @throws \yii\base\InvalidConfigException
     */
    function system_log_info($info, $category)
    {
        $logger = Yii::getLogger();
        $messages = $logger->messages;
        $logger->messages = [];

        $targets = Yii::$app->log->targets;
        // 要更改levels的target
        $targetKeys = ['db'];

        $originLevels = [];
        foreach ($targets as $key => $target) {
            if (in_array($key, $targetKeys)) {
                $levels = $target->getLevels();

                if ($levels && !($levels & \yii\log\Logger::LEVEL_INFO)) {
                    $originLevels[$key] = $levels;
                    $levels |= \yii\log\Logger::LEVEL_INFO;

                    $target->setLevels($levels);
                }
            }
        }

        Yii::info($info, '[admin]' . $category);

        if (!empty($originLevels)) {
            Yii::getLogger()->flush();

            foreach ($targets as $key => $target) {
                if (in_array($key, $targetKeys) && isset($originLevels[$key])) {
                    $target->setLevels($originLevels[$key]);
                }
            }
        }

        $tempMessage = $logger->messages;
        $logger->messages = $messages;
        foreach ($tempMessage as $message) {
            $logger->messages[] = $message;
        }
    }
}