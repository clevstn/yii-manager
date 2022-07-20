<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\builder\common;

use yii\base\BaseObject;

/**
 * 设置选项继承类
 * @author HiLi
 * @since 1.0
 */
abstract class BaseOptions extends BaseObject
{
    /**
     * 输出数组
     * @return array
     * @throws \ReflectionException
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