<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/8
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\builder\filters;

use yii\base\Behavior;
use yii\web\Controller;
use yii\base\ActionEvent;
use yii\web\UnauthorizedHttpException;

/**
 * RBAC验证器
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
            \Yii::$app->getResponse()->getHeaders()->set('x-deny-all', 'No authorization');

            throw new UnauthorizedHttpException('Role-Based policies Access Control deny all.');
        }

        return $event->isValid;
    }
}