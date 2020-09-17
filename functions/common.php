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

if (!function_exists('table_column_helper')) {
    /**
     * 快捷设置表格列
     * @param string $title 字段标题，不设置则已该字段作为该表格列的标题
     * @param array $options 选项
     * - attribute html属性
     * - style     css样式
     * @param null $callback 自定义回调，用来自定义字段值
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function table_column_helper($title = '', $options = [], $callback = null)
    {
        return [
            'title' => $title,
            'options' => $options,
            'callback' => $callback,
        ];
    }
}

if (!function_exists('table_action_helper')) {
    /**
     * 快捷设置表格行操作项
     * @param string $type 调用类型
     * - page 页面调用
     * - modal 模态框调用
     * - ajax XMLHttpRequest调用
     * - division 分割线
     * @param array $options 选项
     * - title 按钮标题和page、modal标题
     * - icon  按钮图标
     * - route 路由
     * - params 路由参数
     * - method 请求动作，当type为ajax时，该配置项有效
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function table_action_helper($type, $options)
    {
        if (!empty($options['route'])) {
            $options['route'] = \yii\helpers\Url::toRoute('/' . ltrim($options['route'], '/'));
        }

        if (empty($options['method'])) {
            $options['method'] = 'get';
        }

        if (empty($options['title'])) {
            $options['title'] = '默认项';
        }

        if (empty($options['icon'])) {
            $options['icon'] = 'fa fa-paper-plane-o';
        }

        $options['method'] = strtolower($options['method']);
        return [
            'type' => $type,
            'options' => $options,
        ];
    }
}

if (!function_exists('resolve_pages')) {
    /**
     * 解析分页
     * @param \yii\db\QueryInterface $query
     * @param array|string $orderBy
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function resolve_pages(\yii\db\QueryInterface $query, $orderBy = ['id' => SORT_DESC])
    {
        $countQuery = clone $query;
        $pages = new \yii\data\Pagination([
            'totalCount' => $countQuery->count(),
            'pageSizeLimit' => [1, 500],
        ]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy($orderBy)
            ->all();

        return [$pages, $models];
    }
}

if (!function_exists('accept_json')) {
    /**
     * 是否接收Json
     * @return bool
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    function accept_json()
    {
        $acceptableTypes = Yii::$app->getRequest()->getAcceptableContentTypes();
        if (!empty($acceptableTypes) && array_keys($acceptableTypes)[0] === 'application/json') {
            return true;
        }

        return false;
    }
}

// 包含用户自定义函数文件
include __DIR__ . '/function.php';

