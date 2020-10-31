<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\admin;

use Yii;

/**
 * 后台管理模块
 * @author cleverstone <yang_hui_lei@163.com>
 * @since 1.0
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\admin\controllers';

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
    public function init()
    {
        parent::init();
        $this->setErrorHandler();
    }

    /**
     * 设置当前模块的错误处理动作
     */
    public function setErrorHandler()
    {
        Yii::$app->errorHandler->errorAction = 'admin/error/error';
    }
}
