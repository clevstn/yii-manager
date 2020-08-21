<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/21
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\behaviors;

use yii\web\Response;
use yii\base\Behavior;

/**
 * 响应前处理器
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class BeforeResponseBehavior extends Behavior
{

    /**
     * inherit
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