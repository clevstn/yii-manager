<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\notify\controllers;

use app\notify\BaseController;

/**
 * Index
 * @author cleverstone
 * @since ym1.0
 */
class IndexController extends BaseController
{
    /**
     * 默认路由方法
     * @return string
     */
    public function actionIndex()
    {
        return 'Request is variable';
    }

    /**
     * 错误
     * @return string
     */
    public function actionError()
    {
        return 'error';
    }
}