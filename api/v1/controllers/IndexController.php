<?php
/**
 * @link http://www.cleverstone.cn/
 * @copyright Copyright (c) 2020 黑与白
 * @license http://yii-manager.cleverstone.cn/license/
 */

namespace app\api\v1\controllers;

use app\api\RestController;

/**
 * 默认接口
 *
 * @author cleverstone <yang_hui_lei@163.com>
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
