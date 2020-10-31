<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\table;

use yii\base\Widget;

/**
 * 表格工具助手
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Table
{
    /**
     * 表格工具栏顶部切点
     */
    const TABLE_TOOL_TOP = 1;

    /**
     * 表格工具栏底部切点
     */
    const TABLE_TOOL_BOTTOM = 2;

    /**
     * 表格分页顶部切点
     */
    const TABLE_PAGE_TOP = 3;

    /**
     * 表格分页底部切点
     */
    const TABLE_PAGE_BOTTOM = 4;

    /**
     * 位于表格脚本上面
     */
    const JS_SCRIPT_TOP = 'script-top';

    /**
     * 位于表格脚本下面
     */
    const JS_SCRIPT_BOTTOM = 'script-bottom';

    /**
     * 位于表格脚本内部
     */
    const JS_SCRIPT_INNER = 'script-inner';

    /**
     * 表格工具栏开始
     * @param array $widgets
     * @return void
     */
    public static function beginTableTool($widgets)
    {
        static::output($widgets, self::TABLE_TOOL_TOP);
    }

    /**
     * 表格工具栏结束
     * @param array $widgets
     * @return void
     */
    public static function endTableTool($widgets)
    {
        static::output($widgets, self::TABLE_TOOL_BOTTOM);
    }

    /**
     * 表格分页开始
     * @param array $widgets
     * @return void
     */
    public static function beginTablePage($widgets)
    {
        static::output($widgets, self::TABLE_PAGE_TOP);
    }

    /**
     * 表格分页结束
     * @param array $widgets
     * @return void
     */
    public static function endTablePage($widgets)
    {
        static::output($widgets, self::TABLE_PAGE_BOTTOM);
    }

    /**
     * 输出超文本
     * @param array $widgets
     * @param int $pos
     */
    protected static function output($widgets, $pos)
    {
        if (!empty($widgets[$pos])) {
            $widgetMap = $widgets[$pos];
            foreach ($widgetMap as $widget) {
                if ($widget instanceof Widget) {
                    echo $widget->run() . "\n";
                } elseif (is_string($widget)) {
                    echo $widget . "\n";
                }
            }
        }

        echo null;
    }
}