<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/10/9
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------
namespace app\builder\form;

use yii\helpers\Html;
use app\builder\common\BaseOptions;

/**
 * 表单字段设置选项
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class FieldsOptions extends BaseOptions
{
    // 文本
    const CONTROL_TEXT = 'text';
    // 数字
    const CONTROL_NUMBER = 'number';
    // 密码
    const CONTROL_PASSWORD = 'password';
    // 多选
    const CONTROL_CHECKBOX = 'checkbox';
    // 单选
    const CONTROL_RADIO = 'radio';
    // 日期，格式：Y-m-d H:i:s
    const CONTROL_DATETIME = 'datetime';
    // 日期，格式：Y-m-d
    const CONTROL_DATE = 'date';
    // 年，格式：Y
    const CONTROL_YEAR = 'year';
    // 月，格式：m
    const CONTROL_MONTH = 'month';
    // 时，格式：H:i:s
    const CONTROL_TIME = 'time';
    // 下拉选择
    const CONTROL_SELECT = 'select';
    // 隐藏
    const CONTROL_HIDDEN = 'hidden';
    // 文件
    const CONTROL_FILE = 'file';
    // 文本域
    const CONTROL_TEXTAREA = 'textarea';
    // 富文本
    const CONTROL_RICHTEXT = 'richtext';
    // 自定义
    const CONTROL_CUSTOM = 'custom';

    /**
     * 控件类型，默认`text`
     * @var string
     */
    public $control = self::CONTROL_TEXT;

    /**
     * 标签名
     * @var string
     */
    public $label = '';

    /**
     * 提示语
     * @var string
     */
    public $placeholder = '';

    /**
     * 默认值
     * @var string
     */
    public $default = '';

    /**
     * 选项，用于`radio`、`checkbox`、`select`控件
     * @var array
     */
    public $options;

    /**
     * bootstrap布局，默认`12`
     * @var string
     */
    public $layouts = '12';

    /**
     * 控件样式
     * @var string|array
     */
    public $style = '';

    /**
     * 控件属性
     * @var string|array
     */
    public $attribute = '';

    /**
     * 初始化
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function init()
    {
        if (!empty($this->style) && is_array($this->style)) {
            $this->style = Html::cssStyleFromArray($this->style) ?: '';
        }

        if (!empty($this->attribute) && is_array($this->attribute)) {
            $this->attribute = Html::renderTagAttributes($this->attribute);
        }
    }
}