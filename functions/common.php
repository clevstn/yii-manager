<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

/**
 * Yii-manager 内置函数库
 * @author cleverstone
 * @since ym1.0
 */

if (!function_exists('dd')) {
    /**
     * 打印调试
     * @param mixed $mixed 变量
     * @param int $depth 内容显示的最大深度
     * @param boolean $highlight 是否高亮显示
     */
    function dd($mixed, $depth = 10, $highlight = true)
    {
        \yii\helpers\VarDumper::dump($mixed, $depth, $highlight);
        exit(0);
    }
}

if (!function_exists('export_str')) {
    /**
     * 返回变量字符串
     * @param mixed $mixed 变量
     * @return string
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
     */
    function encrypt_password($password)
    {
        /**
         * @var int Default cost used for password hashing.
         * Allowed value is between 4 and 31.
         * @see generatePasswordHash()
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
     */
    function random_string($trimSpecial = false, $len = 32)
    {
        if ($len < 1 || $len > 255) {
            $len = 32;
        }

        $randomStr = \Yii::$app->security->generateRandomString($len);
        if ($trimSpecial === true) {
            return strtr($randomStr, ['-' => rand(0, 9), '_' => rand(0, 9)]);
        }

        return $randomStr;
    }
}

if (!function_exists('random_number')) {
    /**
     * 生成指定长度的数字串
     * @param int $len 数字串长度
     * @return int
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
     */
    function html_escape($content, $doubleEncode = false)
    {
        return \yii\helpers\Html::encode($content, $doubleEncode);
    }
}

if (!function_exists('table_column_helper')) {
    /**
     * 快捷设置表格列
     * @param string $title 字段标题，不设置则该字段名作为该表格列的标题
     * @param array $options 选项
     * - attribute html属性
     * - style     css样式
     * @param null $callback 自定义回调，用来自定义字段值
     * @return array
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
     * - title 按钮标题和`page`、`modal`标题
     * - icon  按钮图标
     * - route 路由
     * - params 路由参数
     * - method 请求动作，当type为ajax时，该配置项有效
     * - width  当前type为modal时有效，指定modal的宽，默认500px
     * - height 当前type为modal时有效，指定modal的高，默认500px
     *
     * @return array
     * @throws \ReflectionException
     */
    function table_action_helper($type, $options)
    {
        $optionsInstance = new \app\builder\table\RowActionOptions($options);
        return [
            'type' => $type,
            'options' => $optionsInstance->toArray(),
        ];
    }
}

if (!function_exists('table_toolbar_filter_helper')) {
    /**
     * 快捷设置表格工具栏筛选项
     * @param array $options
     * - control 控件类型 `text`、`select`、`number`、`datetime`、`date`、`year`、`month`、`time`、`custom`
     * - label   标签名
     * - range   是否是区间, 用于日期控件
     * - placeholder 提示
     * - default 默认值(项)
     * - style 样式，值可以是数组也可以是字符串
     * - attribute 属性，值可以是数组也可以是字符串
     * - options 选项，用于select控件 格式：[`value` => `label`]
     * - widget 自定义组件，值必须是\app\builder\table\CustomControl的实现
     *
     * @return array
     * @throws ReflectionException
     */
    function table_toolbar_filter_helper(array $options)
    {
        $toolbarFilterOptions = new \app\builder\table\ToolbarFilterOptions($options);
        return $toolbarFilterOptions->toArray();
    }
}

if (!function_exists('table_toolbar_custom_helper')) {
    /**
     * 快捷设置表格工具栏自定义项
     * @param string $pos
     * - left 工具栏内左边
     * - right 工具栏内右边
     * @param array $options
     * - title string 按钮标题
     * - icon string  按钮图标
     * - option string 选项
     *      - page  页面
     *      - modal 模态框
     *      - ajax  XMLHttpRequest
     * - route string 路由
     * - params array 参数
     * - method string 访问动作, ajax有效 只支持`get`、`post`
     * - width string 当前type为modal时有效，指定modal的宽，默认800px
     * - height string 当前type为modal时有效，指定modal的高，默认520px
     *
     * @return array
     * @throws ReflectionException
     */
    function table_toolbar_custom_helper($pos, $options = [])
    {
        $options['pos'] = $pos;
        $toolbarCustomOptions = new \app\builder\table\ToolbarCustomOptions($options);

        return $toolbarCustomOptions->toArray();
    }
}

if (!function_exists('form_fields_helper')) {
    /**
     * 快捷注册表单字段项
     * @param string $control 控件类型
     * @see \app\builder\form\FieldsOptions
     * @param array $options
     * - label 标签名
     * - placeholder 提示语
     * - default 默认值，多个值用`逗号`隔开
     * - defaultLink 默认附件外链，多个用`逗号隔开`，用于文件上传
     * - required 是否必填项
     * - comment 注释语
     * - range 是否是区间，用于日期控件
     * - options 选项，用于`radio`、`checkbox`、`select`控件，格式：[`value` => `label`]
     * - rows 行数，用于文本域
     * - number 文件数量，用于文件上传
     * - fileScenario 文件上传类型场景，为空则可以上传任意类型，用于文件上传
     * - saveDirectory 文件保存目录(建议使用数据表名称作为保存目录名)，用于文件上传
     * - pathPrefix 文件路径前缀(建议使用数据表格当前数据ID作为路径前缀)，用于文件上传
     * - layouts bootstrap布局，默认`12`
     * - style 控件样式
     * - attribute 控件属性
     * - widget 自定义项，用于`control`类型为`custom`
     * @return array
     * @throws ReflectionException
     */
    function form_fields_helper($control, array $options)
    {
        $options['control'] = $control;
        $fieldsOptions = new \app\builder\form\FieldsOptions($options);

        return $fieldsOptions->toArray();
    }
}

if (!function_exists('resolve_pages')) {
    /**
     * 解析分页
     * @param \yii\db\QueryInterface $query
     * @param array|string $orderBy
     * @return array
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

if (!function_exists('preg_script')) {
    /**
     * 从script标签中提取js脚本
     * @param string $scriptTag
     * @return string
     */
    function preg_script($scriptTag)
    {
        if (preg_match('~<script[^>]*>(.*)</script>~si', trim($scriptTag), $matches)) {
            return $matches[1];
        }

        return '';
    }
}

if (!function_exists('t')) {

    /**
     * I18N转义
     * @param string $messages 消息
     * @param string $category 类别
     * @param array $params 参数
     * @param null|string $language 语言
     * @return string
     */
    function t($messages, $category = 'app', $params = [], $language = null)
    {
        return Yii::t($category, $messages, $params, $language);
    }
}

if (!function_exists('attach_url')) {
    /**
     * 获取附件URL
     * @param int $attachId 附件ID
     * @return string
     */
    function attach_url($attachId)
    {
        if (!empty($attachId)) {
            // SQL查询附件
            // ...

            // 判断是否存在，如果存在返回处理过后的`path`，否则返回默认文件。
            // path: 附件路径
            // attachment_scenario: 附件上传类型场景
            if (!empty($attachmentData)) {
                return Yii::$app->uploads->getAttachmentUrl($attachmentData['path'], $attachmentData['attachment_scenario']);
            }
        }

        // 附件ID为空,返回默认附件路径
        return into_full_url(Yii::$app->params['admin_default_photo']);
    }
}

if (!function_exists('into_full_url')) {
    /**
     * 绝对url转完整url
     * @param string $url url路径
     * @return string
     */
    function into_full_url($url)
    {
        if (strncasecmp($url, 'http', 4)) {
            return rtrim(Yii::$app->request->getHostInfo(), '/') . '/' . ltrim($url, '/');
        }

        return $url;
    }
}

if (!function_exists('xadd')) {
    /**
     * 高精度加法函数
     * @param mixed ...$params
     * @return float
     * @see bcadd()
     * @since >= php5.6
     */
    function xadd(...$params)
    {
        // 参数数量>=1
        if (count($params) == 0) {
            throw new \InvalidArgumentException('At least one parameter. ');
        }

        $tempAddend = 0;
        foreach ($params as $addend) {
            /* bc库最高精度12位 */
            $tempAddend = bcadd($addend, $tempAddend, 12);
        }

        return (float)$tempAddend;
    }
}

if (!function_exists('xsub')) {
    /**
     * 高精度减法函数
     * @param mixed ...$params
     * @return float
     * @see bcsub()
     * @since >= php5.6
     */
    function xsub(...$params)
    {
        // 参数数量>=1
        if (count($params) == 0) {
            throw new \InvalidArgumentException('At least one parameter. ');
        }

        // 拿出第一个参数,做为被减数
        $minuend = array_shift($params);
        // 剩余数量为空,则直接返回被减数
        if (!count($params)) {
            return $minuend;
        }

        // 剩余减数累加
        $total = xadd(...$params);
        // 做精度减法运算,得出结果
        return (float)bcsub($minuend, $total, 12);
    }
}

if (!function_exists('xmul')) {
    /**
     * 高精度乘法函数
     * @param mixed ...$params
     * @return float
     * @see bcmul()
     * @since >= php5.6
     */
    function xmul(...$params)
    {
        // 参数数量>=1
        if (count($params) == 0) {
            throw new \InvalidArgumentException('At least one parameter. ');
        }

        $tempMultiplier = 1;
        foreach ($params as $multiplier) {
            /* bc库最高精度12位 */
            $tempMultiplier = bcmul($multiplier, $tempMultiplier, 12);
        }

        return (float)$tempMultiplier;
    }
}

if (!function_exists('xdiv')) {
    /**
     * 高精度除法函数
     * @param mixed ...$params
     * @return float
     * @see bcdiv()
     * @since >= php5.6
     */
    function xdiv(...$params)
    {
        // 参数数量>=1
        if (count($params) == 0) {
            throw new \InvalidArgumentException('At least one parameter. ');
        }

        // 拿出第一个参数,做为被除数
        $dividend = array_shift($params);
        // 剩余数量为空,则直接返回被除数
        if (!count($params)) {
            return $dividend;
        }

        // 剩余除数累乘
        $total = xmul(...$params);
        // 做精度除法运算,得出结果
        return (float)bcdiv($dividend, $total, 12);
    }
}

if (!function_exists('xfloor')) {
    /**
     * 向下保留n位
     * @param float|int $val 值
     * @param int $precision 精度,默认:2
     * @return float
     */
    function xfloor($val, $precision = 2)
    {
        return bcadd($val, 0, $precision);
    }
}

if (!function_exists('xceil')) {
    /**
     * 向上保留n位
     * @param float|int $val 值
     * @param int $precision 精度,默认:2
     * @return float
     */
    function xceil($val, $precision = 2)
    {
        if ($precision < 0) {
            throw new \InvalidArgumentException('The parameter [[precision]] must be greater than 0. ');
        }

        $multiplier = pow(10, $precision);
        $result = bcdiv(ceil(xmul($val, $multiplier)), $multiplier, $precision);

        return $result;
    }
}

if (!function_exists('xround')) {
    /**
     * 四舍五入保留n位
     * @param float|int $val 值
     * @param int $precision 精度,默认:2
     * @return float
     */
    function xround($val, $precision = 2)
    {
        return round($val, $precision);
    }
}

if (!function_exists('isset_return')) {

    /**
     * 对数据字段进行`isset`验证，失败返回字段对应的提示语
     * @param array $data 待验证的数据
     * @param array $fields 要验证的字段和对应的提示语
     * - key: 字段
     * - value: 自定义提示语
     * --------------
     *
     * - value: 字段
     *
     * @return bool|mixed|string
     */
    function isset_return(array $data, array $fields)
    {
        foreach ($fields as $field => $message) {
            if (is_numeric($field)) {
                // 索引
                if (!isset($data[$message])) {
                    return t('request parameter {param} is not defined', 'app', ['param' => $message]);
                }
            } else {
                // 关联
                if (!isset($data[$field])) {
                    return $message ?: t('request parameter {param} is not defined', 'app', ['param' => $field]);
                }
            }
        }

        return true;
    }
}

if (!function_exists('load_file')) {

    /**
     * 加载文件
     * @param string $filePath 文件正确路径
     * @param bool $isReturn 是否返回结果
     * @param bool $once 是否只引入一次
     * @param bool $force 是否强制引入
     * @return bool|mixed
     */
    function load_file($filePath, $isReturn = false, $once = false, $force = false)
    {
        if (!is_file($filePath)) {
            throw new \http\Exception\InvalidArgumentException(t('this is an invalid file path'));
        }

        if ($once && $force) {
            if ($isReturn) {
                return require_once $filePath;
            }

            require_once $filePath;
        } elseif ($once && !$force) {
            if ($isReturn) {
                return include_once $filePath;
            }

            include_once $filePath;
        } elseif (!$once && $force) {
            if ($isReturn) {
                return require $filePath;
            }

            require $filePath;
        } elseif (!$once && !$force) {
            if ($isReturn) {
                return include $filePath;
            }

            include $filePath;
        }

        return true;
    }
}

if (!function_exists('vv')) {
    /**
     * 检查当前管理员是否允许视图渲染(用于视图)
     * - 如果使用ViewBuilder进行构建，如：增、删、改；ym已经内置视图功能控制。
     * - 如果需要自定义开发视图，并需要加入rbac控制，则需要使用该方法把功能按钮进行包裹。
     * - view.php
     * ```php
     * <?php if ($vv = vv('module/controller-id/action-id')) ?>
     *  <a href="<?= $vv['src'] ?>" target="<?= $vv['dump_way'] ?>"><i class="<?= $vv['icon'] ?>"></i> <?= $vv['label'] ?></a>
     * <?php endif; ?>
     * ```
     * @param string $permissionName 权限
     * @return array|false
     * @throws \Exception
     */
    function vv($permissionName)
    {
        return Yii::$app->rbacManager->checkAccessForViewRender($permissionName);
    }
}

// 包含用户自定义函数文件
include __DIR__ . '/function.php';

