<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/4
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder;

use yii\base\Component;
use yii\base\NotSupportedException;

/**
 * 视图构建器
 * @method \app\builder\table\Builder table(array $config = []) static 表格构建器方法
 * @method \app\builder\form\Builder form(array $config = []) static 表单构建器方法
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class ViewBuilder extends Component
{
    /**
     * yii-manager version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * 注册构建器
     *
     * @var array
     * @since 1.0
     */
    public static $builderMap = [
        'table'     => \app\builder\table\Builder::class,    // table
        'form'      => \app\builder\form\Builder::class,     // form
    ];

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws NotSupportedException
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
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