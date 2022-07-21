<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\api\v1;

use app\api\Module as BaseModule;

/**
 * 接口1.0.0版本
 * @author cleverstone
 * @since 1.0
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
