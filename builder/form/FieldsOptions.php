<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\form;

use yii\helpers\Html;
use app\builder\common\BaseOptions;
use app\builder\contract\InvalidInstanceException;

/**
 * 表单字段设置选项
 * @author cleverstone
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
     * @var string 控件类型，默认`text`
     */
    public $control = self::CONTROL_TEXT;

    /**
     * @var string 标签名
     */
    public $label = '';

    /**
     * @var string 提示语
     */
    public $placeholder = '';

    /**
     * @var string 默认值，多个值用`逗号`隔开
     */
    public $default = '';

    /**
     * @var string 默认外链，多个值用`逗号`隔开，用于文件上传
     */
    public $defaultLink = '';

    /**
     * @var bool 是否必填项
     */
    public $required = true;

    /**
     * @var string 注释语
     */
    public $comment = '';

    /**
     * @var int 是否是区间，用于日期控件
     */
    public $range = 0;

    /**
     * @var array 选项，用于`radio`、`checkbox`、`select`控件，
     * 格式：[`value` => `label`]
     */
    public $options;

    /**
     * @var string 行数，用于文本域
     */
    public $rows = '5';

    /**
     * @var int 文件数量，用于文件上传
     */
    public $number = 1;

    /**
     * @var string 文件上传类型场景
     */
    public $fileScenario = '';

    /**
     * @var string 文件保存目录
     */
    public $saveDirectory = 'common';

    /**
     * @var string 文件路径前缀
     */
    public $pathPrefix = '000000';

    /**
     * @var string bootstrap布局，默认`12`
     */
    public $layouts = '12';

    /**
     * @var string|array 控件样式
     */
    public $style = '';

    /**
     * @var string|array 控件属性
     */
    public $attribute = '';

    /**
     * @var CustomControl|null 自定义项
     */
    public $widget;

    /**
     * 初始化
     * @throws InvalidInstanceException
     */
    public function init()
    {
        if (!empty($this->style) && is_array($this->style)) {
            $this->style = Html::cssStyleFromArray($this->style) ?: '';
        }

        if (!empty($this->attribute) && is_array($this->attribute)) {
            $this->attribute = Html::renderTagAttributes($this->attribute);
        }

        if (
            $this->control == self::CONTROL_CUSTOM
            && (
                empty($this->widget) ||
                !($this->widget instanceof CustomControl)
            )
        ) {
            throw new InvalidInstanceException('The widget option must be implements of `CustomControl`. ');
        }
    }
}