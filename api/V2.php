<?php
// +----------------------------------------------------------------------
// | API模块version 2
// +----------------------------------------------------------------------
// | 日期：2020/8/4
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\api;

class V2 extends \yii\base\Module
{
    /**
     * @var string  The version of this module. Note that the type of this property differs in getter
     */
    public $version = '2.0.0';

    /**
     * @var string|bool the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is `false`, layout will be disabled within this module.
     */
    public $layout = '@api/views/layouts/v2.php';

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\api\controllers\v2';
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
        // custom initialization code goes here
        $this->setViewPath($this->getBasePath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'v2');
    }
}