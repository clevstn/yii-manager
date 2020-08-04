<?php

namespace app\api\controllers\v1;

use yii\web\Controller;

/**
 * Default controller for the `v1` module
 */
class IndexController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('/v1/index/index');
    }
}
