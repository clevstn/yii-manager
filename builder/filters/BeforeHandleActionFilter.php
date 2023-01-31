<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\builder\filters;

use Yii;
use yii\base\Behavior;
use yii\web\Application;

/**
 * 动作拦截器
 * @author cleverstone
 * @since ym1.0
 */
class BeforeHandleActionFilter extends Behavior
{
    /**
     * {@inherit}
     */
    public function events()
    {
        return [
            Application::EVENT_BEFORE_ACTION => 'beforeHandleAction',
        ];
    }

    /**
     * Before action listener
     * @param yii\base\ActionEvent $event
     * @return bool
     */
    public function beforeHandleAction($event)
    {
        $mid = $event->action->controller->module->id;
        $bindMap = defined('BIND_MODULE') ? BIND_MODULE : null;
        if (empty($bindMap)) {
            return $event->isValid;
        }

        $event->isValid = true;

        $bindMap = (array) $bindMap;
        if (!in_array($mid, $bindMap, true)) {
            Yii::$app->getResponse()->redirect('@web/htm/404.html');
            $event->isValid = false;
        }

        return $event->isValid;
    }
}