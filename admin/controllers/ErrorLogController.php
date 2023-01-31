<?php
/**
 *
 * @copyright Copyright (c) 2020 cleverstone
 *
 */

namespace app\admin\controllers;

use app\builder\common\CommonController;

/**
 * 系统错误日志记录
 * @author cleverstone
 * @since ym1.0
 */
class ErrorLogController extends CommonController
{
    /**
     * {@inheritdoc}
     */
    public $actionVerbs = [
        'index' => ['get'],
    ];

    /**
     * 系统错误日志列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('/index/index');
    }
}