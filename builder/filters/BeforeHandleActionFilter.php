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
            Application::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    /**
     * Before action listener
     * @param yii\base\ActionEvent $event
     */
    public function beforeAction($event)
    {
        $mid = $event->action->controller->module->id;
        $bindMap = defined('BIND_MODULE') ? BIND_MODULE : null;
        if (!empty($bindMap)) {
            $bindMap = array_filter(explode(',', $bindMap));
            if (!in_array($mid, $bindMap, true)) {
                Yii::$app->getResponse()->redirect('@web/htm/404.html');
                // 结束事件，并跳过剩余的事件处理程序
                $event->handled = true;
                // 停止访问动作
                $event->isValid = false;
            }
        }
    }
}