<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

/**
 * 建议用户自定义的函数在这里编写
 *
 * @see is_function() 判断一个变量是否为函数
 * @see app_log() 记录应用日志（即端业务日志）
 * @see system_log_error() 记录类型为错误的系统日志（即yii框架和后台日志）
 * @see system_log_info() 记录类型为信息的系统日志（即yii框架和后台日志）
 * @see xml_to_array() XML转数组
 * @see data_to_xml() 数组或对象转XML
 * @see curl_request() CURL
 * @see filesize_unit_convert() 文件大小单位转换
 * @see empty_set_default() 如果数据中指定字段为空则赋予默认值
 * @see notset_set_default() 如果数据中指定字段没有定义则赋予默认值
 * @see html_media_list() html媒体对象列表
 * @see html_tips_page() html无数据,错误等提示页
 *
 * @since ym1.0
 */

if (!function_exists('is_function')) {
    /**
     * 判断一个变量是否为函数
     * @param string|object $func 变量
     * @return bool
     */
    function is_function($func)
    {
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
     * @param string|array|object $params 执行参数
     * @param string|array|object $result 返回结果
     */
    function app_log($subject, $logLevel, $params, $result)
    {
        if (is_array($params)) {
            $params = export_str($params);
        } elseif (is_object($params)) {
            if ($params instanceof JsonSerializable) {
                $params = export_str(\yii\helpers\Json::decode(\yii\helpers\Json::encode($params)));
            } else {
                $params = export_str(get_object_vars($params));
            }
        }

        if (is_array($result)) {
            $result = export_str($result);
        } elseif (is_object($result)) {
            if ($result instanceof JsonSerializable) {
                $result = export_str(\yii\helpers\Json::decode(\yii\helpers\Json::encode($result)));
            } else {
                $result = export_str(get_object_vars($result));
            }
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
     * 记录类型为错误的系统日志（即yii框架和后台日志）
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

if (!function_exists('xml_to_array')) {
    /**
     * XML转数组
     * @param string $xmlStr XML字符串
     * @return mixed
     */
    function xml_to_array($xmlStr)
    {
        $object = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = \yii\helpers\Json::encode($object);
        $array = \yii\helpers\Json::decode($json);

        return $array;
    }
}

if (!function_exists('data_to_xml')) {
    /**
     * 数组或对象转XML
     * @param array|object $data
     * @return string
     */
    function data_to_xml($data)
    {
        if (is_object($data)) {
            $data = get_object_vars($data);
        }

        $xml = '';
        foreach ($data as $key => $val) {
            if (is_null($val)) {
                $xml .= "<$key/>" . PHP_EOL;
            } else {
                if (!is_numeric($key)) {
                    $xml .= "<$key>";
                }

                $xml .= (is_array($val) || is_object($val)) ? data_to_xml($val) : $val;
                if (!is_numeric($key)) {
                    $xml .= "</$key>" . PHP_EOL;
                }
            }
        }

        return '<xml>' . PHP_EOL . $xml . '</xml>';
    }
}

if (!function_exists('curl_request')) {
    /**
     * CURL
     * @param string $url URL
     * @param string $method 请求动作
     * @param string $contentType 内容类型
     * @param array $data 传输数据
     * @param int $timeOut 设置cURL允许执行的最长秒数
     * @return array
     */
    function curl_request($url, $method = 'get', $contentType = '', array $data = [], $timeOut = 20)
    {
        $method = strtolower($method);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        switch ($method) {
            case 'post':
                switch ($contentType) {
                    case 'multipart':
                        $head = 'Content-Type: multipart/form-data; charset=UTF-8';
                        $param = $data;
                        break;
                    case 'xml':
                        $head = 'Content-Type: text/xml; charset=UTF-8';
                        $param = data_to_XML($data);
                        break;
                    case 'json':
                        $head = 'Content-Type: application/json; charset=UTF-8';
                        $param = \yii\helpers\Json::encode($data);
                        break;
                    case 'urlencoded':
                    default:
                        $head = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
                        $param = http_build_query($data);
                }

                curl_setopt($ch, CURLOPT_HTTPHEADER, [$head]);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                break;
            case 'get':
            default:
                ;
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            return ['code' => 500, 'result' => $error];
        }

        curl_close($ch);
        return ['code' => 200, 'result' => $result];
    }
}

if (!function_exists('filesize_unit_convert')) {

    /**
     * 文件大小单位转换
     * @param integer|string|float $size 大小
     * @param string $unit 单位，默认传入的大小单位是字节
     * @return string
     */
    function filesize_unit_convert($size, $unit = 'B')
    {
      settype($size, 'float');

      if ($size > 1024) {
          if (strncasecmp($unit, 'B', 1) === 0) {
              $nextUnit = 'KB';
          } elseif (strncasecmp($unit, 'K', 1) === 0) {
              $nextUnit = 'MB';
          } elseif (strncasecmp($unit, 'M', 1) === 0) {
              $nextUnit = 'GB';
          } elseif (strncasecmp($unit, 'G', 1) === 0) {
              $nextUnit = 'TB';
          } elseif (strncasecmp($unit, 'T', 1) === 0) {
              $nextUnit = 'PB';
          } else {
              return xfloor($size) . $unit;
          }

          return filesize_unit_convert(xdiv($size, 1024), $nextUnit);
      }

        return xfloor($size) . $unit;
    }
}

if (!function_exists('empty_set_default')) {
    /**
     * 如果数据中指定字段为空则赋予默认值
     * @param array $data 要验证的数据
     * @param array $fields 指定字段
     * - key 字段
     * - value 默认值
     *
     * @return array
     * @see notset_return
     */
    function empty_set_default(array $data, array $fields)
    {
        foreach ($fields as $field => $defaultValue) {
            if (empty($data[$field])) {
                $data[$field] = $defaultValue;
            }
        }

        return $data;
    }
}

if (!function_exists('notset_set_default')) {
    /**
     * 如果数据中指定字段没有定义则赋予默认值
     * @param array $data 要验证的数据
     * @param array $fields 指定字段
     * - key 字段
     * - value 默认值
     *
     * @return array
     */
    function notset_set_default(array $data, array $fields)
    {
        foreach ($fields as $field => $defaultValue) {
            if (!isset($data[$field])) {
                $data[$field] = $defaultValue;
            }
        }

        return $data;
    }
}

if (!function_exists('html_media_list')) {

    /**
     * html媒体对象列表
     * @param string|null $imgUrl 图像外链
     * - 如：http://local.testbuerzuzu.com/media/image/admin_static/default-0.jpg
     *
     * @param string|null $mediaHeader 媒体头部内容
     * - 佳能 EOS 5D4单机身【镜头根据需求搭配另拍】
     *
     * @param array $extraOptions 其他选项
     * - array mediaBody 媒体内容
     *          - array
     *          ||
     *          - string|int key 标题标签
     *          - value 内容
     *
     * -  array list 底部列表
     *          - array
     *          ||
     *          - string|int key 标题标签
     *          - value 内容
     *
     * - array options 选项
     *
     *          - media => ...
     *
     *          - mediaLeft => ['class' => .., 'style' => ...]
     *          - img => ['class' => .., 'style' => ...]
     *
     *          - mediaRight => ...
     *          - mediaHeader => ['class' => .., 'style' => ...]
     *          - mediaBody => ...
     *
     *          - list => ...
     *
     * @return string
     * @throws Throwable
     * @see \app\widgets\MediaList::widget()
     */
    function html_media_list($imgUrl, $mediaHeader = null, array $extraOptions = [])
    {
        return \app\widgets\MediaList::widget([
            'imgUrl' => $imgUrl,
            'mediaHeader' => $mediaHeader,
            'mediaBody' => isset($extraOptions['mediaBody']) ? $extraOptions['mediaBody'] : [],
            'list' => isset($extraOptions['list']) ? $extraOptions['list'] : [],
            'options' => isset($extraOptions['options']) ? $extraOptions['options'] : [],
        ]);
    }
}

if (!function_exists('html_tips_page')) {
    /**
     * html无数据,错误等提示页
     * @param string $message 消息字符串
     * @param string $alertType 提示类型
     * - info 信息
     * - error 错误
     * - danger 危险
     * - success 成功
     * - warning 警告
     *
     * @param array $options 其他选项
     * @return string
     * @throws Throwable
     */
    function html_tips_page($message, $alertType = 'info', $options = [])
    {
        $alertTypes = [
            'error'   => 'alert-danger',
            'danger'  => 'alert-danger',
            'success' => 'alert-success',
            'info'    => 'alert-info',
            'warning' => 'alert-warning'
        ];

        return \yii\bootstrap\Alert::widget([
            'body' => $message,
            'options' => array_merge($options, [
                'class' => $alertTypes[$alertType],
            ]),
        ]);
    }
}