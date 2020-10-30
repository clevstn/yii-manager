<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\filters;

use yii\base\Behavior;
use yii\web\Controller;
use yii\base\ActionEvent;
use yii\web\UnauthorizedHttpException;

/**
 * RBAC验证器
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class RbacFilter extends Behavior
{
    /**
     * Declares event handlers for the [[owner]]'s events.
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param ActionEvent $event
     * @return bool
     * @throws UnauthorizedHttpException
     */
    public function beforeAction($event)
    {
        if (!\Yii::$app->adminUser->can($event->action->controller->route)) {
            $event->isValid = false;
            \Yii::$app->getResponse()->getHeaders()->set('X-DENY-ALL', 'No authorization');

            throw new UnauthorizedHttpException('Role-Based policies Access Control deny all.');
        }

        return $event->isValid;
    }
}