<?php

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends CommonController
{
    /**
     * Verbs to specify the actions.
     *
     * @var array
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * Define actions that do not require authorization.
     *
     * @var array
     */
    public $guestActions = ['index'];

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        dd($this->get);
        return $this->render('index');
    }
}
