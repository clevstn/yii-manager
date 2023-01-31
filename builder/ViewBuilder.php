<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder;

use yii\base\Component;
use yii\base\NotSupportedException;

/**
 * 视图构建器
 * @method \app\builder\table\Builder table(array $config = []) static 表格构建器方法
 * @method \app\builder\form\Builder form(array $config = []) static 表单构建器方法
 * @author cleverstone
 * @since ym1.0
 */
class ViewBuilder extends Component
{
    /**
     * @var string yii-manager version
     */
    public $version = '1.0.0';

    /**
     * @var array 注册构建器
     */
    public static $builderMap = [
        'table'     => \app\builder\table\Builder::class,    // table
        'form'      => \app\builder\form\Builder::class,     // form
    ];

    /**
     * 构建器映射方法
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws NotSupportedException
     */
    public static function __callStatic($name, $arguments)
    {
        $builderMap = self::$builderMap;
        if (!empty($builderMap[$name])) {
            $builderClass = $builderMap[$name];
            $config = [];
            if (!empty($arguments) && !empty(current($arguments))) {
                $config = current($arguments);
            }

            return new $builderClass($config);
        }

        throw new NotSupportedException("The {$name} builder not supported. ");
    }
}