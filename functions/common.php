<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/8
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

if (!function_exists('dd')) {
    /**
     * 打印调试
     * @param mixed $mixed 变量
     * @param int $depth 内容显示的最大深度
     * @param boolean $highlight 是否高亮显示
     * @author cleverstone <yang_hui_lei@163.com>
     */
    function dd($mixed, $depth = 10, $highlight = true)
    {
        \yii\helpers\VarDumper::dump($mixed, $depth, $highlight);
        exit(0);
    }
}

if (!function_exists('export_str')) {
    /**
     * 导出变量为字符串
     * @param mixed $mixed 变量
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     */
    function export_str($mixed)
    {
        return \yii\helpers\VarDumper::export($mixed);
    }
}

if (!function_exists('encrypt_password')) {
    /**
     * 密码加密
     * @param string $password 明文密码
     * @return string
     * @throws \yii\base\Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function encrypt_password($password)
    {
        /**
         * @var int Default cost used for password hashing.
         * Allowed value is between 4 and 31.
         * @see generatePasswordHash()
         * @since 1.0
         */
        $passwordHashCost = 4;
        return \Yii::$app->security->generatePasswordHash($password, $passwordHashCost);
    }
}

if (!function_exists('check_password')) {

    /**
     * 密码校验
     * @param string $password 明文密码
     * @param string $hash 密码hash
     * @return boolean
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function check_password($password, $hash)
    {
        return \Yii::$app->security->validatePassword($password, $hash);
    }
}

if (!function_exists('random_string')) {
    /**
     * 生成指定长度的字符串
     * @param boolean $trimSpecial 是否去除特殊字符, 如: -_
     * @param int $len 字符串长度
     * @return string
     * @throws \yii\base\Exception
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function random_string($trimSpecial = false, $len = 32)
    {
        if ($len < 1 || $len > 255) {
            $len = 32;
        }

        $randomStr = \Yii::$app->security->generateRandomString($len);
        if ($trimSpecial === true) {
            return strtr($randomStr, '-_', '');
        }

        return $randomStr;
    }
}

if (!function_exists('random_number')) {
    /**
     * 生成指定长度的数字串
     * @param int $len 数字串长度
     * @return int
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function random_number($len = 6)
    {
        $minLength = 1;
        $defaultLength = 6;
        // 最大长度不能超出10位数,否则PHP引擎会自动转换为浮点型
        $maxLength = 10;
        if ($len < $minLength || $len > $maxLength) {
            $len = $defaultLength;
        }

        if ($len <= 1) {
            $min = 0;
        } else {
            $min = pow(10, $len - 1);
        }

        if ($len >= 10) {
            $max = 2147483647;
        } else {
            $max = pow(10, $len) - 1;
        }

        return mt_rand($min, $max);
    }
}

if (!function_exists('order_number')) {
    /**
     * 生成指定前缀的订单号
     * @param string $prefix 订单前缀
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function order_number($prefix = '')
    {
        $prefix = (string)$prefix;
        return $prefix . date('YmdHis') . substr(microtime(), 2, 4) . random_number(5);
    }
}

if (!function_exists('now')) {

    /**
     * 获取当前时间
     * @param bool|string $toString 是否格式化或格式化正则
     * @param string $timeZone 时区
     * @return false|int|string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     *
     */
    function now($toString = true, $timeZone = '')
    {
        if (!empty($timeZone)) {
            date_default_timezone_set($timeZone);
        }

        if ($toString === true) {
            return date('Y-m-d H:i:s');
        } elseif (is_string($toString)) {
            return date($toString);
        } else {
            return time();
        }
    }
}

if (!function_exists('custom_config')) {
    /**
     * 获取用户自定义配置
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function custom_config()
    {
        $files = \yii\helpers\FileHelper::findFiles(\Yii::getAlias('@builder/database/config'), [
            'only' => ['*.php'],
        ]);

        $config = [];
        $temp = [];
        if (!empty($files)) {
            foreach ($files as $file) {
                $temp = array_merge((array)include $file, $temp);
            }

            foreach ($temp as $item) {
                if (is_array($item)) {
                    array_push($config, $item);
                }
            }
        }

        return $config;
    }
}

if (!function_exists('xss_filter')) {

    /**
     * Formats the value as HTML text.
     * The value will be purified using [[HtmlPurifier]] to avoid XSS attacks.
     * @param string $html html文本
     * @param null $config 配置项
     * @return string
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function xss_filter($html, $config = null)
    {
        return \yii\helpers\HtmlPurifier::process($html, $config);
    }
}

if (!function_exists('html_escape')) {
    /**
     * Encodes special characters into HTML entities.
     * The [[\yii\base\Application::charset|application charset]] will be used for encoding.
     * @param string $content the content to be encoded
     * @param bool $doubleEncode whether to encode HTML entities in `$content`. If false,
     * HTML entities in `$content` will not be further encoded.
     * @return string the encoded content
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function html_escape($content, $doubleEncode = false)
    {
        return \yii\helpers\Html::encode($content, $doubleEncode);
    }
}

// 包含用户自定义函数文件
include __DIR__ . '/function.php';

