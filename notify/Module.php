<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\notify;

use Yii;

/**
 * 通知模块
 * @author cleverstone
 * @since ym1.0
 */
class Module extends \yii\base\Module
{
    /**
     * @var string the default route of this module. Defaults to `default`.
     * The route may consist of child module ID, controller ID, and/or action ID.
     * For example, `help`, `post/create`, `admin/post/create`.
     * If action ID is not given, it will take the default value as specified in
     * [[Controller::defaultAction]].
     */
    public $defaultRoute = 'index';

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\notify\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->setErrorHandler();
    }

    /**
     * 设置当前模块的错误处理动作
     */
    public function setErrorHandler()
    {
        Yii::$app->errorHandler->errorAction = 'notify/index/error';
    }
}