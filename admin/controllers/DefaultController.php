<?php

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends CommonController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
