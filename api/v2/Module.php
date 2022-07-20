<?php
/**
 * @link http://www.hili.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.hili.cn/license/
 */

namespace app\api\v2;

use app\api\Module as BaseModule;

/**
 * 接口2.0.0版本
 * @author HiLi
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