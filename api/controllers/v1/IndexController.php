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
     * @return array
     */
    public function actionIndex()
    {
        return ['ok' => 1];
    }
}
