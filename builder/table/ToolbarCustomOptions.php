<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/9/18
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\table;

use yii\base\BaseObject;

/**
 * 工具栏自定义选项
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class ToolbarCustomOptions extends BaseObject
{
    /**
     * 初始化选项
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function init()
    {

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