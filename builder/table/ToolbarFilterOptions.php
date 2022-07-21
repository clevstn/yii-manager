<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\table;

use yii\helpers\Html;
use app\builder\common\BaseOptions;
use app\builder\contract\InvalidInstanceException;

/**
 * 工具栏筛选选项
 * @author cleverstone
 * @since 1.0
 */
class ToolbarFilterOptions extends BaseOptions
{
    const CONTROL_TEXT = 'text';
    const CONTROL_SELECT = 'select';
    const CONTROL_NUMBER = 'number';
    const CONTROL_DATETIME = 'datetime';
    const CONTROL_DATE = 'date';
    const CONTROL_YEAR = 'year';
    const CONTROL_MONTH = 'month';
    const CONTROL_TIME = 'time';
    const CONTROL_CUSTOM = 'custom';

    /**
     * @var array 控件类型
     * - text
     * - select
     * - number
     * - datetime
     * - date
     * - year
     * - month
     * - time
     * - custom
     */
    public $control = 'text';

    /**
     * @var string 标签
     */
    public $label = '';

    /**
     * @var bool 是否是区间, 用于日期控件
     */
    public $range = 0;

    /**
     * @var string 提示
     */
    public $placeholder = '';

    /**
     * @var string 默认值
     */
    public $default = '';

    /**
     * @var string|array 样式
     */
    public $style = '';

    /**
     * @var string|array 属性
     */
    public $attribute = '';

    /**
     * @var array 选项，用于select控件
     */
    public $options = [];

    /**
     * @var CustomControl 用于自定义组件
     */
    public $widget;

    /**
     * 初始化选项
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
            $this->control == 'custom'
            && (
                empty($this->widget) ||
                !($this->widget instanceof CustomControl)
            )
        ) {
            throw new InvalidInstanceException('The widget option must be implements of `CustomControl`. ');
        }
    }
}