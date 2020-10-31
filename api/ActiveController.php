<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\api;

use yii\web\ForbiddenHttpException;
use yii\rest\ActiveController as BaseActiveController;
use function Webmozart\Assert\Tests\StaticAnalysis\throws;

/**
 * 资源接口继承类
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class ActiveController extends BaseActiveController
{
    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param object $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        
    }
}