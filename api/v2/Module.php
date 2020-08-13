<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/4
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\api\v2;

use app\api\Module as BaseModule;

/**
 * 接口2.0.0版本
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Module extends BaseModule
{
    /**
     * @var string  The version of this module. Note that the type of this property differs in getter
     */
    public $version = '2.0.0';

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\api\v2\controllers';
}