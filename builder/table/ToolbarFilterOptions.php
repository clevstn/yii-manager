<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/18
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\table;

use yii\helpers\Html;
use yii\base\BaseObject;
use app\builder\contract\InvalidInstanceException;

/**
 * 工具栏筛选字段选项
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class ToolbarFilterOptions extends BaseObject
{
    const CONTROL_TEXT = 'text';
    const CONTROL_SELECT = 'select';
    const CONTROL_NUMBER = 'number';
    const CONTROL_TEXTAREA = 'textarea';
    const CONTROL_RANGE = 'range';
    const CONTROL_CHECKBOX = 'checkbox';
    const CONTROL_RADIO = 'radio';
    const CONTROL_DATETIME = 'datetime';
    const CONTROL_DATE = 'date';
    const CONTROL_TIME = 'time';
    const CONTROL_CUSTOM = 'custom';

    /**
     * 控件类型
     * - text
     * - select
     * - number
     * - textarea
     * - range
     * - checkbox
     * - radio
     * - datetime
     * - date
     * - time
     * - custom
     * @var array
     * @since 1.0
     */
    public $control = 'text';

    /**
     * 提示
     * @var string
     * @since 1.0
     */
    public $placeholder = '';

    /**
     * 默认值
     * @var string
     * @since 1.0
     */
    public $default = '';

    /**
     * 样式
     * @var string|array
     * @since 1.0
     */
    public $style = '';

    /**
     * 属性
     * @var string|array
     * @since 1.0
     */
    public $attribute = '';

    /**
     * 选项 - 用于select控件
     * @var array
     * @since 1.0
     */
    public $options = [];

    /**
     * 用于自定义组件
     * @var CustomControl
     * @since 1.0
     */
    public $widget;

    /**
     * 初始化选项
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

        if (!empty($this->widget) && !($this->widget instanceof CustomControl)) {
            throw new InvalidInstanceException('The widget not instance of interface `CustomControl`. ');
        }
    }

    /**
     * 输出数组
     * @return array
     * @throws \ReflectionException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function toArray()
    {
        $class = new \ReflectionClass($this);

        $attributes = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $attributes[$property->getName()] = $property->getValue($this);
            }
        }

        return $attributes;
    }
}