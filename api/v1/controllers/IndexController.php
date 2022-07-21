<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\api\v1\controllers;

use app\api\RestController;

/**
 * 默认接口
 * @author cleverstone
 * @since 1.0
 */
class IndexController extends RestController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'version' => $this->module->version,
        ]);
    }
}
