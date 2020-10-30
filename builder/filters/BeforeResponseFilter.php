<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\builder\filters;

use yii\web\Response;
use yii\base\Behavior;

/**
 * 响应拦截器
 *
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class BeforeResponseFilter extends Behavior
{
    /**
     * inherit
     *
     * @return array
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function events()
    {
        return [
            Response::EVENT_BEFORE_SEND => 'beforeResponseSend',
        ];
    }

    /**
     * Before send listener
     *
     * @param \yii\base\Event $event
     * @author cleverstone <yang_hui_lei@163.com>
     * @since 1.0
     */
    public function beforeResponseSend($event)
    {
        /* @var Response $owner */
        $owner = $this->owner;
        $owner->headers->set('X-Powered-By', 'ym/1.0');

        return;
    }
}