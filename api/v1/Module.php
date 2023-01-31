<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\api\v1;

use app\api\Module as BaseModule;

/**
 * 接口1.0.0版本
 * @author cleverstone
 * @since ym1.0
 */
class Module extends BaseModule
{
    /**
     * @var string  The version of this module. Note that the type of this property differs in getter
     */
    public $version = '1.0.0';

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\api\v1\controllers';
}
