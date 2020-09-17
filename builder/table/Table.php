<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/17
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\table;

use yii\base\Widget;

/**
 * 表格工具类
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
     * 表格工具栏开始
     * @param array $widgets
     * @return void
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function beginTableTool($widgets)
    {
        static::output($widgets, self::TABLE_TOOL_TOP);
    }

    /**
     * 表格工具栏结束
     * @param array $widgets
     * @return void
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function endTableTool($widgets)
    {
        static::output($widgets, self::TABLE_TOOL_BOTTOM);
    }

    /**
     * 表格分页开始
     * @param array $widgets
     * @return void
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function beginTablePage($widgets)
    {
        static::output($widgets, self::TABLE_PAGE_TOP);
    }

    /**
     * 表格分页结束
     * @param array $widgets
     * @return void
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public static function endTablePage($widgets)
    {
        static::output($widgets, self::TABLE_PAGE_BOTTOM);
    }

    /**
     * 输出超文本
     * @param array $widgets
     * @param int $pos
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
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