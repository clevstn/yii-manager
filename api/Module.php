<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 * 
 */

namespace app\api;

use Yii;

/**
 * 接口模块继承类
 * @author cleverstone
 * @since ym1.0
 */
abstract class Module extends \yii\base\Module
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
     * @var string|bool the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is `false`, layout will be disabled within this module.
     */
    public $layout = 'index';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        // Close session
        Yii::$app->user->enableSession = false;
    }
}