<?php
// +----------------------------------------------------------------------
// | yii-manager version 1.0.0
// +----------------------------------------------------------------------
// | 日期：2020/8/5
// +----------------------------------------------------------------------
// | 作者：cleverstone <yang_hui_lei@163.com>
// +----------------------------------------------------------------------

namespace app\api\v1\controllers;

use app\api\RestController;

/**
 * 默认接口
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
