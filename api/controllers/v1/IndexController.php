<?php

namespace app\api\controllers\v1;

use app\api\controllers\RestController;

/**
 * Default controller for the `v1` module
 */
class IndexController extends RestController
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
